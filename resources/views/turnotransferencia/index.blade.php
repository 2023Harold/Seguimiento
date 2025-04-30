@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('turnoarchivotransferencia.index',$auditoria) }}
@endsection
@section('content')
<div class="row"> 
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">
                        <a href="{{ route('inicioarchivotransferencia.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                        Envío Archivo de Transferencia                   
                </h1>
                </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._turnoarchivo')                                   
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Inventario de documentos</th>
                                    <th>Fecha de transferencia</th>
                                    <th>Tiempo de resguardo</th>
                                    <th>Clave topográfica</th>
                                    <th>Fase/Acción</th>
                                    <th> Envío </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($auditoria->turnoarchivotransferencia))
                                <tr>
                                    <td class="text-center">
                                        @btnFile($auditoria->turnoarchivotransferencia->inventario_transferencia)
                                    </td>
                                    <td class="text-center">
                                        {{  fecha($auditoria->turnoarchivotransferencia->fecha_transferencia)}}
                                    </td>
                                    <td class="text-center">
                                        {{ ($auditoria->turnoarchivotransferencia->tiempo_resguardo) }}
                                    </td>
                                    <td class="text-center">
                                        {{$auditoria->turnoarchivotransferencia->clave_topografica }}
                                    </td>
                                    <td class="text-center">
                                        @if (empty($auditoria->turnoarchivotransferencia->fase_autorizacion)||$auditoria->turnoarchivotransferencia->fase_autorizacion=='Rechazado')
                                                <span class="badge badge-light-danger">{{ $auditoria->turnoarchivotransferencia->fase_autorizacion }} </span>
                                                @can('turnoarchivotransferencia.edit')
                                                <a href="{{ route('turnoarchivotransferencia.edit',$auditoria->turnoarchivotransferencia) }}" class="btn btn-primary">
                                                    <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                                </a>
                                                @endcan
                                            @endif
                                            @if ($auditoria->turnoarchivotransferencia->fase_autorizacion == 'En revisión')
                                            @can('turnoarchivotransferenciarevision.edit')
                                                <a href="{{ route('turnoarchivotransferenciarevision.edit',$auditoria->turnoarchivotransferencia) }}" class="btn btn-primary">
                                                    <li class="fa fa-gavel"></li>
                                                    Revisar
                                                </a>
                                            @else
                                                <span class="badge badge-light-warning">{{ $auditoria->turnoarchivotransferencia->fase_autorizacion }} </span>
                                            @endcan
                                            @endif
                                            @if ($auditoria->turnoarchivotransferencia->fase_autorizacion == 'En validación')
                                                @can('turnoarchivotransferenciavalidacion.edit')
                                                    <a href="{{ route('turnoarchivotransferenciavalidacion.edit',$auditoria->turnoarchivotransferencia) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Validar
                                                    </a>
                                                @else 
                                                    <span class="badge badge-light-warning">{{ $auditoria->turnoarchivotransferencia->fase_autorizacion }} </span>
                                                @endcan
                                            @endif
                                            @if ($auditoria->turnoarchivotransferencia->fase_autorizacion == 'En autorización')
                                            @can('turnoarchivotransferenciaautorizacion.edit')
                                                <a href="{{ route('turnoarchivotransferenciaautorizacion.edit',$auditoria->turnoarchivotransferencia) }}" class="btn btn-primary">
                                                    <li class="fa fa-gavel"></li>
                                                    Autorizar
                                                </a>
                                            @else
                                                <span class="badge badge-light-warning">{{ $auditoria->turnoarchivotransferencia->fase_autorizacion }} </span>
                                            @endcan
                                        @endif
                                            @if ($auditoria->turnoarchivotransferencia->fase_autorizacion=='Autorizado')
                                                <span class="badge badge-light-success">{{ $auditoria->turnoarchivotransferencia->fase_autorizacion }} </span>
                                            @endif
                                        <td class="text-center">
                                            @if (empty($auditoria->turnoarchivotransferencia->fase_autorizacion)||$auditoria->turnoarchivotransferencia->fase_autorizacion=='Rechazado')
                                                @can('turnoarchivotransferencia.edit')
                                                    <a href="{{ route('turnoarchivotransferenciaenvio.edit',$auditoria->turnoarchivotransferencia) }}" class="btn btn-primary">
                                                    Enviar
                                                    </a>
                                                @endcan
                                            @endif
                                        </td>
                                    </td>
                                    @if (!empty($auditoria->turnoarchivotransferencia))
                                    {!! movimientosDesglose($auditoria->turnoarchivotransferencia->id, 10, $auditoria->turnoarchivotransferencia->movimientos) !!}
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
            </div>                
            </div>
        </div>
    </div>
</div>
@endsection
