@extends('layouts.reporte')
@section('Titulo')
Autorización de atención del pliego de observación.
@endsection
@section('content')
<span
    style="font-family: Arial; color: #951953; font-size: 12px;   font-weight: bold; text-decoration: underline;">Auditoría</span>

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
            <span
                style="font-family: Arial; color: #030303; font-size: 10px;">{{$auditoria->entidad_fiscalizable}}</span>
        </td>
    </tr>
</table>
<hr>
<span
    style="font-family: Arial; color: #951953; font-size: 12px;   font-weight: bold; text-decoration: underline;">Acción</span>
<table width="100%">
    <tr>
        <td>
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Tipo de acción: </span>
            <span style="font-family: Arial; color: #030303; font-size: 10px;">{{$accion->tipo}}</span>
        </td>
    </tr>
    <tr>
        <td>
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Número de acción:</span>
            <span style="font-family: Arial; color: #030303; font-size: 10px;">{{ $accion->numero }}</span>
        </td>
    </tr>
    <td>
        <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Monto por aclarar:</span>
        <span
            style="font-family: Arial; color: #030303; font-size: 10px;">{{ '$'.number_format( $accion->monto_aclarar, 2) }}</span>

    </td>
    <tr>
        <td>
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Acción: </span><br>
            <p style="font-family: Arial; color: #030303; font-size: 10px; text-align: justify;">
                @php
                echo nl2br($accion->accion);
                @endphp
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Normativa infringida: </span>
            <p style="font-family: Arial; color: #030303; font-size: 10px; text-align: justify;">
                @php
                echo nl2br($accion->normativa_infringida);
                @endphp
            </p>
        </td>
    </tr>
</table>
<hr>
<span style="font-family: Arial; color: #951953; font-size: 12px;   font-weight: bold; text-decoration: underline;">Turno del PRAS a OIC o equivalente</span>

<table border="0" width="100%">
    <tr>
        <td colspan="3" width="100%">
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Titular del OIC al que se turna: </span><br>
            <span style="font-family: Arial; color: #030303; font-size: 10px;">
               {{$modelo->nombre_titular_oic}}
            </span>

        </td>
    </tr>
    <tr>
        <td colspan="3" width="100%">
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Fecha del oficio: </span><br>
            <span style="font-family: Arial; color: #030303; font-size: 10px; text-align: justify;">
                {{fecha($modelo->fecha_acuse_oficio)}}
            </span>
        </td>
    </tr>
    <tr>
        <td colspan="3" width="100%">
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Número del oficio: </span>
            <span
                style="font-family: Arial; color: #030303; font-size: 10px;">{{$modelo->numero_oficio}}</span>

        </td>
    </tr>    
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
            <p style="font-family: Arial; color: #030303; font-size: 10px;">
                @php
                echo nl2br($modelo->motivo_rechazo);
                @endphp
            </p>
        </td>
    </tr>
    @endif
</table>
@endif

@endsection