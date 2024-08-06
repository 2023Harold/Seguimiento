@extends('layouts.reporte')
@section('Titulo')
Registro del acuerdo de radicación
@endsection
@section('content')
    <span style="font-family: Arial; color: #951953; font-size: 12px;   font-weight: bold; text-decoration: underline;">Auditoría</span>

    <table width="100%">
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Número de auditoría: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{$auditoria->numero_auditoria}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Tipo de auditoría:</span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{$auditoria->acto_fiscalizacion}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Entidad fiscalizable: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{$auditoria->entidad_fiscalizable}}</span>
            </td>
        </tr>
    </table>
    <hr>
    <span style="font-family: Arial; color: #951953; font-size: 12px;   font-weight: bold; text-decoration: underline;">Radicación</span>
    <table width="100%">
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Número del expediente: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{$auditoria->radicacion->numero_expediente}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Número del acuerdo de radicación:</span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ $auditoria->radicacion->numero_acuerdo }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Fecha del acuerdo: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ fecha($auditoria->radicacion->fecha_oficio_acuerdo) }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Documento del acuerdo: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ $auditoria->radicacion->oficio_acuerdo }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Nombre del titular a quien se dirige la comparecencia:: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ $auditoria->comparecencia->nombre_titular }}  </span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Cargo del titular a quien se dirige la comparecencia: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ $auditoria->comparecencia->cargo_titular }} </span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Fecha de la comparecencia: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ fecha($auditoria->comparecencia->fecha_comparecencia)}}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Hora de inicio de la comparecencia: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_inicio))  }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Hora aproximada de término de la comparecencia: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_termino))  }}</span>
            </td>
        </tr>
        @if ($auditoria->acto_fiscalizacion!='Desempeño')
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Inicio de la etapa de aclaración: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ fecha($auditoria->comparecencia->fecha_inicio_aclaracion)  }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Término de la etapa de aclaración: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ fecha($auditoria->comparecencia->fecha_termino_aclaracion) }}</span>
            </td>
        </tr>
        @endif
        @if ($auditoria->acto_fiscalizacion=='Legalidad' || $auditoria->acto_fiscalizacion=='Desempeño')
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Inicio del proceso de atención: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ fecha($auditoria->comparecencia->fecha_inicio_proceso)  }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Término del proceso de atención: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ fecha($auditoria->comparecencia->fecha_termino_proceso) }}</span>
            </td>
        </tr>
        @endif
    </table>   
    @if (!empty($estatus))
    <hr>
    <table>
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Estatus: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ $estatus }}</span>
            </td>
        </tr>
        @if ($estatus=='Rechazado')
        <tr>
            <td>
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Motivo del rechazo: </span>
                <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ $motivo_rechazo }}</span>
            </td>
        </tr>
        @endif
    </table>
    @endif
    
@endsection
