@extends('layouts.app')

@section('breadcrums')
    {{Breadcrumbs::render('reportesesion.index') }}
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
                            Reporte de Sesiones por Usuario
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{ route('reportesesion.exportPdf', request()->query()) }}" target="_blank" class="btn btn-export btn-pdf m-2 float-end text-white" title="Exportar a PDF (se abre en pestaña nueva para imprimir)">
                                <i class="fas fa-file-pdf text-white"></i> PDF
                            </a>
                            <a href="{{ route('reportesesion.exportExcel', request()->query()) }}"class="btn btn-export btn-excel float-end m-2 text-white"title="Exportar a Excel" data-no-spinner>
                                <i class="fas fa-file-excel text-white"></i> Excel
                            </a>
                        </div>

                    </div>
                </h1>
            </div>
            <div class="card-body">
                {{-- ── KPIs ─────────────────────────────────────────────────── --}}
                <div class="row mb-4">
                    <div class="col-6 col-md-3 mb-2">
                        <div class="card shadow-sm h-100">
                            <div class="card-body audit-card fancy-border">
                                <div class="text-muted font-weight-bold text-uppercase mb-1">Usuarios Activos</div>
                                <div class="h3 mb-0" style="color:#022626;">{{ $totalUsuarios }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="card shadow-sm h-100">
                            <div class="card-body audit-card fancy-border">
                                <div class="text-muted font-weight-bold text-uppercase mb-1">Con Sesión en Período/Mes</div>
                                <div class="h3 mb-0" style="color:#3c5c14;">{{ $usuariosConSesion }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="card shadow-sm h-100">
                            <div class="card-body audit-card fancy-border">
                                <div class="text-muted font-weight-bold text-uppercase mb-1">Sin Actividad</div>
                                <div class="h3 mb-0" style="color:#960048;">{{ $usuariosSinSesion }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="card shadow-sm h-100">
                            <div class="card-body audit-card fancy-border">
                                <div class="text-muted font-weight-bold text-uppercase mb-1">Duración Prom. Sesión</div>
                                <div class="h3 mb-0" style="color:#90144a; font-size:1.15rem;">{{ $duracionPromedioFmt }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Filtros ──────────────────────────────────────────────── --}}
                <div class="card fancy-border mb-4">
                    <div class="card-header">
                        <h1 class="card-title w-100 mb-0">
                            <i class="fas fa-filter text-primary"></i> Filtros
                        </h1>
                    </div>
                    <div class="card-body">
                        {!!BootForm::open(['route' => 'reportesesion.index', 'method' => 'GET']) !!}

                        {{-- Fila 1: período --}}
                        <div class="row align-items-end mb-2">
                            <div class="col-6">
                                <h4 class="font-weight-bold text-uppercase" style="color:#022626;">
                                    <i class="fas fa-calendar-alt fa-1x" style="color:#022626;"></i> Por rango de fechas
                                </h4>
                            </div>
                            <div class="col-6">
                                <h4 class="font-weight-bold text-uppercase" style="color:#022626;">
                                    <h4 class="d-block mb-1" style="color:#022626;">— o por mes y año —</h4>
                                </h4>
                            </div>
                            <div class="col-md-3">
                                {!!BootForm::date('fecha_inicio', 'Fecha Inicio:', old('fecha_inicio', request('fecha_inicio'))) !!}
                            </div>
                            <div class="col-md-3">
                                {!!BootForm::date('fecha_fin', 'Fecha Fin:', old('fecha_fin', request('fecha_fin'))) !!}
                            </div>
                            <div class="col-3">
                                {!!BootForm::select('mes', 'Mes:', ['' => '-- Mes --'] + $meses, old('mes', request('mes'))) !!}
                            </div>
                            <div class="col-3">
                                {!!BootForm::select('anio', 'Año:', ['' => '-- Año --'] + array_combine($anios, $anios), old('anio', request('anio'))) !!}
                            </div>
                        </div>

                        {{-- Fila 2: filtros de usuario --}}
                        <div class="row align-items-end mt-10">
                            <div class="col-12 mb-1">
                                <h4 class="font-weight-bold text-uppercase" style="color:#022626;">
                                    <i class="fas fa-users fa-1x" style="color:#022626;"></i> Por usuario
                                </h4>
                            </div>

                            {{-- Dirección: Titular, ATI, UC --}}
                            @if(in_array($yo->siglas_rol, ['TUS', 'ATI', 'ADMIN', 'UC']))
                            <div class="col-md-2">
                                {!!BootForm::select('direccion_id', 'Dirección:', ['' => '-- Todas --'] + $direcciones->toArray(), old('direccion_id', request('direccion_id'))) !!}
                            </div>
                            @endif

                            {{-- Departamento: Titular, ATI, UC, DS --}}
                            @if(in_array($yo->siglas_rol, ['TUS', 'ATI', 'ADMIN', 'UC', 'DS']) && $departamentos->count())
                            <div class="col-md-2">
                                {!!BootForm::select('departamento_id', 'Departamento:', ['' => '-- Todos --'] + $departamentos->toArray(), old('departamento_id', request('departamento_id'))) !!}
                            </div>
                            @endif

                            <div class="col-md-2">
                                {!!BootForm::select('siglas_rol', 'Rol:', ['' => '-- Todos --'] + $rolesDisponibles, old('siglas_rol', request('siglas_rol'))) !!}
                            </div>
                            <div class="col-md-3">
                                {!!BootForm::text('nombre', 'Nombre:', old('nombre', request('nombre'))) !!}
                            </div>
                            <div class="col-md-2 mt-2">
                                <button type="submit" class="btn btn-primary btn-block mb-1">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                                <a href="{{ route('reportesesion.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-redo"></i> Limpiar
                                </a>
                            </div>
                        </div>

                        {!!BootForm::close() !!}
                    </div>
                </div>

                @if($sinDatos)
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                            No hay registros en el período seleccionado
                        <br>
                        <small>{{ $fechaInicio->format('d/m/Y') }} — {{ $fechaFin->format('d/m/Y') }}</small>
                    </div>
                @endif
                @if(!$sinDatos)
                    {{-- ── Gráficas fila 1: distribución de usuarios + actividad hoy ── --}}
                    <div class="row mb-4">

                        {{-- Gráfica 1: cuántos usuarios hay por rol --}}
                        <div class="col-md-4 mb-3">
                            <div class="card fancy-border h-100">
                                <div class="card-header">
                                    <h1 class="card-title w-100 mb-0">
                                        <i class="fas fa-users text-primary fa-1x m-2"></i> Usuarios por Rol
                                    </h1>
                                </div>
                                <div class="card-body">
                                    <div id="chartRol" style="height:280px;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Gráfica 2: de esos usuarios, cuántos activos hoy --}}
                        <div class="col-md-4 mb-3">
                            <div class="card fancy-border h-100">
                                <div class="card-header">
                                    <h1 class="card-title w-100 mb-0">
                                        <i class="fas fa-user-clock text-primary fa-1x m-2"></i> Actividad Periodo
                                        <small> {{ $fechaInicio->format('d/m/Y') }} — {{ $fechaFin->format('d/m/Y') }} </small>
                                        {{-- <small class="m-5 mt-5" style="font-size:0.55em; color:#022626;">{{ now()->format('d/m/Y') }}</small> --}}
                                    </h1>
                                </div>
                                <div class="card-body">
                                    <div id="chartHoy" style="height:280px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card fancy-border">
                                <div class="card-header">
                                    <h1 class="card-title">
                                        Comparativo Semanal
                                    </h1>
                                </div>
                                <div class="card-body">
                                    <div id="chartComparativo" style="height:280px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ── Gráfica 3: sesiones por día (ancho completo) ─────────── --}}
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card fancy-border">
                                <div class="card-header">
                                    <h1 class="card-title w-100 mb-0">
                                        <i class="fas fa-chart-line text-primary fa-1x m-2"></i> Sesiones por Día
                                        <small class="m-5 mt-5 ml-1" style="font-size:0.55em; color:#022626;">
                                            {{ $fechaInicio->format('d/m/Y') }} — {{ $fechaFin->format('d/m/Y') }}
                                        </small>
                                    </h1>
                                </div>
                                <div class="card-body">
                                    <div id="chartFecha" style="height:260px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Tabla resumen por usuario ────────────────────────────── --}}
                    <div class="card fancy-border mb-4">
                        <div class="card-header">
                            <h1 class="card-title w-100 mb-0">
                                <i class="fas fa-table text-primary fa-1x m-2"></i> Actividad por Usuario
                                <small class="m-5 mt-5 ml-2" style="font-size:0.55em; color:#022626;">
                                    {{ $fechaInicio->format('d/m/Y') }} — {{ $fechaFin->format('d/m/Y') }}
                                    &nbsp;·&nbsp; {{ $totalUsuarios }} usuario(s)
                                </small>
                            </h1>
                        </div>
                        <div class="card-body">
                            @if($resumenUsuarios->count())
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="tablaUsuarios">
                                    <thead style="background-color:#ece6e0;">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Rol</th>
                                            <th>Unidad</th>
                                            <th class="text-center">Sesiones</th>
                                            <th class="text-center">Últ. Ingreso</th>
                                            <th class="text-center">Últ. Cierre</th>
                                            <th class="text-center">Dur. Prom.</th>
                                            <th class="text-center">Hoy</th>
                                            <th class="text-center">Ver</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($resumenUsuarios as $u)
                                        @php
                                            $rolConfig = [
                                                'DS'    => ['Director',            '#022626'],
                                                'UC'    => ['Usr. Consulta',       '#457b9d'],
                                                'JD'    => ['Jefe Depto.',         '#90144a'],
                                                'LP'    => ['Líder',               '#3c5c14'],
                                                'ANA'   => ['Analista',            '#960048'],
                                                'STAFF' => ['Staff',               '#6d4c41'],
                                                'ATUS' => ['Asistente de Titular', '#ab7f6e'],
                                            ];
                                            $rc  = $rolConfig[$u['siglas_rol']] ?? [$u['siglas_rol'], '#aaa'];
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $u['name'] }}</strong></td>
                                            <td>
                                                <span class="badge" style="background-color:{{ $rc[1] }}; color:#fff;">
                                                    {{ $rc[0] }}
                                                </span>
                                            </td>
                                            <td><small>{{ $unidades[$u['ua_id']] ?? '—' }}</small></td>
                                            <td class="text-center">
                                                @if($u['total_sesiones'] > 0)
                                                    <span class="badge badge-morado">{{ $u['total_sesiones'] }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ $u['ultima_sesion'] ? $u['ultima_sesion']->format('d/m/Y H:i') : '—' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $u['ultimo_logout'] ? $u['ultimo_logout']->format('d/m/Y H:i') : '—' }}
                                            </td>
                                            <td class="text-center"><small>{{ $u['dur_promedio'] }}</small></td>
                                            <td class="text-center">
                                                @if($u['activo_hoy'])
                                                    <span class="badge" style="background-color:#3c5c14;color:#fff;">
                                                        <i class="fas fa-circle text-success"></i> Sí
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">No</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('reportesesion.show', $u['id']) }}"
                                                class="corner-button-cafe" title="Ver historial">
                                                    <span class="cb-content">
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
                                    No hay usuarios que coincidan con los filtros aplicados.
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>{{-- /card-body --}}
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('vendor/highcharts/highcharts.js') }}"></script>
<script src="{{ asset('vendor/highcharts/highcharts-more.js') }}"></script>
<script src="{{ asset('vendor/highcharts/modules/accessibility.js') }}"></script>

{{-- DataTable --}}
<script>
$(document).ready(function () {
    var $t = $('#tablaUsuarios');
    if ($t.length && typeof $.fn.DataTable !== 'undefined') {
        if ($.fn.DataTable.isDataTable($t)) { $t.DataTable().destroy(); }
        $t.DataTable({
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
            paging: true, pageLength: 10, lengthMenu: [10, 25, 50],
            searching: true, ordering: true, info: true,
            order: [[4, 'desc']]
        });
    }
});
</script>

{{-- Highcharts --}}
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

    var palette = ['#022626','#90144a','#3c5c14','#960048','#457b9d','#6d4c41','#ab7f6e'];

    var rolLabels = {'DS':'Director', 'UC':'Usr. Consulta', 'JD':'Jefe Depto.','LP':'Líder', 'ANA':'Analista', 'STAFF':'Staff', 'ATUS':'Asistente Titular'};
    var rolColores = {'DS':'#022626', 'UC':'#457b9d', 'JD':'#90144a',
        'LP':'#3c5c14', 'ANA':'#960048', 'STAFF':'#6d4c41', 'ATUS':'#ab7f6e'
    };

    // ── Gráfica 1: usuarios por rol (columnas) ────────────────────
    var rolCats = [], rolData = [];
    @foreach($usuariosPorRol as $rol => $cantidad)
        rolCats.push(rolLabels['{{ $rol }}'] || '{{ $rol }}');
        rolData.push({ y: {{ $cantidad }}, color: rolColores['{{ $rol }}'] || '#aaa' });
    @endforeach

    Highcharts.chart('chartRol', {
        chart: { type: 'column' },
        title: { text: null },
        accessibility: { enabled: false },
        exporting: { enabled: false },
        xAxis: {
            categories: rolCats,
            labels: { style: { fontSize: '13px' } }
        },
        yAxis: {
            min: 0, allowDecimals: false,
            title: { text: 'Usuarios', style: { fontSize: '11px' } },
            labels: { style: { fontSize: '12px' } }
        },
        plotOptions: {
            column: {
                borderWidth: 0,
                dataLabels: { enabled: true, style: { fontSize: '14px', fontWeight: 'bold' } }
            }
        },
        series: [{ name: 'Usuarios', colorByPoint: true, data: rolData, showInLegend: false }],
        credits: { enabled: false },
        responsive: responsiveRules
    });

    // ── Gráfica 2: activos hoy vs inactivos (dona) ────────────────
    Highcharts.chart('chartHoy', {
        chart: { type: 'pie' },
        title: { text: null },
        accessibility: { enabled: false },
        exporting: { enabled: false },
        plotOptions: {
            pie: {
                innerSize: '55%',
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b><br>{point.y} ({point.percentage:.1f}%)',
                    style: { fontSize: '13px' }
                }
            }
        },
        series: [{
            name: 'Usuarios',
            colorByPoint: true,
            data: [
                    { name: 'Con actividad',  y: {{ $activosPeriodo }},   color: '#3c5c14' },
                    { name: 'Sin actividad',  y: {{ $inactivosPeriodo }}, color: '#960048' }
                ]

            /* data: [
                { name: 'Con sesión hoy',  y: {{ $activosHoy }},   color: '#3c5c14' },
                { name: 'Sin sesión hoy',  y: {{ $inactivosHoy }}, color: '#960048' }
            ] */
        }],
        credits: { enabled: false },
        responsive: responsiveRules
    });

    // ── Gráfica 3: sesiones por día (área) ────────────────────────
    Highcharts.chart('chartFecha', {
        chart: { type: 'area', reflow: true },
        title: { text: null },
        accessibility: { enabled: false },
        exporting: { enabled: false },
        xAxis: {
            /* categories: {!! json_encode($fechasGrafica) !!}, */
            categories: {!! json_encode(count($fechasGrafica) ? $fechasGrafica : ['Sin datos']) !!},
            crosshair: true,
            labels: {
                rotation: -45, style: { fontSize: '12px' },
                step: {{ count($fechasGrafica) > 20 ? 2 : 1 }}
            }
        },
        yAxis: {
            min: 0, allowDecimals: false,
            title: { text: 'Num. Sesiones', style: { fontSize: '12px' } },
            labels: { style: { fontSize: '12px' } }
        },
        plotOptions: {
            area: {
                fillOpacity: 0.2,
                marker: { enabled: true, radius: 3 },
                lineWidth: 2
            }
        },
        series: [{
            name: 'Sesiones',
            data: {!! json_encode(count($totalesGrafica) ? $totalesGrafica : [0]) !!},
            /* data: {!! json_encode($totalesGrafica) !!}, */
            color: '#90144a',
            fillColor: {
                linearGradient: { x1:0, y1:0, x2:0, y2:1 },
                stops: [
                    [0, 'rgba(144,20,74,0.35)'],
                    [1, 'rgba(144,20,74,0.02)']
                ]
            }
        }],
        credits: { enabled: false },
        responsive: {
            rules: [{
                condition: { maxWidth: 500 },
                chartOptions: { xAxis: { labels: { rotation: -30, style: { fontSize: '10px' } } } }
            }]
        }
    });

    //Gráfica 4: sesiones por tipo de cierre (pastel) ─────────────
     Highcharts.chart('chartComparativo', {
        chart: { type: 'column' },
        title: { text: null },
        xAxis: {
            categories: [
                'Semana pasada ({{ $labelSemanaAnterior }})',
                'Semana actual ({{ $labelSemanaActual }})'
            ]
        },
        yAxis: {
            title: { text: 'Sesiones' }
        },
        tooltip: {
            pointFormat: 'Sesiones: <b>{point.y}</b>'
        },
        series: [{
            name: 'Sesiones',
            data: [{{ $semanaAnterior }}, {{ $semanaActual }}],
            color: '#022626'
        }],
        exporting: { enabled: false },
        credits: { enabled: false },
        responsive: responsiveRules
    });
});
</script>
@endsection
