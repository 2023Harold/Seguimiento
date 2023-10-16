@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('pliegosobservacionatencion.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('pliegosobservacionacciones.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;Atención de los pliegos de observación
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._accion')
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="card-title text-primary float">Atención de pliegos de observación</h3>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Oficios de contestación</th>
                                <th>Listado de Doc.</th>
                                <th>Análisis</th>
                                <th>Calificación Sugerida</th>
                                <th>Calificación Definitiva</th>
                                <th>Fase / Constancia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pliegosobservacion as $pliegos)
                            <tr>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($pliegos->fase_autorizacion) || $pliegos->fase_autorizacion=='Rechazado'))
                                       @if (empty($pliegos->fase_revision) || ($pliegos->fase_revision!='Pendiente' && $pliegos->fase_revision!='Revisión Jefe' ))
                                            @can('pliegosobservacionatencioncontestacion.edit')
                                                <a href="{{ route('pliegosobservacionatencioncontestacion.index') }}"  class="icon-hover-active">
                                                    <span class="fa fa-list" aria-hidden="true" ></span>
                                                </a>
                                            @endcan
                                       @else
                                            @can('pliegosobservacionatencioncontestacion.show')
                                                <a href="{{ route('pliegosobservacionatencioncontestacion.show',$pliegos) }}"  class="btn btn-link btn-color-muted btn-active-color-primary">
                                                    <span class="fa fa-list" aria-hidden="true"></span>
                                                </a>
                                            @endcan
                                       @endif
                                    @else
                                        @can('pliegosobservacionatencioncontestacion.show')
                                            <a href="{{ route('pliegosobservacionatencioncontestacion.show',$pliegos) }}"  class="btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                        @endcan
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($pliegos->fase_autorizacion) || $pliegos->fase_autorizacion=='Rechazado'))
                                        @if (empty($pliegos->fase_revision) || $pliegos->fase_revision!='Pendiente'&& $pliegos->fase_revision!='Revisión LP')
                                            @can('pliegosobservaciondocumentos.index')    
                                            <a href="{{ route('pliegosobservaciondocumentos.index', $pliegos) }}" class="icon-hover-active popupSinLocation">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                            @endcan
                                        @else
                                            @can('pliegosobservaciondocumentos.show')
                                            <a href="{{ route('pliegosobservaciondocumentos.index') }}"class="btn btn btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                            @endcan
                                        @endif
                                    @else
                                        @can('pliegosobservaciondocumentos.show')
                                        <a href="{{ route('pliegosobservaciondocumentos.index') }}" class="btn btn btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                        @endcan
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($pliegos->fase_autorizacion) || $pliegos->fase_autorizacion=='Rechazado'))
                                        @if (empty($pliegos->fase_revision) || ($pliegos->fase_revision!='Pendiente'&& $pliegos->fase_revision!='Revisión Jefe'))
                                            @can('pliegosobservacionanalisis.edit')
                                            <a href="{{ route('pliegosobservacionanalisis.edit',$pliegos) }}" class="icon-hover-active">
                                                <span class="fa fa-align-justify"" aria-hidden="true"></span>
                                            </a>
                                            @endcan
                                            &nbsp;|&nbsp;
                                            @can('pliegosobservacionanalisisenvio.edit')
                                            <a href="{{ route('pliegosobservacionanalisisenvio.edit',$pliegos) }}" class="icon-hover-active">
                                                <span class="fa phpdebugbar-fa-send" aria-hidden="true"></span>
                                            </a>
                                            @endcan
                                        @else
                                            @can('pliegosobservacionanalisis.show')
                                            <a href="{{ route('pliegosobservacionanalisis.show',$pliegos) }}" class="btn btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                            @endcan
                                        @endif
                                    @else
                                        @if (in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())&&!empty($pliegos->fase_revision)&&$pliegos->fase_revision=='Pendiente')
                                            @can('pliegosobservacionanalisisrevision.edit')
                                            <a href="{{ route('pliegosobservacionanalisisrevision.edit',$pliegos) }}" class="icon-hover-active">
                                                <span class="fa fa-gavel" aria-hidden="true"></span>
                                            </a>
                                            @endcan
                                        @elseif(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())&&!empty($pliegos->fase_revision)&&$pliegos->fase_revision=='Revisión Jefe')
                                           @can('pliegosobservacionanalisisrevision02.edit')
                                            <a href="{{ route('pliegosobservacionanalisisrevision02.edit',$pliegos) }}" class="icon-hover-active">
                                                <span class="fa fa-gavel" aria-hidden="true"></span>
                                            </a>
                                            @endcan
                                        @else
                                            @can('pliegosobservacionanalisis.show')
                                            <a href="{{ route('pliegosobservacionanalisis.show',$pliegos) }}" class="btn btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                            @endcan
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                        @if (!empty($pliegos->calificacion_sugerida))
                                            @if ($pliegos->calificacion_sugerida=='Solventado')
                                            <span class="badge badge-light-success">Solventado</span><br>
                                            @endif
                                            @if ($pliegos->calificacion_sugerida=='No Solventado')
                                                <span class="badge badge-light-danger">No Solventado</span><br>
                                            @endif
                                            @if ($pliegos->calificacion_sugerida=='Solventado Parcialmente')
                                                <span class="badge badge-light-warning">Solventado Parcialmente</span><br>
                                            @endif                                           
                                        @endif
                                </td>
                                <td class="text-center">
                                @if ((in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())&&empty($pliegos->fase_autorizacion)) || (in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray()) && $pliegos->fase_autorizacion=='Rechazado'))
                                    @can('pliegosatencioncalificacion.edit')
                                    <a href="{{ route('pliegosatencioncalificacion.edit',$pliegos) }}" class="icon-hover-active">
                                        <span class="fa-solid fa-ranking-star fa-2x"></span>
                                    </a>
                                    @endcan
                                @else
                                    @if (!empty($pliegos->calificacion_atencion))
                                        @can('pliegosatencioncalificacion.show')
                                        <a href="{{ route('pliegosatencioncalificacion.show',$pliegos) }}" class="btn btn-link btn-color-muted btn-active-color-primary">
                                            @if ($pliegos->calificacion_atencion=='Solventado')
                                                <span class="badge badge-light-success">Solventado</span><br>
                                            @endif
                                            @if ($pliegos->calificacion_atencion=='No Solventado')
                                                <span class="badge badge-light-danger">No Solventado</span><br>
                                            @endif
                                            @if ($pliegos->calificacion_atencion=='Solventado Parcialmente')
                                                <span class="badge badge-light-warning">Solventado Parcialmente</span><br>
                                            @endif
                                            <span class="fa fa-align-justify" aria-hidden="true"></span>
                                        </a>
                                        @endcan
                                    @endif
                                @endif
                                </td>
                                <td class="text-center">
                                    @if ($pliegos->fase_autorizacion == 'Rechazado')
                                        <span class="badge badge-light-danger">{{ $pliegos->fase_autorizacion }}</span>
                                    @endif                                    
                                    @if ($pliegos->fase_autorizacion == 'En validación')
                                        @can('pliegosatencioncalificacionvalidacion.edit')
                                            <a href="{{ route('pliegosatencioncalificacionvalidacion.edit',$pliegos) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Validar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $pliegos->fase_autorizacion }} </span>
                                        @endcan
                                    @endif
                                    @if ($pliegos->fase_autorizacion == 'En autorización')
                                        @can('pliegosatencioncalificacionautorizacion.edit')
                                            <a href="{{ route('pliegosatencioncalificacionautorizacion.edit',$pliegos) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Autorizar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $pliegos->fase_autorizacion }} </span>
                                        @endcan
                                    @endif
                                    @if ($pliegos->fase_autorizacion=='Autorizado')
                                    <span class="badge badge-light-success">{{ $pliegos->fase_autorizacion }} </span> <br>
                                        @btnFile($pliegos->constancia)
                                        @btnXml($pliegos, 'constancia')
                                    @endif
                                </td>
                            </tr>
                            {!! movimientosDesglose($pliegos->id, 9, $pliegos->movimientos) !!}
                            @empty
                            <tr>
                                <td colspan="9" class="text-center table-secondary">
                                    Aun no se encuentra registrada la atención de pliegos de observacion.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (auth()->user()->siglas_rol!='ANA')
                <div class="row">
                    <div class="col-md-12">                          
                        <span>
                            <h3 class="card-title text-primary float">Comentarios
                            <a class="btn btn-primary float-end popupcomentario" href="{{ route('revisionespliegos.create') }}">
                                Agregar
                            </a>
                        </h3> 
                        </span>                     
                    </div>                    
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Nombre</th>
                                <th>Comentario</th>
                                <th>Estatus</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- {{ dd($accion,$accion->comentariossolicitudes) }} --}}
                            @forelse ($accion->comentariospliegos as $comentario)
                             <tr>
                                <td>
                                    {{ fecha($comentario->created_at,'d/m/Y H:m:s') }}
                                </td>
                                <td>
                                    {{ $comentario->deusuario->name }} <br>
                                    <small class="text-muted">{{ $comentario->deusuario->puesto }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('revisionespliegos.show',$comentario) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                        <span class="fa fa-comment fa-lg" aria-hidden="true"></span>
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if ($comentario->estatus=='Pendiente')
                                        <span class="badge badge-light-primary"> {{ $comentario->estatus }}</span>
                                    @else
                                        <span class="badge badge-light-success">{{ $comentario->estatus }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(auth()->user()->siglas_rol=='ANA'&& $comentario->estatus=='Pendiente')
                                        <a class="btn btn-primary popupcomentario" href="{{ route('revisionespliegos.edit',$comentario) }}">
                                            Atender
                                        </a>
                                    @endif 
                                </td>
                            </tr>
                            @if (count($comentario->respuestas)>0)
                           <tr>
                                <td colspan="5">
                                    <div class="row mb-1">
                                        <div class="col-md-12 list-desglose">
                                            <div class="text-primary pl-4 pt-2 collapsed" data-bs-toggle="collapse" href="#a-listrespuesta-{{$comentario->id}}" aria-expanded="true">
                                                <i class="fa fa-chevron-down fa-chev"></i>Respuesta
                                            </div>
                                        </div>
                                    </div>
                                    <div id="a-listrespuesta-{{$comentario->id}}" class="collapse">                           
                                        <table class="table gray-200">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Nombre</th>
                                                    <th>Comentario</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($comentario->respuestas as $respuesta)
                                                    <tr>
                                                        <td class="text-center">{{ fecha($respuesta->created_at,'d/m/Y H:m:s') }}</td>
                                                        <td>
                                                            {{ $respuesta->deusuario->name }} <br>
                                                            <small class="text-muted">{{ $comentario->deusuario->puesto }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('revisionespliegos.show',$respuesta) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                                                <span class="fa fa-comment fa-lg" aria-hidden="true"></span>
                                                            </a> 
                                                        </td>
                                                    </tr>
                                                @endforeach                                    
                                            </tbody>
                                        </table>                            
                                    </div>
                                </td>
                            </tr>                               
                           @endif 
                           @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    Sin comentarios
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.popupcomentario').colorbox({
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
