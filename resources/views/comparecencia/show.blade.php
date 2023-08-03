@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('comparecencia.show',$comparecencia) }}  
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('comparecencia.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Consulta
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')            
            <h4 class="text-primary">Comparecencia</h3>
            <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre del titular a quien se dirige</th>
                                <th>Cargo del titular a quien se dirige</th>
                                <th>Oficio de notificación de la comparecencia</th>
                                <th>Fecha y hora de la comparecencia</th>
                                <th>Periodo de la etapa de aclaración</th>                              
                            </tr>
                        </thead>
                        <tbody>                            
                                <tr>
                                    <td>
                                        {{ $auditoria->comparecencia->nombre_titular }}
                                    </td>
                                    <td>
                                        {{ $auditoria->comparecencia->cargo_titular }}                                  
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ asset($auditoria->comparecencia->oficio_comparecencia) }}" target="_blank">
                                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_comparecencia)) ?>
                                        </a> 
                                    </td>
                                    <td class="text-center">
                                        <span>
                                            {{ fecha($auditoria->comparecencia->fecha_comparecencia) . ' ' . $auditoria->comparecencia->hora_comparecencia_inicio . ' - ' . $auditoria->comparecencia->hora_comparecencia_termino }}
                                        </span>
                                    </td>                                                                       
                                    <td class="text-center"> 
                                        {{ fecha($auditoria->comparecencia->fecha_inicio_aclaracion) . ' - ' .fecha($auditoria->comparecencia->fecha_termino_aclaracion) }}
                                    </td>                                                                                                              
                                </tr>       
                        </tbody>
                    </table> 
                </div>        
            <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Comprobante de recepción depto. de notificaciones</th>
                                <th>Acuse del oficio de la comparecencia</th>
                                <th>Cédula</th>
                                <th>Acta de comparecencia</th>
                                <th>Oficio de respuesta de la Entidad Fiscalizable</th>                                                              
                            </tr>
                        </thead>
                        <tbody>                            
                                <tr>
                                    <td class="text-center">
                                        @if (!empty($auditoria->comparecencia->oficio_recepcion))
                                            <a href="{{ asset($auditoria->comparecencia->oficio_recepcion) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_recepcion)) ?>
                                            </a><br>
                                            <small>{{  fecha($auditoria->comparecencia->fecha_recepcion) }}</small>
                                        @endif  
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($auditoria->comparecencia->oficio_acuse))
                                            <a href="{{ asset($auditoria->comparecencia->oficio_acuse) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acuse)) ?>
                                            </a><br>
                                            <small>{{  fecha($auditoria->comparecencia->fecha_acuse) }}</small>
                                        @endif                                                                   
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($auditoria->comparecencia->cedula_general))
                                            <a href="{{ asset($auditoria->comparecencia->cedula_general) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->cedula_general)) ?>
                                            </a><br>
                                            <small>{{  fecha($auditoria->comparecencia->fecha_cedula) }}</small>
                                        @endif  
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($auditoria->comparecencia->oficio_acta))
                                            <a href="{{ asset($auditoria->comparecencia->oficio_acta) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acta)) ?>
                                            </a><br>
                                            <small>{{  'No. '.$auditoria->comparecencia->numero_acta }}</small><br>
                                            <small>{{  fecha($auditoria->comparecencia->fecha_cedula) }}</small>
                                        @endif 
                                    </td>                                                                       
                                    <td class="text-center"> 
                                        @if (!empty($auditoria->comparecencia->oficio_respuesta))
                                            <a href="{{ asset($auditoria->comparecencia->oficio_respuesta) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_respuesta)) ?>
                                            </a><br>                                            
                                            <small>{{  fecha($auditoria->comparecencia->fecha_respuesta) }}</small>
                                        @endif 
                                    </td>                                                                  
                                </tr>       
                        </tbody>
                    </table> 
                </div>        
        </div>
    </div>
@endsection
