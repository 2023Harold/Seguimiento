@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('seguimientoauditorias') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Auditorias
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                {!! BootForm::open(['route'=>'seguimientoauditoria.index','method'=>'GET']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            {!! BootForm::text('numero_auditoria', "No. auditoría:", old('numero_auditoria', $request->numero_auditoria)) !!}
                        </div>
                        <div class="col-md-2">
                            {!! BootForm::text('entidad_fiscalizable', "Entidad fiscalizable:", old('entidad_fiscalizable', $request->entidad_fiscalizable)) !!}
                        </div>
                        <div class="col-md-2">
                            {!! BootForm::text('acto_fiscalizacion', "Acto de fiscalización:", old('acto_fiscalizacion', $request->acto_fiscalizacion)) !!}
                        </div>
                        <div class="col-md-6 mt-8">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                {!! BootForm::close() !!}
				<div class="row">
					<div class="col-md-12">
						<div class="pagination float-end">
							{{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5') }}
						</div>
					</div>
				</div>
                @can('seguimientoauditoria.create')
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn btn-primary float-end" href="{{ route('seguimientoauditoria.create') }}">
                                Agregar auditoria
                            </a>
                        </div>
                    </div>
                @endcan
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. de auditoría</th>
                                <th>No. de orden de auditoría</th>
                                <th>Entidad fiscalizable</th>
                                <th>Acto de fiscalización</th>
                                <th>Informe de auditoría</th>
                                <th>Número de fojas</th>
                                <th>Acciones promovidas</th>
                                <th>Monto por aclarar</th>
                                <th>Fase</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                                <tr>
                                    <td>
                                        {{ $auditoria->numero_auditoria }}
                                    </td>
                                    <td>
                                        {{ $auditoria->numero_orden }}
                                    </td>
                                    <td  width='40%'>
                                        @php
                                            $entidadparciales = explode("-", $auditoria->entidad_fiscalizable);
                                        @endphp
                                        @foreach ($entidadparciales as $entidadparcial)
                                            {{ mb_convert_encoding(mb_convert_case(strtolower($entidadparcial), MB_CASE_TITLE), "UTF-8"); }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $auditoria->acto_fiscalizacion }}
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($auditoria->informe_auditoria))
											@php
												$archivo=str_replace('storage/','',$auditoria->informe_auditoria);
											@endphp
											{{-- @btnFileMinio($archivo) --}}

                                        @endif
                                    </td>
                                    <td>
                                        {{ $auditoria->fojas_utiles}}
                                    </td>
                                    <td class="text-center">
                                        @if(!empty($auditoria->fase_autorizacion) && auth()->user()->siglas_rol!='ANA')
                                            <a href="{{ route('seguimientoauditoria.accionesconsulta', $auditoria) }}" class="btn btn-secondary">Consultar</a>
                                        @endif
                                        @if(!empty($auditoria->fase_autorizacion) && $auditoria->fase_autorizacion!='Rechazado' && auth()->user()->siglas_rol=='ANA')
                                            <a href="{{ route('seguimientoauditoria.accionesconsulta', $auditoria) }}" class="btn btn-secondary">Consultar</a>
                                        @endif
                                    </td>
                                    <td style="text-align: right!important;">
                                        {{ '$'.number_format( $auditoria->total(), 2) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($auditoria->fase_autorizacion == 'En revisión 01')
                                            <span class="badge badge-light-warning">En revisión</span>
                                        @elseif($auditoria->fase_autorizacion == 'En revisión')
                                            <span class="badge badge-light-warning">{{ $auditoria->fase_autorizacion }}</span>
                                        @elseif($auditoria->fase_autorizacion == 'En validación')
                                            <span class="badge badge-light-warning">{{ $auditoria->fase_autorizacion }}</span>
                                        @elseif($auditoria->fase_autorizacion == 'En autorización')
                                            <span class="badge badge-light-warning">{{ $auditoria->fase_autorizacion }}</span>
                                        @elseif($auditoria->fase_autorizacion == 'Rechazado')
                                            <span class="badge badge-light-danger">{{ $auditoria->fase_autorizacion }}</span>
                                        @elseif($auditoria->fase_autorizacion == 'Autorizado')
                                            <span class="badge badge-light-success">{{ $auditoria->fase_autorizacion }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
										 @if ($auditoria->numero_auditoria=='AD-098')
                                            @can('seguimientoauditoria.edit')
                                                <a href="{{ route('seguimientoauditoria.edit',$auditoria) }}">
                                                    <i class="align-middle fas fa-edit text-primary" aria-hidden="true"></i>
                                                </a>
                                            @endcan
                                        @endif
                                        @if (empty($auditoria->fase_autorizacion)||$auditoria->fase_autorizacion=='Rechazado')
                                            @can('seguimientoauditoria.edit')
                                                <a href="{{ route('seguimientoauditoria.edit',$auditoria) }}">
                                                    <i class="align-middle fas fa-edit text-primary" aria-hidden="true"></i>
                                                </a>
                                            @endcan
                                        @endif
                                        @if ($auditoria->fase_autorizacion == 'En revisión 01')
                                            @can('seguimientoauditoriarevision01.edit')
                                                <a href="{{ route('seguimientoauditoriarevision01.edit',$auditoria) }}" class="btn btn-primary">
                                                    Revisar
                                                </a>
                                            @endcan
                                        @endif
                                        @if ($auditoria->fase_autorizacion == 'En revisión')
                                            @can('seguimientoauditoriarevision.edit')
                                                <a href="{{ route('seguimientoauditoriarevision.edit',$auditoria) }}" class="btn btn-primary">
                                                    Revisar
                                                </a>
                                            @endcan
                                        @endif
                                        @if ($auditoria->fase_autorizacion == 'En validación')
                                            @can('seguimientoauditoriavalidacion.edit')
                                                <a href="{{ route('seguimientoauditoriavalidacion.edit',$auditoria) }}" class="btn btn-primary popuprevisar">
                                                    Validar
                                                </a>
                                            @endcan
                                        @endif
                                        @if ($auditoria->fase_autorizacion == 'En autorización')
                                            @can('seguimientoauditoriaautorizacion.edit')
                                                <a href="{{ route('seguimientoauditoriaautorizacion.edit',$auditoria) }}" class="btn btn-primary popuprevisar">
                                                    Autorizar
                                                </a>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                                    {!! movimientosDesglose($auditoria->id, 10, $auditoria->movimientos) !!}
                            @empty
                                <tr>
                                    <td class="text-center" colspan="8">
                                        <span class='text-center'>No hay registros en éste apartado</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination float-end">
                    {{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\AprobarFlujoAutorizacionRequest') !!}
    <script>
        $(document).ready(function() {
            $('.popuprevisar').colorbox({
                width:"65%",
                height:"650px",
                maxWidth:400,
                maxHeight:"650px",
                iframe: true,
                onClosed: function() {
                    location.reload(true);
                },
                onComplete: function () {
                 $(this).colorbox.resize({width:"65%",maxWidth:400, height:"650px", maxHeight:"650px"});
                 $(window).trigger("resize");
                }
            });
        });
    </script>
@endsection

