@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('solicitudesaclaracionatencion.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9">
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
                @include('layouts.contextos._accionsolicitud')
                @if (count($solicitudesaclaracion)>0 && !empty($solicitudesaclaracion[0]->fase_autorizacion))
                   <div class="row">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Importe promovido</th>
										<th>Importe solventado</th>
										<th>Importe no solventado</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="text-align: right!important;">{{ '$'.number_format( $accion->monto_aclarar, 2) }}</td>
										<td style="text-align: right!important;">{{ '$'.number_format( $solicitudesaclaracion[0]->monto_solventado, 2) }}</td>
										<td style="text-align: right!important;">{{ '$'.number_format( ($accion->monto_aclarar - $solicitudesaclaracion[0]->monto_solventado), 2) }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
                @endif
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
                                <th>Calificación de la atención</th>
                                <th>Fase / Constancia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($solicitudesaclaracion as $solicitud)
                            <tr>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($solicitud->fase_autorizacion) || $solicitud->fase_autorizacion=='Rechazado'))
                                       @if (empty($solicitud->fase_revision) || ($solicitud->fase_revision!='Pendiente' && $solicitud->fase_revision!='Revisión Jefe' ))
                                            @can('solicitudesaclaracioncontestacion.index')
                                                <a href="{{ route('solicitudesaclaracioncontestacion.index') }}" class="btn btn-light-primary">
                                                    <span class="fa fa-list" aria-hidden="true"></span>
                                                </a>
                                            @endcan
                                       @else
                                            @can('solicitudesaclaracioncontestacion.show')
                                                <a href="{{ route('solicitudesaclaracioncontestacion.show',$solicitud) }}" class="btn btn-link btn-color-muted btn-active-color-primary">
                                                    <span class="fa fa-list" aria-hidden="true"></span>
                                                </a>
                                            @endcan
                                       @endif
                                    @else
                                        @can('solicitudesaclaracioncontestacion.show')
                                            <a href="{{ route('solicitudesaclaracioncontestacion.show',$solicitud) }}" class="btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                        @endcan
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($solicitud->fase_autorizacion) || $solicitud->fase_autorizacion=='Rechazado'))
                                        @if (empty($solicitud->fase_revision) || ($solicitud->fase_revision!='Pendiente' && $solicitud->fase_revision!='Revisión Jefe' ))
                                            {{-- @can('solicitudesaclaraciondocumentos.edit') --}}
                                            <a href="{{ route('solicitudesaclaraciondocumentos.edit', $solicitud) }}" class="btn btn-light-primary">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                            {{-- @endcan --}}
                                        @else
                                            @can('solicitudesaclaraciondocumentos.show')
                                            <a href="{{ route('solicitudesaclaraciondocumentos.show', $solicitud) }}" class="btn btn btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-list" aria-hidden="true"></span>
                                            </a>
                                            @endcan
                                        @endif
                                    @else
                                        @can('solicitudesaclaraciondocumentos.show')
                                        <a href="{{ route('solicitudesaclaraciondocumentos.show', $solicitud) }}" class="btn btn btn btn-link btn-color-muted btn-active-color-primary">
                                            <span class="fa fa-list" aria-hidden="true"></span>
                                        </a>
                                        @endcan
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (in_array("Analista", auth()->user()->getRoleNames()->toArray())&&(empty($solicitud->fase_autorizacion) || $solicitud->fase_autorizacion=='Rechazado'))
                                        @if (empty($solicitud->fase_revision) || ($solicitud->fase_revision!='Pendiente' && $solicitud->fase_revision!='Revisión Jefe' ))
                                            @can('solicitudesaclaracionanalisis.edit')
                                            <a href="{{ route('solicitudesaclaracionanalisis.edit',$solicitud) }}" class="btn btn-light-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                            @endcan
                                        @else
                                            @can('solicitudesaclaracionanalisis.show')
                                            <a href="{{ route('solicitudesaclaracionanalisis.show',$solicitud) }}" class="btn btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                            @endcan
                                        @endif
                                    @else
                                        @can('solicitudesaclaracionanalisis.show')
                                            <a href="{{ route('solicitudesaclaracionanalisis.show',$solicitud) }}" class="btn btn btn-link btn-color-muted btn-active-color-primary">
                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                            </a>
                                        @endcan
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (empty($solicitud->calificacion_atencion))
                                        @if ($solicitud->calificacion_sugerida=='Solventada')
                                            <span class="badge badge-light-success">Solventada</span><br>
                                        @endif
                                        @if ($solicitud->calificacion_sugerida=='No Solventada')
                                                <span class="badge badge-light-danger">No Solventada</span><br>
                                        @endif
                                        @if ($solicitud->calificacion_sugerida=='Solventada Parcialmente')
                                                <span class="badge badge-light-warning">Solventada Parcialmente</span><br>
                                        @endif
                                    @else
                                        @if ($solicitud->calificacion_atencion=='Solventada')
                                            <span class="badge badge-light-success">Solventada</span><br>
                                        @endif
                                        @if ($solicitud->calificacion_atencion=='No Solventada')
                                            <span class="badge badge-light-danger">No Solventada</span><br>
                                        @endif
                                        @if ($solicitud->calificacion_atencion=='Solventada Parcialmente')
                                            <span class="badge badge-light-warning">Solventada Parcialmente</span><br>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (empty($solicitud->fase_autorizacion))
                                        @can('solicitudesaclanalisisenvio.edit')
                                            <a href="{{ route('solicitudesaclanalisisenvio.edit',$solicitud) }}" class="btn btn-primary">
                                                <span class="fa phpdebugbar-fa-send" aria-hidden="true"></span> Enviar
                                            </a>
                                        @endcan
                                    @endif
                                    @if ($solicitud->fase_autorizacion == 'Rechazado')
                                        <span class="badge badge-light-danger">{{ $solicitud->fase_autorizacion }}</span>
                                        @can('solicitudesaclanalisisenvio.edit')
                                            <br>
                                            <a href="{{ route('solicitudesaclanalisisenvio.edit',$solicitud) }}" class="btn btn-primary">
                                                <span class="fa phpdebugbar-fa-send" aria-hidden="true"></span> Enviar
                                            </a>
                                        @endcan
                                    @endif
                                    @if ($solicitud->fase_autorizacion == 'En revisión 01')
                                        @can('solicitudesaclaracionrevision01.edit')
                                            <a href="{{ route('solicitudesaclaracionrevision01.edit',$solicitud) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">En revisión</span>
                                        @endcan
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
                                        <span class="badge badge-light-success">{{ $solicitud->fase_autorizacion }} </span>                                     
                                    @endif                                    
                                </td>
                            </tr>
                            {!! movimientosDesglose($solicitud->id, 9, $solicitud->movimientos) !!}
                            @empty
                            <tr>
                                <td colspan="7" class="text-center table-secondary">
                                    Aun no se encuentra registrada la fecha de compromiso de atención.
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
                            {{-- <a class="btn btn-primary float-end popupcomentario" href="{{ route('revisionessolicitudes.create') }}">
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
                            {{-- {{ dd($accion,$accion->comentariossolicitudes) }} --}}
                            @forelse ($accion->comentariossolicitudes as $comentario)
                             <tr>
                                <td>
                                    {{ fecha($comentario->created_at,'d/m/Y H:m:s') }}
                                </td>
                                <td>
                                    {{ $comentario->deusuario->name }} <br>
                                    <small class="text-muted">{{ $comentario->deusuario->puesto }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('revisionessolicitudes.show',$comentario) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
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
                                      @if (auth()->user()->siglas_rol=='JD'&& $comentario->estatus=='Pendiente'&& $comentario->de_usuario_id=='101' && ($comentario->tipo=="Analisis" ))
                                    
                                        <a class="btn btn-primary text-center" href="{{ route('solicitudesaclaracionanalisis.edit',$comentario) }}">
                                                Atender 
                                        </a>   
                                        
                                    @elseif (auth()->user()->siglas_rol=='JD'&& $comentario->estatus=='Pendiente'&& $comentario->de_usuario_id=='101'&& $comentario->tipo=="Conclusión") 
                                     
                                            <a class="btn btn-primary popupcomentario" href="{{ route('solicitudesaclaracionanalisis.edit',$comentario) }}">
                                                  Atender
                                            </a>
                                    
                                    @elseif (auth()->user()->siglas_rol=='JD'&& $comentario->estatus=='Pendiente'&& $comentario->de_usuario_id=='101'&& $comentario->tipo=="Conclusión") 
                                     


                                            <a class="btn btn-primary popupcomentario" href="{{ route('solicitudesaclaraciondocumentos.edit',$comentario) }}">
                                                    solicitudesdocumentos
                                            </a>

                                    @elseif(auth()->user()->siglas_rol=='ANA'&& $comentario->estatus=='Pendiente' && (empty($comentario->de_usuario_id=='101')))
                                    
                                            <a class="btn btn-primary popupcomentario" href="{{ route('revisionessolicitudesatencion.edit',$comentario) }}">
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
                                                            <a href="{{ route('revisionessolicitudes.show',$respuesta) }}" class="btn btn-link btn-color-muted btn-active-color-primary popupSinLocation">
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
