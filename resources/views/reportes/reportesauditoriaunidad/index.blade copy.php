@extends('layouts.app')

@section('breadcrums')
    {{Breadcrumbs::render('reporteauditoriaunidad.index') }}
@endsection

@section('content')
<style>
    tr:hover { background-color: #CAD5E2 !important; }

    #container { min-height: 620px; }

    .highcharts-breadcrumbs text { font-size: 13px !important; }
    .highcharts-data-label text { cursor: pointer; }
    .highcharts-point:hover { opacity: 0.9; }

    .badge { font-size: 13px; padding: 6px 10px; border-radius: 6px; }

    /* Estilos para la cuadrícula de auditorías */
    #auditoriasPanel .card { border: 1px linear-gradient(135deg, #ffffff 0%, #fbfbfe 100%); }

    #auditoriasPanel .audit-card-title { font-weight: 600; font-size: .95rem; line-height: 1.2; min-height: 2.1em; }
    #auditoriasPanel .audit-meta { font-size: .8rem; color: #6b7280; }

    @keyframes breath { 0% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.45); opacity: 0.9; } 100% { transform: scale(1); opacity: 1; } }

    .audit-card {
        border-radius: 10px; overflow: hidden; border: 0;
        background: linear-gradient(135deg, #ffffff 0%, #fbfbfe 100%);
        box-shadow: 0 6px 18px rgba(16, 24, 40, 0.06);
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .audit-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 6px;
        background: linear-gradient(180deg, #960048, #BB945C);
        border-top-left-radius: 18px; border-bottom-left-radius: 18px;
    }
    .audit-card-title { font-weight: 600; font-size: 1rem; color: #111827; }
    .audit-meta { font-size: 0.95rem; color: #374151; line-height: 1.45; }
    .audit-meta .small { font-size: 0.95rem; color: #6b7280; }
    .audit-meta .badge { font-size: 0.95rem; padding: .36rem .6rem; border-radius: .375rem; }

    .audit-meta .audit-field {
        font-size: 14px; font-weight: 700; color: #111827; margin-bottom: 6px;
        display: flex; align-items: baseline; gap: 8px;
    }
    .audit-meta .audit-field .field-value { font-weight: 400; color: #374151; margin-left: 0; }
    .audit-meta b { color: #111827; font-weight: 600; }

    .status-breath {
        width: 14px; height: 14px; border-radius: 50%; box-shadow: 0 0 10px rgba(0, 0, 0, 0.06);
        flex: 0 0 auto; margin-top: 4px;
    }
    .status-autorizado { background: #10b981; box-shadow: 0 0 12px rgba(16, 185, 129, 0.28); }
    .status-pending    { background: #f59e0b; box-shadow: 0 0 12px rgba(245, 158, 11, 0.18); }
    .status-absent     { background: #ef4444; box-shadow: 0 0 12px rgba(239, 68, 68, 0.12); }

    @keyframes card-breath { 0% { transform: translateY(0) } 50% { transform: translateY(-2px) } 100% { transform: translateY(0) } }

    /* ====== Ajustes generales para que NUNCA se salga el texto ====== */
    #auditoriasPanel .audit-meta,
    #auditoriasPanel .audit-meta .audit-field,
    #auditoriasPanel .audit-meta .audit-field .field-value,
    #auditoriasPanel .audit-card-title,
    #auditoriasPanel .audit-meta .small,
    #auditoriasPanel .audit-meta .badge {
        overflow-wrap: anywhere; word-break: break-word; hyphens: auto;
    }
    #auditoriasPanel .audit-meta .audit-field { align-items: flex-start; }
    #auditoriasPanel .audit-meta p { margin: 0; }

    /* Móvil */
    @media (max-width: 576px) {
        #auditoriasPanel .audit-card { border-radius: 10px; }
        #auditoriasPanel .audit-card .card-body { padding: 12px 12px; }
        #auditoriasPanel .audit-card-title { font-size: 0.95rem; line-height: 1.25; margin-bottom: .5rem; }
        #auditoriasPanel .audit-meta { font-size: .78rem; line-height: 1.35; }
        #auditoriasPanel .audit-meta .audit-field { font-size: .78rem; margin-bottom: 6px; gap: 6px; }
        #auditoriasPanel .audit-meta .audit-field .field-value { font-size: .78rem; }
        #auditoriasPanel .audit-meta .badge, #auditoriasPanel .audit-meta .small { font-size: .72rem; }
        #auditoriasPanel .d-flex.align-items-start.gap-3 { gap: 8px !important; }
        #auditoriasPanel .status-breath { width: 10px; height: 10px; margin-top: 2px; }
        #auditoriasPanel .audit-meta b, #auditoriasPanel .audit-meta .field-value, #auditoriasPanel .audit-meta .text-muted {
            overflow-wrap: anywhere; word-break: break-word;
        }
    }

    /* Tablet */
    @media (min-width: 577px) and (max-width: 768px) {
        #auditoriasPanel .audit-card .card-body { padding: 14px 14px; }
        #auditoriasPanel .audit-card-title { font-size: 1rem; }
        #auditoriasPanel .audit-meta, #auditoriasPanel .audit-meta .audit-field { font-size: .85rem; }
        #auditoriasPanel .audit-meta .badge, #auditoriasPanel .audit-meta .small { font-size: .78rem; }
    }

    .fancy-border { border: 2px solid #BB945C; box-shadow: 1px 1px 10px 1px rgba(239, 193, 139, .95); }
    .tooltip .tooltip-inner { max-width: 420px; text-align: left; font-size: 14PX; }

    /* Loader cuadrado */
    .swapping-squares-spinner, .swapping-squares-spinner * { box-sizing: border-box; }
    .swapping-squares-spinner {
        height: 65px; width: 65px; position: relative; display: flex; flex-direction: row;
        justify-content: center; align-items: center; margin: 8px auto;
    }
    .swapping-squares-spinner .square {
        height: calc(65px * 0.25 / 1.3);
        width: calc(65px * 0.25 / 1.3);
        animation-duration: 1000ms;
        border: calc(65px * 0.04 / 1.3) solid #BB945C;
        margin-right: auto; margin-left: auto; position: absolute; animation-iteration-count: infinite;
    }
    .swapping-squares-spinner .square:nth-child(1) { animation-name: swapping-squares-animation-child-1; animation-delay: 500ms; }
    .swapping-squares-spinner .square:nth-child(2) { animation-name: swapping-squares-animation-child-2; animation-delay: 0ms; }
    .swapping-squares-spinner .square:nth-child(3) { animation-name: swapping-squares-animation-child-3; animation-delay: 500ms; }
    .swapping-squares-spinner .square:nth-child(4) { animation-name: swapping-squares-animation-child-4; animation-delay: 0ms; }

    @keyframes swapping-squares-animation-child-1 { 50% { transform: translate(150%, 150%) scale(2, 2); } }
    @keyframes swapping-squares-animation-child-2 { 50% { transform: translate(-150%, 150%) scale(2, 2); } }
    @keyframes swapping-squares-animation-child-3 { 50% { transform: translate(-150%, -150%) scale(2, 2); } }
    @keyframes swapping-squares-animation-child-4 { 50% { transform: translate(150%, -150%) scale(2, 2); } }

    /* === Layout del gauge + panel derecho === */
    .dept-gauge-wrap{ display:flex; align-items:stretch; gap:14px; min-height: 260px; }
    .dept-gauge-wrap .highcharts-figure{ flex: 0 1 360px; min-width: 280px; margin: 0; }
    #grafica_deptos{ min-width: 260px; min-height: 260px; }
    .dept-gauge-info{
        flex: 1 1 220px; min-width: 200px; display:flex; flex-direction:column; justify-content:center;
        border-left: 1px solid rgba(187,148,92,.25); padding-left: 12px; color: #132a29;
    }
    .dept-gauge-info h6{ margin:0 0 6px 0; font-weight:700; }
    .dept-gauge-info .kpi{ display:flex; align-items:baseline; gap:8px; margin-bottom:6px; }
    .dept-gauge-info .kpi .big{ font-size: clamp(1.2rem, 2.2vw, 1.7rem); font-weight: 800; }
    .dept-gauge-info .muted{ color:#6b7280; }
    @media (max-width: 1200px){ .dept-gauge-wrap .highcharts-figure{ flex-basis: 320px; min-width: 260px; } }
    @media (max-width: 768px){
        .dept-gauge-wrap{ flex-direction: column; }
        .dept-gauge-info{ border-left: 0; border-top:1px solid rgba(187,148,92,.25); padding-top: 10px; padding-left:0; }
        .dept-gauge-wrap .highcharts-figure{ flex-basis: auto; min-width: 100%; }
    }
    .dept-gauge-wrap.is-stacked{ flex-direction: column; }
    .dept-gauge-wrap.is-stacked .dept-gauge-info{
        border-left: 0; border-top:1px solid rgba(187,148,92,.25);
        padding-top: 10px; padding-left:0;
    }
    .dept-gauge-wrap.is-stacked .highcharts-figure{ flex-basis: auto; min-width: 100%; }

    
    @media print {
    /* Oculta TODO excepto #exportArea */
    body > * {
        display: none !important;
    }

    /* Muestra solo la parte exportable */
    #exportArea {
        display: block !important;
        position: relative !important;
        overflow: visible !important;
    }

    /* Oculta botones dentro de exportArea */
    #exportArea [data-print="hide"],
    #exportArea .highcharts-contextbutton {
        display: none !important;
    }

    /* Evita cortes feos */
    .card, .audit-card {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    @page {
        size: Letter;
        margin: 10mm;
    }
    }

</style>
@if(request('print') == '1')
    <style>
    /* Oculta TODO menos #exportArea */
    body > * { display: none !important; }
    #exportArea {
        display: block !important;
        position: relative !important;
        overflow: visible !important;
    }
    /* Oculta controles dentro del área (botones, etc.) */
    #exportArea [data-print="hide"],
    #exportArea .highcharts-contextbutton {
        display: none !important;
    }
    .card, .audit-card { break-inside: avoid; page-break-inside: avoid; }
    @page { size: Letter; margin: 10mm; }
    </style>

    <script>
    // Cuando Browsershot carga con ?print=1: asegúrate de mostrar Treemap/Panel si quieres que salgan.
    document.addEventListener('DOMContentLoaded', () => {
        try {
        // Si los tienes ocultos normalmente:
        document.getElementById('treemapFigure')?.classList.remove('d-none');
        document.getElementById('auditoriasPanel')?.classList.remove('d-none');

        // Forzar reflow/redraw de Highcharts por si el layout cambió
        (window.Highcharts?.charts || []).forEach(c => { if (c) { try { c.reflow(); c.redraw(); } catch (e) {} } });

        // Si necesitas esperar datos async, puedes demorar un poco la “estabilidad”
        setTimeout(() => { /* listo para imprimir */ }, 200);
        } catch (e) {}
    });
    </script>
@endif

<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title w-100">
                    <div class="row w-100">
                        <div class="col-md-11">
                            <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>&nbsp;
                                Reportes de Acciones de Auditoría por Unidad
                        </div>
                    </div>
                </h1>
            </div>

            {{-- Área a exportar --}}
            <div id="exportArea">
                <div class="card-body">
                    @include('flash::message')

                    <div class="d-flex justify-content-end mb-2">
                        <button class="btn btn-danger" onclick="sendPdfRequest()">PDF</button>
                    </div>
                    <div class="my-4">
                        <div class="row g-4">
                            {{-- Total de auditorías --}}
                            <div class="col-12 col-lg-12">
                                <div class="card h-60 shadow-sm">
                                    <div class="card-body audit-card fancy-border">
                                        <span style="font-size: 24px">
                                            <b>Total auditorías:</b>
                                            <b id="totalAuditorias">
                                                {{ $auditoriasPorDireccionA->count() + $auditoriasPorDireccionB->count() }}
                                            </b>
                                            <b> - Cuenta pública {{ getSession('cp') }}</b>
                                        </span>
                                    </div>
                                </div>
                            </div>
                                
                            @if(auth()->user()->unidadAdministrativa->id == 122100 || auth()->user()->unidadAdministrativa->id ==  122000)
                                {{-- Encargados A --}}
                                <div class="col-12 col-lg-4">
                                    <div class="card shadow-sm">
                                        <div class="card-body audit-card fancy-border" style="font-size: 14px">
                                            <h2> Total de Auditorias dirección A: {{ $auditoriasPorDireccionA->count() }} </h2>
                                        </div>
                                    </div>
                                    <div class="card h-20 shadow-sm">
                                        <div class="card-body audit-card fancy-border" id="cardEncargadosA" style="font-size: 14px">
                                            <small class="d-block mb-1"><b>Encargados</b></small>
                                            <div id="encargadosDireccionA" class="mb-1">
                                                <b>Dirección:</b> <span id="enc_dirA">Dirección de Seguimiento "A"</span>
                                            </div>
                                            <div id="encargadosDirectorA" class="mb-2">
                                                <b>Director:</b> <span id="enc_directorA">—</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card h-20 shadow-sm">
                                        <div class="card-body audit-card fancy-border" id="cardEncargadosA" style="font-size: 14px">
                                            <div id="encargadosDeptoA" class="mb-1">
                                                <b>Departamento:</b> <span id="enc_deptoA">—</span>
                                            </div>
                                            <div id="encargadosJefeA" class="mb-0">
                                                <b>Jefe:</b> <span id="enc_jefeA">—</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Gauge A --}}
                                <div class="col-12 col-lg-8">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body audit-card fancy-border">
                                            <figure class="highcharts-figure mb-0">
                                                <div id="grafica_depto_auditoriasA"></div>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                                
                            @endif
                            @if(auth()->user()->unidadAdministrativa->id == 122200 || auth()->user()->unidadAdministrativa->id ==  122000)
                                {{-- Encargados B --}}
                                <div class="col-12 col-lg-6">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body audit-card fancy-border" id="cardEncargadosB" style="font-size: 14px">
                                            <small class="d-block mb-1"><b>Encargados</b></small>
                                            <div id="encargadosDireccionB" class="mb-1">
                                                <b>Dirección:</b> <span id="enc_dirB">Dirección de Seguimiento "B"</span>
                                            </div>
                                            <div id="encargadosDirectorB" class="mb-2">
                                                <b>Director:</b> <span id="enc_directorB">—</span>
                                            </div>
                                            <div id="encargadosDeptoB" class="mb-1 d-none">
                                                <b>Departamento:</b> <span id="enc_deptoB">—</span>
                                            </div>
                                            <div id="encargadosJefeB" class="mb-0 d-none">
                                                <b>Jefe:</b> <span id="enc_jefeB">—</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Gauge B + panel lateral --}}
                                <div class="col-12 col-lg-8">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body audit-card fancy-border">
                                            <div id="grafica_deptos_wrap" class="dept-gauge-wrap">
                                                <figure class="highcharts-figure mb-0">
                                                    <div id="grafica_deptos"></div>
                                                </figure>
                                                <div id="grafica_deptos_info" class="dept-gauge-info">
                                                    {{-- Info dinámica al hover --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>{{-- row g-4 --}}
                    </div>

                    <br><hr class="fancy-border">
                    {{-- Filtros de búsqueda (se muestran en vista de auditorías) --}}
                    <div id="auditFiltersCard" class="d-none">
                        {!! BootForm::open(['route' => 'reporteauditoriaunidad.index', 'method' => 'GET', 'id' => 'auditFiltersForm']) !!}
                        <div class="row align-items-end">
                            <div class="col-md-2">
                                {!! BootForm::text('numero_auditoria', "No. auditoría:", old('numero_auditoria', $request->numero_auditoria), ['id' => 'f_numero_auditoria']) !!}
                            </div>
                            <div class="col-md-3">
                                {!! BootForm::text('entidad_fiscalizable', "Entidad fiscalizable:", old('entidad_fiscalizable', $request->entidad_fiscalizable), ['id' => 'f_entidad_fiscalizable']) !!}
                            </div>
                            <div class="col-md-3">
                                {!! BootForm::text('acto_fiscalizacion', "Acto de fiscalización:", old('acto_fiscalizacion', $request->acto_fiscalizacion), ['id' => 'f_acto_fiscalizacion']) !!}
                            </div>
                            <div class="col-md-1 mt-2">
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </div>
                            <div class="col-md-1 mt-2">
                                <button type="button" id="f_limpiar" class="btn btn-secondary">Limpiar</button>
                            </div>
                        </div>
                        {!! BootForm::close() !!}
                    </div>

                    {{-- Treemap (se muestra al seleccionar depto) --}}
                    <figure class="highcharts-figure mb-4 d-none" id="treemapFigure">
                        <div id="container"></div>
                    </figure><br>
                    {{-- % Avance --}}
                            <div class="col-12 col-lg-12">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body audit-card fancy-border">
                                        <b>Porcentaje de avance de la Auditoría: <span id="audi_name">—</span></b>
                                        <h4 class="fw-bold" id="avanceAuditoria">—</h4>
                                        <div class="progress mt-2" style="height: 10px;">
                                            <div id="avanceBar" class="progress-bar bg-warning" role="progressbar"
                                                 style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <hr class="fancy-border">
                    {{-- Panel de cuadrícula de auditorías --}}
                    <div id="auditoriasPanel" class="d-none">
                        <div class="d-flex align-items-center justify-content-between mb-15">
                            <div>
                                <h5 class="mb-3">
                                    Seguimiento Auditoria — <span id="panelDir"></span> / <span id="panelDept"></span>
                                </h5>
                                <small class="text-muted">
                                    Se muestran los datos más relevantes de la auditoría seleccionada
                                    (para cambiar los datos, selecciona otra auditoría u otro departamento).
                                </small>
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary" id="btnOcultarPanel">Limpiar</button>
                        </div>
                        <hr class="fancy-border">
                        <div id="auditoriasGrid" class="row g-3"></div>
                    </div>

                </div> {{-- /card-body --}}
            </div> {{-- /exportArea --}}
        </div> {{-- /card --}}
    </div>
</div>
@endsection

@section('script')
<!-- Highcharts core + módulos -->
<script src="https://code.highcharts.com/11.4.0/highcharts.js"></script>
<script src="https://code.highcharts.com/11.4.0/modules/treemap.js"></script>
<script src="https://code.highcharts.com/11.4.0/highcharts-more.js"></script>
<script src="https://code.highcharts.com/11.4.0/modules/solid-gauge.js"></script>
<script src="https://code.highcharts.com/11.4.0/modules/accessibility.js"></script>
<!-- (opcional) exportación de Highcharts -->
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
<!-- canvg UMD (antes de chartToPngById / sendPdfRequest) -->
<script src="https://cdn.jsdelivr.net/npm/canvg@3.0.7/lib/umd.js"></script>



<script>
    window.REPORT_DATA = {
        treemapData: @json($treemapData),
        auditoriasMap: @json($auditoriasGrid),
        currentUserId: @json(auth()->id()),
        detalleBaseUrl: "{{ url('reportes/auditoria') }}",
        directoresPorDireccion: @json($directoresPorDireccion),
        jefesPorDepartamento: @json($jefesPorDepartamento),
        auditoriaSeleccionada: @json($auditoriaSeleccionada),
    };
    // Devuelve el objeto Highcharts cuyo contenedor tiene el id dado
    function getChartByContainerId(containerId) {
        const list = (window.Highcharts && Highcharts.charts) ? Highcharts.charts : [];
        return list.find(c => c && c.renderTo && c.renderTo.id === containerId) || null;
    }

    // Convierte un chart Highcharts a PNG usando canvg (desde el SVG, sin html2canvas)
    async function chartToPngById(containerId, scale = 2) {
        // 1) Obtén la clase Canvg desde la build UMD
        if (!window.canvg || !window.canvg.Canvg) {
        console.error('[chartToPngById] canvg UMD no está cargado. Revisa el <script src=".../umd.js">');
        return null;
        }
        const { Canvg } = window.canvg;

        const chart = getChartByContainerId(containerId);
        if (!chart || !chart.getSVG) {
        console.warn('[chartToPngById] No encontré chart con containerId:', containerId);
        return null;
        }

        // Asegura layout estable
        try { chart.reflow(); chart.redraw(); } catch (e) {}

        const w = Math.max(1, chart.chartWidth);
        const h = Math.max(1, chart.chartHeight);

        // 2) Obtener el SVG nativo del chart
        const svg = chart.getSVG({
        exporting: { sourceWidth: w, sourceHeight: h }
        });

        // 3) Rasterizar SVG → PNG con canvg (nitidez x2)
        const canvas = document.createElement('canvas');
        canvas.width  = Math.round(w * scale);
        canvas.height = Math.round(h * scale);
        const ctx = canvas.getContext('2d');

        const v = await Canvg.fromString(ctx, svg, {
        ignoreMouse: true,
        ignoreAnimation: true
        });
        await v.render();

        return canvas.toDataURL('image/png');
    }

    // Envía al servidor los PNG en Base64 para que Dompdf los incruste
    async function sendPdfRequest() {
        // Imágenes de charts (base64) con canvg
        const imgs = {
        gaugeA: await chartToPngById('grafica_depto_auditoriasA'),
        gaugeB: await chartToPngById('grafica_deptos'),
        treemap: await chartToPngById('chartInner') // si el treemap ya se dibujó
        };

        // Cards superiores visibles
        const totalAud = document.getElementById('totalAuditorias')?.textContent?.trim() || '';
        const dirA = document.getElementById('enc_dirA')?.textContent?.trim() || '';
        const dirB = document.getElementById('enc_dirB')?.textContent?.trim() || '';
        const directorA = document.getElementById('enc_directorA')?.textContent?.trim() || '';
        const directorB = document.getElementById('enc_directorB')?.textContent?.trim() || '';

        // Contexto del panel / auditoría actual
        const deptId      = window.__pdfDeptId || '';
        const deptName    = window.__pdfDeptName || '';
        const dirName     = window.__pdfDirName || '';
        const auditId     = window.__pdfAuditId || '';
        const auditName   = window.__pdfAuditName || '';
        const auditAvance = window.__pdfAuditAvanceText || '';

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("reporteauditoriaunidad.pdf") }}';

        const add = (name, value) => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = name;
        inp.value = value || '';
        form.appendChild(inp);
        };

        // CSRF
        add('_token', '{{ csrf_token() }}');

        // Imágenes
        add('gaugeA', imgs.gaugeA);
        add('gaugeB', imgs.gaugeB);
        add('treemap', imgs.treemap);

        // Cards superiores
        add('totalAuditorias', totalAud);
        add('dirA', dirA);
        add('dirB', dirB);
        add('directorA', directorA);
        add('directorB', directorB);

        // Contexto
        add('deptId', deptId);
        add('deptName', deptName);
        add('dirName', dirName);
        add('auditId', auditId);
        add('auditName', auditName);
        add('auditAvance', auditAvance);

        document.body.appendChild(form);
        form.submit();
    }

</script>

{{-- JS de la página (tu archivo) --}}
<script src="{{ asset('js/reportesauditoriaunidad.js') }}?v={{ filemtime(public_path('js/reportesauditoriaunidad.js')) }}"></script>
@endsection