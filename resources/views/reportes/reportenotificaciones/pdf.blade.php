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
            <div class="card-ribbon">Reporte de Notificaciones</div>
            {{-- Botón solo visible en pantalla --}}

            {{-- ── Cabecera ── --}}
            <div class="report-header">
                <div>
                    <h1>Reporte de Notificaciones Enviadas</h1>
                    <div style="font-size:9pt; color:#555; margin-top:4px;">
                        Generado por: <strong>{{ Auth::user()->name }}</strong>
                    </div>
                </div>
                <div class="meta">
                    Fecha de generación:<br>
                    <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                </div>
            </div>
        </div>
        <div class="card fancy-border">
            <div class="card-ribbon">Filtros aplicados</div>
            {{-- ── Resumen de filtros aplicados ── --}}
            <div class="filtros-resumen">
                <strong>Período:</strong>
                {{ $fechaInicio->format('d/m/Y') }} — {{ $fechaFin->format('d/m/Y') }}
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <strong>Tipo:</strong>
                {{ request('tipo_destinatario') === 'individual' ? 'Individual' : (request('tipo_destinatario') ===
                'equipo' ? 'Equipo' : 'Todos') }}
            </div>
        </div>
        {{-- KPIs --}}
        {{-- ── KPIs ── --}}
        <div class="kpis ">
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Total</div>
                <div class="kpi-value" style="color:#022626;">{{ $totalNotificaciones }}</div>
            </div>
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Leídas</div>
                <div class="kpi-value" style="color:#3c5c14;">{{ $notificacionesLeidas }}</div>
            </div>
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Pendientes</div>
                <div class="kpi-value" style="color:#960048;">{{ $notificacionesNoLeidas }}</div>
            </div>
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Tasa lectura</div>
                <div class="kpi-value" style="color:#90144a;">{{ $porcentajeLeidas }}%</div>
            </div>
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Individuales</div>
                <div class="kpi-value" style="color:#90144a;">{{ $notificacionesIndividuales }}</div>
            </div>
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Equipo</div>
                <div class="kpi-value" style="color:#022626;">{{ $notificacionesEquipo }}</div>
            </div>
        </div>
        <div class="card fancy-border">
            <div class="card-ribbon">Detalle</div>
            {{-- ── Tabla detalle ── --}}
            @if($notificaciones->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Destinatario</th>
                        <th>Tipo</th>
                        <th>Estatus</th>
                        <th>Fecha Envío</th>
                        <th>Fecha Lectura</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notificaciones->sortByDesc('created_at') as $i => $notificacion)
                    <tr>
                        <td style="text-align: center;">{{ $i + 1 }}</td>
                        <td>
                            {{ Str::limit($notificacion->titulo, 60) }}<br>
                            @php $partes = explode('<br>', $notificacion->mensaje); @endphp
                            <small>{{ Str::limit($partes[1] ?? $partes[0], 80) }}</small>
                        </td>
                        <td >
                            @if($notificacion->destinatario_id)
                                {{ $notificacion->usuarioDestinatario?->name ?? 'Sin nombre' }}
                            @elseif($notificacion->equipo_id)
                                {{ $notificacion->destinatario ?? 'General' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($notificacion->destinatario_id && !$notificacion->equipo_id)
                            <span class="badge badge-individual">Individual</span>
                            @elseif($notificacion->equipo_id)
                            <span class="badge badge-equipo">Equipo</span>
                            @else
                            <span>N/A</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($notificacion->estatus !== 'Pendiente')
                            <span class="badge badge-leida">Leída</span>
                            @else
                            <span class="badge badge-pendiente">Pendiente</span>
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $notificacion->created_at->format('d/m/Y H:i') }}</td>
                        <td style="text-align: center;">
                            @if($notificacion->estatus !== 'Pendiente')
                            {{ $notificacion->updated_at->format('d/m/Y H:i') }}
                            @else
                            —
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="color:#777; font-style:italic;">No hay notificaciones que coincidan con los filtros aplicados.</p>
            @endif

            {{-- ── Pie ── --}}
            <div class="report-footer">
                Seguimiento a las observaciones de fiscalización &nbsp;·&nbsp; {{ now()->format('d/m/Y H:i') }}
                &nbsp;·&nbsp; Página generada automáticamente
            </div>
        </div>
    </div>
@endsection
