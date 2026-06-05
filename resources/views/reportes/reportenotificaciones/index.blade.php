@extends('layouts.app')

@section('breadcrums')
    {{Breadcrumbs::render('reportenotificaciones.index') }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title w-100">
                    <div class="row w-100 align-items-center">
                        <div class="col-md-8">
                            <a href="{{ route('home') }}">
                                <i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i>
                            </a>&nbsp;
                            Reporte de Notificaciones Enviadas
                        </div>
                        {{-- ── Botones de exportación ── --}}
                        <div class="col-md-4 text-right">
                            <a href="{{ route('reportenotificaciones.exportPdf', request()->query()) }}"
                            target="_blank"
                            class="btn btn-export btn-pdf m-2 float-end text-white"
                            title="Exportar a PDF (se abre en pestaña nueva para imprimir)">
                                <i class="fas fa-file-pdf text-white"></i> PDF
                            </a>

                            <a href="{{ route('reportenotificaciones.exportExcel', request()->query()) }}"
                            class="btn btn-export btn-excel float-end m-2 text-white"
                            title="Exportar a Excel" data-no-spinner>
                                <i class="fas fa-file-excel text-white"></i> Excel
                            </a>
                        </div>
                    </div>
                </h1>
            </div>

            <div class="card-body">

                {{-- ── KPIs ── --}}
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card h-30 shadow-sm">
                            <div class="card-body audit-card fancy-border">
                                <div class="text-muted font-weight-bold text-uppercase mb-1">Total de Notificaciones</div>
                                <div class="h3 mb-0" style="color:#022626;">{{ $totalNotificaciones }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-30 shadow-sm">
                            <div class="card-body audit-card fancy-border">
                                <div class="text-muted font-weight-bold text-uppercase mb-1">Notificaciones Leídas</div>
                                <div class="h3 mb-0" style="color:#3c5c14;">
                                    {{ $notificacionesLeidas }}
                                    <small class="text-muted" style="font-size:0.6em;">({{ $porcentajeLeidas }}%)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-30 shadow-sm">
                            <div class="card-body audit-card fancy-border">
                                <div class="text-muted font-weight-bold text-uppercase mb-1">Pendientes de Leer</div>
                                <div class="h3 mb-0" style="color:#960048;">{{ $notificacionesNoLeidas }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-30 shadow-sm">
                            <div class="card-body audit-card fancy-border">
                                <div class="text-muted font-weight-bold text-uppercase mb-1">Tasa de Lectura</div>
                                <div class="h3 mb-0" style="color:#90144a;">{{ $porcentajeLeidas }}%</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Filtros ── --}}
                <div class="card fancy-border mb-4">
                    <div class="card-header">
                        <h1 class="card-title w-100 mb-0">
                            <i class="fas fa-filter text-primary fa-1x"></i> Filtros
                        </h1>
                    </div>
                    <div class="card-body">
                        {!!BootForm::open(['route' => 'reportenotificaciones.index', 'method' => 'GET']) !!}
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                {!!BootForm::date('fecha_inicio', 'Fecha Inicio:', old('fecha_inicio', request('fecha_inicio'))) !!}
                            </div>
                            <div class="col-md-3">
                                {!!BootForm::date('fecha_fin', 'Fecha Fin:', old('fecha_fin', request('fecha_fin'))) !!}
                            </div>
                            <div class="col-md-3">
                                {!!BootForm::select('tipo_destinatario', 'Tipo:', [
                                    ''           => '-- Todos --',
                                    'individual' => 'Individual',
                                    'equipo'     => 'Equipo',
                                ], old('tipo_destinatario', request('tipo_destinatario'))) !!}
                            </div>
                            <div class="col-md-3 mt-2">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                                <a href="{{ route('reportenotificaciones.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Limpiar
                                </a>
                            </div>
                        </div>
                        {!!BootForm::close() !!}
                    </div>
                </div>

                {{-- ── Gráficas ── --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card fancy-border mb-4">
                            <div class="card-header">
                                <h1 class="card-title w-100 mb-0">
                                    <i class="fas fa-chart-pie text-primary fa-1x m-2"></i> Estatus de Lectura
                                </h1>
                            </div>
                            <div class="card-body">
                                <div id="chartEstatus" style="height:320px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card fancy-border mb-4">
                            <div class="card-header">
                                <h1 class="card-title w-100 mb-0">
                                    <i class="fas fa-chart-bar text-primary fa-1x m-2"></i> Tipo de Destinatario
                                </h1>
                            </div>
                            <div class="card-body">
                                <div id="chartDestinatario" style="height:320px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card fancy-border mb-4">
                            <div class="card-header">
                                <h1 class="card-title w-100 mb-0">
                                    <i class="fas fa-chart-line text-primary fa-1x m-2"></i> Notificaciones por Fecha (Últimos 30 días)
                                </h1>
                            </div>
                            <div class="card-body">
                                <div id="chartFecha" style="height:320px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Tabla ── --}}
                <div class="card fancy-border mb-4">
                    <div class="card-header">
                        <h1 class="card-title w-100 mb-0">
                            <i class="fas fa-table text-primary fa-1x m-2"></i> Detalle de Notificaciones
                        </h1>
                    </div>
                    <div class="card-body">
                        @if($notificaciones->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="tablaNotificaciones">
                                    <thead style="background-color:#ece6e0;">
                                        <tr>
                                            <th>Título</th>
                                            <th>Destinatario</th>
                                            <th>Tipo</th>
                                            <th>Estatus</th>
                                            <th>Fecha Envío</th>
                                            <th>Fecha Lectura</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($notificaciones as $notificacion)
                                            <tr>
                                                <td>
                                                    <strong class="text-primary">{{ Str::limit($notificacion->titulo, 100) }}</strong><br>
                                                    @php $partes = explode('<br>', $notificacion->mensaje); @endphp
                                                    <strong>{{ $partes[1] ?? $partes[0] }}</strong>
                                                </td>
                                                <td>
                                                    @if($notificacion->destinatario_id)
                                                        <span class="badge badge-gris">
                                                            {{ $notificacion->usuarioDestinatario?->name ?? 'Sin nombre' }}
                                                        </span>
                                                    @elseif($notificacion->equipo_id)
                                                        <span class="badge badge-verde">
                                                            {{ $notificacion->destinatario ?? 'General' }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($notificacion->destinatario_id && !$notificacion->equipo_id)
                                                        <span class="badge badge-primary">Individual</span>
                                                    @elseif($notificacion->equipo_id)
                                                        <span class="badge badge-amarillo text-dark">Equipo</span>
                                                    @else
                                                        <span class="badge badge-secondary">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($notificacion->estatus !== 'Pendiente')
                                                        <span class="badge badge-verdeclaro">
                                                            <i class="fas fa-check-circle text-white"></i> Leída
                                                        </span>
                                                    @else
                                                        <span class="badge badge-rojo">
                                                            <i class="fas fa-clock text-white"></i> Pendiente
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $notificacion->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="text-center">
                                                    @if($notificacion->estatus !== 'Pendiente')
                                                        {{ $notificacion->updated_at->format('d/m/Y H:i') }}
                                                    @else
                                                        <small class="text-muted">No leída aún</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('reportenotificaciones.show', $notificacion->id) }}"
                                                       class="corner-button-cafe" title="Ver detalle">
                                                        <span class="cb-content text-cafe">
                                                            <i class="fas fa-eye text-cafe"></i>
                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No hay notificaciones que coincidan con los filtros aplicados.
                            </div>
                        @endif
                    </div>
                </div>

            </div>{{-- /card-body --}}
        </div>{{-- /card --}}
    </div>
</div>
@endsection

@section('script')

<script src="{{ asset('vendor/highcharts/highcharts.js') }}"></script>
<script src="{{ asset('vendor/highcharts/highcharts-more.js') }}"></script>
<script src="{{ asset('vendor/highcharts/modules/accessibility.js') }}"></script>
<script src="{{ asset('vendor/highcharts/modules/exporting.js') }}"></script>
<script src="{{ asset('vendor/highcharts/modules/export-data.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const responsiveRules = {
        rules: [{
            condition: { maxWidth: 500 },
            chartOptions: {
                legend: { layout: 'horizontal', align: 'center', verticalAlign: 'bottom' },
                xAxis:  { labels: { style: { fontSize: '12px' }, rotation: -30 } },
                yAxis:  { labels: { style: { fontSize: '12px' } } },
                plotOptions: {
                    pie:    { dataLabels: { style: { fontSize: '12px' } } },
                    column: { dataLabels: { style: { fontSize: '12px' } } },
                    line:   { dataLabels: { style: { fontSize: '12px' } } },
                },
                tooltip: { style: { fontSize: '11px' } }
            }
        }, {
            condition: { minWidth: 501, maxWidth: 768 },
            chartOptions: {
                xAxis:  { labels: { style: { fontSize: '14px' }, rotation: -35 } },
                yAxis:  { labels: { style: { fontSize: '14px' } } },
                plotOptions: {
                    pie:    { dataLabels: { style: { fontSize: '14px' } } },
                    column: { dataLabels: { style: { fontSize: '14px' } } },
                },
                tooltip: { style: { fontSize: '13px' } }
            }
        }, {
            condition: { minWidth: 769 },
            chartOptions: {
                xAxis:  { labels: { style: { fontSize: '16px' }, rotation: -45 } },
                yAxis:  { labels: { style: { fontSize: '16px' } } },
                plotOptions: {
                    pie:    { dataLabels: { style: { fontSize: '16px' } } },
                    column: { dataLabels: { style: { fontSize: '16px' } } },
                },
                tooltip: { style: { fontSize: '15px' } }
            }
        }]
    };

    // ── Pastel: Leídas vs Pendientes ──────────────────────────────
    Highcharts.chart('chartEstatus', {
        chart: { type: 'pie' },
        title: { text: null },
        accessibility: { enabled: false },
        exporting: { enabled: false },   // ← quita el menú hamburguesa
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b style="font-size:16px">{point.name}</b><br>{point.y} ({point.percentage:.1f}%)',
                    distance: 20,
                    connectorPadding: 5,
                }
            }
        },
        series: [{
            name: 'Cantidad',
            colorByPoint: true,
            data: [
                { name: 'Leídas',     y: {{ $notificacionesLeidas }},  color: '#3c5c14' },
                { name: 'Pendientes', y: {{ $notificacionesNoLeidas }}, color: '#960048' }
            ]
        }],
        credits: { enabled: false },
        responsive: responsiveRules
    });

    // ── Barras: Individual vs Equipo ──────────────────────────────
    Highcharts.chart('chartDestinatario', {
        chart: { type: 'column' },
        title: { text: null },
        accessibility: { enabled: false },
        exporting: { enabled: false },   // ← quita el menú hamburguesa
        xAxis: {
            categories: ['Individual', 'Equipo'],
            crosshair: true,
            labels: { style: { fontSize: '16px' } },
            title: { text: null }
        },
        yAxis: {
            min: 0,
            allowDecimals: false,
            title: { text: 'Cantidad', style: { fontSize: '12px' } },
            labels: { style: { fontSize: '14px' } }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    style: { fontSize: '16px', fontWeight: 'bold' }
                }
            }
        },
        series: [{
            name: 'Notificaciones',
            colorByPoint: true,
            data: [
                { y: {{ $notificacionesIndividuales }}, color: '#90144a' },
                { y: {{ $notificacionesEquipo }},       color: '#022626' }
            ]
        }],
        credits: { enabled: false },
        responsive: responsiveRules
    });

    // ── Línea: rango de fechas activo ─────────────────────────────
    Highcharts.chart('chartFecha', {
        chart: { type: 'line', reflow: true },
        title: { text: null },
        accessibility: { enabled: false },
        exporting: { enabled: false },   // ← quita el menú hamburguesa
        xAxis: {
            categories: {!! json_encode($fechasData) !!},
            crosshair: true,
            labels: {
                rotation: -45,
                style: { fontSize: '14px' },
                step: {{ count($fechasData) > 15 ? 2 : 1 }}
            }
        },
        yAxis: {
            min: 0,
            allowDecimals: false,
            title: { text: 'Cantidad', style: { fontSize: '12px' } },
            labels: { style: { fontSize: '14px' } }
        },
        legend: {
            enabled: true,
            itemStyle: { fontSize: '16px' }
        },
        plotOptions: {
            line: {
                marker: { enabled: true, radius: 4 },
                dataLabels: { enabled: false }
            }
        },
        series: [
            {
                name: 'Total Enviadas',
                data: {!! json_encode($totalesData) !!},
                color: '#90144a',
                lineWidth: 2
            },
            {
                name: 'Leídas',
                data: {!! json_encode($leidasData) !!},
                color: '#3c5c14',
                lineWidth: 2
            }
        ],
        credits: { enabled: false },
        responsive: responsiveRules
    });

    // ── DataTable ─────────────────────────────────────────────────
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#tablaNotificaciones').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json',
                sProcessing: "Procesando...",
                sLengthMenu: "Mostrar _MENU_ registros",
                sZeroRecords: "No se encontraron resultados",
                sEmptyTable: "Ningún dato disponible en esta tabla",
                sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                sSearch: "Buscar:",
                oPaginate: {
                    sFirst: "Primero",
                    sLast: "Último",
                    sNext: "Siguiente",
                    sPrevious: "Anterior"
                },
            },
            paging:     true,
            pageLength: 15,          // últimas 15 por defecto
            lengthMenu: [15, 25, 50, 100, 250, 500, 1000],
            searching:  true,

            ordering:   true,
            info:       true,
            order:      [[4, 'desc']] // ordenar por Fecha Envío DESC
        });
    }

});
</script>
@endsection
