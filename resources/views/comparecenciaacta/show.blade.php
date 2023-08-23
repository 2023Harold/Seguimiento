@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('comparecenciaacta.show',$comparecencia) }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('comparecencia.index') }}"><i
                    class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Acta
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
                            <th>Fecha y hora de la comparecencia</th>
                            <th>Periodo de la etapa de aclaración</th>
                            <th>Comprobante de recepción depto. de notificaciones</th>
                            <th>Acuse de la radicación y comparecencia</th>
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
                                <a href="{{ asset($auditoria->comparecencia->oficio_recepcion) }}" target="_blank">
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
                                <a href="{{ asset($auditoria->comparecencia->oficio_acreditacion) }}" target="_blank">
                                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acreditacion)) ?>
                                </a>
                                @endif
                            </td>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nombre del representante</th>
                                        <th>Cargo del representante</th>
                                        <th>Número de identificación del representante</th>
                                    </tr>
                                </thead>
                                <tbody>
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

                                </tbody>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nombre del testigo 1</th>
                                            <th>Cargo del testigo 1</th>
                                            <th>Número de identificación del testigo 1</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                    </tbody>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nombre del testigo 2</th>
                                                <th>Cargo del testigo 2</th>
                                                <th>Número de identificación del testigo 2</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{ $auditoria->comparecencia->nombre_testigo2 }}
                                                </td>
                                                <td>
                                                    {{ $auditoria->comparecencia->cargo_testigo2 }}
                                                </td>
                                                <td>
                                                    {{ $auditoria->comparecencia->numero_identificacion_testigo2 }}
                                                </td>
                                            </tr>
                                        </tbody>




                    </tbody>
                </table>
            </div>
    </div>
</div>
@endsection