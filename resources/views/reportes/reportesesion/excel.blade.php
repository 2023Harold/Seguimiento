<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Calibri, Arial, sans-serif; font-size:11pt; }
        .title-row td  { font-size:14pt; font-weight:bold; color:#022626; background:#e8f0e8; padding:6px 10px; }
        .meta-row td   { font-size:9pt; color:#555; padding:2px 10px; }
        .section-header td { font-weight:bold; color:#fff; background:#022626; padding:5px 10px; }
        .kpi-label     { font-weight:bold; color:#444; background:#f0ebe5; }
        .kpi-value     { font-weight:bold; font-size:13pt; }
        .detail-header td { font-weight:bold; color:#fff; background:#022626; padding:5px 8px; border:1px solid #011515; }
        .detail-row td     { padding:4px 8px; border:1px solid #ddd; }
        .detail-row-alt td { background:#f9f6f3; }
        .spacer td { height:12px; }
    </style>
</head>
<body>
<table>
    <tr class="title-row"><td colspan="9">Reporte de Sesiones por Usuario</td></tr>
    <tr class="meta-row">
        <td colspan="9">
            Generado por: {{ Auth::user()->name }}
            &nbsp;|&nbsp; Fecha: {{ now()->format('d/m/Y H:i') }}
            &nbsp;|&nbsp; Período: {{ $fechaInicio->format('d/m/Y') }} — {{ $fechaFin->format('d/m/Y') }}
        </td>
    </tr>
    <tr class="spacer"><td colspan="9"></td></tr>

    <tr class="section-header"><td colspan="9">Resumen</td></tr>
    <tr>
        <td class="kpi-label">Usuarios en Reporte</td>
        <td class="kpi-value" style="color:#022626;">{{ $totalUsuarios }}</td>
        <td class="kpi-label">Con Sesión</td>
        <td class="kpi-value" style="color:#3c5c14;">{{ $usuariosConSesion }}</td>
        <td class="kpi-label">Sin Actividad</td>
        <td class="kpi-value" style="color:#960048;">{{ $usuariosSinSesion }}</td>
        <td class="kpi-label">Activos Hoy</td>
        <td class="kpi-value" style="color:#3c5c14;">{{ $activosHoy }}</td>
        <td></td>
    </tr>
    <tr>
        <td class="kpi-label">Total Sesiones</td>
        <td class="kpi-value" style="color:#022626;">{{ $totalSesiones }}</td>
        <td class="kpi-label">Duración Promedio</td>
        <td class="kpi-value" style="color:#90144a;" colspan="6">{{ $duracionPromedioFmt }}</td>
    </tr>
    <tr class="spacer"><td colspan="9"></td></tr>

    <tr class="section-header"><td colspan="9">Actividad por Usuario</td></tr>
    <tr class="detail-header">
        <td>#</td><td>Nombre</td><td>Rol</td><td>Unidad Administrativa</td>
        <td>Total Sesiones</td><td>Último Ingreso</td><td>Último Cierre</td>
        <td>Duración Promedio</td><td>Activo Hoy</td>
    </tr>

    @php
        $etiquetas = ['DS'=>'Director','UC'=>'Usuario de Consulta','JD'=>'Jefe de Dpto.','LP'=>'Líder','ANA'=>'Analista','STAFF'=>'Staff'];
        $i = 1;
    @endphp
    @foreach($resumenUsuarios as $u)
    <tr class="detail-row {{ $i % 2 === 0 ? 'detail-row-alt' : '' }}">
        <td>{{ $i++ }}</td>
        <td>{{ $u['name'] }}</td>
        <td>{{ $etiquetas[$u['siglas_rol']] ?? $u['siglas_rol'] }}</td>
        <td>{{ $unidades[$u['ua_id']] ?? $u['ua_id'] }}</td>
        <td>{{ $u['total_sesiones'] ?: 0 }}</td>
        <td>{{ $u['ultima_sesion'] ? $u['ultima_sesion']->format('d/m/Y H:i') : '—' }}</td>
        <td>{{ $u['ultimo_logout'] ? $u['ultimo_logout']->format('d/m/Y H:i') : '—' }}</td>
        <td>{{ $u['dur_promedio'] }}</td>
        <td>{{ $u['activo_hoy'] ? 'Sí' : 'No' }}</td>
    </tr>
    @endforeach

    <tr class="spacer"><td colspan="9"></td></tr>
    <tr><td colspan="9" style="font-size:8pt;color:#aaa;">Sistema de Auditoría — {{ now()->format('d/m/Y H:i') }}</td></tr>
</table>
</body>
</html>
