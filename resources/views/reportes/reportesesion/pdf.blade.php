@extends('layouts.pdf_reportes')

@section('title', 'Historial de Sesiones')

@section('content')
    <div class="page-wrap">
        <div class="print-button-container" style="text-align: right;">
            <button class="print-btn" onclick="window.print()">
                <span> <i class="bi bi-printer fa-1x "></i> Imprimir</span>
            </button>
        </div>
        <div class="card fancy-border">
            <div class="card-ribbon">Historial de Sesiones</div>
            {{-- Botón solo visible en pantalla --}}

            {{-- ── Cabecera ── --}}
            <div class="report-header">
                <div>
                    <h1>Reporte de Sesiones por Usuario</h1>
                    <div style="font-size:8.5pt;color:#555;margin-top:3px;">Generado por: <strong>{{ Auth::user()->name }}</strong></div>
                </div>
                <div class="meta">Generado el:<br><strong>{{ now()->format('d/m/Y H:i') }}</strong></div>
            </div>
        </div>

        <div class="card fancy-border">
            <div class="card-ribbon">Filtros aplicados</div>
            {{-- ── Resumen de filtros aplicados ── --}}
            <div class="filtros-resumen">
                <strong>Período:</strong> {{ $fechaInicio->format('d/m/Y') }} — {{ $fechaFin->format('d/m/Y') }}
                &nbsp;|&nbsp; <strong>Usuarios:</strong> {{ $totalUsuarios }}
            </div>
        </div>

        <div class="kpis">
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Usuarios</div>
                <div class="kpi-value" style="color:#022626;">{{ $totalUsuarios }}</div>
            </div>
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Con Sesión</div>
                <div class="kpi-value" style="color:#3c5c14;">{{ $usuariosConSesion }}</div>
            </div>
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Sin Actividad</div>
                <div class="kpi-value" style="color:#960048;">{{ $usuariosSinSesion }}</div>
            </div>
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Total Sesiones</div>
                <div class="kpi-value" style="color:#022626;">{{ $totalSesiones }}</div>
            </div>
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Dur. Promedio</div>
                <div class="kpi-value" style="color:#90144a; font-size:12pt;">{{ $duracionPromedioFmt }}</div>
            </div>
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Activos Hoy</div>
                <div class="kpi-value" style="color:#3c5c14;">{{ $activosHoy }}</div>
            </div>
        </div>

        @php
            $etiquetas = ['DS'=>'Director','UC'=>'Usr. Consulta','JD'=>'Jefe Depto.','LP'=>'Líder','ANA'=>'Analista','STAFF'=>'Staff'];
        @endphp
        <div class="card fancy-border">
            <div class="card-ribbon">Detalle</div>
            <table>
                <thead>
                    <tr>
                        <th>#</th><th>Nombre</th><th>Rol</th><th>Unidad</th>
                        <th>Sesiones</th><th>Último Ingreso</th><th>Último Cierre</th>
                        <th>Dur. Prom.</th><th>Activo Hoy</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resumenUsuarios as $i => $u)
                    <tr>
                        <td style="text-align: center;">{{ $i + 1 }}</td>
                        <td><strong>{{ $u['name'] }}</strong></td>
                        <td style="text-align: center;">{{ $etiquetas[$u['siglas_rol']] ?? $u['siglas_rol'] }}</td>
                        <td><small>{{ $unidades[$u['ua_id']] ?? $u['ua_id'] }}</small></td>
                        <td style="text-align: center;"> <span class="badge badge-morado">{{ $u['total_sesiones'] ?: '—' }}</span></td>
                        <td style="text-align: center;">{{ $u['ultima_sesion'] ? $u['ultima_sesion']->format('d/m/Y H:i') : '—' }}</td>
                        <td style="text-align: center;">{{ $u['ultimo_logout'] ? $u['ultimo_logout']->format('d/m/Y H:i') : '—' }}</td>
                        <td style="text-align: center;">{{ $u['dur_promedio'] }}</td>
                        <td style="text-align: center;">
                            @if($u['activo_hoy'])
                                <span class="badge badge-verde">Sí</span>
                            @else
                                <span class="badge badge-rojo">No</span>
                            @endif
                            <!-- <span class="badge {{ $u['activo_hoy'] ? 'b-si' : 'b-no' }}">{{ $u['activo_hoy'] ? 'Sí' : 'No' }}</span> -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="report-footer">
            Seguimiento a las observaciones de fiscalización &nbsp;·&nbsp; {{ now()->format('d/m/Y H:i') }} &nbsp;·&nbsp; Generado automáticamente
        </div>
    </div>
@endsection
