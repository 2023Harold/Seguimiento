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
                        <h3 class="card-title text-primary float">Atención de los pliegos de observación</h3>
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
                                       @if (empty($pliegos->fase_revision) || $pliegos->fase_revision!='Pendiente')
                                        <a href="{{ route('pliegosobservacioncontestaciones.index') }}" class="btn btn-light-linkedin popupSinLocation">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                       @else
                                        <a href="{{ route('pliegosobservacioncontestaciones.show',$pliegos) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                       @endif
                                    @else
                                        <a href="{{ route('pliegosobservacioncontestaciones.show',$pliegos) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($pliegos->fase_autorizacion) || $pliegos->fase_autorizacion=='Rechazado'))
                                        @if (empty($pliegos->fase_revision) || $pliegos->fase_revision!='Pendiente')
                                            <a href="{{ route('pliegosobservaciondocumentos.index', $pliegos) }}" class="btn btn-light-linkedin popupSinLocation">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('pliegosobservaciondocumentos.show', $pliegos) }}" class="btn btn btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('pliegosobservaciondocumentos.show', $pliegos) }}" class="btn btn btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($pliegos->fase_autorizacion) || $pliegos->fase_autorizacion=='Rechazado'))
                                        @if (empty($pliegos->fase_revision) || $pliegos->fase_revision!='Pendiente')
                                            <a href="{{ route('pliegosobservacionanalisis.edit',$pliegos) }}" class="btn btn-light-linkedin">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                            <a href="{{ route('pliegosobservacionanalisisenvio.edit',$pliegos) }}" class="btn btn-light-linkedin">
                                                <span class="fa phpdebugbar-fa-send" aria-hidden="true"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('pliegosobservacionanalisis.show',$pliegos) }}" class="btn btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                        @endif
                                    @else
                                        @if (in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())&&!empty($pliegos->fase_revision)&&$pliegos->fase_revision=='Pendiente')
                                            <a href="{{ route('pliegosobservacionanalisisrevision.edit',$pliegos) }}" class="btn btn-light-linkedin">
                                                <span class="fa fa-gavel" aria-hidden="true"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('pliegosobservacionanalisis.show',$pliegos) }}" class="btn btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($pliegos->fase_autorizacion) || $pliegos->fase_autorizacion=='Rechazado'))
                                        <a href="{{ route('pliegosobservacioncalificacion.edit',$pliegos) }}" class="btn btn-primary">
                                            <span class="fas fa-check-circle" aria-hidden="true"></span>&nbsp;/&nbsp;<span class="fas fa-times-circle" aria-hidden="true"></span>
                                        </a>
                                    @else
                                        @if (!empty($pliegos->calificacion_sugerida))
                                            @if ($pliegos->calificacion_atencion=='Atendida')
                                            <span class="badge badge-light-success">Atendida</span><br>
                                            @endif
                                            @if ($pliegos->calificacion_sugerida=='No Atendida')
                                                <span class="badge badge-light-danger">No Atendida</span><br>
                                            @endif
                                            <a href="{{ route('pliegosobservacioncalificacion.show',$pliegos) }}" class="btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ((in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())&&empty($pliegos->fase_autorizacion)) || (in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray()) && $recomendacion->fase_autorizacion=='Rechazado'))
                                    <a href="{{ route('recomendacionescalificacion.edit',$pliegos) }}" class="icon-hover-active">
                                        <span class="fa-solid fa-ranking-star fa-2x"></span>
                                    </a>
                                @else
                                    @if (!empty($pliegos->calificacion_atencion))

                                        <a href="{{ route('recomendacionescalificacion.show',$pliegos) }}" class="btn btn-link btn-color-muted btn-active-color-primary">
                                            @if ($pliegos->calificacion_atencion=='Atendida')
                                                <span class="badge badge-light-success">Atendida</span><br>
                                            @endif
                                            @if ($pliegos->calificacion_atencion=='No Atendida')
                                                <span class="badge badge-light-danger">No Atendida</span><br>
                                            @endif
                                            @if ($pliegos->calificacion_atencion=='Parcialmente Atendida')
                                                <span class="badge badge-light-warning">Parcialmente Atendida</span><br>
                                            @endif
                                            <span class="fa fa-align-justify" aria-hidden="true"></span>
                                        </a>
                                    @endif
                                @endif
                                </td>
                                <td class="text-center">
                                    @if ($pliegos->fase_autorizacion == 'Rechazado')
                                        <span class="badge badge-light-danger">{{ $pliegos->fase_autorizacion }}</span>
                                    @endif
                                    @if ($pliegos->fase_autorizacion == 'En revisión 01')
                                        {{-- @can('pliegosobservacionrevision01.edit') --}}
                                            <a href="{{ route('pliegosobservacionrevision01.edit',$pliegos) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">En revisión</span>
                                        {{-- @endcan --}}
                                    @endif
                                    @if ($pliegos->fase_autorizacion == 'En revisión')
                                        @can('pliegosobservacionrevision.edit')
                                            <a href="{{ route('pliegosobservacionrevision.edit',$pliegos) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $pliegos->fase_autorizacion }} </span>
                                        @endcan
                                    @endif
                                    @if ($pliegos->fase_autorizacion == 'En validación')
                                        @can('pliegosobservacionvalidacion.edit')
                                            <a href="{{ route('pliegosobservacionvalidacion.edit',$pliegos) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Validar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $pliegos->fase_autorizacion }} </span>
                                        @endcan
                                    @endif
                                    @if ($pliegos->fase_autorizacion == 'En autorización')
                                        @can('pliegosobservacionautorizacion.edit')
                                            <a href="{{ route('pliegosobservacionautorizacion.edit',$pliegos) }}" class="btn btn-primary">
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
                                <td class="text-center">
                                    {{ fecha($accion->fecha_termino_recomendacion) }}
                                </td>
                                <td colspan="3" class="text-center table-active">
                                    <a class="btn btn-primary" href="{{ route('pliegosobservacionatencion.create') }}">
                                        Registrar
                                    </a>
                                </td>
                                <td colspan="5" class="text-center table-secondary">
                                    Aun no se encuentra registrada la fecha de compromiso de atención.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <span>
                            <h3 class="card-title text-primary float">Comentarios
                            <a class="btn btn-primary float-end popupcomentario" href="{{ route('revisionespliegosobservacion.create') }}">
                                Agregar
                            </a>
                        </h3>
                        </span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Nombre</th>
                                <th>Comentario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($accion->comentarios as $comentario)
                             <tr>
                                <td class="text-center">
                                    {{ fecha($comentario->created_at,'d/m/Y H:m:s') }}
                                </td>
                                <td>
                                    {{ $comentario->deusuario->name }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('revisionespliegosobservacion.show',$comentario) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                        <span class="fa fa-comment fa-lg" aria-hidden="true"></span>
                                    </a>
                                </td>
                            </tr>
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
