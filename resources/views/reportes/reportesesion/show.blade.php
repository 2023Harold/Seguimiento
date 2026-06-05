@extends('layouts.app')

@section('breadcrums')
    {{Breadcrumbs::render('reportesesion.show', $usuario) }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title w-100">
                    <div class="row w-100 align-items-center">
                        <div class="col-md-8">
                            <a href="{{ route('reportesesion.index') }}">
                                <i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i>
                            </a>&nbsp;
                            Historial de Sesiones &mdash; <strong>{{ $usuario->name }}</strong>
                        </div>
                        {{-- ── Botones de exportación ── --}}
                        <div class="col-md-4 text-right">
                            <a href="{{ route('reportesesion.exportPdfShow', ['id' => $usuario->id] + request()->query()) }}"
                            target="_blank"
                            class="btn btn-export btn-pdf m-2 float-end text-white"
                            title="Exportar a PDF (se abre en pestaña nueva para imprimir)">
                                <i class="fas fa-file-pdf text-white"></i> PDF
                            </a>

                            <a href="{{ route('reportesesion.exportExcelShow', ['id' => $usuario->id] + request()->query()) }}"
                            class="btn btn-export btn-excel float-end m-2 text-white"
                            title="Exportar a Excel" data-no-spinner>
                                <i class="fas fa-file-excel text-white"></i> Excel
                            </a>
                        </div>
                    </div>
                </h1>
            </div>

            <div class="card-body">

                {{-- ── KPIs del usuario ────────────────────────────────────── --}}
                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="card shadow-sm">
                            <div class="card-body audit-card fancy-border">
                                <div class="text-muted font-weight-bold text-uppercase mb-1">Total de Sesiones</div>
                                <div class="h3 mb-0" style="color:#022626;">{{ $stats['total'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="card shadow-sm">
                            <div class="card-body audit-card fancy-border">
                                <div class="text-muted font-weight-bold text-uppercase mb-1">Duración Promedio</div>
                                <div class="h3 mb-0" style="color:#3c5c14; font-size:1.3rem;">{{ $stats['dur_promedio'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="card shadow-sm">
                            <div class="card-body audit-card fancy-border">
                                <div class="text-muted font-weight-bold text-uppercase mb-1">Última Conexión</div>
                                <div class="h3 mb-0" style="color:#90144a; font-size:1.1rem;">
                                    {{ $stats['ultima'] ? $stats['ultima']->format('d/m/Y H:i') : 'Sin registro' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Tabla de sesiones ────────────────────────────────────── --}}
                <div class="card fancy-border">
                    <div class="card-header">
                        <h1 class="card-title w-100 mb-0">
                            <i class="fas fa-history text-primary fa-1x m-2"></i> Historial Completo
                        </h1>
                    </div>
                    <div class="card-body">
                        <div class="card fancy-border mb-4">
                            <div class="card-header">
                                <h1 class="card-title w-100 mb-0">
                                    <i class="fas fa-filter text-primary"></i> Filtros
                                </h1>
                            </div>
                            <div class="card-body">
                                {!!BootForm::open(['route' => ['reportesesion.show', $usuario->id],'method' => 'GET' ]) !!}

                                <div class="row align-items-end">

                                    <div class="col-md-3">
                                        {!!BootForm::date('fecha_inicio', 'Fecha Inicio:', request('fecha_inicio')) !!}
                                    </div>

                                    <div class="col-md-3">
                                        {!!BootForm::date('fecha_fin', 'Fecha Fin:', request('fecha_fin')) !!}
                                    </div>

                                    <div class="col-md-3">
                                        {!!BootForm::select('tipo_cierre', 'Tipo de cierre:', [
                                            '' => '-- Todos --',
                                            'manual' => 'Manual',
                                            'expired' => 'Expiró',
                                            'unknown' => 'Desconocido'
                                        ], request('tipo_cierre')) !!}
                                    </div>

                                    <div class="col-md-3 mt-2">
                                        <button class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filtrar
                                        </button>

                                        <a href="{{ route('reportesesion.show', $usuario->id) }}" class="btn btn-secondary">
                                            <i class="fas fa-redo"></i> Limpiar
                                        </a>
                                    </div>

                                </div>

                                {!!BootForm::close() !!}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead style="background-color:#ece6e0;">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha Ingreso y Hora</th>
                                        <th>Fecha Cierre y Hora</th>
                                        <th class="text-center">Duración</th>
                                        {{-- <th>IP</th> --}}
                                        <th class="text-center">Tipo de Cierre</th>
                                        <th class="text-center">Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sesiones as $i => $s)
                                    @php
                                        // Determinamos si el registro está incompleto (unknown o sin logout)
                                        $esUnknown   = $s->logout_type === 'unknown';
                                        $sinCierre   = is_null($s->logout_at);
                                        $incompleto  = $esUnknown || ($sinCierre && is_null($s->duration_seconds));
                                    @endphp
                                    <tr class="{{ $incompleto ? 'table-warning' : '' }}">
                                        <td>{{ ($sesiones->currentPage() - 1) * $sesiones->perPage() + $i + 1 }}</td>

                                        {{-- Ingreso: siempre disponible --}}
                                        <td>{{ $s->login_at->format('d/m/Y H:i:s') }}</td>

                                        {{-- Cierre --}}
                                        <td>
                                            @if($incompleto)
                                                <span class="text-dark"><i class="fas fa-question-circle text-warning"></i> Se desconoce</span>
                                            @else
                                                {{ $s->logout_at ? $s->logout_at->format('d/m/Y H:i:s') : '—' }}
                                            @endif
                                        </td>

                                        {{-- Duración --}}
                                        <td class="text-center">
                                            @if($incompleto)
                                                <span class="text-muted">—</span>
                                            @else
                                                {{ $s->duration_formatted }}
                                            @endif
                                        </td>
                                        {{-- IP --}}
                                        {{-- <td><small class="text-muted">{{ $s->ip_address ?? '—' }}</small></td> --}}
                                        {{-- Tipo de cierre --}}
                                        <td class="text-center">
                                            @if($esUnknown)
                                                <span class="badge badge-amarillo text-dark">
                                                    <i class="fas fa-question m-1 text-dark"></i> Desconocido
                                                </span>
                                            @elseif($s->logout_type === 'manual')
                                                <span class="badge" style="background-color:#022626; color:#fff;">
                                                    <i class="fas fa-sign-out-alt m-1 text-white"></i> Manual
                                                </span>
                                            @elseif($s->logout_type === 'expired' || $s->logout_type === 'timeout')
                                                <span class="badge" style="background-color:#90144a; color:#fff;">
                                                    <i class="fas fa-clock m-1 text-white"></i> Expiró
                                                </span>
                                            @elseif($s->logout_type)
                                                <span class="badge badge-amarillo">{{ $s->logout_type }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Estado del registro --}}
                                        <td class="text-center">
                                            @if($incompleto)
                                                <span class="badge badge-amarillo text-dark"
                                                      title="Solo se registró el ingreso. El cierre y duración se desconocen.">
                                                    <i class="fas fa-exclamation-triangle m-1 text-dark"></i> Incompleto
                                                </span>
                                            @else
                                                <span class="badge" style="background-color:#3c5c14; color:#fff;">
                                                    <i class="fas fa-check m-1 text-white"></i> Completo
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Leyenda registros incompletos --}}
                        {{-- <div class="alert alert-warning mt-3 mb-0 py-2" style="font-size:0.85rem;">
                            <i class="fas fa-info-circle"></i>
                            Las filas marcadas en amarillo son registros <strong>incompletos</strong>:
                            el sistema detectó el ingreso pero no pudo determinar el cierre de sesión
                            (tipo <code>unknown</code>). Se conserva la fecha y hora de ingreso como único dato disponible.
                        </div> --}}

                        <div class="mt-3">
                            {{ $sesiones->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
