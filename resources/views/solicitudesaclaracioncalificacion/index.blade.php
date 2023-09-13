@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('solicitudesaclaracioncalificacion.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('solicitudesaclaracionacciones.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;Calificación de la solicitud de aclaración 
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._accion')
                <h3 class="card-title text-primary">Atención de la recomendación</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>                                
                                <th>Oficio de la contestación de la solicitud de aclaración</th>
                                <th>Listado de Doc.</th>
                                <th>Calificación</th>
                                <th>Monto solventado</th>
                                <th>Fase / Constancia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($solicitudes as $solicitud)
                            <tr>                                
                                <td class="text-center">                             
                                    <a href="{{ asset($solicitud->oficio_atencion) }}" target="_blank">
                                        <?php echo htmlspecialchars_decode(iconoArchivo($solicitud->oficio_atencion)) ?>
                                    </a> <br>
                                    <small>{{ fecha($solicitud->fecha_oficio_atencion) }}</small><br>
                                {{-- @can('solicitudesaclaracionacciones.edit') --}}
                                    @if ($solicitud->concluido=='No')
                                        <a href="{{ route('solicitudesaclaracionacciones.edit',$accion) }}" class="btn btn-secondary popupcontestacion">
                                            <i class="align-middle fas fa-pencil" aria-hidden="true"></i>Editar 
                                        </a>
                                    @endif                                 
                                </td>
                                <td class="text-center">                             
                                    <a href="{{ route('solicitudesaclaracioncalificacion.show', $solicitud) }}" class="btn btn-secondary popupSinLocation">
                                        <span class="fa fa-list" aria-hidden="true"></span>
                                    </a>                                   
                                </td>
                                <td class="text-center">
                                    @if ($solicitud->cumple=='Atendida')
                                        <span class="badge badge-light-success">Atendida</span>
                                    @endif
                                    @if ($solicitud->cumple=='No Atendida')
                                        <span class="badge badge-light-danger">No Atendida</span>
                                    @endif
                                    @if ($solicitud->cumple=='Parcialmente Atendida')
                                        <span class="badge badge-light-warning">Parcialmente Atendida</span>
                                    @endif
                                </td>
                                <td style="text-align: right!important;">
                                    {{ '$'.number_format( $solicitud->monto_solventado, 2) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light-info">{{ $solicitud->fase_autorizacion }} </span><br>                                                 
                                    @if (empty($solicitud->fase_autorizacion)||$solicitud->fase_autorizacion=='Rechazado')   
                                        <span class="badge badge-light-danger">{{ $solicitud->fase_autorizacion }} </span><br>
                                            {{-- @can('recomendacionesatencion.edit') --}}
                                                <a href="{{ route('solicitudesaclaracioncalificacion.edit',$solicitud) }}" class="btn btn-primary">
                                                    <span class="fas fa-edit text-primar" aria-hidden="true"></span>&nbsp; Editar
                                                </a>  
                                            {{-- @endcan --}}
                                    @endif 
                                    @if ($solicitud->fase_autorizacion == 'En revisión 01')                                                
                                        {{-- @can('solicitudesaclaracionrevision01.edit') --}}
                                            <a href="{{ route('solicitudesaclaracionrevision01.edit',$solicitud) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        {{-- @else
                                            <span class="badge badge-light-warning">En revisión</span>
                                        @endcan                                                --}}
                                    @endif
                                    @if ($solicitud->fase_autorizacion == 'En revisión')                                                
                                        {{-- @can('recomendacionesrevision.edit') --}}
                                            <a href="{{ route('solicitudesaclaracionrevision.edit',$solicitud) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        {{-- @else
                                            <span class="badge badge-light-warning">{{ $recomendacion->fase_autorizacion }} </span>
                                        @endcan                                                --}}
                                    @endif
                                   @if ($solicitud->fase_autorizacion == 'En validación')                                                
                                        {{-- @can('recomendacionesvalidacion.edit') --}}
                                            <a href="{{ route('solicitudesaclaracionvalidacion.edit',$solicitud) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Validar
                                            </a>
                                        {{-- @else
                                            <span class="badge badge-light-warning">{{ $recomendacion->fase_autorizacion }} </span>
                                        @endcan                                                --}}
                                    @endif
                                     {{--@if ($recomendacion->fase_autorizacion == 'En autorización')                                                
                                        @can('recomendacionesautorizacion.edit')
                                            <a href="{{ route('recomendacionesautorizacion.edit',$recomendacion) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Autorizar
                                            </a> 
                                        @else
                                            <span class="badge badge-light-warning">{{ $recomendacion->fase_autorizacion }} </span>                                           
                                        @endcan
                                    @endif                                     
                                    @if ($recomendacion->fase_autorizacion=='Autorizado')
                                    <span class="badge badge-light-success">{{ $recomendacion->fase_autorizacion }} </span> <br>
                                        @btnFile($recomendacion->constancia)
                                        @btnXml($recomendacion, 'constancia')
                                    @endif --}}
                                </td>
                            </tr>
                            {!! movimientosDesglose($solicitud->id, 5, $solicitud->movimientos) !!}
                            @empty
                            <td colspan="6" class="text-center">
                                No se encontraron registros en este apartado.
                            </td>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection