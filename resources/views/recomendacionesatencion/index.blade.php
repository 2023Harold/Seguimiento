@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('recomendacionesatencion.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('recomendacionesacciones.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;Atención de la recomendación
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._accionrecomendacion')
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="card-title text-primary float">Atención de la recomendación</h3>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha compromiso de atención</th>
                                <th>Nombre del responsable de la entidad fiscalizada</th>
                                <th>Cargo del responsable</th>
                                <th>Oficios de contestación</th>
                                <th>Listado de Doc.</th>
                                <th>Análisis</th>
                                <th>Calificación</th>
                                <th>Fase / Constancia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recomendaciones as $recomendacion)
                            <tr>
                                <td class="text-center">
                                    {{ fecha($accion->fecha_termino_recomendacion) }}
                                </td>
								<td>
                                    {{ $recomendacion->auditoria->comparecencia->nombre_titular }}
                                </td>
                                <td>
                                    {{ $recomendacion->auditoria->comparecencia->cargo_titular }}
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($recomendacion->fase_autorizacion) || $recomendacion->fase_autorizacion=='Rechazado'))

                                        @if (empty($recomendacion->fase_revision) || ($recomendacion->fase_revision!='Pendiente'&& $recomendacion->fase_revision!='Revisión Jefe'))
                                        <a href="{{ route('recomendacionescontestaciones.index') }}" class="btn btn-light-primary">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                       @else
                                        <a href="{{ route('recomendacionescontestaciones.show',$recomendacion) }}" class="btn btn-link btn-color-muted btn-active-color-primary">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                       @endif
                                    @else
                                        <a href="{{ route('recomendacionescontestaciones.show',$recomendacion) }}" class="btn btn-link btn-color-muted btn-active-color-primary">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($recomendacion->fase_autorizacion) || $recomendacion->fase_autorizacion=='Rechazado'))
                                        @if (empty($recomendacion->fase_revision) || ($recomendacion->fase_revision!='Pendiente'&& $recomendacion->fase_revision!='Revisión Jefe'))
                                            <a href="{{ route('recomendacionesdocumentos.edit', $recomendacion) }}" class="btn btn-light-primary">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('recomendacionesdocumentos.show', $recomendacion) }}" class="btn btn btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('recomendacionesdocumentos.show', $recomendacion) }}" class="btn btn btn btn-link btn-color-muted btn-active-color-primary">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($recomendacion->fase_autorizacion) || $recomendacion->fase_autorizacion=='Rechazado'))
                                        @if (empty($recomendacion->fase_revision) || ($recomendacion->fase_revision!='Pendiente'&& $recomendacion->fase_revision!='Revisión Jefe'))
                                            <a href="{{ route('recomendacionesanalisis.edit',$recomendacion) }}" class="btn btn-light-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                        @else
                                            <a href="{{ route('recomendacionesanalisis.show',$recomendacion) }}" class="btn btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('recomendacionesanalisis.show',$recomendacion) }}" class="btn btn btn-link btn-color-muted btn-active-color-primary">
                                            <span class="fa fa-align-justify" aria-hidden="true"></span>
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (!empty($recomendacion->calificacion_atencion))
                                        <a href="{{ route('recomendacionescalificacion.show',$recomendacion) }}" class="btn btn-link btn-color-muted btn-active-color-primary">
                                            @if ($recomendacion->calificacion_atencion=='Atendida')
                                                <span class="badge badge-light-success">Atendida</span><br>
                                            @endif
                                            @if ($recomendacion->calificacion_atencion=='No Atendida')
                                                <span class="badge badge-light-danger">No Atendida</span><br>
                                            @endif
                                            @if ($recomendacion->calificacion_atencion=='Parcialmente Atendida')
                                                <span class="badge badge-light-warning">Parcialmente Atendida</span><br>
                                            @endif
                                            <span class="fa fa-align-justify" aria-hidden="true"></span>
                                        </a>
                                    @else
                                        @if (!empty($recomendacion->calificacion_sugerida))
                                            @if ($recomendacion->calificacion_sugerida=='Atendida')
                                                <span class="badge badge-light-success">Atendida</span><br>
                                            @endif
                                            @if ($recomendacion->calificacion_sugerida=='No Atendida')
                                                <span class="badge badge-light-danger">No Atendida</span><br>
                                            @endif
                                            @if ($recomendacion->calificacion_sugerida=='Parcialmente Atendida')
                                                <span class="badge badge-light-warning">Parcialmente Atendida</span><br>
                                            @endif
                                        @endif
                                    @endif

                                </td>
                                <td class="text-center">                                   
                                    @if (empty($recomendacion->fase_autorizacion))
                                        @can('recomendacionesanalisisenvio.edit')
                                            <a href="{{ route('recomendacionesanalisisenvio.edit',$recomendacion) }}" class="btn btn-primary">
                                                <span class="fa phpdebugbar-fa-send" aria-hidden="true"></span> Enviar
                                            </a>
                                        @endcan                                        
                                    @endif
                                    @if ($recomendacion->fase_autorizacion == 'Rechazado')
                                        <span class="badge badge-light-danger">{{ $recomendacion->fase_autorizacion }}</span>
                                        @if (auth()->user()->siglas_rol=='ANA')
                                            <a href="{{ route('recomendacionesanalisisenvio.edit',$recomendacion) }}" class="btn btn-primary">
                                                <span class="fa phpdebugbar-fa-send" aria-hidden="true"></span> Enviar
                                            </a>
                                        @endif
                                    @endif
                                    @if ($recomendacion->fase_autorizacion == 'En revisión 01')
                                        @can('recomendacionesrevision01.edit')
                                            <a href="{{ route('recomendacionesrevision01.edit',$recomendacion) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">En revisión</span>
                                        @endcan
                                    @endif
                                    @if ($recomendacion->fase_autorizacion == 'En revisión')
                                        @can('recomendacionesrevision.edit')
                                            <a href="{{ route('recomendacionesrevision.edit',$recomendacion) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $recomendacion->fase_autorizacion }} </span>
                                        @endcan
                                    @endif
                                    @if ($recomendacion->fase_autorizacion == 'En validación')
                                        @can('recomendacionesvalidacion.edit')
                                            <a href="{{ route('recomendacionesvalidacion.edit',$recomendacion) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Validar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $recomendacion->fase_autorizacion }} </span>
                                        @endcan
                                    @endif
                                    @if ($recomendacion->fase_autorizacion == 'En autorización')
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
                                    <span class="badge badge-light-success">{{ $recomendacion->fase_autorizacion }} </span> 
                                        {{-- <br>
                                        @btnFile($recomendacion->constancia_autorizacion)                                        --}}
                                    @endif                                                                     
                                </td>
                            </tr>
                            {!! movimientosDesglose($recomendacion->id, 9, $recomendacion->movimientos) !!}
                            @empty
                            <tr>
                                <td colspan="9" class="text-center table-secondary">
                                    Aun no se encuentra registrada la atención de la recomendación.
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
                            {{-- <a class="btn btn-primary float-end popupcomentario" href="{{ route('revisionesrecomendaciones.create') }}">
                                Agregar
                            </a> --}}
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
                            @forelse ($accion->comentarios as $comentario)
                           <tr>
                                <td class="text-center">
                                    {{ fecha($comentario->created_at,'d/m/Y H:m:s') }}
                                </td>
                                <td>
                                    {{ $comentario->deusuario->name }}
                                    <br>
                                    <small class="text-muted">{{ $comentario->deusuario->puesto }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('revisionesrecomendaciones.show',$comentario) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupcomentario">
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
                                    {{-- @if(auth()->user()->siglas_rol=='ANA'&& $comentario->estatus=='Pendiente')
                                        <a class="btn btn-primary popupcomentario" href="{{ route('revisionesrecomendacionesatencion.edit',$comentario) }}">
                                            Atender
                                        </a>
                                    @endif --}}
                                    @if (auth()->user()->siglas_rol=='JD'&& $comentario->estatus=='Pendiente'&& $comentario->de_usuario_id=='101' && ($comentario->tipo=="Analisis" ))
                                    
                                        <a class="btn btn-primary text-center" href="{{ route('recomendacionesanalisis.edit',$recomendaciones) }}">
                                                Atender 
                                        </a>   
                                        
                                    @elseif (auth()->user()->siglas_rol=='JD'&& $comentario->estatus=='Pendiente'&& $comentario->de_usuario_id=='101'&& $comentario->tipo=="Conclusión") 
                                    
                                            <a class="btn btn-primary popupcomentario" href="{{ route('recomendacionesanalisis.edit',$comentario) }}">
                                                  Atender
                                            </a>
                                    
                                    @elseif (auth()->user()->siglas_rol=='JD'&& $comentario->estatus=='Pendiente'&& $comentario->de_usuario_id=='101'&& $comentario->tipo=="Conclusión") 
                                    

                                            <a class="btn btn-primary popupcomentario" href="{{ route('recomendacionesdocumentos.edit',$comentario) }}">
                                                    recomendaciondocumentos
                                            </a>

                                    @elseif(auth()->user()->siglas_rol=='ANA'&& $comentario->estatus=='Pendiente' && (empty($comentario->de_usuario_id=='101')))
                                
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
                                                        <td>{{ $respuesta->deusuario->name }}
                                                            <br>
                                                            <small class="text-muted">{{ $comentario->deusuario->puesto }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('revisionesrecomendaciones.show',$respuesta) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
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
                                <td colspan="5" class="text-center">
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
                width:"80%",
                height:"1050px",
                maxWidth:700,
                maxHeight:"1050px",
                iframe: true,
                onClosed: function() {
                    location.reload(true);
                },
                onComplete: function () {
                 $(this).colorbox.resize({width:"80%",maxWidth:600, height:"800px", maxHeight:"800px"});
                 $(window).trigger("resize");
                }
            });
        });
    </script>
@endsection
