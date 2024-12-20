@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('turnoarchivo.index',$auditoria) }}
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
                    Turno acuse envío archivo
                </h1>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')                
                <div class="row">
                    <div class="col-md-12">
                        @if (empty($turnoarchivo))
                            @can('turnoarchivo.create')
                                <a class="btn btn-primary float-end" href="{{ route('turnoarchivo.create') }}">
                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Acuse envío archivo
                                </a> 
                            @endcan
                        @endif
                    </div>                    
                </div>                                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th rowspan=1 colspan=2 style="width:20px" class="text-center"> Expediente Técnico de la Auditoría</th>
                                <th rowspan=1 colspan=2 style="width:20px" class="text-center"> Expediente Técnico de Seguimiento</th>                                
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th> Fecha </th>
                                <th>Número del oficio</th>
                                <th>Acuse de notificación</th>
                                <th>Número de legajos</th>
                                <th>Número de fojas</th>
                                <th>Número de legajos</th>
                                <th>Número de fojas</th>                                
                                <th>Fase/Acción</th>
                                <th>Envío</th>
                            </tr>    
                        </thead>
                        <tbody>
                            @if (!empty($turnoarchivo))
                            <tr>
                                <td class="text-center">
                                    {{ fecha($turnoarchivo->fecha_turno_archivo) }}
                                </td>
                                <td class="text-center">
                                    {{$turnoarchivo->numero_turno_archivo }}
                                </td>
                                <td class="text-center">
                                    @btnFile($turnoarchivo->turno_archivo)<br>                         
                                    <small>{{ fecha($turnoarchivo->fecha_notificacion_archivo) }} <small>
                                </td>
                                <td class="text-center">
                                    {{$turnoarchivo->legajos_tecnico_archivo }}
                                </td>    
                                <td class="text-center">
                                    {{$turnoarchivo->fojas_tecnico_archivo }}
                                </td>    
                                <td class="text-center">
                                    {{$turnoarchivo->legajos_tecnico_archivo }}
                                </td>
                                <td class="text-center">
                                    {{$turnoarchivo->fojas_tecnico_archivo }}
                                </td>    
                                <td class="text-center">                                                                                                                                                                                                                                                                                       
                                    @if (empty($auditoria->turnoarchivo->fase_autorizacion)||$auditoria->turnoarchivo->fase_autorizacion=='Rechazado')
                                        <span class="badge badge-light-danger">{{ $auditoria->turnoarchivo->fase_autorizacion }} </span>
                                        @can('turnoarchivo.edit')                                                        
                                        <a href="{{ route('turnoarchivo.edit',$auditoria->turnoarchivo) }}" class="btn btn-primary">
                                            <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                        </a>                                                                          
                                        @endcan
                                    @endif
                                    @if ($auditoria->turnoarchivo->fase_autorizacion == 'En revisión')
                                    @can('turnoarchivorevision.edit')
                                        <a href="{{ route('turnoarchivorevision.edit',$auditoria->turnoarchivo) }}" class="btn btn-primary">
                                            <li class="fa fa-gavel"></li>
                                            Revisar
                                        </a>
                                    @else
                                        <span class="badge badge-light-warning">{{ $auditoria->turnoarchivo->fase_autorizacion }} </span>
                                    @endcan
                                @endif           
                                    @if ($auditoria->turnoarchivo->fase_autorizacion == 'En validación')
                                        @can('turnoarchivovalidacion.edit')
                                            <a href="{{ route('turnoarchivovalidacion.edit',$auditoria->turnoarchivo) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Validar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $auditoria->turnoarchivo->fase_autorizacion }} </span>
                                        @endcan
                                    @endif      
                                    @if ($auditoria->turnoarchivo->fase_autorizacion == 'En autorización')
                                    @can('turnoarchivoautorizacion.edit')
                                        <a href="{{ route('turnoarchivoautorizacion.edit',$auditoria->turnoarchivo) }}" class="btn btn-primary">
                                            <li class="fa fa-gavel"></li>
                                            Autorizar
                                        </a>
                                    @else
                                        <span class="badge badge-light-warning">{{ $auditoria->turnoarchivo->fase_autorizacion }} </span>
                                    @endcan
                                @endif           
                                    @if ($auditoria->turnoarchivo->fase_autorizacion=='Autorizado')
                                        <span class="badge badge-light-success">{{ $auditoria->turnoarchivo->fase_autorizacion }} </span>                                                                                                                                               
                                    @endif                                                                                                                                    
                                <td class="text-center">    
                                    @if (empty($auditoria->turnoarchivo->fase_autorizacion)||$auditoria->turnoarchivo->fase_autorizacion=='Rechazado')                                       
                                        @can('turnoarchivo.edit')                                                        
                                            <a href="{{ route('turnoarchivoenvio.edit',$auditoria->turnoarchivo) }}" class="btn btn-primary">
                                             Enviar
                                            </a>
                                        @endcan
                                    @endif
                                </td>  
                            </td>                
                            </tr>
                            @if (!empty($auditoria->turnoarchivo))
                                    {!! movimientosDesglose($auditoria->turnoarchivo->id, 10, $auditoria->turnoarchivo->movimientos) !!}
                            @endif   
                            @else    
                                <td class="text-center" colspan="5">
                                    No se encuentran registros en este apartado.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div> 
                <h1 class="card-title">
                    <span class="text-primary"> 
                Envío archivo de transferencia
                    </span>
                </h1>
                <div class="card-body">              
                    <div class="row">
                        <div class="col-md-12">
                            @if (empty($auditoria->turnotransferencia) && empty($auditoria->turnoarchivo->fase_autorizacion)||$auditoria->turnoarchivo->fase_autorizacion=='Autorizado')
                            @can('turnoarchivotransferencia.create')
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('turnoarchivotransferencia.create',$auditoria->turnotransferencia) }}"  class="btn btn-primary float-end">
                                            <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar
                                        </a>
                                    </div>                    
                                </div>
                            @endcan                 
                        @endif  
                        </div>                                                                        
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Número de oficio de transferencia</th>
                                    <th>Inventario de documentos</th>
                                    <th>Fecha de de trasferencia</th>
                                    <th>Tiempo de resguardo</th>
                                    <th>Clave topográfica</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($auditoria->archivotransferencia))
                                <tr>
                                    <td class="text-center">
                                        {{ ($auditoria->archivotransferencia->numero_transferencia) }}
                                    </td>
                                    <td class="text-center">
                                        @btnFile($auditoria->archivotransferencia->inventario_transferencia)
                                    </td>
                                    <td class="text-center">
                                        {{  fecha($auditoria->archivotransferencia->fecha_transferencia)}}
                                    </td>
                                    <td class="text-center">
                                        {{ ($auditoria->archivotransferencia->tiempo_resguardo) }}
                                    </td>
                                    <td class="text-center">
                                        {{$auditoria->archivotransferencia->clave_topografica }}
                                    </td>                                
                                </tr>
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
</div>
@endsection
