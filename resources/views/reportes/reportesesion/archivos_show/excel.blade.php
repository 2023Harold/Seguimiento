<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="UTF-8">

<style>
    body { font-family: Calibri, Arial; font-size:11pt; }

    .title td {
        font-size:14pt;
        font-weight:bold;
        color:#022626;
        background:#e8f0e8;
        padding:6px 10px;
    }

    .meta td {
        font-size:9pt;
        padding:3px 10px;
        color:#555;
    }

    .header td {
        background:#022626;
        color:white;
        font-weight:bold;
        padding:5px;
        border:1px solid #011515;
    }

    .row td {
        border:1px solid #ddd;
        padding:4px;
    }

    .alt td {
        background:#f9f6f3;
    }
</style>

</head>
<body>

<table>

{{-- ✅ TITULO --}}
<tr class="title">
    <td colspan="6">Historial de Sesiones - {{ $usuario->name }}</td>
</tr>

{{-- ✅ META --}}
<tr class="meta">
    <td colspan="6">
        Generado por: {{ Auth::user()->name }}
        | Fecha: {{ now()->format('d/m/Y H:i') }}
        | Periodo:
        {{ $fechaInicio->format('d/m/Y') }} —
        {{ $fechaFin->format('d/m/Y') }}
    </td>
</tr>

{{-- ✅ KPIs DEL SHOW --}}
<tr>
    <td colspan="6"><b>Resumen</b></td>
</tr>

<tr class="meta">
    <td>Total Sesiones</td>
    <td><b>{{ $stats['total'] }}</b></td>

    <td>Duración Promedio</td>
    <td><b>{{ $stats['dur_promedio'] }}</b></td>

    <td>Última Conexión</td>
    <td>
        <b>
        {{ $stats['ultima']
            ? $stats['ultima']->format('d/m/Y H:i')
            : '—' }}
        </b>
    </td>
</tr>

<tr><td colspan="6"></td></tr>

{{-- ✅ TABLA --}}
<tr class="header">
    <td>#</td>
    <td>Ingreso</td>
    <td>Cierre</td>
    <td>Duración</td>
    <td>Tipo</td>
    <td>Estado</td>
</tr>

@php $i = 1; @endphp

@foreach($sesiones as $s)
@php
    $incompleto = $s->logout_type === 'unknown'
        || (!$s->logout_at && !$s->duration_seconds);
@endphp

<tr class="row {{ $i % 2 == 0 ? 'alt' : '' }}">
    <td>{{ $i++ }}</td>

    <td>{{ $s->login_at->format('d/m/Y H:i:s') }}</td>

    <td>
        {{ $s->logout_at
            ? $s->logout_at->format('d/m/Y H:i:s')
            : '—' }}
    </td>

    <td>
        {{ $incompleto ? '—' : $s->duration_formatted }}
    </td>

    <td>
        {{ $s->logout_type ?? '—' }}
    </td>

    <td>
        {{ $incompleto ? 'Incompleto' : 'Completo' }}
    </td>
</tr>

@endforeach

</table>

</body>
</html>
