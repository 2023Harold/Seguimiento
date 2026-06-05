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
                    <h1>Historial de Sesiones</h1>
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
                {{-- <strong>Tipo:</strong>
                {{ request('tipo_cierre') ?? 'Todos' }} --}}
                <strong>Usuario:</strong> {{ $usuario->name }}
            </div>
        </div>
        {{-- KPIs --}}
        <div class="kpis ">
            <div class="kpi-box fancy-border">
                <div class="kpi-label">Total</div>
                <div class="kpi-value" style="color:#022626;">
                    {{ $stats['total'] }}
                </div>
            </div>

            <div class="kpi-box fancy-border">
                <div class="kpi-label">Promedio</div>
                <div class="kpi-value" style="color:#3c5c14;">
                    {{ $stats['dur_promedio'] }}
                </div>
            </div>

            <div class="kpi-box fancy-border">
                <div class="kpi-label">Última</div>
                <div class="kpi-value" style="color:#960048;">
                    {{ $stats['ultima'] ? $stats['ultima']->format('d/m/Y H:i') : '—' }}
                </div>
            </div>
        </div>
        <div class="card fancy-border">
            <div class="card-ribbon">Detalle</div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ingreso</th>
                        <th>Cierre</th>
                        <th>Duración</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($sesiones as $i => $s)
                        @php
                            $incompleto = $s->logout_type === 'unknown' || (!$s->logout_at && !$s->duration_seconds);
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
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
                                @if($incompleto)
                                    <span class="badge warn">Incompleto</span>
                                @else
                                    <span class="badge ok">Completo</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
