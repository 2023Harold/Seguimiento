@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('solicitudesaclaracionatencion.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('solicitudesaclaracionacciones.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;Atención de las solicitudes de aclaración
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._accion')
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="card-title text-primary float">Atención de las solicitudes de aclaración</h3>
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
                            @forelse ($solicitudesaclaracion as $solicitud)
                            <tr>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($solicitud->fase_autorizacion) || $solicitud->fase_autorizacion=='Rechazado'))
                                       @if (empty($solicitud->fase_revision) || $solicitud->fase_revision!='Pendiente')
                                        <a href="{{ route('solicitudesaclaracioncontestacion.index') }}" class="btn btn-light-linkedin popupSinLocation">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                       @else
                                        <a href="{{ route('solicitudesaclaracioncontestacion.show',$solicitud) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                       @endif
                                    @else
                                        <a href="{{ route('solicitudesaclaracioncontestacion.show',$solicitud) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($solicitud->fase_autorizacion) || $solicitud->fase_autorizacion=='Rechazado'))
                                        @if (empty($solicitud->fase_revision) || $solicitud->fase_revision!='Pendiente')
                                            <a href="{{ route('solicitudesaclaraciondocumentos.index', $solicitud) }}" class="btn btn-light-linkedin popupSinLocation">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('solicitudaclaraciondocumentos.show', $solicitud) }}" class="btn btn btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('solicitudesaclaraciondocumentos.show', $solicitud) }}" class="btn btn btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($solicitud->fase_autorizacion) || $solicitud->fase_autorizacion=='Rechazado'))
                                        @if (empty($solicitud->fase_revision) || $solicitud->fase_revision!='Pendiente')
                                            <a href="{{ route('solicitudesaclaracionanalisis.edit',$solicitud) }}" class="btn btn-light-linkedin">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                            <a href="{{ route('solicitudesaclanalisisenvio.edit',$solicitud) }}" class="btn btn-light-linkedin">
                                                <span class="fa phpdebugbar-fa-send" aria-hidden="true"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('solicitudesaclaracionanalisis.show',$solicitud) }}" class="btn btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                        @endif
                                    @else
                                        @if (in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())&&!empty($solicitud->fase_revision)&&$solicitud->fase_revision=='Pendiente')
                                            <a href="{{ route('solicitudesaclaracionanalisisrevision.edit',$solicitud) }}" class="btn btn-light-linkedin">
                                                <span class="fa fa-gavel" aria-hidden="true"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('solicitudesaclaracionanalisis.show',$solicitud) }}" class="btn btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($solicitud->fase_autorizacion) || $solicitud->fase_autorizacion=='Rechazado'))
                                        <a href="{{ route('solicitudesaclaracioncalificacion.edit',$solicitud) }}" class="btn btn-primary">
                                            <span class="fas fa-check-circle" aria-hidden="true"></span>&nbsp;/&nbsp;<span class="fas fa-times-circle" aria-hidden="true"></span>
                                        </a>
                                    @else
                                        @if (!empty($solicitud->calificacion_sugerida))
                                            @if ($solicitud->calificacion_atencion=='Atendida')
                                            <span class="badge badge-light-success">Atendida</span><br>
                                            @endif
                                            @if ($solicitud->calificacion_sugerida=='No Atendida')
                                                <span class="badge badge-light-danger">No Atendida</span><br>
                                            @endif
                                            <a href="{{ route('solicitudesaclaracioncalificacion.show',$solicitud) }}" class="btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ((in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())&&empty($solicitud->fase_autorizacion)) || (in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray()) && $recomendacion->fase_autorizacion=='Rechazado'))
                                    <a href="{{ route('solicitudesaclaracioncalificacion.edit',$solicitud) }}" class="icon-hover-active">
                                        <span class="fa-solid fa-ranking-star fa-2x"></span>
                                    </a>
                                @else
                                    @if (!empty($solicitud->calificacion_atencion))

                                        <a href="{{ route('solicitudesaclaracionescalificacion.show',$solicitud) }}" class="btn btn-link btn-color-muted btn-active-color-primary">
                                            @if ($solicitud->calificacion_atencion=='Atendida')
                                                <span class="badge badge-light-success">Atendida</span><br>
                                            @endif
                                            @if ($solicitud->calificacion_atencion=='No Atendida')
                                                <span class="badge badge-light-danger">No Atendida</span><br>
                                            @endif
                                            @if ($solicitud->calificacion_atencion=='Parcialmente Atendida')
                                                <span class="badge badge-light-warning">Parcialmente Atendida</span><br>
                                            @endif
                                            <span class="fa fa-align-justify" aria-hidden="true"></span>
                                        </a>
                                    @endif
                                @endif
                                </td>
                                <td class="text-center">
                                    @if ($solicitud->fase_autorizacion == 'Rechazado')
                                        <span class="badge badge-light-danger">{{ $solicitud->fase_autorizacion }}</span>
                                    @endif
                                    @if ($solicitud->fase_autorizacion == 'En revisión 01')
                                        {{-- @can('pliegosobservacionrevision01.edit') --}}
                                            <a href="{{ route('solicitudesaclaracionrevision01.edit',$solicitud) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">En revisión</span>
                                        {{-- @endcan --}}
                                    @endif
                                    @if ($solicitud->fase_autorizacion == 'En revisión')
                                        @can('solicitudesaclaracionrevision.edit')
                                            <a href="{{ route('solicitudesaclaracionrevision.edit',$solicitud) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $solicitud->fase_autorizacion }} </span>
                                        @endcan
                                    @endif
                                    @if ($solicitud->fase_autorizacion == 'En validación')
                                        @can('solicitudesaclaracionvalidacion.edit')
                                            <a href="{{ route('solicitudesaclaracionvalidacion.edit',$solicitud) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Validar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $solicitud->fase_autorizacion }} </span>
                                        @endcan
                                    @endif
                                    @if ($solicitud->fase_autorizacion == 'En autorización')
                                        @can('solicitudesaclaracionautorizacion.edit')
                                            <a href="{{ route('solicitudesaclaracionautorizacion.edit',$solicitud) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Autorizar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $solicitud->fase_autorizacion }} </span>
                                        @endcan
                                    @endif
                                    @if ($solicitud->fase_autorizacion=='Autorizado')
                                    <span class="badge badge-light-success">{{ $solicitud->fase_autorizacion }} </span> <br>
                                        @btnFile($solicitud->constancia)
                                        @btnXml($solicitud, 'constancia')
                                    @endif
                                </td>
                            </tr>
                            {!! movimientosDesglose($solicitud->id, 9, $solicitud->movimientos) !!}
                            @empty
                            <tr>
                                <td class="text-center">
                                    {{ fecha($accion->fecha_termino_recomendacion) }}
                                </td>
                                <td colspan="3" class="text-center table-active">
                                    <a class="btn btn-primary" href="{{ route('solicitudesaclaracionatencion.create') }}">
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
                                    <a href="{{ route('revisionessolicitudesaclaracion.show',$comentario) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
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
