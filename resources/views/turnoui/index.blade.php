@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('turnoui.index',$auditoria) }}
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
                    Turno UI
                </h1>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')                
                <div class="row">
                    <div class="col-md-12">
                        @if (empty($turnoui))
                            @can('turnoui.create')
                                <a class="btn btn-primary float-end" href="{{ route('turnoui.create') }}">
                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Turno UI
                                </a> 
                            @endcan
                        @endif
                    </div>                    
                </div>                                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha </th>
                                <th>Número de oficio </th>
                                <th>Acuse de notificación</th>
                                <th>Fecha de notificacion</th>
                                <th>Fase / Acción </th>
                                <th>Envío</th>
                            </tr>
                        </thead>
                        <tbody>
                         @if (!empty($turnoui))
                            <tr>
                                <td class="text-center">
                                    {{ fecha($turnoui->fecha_turno_oi) }}
                                </td>
                                <td class="text-center">
                                    {{$turnoui->numero_turno_ui }}
                                </td>
                                <td class="text-center">
                                    @btnFile($turnoui->turno_ui)
                                </td>
                                <td class="text-center">
                                    {{ fecha($turnoui->fecha_notificacion_ui) }}
                                </td>                             
                                <td class="text-center">                                                                                                                                                                                                                                                                                       
                                        @if (empty($auditoria->turnoui->fase_autorizacion)||$auditoria->turnoui->fase_autorizacion=='Rechazado')
                                            <span class="badge badge-light-danger">{{ $auditoria->turnoui->fase_autorizacion }} </span>
                                            @can('turnoui.edit')                                                        
                                            <a href="{{ route('turnoui.edit',$auditoria->turnoui) }}" class="btn btn-primary">
                                                <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                            </a>                                                                          
                                            @endcan
                                        @endif
                                        @if ($auditoria->turnoui->fase_autorizacion == 'En Revisión')
                                        @can('turnouirevision.edit')
                                            <a href="{{ route('turnouirevision.edit',$auditoria->turnoui) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $auditoria->turnoui->fase_autorizacion }} </span>
                                        @endcan
                                    @endif           
                                        @if ($auditoria->turnoui->fase_autorizacion == 'En validación')
                                            @can('turnouivalidacion.edit')
                                                <a href="{{ route('turnouivalidacion.edit',$auditoria->turnoui) }}" class="btn btn-primary">
                                                    <li class="fa fa-gavel"></li>
                                                    Validar
                                                </a>
                                            @else
                                                <span class="badge badge-light-warning">{{ $auditoria->turnoui->fase_autorizacion }} </span>
                                            @endcan
                                        @endif      
                                        @if ($auditoria->turnoui->fase_autorizacion == 'En autorización')
                                        @can('turnouiautorizacion.edit')
                                            <a href="{{ route('turnouiautorizacion.edit',$auditoria->turnoui) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Autorizar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $auditoria->turnoui->fase_autorizacion }} </span>
                                        @endcan
                                    @endif           
                                        @if ($auditoria->turnoui->fase_autorizacion=='Autorizado')
                                            <span class="badge badge-light-success">{{ $auditoria->turnoui->fase_autorizacion }} </span>                                                                                                                                               
                                        @endif                                                                                                                                    
                                    <td class="text-center">    
                                        @if (empty($auditoria->turnoui->fase_autorizacion)||$auditoria->turnoui->fase_autorizacion=='Rechazado')                                       
                                            @can('turnoui.edit')                                                        
                                                <a href="{{ route('turnouienvio.edit',$auditoria->turnoui) }}" class="btn btn-primary">
                                                 Enviar
                                                </a>
                                            @endcan
                                        @endif
                                    </td>  
                                </td>                
                                </tr>
                                @if (!empty($auditoria->turnoui))
                                    {!! movimientosDesglose($auditoria->turnoui->id, 10, $auditoria->turnoui->movimientos) !!}
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
