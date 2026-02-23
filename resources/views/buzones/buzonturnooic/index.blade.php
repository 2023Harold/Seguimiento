@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('buzonseg.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            {{--}<div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Auditorias
                </h1>
            </div>--}}
            <div class="card-body">
                @include('layouts.partials.Buzones._tabsbuzonseguimiento')
                @include('layouts.partials.Buzones._tabsbuzonseguimientoapartados')
                @include('flash::message')
                {!!BootForm::open(['route'=>'buzonturnooic.index','method'=>'GET']) !!}
                    <div class="row">
                        {!!BootForm::hidden('apartado',"turno_oic") !!}
                        <div class="col-md-2">
                            {!!BootForm::text('numero_auditoria', "No. auditoría:", old('numero_auditoria', $request->numero_auditoria)) !!}
                        </div>
                        <div class="col-md-2">
                            {!!BootForm::text('entidad_fiscalizable', "Entidad fiscalizable:", old('entidad_fiscalizable', $request->entidad_fiscalizable)) !!}
                        </div>
                        <div class="col-md-2">
                            {!!BootForm::text('acto_fiscalizacion', "Acto de fiscalización:", old('acto_fiscalizacion', $request->acto_fiscalizacion)) !!}
                        </div>
                        <div class="col-md-2">
                            {!!BootForm::select('estatus', 'Estatus:', [
                                '' => 'Todos',
                                'Rechazado' => 'Rechazado',
                                'En revisión' => 'En revisión',
                                'En validación' => 'En validación',
                                'En autorización' => 'En autorización',
                                'Autorizado' => 'Autorizado',
                            ], old('estatus', $request->estatus),['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                        </div>
                        <div class="col-md-3 mt-8">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                {!!BootForm::close() !!}
				<div class="row">
					<div class="col-md-12">
						<div class="pagination float-end">
							{{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion, 'departamentoasig'=>$request->departamentoasig, 'liderasig'=>$request->liderasig, 'analistaasig'=>$request->analistaasig, 'direccionaud'=>$request->direccionaud,'estatus'=>$request->estatus,'apartado'=>$request->apartado])->links('vendor.pagination.bootstrap-5') }}
						</div>
					</div>
				</div>	
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Auditoria</th>
                                <th class="text-center ">Turno OIC</th>
                                <th class="text-center "></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                                <tr>
                                    <td class="col-md-2">
                                        @php
                                            $AUD = "AUD-".$auditoria->id;
                                        @endphp
                                        <a href="{{ route('buzonseg.show', $AUD) }}">
                                            -{{ $auditoria->numero_auditoria }}
                                        </a><br>
                                        <small>{{$auditoria->entidad_fiscalizable}}</small>
                                    </td>
                                    @php
                                        $TOIC = "TOIC-".$auditoria->id;
                                    @endphp   
                                    <td>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Fecha </th>
                                                    <th>Número de oficio </th>
                                                    <th>Nombre de titular</th>
                                                    <th>Acuse de envio a notificar</th>
                                                    <th>Fecha de envío a notificar</th>
                                                    <th>Acuse de notificación</th>
                                                    <th>Fecha de notificación</th>
                                                    <th>Fase / Acción </th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($turnooic))
                                                    <tr>
                                                        <td class="text-center">
                                                            {{ fecha($turnooic->fecha_turno_oic) }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{$turnooic->numero_turno_oic }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{$turnooic->nombre_titular_oic }}
                                                        </td>
                                                        <td class="text-center">
                                                            @btnFile($turnooic->turno_oic)
                                                        </td>
                                                        <td class="text-center">
                                                            {{ fecha($turnooic->fecha_envio) }}
                                                        </td>
                                                        <td class="text-center">
                                                            @btnFile($turnooic->acuse_notificacion)
                                                        </td>
                                                        <td class="text-center">
                                                            {{ fecha($turnooic->fecha_notificacion) }}
                                                        </td>

                                                        <td class="text-center">
                                                            @if (empty($auditoria->turnooic->fase_autorizacion)||$auditoria->turnooic->fase_autorizacion=='Rechazado')
                                                                <span class="badge badge-light-danger">{{ $auditoria->turnooic->fase_autorizacion }} </span>
                                                            @endif
                                                            @if ($auditoria->turnooic->fase_autorizacion == 'En revisión')
                                                                <span class="badge badge-light-warning">{{ $auditoria->turnooic->fase_autorizacion }} </span>
                                                            @endif
                                                            @if ($auditoria->turnooic->fase_autorizacion == 'En validación')
                                                                <span class="badge badge-light-warning">{{ $auditoria->turnooic->fase_autorizacion }} </span>
                                                            @endif
                                                            @if ($auditoria->turnooic->fase_autorizacion == 'En autorización')
                                                                <span class="badge badge-light-warning">{{ $auditoria->turnooic->fase_autorizacion }} </span>
                                                            @endif
                                                            @if ($auditoria->turnooic->fase_autorizacion=='Autorizado')
                                                                <span class="badge badge-light-success">{{ $auditoria->turnooic->fase_autorizacion }} </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if (!empty($auditoria->turnooic))
                                                        {!! movimientosDesglose($auditoria->turnooic->id, 10, $auditoria->turnooic->movimientos) !!}
                                                    @endif
                                                @else
                                                    <tr>
                                                        <td class="text-center" colspan="11">
                                                            No se encuentran registros en este apartado.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </td>  
                                    <td class="text-center">
                                        <a href="{{ route('buzonseg.show', $TOIC) }}" class="corner-button">
                                            <span class="cb-content">Turno OIC<i class="bi bi-arrow-up-right-circle-fill text-primary fs-1" aria-hidden="true"></i></span>
                                        </a>
                                    </td>               
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="11">
                                        <span class='text-center'>No hay registros en éste apartado</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination float-end">
					{{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion, 'departamentoasig'=>$request->departamentoasig, 'liderasig'=>$request->liderasig, 'analistaasig'=>$request->analistaasig, 'direccionaud'=>$request->direccionaud,'estatus'=>$request->estatus,'apartado'=>$request->apartado])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
</script>
@endsection