@extends('layouts.app')
@section('breadcrums')
{{Breadcrumbs::render('informeprimeraetapa.index',$auditoria) }}
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
                    Informe Primera Etapa
                </h1>
				<div class="float-end">
                    <a href="{{route('informeprimeraetapa.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;IS</a>                                  
                </div>
            </div>           
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')                     
                <h1 class="card-title">
                    <span class="text-primary"> 
                        Informe Recomendaciones
                    </span>
                </h1>    
                <div class="row">
                    <div class="col-md-12">
                        @if (empty($auditoria->informeprimeraetapa))
                            @can('informeprimeraetapa.create')
                                <a class="btn btn-primary float-end" href="{{ route('informeprimeraetapa.create') }}">
                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar
                                </a> 
                            @endcan
                        @endif
                    </div>                    
                </div>                                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Número de oficio</th>
                                <th>Nombre del titular</th>
								<th>Informe</th>
                                <th>Acuse envío a notificar</th>                                
                                <th>Acuse de notificación</th>
                                <th>Fecha de notificación</th>
                                <th>Fase / Acción </th>
                                <th>Envío</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($auditoria->informeprimeraetapa))
                            <tr>                                
                                <td class="text-center">
                                    {{ fecha($auditoria->informeprimeraetapa->fecha_informe) }}
                                </td>
                                <td class="text-center">
                                    {{$auditoria->informeprimeraetapa->numero_informe }}
                                </td>
                                <td class="text-center">
                                    {{$auditoria->informeprimeraetapa->nombre_titular_informe }}
                                </td>
								<td class="text-center">
                                    @btnFile($auditoria->informeprimeraetapa->informe)
                                </td>
                                <td class="text-center">
                                    @btnFile($auditoria->informeprimeraetapa->acuse_envio)
                                </td>                                
                                <td class="text-center">
                                    @btnFile($auditoria->informeprimeraetapa->acuse_notificacion)
                                </td>
                                <td class="text-center">
                                    {{ fecha($auditoria->informeprimeraetapa->fecha_notificacion) }}
                                </td>
                                <td class="text-center">                                                                                                                                                                                                                                                                                       
                                    @if (empty($auditoria->informeprimeraetapa->fase_autorizacion)||$auditoria->informeprimeraetapa->fase_autorizacion=='Rechazado')
                                        <span class="badge badge-light-danger">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>
                                        @can('informeprimeraetapa.edit')                                                        
                                        <a href="{{ route('informeprimeraetapa.edit',$auditoria->informeprimeraetapa) }}" class="btn btn-primary">
                                            <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                        </a>                                                                          
                                        @endcan
                                    @endif                                   
                                    @if ($auditoria->informeprimeraetapa->fase_autorizacion == 'En validación')
                                        @can('informeprimeraetapavalidacion.edit')
                                            <a href="{{ route('informeprimeraetapavalidacion.edit',$auditoria->informeprimeraetapa) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Validar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>
                                        @endcan
                                    @endif      
                                    @if ($auditoria->informeprimeraetapa->fase_autorizacion == 'En autorización')
                                    @can('informeprimeraetapaautorizacion.edit')
                                        <a href="{{ route('informeprimeraetapaautorizacion.edit',$auditoria->informeprimeraetapa) }}" class="btn btn-primary">
                                            <li class="fa fa-gavel"></li>
                                            Autorizar
                                        </a>
                                    @else
                                        <span class="badge badge-light-warning">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>
                                    @endcan
                                @endif           
                                    @if ($auditoria->informeprimeraetapa->fase_autorizacion=='Autorizado')
                                        <span class="badge badge-light-success">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>                                                                                                                                               
                                    @endif                                                                                                                                    
                                <td class="text-center">    
                                    @if (empty($auditoria->informeprimeraetapa->fase_autorizacion)||$auditoria->informeprimeraetapa->fase_autorizacion=='Rechazado')                                       
                                        @can('informeprimeraetapa.edit')                                                        
                                            <a href="{{ route('informeprimeraetapaenvio.edit',$auditoria->informeprimeraetapa) }}" class="btn btn-primary">
                                             Enviar
                                            </a>
                                        @endcan
                                    @endif
                                </td>  
                            </td>                
                            </tr>
                            @if (!empty($auditoria->informeprimeraetapa))
                                {!! movimientosDesglose($auditoria->informeprimeraetapa->id, 10, $auditoria->informeprimeraetapa->movimientos) !!}
                            @endif   
                            @else                                                             
                            <tr>            
                                <td class="text-center" colspan="5">
                                    No se encuentran registros en este apartado.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>                   
            </div>                       
                <div class="card-body">     
            <h1 class="card-title">
                <span class="text-primary"> 
                    Informe Pliegos
                </span>
            </h1>                         
                <div class="row">
                    <div class="col-md-12">
                        @if (empty($auditoria->informepliegos))
                        @can('informepliegos.create')
                            <a class="btn btn-primary float-end" href="{{ route('informepliegos.create') }}">
                                 <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar
                             </a>
                    </div>                                        
                        @endcan                 
                        @endif  
                </div>                                                                        
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Número de oficio</th>
                                    <th>Nombre del titular</th>
                                    <th>Informe</th>
                                    <th>Acuse envío a notificar</th>                                
                                    <th>Acuse de notificación</th>
                                    <th>Fecha de notificación</th>
                                    <th>Fase / Acción </th>
                                    <th>Envío</th>
    
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($auditoria ->informepliegos))
                                <tr>
                                    <td class="text-center">
                                        {{ fecha($auditoria->informepliegos->fecha_informe) }}
                                    </td>
                                    <td class="text-center">
                                        {{$auditoria->informepliegos->numero_informe }}
                                    </td>
                                    <td class="text-center">
                                        {{$auditoria->informepliegos->nombre_titular_informe }}
                                    </td>
                                    <td class="text-center">
                                        @btnFile($auditoria->informepliegos->informe)
                                    </td>
                                    <td class="text-center">
                                        @btnFile($auditoria->informepliegos->acuse_envio)
                                    </td>                                
                                    <td class="text-center">
                                        @btnFile($auditoria->informepliegos->acuse_notificacion)
                                    </td>
                                    <td class="text-center">
                                        {{ fecha($auditoria->informepliegos->fecha_notificacion) }}
                                    </td>
                                    <td class="text-center">                                                                                                                                                                                                                                                                                       
                                        @if (empty($auditoria->informepliegos->fase_autorizacion)||$auditoria->informepliegos->fase_autorizacion=='Rechazado')
                                            <span class="badge badge-light-danger">{{ $auditoria->informepliegos->fase_autorizacion }} </span>
                                            @can('informeprimeraetapa.edit')                                                        
                                            <a href="{{ route('informeprimeraetapa.edit',$auditoria->informepliegos) }}" class="btn btn-primary">
                                                <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                            </a>                                                                          
                                            @endcan
                                        @endif                                   
                                        @if ($auditoria->informepliegos->fase_autorizacion == 'En validación')
                                            @can('informeprimeraetapavalidacion.edit')
                                                <a href="{{ route('informeprimeraetapavalidacion.edit',$auditoria->informepliegos) }}" class="btn btn-primary">
                                                    <li class="fa fa-gavel"></li>
                                                    Validar
                                                </a>
                                            @else
                                                <span class="badge badge-light-warning">{{ $auditoria->informepliegos->fase_autorizacion }} </span>
                                            @endcan
                                        @endif      
                                        @if ($auditoria->informepliegos->fase_autorizacion == 'En autorización')
                                        @can('informeprimeraetapaautorizacion.edit')
                                            <a href="{{ route('informeprimeraetapaautorizacion.edit',$auditoria->informepliegos) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Autorizar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $auditoria->informepliegos->fase_autorizacion }} </span>
                                        @endcan
                                    @endif           
                                        @if ($auditoria->informepliegos->fase_autorizacion=='Autorizado')
                                            <span class="badge badge-light-success">{{ $auditoria->informepliegos->fase_autorizacion }} </span>                                                                                                                                               
                                        @endif                                                                                                                                    
                                    <td class="text-center">    
                                        @if (empty($auditoria->informepliegos->fase_autorizacion)||$auditoria->informepliegos->fase_autorizacion=='Rechazado')                                       
                                            @can('informeprimeraetapa.edit')                                                        
                                                <a href="{{ route('informeprimeraetapaenvio.edit',$auditoria->informepliegos) }}" class="btn btn-primary">
                                                 Enviar
                                                </a>
                                            @endcan
                                        @endif
                                    </td>  
                                </td>                
                                </tr>
                                @if (!empty($auditoria->informepliegos))
                                    {!! movimientosDesglose($auditoria->informepliegos->id, 10, $auditoria->informepliegos->movimientos) !!}
                                @endif   
                                @else                                                             
                                <tr>
                                    <td class="text-center" colspan="5">
                                        No se encuentran registros en este apartado.
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>   
                </div>  
            </div>        
        </div>                
    </div>                 

@endsection
