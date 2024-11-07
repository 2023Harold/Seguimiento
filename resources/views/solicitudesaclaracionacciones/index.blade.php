@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('solicitudesaclaracionacciones.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('auditoriaseguimiento.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Solicitudes de aclaración
                </h1>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')
                {!! BootForm::open(['route'=>'solicitudesaclaracionacciones.index','method'=>'GET']) !!}
                <div class="row">
                    <div class="col-md-2">
                        {!! BootForm::text('numero_accion', "No. acción:", old('numero_accion',
                        $request->numero_accion)) !!}
                    </div>
                    <div class="col-md-6 mt-8">
                        <button type="submit" class="btn btn-primary"><i class="align-middle fas fa-search"
                                aria-hidden="true"></i> Buscar</button>
                    </div>
                </div>
                {!! BootForm::close() !!}
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. consecutivo</th>
                                <th>No. de acción</th>
                                <th>Tipo de acción</th>
                                <th>Monto por aclarar</th>
								<th>Fase</th>
                                <th>Datos de atención</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($acciones as $accion)
                            <tr>
                                <td class="text-center">
                                    {{$loop->iteration}}
                                </td>
                                <td>
                                    {{ $accion->numero }}
                                </td>
                                <td>
                                    {{ $accion->tipo}}
                                </td>
                                <td style="text-align: right!important;">
                                    {{'$'.number_format( $accion->monto_aclarar, 2)}}
                                </td>
								<td class="text-center">
									@if (empty($accion->solicitudesaclaracion->fase_autorizacion))
                                        <span class="badge badge-light-warning">Pendiente</span>                                 
                                    @elseif ($accion->solicitudesaclaracion->fase_autorizacion == 'En revisión 01')                                        
                                        <span class="badge badge-light-warning">En revisión</span>
                                    @elseif ($accion->solicitudesaclaracion->fase_autorizacion == 'En revisión')
                                        <span class="badge badge-light-warning">{{ $accion->solicitudesaclaracion->fase_autorizacion }} </span>
                                    @elseif ($accion->solicitudesaclaracion->fase_autorizacion == 'En validación')
                                        <span class="badge badge-light-warning">{{ $accion->solicitudesaclaracion->fase_autorizacion }} </span>
                                    @elseif ($accion->solicitudesaclaracion->fase_autorizacion == 'En autorización')
										<span class="badge badge-light-warning">{{ $accion->solicitudesaclaracion->fase_autorizacion }} </span>
                                    @elseif ($accion->solicitudesaclaracion->fase_autorizacion=='Autorizado')
										<span class="badge badge-light-success">{{ $accion->solicitudesaclaracion->fase_autorizacion }} </span>                                         
                                    @endif 
								</td>
                                <td class="text-center">
                                    @if (!empty($accion->auditoria->comparecencia)&&!empty($accion->auditoria->comparecencia->oficio_acta))
                                        @can('solicitudesaclaracionacciones.edit')
                                            <a href="{{ route('solicitudesaclaracionacciones.edit',$accion) }}" class="btn btn-primary">
                                                <i class="align-middle fa fa-file-circle-plus" aria-hidden="true"></i> Ingresar
                                            </a>
                                        @endcan  
                                    @endif                                  
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="5">
                                    No se encuentran registros en este apartado.
                                </td>
                            </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{
                    $acciones->appends(['numero_accion'=>$request->numero_accion])->links('vendor.pagination.bootstrap-5')
                    }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
