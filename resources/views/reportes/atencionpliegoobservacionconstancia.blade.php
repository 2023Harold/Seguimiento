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
<span
    style="font-family: Arial; color: #951953; font-size: 12px;   font-weight: bold; text-decoration: underline;">Datos
    de atención</span>
<div style="page-break-inside: auto;">
    <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Listado de fechas de contestación: </span>
    <table width="100%" class="table">
        <thead>
            <tr>
                <th class="th" width="25%"
                    style="background-color: #C7C7C7; padding-left: 3px; padding-right: 3px; text-indent: 0px;  vertical-align: middle;text-align: center;">
                    <span style="font-family:  Arial; color: #000000; font-size: 10px;">Número</span>
                </th>
                <th class="th" width="25%"
                    style="background-color: #C7C7C7; padding-left: 3px; padding-right: 3px; text-indent: 0px;  vertical-align: middle;text-align: center;">
                    <span style="font-family:  Arial; color: #000000; font-size: 10px;">Fecha</span>
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($relacion1 as $contestacion)
            <tr>
                <td class="td">
                    <span style="font-family: Arial; color: #000000; font-size: 10px;">
                        <center>{{ $contestacion->consecutivo }}</center>
                    </span>
                </td>
                <td class="td">
                    <span style="font-family: Arial; color: #000000; font-size: 10px;">
                        <center>{{ fecha($contestacion->fecha_oficio_contestacion) }}</center>
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4"> No se han registrado datos en este apartado</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<table border="0" width="100%">
    <tr>
        <td colspan="3" width="100%">
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Listado de documentos: </span><br>
            <p style="font-family: Arial; color: #030303; font-size: 10px;">
                @php
                echo nl2br($modelo->listado_documentos);
                @endphp
            </p>

        </td>
    </tr>
    <tr>
        <td colspan="3" width="100%">
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Analisis: </span><br>
            <p style="font-family: Arial; color: #030303; font-size: 10px; text-align: justify;">
                @php
                echo nl2br($modelo->analisis);
                @endphp
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="3" width="100%">
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Calificación de la atención: </span>
            <span
                style="font-family: Arial; color: #030303; font-size: 10px;">{{ $modelo->calificacion_sugerida }}</span>

        </td>
    </tr>
    <tr>
        <td colspan="3" width="100%">
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Conclusión: </span><br>
            <p style="font-family: Arial; color: #030303; font-size: 10px; text-align: justify;">
                @php
                echo nl2br($modelo->conclusion);
                @endphp
            </p>
        </td>
    </tr>
    @if (!empty($modelo->promocion))
        <tr>
            <td colspan="3" width="100%">
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Promoción:</span>
                <span
                    style="font-family: Arial; color: #030303; font-size: 10px;">
                    @if ($modelo->promocion==1)
                    Solicitud de aclaración
                    @endif
                    @if ($modelo->promocion==2)
                    Recomendación
                    @endif
                    @if ($modelo->promocion==3)
                    Pliego de observación
                    @endif
                    @if ($modelo->promocion==4)
                    Promoción de responsabilidad administrativa sancionatoria
                    @endif                    
                </span>

            </td>
        </tr>
        <tr>
            <td colspan="3" width="100%">
                <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Monto de la promoción:</span>
                <span
                    style="font-family: Arial; color: #030303; font-size: 10px;">{{ '$'.number_format( $modelo->monto_promocion, 2) }}</span>

            </td>
        </tr>
    @endif     
    <tr>
        <td width="33%">
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Importe promovido: </span>
            <span
                style="font-family: Arial; color: #030303; font-size: 10px;">{{ '$'.number_format( $accion->monto_aclarar, 2) }}</span>

        </td>    
        <td width="33%">
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Importe solventado:</span>
            <span
                style="font-family: Arial; color: #030303; font-size: 10px;">{{ '$'.number_format( $modelo->monto_solventado, 2) }}</span>

        </td>    
        <td width="34%">
            <span style="font-family: Arial; color: #4A4A4A; font-size: 10px;">Importe no solventado:</span>
            <span
                style="font-family: Arial; color: #030303; font-size: 10px;">{{ '$'.number_format( ($accion->monto_aclarar-$modelo->monto_solventado), 2)  }}</span>

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