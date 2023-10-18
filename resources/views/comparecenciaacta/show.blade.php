@extends('layouts.app')
@section('breadcrums')
@if (empty($comparecencia))
    {{ Breadcrumbs::render('comparecenciaacta2.show',$auditoria) }}
@else
    {{ Breadcrumbs::render('comparecenciaacta.show',$comparecencia,$auditoria) }}
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
                                    <th>Periodo de la etapa de aclaración</th>
                                    <th>Comprobante de recepción depto. de notificaciones</th>
                                    <th>Acuse de la radicación y comparecencia</th>
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
                                            {{ fecha($auditoria->comparecencia->fecha_comparecencia) . ' ' .
                                            $auditoria->comparecencia->hora_comparecencia_inicio . ' - ' .
                                            $auditoria->comparecencia->hora_comparecencia_termino }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ fecha($auditoria->comparecencia->fecha_inicio_aclaracion) . ' - '
                                        .fecha($auditoria->comparecencia->fecha_termino_aclaracion) }}
                                    </td>
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
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Acta de comparecencia</th>
                                    <th>Oficio de acreditación</th>
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
                                        @if (!empty($auditoria->comparecencia->oficio_acreditacion))
                                        <a href="{{ asset($auditoria->comparecencia->oficio_acreditacion) }}"
                                            target="_blank">
                                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acreditacion)) ?>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td class="text-center" colspan="2">
                                        No hay registros en éste apartado.
                                    </td>
                                </tr>

                                @endif
                            </tbody>
                        </table>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre del representante</th>
                                    <th>Cargo del representante</th>
                                    <th>Número de identificación del representante</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($auditoria->comparecencia))
                                <tr>
                                    <td>
                                        {{ $auditoria->comparecencia->nombre_representante }}
                                    </td>
                                    <td>
                                        {{ $auditoria->comparecencia->cargo_representante1 }}
                                    </td>
                                    <td>
                                        {{ $auditoria->comparecencia->numero_identificacion_representante }}
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td class="text-center" colspan="3">
                                        No hay registros en éste apartado.
                                    </td>
                                </tr>                                    
                                @endif
                            </tbody>
                        </table>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre del testigo 1</th>
                                    <th>Cargo del testigo 1</th>
                                    <th>Número de identificación del testigo 1</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($auditoria->comparecencia))
                                <tr>
                                    <td>
                                        {{ $auditoria->comparecencia->nombre_testigo1 }}
                                    </td>
                                    <td>
                                        {{ $auditoria->comparecencia->cargo_testigo1 }}
                                    </td>
                                    <td>
                                        {{ $auditoria->comparecencia->numero_identificacion_testigo1 }}
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td class="text-center" colspan="3">
                                        No hay registros en éste apartado.
                                    </td>
                                </tr>                                    
                                @endif
                            </tbody>
                        </table>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre del testigo 2</th>
                                    <th>Cargo del testigo 2</th>
                                    <th>Número de identificación del testigo 2</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($auditoria->comparecencia))
                                <tr>
                                    <td>
                                        {{ $auditoria->comparecencia->nombre_testigo2 }}
                                    </td>
                                    <td>
                                        {{ $auditoria->comparecencia->cargo_testigo2 }}
                                    </td>
                                    <td>
                                        {{ $auditoria->comparecencia->numero_identificacion_testigo2
                                        }}
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td class="text-center" colspan="3">
                                        No hay registros en éste apartado.
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
