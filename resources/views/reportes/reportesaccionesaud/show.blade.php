@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('reporteauditoriaacciones.index') }}
@endsection
@section('content')
<style>
/* ===============================
   BENTO GRID BASE (DESKTOP)
================================ */
.bento-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    grid-auto-rows: minmax(180px, auto);
    gap: 20px;
}

/* ===============================
   CAJAS
================================ */
.bento-box {
    background: #fff;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,.08);
    display: flex;
    flex-direction: column;
}

.bento-box h5 {
    font-weight: 600;
    margin-bottom: 10px;
    line-height: 1.3;
}

/* ===============================
   TAMAÑOS DESKTOP
================================ */
.bento-hero {
    grid-column: span 12;
}

.bento-lg {
    grid-column: span 6;
    grid-row: span 2;
}

.bento-lgg {
    grid-column: span 12;
}

.bento-md {
    grid-column: span 4;
    grid-row: span 2;
}

.bento-sm {
    grid-column: span 3;
    grid-row: span 2;
}

.bento-smm {
    grid-column: span 2;
    grid-row: span 2;
}

/* ===============================
   TABLET (≤ 992px)
================================ */
@media (max-width: 992px) {

    .bento-grid {
        grid-template-columns: repeat(8, 1fr);
        gap: 16px;
    }

    .bento-hero,
    .bento-lg,
    .bento-md,
    .bento-sm,
    .bento-smm,
    .bento-lgg {
        grid-column: span 8;
        grid-row: auto;
    }

    .bento-box {
        padding: 18px;
    }
}

/* ===============================
   MOBILE REAL (≤ 576px)
================================ */
@media (max-width: 576px) {

    .bento-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .bento-box {
        padding: 14px;
        border-radius: 14px;
    }

    .bento-box h5 {
        font-size: 14px;
        margin-bottom: 6px;
        text-align: center;
        word-break: break-word;
        hyphens: auto;
    }

    .bento-hero,
    .bento-lg,
    .bento-md,
    .bento-sm,
    .bento-smm,
    .bento-lgg {
        grid-column: span 1;
        grid-row: auto;
    }
}

/* ===============================
   HIGHCHARTS CONTENEDORES
   (NO TOCA TU TEMA)
================================ */
.highcharts-figure {
    margin: 0;
}

/* ===== DESKTOP ===== */
#progresoContainer {
    height: 170px;
}

#progresoDetalleContainer {
    height: 450px;
}

#stackedContainer {
    height: 280px;
}

#recLogChart,
#poLogChart,
#solLogChart,
#prasLogChart {
    height: 300px;
}

/* ===== TABLET ===== */
@media (max-width: 992px) {
    #progresoDetalleContainer {
        height: 380px;
    }

    #stackedContainer {
        height: 240px;
    }
}

/* ===== MOBILE ===== */
@media (max-width: 576px) {

    #progresoContainer {
        height: 140px;
    }

    #progresoDetalleContainer {
        height: 300px;
    }

    #stackedContainer {
        height: 220px;
    }

    #recLogChart,
    #poLogChart,
    #solLogChart,
    #prasLogChart {
        height: 200px;
    }
}

</style>

<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title w-100">
                    <div class="row w-100">
                        <div class="col-md-11">
                            <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                            Reportes
                        </div>
                    </div>                
                </h1>                
            </div> 
            <div class="card-body">     
				<h4>Seleccione una auditoría (Cuenta Pública {{ $cp }})</h4>
                <select class="form-control mb-4 form-select form-group " data-control='select2'
                    onchange="if(this.value) window.location.href=this.value">
                    <option value="">Seleccione</option>
                    @foreach($auditorias as $aud)
                        <option value="{{ route('reporteauditoriaacciones.show', $aud->id) }}"
                            {{ $auditoria->id == $aud->id ? 'selected' : '' }}>
                            {{ $aud->numero_auditoria." - ".$aud->entidad_fiscalizable }}
                        </option>
                    @endforeach
                </select>

                <div class="bento-grid">

                    <!-- HERO -->                    
                    <div class="bento-box bento-hero">
                        
                        <figure class="highcharts-figure" style="margin:0;">
                        <div id="progresoContainer" aria-label="Barra de progreso general"></div>
                        </figure>

                    </div>
                    <div class="bento-box bento-lg">
                        <figure class="highcharts-figure" style="margin-top:10px;">
                            <div id="progresoDetalleContainer" aria-label="Barra de progreso por tipo"></div>
                        </figure>

                    </div>
                    
                    <div class="bento-box bento-lg">
                        <h5>Atención a las Acciones (Calificación)</h5>
                        <figure class="highcharts-figure" style="margin:10;">
                            <div id="stackedContainer" aria-label="Columnas superpuestas de acciones solventadas vs no solventadas"></div>
                        </figure>
                    </div>

                    <!-- DONUT
                    <div class="bento-box bento-smm">
                        <h5>Informes</h5>
                        <canvas id="pieChart"></canvas>
                    </div>-->

                    <!-- LINE -->
                    
                    <div class="bento-box bento-sm">
                        
                        <figure class="highcharts-figure" style="margin:0;">
                            <div id="recLogChart"></div>
                        </figure>
                    </div>

                    <div class="bento-box bento-sm">
                        
                        <figure class="highcharts-figure" style="margin:0;">
                            <div id="poLogChart"></div>
                        </figure>
                    </div>

                    <div class="bento-box bento-sm">
                        
                        <figure class="highcharts-figure" style="margin:0;">
                            <div id="solLogChart"></div>
                        </figure>
                    </div>

                    <div class="bento-box bento-sm">
                        
                        <figure class="highcharts-figure" style="margin:0;">
                            <div id="prasLogChart"></div>
                        </figure>
                    </div>
                    <!-- TURNOS 
                    <div class="bento-box bento-sm">
                        <h5>Turnos</h5>
                        <canvas id="turnosChart"></canvas>
                    </div>-->

                </div>

                <div class="pagination" style="justify-content:right !important;">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')   
{{-- Cargas de Highcharts (sin el tema adaptive.js) --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    
/***************************************************************************************************************************************************/
/*********************************  PRIMERA GRAFICA DE HERO - INICIO     ***************************/
/***************************************************************************************************************************************************/
    // (Opcional) Plugin del demo para la “punta de flecha” en las barras.
    // Si no te gusta la punta, puedes comentar todo este IIFE.
    (function (H) {
        H.addEvent(H.seriesTypes.column, 'afterColumnTranslate', function () {
            const series = this, options = series.options, topMargin = options.topMargin || 0,
                bottomMargin = options.bottomMargin || 0, idx = series.index;

            if (options.headSize) {
                series.points.forEach(function (point) {
                    const shapeArgs = point.shapeArgs, w = shapeArgs.width, h = shapeArgs.height,
                        x = shapeArgs.x, y = shapeArgs.y, cutLeft = idx !== 0,
                        cutRight = point.stackY !== point.y || !cutLeft;
                    let len = options.headSize; // px
                    if (point.y < 0) len *= -1;

                    // Preserva caja para data labels
                    point.dlBox = point.shapeArgs;

                    point.shapeType = 'path';
                    point.shapeArgs = {
                        d: [
                            ['M', x, y + topMargin],
                            ['L', x + w / 2, y + topMargin + (cutRight ? len : 0)],
                            ['L', x + w, y + topMargin],
                            ['L', x + w, y + h],
                            ['L', x + w / 2, y + h + bottomMargin + (cutLeft ? len : 0)],
                            ['L', x, y + h + bottomMargin],
                            ['L', x, y],
                            ['Z']
                        ]
                    };
                });
            }
        });
    }(Highcharts));


    /* === Datos del backend === */
    const seriesAgg   = @json($seriesAgg ?? []);
    const breakdown   = @json($breakdown ?? []);
    const typeColors  = @json($paletteByType ?? []);

    const css       = getComputedStyle(document.documentElement);
    const textColor = (css.getPropertyValue('--text-color') || '#222').trim();

    /* === Render de Highcharts === */
    Highcharts.chart('progresoContainer', {
        chart: {
            type: 'bar',
            height: 130,
            backgroundColor: 'transparent',
            style: { fontFamily: 'inherit',fontSize: '17px', },
            spacingLeft: 5,
            spacingRight: 5
        },
        title: { text: 'Progreso General del Primer Análisis' },
        credits: { enabled: false },
        exporting: { enabled: false },
        legend: { enabled: false },

        tooltip: {
            backgroundColor: 'rgba(255,255,255,0.95)',
            borderColor: '#ddd',
            style: { color: textColor },
            useHTML: true,
            formatter: function () {
                // Punto agregado (Completado o Pendiente)
                const grp = this.point.group || this.series.name; // 'Completado' o 'Pendiente'
                const y   = typeof this.point.y === 'number' ? this.point.y : 0;
                const pct = y.toFixed(2).replace('.00','');
                const cnt = this.point.count ?? 0;

                // Encabezado: total del segmento
                let html = `<div style="min-width:220px">
                    <div><b>${grp}:</b> ${cnt} (${pct}%)</div>`;

                // Desglose por tipo SÓLO en hover
                const rows = breakdown[grp] || [];
                if (rows.length) {
                    html += `<hr style="margin:6px 0;border:none;border-top:1px solid #eee;">`;
                    rows.forEach(r => {
                        const color = typeColors[r.key] || '#999';
                        const pct2 = (r.pct ?? 0).toFixed(2).replace('.00','');
                        html += `
                        <div style="display:flex;align-items:center;gap:6px;">
                            <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:${color};"></span>
                            <span>${r.label}:</span>
                            <b style="margin-left:auto;">${r.count} (${pct2}%)</b>
                        </div>`;
                    });
                }
                html += `</div>`;
                return html;
            }
        },

        plotOptions: {
            series: { animation: { duration: 400 } },
            bar: {
                stacking: 'normal',  // apila los 2 segmentos como una sola barra
                borderWidth: 0,
                // Punta de flecha (si mantienes el plugin):
                headSize: 20,
                dataLabels: {
                    enabled: true,
                    y:30,
                    style: {
                        color: textColor,
                        fontSize: '15px',
                        fontWeight: 600,
                    },
                    crop: false,
                    overflow: 'allow',
                    verticalAlign: 'bottom',
                    color: textColor,
                    formatter: function () {
                        // Muestra SOLO el porcentaje del segmento (sin desglose)
                        const y = this.point.y || 0;
                        const pct = y.toFixed(2).replace('.00','');
                        // Evita ruido en segmentos muy pequeños
                        return y >= 5 ? `${this.series.name}: ${pct}%` : null;
                    }
                },
                pointPadding: 0,
                groupPadding: 0.08
            }
        },

        xAxis: { categories: [''], visible: false },
        yAxis: {
            min: -2,
            max: 100,
            gridLineColor: 'transparent',
            lineColor: 'transparent',
            tickColor: 'transparent',
            labels: { enabled: false },
            title: { text: null }
        },

        // **Solo 2 series**: Completado y Pendiente
        series: seriesAgg
    });
/*********************************  PRIMERA GRAFICA DE HERO -FIN     ***************************/


/*********************************  SEGUNDA GRAFICA  Desglose de Acciones completadas y no Completadas del Primer Análisis -INICIO   ************************/
// Paleta desde backend (por tipo). Cada tipo tiene [colorCompletado, colorPendiente]
const palette = @json($palette ?? []);
const seriesPorTipoRaw = @json($seriesPorTipo ?? []);

// Mapear colores según (typeKey, stack)
const seriesPorTipo = seriesPorTipoRaw.map(s => {
    const colors = palette[s.typeKey] || ['#A13B71', '#ffc700'];
    const color = (s.stack === 'Completado') ? colors[0] : colors[1];
    return Object.assign({}, s, { color });
});

// Render del detalle por tipo
Highcharts.chart('progresoDetalleContainer', {
    chart: {
        type: 'bar',
        height: 300,
        backgroundColor: 'transparent',
        style: { fontFamily: 'inherit', fontSize: '16px',},
        spacingLeft: 20,
        spacingRight: 20
    },
    title: { text: "Desglose de Acciones completadas y no Completadas del Primer Análisis" },
    credits: { enabled: false },
    exporting: { enabled: false },

    // Muestra leyenda para distinguir tipos (Recomendaciones, PRAS, etc.)
    legend: {
        enabled: true,
        align: 'center',
        fontSize: '15px',
        verticalAlign: 'bottom',
        itemStyle: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-color') || '#222' }
    },

    tooltip: {
        backgroundColor: 'rgba(255,255,255,0.95)',
        borderColor: '#ddd',
        style: {
                fontSize: '15px',
                fontWeight: 600,
            },
        useHTML: true,
        formatter: function () {
            const pct = (this.point.y || 0).toFixed(2).replace('.00','');
            const cnt = this.point.count ?? 0;
            // this.series.options.stack => "Completado" o "Pendiente"
            // this.series.name => Tipo ("Recomendaciones", "PRAS", etc.)
            return `
              <div>
                <div><b>${this.series.options.stack}</b></div>
                <div>${this.series.name}: <b>${cnt}</b> (${pct}%)</div>
              </div>
            `;
        }
    },

    plotOptions: {
        series: { animation: { duration: 400 } },
        bar: {
            stacking: 'normal',
            borderWidth: 10,
            dataLabels: {
                enabled: true,
                y: -5,
                style: {
                        color: textColor,
                        fontSize: '15px',
                        fontWeight: 600,
                    },
                verticalAlign: 'bottom',
                color: getComputedStyle(document.documentElement).getPropertyValue('--text-color') || '#222',
                formatter: function () {
                    const y = this.point.y || 0;
                    const cnt = this.point.count ?? 0;
                    // Evita ruido visual en segmentos muy pequeños
                    return y >= 5 ? `${this.series.name}: ${cnt}` : null;
                }
            },
            pointPadding: 0,
            groupPadding: 0.08
        }
    },

    xAxis: {
        categories: [''], // Una sola categoría (una barra)
        visible: false,
        lineColor: 'transparent',
        tickColor: 'transparent'
    },
    yAxis: {
        min: 0,
        max: 100,
        gridLineColor: 'transparent',
        lineColor: 'transparent',
        tickColor: 'transparent',
        labels: { enabled: false },
        title: { text: null },
        startOnTick: false,
        endOnTick: false
    },

    // MUY IMPORTANTE: El orden de series define qué stack va a la izquierda.
    // Ponemos primero TODAS las de "Completado", luego TODAS las de "Pendiente".
    series: [
        ...seriesPorTipo.filter(s => s.stack === 'Completado'),
        ...seriesPorTipo.filter(s => s.stack === 'Pendiente')
    ]
});
/*********************************  SEGUNDA GRAFICA  Desglose de Acciones completadas y no Completadas del Primer Análisis -FIN   ************************/

/******************************** TERCERA GRAFICA Calificación de acción - INICIO********************************************************** */
    /* === Datos del backend === */
    const labelsStacked = @json($stacked['labels']);          // ['Recomendaciones', 'PRAS', 'Solicitudes', 'Pliegos']
    const dataSolv      = @json($stacked['solventadas']);     // [ ... ]
    const dataNoSolv    = @json($stacked['no_solventadas']);  // [ ... ]
    const colorOK   = '#90144a';  // Solventadas (frente)
    const colorNO   = '#EDDDD4';  // No solventadas (fondo)


    /* === Highcharts: columnas superpuestas === */
    Highcharts.chart('stackedContainer', {
    chart: {
        type: 'column',
        backgroundColor: 'transparent',
        style: { fontFamily: 'inherit',fontSize: '16px', },
        spacingTop: 8, spacingLeft: 8, spacingRight: 8, spacingBottom: 8
    },
    title: { text: null },
    credits: { enabled: false },
    exporting: { enabled: false },

    legend: {
        enabled: true,
        align: 'center',
        verticalAlign: 'top',
        itemStyle: { color: textColor }
    },

    xAxis: {
        categories: labelsStacked,
        lineColor: 'transparent',
        tickColor: 'transparent',
        labels: { style: { color: textColor,fontSize: '15px',  } }
    },
    yAxis: {
        min: 0,
        title: { text: null },
        gridLineColor: 'rgba(0,0,0,0.06)',
        labels: { style: { color: textColor } }
    },

    tooltip: {
        shared: true,
        backgroundColor: 'rgba(255,255,255,0.95)',
        borderColor: '#ddd',
        style: {
                color: textColor,
                fontSize: '15px',
                fontWeight: 600,
                },
        formatter: function () {
        const points = this.points || [this.point];
        const total  = points.reduce((sum, p) => sum + p.y, 0);
        //let html = `<b>${this.x}</b><br/>`;
        let html = `<b>Datos</b><br/>`;
        points.forEach(p => {
            const pct = total ? ((p.y / total) * 100).toFixed(1) : 0;
            html += `<span style="color:${p.color}">\u25CF</span> ${p.series.name}: <b>${p.y}</b> (${pct}%)<br/>`;
        });
        html += `<span style="color:#999">Total: ${total}</span>`;
        return html;
        }
    },

    plotOptions: {
        column: {
        grouping: false,   // ✅ superponer en la misma categoría
        shadow: false,
        borderWidth: 0,
        pointPadding: 0.2
        }
        
    },

    series: [
        // Fondo (No solventadas)
        {
        name: 'No solventadas',
        color: colorNO,
        data: dataNoSolv,
        pointPadding: 0.10,     // un poco más “atrás”
        pointPlacement: 0,
        zIndex: 1,
        dataLabels: { enabled: false }
        },
        // Frente (Solventadas)
        {
        name: 'Solventadas',
        color: colorOK,
        data: dataSolv,
        pointPadding: 0.20,     // menos padding para quedar “adelante”
        pointPlacement: 0,
        zIndex: 2,
        dataLabels: {
            enabled: true,
            style: { fontSize: '12px', fontWeight: 600, color: textColor },
            formatter: function () { return this.y > 0 ? this.y : null; }
        }
        }
    ],

    accessibility: {
        announceNewData: { enabled: true },
        point: { valueSuffix: ' acciones' }
    }
    });

/******************************** TERCERA GRAFICA Calificación de acción - FIN************************************************************* */

/******************************** CUARTA GRAFICA fases de acciónes - INCIO************************************************************* */
const hcTextColor = textColor;

function renderPhaseColumn(containerId, titulo, categories, data, color) {
    Highcharts.chart(containerId, {
        chart: {
            type: 'column',
            backgroundColor: 'transparent',
            spacing: [10, 10, 10, 10],
            style: { fontFamily: 'inherit' }
        },

        title: { text: titulo },
        credits: { enabled: false },
        exporting: { enabled: false },
        legend: { enabled: false },

        xAxis: {
            categories: categories,
            labels: {
                style: {
                    color: hcTextColor,
                    fontSize: '13px'
                }
            },
            lineColor: 'transparent',
            tickLength: 0
        },

        yAxis: {
            min: 0,
            title: { text: null },
            gridLineColor: 'rgba(0,0,0,0.06)',
            labels: {
                style: {
                    color: hcTextColor,
                    fontSize: '11px'
                }
            }
        },

        tooltip: {
            backgroundColor: 'rgba(255,255,255,0.95)',
            borderColor: '#ddd',
            style: {
                color: hcTextColor,
                fontSize: '14px',
                fontWeight: 600
            },
            formatter() {
                //return `<br/>${titulo}: <b>${this.y}</b>`;
                return `<br/><b>${this.y}</b>`;
            }
        },

        plotOptions: {
            column: {
                borderRadius: 4,
                borderWidth: 0,
                color: color,
                pointPadding: 0.1,
                groupPadding: 0.1
            }
        },

        series: [{
            name: titulo,
            data: data
        }]
    });
}
const recSeries  = @json($recSeries);
const poSeries   = @json($poSeries);
const solSeries  = @json($solSeries);
const prasSeries = @json($prasSeries);
const lineColors = @json($lineColors);

renderPhaseColumn(
    'recLogChart',
    'Recomendaciones por fase',
    recSeries.labels,
    recSeries.data,
    lineColors.rec
);

renderPhaseColumn(
    'poLogChart',
    'Pliegos de Observación por fase',
    poSeries.labels,
    poSeries.data,
    lineColors.po
);

renderPhaseColumn(
    'solLogChart',
    'Solicitudes de Aclaración por fase',
    solSeries.labels,
    solSeries.data,
    lineColors.sol
);

renderPhaseColumn(
    'prasLogChart',
    'PRAS por fase',
    prasSeries.labels,
    prasSeries.data,
    lineColors.pras
);

/******************************** CUARTA GRAFICA fases de acciónes- FIN************************************************************* */
window.addEventListener('resize', () => {
    Highcharts.charts.forEach(chart => {
        if (chart) {
            chart.reflow();
        }
    });
});
/*
//PIE /
new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: @json($informes['labels']),
        datasets: [{
            data: @json($informes['data']),
            backgroundColor: ['#90144a', '#f6c23e']
        }]
    }
});

/// HORIZONTAL BAR 
new Chart(document.getElementById('turnosChart'), {
    type: 'bar',
    data: {
        labels: @json($turnos['labels']),
        datasets: [{
            data: @json($turnos['data']),
            backgroundColor: '#A13B71'
        }]
    },
    options: {
        indexAxis: 'y'
    }
});


new Chart(document.getElementById('progresoChart'), {
    type: 'line',
    data: {
        labels: ['Inicio', 'Actual'],
        datasets: [{
            label: 'Progreso %',
            data: [0, {{ $porcentaje }}],
            borderColor: '#A13B71',
            backgroundColor: '#A13B71',
            tension: .4,
            fill: true
        }]
    },
    options: {
        scales: {
            y: { min: 0, max: 100 }
        }
    }
});*/
</script>

@endsection

