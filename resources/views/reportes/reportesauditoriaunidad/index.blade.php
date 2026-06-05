@extends('layouts.app')

@section('breadcrums')
    {{Breadcrumbs::render('reporteauditoriaunidad.index') }}
@endsection

@section('content')

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
                                    <div class="card h-30 shadow-sm">
                                        <div class="card-body audit-card fancy-border" style="font-size: 14px">
                                            <h2> Total de Auditorias dirección A: {{ $auditoriasPorDireccionA->count() }} </h2>
                                        </div>
                                    </div><br>
                                    <div class="card h-30 shadow-sm">
                                        <div class="card-body audit-card fancy-border" id="cardEncargadosA" style="font-size: 14px">
                                            <small class="d-block mb-1"><b>Encargados</b></small>
                                            <div id="encargadosDireccionA" class="mb-1">
                                                <b>Dirección:</b> <span id="enc_dirA">Dirección de Seguimiento "A"</span>
                                            </div>
                                            <div id="encargadosDirectorA" class="mb-2">
                                                <b>Director:</b> <span id="enc_directorA">—</span>
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="card h-30 shadow-sm">
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
                                        <div class="card-body audit-card fancy-border audit-card--chart">
                                            <figure class="highcharts-figure mb-0">
                                                <div id="grafica_depto_auditoriasA"></div>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(auth()->user()->unidadAdministrativa->id == 122200 || auth()->user()->unidadAdministrativa->id ==  122000)
                                {{-- Encargados B --}}
                                <div class="col-12 col-lg-4">
                                    <div class="card h-30 shadow-sm">
                                        <div class="card-body audit-card fancy-border" style="font-size: 14px">
                                            <h2> Total de Auditorias dirección B: {{ $auditoriasPorDireccionB->count() }} </h2>
                                        </div>
                                    </div><br>
                                    <div class="card h-30 shadow-sm">
                                        <div class="card-body audit-card fancy-border" id="cardEncargadosA" style="font-size: 14px">
                                            <small class="d-block mb-1"><b>Encargados</b></small>
                                            <div id="encargadosDireccionB" class="mb-1">
                                                <b>Dirección:</b> <span id="enc_dirB">Dirección de Seguimiento "B"</span>
                                            </div>
                                            <div id="encargadosDirectorB" class="mb-2">
                                                <b>Director:</b> <span id="enc_directorB">—</span>
                                            </div>
                                        </div>
                                    </div><br>
                                    <div class="card h-30 shadow-sm">
                                        <div class="card-body audit-card fancy-border" id="cardEncargadosA" style="font-size: 14px">
                                            <small class="d-block mb-1"><b>Encargados</b></small>
                                            <div id="encargadosDeptoB" class="mb-1">
                                                <b>Departamento:</b> <span id="enc_deptoB">—</span>
                                            </div>
                                            <div id="encargadosJefeB" class="mb-0 ">
                                                <b>Jefe:</b> <span id="enc_jefeB">—</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Gauge B + panel lateral --}}
                                <div class="col-12 col-lg-8">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body audit-card fancy-border audit-card--chart">
                                            @if (auth()->user()->siglas_rol == "DS" || auth()->user()->siglas_rol == "TUS")
                                                <div id="grafica_deptos_wrap" class="dept-gauge-wrap">
                                                    <figure class="highcharts-figure mb-0">
                                                        <div id="grafica_deptos"></div>
                                                    </figure>
                                                    <div id="grafica_deptos_info" class="dept-gauge-info">
                                                        {{-- Info dinámica al hover --}}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>{{-- row g-4 --}}
                    </div>
                    <div id="jefesdiv">

                    </div>

                    <br><hr class="fancy-border">
                    {{-- Filtros de búsqueda (se muestran en vista de auditorías) --}}
                    <div id="auditFiltersCard" class="d-none">
                        @if (auth()->user()->siglas_rol!="JD")
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="button" id="btnOcultarTreemap" class="btn btn-secondary">
                                        <i class="bi bi-arrow-repeat" style="font-size: 1.5em;"></i>Limpiar Departamento
                                    </button>
                                </div>
                            </div>
                        @endif
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
                            <div class="col-md-3 mt-1">
                                <button type="submit" class="btn btn-primary">Buscar</button>
                                <button type="button" id="f_limpiar" class="btn btn-secondary"><i class="bi bi-arrow-repeat" style="font-size: 1.5em;"></i> Limpiar Filtros</button>
                            </div>
                            <div class="col-md-1">

                            </div>
                        </div>
                        {!! BootForm::close() !!}
                    </div>

                    {{-- Treemap (se muestra al seleccionar depto) --}}
                    <figure class="highcharts-figure mb-4 d-none" id="treemapFigure">
                        <div id="container" style="position:relative">

                        </div>
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
                            <button type="button" class="btn btn-sm btn-secondary" id="btnOcultarPanel"><i class="bi bi-arrow-repeat" style="font-size: 2em;"></i>Limpiar Auditoría</button>
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

<script src="{{ asset('vendor/highcharts/highcharts.js') }}"></script>
<script src="{{ asset('vendor/highcharts/highcharts-more.js') }}"></script>
<script src="{{ asset('vendor/highcharts/modules/treemap.js') }}"></script>
<script src="{{ asset('vendor/highcharts/modules/solid-gauge.js') }}"></script>
<script src="{{ asset('vendor/highcharts/modules/accessibility.js') }}"></script>
<script src="{{ asset('vendor/highcharts/modules/exporting.js') }}"></script>
<!-- canvg UMD (antes de chartToPngById / sendPdfRequest) -->
<script src="https://cdn.jsdelivr.net/npm/canvg@3.0.7/lib/umd.js"></script>

<script>

  // ========= Datos inyectados =========
  window.REPORT_DATA = {
    treemapData: @json($treemapData),
    auditoriasMap: @json($auditoriasGrid),
    currentUserId: @json(auth()->id()),
    detalleBaseUrl: "{{ url('reportes/auditoria') }}",
    directoresPorDireccion: @json($directoresPorDireccion),
    jefesPorDepartamento: @json($jefesPorDepartamento),
    auditoriaSeleccionada: @json($auditoriaSeleccionada),
    userRole: @json(auth()->user()->siglas_rol),
    userDeptId: @json(auth()->user()->unidadAdministrativa->id ?? null),
    detalleBaseUrl: "{{ url('reportes/auditoria') }}",

  };

  // ========= Botón: ocultar treemap y reset =========
  function hideTreemapAndReset() {
    try {
        document.getElementById('auditFiltersCard')?.classList.add('d-none');
        document.getElementById('treemapFigure')?.classList.add('d-none');
        document.getElementById('auditFiltersForm')?.reset();
        document.getElementById('auditPagination')?.replaceChildren();

        const t = document.getElementById('avanceAuditoria'); if (t) t.textContent = '—';
        const a = document.getElementById('audi_name'); if (a) a.textContent = '—';
        const b = document.getElementById('avanceBar');
        if (b) {
        b.style.width = '0%';
        b.setAttribute('aria-valuenow', '0');
        b.classList.remove('bg-success', 'bg-warning', 'bg-danger');
        b.classList.add('bg-warning');
        }

        const panel = document.getElementById('auditoriasPanel');
        if (panel) panel.classList.add('d-none');

        const ci = document.getElementById('chartInner');
        if (ci) ci.innerHTML = '';

        try { sessionStorage.removeItem('auditoriaViewState'); } catch (e) {}
        clearPdfDeptState();

    } catch (e) { console.error(e); }
    }

    function clearPdfAuditState() {
    window.__pdfAuditId = null;
    window.__pdfAuditName = null;
    window.__pdfAuditAvanceText = null;
    }

    function clearPdfDeptState() {
    window.__pdfDeptId = null;
    window.__pdfDeptName = null;
    window.__pdfDirName = null;

    // Al limpiar departamento, también se limpia la auditoría
    clearPdfAuditState();
    }

  document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btnOcultarTreemap')?.addEventListener('click', hideTreemapAndReset);
  });

  // ========= Utilidades Highcharts =========
  function getChartByContainerId(containerId) {
    const list = (window.Highcharts && Highcharts.charts) ? Highcharts.charts : [];
    return list.find(c => c && c.renderTo && c.renderTo.id === containerId) || null;
  }
  async function reflowIfChart(containerId) {
    const chart = getChartByContainerId(containerId);
    if (!chart) return false;
    try { chart.reflow(); chart.redraw(); } catch (e) {}
    await new Promise(requestAnimationFrame);
    return true;
  }
  // Espera hasta que el chart exista y tenga dimensiones (>0)
  async function waitForChart(containerId, timeoutMs = 5000, stepMs = 150) {
    const start = Date.now();
    while (Date.now() - start < timeoutMs) {
      const c = getChartByContainerId(containerId);
      if (c && c.chartWidth > 0 && c.chartHeight > 0 && typeof c.getSVG === 'function') return true;
      await new Promise(r => setTimeout(r, stepMs));
    }
    return false;
  }

  // ========= Rasterizar chart visible a imagen (JPEG ligero) =========


async function chartToImageById(containerId, opts = {}) {
  const { scale = 1.25, mime = 'image/jpeg', quality = 0.92 } = opts;

  if (!window.canvg || !window.canvg.Canvg) {
    console.error('[chartToImageById] canvg UMD no está cargado');
    return null;
  }
  const { Canvg } = window.canvg;

  // ===== 1) Obtiene SVG del chart (o del DOM como fallback) =====
  const chart = getChartByContainerId(containerId);
  let svg = null, w = 800, h = 450;

  if (chart) {
    try { chart.reflow(); chart.redraw(); } catch (e) {}
    await new Promise(requestAnimationFrame);

    const cw = Math.max(1, chart.chartWidth);
    const ch = Math.max(1, chart.chartHeight);
    if (cw > 0 && ch > 0 && typeof chart.getSVG === 'function') {
      w = cw; h = ch;
      // Forzamos fondo blanco SOLO para exportación
      svg = chart.getSVG({
        exporting: { sourceWidth: cw, sourceHeight: ch },
        chart: { backgroundColor: '#FFFFFF', plotBackgroundColor: '#FFFFFF' }
      });
    }
  }

  // Fallback: usa el <svg> que está en el DOM
  if (!svg) {
    const mount = document.getElementById(containerId);
    const svgEl = mount?.querySelector('svg') || mount?.parentElement?.querySelector('svg');
    if (!svgEl) return null;
    svg = svgEl.outerHTML;

    const box = mount?.getBoundingClientRect();
    if (box && box.width && box.height) {
      w = Math.max(1, Math.round(box.width));
      h = Math.max(1, Math.round(box.height));
    }
  }

  // ===== 2) Inyecta un <rect> blanco al inicio del SVG (quita transparencias) =====
  // Inserta justo después de la apertura de <svg ...>
  try {
    const hasRect = /<rect[^>]+fill\s*=\s*["']#?fff/i.test(svg);
    if (!hasRect) {
      svg = svg.replace(
        /<svg([^>]*)>/i,
        (m, attrs) => `<svg${attrs}><rect width="100%" height="100%" x="0" y="0" fill="#FFFFFF"/>`
      );
    }
  } catch (e) {
    // si algo falla, seguimos; el paso 3 cubre el fondo igualmente
  }

  // ===== 3) Renderiza con canvg y PONE fondo blanco por debajo =====
  const canvas = document.createElement('canvas');
  canvas.width  = Math.round(w * scale);
  canvas.height = Math.round(h * scale);
  const ctx = canvas.getContext('2d');

  try {
    const v = await Canvg.fromString(ctx, svg, {
      ignoreMouse: true,
      ignoreAnimation: true
    });
    await v.render();

    // Fondo blanco *debajo* de lo ya dibujado
    ctx.save();
    ctx.globalCompositeOperation = 'destination-over';
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.restore();
  } catch (e) {
    console.error('[chartToImageById] canvg falló al renderizar el SVG', e);
    return null;
  }

  return canvas.toDataURL(mime, quality);
}

  // ========= Enviar PDF SOLO con lo que se está mostrando =========
  async function sendPdfRequest() {
    const btn = event?.currentTarget;
    if (btn) { btn.disabled = true; btn.textContent = 'Generando…'; }

    // Reflow por si cambió layout
    await reflowIfChart('grafica_depto_auditoriasA');
    await reflowIfChart('grafica_deptos');
    await reflowIfChart('chartInner');

    // Espera a que charts visibles estén listos (si existen en DOM)
    const ids = ['grafica_depto_auditoriasA', 'grafica_deptos', 'chartInner']
      .filter(id => document.getElementById(id)); // sólo los que existen
    await Promise.all(ids.map(id => waitForChart(id, 5000, 150)));

    // Captura SÓLO si hay chart real
    const imgs = {
      gaugeA: getChartByContainerId('grafica_depto_auditoriasA')
                ? await chartToImageById('grafica_depto_auditoriasA', { scale: 1.25, mime: 'image/jpeg', quality: 0.85 })
                : null,
      gaugeB: getChartByContainerId('grafica_deptos')
                ? await chartToImageById('grafica_deptos', { scale: 1.25, mime: 'image/jpeg', quality: 0.85 })
                : null,
      treemap: getChartByContainerId('chartInner')
                ? await chartToImageById('chartInner', { scale: 1.10, mime: 'image/jpeg', quality: 0.80 })
                : null,
    };

    //console.log('[PDF] sizes:', { A: imgs.gaugeA?.length || 0, B: imgs.gaugeB?.length || 0, T: imgs.treemap?.length || 0 });

    // Si no hay NINGUNA imagen, avisa y detén (WYSIWYG)
    if (!imgs.gaugeA && !imgs.gaugeB && !imgs.treemap) {
      if (window.Swal) {
        Swal.fire({
          icon: 'warning',
          title: 'No hay gráficos para exportar',
          text: 'La exportación respeta lo que está en pantalla. Espera a que se dibujen los gauges o el treemap.'
        });
      } else {
        alert('No hay gráficos para exportar (espera a que se dibujen).');
      }
      if (btn) { btn.disabled = false; btn.textContent = 'PDF'; }
      return;
    }

    // Cards de texto
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

    // POST por form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("reporteauditoriaunidad.pdf") }}';

    const add = (name, value) => {
      if (value == null || value === '') return;
      const inp = document.createElement('input');
      inp.type = 'hidden';
      inp.name = name;
      inp.value = (typeof value === 'string') ? value.trim() : value;
      form.appendChild(inp);
    };

    add('_token', '{{ csrf_token() }}');
    if (imgs.gaugeA) add('gaugeA', imgs.gaugeA);
    if (imgs.gaugeB) add('gaugeB', imgs.gaugeB);
    if (imgs.treemap) add('treemap', imgs.treemap);

    add('totalAuditorias', totalAud);
    add('dirA', dirA);
    add('dirB', dirB);
    add('directorA', directorA);
    add('directorB', directorB);

    add('deptId', deptId);
    add('deptName', deptName);
    add('dirName', dirName);
    add('auditId', auditId);
    add('auditName', auditName);
    add('auditAvance', auditAvance);

    document.body.appendChild(form);
    form.submit();

    setTimeout(() => { if (btn) { btn.disabled = false; btn.textContent = 'PDF'; } }, 2000);
  }

// FIX PRINT: recalcula gauges antes de imprimir
window.addEventListener('beforeprint', () => {
  try {
    Highcharts.charts.forEach(ch => {
      if (!ch) return;
      ch.reflow();
      ch.redraw(false);
    });
  } catch (e) {}
});

</script>
{{-- JS de la página (tu archivo) --}}
<script src="{{ asset('js/reportesauditoriaunidad.js') }}?v={{ filemtime(public_path('js/reportesauditoriaunidad.js')) }}"></script>
@endsection
