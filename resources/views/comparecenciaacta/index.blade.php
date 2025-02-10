@extends('layouts.app')
@section('breadcrums')
@if (empty($comparecencia))
    {{Breadcrumbs::render('comparecenciaacta2.show',$auditoria) }}
@else
    {{Breadcrumbs::render('comparecenciaacta.index',$comparecencia,$auditoria) }}
@endif
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
                    &nbsp; Comparecencia
                </h1>
				 <div class="float-end">
                    <a href="{{route('comparecencia.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;AC</a>                                  
                </div>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')                
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre del titular a quien se dirige</th>
                                    <th>Cargo del titular a quien se dirige</th>
                                    <th>Fecha y hora de la comparecencia</th>
                                    <th>Hora aproximada de término</th>
                                    <th>Sala</th>
                                    {{-- <th>Periodo de la etapa de aclaración</th> --}}
                                    <th>Comprobante de recepción depto. de notificaciones</th>
                                    <th>Acuse de notificación de informe de auditoría</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($auditoria->comparecencia))
                                <tr>
                                    <td>
                                        {{ $auditoria->comparecencia->nombre_titular }}
                                    </td>
                                    <td>
                                        {{ $auditoria->comparecencia->cargo_titular }}
                                    </td>
                                    <td class="text-center">
                                        <span>
                                            {{ fecha($auditoria->comparecencia->fecha_comparecencia)  }} <br>
                                            {{date('g:i a', strtotime($auditoria->comparecencia->hora_comparecencia_inicio))  . ' - ' .
                                              (empty($auditoria->comparecencia->hora_comparecencia_termino)?"00:00":date('g:i a', strtotime($auditoria->comparecencia->hora_comparecencia_termino)))  }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ (empty($auditoria->comparecencia->agenda->hora_fin)?'':date('g:i a', strtotime($auditoria->comparecencia->agenda->hora_fin))) }}</td>
                                     <td class="text-center">{{ empty($auditoria->comparecencia->agenda->sala)?'':$auditoria->comparecencia->agenda->sala }}</td>
                                    {{-- {{ (empty($auditoria->comparecencia->agenda->hora_fin)?'':date('g:i a', strtotime($auditoria->comparecencia->agenda->hora_fin))) }}</td>
                                     <td class="text-center">
                                        {{ fecha($auditoria->comparecencia->fecha_inicio_aclaracion) . ' - '
                                        .fecha($auditoria->comparecencia->fecha_termino_aclaracion) }}
                                    </td> --}}
                                    <td class="text-center">
                                        @if (!empty($auditoria->comparecencia->oficio_recepcion))
                                        <a href="{{ asset($auditoria->comparecencia->oficio_recepcion) }}"
                                            target="_blank">
                                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_recepcion)) ?>
                                        </a><br>
                                        <small>{{ fecha($auditoria->comparecencia->fecha_recepcion) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($auditoria->comparecencia->oficio_acuse))
                                        <a href="{{ asset($auditoria->comparecencia->oficio_acuse) }}" target="_blank">
                                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acuse)) ?>
                                        </a><br>
                                        <small>{{ fecha($auditoria->comparecencia->fecha_acuse) }}</small>
                                        @endif
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td class="text-center" colspan="6">
                                        No hay registros en éste apartado.
                                    </td>
                                </tr>
                                @endif                                
                            </tbody>
                        </table>
                    </div>	
        	
                    @if(getSession('cp')==2022)                                                       
                            @if (empty($auditoria->comparecencia->oficio_acta)&&!empty($auditoria->comparecencia->oficio_acuse) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                                @can('comparecenciaacta.edit')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ route('comparecenciaacta.edit',$auditoria->comparecencia) }}"  class="btn btn-primary float-end">
                                                <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar acta
                                            </a>
                                        </div>                    
                                    </div>
                                @endcan    
                            @endif                                                                                  
                    @elseif(getSession('cp')==2023)  
                                @if (empty($auditoria->comparecencia->oficio_acta)&&!empty($auditoria->comparecencia->oficio_acuse) && $auditoria->lidercp_id==auth()->user()->id)
                                    @can('comparecenciaacta.edit')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="{{ route('comparecenciaacta.edit',$auditoria->comparecencia) }}"  class="btn btn-primary float-end">
                                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar acta
                                                </a>
                                            </div>                    
                                        </div>
                                    @endcan    
                                @endif 
                    @endif 
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Acta de comparecencia</th>
                                        <th>Oficio de designación</th>
										<th>Fase / Acción</th>
                                        @if (getSession('cp')==2022 && auth()->user()->siglas_rol=='JD')
                                        <th>Enviar</th> 
                                        @elseif(getSession('cp')==2023 && auth()->user()->siglas_rol=='LP')    
                                        <th>Enviar</th> 
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($auditoria->comparecencia->oficio_acta))
                                        <tr>
                                            <td class="text-center">
                                                @if (!empty($auditoria->comparecencia->oficio_acta))
                                                <a href="{{ asset($auditoria->comparecencia->oficio_acta) }}" target="_blank">
                                                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acta)) ?>
                                                </a><br>
                                                <small>{{ 'No. '.$auditoria->comparecencia->numero_acta }}</small><br>
                                                <small>{{ fecha($auditoria->comparecencia->fecha_cedula) }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (!empty($auditoria->comparecencia->oficio_designacion))
                                                <a href="{{ asset($auditoria->comparecencia->oficio_designacion) }}"
                                                    target="_blank">
                                                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_designacion)) ?>
                                                </a><br>
                                                <small>{{ fecha($auditoria->comparecencia->fecha_oficio_designacion) }}</small>
                                                @endif                                        
                                            </td>
                                            <td class="text-center">                                                                                                                                
                                                @if (empty($auditoria->comparecencia->fase_autorizacion)||$auditoria->comparecencia->fase_autorizacion=='Rechazado')
                                                    <span class="badge badge-light-danger">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                    @can('comparecenciaacta.edit')                                                        
                                                            <a href="{{ route('comparecenciaacta.edit',$auditoria->comparecencia) }}" class="text-primary">
                                                            <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                            </a>
                                                        @endcan
                                                @endif
                                                @if (getSession('cp')==2022 && auth()->user()->siglas_rol=='Ld')
                                                    @if ($auditoria->comparecencia->fase_autorizacion == 'En validación')
                                                        @can('comparecenciavalidacion.edit')
                                                            <a href="{{ route('comparecenciavalidacion.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                                <li class="fa fa-gavel"></li>
                                                                Validar
                                                            </a>
                                                    @else
                                                            <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                        @endcan
                                                @elseif($auditoria->comparecencia->fase_autorizacion == 'En revisión')                                    
                                                     @can('comparecenciarevision.edit')
                                                    <a href="{{ route('comparecenciarevision.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Revisar
                                                    </a>
                                                @else
                                                    <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                @endcan
                                            @endif       
                                    @endif       
                                    @if ($auditoria->comparecencia->fase_autorizacion == 'En autorización')
                                    @can('comparecenciaautorizacion.edit')
                                        <a href="{{ route('comparecenciaautorizacion.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                            <li class="fa fa-gavel"></li>
                                            Autorizar
                                        </a>                
                                    @else
                                        <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                    @endcan
                                @endif  
											@if ($auditoria->comparecencia->fase_autorizacion=='Autorizado')
												<span class="badge badge-light-success">{{ $auditoria->radicacion->fase_autorizacion }} </span>                                                                                                                                               
											@endif                                                                                                 
										</td>
										<td class="text-center">    
											@if (empty($auditoria->comparecencia->fase_autorizacion)||$auditoria->comparecencia->fase_autorizacion=='Rechazado')                                       
                                                @if (getSession('cp')==2022 && auth()->user()->siglas_rol=='JD')
                                                    @can('comparecenciaacta.edit')                                                        
                                                        <a href="{{ route('comparecenciaenvio.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                        Enviar
                                                        </a>
                                                    @endcan
                                                @elseif (getSession('cp')==2023 && $auditoria->lidercp_id==auth()->user()->id)
                                                    @can('comparecenciaacta.edit')                                                        
                                                        <a href="{{ route('comparecenciacpenvio.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                        Enviar
                                                        </a> 
                                                    @endcan
                                                @endif               
											@endif
										</td>            
                                    </tr>
									@if (!empty($auditoria->comparecencia))
										{!! movimientosDesglose($auditoria->comparecencia->id, 10, $auditoria->comparecencia->movimientos) !!}
									@endif   
                                            <tr>
                                                <td class="text-center">
                                                    @if (!empty($auditoria->comparecencia->oficio_acta))
                                                        <a href="{{ asset($auditoria->comparecencia->oficio_acta) }}" target="_blank">
                                                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acta)) ?>
                                                        </a><br>
                                                        <small>{{ 'No. '.$auditoria->comparecencia->numero_acta }}</small><br>
                                                        <small>{{ fecha($auditoria->comparecencia->fecha_cedula) }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (!empty($auditoria->comparecencia->oficio_designacion))
                                                        <a href="{{ asset($auditoria->comparecencia->oficio_designacion) }}"
                                                            target="_blank">
                                                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_designacion)) ?>
                                                        </a><br>
                                                        <small>{{ fecha($auditoria->comparecencia->fecha_oficio_designacion) }}</small>
                                                    @endif                                        
                                                </td>
                                                <td class="text-center">                                                                                                                                
                                                    @if (empty($auditoria->comparecencia->fase_autorizacion)||$auditoria->comparecencia->fase_autorizacion=='Rechazado')
                                                        <span class="badge badge-light-danger">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                        @can('comparecenciaacta.edit')                                                        
                                                                <a href="{{ route('comparecenciaacta.edit',$auditoria->comparecencia) }}" class="text-primary">
                                                                <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                                </a>
                                                            @endcan
                                                    @endif
                                                    @if(getSession('cp')==2023)      
                                                        @if ($auditoria->comparecencia->fase_autorizacion == 'En validación')
                                                            @can('comparecenciavalidacion.edit')
                                                                <a href="{{ route('comparecenciavalidacion.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Validar
                                                                </a>
                                                            @else
                                                                <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                            @endcan
                                                        @elseif($auditoria->comparecencia->fase_autorizacion == 'En revisión')                                    
                                                            @can('comparecenciarevision.edit')
                                                                <a href="{{ route('comparecenciarevision.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Revisar
                                                                </a>
                                                            @else
                                                                <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                            @endcan
                                                        @endif
                                                        @if ($auditoria->comparecencia->fase_autorizacion=='Autorizado')
                                                                <span class="badge badge-light-success">{{ $auditoria->radicacion->fase_autorizacion }} </span>                                                                                                                                               
                                                            @endif
                                                    @elseif(getSession('cp')!=2023)   
                                                        @if ($auditoria->comparecencia->fase_autorizacion == 'En validación')
                                                            @can('comparecenciavalidacion.edit')
                                                                <a href="{{ route('comparecenciavalidacion.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Autorizar
                                                                </a>                
                                                            @else
                                                                <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                            @endcan
                                                        @endif  
                                                        @if ($auditoria->comparecencia->fase_autorizacion=='Autorizado')
                                                                <span class="badge badge-light-success">{{ $auditoria->radicacion->fase_autorizacion }} </span>                                                                                                                                               
                                                        @endif
                                                    @endif                                                                                                 
                                                </td>            
                                            <td class="text-center">    
                                                @if (empty($auditoria->comparecencia->fase_autorizacion)||$auditoria->comparecencia->fase_autorizacion=='Rechazado')                                       
                                                    @if (getSession('cp')==2022 && auth()->user()->siglas_rol=='JD')
                                                        @can('comparecenciaacta.edit')                                                        
                                                            <a href="{{ route('comparecenciaenvio.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                            Enviar
                                                            </a>
                                                        @endcan
                                                    @else
                                                        @can('comparecenciaacta.edit')                                                        
                                                            <a href="{{ route('comparecenciaenvio.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                            Enviar
                                                            </a> 
                                                        @endcan
                                                    @endif               
                                                @endif
                                            </td>            
                                        </tr>
                                            @if (!empty($auditoria->comparecencia))
                                                {!! movimientosDesglose($auditoria->comparecencia->id, 10, $auditoria->comparecencia->movimientos) !!}
                                            @endif   
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="2">
                                                No hay registros en éste apartado.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>                                                              
                        @if (!empty($auditoria->comparecencia->comparecio) && $auditoria->comparecencia->comparecio=="X")
                        
                            <h4 class="text-primary">Titular o representante  </h4><br>
                            <div class="row">
                            
                                <div class="col-md-6">
                                    <label>Nombre:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->nombre_representante }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <label>Cargo:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->cargo_representante1 }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <label>Tipo de identificación:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->tipo_identificacion }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <label>Número de identificación:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->numero_identificacion_representante }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="row">                    
                                <div class="col-md-12"><hr></div>
                            </div>
                            @if (!empty($auditoria->comparecencia->nombre_testigo1))
                            <h4 class="text-primary">Primer testigo  </h4><br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Nombre:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->nombre_testigo1 }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <label>Cargo:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->cargo_testigo1 }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <label>Tipo de identificación:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->tipo_identificacion1 }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <label>Número de identificación:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->numero_identificacion_testigo1 }}
                                    </span>
                                </div>
                            </div>                           
                            <div class="row">                    
                                <div class="col-md-12"><hr></div>
                            </div>
                            @endif
                            @if (!empty($auditoria->comparecencia->nombre_testigo2))
                            <h4 class="text-primary">Segundo testigo  </h4><br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Nombre:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->nombre_testigo2 }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <label>Cargo:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->cargo_testigo2 }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <label>Tipo de identificación:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->tipo_identificacion2 }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <label>Número de identificación:</label>
                                    <span class="text-primary">
                                        {{ $auditoria->comparecencia->numero_identificacion_testigo2 }}
                                    </span>
                                </div>
                            </div> 
                            @endif                       
                        @endif                    
            </div>
        </div>
    </div>
</div>
@endsection
