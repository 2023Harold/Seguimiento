// reportesauditoriaunidad.js (A3: 2 gauges, sin vista de direcciones/deptos)
(function () {
  
  // ====== Debounce resize para treemap ======
  let chart = null;
  let _resizeTO = null;
  window.addEventListener("resize", () => {
    clearTimeout(_resizeTO);
    _resizeTO = setTimeout(() => {
      try {
        if (!chart) return;
        const FS = getFontSizes();
        chart.update(
          {
            title: { style: { fontSize: FS.title } },
            subtitle: { style: { fontSize: FS.subtitle } },
            tooltip: {
              style: { fontSize: FS.tooltip, maxWidth: FS.tooltipMaxW },
            },
            series: [{ dataLabels: { style: { fontSize: FS.dataLabel } } }],
          },
          false
        );
        chart.reflow();
        chart.redraw();
      } catch (e) { }
    }, 150);
  });

  // ===== Utilidades / helpers =====
  // === Inserta card de acciones SOLO si hay elementos (sin "No hay ...") ===
  
  
  const TREEMAP_GAUGE_SPINNER_HTML = `
    <div style="
      display:flex;
      align-items:center;
      justify-content:center;
      min-height:420px;
      width:100%;
    ">
      <div class="swapping-squares-spinner" aria-label="Cargando">
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
        <div class="square"></div>
      </div>
    </div>
  `;


  function esc(s) {
    return String(s).replace(/[&<>"']/g, (ch) =>
      ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[ch])
    );
  }
  function toArr(v) {
    if (!v) return [];
    return Array.isArray(v) ? v : [v];
  }
  function isMobile() {
    return window.matchMedia("(max-width: 576px)").matches;
  }
  function getFontSizes() {
    const mobile = isMobile();
    return {
      dataLabel: mobile ? "12px" : "16px",
      tooltip: mobile ? "12px" : "14px",
      title: mobile ? "16px" : "20px",
      subtitle: mobile ? "12px" : "14px",
      tooltipMaxW: mobile ? "280px" : "640px",
    };
  }
  const _measureCanvas = (function () {
    const c = document.createElement("canvas");
    return c.getContext ? c.getContext("2d") : null;
  })();
  function measureTextWidth(text, font) {
    if (!_measureCanvas) return text.length * 8;
    _measureCanvas.font = font || "600 16px sans-serif";
    return _measureCanvas.measureText(text).width;
  }
  function ciIncludes(haystack, needle) {
    if (!needle) return true;
    const h = String(haystack || "").toLocaleLowerCase();
    const n = String(needle || "").toLocaleLowerCase();
    return h.includes(n);
  }

  // ===== Datos inyectados por Blade =====
  const {
    treemapData: baseData = [],
    auditoriasMap = {},
    currentUserId = null,
    detalleBaseUrl = "",
    directoresPorDireccion = {},
    jefesPorDepartamento = {},
    userRole,
    userDeptId
  } = window.REPORT_DATA || {};

  const DETALLE_URL = (detalleBaseUrl || "").replace(/\/+$/, "");
  const _auditProgressCache = new Map(); // id -> { percent, done, total }
  const LS_KEY = "__auditProgressLSv1";
  const LS_TTL_MS = 15 * 60 * 1000; // 15 min

  function loadLsCache() {
    try { return JSON.parse(localStorage.getItem(LS_KEY) || "{}"); } catch { return {}; }
  }
  function saveLsCache(obj) {
    try { localStorage.setItem(LS_KEY, JSON.stringify(obj)); } catch { }
  }

  const dirNames = {
    dirA: 'Dirección de Seguimiento "A"',
    dirB: 'Dirección de Seguimiento "B"',
  };
  const deptsByDir = {
    dirA: (baseData || []).filter((p) => p.parent === "dirA"),
    dirB: (baseData || []).filter((p) => p.parent === "dirB"),
  };

  // === Timeout con AbortController para fetch ===
  async function fetchJsonWithTimeout(url, opts = {}, timeoutMs = 8000) {
    const ctrl = new AbortController();
    const id = setTimeout(() => ctrl.abort(), timeoutMs);
    try {
      return await fetchJson(url, { ...opts, signal: ctrl.signal });
    } finally {
      clearTimeout(id);
    }
  }

  // === Reintentos con backoff para errores 5xx/429 ===
  async function fetchJsonWithRetry(url, opts = {}, attempts = 2, backoffMs = 300, timeoutMs = 8000) {
    let lastErr = null;
    for (let i = 0; i <= attempts; i++) {
      try {
        return await fetchJsonWithTimeout(url, opts, timeoutMs);
      } catch (err) {
        lastErr = err;
        const status = err?.status || 0;
        // Reintenta solo si parece recuperable o timeout/abort
        const isRetryable = [0, 408, 429, 500, 502, 503, 504].includes(status);
        if (!isRetryable) break;
        if (i < attempts) {
          await new Promise(r => setTimeout(r, backoffMs * (i + 1))); // backoff incremental
        }
      }
    }
    throw lastErr;
  }

  // ====== fetch JSON robusto (evita HTML por login/redirect) ======
  async function fetchJson(url, opts = {}) {
    const res = await fetch(url, {
      credentials: "same-origin",
      headers: {
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      ...opts,
    });

    const ct = res.headers.get("content-type") || "";
    const isJson = ct.includes("application/json");

    if (!res.ok) {
      const text = await res.text().catch(() => "");
      const err = new Error(`HTTP ${res.status} ${res.statusText}`);
      err.status = res.status;
      err.body = text;
      throw err;
    }

    if (!isJson) {
      const text = await res.text().catch(() => "");
      if (text && text.trim().startsWith("<")) {
        const err = new Error("La respuesta no es JSON (posible redirect o error HTML).");
        err.body = text;
        throw err;
      }
      try {
        return JSON.parse(text);
      } catch (e) {
        const err = new Error("Respuesta no-JSON");
        err.body = text;
        throw err;
      }
    }

    return res.json();
  }

  // ====== Carga/loader del treemap (detalle) ======
  function injectLoadingStyles() {
    if (document.getElementById("audit-loading-styles")) return;
    const css = `
      .loader-overlay { position: absolute; inset: 0; display:flex; align-items:center; justify-content:center; background: rgba(255,255,255,0.8); z-index: 1200; }
      .loader-demo-box { width: 160px; height: 120px; border: 1px solid #f3f3f3; border-radius: 6px; display:flex; align-items:center; justify-content:center; background: #132a29; }
      .pixel-loader { width: 10px; height: 10px; background: linear-gradient(120deg,#A13B71,#960048); color: #960048; margin: 0 auto; box-shadow: 15px 15px 0 0 #A13B71, -15px -15px 0 0 #A13B71, 15px -15px 0 0 #A13B71, -15px 15px 0 0 #A13B71, 0 15px 0 0 #960048, 15px 0 0 0 #960048, -15px 0 0 0 #960048, 0 -15px 0 0 #960048; animation: audit-anim 2s linear infinite; }
      @keyframes audit-anim { 0% { filter: hue-rotate(0deg) } 50% { box-shadow: 20px 20px 0 0, -20px -20px 0 0, 20px -20px 0 0, -20px 20px 0 0, 0 10px 0 0, 10px 0 0 0, -10px 0 0 0, 0 -10px 0 0 } 75% { box-shadow: 20px 20px 0 0, -20px -20px 0 0, 20px -20px 0 0, -20px 20px 0 0, 0 10px 0 0, 10px 0 0 0, -10px 0 0 0, 0 -10px 0 0 } 100% { transform: rotate(360deg); filter: hue-rotate(360deg) } }
    `;
    const s = document.createElement("style");
    s.id = "audit-loading-styles";
    s.appendChild(document.createTextNode(css));
    document.head.appendChild(s);
  }
  function showLoading() {
    injectLoadingStyles();
    const chartEl = document.getElementById("chartInner");
    if (!chartEl) return;
    chartEl.style.minHeight = "220px";
    chartEl.style.position = "relative";
    if (chartEl.querySelector(".loader-overlay")) return;
    const overlay = document.createElement("div");
    overlay.className = "loader-overlay";
    overlay.innerHTML = `<div class="loader-demo-box"><div class="pixel-loader" aria-hidden="true"></div></div>`;
    chartEl.appendChild(overlay);
  }
  function hideLoading() {
    const chartEl = document.getElementById("chartInner");
    if (!chartEl) return;
    const overlay = chartEl.querySelector(".loader-overlay");
    if (overlay) overlay.remove();
    chartEl.style.minHeight = "";
    chartEl.style.position = "";
  }

  // ====== Avance auditoría (cálculo + tooltip) ======
  function isAuth(val) {
    return String(val ?? "").trim() == "Autorizado";
  }
  
  function getAuditSemaphore(av) {
    // av = { percent, done, total }
    if (!av || av.total === 0) return 'red';
    if (av.percent === 100) return 'green';
    return 'yellow';
  }

  async function mapWithConcurrency(items, limit, worker) {
    const ret = new Array(items.length);
    let idx = 0;
    const runners = new Array(Math.min(limit, items.length)).fill(0).map(async () => {
      while (idx < items.length) {
        const i = idx++;
        try {
          ret[i] = await worker(items[i], i);
        } catch (e) {
          console.warn("Error worker item", items[i], e);
          ret[i] = null;
        }
      }
    });
    await Promise.all(runners);
    return ret;
  }
  async function getAuditProgressPercent(audId) {
    if (_auditProgressCache.has(audId)) return _auditProgressCache.get(audId);
    if (!DETALLE_URL) return { percent: 0, done: 0, total: 0 };
    try {
      const aud = await fetchJson(`${DETALLE_URL}/${audId}`);
      const av = computeAuditProgress(aud);
      _auditProgressCache.set(audId, av);
      return av;
    } catch (e) {
      //console.error("Error obteniendo detalle de auditoría", audId, e);
      return { percent: 0, done: 0, total: 0 }; // fallback
    }
  }
  

async function computeDeptMetrics(deptId) {
  const url = `${DETALLE_URL}/metrics/${encodeURIComponent(deptId)}`;
  return await fetchJson(url);
}


  function computeAuditProgress(aud) {

    let total = 0,
      done = 0;

    // Desglose visible en el tooltip y, si gustas, para futuros KPIs
    const breakdown = {
      radicacion: { total: 0, done: 0 },
      comparecencia: { total: 0, done: 0 },
      acuerdosRecs: { total: 0, done: 0 },
      acuerdosPliegos: { total: 0, done: 0 },
      accionesRecs: { total: 0, done: 0 },
      accionesPO: { total: 0, done: 0 },
      accionesSol: { total: 0, done: 0 },
      accionesPRAS: { total: 0, done: 0 },
      informesRecs: { total: 0, done: 0 },
      informesPliegos: { total: 0, done: 0 },
      turnoUI: { total: 0, done: 0 },
      turnoOIC: { total: 0, done: 0 },
      turnoArchivo: { total: 0, done: 0 },
    };

    // Radicación
    if (aud.radicacion) {
      breakdown.radicacion.total++;
      total++;
      if (isAuth(aud.radicacion.fase_autorizacion)) {
        breakdown.radicacion.done++;
        done++;
      }
    }

    // Comparecencia
    if (aud.comparecencia) {
      breakdown.comparecencia.total++;
      total++;
      if (isAuth(aud.comparecencia.fase_autorizacion)) {
        breakdown.comparecencia.done++;
        done++;
      }
    }

    // Acuerdos de conclusión (Recomendaciones)
    toArr(aud.acuerdoconclusion).forEach((rec) => {
      breakdown.acuerdosRecs.total++;
      total++;
      if (isAuth(rec?.fase_autorizacion)) {
        breakdown.acuerdosRecs.done++;
        done++;
      }
    });

    // Acuerdos de conclusión (Pliegos)
    toArr(aud.acuerdoconclusionpliegos).forEach((pl) => {
      breakdown.acuerdosPliegos.total++;
      total++;
      if (isAuth(pl?.fase_autorizacion)) {
        breakdown.acuerdosPliegos.done++;
        done++;
      }
    });

    // Helper para acciones con relaciones (PRAS, PO, Solicitudes, Recs.)
    function countActionRel(list, relKey, bucketKey) {
      toArr(list).forEach((act) => {
        const rel = act?.[relKey];
        if (!rel) return;
        toArr(rel).forEach((r) => {
          breakdown[bucketKey].total++;
          total++;
          if (isAuth(r?.fase_autorizacion)) {
            breakdown[bucketKey].done++;
            done++;
          }
        });
      });
    }

    countActionRel(aud.accionesrecomendaciones, "recomendaciones", "accionesRecs");
    countActionRel(aud.accionespo, "pliegosobservacion", "accionesPO");
    countActionRel(aud.accionessolacl, "solicitudesaclaracion", "accionesSol");
    countActionRel(aud.accionespras, "pras", "accionesPRAS");

    // Informes (Recs. / Pliegos)
    toArr(aud.informeprimeraetapa).forEach((inf) => {
      breakdown.informesRecs.total++;
      total++;
      if (isAuth(inf?.fase_autorizacion)) {
        breakdown.informesRecs.done++;
        done++;
      }
    });

    toArr(aud.informepliegos).forEach((inf) => {
      breakdown.informesPliegos.total++;
      total++;
      if (isAuth(inf?.fase_autorizacion)) {
        breakdown.informesPliegos.done++;
        done++;
      }
    });

    // Turnos (UI / OIC / Archivo)
    [
      ["turnoui", "turnoUI"],
      ["turnooic", "turnoOIC"],
      ["turnoarchivo", "turnoArchivo"],
    ].forEach(([key, bKey]) => {
      const node = aud[key];
      if (!node) return;
      breakdown[bKey].total++;
      total++;
      if (isAuth(node?.fase_autorizacion)) {
        breakdown[bKey].done++;
        done++;
      }
    });

    const percent = total ? Math.round((done / total) * 100) : 0;
    return { percent, done, total, breakdown };
  }

  // ====== Encargados (cards A / B) ======
  function setEncargadosDireccionCard(side /* 'A' | 'B' */) {
    try {
      const dirKey = side === "A" ? "dirA" : "dirB";
      const dirTxt = dirNames[dirKey] || "—";
      const dirName = directoresPorDireccion?.[dirKey];

      const $dir = document.getElementById(`enc_dir${side}`);
      const $director = document.getElementById(`enc_director${side}`);
      if ($dir) $dir.replaceChildren(document.createTextNode(dirTxt));
      if ($director)
        $director.replaceChildren(document.createTextNode(dirName || "—"));

      const $d = document.getElementById(`encargadosDepto${side}`);
      const $j = document.getElementById(`encargadosJefe${side}`);
      const $de = document.getElementById(`enc_depto${side}`);
      const $je = document.getElementById(`enc_jefe${side}`);
      //$d && $d.classList.add("d-none");
      //$j && $j.classList.add("d-none");
      $de && $de.replaceChildren(document.createTextNode("—"));
      $je && $je.replaceChildren(document.createTextNode("—"));
    } catch (e) { }
  }
  function setEncargadosDepartamentoCard(side, deptId, deptName) {
    try {
      const jefe = jefesPorDepartamento?.[Number(deptId)];
      const $d = document.getElementById(`encargadosDepto${side}`);
      const $j = document.getElementById(`encargadosJefe${side}`);
      const $de = document.getElementById(`enc_depto${side}`);
      const $je = document.getElementById(`enc_jefe${side}`);
      //if ($d) $d.classList.remove("d-none");
      //if ($j) $j.classList.remove("d-none");
      $de && $de.replaceChildren(document.createTextNode(deptName || "—"));
      $je && $je.replaceChildren(document.createTextNode(jefe || "—"));
    } catch (e) { }
  }

  // ====== Filtros (solo en viewAuditorias) ======
  function toggleAuditFilters(show) {
    const card = document.getElementById("auditFiltersCard");
    if (!card) return;
    card.classList.toggle("d-none", !show);
  }
  function readFilters() {
    const form = document.getElementById("auditFiltersForm");
    if (!form) return { numero: "", entidad: "", acto: "" };
    const numero = (form.elements["numero_auditoria"]?.value || "").trim();
    const entidad = (form.elements["entidad_fiscalizable"]?.value || "").trim();
    const acto = (form.elements["acto_fiscalizacion"]?.value || "").trim();
    return { numero, entidad, acto, solo: true };
  }

  // ====== Neon skin + fondo animado para treemap ======
  function injectNeonSkinStyles() {
    const id = "treemap-neon-styles";
    if (document.getElementById(id)) return;
    const css = `

      :root{
        --bg1:#f7f8f9; --bg2:#ffffff;
        --aura: rgba(136,137,140,.22); --aura-strong: rgba(136,137,140,.34);
        --neon: rgba(187,148,92,.75); --neon-strong: rgba(187,148,92,1); --neon-hover: rgba(239,193,139,.95);
        --ink: #132a29; --muted: #888B86;
      }
      #chartInner.treemap-neon{
        position:relative; overflow:hidden; border-radius:20px;
        background:
          radial-gradient(1200px 800px at var(--mx,50%) var(--my,50%),
            color-mix(in srgb, var(--aura) 10%, transparent),
            color-mix(in srgb, var(--aura) 6%, transparent) 35%,
            color-mix(in srgb, var(--aura) 4%, transparent) 60%,
            transparent 72%),
          linear-gradient(180deg,var(--bg1),var(--bg2));
      }
      #chartInner.treemap-neon::before{
        content:''; position:absolute; inset:-2px;
        background:
          conic-gradient(from var(--rot,0deg),
            color-mix(in srgb, var(--aura-strong) 14%, transparent),
            transparent 40%,
            color-mix(in srgb, var(--aura-strong) 12%, transparent) 80%),
          radial-gradient(600px 400px at var(--mx,50%) var(--my,50%),
            color-mix(in srgb, var(--aura-strong) 9%, transparent),
            transparent 60%);
        filter: blur(14px) saturate(1.0);
        animation: treemap-spin 26s linear infinite;
        pointer-events: none; z-index:0;
      }
        .audit-title{
            display: flex;
            align-items: center;
            gap: 6px;
          }

          .audit-dot{
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
            flex: 0 0 auto;
          }

          .audit-dot.red{
            background: #dc2626;
          }

          .audit-dot.yellow{
            background: #f59e0b;
          }

          .audit-dot.green{
            background: #16a34a;
          }
      @keyframes treemap-spin { to { --rot: 360deg; } }
      #chartInner.treemap-neon .highcharts-container, #chartInner.treemap-neon svg{ position:relative; z-index:1; }
      #chartInner.treemap-neon, #chartInner.treemap-neon * { color: var(--ink); }
      #chartInner.treemap-neon .highcharts-data-label text,
      #chartInner.treemap-neon .highcharts-data-label span{ fill: var(--ink) !important; color: var(--ink) !important; }
      #chartInner.treemap-neon .highcharts-point{
        fill: transparent !important; stroke: var(--neon-strong); stroke-width: 3.8;
        filter: drop-shadow(0 0 6px color-mix(in srgb, var(--neon) 100%, transparent)) drop-shadow(0 0 14px color-mix(in srgb, var(--neon) 100%, transparent));
        transition: filter .18s ease, stroke-width .18s ease;
      }
      #chartInner.treemap-neon .highcharts-point.neon-hover{
        fill: color-mix(in srgb, var(--neon-hover) 8%, transparent) !important;
        stroke-width: 2.0;
        filter: drop-shadow(0 0 10px color-mix(in srgb, var(--neon-hover) 85%, transparent)) drop-shadow(0 0 22px color-mix(in srgb, var(--neon-hover) 60%, transparent));
      }
      #chartInner .highcharts-point.is-highlight{
        fill: rgba(239,193,139,.20) !important; stroke-width: 4 !important;
        filter: drop-shadow(0 0 10px rgba(239,193,139,.70)) drop-shadow(0 0 24px rgba(239,193,139,.50));
        animation: hi-pulse 1.6s ease-in-out 2;
      }
      @keyframes hi-pulse { 0%,100%{filter:drop-shadow(0 0 10px rgba(239,193,139,.70)) drop-shadow(0 0 24px rgba(239,193,139,.50));} 50%{filter:drop-shadow(0 0 14px rgba(239,193,139,.85)) drop-shadow(0 0 30px rgba(239,193,139,.65));}}
    `;
    const s = document.createElement("style");
    s.id = id;
    s.appendChild(document.createTextNode(css));
    document.head.appendChild(s);
  }
  function attachBgMotion(renderToId) {
    const el = document.getElementById(renderToId);
    if (!el) return;
    let raf = null;
    function onMove(ev) {
      const rect = el.getBoundingClientRect();
      const x = ((ev.clientX - rect.left) / rect.width) * 100;
      const y = ((ev.clientY - rect.top) / rect.height) * 100;
      if (raf) cancelAnimationFrame(raf);
      raf = requestAnimationFrame(() => {
        el.style.setProperty("--mx", x + "%");
        el.style.setProperty("--my", y + "%");
      });
    }
    el.addEventListener("mousemove", onMove);
    el.addEventListener("mouseleave", () => {
      el.style.removeProperty("--mx");
      el.style.removeProperty("--my");
    });
  }

  // ====== Treemap genérico (para auditorías) ======
  function fitHtmlLabels(hcChart, clampLines) {
    const chart = hcChart;
    if (!chart || !chart.series || !chart.series.length) return;
    const s = chart.series[0];
    const bw = s && s.options && s.options.borderWidth ? s.options.borderWidth : 0;
    const pad = 8;
    (s.points || []).forEach((p) => {
      if (!p || !p.shapeArgs || !p.dataLabel) return;
      const sh = p.shapeArgs;
      const el = p.dataLabel.div || p.dataLabel.element || p.dataLabel;
      if (!el) return;
      const maxW = Math.max(12, sh.width - pad - bw * 1.5);
      const maxH = Math.max(12, sh.height - pad - bw * 1.5);
      const inner = el.querySelector(".hc-label-inner");
      if (!inner) return;
      inner.style.maxWidth = maxW + "px";
      inner.style.maxHeight = maxH + "px";
      inner.style.overflow = "hidden";
      inner.style.whiteSpace = "normal";
      inner.style.lineHeight = "1.2";
      inner.style.textAlign = "center";
      inner.style.display = "-webkit-box";
      inner.style.webkitBoxOrient = "vertical";
      inner.style.webkitLineClamp = String(clampLines || 3);
      const wrap = el.querySelector(".hc-label-wrap");
      if (wrap) {
        wrap.style.width = Math.max(0, sh.width - bw * 0.5) + "px";
        wrap.style.height = Math.max(0, sh.height - bw * 0.5) + "px";
        wrap.style.display = "flex";
        wrap.style.alignItems = "center";
        wrap.style.justifyContent = "center";
        wrap.style.padding = "4px";
        wrap.style.pointerEvents = "none";
      }
    });
  }
  //console.log('[renderPage] nodes a pintar:', nodes.length, nodes.slice(0, 3));
  function drawTreemapIn(renderToId, title, subtitle, nodes, onPointClick, options = {}) {
    if (chart) {
      chart.destroy();
      chart = null;
    }
    const FS = getFontSizes();
    chart = Highcharts.chart(renderToId, {
      colors: ['#fff', '#A13B71', '#BB945C', '#612344', '#EFc18B', '#6b7280', '#132a29', '#9CA3AF'],
      chart: {
        spacing: [10, 10, 10, 10],
        backgroundColor: "transparent",
        plotBackgroundColor: "transparent",
        height: options.height || null,
      },
      events: {
        render: function () {
          const lines =
            options.labelClamp != null
              ? options.labelClamp
              : typeof (
                this.series &&
                this.series[0] &&
                this.series[0].points &&
                this.series[0].points[0] &&
                this.series[0].points[0].real
              ) !== "undefined"
                ? 2
                : 3;
          fitHtmlLabels(this, lines);
        },
      },
      title: { text: title, align: "center", style: { fontSize: FS.title, color: "#132a29" } },
      subtitle: { text: subtitle || "", align: "center", style: { fontSize: FS.subtitle, color: "#132a29" } },
      credits: { enabled: false },
      
      exporting: {
        enabled: false,
        buttons: {
          contextButton: {
            symbolStroke: '#BB955C', // opcional: color del ícono
          }
        }
      },
      series: [
        {
          type: "treemap",
          sort: false,                              //no re-ordenes lo que ya te mandé
          layoutAlgorithm: "sliceAndDice",          // orden legible (respeta secuencia)
          layoutStartingDirection: "vertical",    // primero filas horizontales
          alternateStartingDirection: false,        // sin alternancia (lectura natural)
          borderRadius: 8,                          // puedes bajar a 6 si quieres más compacto
          borderWidth: 6.5,                         // antes 10.2 → reduce “marcos”
          borderColor: "#BB945C",
          pointPadding: 2,                        // antes 0 → deja un respirito sutil
          nodePadding: 1.5,                           // puedes bajar a 1.5 si aún ves huecos
          colorByPoint: false,
          states: { hover: { enabled: true, brightness: 0 } },
          cursor: 'pointer',
          point: {
              events: {
                click: function () {
                  // 1) deselecciona los demás
                  this.series.points.forEach(p => {
                    if (p.selected) p.select(false, false);
                  });

                  // 2) selecciona este punto
                  this.select(true, false);

                  // 3) lógica original
                  if (onPointClick) onPointClick(this);
                }
              }
          },

          // niveles (si sigues usando)
          levels: [{
            level: 1,
            borderColor: '#BB945C',
            borderWidth: 6.5
          }],

          dataLabels: {
            enabled: true,
            useHTML: true,
            inside: true,
            allowOverlap: false,
            padding: 2,
            style: {
              textOutline: "none",
              fontWeight: "600",
              fontSize: options.labelFontSize || getFontSizes().dataLabel, // ya subimos fuente arriba
              color: "#132a29"
            },
            
          allowPointSelect: true,
          states: {
            select: {
              color: 'rgba(239,193,139,0.25)',
              borderColor: '#EFc18B',
              borderWidth: 5
            },
            hover: {
              enabled: true,
              brightness: 0
            }
          },

            formatter: function () {
              const p = this.point;
              const clamp = (txt, max = 140) => {
                txt = String(txt || "").trim();
                return txt.length > max ? txt.slice(0, max - 1) + "…" : txt;
              };
              // — Primer renglón: No. Auditoría (ID visible si lo deseas)
              const noAud = p.numero_auditoria ? esc(clamp(p.numero_auditoria, 34)) : "";
              //const noAud = p.numero_auditoria ? esc(clamp(p.numero_auditoria, 34)) : (p.id ? esc(String(p.id)) : "");
              
              // — Segundo renglón: Entidad (o fallback)
              const entidad = p.nombre_entidad
                  ? esc(clamp(p.nombre_entidad, 100))
                  : p.entidad_fiscalizable
                    ? esc(clamp(p.entidad_fiscalizable, 100))
                    : "";
              // — Tercer renglón: Acto (si existe)
              const acto = p.acto_fiscalizacion ? esc(clamp(p.acto_fiscalizacion, 70)) : "";

              const lines = [noAud, entidad, acto].filter(Boolean);
              //const html = lines.map(l => `<div>${l}</div>`).join("");
              
              const dot = `<span class="audit-dot ${p.semaphore}"></span>`;
              const html = lines.map((l, i) =>
                i === 0
                  ? `<div class="audit-title">${dot}${l}</div>`
                  : `<div>${l}</div>`
              ).join("");

              return `<div class="hc-label-wrap"><div class="hc-label-inner">${html}</div></div>`;
            },
          },

          tooltip: {
            useHTML: true,
            enabled: true,
            followPointer: true,
            backgroundColor: "rgba(255,255,255,.96)",
            borderWidth: 0,
            borderRadius: 6,
            shadow: false,
            style: { color: "#132a29", fontSize: getFontSizes().tooltip, maxWidth: getFontSizes().tooltipMaxW, whiteSpace: "normal" },
            
            pointFormatter: function () {
              try {
                const p = this;
                const h = [`<div style="font-weight:700;margin-bottom:6px;color:#132a29;">${esc(p.name || "")}</div><br>`];
                const entidad = p.nombre_entidad || p.entidad_fiscalizable;
                if (entidad) h.push(`<div><b>Entidad:</b> ${esc(String(entidad))}</div><br>`);
                if (p.acto_fiscalizacion) h.push(`<div><b>Acto:</b> ${esc(String(p.acto_fiscalizacion))}</div><br>`);
                return `<div style="max-width:${getFontSizes().tooltipMaxW};white-space:normal;color:#132a29;">${h.join("")}</div><br>`;
              } catch (e) { return ""; }
            }
          },

          data: nodes
        }
      ],

      responsive: {
        rules: [
          {
            condition: { maxWidth: 576 },
            chartOptions: {
              title: { style: { fontSize: "16px" } },
              subtitle: { style: { fontSize: "12px" } },
              series: [{ dataLabels: { style: { fontSize: "12px" }, padding: 1 } }],
              tooltip: { style: { fontSize: "12px", maxWidth: "280px" } },
            },
          },
          {
            condition: { maxWidth: 768 },
            chartOptions: {
              title: { style: { fontSize: "18px" } },
              subtitle: { style: { fontSize: "13px" } },
              series: [{ dataLabels: { style: { fontSize: "14px" } } }],
              tooltip: { style: { fontSize: "13px", maxWidth: "400px" } },
            },
          },
        ],
      },
    });
    try {
      // Evita que las etiquetas HTML intercepten el click
        const disableClicksOnLabels = () => {
          chart?.container?.querySelectorAll(
            '.highcharts-data-label, .highcharts-label, .hc-label-wrap, .hc-label-inner'
          )?.forEach(el => { el.style.pointerEvents = 'none'; });
        };

        // Llamar al inicio y en cada render (por si Highcharts re-crea labels)
        disableClicksOnLabels();
        Highcharts.addEvent(chart, 'render', disableClicksOnLabels);

      const chartContainer = chart && chart.container;
      if (chartContainer) {
        const hideEmptyTooltip = () => {
          const t = chartContainer.querySelector(".highcharts-tooltip");
          if (t) {
            if (!t.textContent || t.textContent.trim() === "") t.style.display = "none";
            else t.style.display = "";
          }
        };
        const mo = new MutationObserver(hideEmptyTooltip);
        mo.observe(chartContainer, { childList: true, subtree: true });
        chartContainer.addEventListener("mousemove", hideEmptyTooltip);
        const origDestroy = chart.destroy;
        chart.destroy = function () {
          try {
            mo.disconnect();
            chartContainer.removeEventListener("mousemove", hideEmptyTooltip);
          } catch (e) { }
          origDestroy.apply(this, arguments);
        };
      }
    } catch (e) { }
    attachBgMotion(renderToId);
  }

  // ====== Gauge multi-anillo (genérico) ======
  /**
   * renderDeptSolidGauge(dirKey, mountId, options)
   * options.useSideInfo: si true, usa panel lateral (tooltip Highcharts deshabilitado)
   */
  async function renderDeptSolidGauge(dirKey, mountId, options = {}) {
    const mount = document.getElementById(mountId);
    if (!mount) {
      //console.warn(`[DeptGauge] No existe #${mountId}`);
      return;
    }
    const useSideInfo = !!options.useSideInfo;

    // IDs de wrap/info segun mountId (para el gauge derecho ya existen en HTML)
    const wrapId = mountId === "grafica_deptos" ? "grafica_deptos_wrap" : `${mountId}_wrap`;
    const infoId = mountId === "grafica_deptos" ? "grafica_deptos_info" : `${mountId}_info`;

    // Asegura wrap lateral SOLO si useSideInfo=true
    if (useSideInfo) {
      let wrap = document.getElementById(wrapId);
      let info = document.getElementById(infoId);
      if (!wrap) {
        const fig = mount.closest("figure") || mount;
        wrap = document.createElement("div");
        wrap.id = wrapId;
        wrap.className = "dept-gauge-wrap";
        fig.parentNode.insertBefore(wrap, fig);
        wrap.appendChild(fig);
      }
      if (!info) {
        info = document.createElement("div");
        info.id = infoId;
        info.className = "dept-gauge-info";
        info.innerHTML = `<div class="muted">Pasa el cursor sobre un anillo para ver los detalles…</div>`;
        wrap.appendChild(info);
      }
    }

    // Loader local
    const prevHTML = mount.innerHTML;
    mount.style.minHeight = "220px";
    mount.innerHTML = `
      <div style="display:flex;align-items:center;justify-content:center;height:100%;">
        <div class="swapping-squares-spinner" aria-label="Cargando">
          <div class="square"></div><div class="square"></div><div class="square"></div><div class="square"></div>
        </div>
      </div>`;

    try {
      if (!window.Highcharts || !Highcharts.chart) return;

      // 3 depts fijos por dirección (si faltan, placeholders)
      const baseDepts = (deptsByDir?.[dirKey] || []).slice(0, 3);
      const depts = baseDepts.slice();
      while (depts.length < 3) depts.push({ name: "—", deptId: null });

      // Métricas por depto
      const results = await Promise.all(
        depts.map((d) =>
          d.deptId
            ? computeDeptMetrics(d.deptId)
            : Promise.resolve({ avgPercent: 0, completed: 0, total: 0, completedPercent: 0 })
        )
      );

      const brand = { ink: "#fff", magenta: "#612344", gold: "#960048", accent: "#A13B71" };
      const ringColors = [brand.magenta, brand.gold, brand.accent];
      const vals = results.map((m) => m.avgPercent);
      const names = depts.map((d, i) => d.name || ((dirKey === "dirA" ? "A" : "B") + (i + 1)));

      // Panel lateral (solo derecho)
      const infoEl = useSideInfo ? document.getElementById(infoId) : null;
      function updateSideInfo(idx) {
        if (!infoEl) return;
        const d = depts[idx] || {};
        const m = results[idx] || { avgPercent: 0, completed: 0, total: 0, completedPercent: 0 };
        const prefix = dirKey === "dirA" ? "A" : "B";
        const color = ringColors[idx % ringColors.length];
        infoEl.innerHTML = `
          <h6>${esc(dirNames?.[dirKey] || "")}</h6>
          <div class="kpi"><strong>${esc(d.name || `${prefix}${idx + 1}`)}</strong></div>
          <div class="kpi"><span class="muted">Total de auditorías:</span>${m.total}</div>
          <div class="kpi"><span class="muted">Avance:</span><span class="big" style="color:${color}">${m.avgPercent}%</span></div>
          <div class="kpi"><span class="muted">Completadas:</span> ${m.completed} / ${m.total} (${m.completedPercent}%)</div>
        `;
      }

      // Render del gauge
      mount.innerHTML = "";
      const gChart = Highcharts.chart(mountId, {
        chart: {
          type: "solidgauge",
          backgroundColor: "#FFFFFF",
          clip: false,
          events: {
            render: function () {
              // Etiquetas fijas (A1/A2/A3 o B1/B2/B3)
              const c = this;
              const probe = c.series?.[0]?.points?.[0];
              if (!probe?.shapeArgs || isNaN(probe.shapeArgs.r) || isNaN(probe.shapeArgs.innerR)) return;
              (c.series || []).forEach((ser, i) => {
                const p = ser.points?.[0];
                if (!p?.shapeArgs) return;
                const sa = p.shapeArgs;
                const midY = (c.plotHeight / 2) - sa.innerR - ((sa.r - sa.innerR) / 2) + 8;
                const posX = (c.chartWidth / 2) - 15;
                const label = `${dirKey === "dirA" ? "A" : "B"}${i + 1}`;
                if (!ser._fixedLabel) {
                  ser._fixedLabel = c.renderer
                    .text(label, posX, midY)
                    .attr({ zIndex: 10 })
                    .css({ color: brand.ink, fontWeight: 700, fontSize: "12px" })
                    .add(c.series[Math.min(2, c.series.length - 1)].group);
                } else {
                  ser._fixedLabel.attr({ x: posX, y: midY });
                }
              });
            },
          },
        },
        title: { text: "Avance por Depto.", style: { fontSize: "14px", color: "#132a29", fontWeight: 700 } },
        subtitle: { text: dirNames?.[dirKey] || "", style: { fontSize: "12px", color: "#132a29" } },
        tooltip: { enabled: !useSideInfo }, // si hay panel lateral, desactiva tooltip
        pane: {
          clip: false,
          startAngle: 0,
          endAngle: 360,
          background: [
            { outerRadius: "112%", innerRadius: "88%", backgroundColor: Highcharts.color(ringColors[0]).setOpacity(0.18).get("rgba"), borderWidth: 0 },
            { outerRadius: "87%", innerRadius: "63%", backgroundColor: Highcharts.color(ringColors[1]).setOpacity(0.18).get("rgba"), borderWidth: 0 },
            { outerRadius: "62%", innerRadius: "38%", backgroundColor: Highcharts.color(ringColors[2]).setOpacity(0.18).get("rgba"), borderWidth: 0 },
            
          ],
          
        },
        yAxis: { min: 0, max: 100, lineWidth: 0, tickPositions: [] },
        plotOptions: {
          solidgauge: { dataLabels: { enabled: false }, linecap: "round", stickyTracking: false, rounded: true },
          series: {
            clip: false,
            animation: { duration: 300 },
            cursor: "pointer",
            point: {
              events: {
                mouseOver: function () {
                  const idx = this.series.index;
                  if (useSideInfo) updateSideInfo(idx);
                },
                click: function () {
                  const idx = this.series.index;
                  const d = depts[idx];
                  if (!d?.deptId) return;
                  const side = dirKey === "dirA" ? "A" : "B";
                  const panel = document.getElementById("auditoriasPanel");
                  if (panel) panel.classList.add("d-none");
                  resetAvanceCard();
                  setEncargadosDepartamentoCard(side, d.deptId, d.name);
                  viewAuditorias(dirKey, d.deptId, dirNames[dirKey], d.name);
                  const figure = document.getElementById("container");
                  try { figure?.scrollIntoView({ behavior: "smooth", block: "start" }); } catch (e) { }
                },
              },
            },
          },
        },
        credits: { enabled: false },
        exporting: {
          enabled: false,
          buttons: {
            contextButton: {
              symbolStroke: '#BB955C', // opcional: color del ícono
            }
          }
        },
        series: [
          { name: names[0], data: [{ color: ringColors[0], radius: "112%", innerRadius: "88%", y: vals[0] }] },
          { name: names[1], data: [{ color: ringColors[1], radius: "87%", innerRadius: "63%", y: vals[1] }] },
          { name: names[2], data: [{ color: ringColors[2], radius: "62%", innerRadius: "38%", y: vals[2] }] },
          
        ],
      });
      // Altura responsiva del gauge
      function setGaugeHeightByWidth(chart, minH = 240, maxH = 360) {
        const cont = chart?.container?.parentElement;
        if (!cont) return;
        const w = cont.getBoundingClientRect().width || 320;
        const h = Math.max(minH, Math.min(maxH, Math.round(w * 0.9)));
        chart.update({ chart: { height: h } }, false);
        chart.reflow();
      }
      setGaugeHeightByWidth(gChart);
      requestAnimationFrame(() => { try { gChart.reflow(); } catch (e) { } });

      // Auto-stack (solo si hay panel lateral)
      if (useSideInfo) {
        const wrapEl = document.getElementById(wrapId) || mount.parentElement;
        const infoEl2 = document.getElementById(infoId);
        const gap = 14;
        function autoStackGaugeLayout() {
          if (!wrapEl || !infoEl2) return;
          const need = 280 /* figure min */ + 200 /* panel min */ + gap;
          const wrapW = wrapEl.getBoundingClientRect().width;
          if (!wrapW) return;
          if (wrapW < need) wrapEl.classList.add("is-stacked");
          else wrapEl.classList.remove("is-stacked");
        }
        autoStackGaugeLayout();
        if ("ResizeObserver" in window) {
          const ro = new ResizeObserver(() => {
            autoStackGaugeLayout();
            setGaugeHeightByWidth(gChart);
            try { gChart.reflow(); } catch (e) { }
          });
          ro.observe(wrapEl);
          ro.observe(infoEl2);
          const _destroy = gChart.destroy;
          gChart.destroy = function () {
            try { ro.disconnect(); } catch (e) { }
            _destroy.apply(this, arguments);
          };
        } else {
          window.addEventListener("resize", () => {
            autoStackGaugeLayout();
            setGaugeHeightByWidth(gChart);
          });
        }
        // Muestra por defecto el primer anillo en el panel
        updateSideInfo(0);
      }
    } catch (e) {
      //console.error("Error renderDeptSolidGauge", e);
      mount.innerHTML = prevHTML;
    }
    
  }
  /******JEFES DE DEPARTAMENTO*********/
    async function initJefeDepartamentoView() {
      showJDSpinner(); //Spinner 

      const deptId = userDeptId;
      if (!deptId) return;

      let dirKey = null;
      let dept = null;

      if (deptsByDir.dirA.some(d => d.deptId === deptId)) {
        dirKey = "dirA";
        dept = deptsByDir.dirA.find(d => d.deptId === deptId);
      }
      if (deptsByDir.dirB.some(d => d.deptId === deptId)) {
        dirKey = "dirB";
        dept = deptsByDir.dirB.find(d => d.deptId === deptId);
      }

      if (!dirKey || !dept) return;

      const metrics = await computeDeptMetrics(deptId);

      renderJefeDepartamentoBlock({
        dirName: dirNames[dirKey],
        deptName: dept.name,
        metrics
      });

      // Renderiza directamente sus auditorías
      viewAuditorias(dirKey, deptId, dirNames[dirKey], dept.name);
    }

    
    function showJDSpinner() {
      const el = document.getElementById('jefesdiv');
      if (!el) return;

      el.innerHTML = `
        <div class="d-flex justify-content-center align-items-center" style="min-height: 180px;">
          <div class="swapping-squares-spinner" aria-label="Cargando">
            <div class="square"></div>
            <div class="square"></div>
            <div class="square"></div>
            <div class="square"></div>
          </div>
        </div>
      `;
    }

    function hideJDSpinner() {
      const el = document.getElementById('jefesdiv');
      if (!el) return;
      el.innerHTML = '';
    }

     function renderJefeDepartamentoBlock({ dirName, deptName, metrics }) {
      const el = document.getElementById('jefesdiv');
      if (!el) return;

      el.innerHTML = `
        <div class="card fancy-border mb-3">
          <div class="card-body">

            <h5 class="mb-2">
              ${dirName} / <b>${deptName}</b>
            </h5>

            <div class="mb-2">
              <b>Total de auditorías del departamento:</b>
              <span>${metrics.total}</span>
            </div>

            <div class="mb-1">
              <b>Avance promedio del departamento:</b>
              ${metrics.avgPercent}%
            </div>

            <div class="progress" style="height: 10px;">
              <div class="progress-bar bg-success"
                  role="progressbar"
                  style="width: ${metrics.avgPercent}%"
                  aria-valuenow="${metrics.avgPercent}"
                  aria-valuemin="0"
                  aria-valuemax="100">
              </div>
            </div>

            <div class="mt-2 muted">
              Auditorías completadas:
              ${metrics.completed} / ${metrics.total}
              (${metrics.completedPercent}%)
            </div>

          </div>
        </div>
      `;
    }

  // ====== Shell del treemap (contenedor) ======
  const container = document.getElementById("container");
  function renderShell({ titleHTML = "", minHeight = 680 } = {}) {
    container.innerHTML = `
      ${titleHTML}
      <div id="chartInner" class="treemap-neon" style="min-height: ${minHeight}px;"></div>
      <div id="auditPagination" class="mt-2"></div>
    `;
  }

  // ====== Reset de barra de avance arriba ======
  function resetAvanceCard() {
    const t = document.getElementById("avanceAuditoria");
    if (t) t.textContent = "—";
    const b = document.getElementById("avanceBar");
    const a = document.getElementById("audi_name");
    if (a) a.textContent = "—";
    if (b) {
      b.style.width = "0%";
      b.setAttribute("aria-valuenow", "0");
      b.classList.remove("bg-success", "bg-warning", "bg-danger");
      b.classList.add("bg-warning");
    }
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

    // Al limpiar depto, también se limpia auditoría
    clearPdfAuditState();
}

  function setAuditoriaSeleccionada(audId, AudName) {
    try {
      document.getElementById("audi_name")?.replaceChildren(document.createTextNode(AudName || "—"));
    } catch (e) { }
  }

  // ====== Progreso tooltip en avance de auditoría (UI superior) ======
  function buildProgressTooltip(av) {
    const b = av.breakdown;
    const icon = (d) => (d.total ? (d.done === d.total ? "✅" : "⏳") : "—");
    const row = (label, d) => `<div><b>${label}:</b> ${d.done}/${d.total} ${icon(d)}</div>`;
    return `
      <div style="min-width:260px">
        ${row("Radicación", b.radicacion)}
        ${row("Comparecencia", b.comparecencia)}
        <hr style="margin:6px 0"/>
        ${row("Acuerdos (Recs.)", b.acuerdosRecs)}
        ${row("Acuerdos (Pliegos)", b.acuerdosPliegos)}
        <hr style="margin:6px 0"/>
        ${row("Acciones (Recs.)", b.accionesRecs)}
        ${row("Acciones (PO)", b.accionesPO)}
        ${row("Acciones (Sol.)", b.accionesSol)}
        ${row("Acciones (PRAS)", b.accionesPRAS)}
        <hr style="margin:6px 0"/>
        ${row("Informes (Recs.)", b.informesRecs)}
        ${row("Informes (Pliegos)", b.informesPliegos)}
        <hr style="margin:6px 0"/>
        ${row("Turno UI", b.turnoUI)}
        ${row("Turno OIC", b.turnoOIC)}
        ${row("Turno Archivo", b.turnoArchivo)}
      </div>
    `;
  }
  function applyProgressTooltip(targetEl, av) {
    if (!targetEl) return;
    const html = buildProgressTooltip(av);
    try {
      if (targetEl._avTooltip && typeof targetEl._avTooltip.dispose === "function") {
        targetEl._avTooltip.dispose();
      }
    } catch (e) { }
    targetEl.setAttribute("data-bs-toggle", "tooltip");
    targetEl.setAttribute("data-bs-html", "true");
    targetEl.setAttribute("data-bs-placement", "bottom");
    targetEl.setAttribute("title", html);
    if (window.bootstrap && bootstrap.Tooltip) {
      targetEl._avTooltip = new bootstrap.Tooltip(targetEl, {
        container: "body",
        trigger: "hover focus",
        html: true,
        sanitize: false,
        boundary: "window",
      });
    } else {
      targetEl.setAttribute("title", html.replace(/<[^>]+>/g, "").replace(/\s+/g, " ").trim());
    }
  }

  // ====== Vista de Auditorías (treemap por depto) ======
  function viewAuditorias(dirKey, deptId, dirName, deptName) {
    document.getElementById('treemapFigure')?.classList.remove('d-none');

    const container = document.getElementById('container');
    if (container) {
      container.innerHTML = TREEMAP_GAUGE_SPINNER_HTML;
    }


    // Deja a la mano para el PDF
    window.__pdfDeptId = deptId;
    window.__pdfDeptName = deptName;
    window.__pdfDirName = dirName;

    const titleHTML = `
    <div class="mb-2">
      <h6 class="mb-1">Auditorías — <span>${esc(dirName)}</span> / <span>${esc(deptName)}</span></h6>
      <hr class="fancy-border">
    </div>
  `;
    //renderShell({ titleHTML });

    // Mostrar el contenedor del treemap si estaba oculto
    const fig = document.getElementById('treemapFigure');
    if (fig) fig.classList.remove('d-none');

    resetAvanceCard();

    // Actualiza Encargados (depende de la dirección)
    const side = dirKey === "dirA" ? "A" : "B";
    setEncargadosDepartamentoCard(side, deptId, deptName);

    const items = auditoriasMap[String(deptId)] || [];
    const originalItems = items.slice(); // fuente inmutable para filtros
    let activeItems = items.slice();     // dataset visible/filtrado

    const perPage = 12;
    let totalPages = Math.max(1, Math.ceil(activeItems.length / perPage));
    let currentPage = 1;
    let highlightId = null;

    // ====== Estado de vista por depto (paginación) ======
    try {
      const s = JSON.parse(sessionStorage.getItem("auditoriaViewState") || "null");
      if (
        s &&
        s.userId === currentUserId &&
        s.pathname === window.location.pathname &&
        String(s.deptId) === String(deptId) &&
        s.page
      ) {
        currentPage = Math.min(Math.max(1, parseInt(s.page, 10) || 1), totalPages);
      } else {
        try { sessionStorage.removeItem("auditoriaViewState"); } catch (e) { }
      }
    } catch (e) { }

    function saveViewState(page) {
      try {
        sessionStorage.setItem(
          "auditoriaViewState",
          JSON.stringify({
            dirKey, deptId, dirName, deptName,
            page, pathname: window.location.pathname,
            userId: currentUserId,
          })
        );
      } catch (e) { }
    }

    // ====== Filtros ======
    // Mostramos tarjeta de filtros en esta vista
    toggleAuditFilters(true);

    // Guardamos el último depto/dirección para decidir si reseteamos los inputs
    window.__lastDeptKey = window.__lastDeptKey || null;
    const thisDeptKey = `${dirKey}-${deptId}`;

    /**
     * Clona el form para eliminar listeners previos y vuelve a enlazar handlers
     * con el dataset del depto actual.
     */
    function rewireFilters() {
      const form = document.getElementById("auditFiltersForm");
      if (!form) return;

      // 1) Reset de inputs si cambiaste de depto/dirección
      if (window.__lastDeptKey !== thisDeptKey) {
        try { form.reset(); } catch (e) { }
        window.__lastDeptKey = thisDeptKey;
      }

      // 2) Clonar para quitar listeners antiguos y evitar submits al servidor
      const parent = form.parentNode;
      const newForm = form.cloneNode(true);
      parent.replaceChild(newForm, form);

      const btnClear = newForm.querySelector("#f_limpiar");

      // 3) Submit (Buscar): filtra SIEMPRE en cliente, sin recargar
      newForm.addEventListener("submit", (ev) => {
        ev.preventDefault();
        ev.stopPropagation();

        const f = readFilters(); // { numero, entidad, acto, solo: true }

        // Sin criterios → restaurar dataset original
        if (!f.numero && !f.entidad && !f.acto) {
          activeItems = originalItems.slice();
          totalPages = Math.max(1, Math.ceil(activeItems.length / perPage));
          highlightId = null;
          renderPage(1);
          return;
        }

        // Coincidencias case-insensitive
        const matches = originalItems.filter((a) =>
          ciIncludes(a.numero_auditoria, f.numero) &&
          (ciIncludes(a.entidad_fiscalizable, f.entidad) || ciIncludes(a.nombre_entidad, f.entidad)) &&
          ciIncludes(a.acto_fiscalizacion, f.acto)
        );

        if (f.solo) {
          // Mostrar SOLO coincidencias
          activeItems = matches;
          totalPages = Math.max(1, Math.ceil(activeItems.length / perPage));
          highlightId = matches.length ? "aud" + matches[0].id : null;
          renderPage(1, highlightId);
        } else {
          // (modo no-solo) Ir a la página donde está la primera coincidencia
          if (!matches.length) {
            highlightId = null;
            renderPage(currentPage);
            return;
          }
          const firstId = matches[0].id;
          const idx = originalItems.findIndex((x) => x.id === firstId);
          const page = Math.max(1, Math.floor(idx / perPage) + 1);
          activeItems = originalItems.slice();
          totalPages = Math.max(1, Math.ceil(activeItems.length / perPage));
          highlightId = "aud" + firstId;
          renderPage(page, highlightId);
        }
      });

      // 4) Limpiar: no recarga, solo restaura dataset/paginación
      btnClear?.addEventListener("click", (ev) => {
        ev.preventDefault();
        ev.stopPropagation();
        try { newForm.reset(); } catch (e) { }

        activeItems = originalItems.slice();
        totalPages = Math.max(1, Math.ceil(activeItems.length / perPage));
        highlightId = null;
        renderPage(1);
      });
    }

    // ====== Render (treemap + paginación) ======
    async function renderPage(page, forceHighlightId) {
      currentPage = page || 1;
      saveViewState(currentPage);

      const start = (currentPage - 1) * perPage;
      const pageItems = activeItems.slice(start, start + perPage);

      if (forceHighlightId) highlightId = forceHighlightId;

      //ORDENAR pageItems existe
      const ordered = pageItems.slice().sort((a, b) => {
        const ai = Number(a.id) || 0;
        const bi = Number(b.id) || 0;
        return ai - bi; // ASC por ID
      });

      let nodes;
      if (!ordered.length) {
        nodes = [{ id: "no-data", name: "Sin auditorías", value: 1, color: "#f8d7da" }];
      } else {
        
          const progressMap = new Map();

          await Promise.all(
            ordered.map(async (a) => {
              try {
                const av = await getAuditProgressPercent(a.id);
                progressMap.set(a.id, av);
              } catch {
                progressMap.set(a.id, { percent: 0, total: 0 });
              }
            })
          );

        
          nodes = ordered.map((a) => {
            const av = progressMap.get(a.id) || { percent: 0, total: 0 };
            const semaforo = getAuditSemaphore(av);

            return {
              id: "aud" + a.id,
              sortIndex: Number(a.id) || 0,
              numero_auditoria: a.numero_auditoria,
              entidad_fiscalizable: a.entidad_fiscalizable,
              nombre_entidad: a.nombre_entidad,
              acto_fiscalizacion: a.acto_fiscalizacion,
              name: a.numero_auditoria ? a.numero_auditoria : "AUD-" + a.id,
              value: 1,
              semaphore: semaforo,
              className: highlightId === "aud" + a.id ? "is-highlight" : "",
            };
          });

      }
      renderShell({ titleHTML });
      drawTreemapIn(
        "chartInner",
        `${dirName} / ${deptName}`,
        activeItems.length ? "Auditorías" : "Sin resultados",
        nodes,
        (point) => {
          if (!point || !point.id || String(point.id).startsWith("no-data")) return;
          const audId = String(point.id).replace(/^aud/, "");
          if (!audId) return;
          try { showLoading(); } catch (e) { }
          fetchJson(`${DETALLE_URL}/${audId}`)
            .then((aud) => {
              hideLoading();
              renderAuditDetails(aud, dirName, deptName);
            })
            .catch((err) => {
              hideLoading();
              //console.error("Error cargando auditoría", err);
              const panel = document.getElementById("auditoriasPanel");
              if (panel) panel.classList.add("d-none");
              SwalBT.fire({
                icon: 'error',
                title: 'No se pudo cargar',
                html: 'Verifica tu sesión o la URL del servicio.<br>Si el problema persiste, intenta refrescar la página.',
                confirmButtonText: 'Entendido'
              });
            });
        },
        {
          height: 800,
          minPointSize: 8,
          labelFontSize: "16px",
          labelClamp: 3
        }
      );
      // ===== Paginación =====
      const pager = document.getElementById("auditPagination");
      if (!pager) return;
      pager.innerHTML = "";

      if (totalPages > 1) {
        let html = '<nav aria-label="Paginación auditorías"><ul class="pagination pagination-sm">';
        for (let p = 1; p <= totalPages; p++) {
          html += `
            <li class="page-item ${p === currentPage ? "active" : ""}">
              <a href="#" class="page-link" data-page="${p}">${p}</a>
            </li>`;
        }
        html += "</ul></nav>";
        pager.innerHTML = html;

        pager.querySelectorAll(".page-link").forEach((el) => {
          el.addEventListener("click", (ev) => {
            ev.preventDefault();
            renderPage(parseInt(el.dataset.page, 10) || 1);
          });
        });
      }
    }

    // Importante: primero re-alambramos el form (para cortar handlers viejos), luego render.
    rewireFilters();
    renderPage(currentPage);
  }


  // ====== Panel de detalles de auditoría ======
  function renderAuditDetails(aud, dirName, deptName) {
    const panel = document.getElementById("auditoriasPanel");
    if (panel) panel.classList.remove("d-none");
    const pd = document.getElementById("panelDir");
    if (pd) pd.textContent = dirName;
    const pdept = document.getElementById("panelDept");
    if (pdept) pdept.textContent = deptName;

    // % Avance
    try {
      const av = computeAuditProgress(aud);
      setAuditoriaSeleccionada(aud.id, aud.numero_auditoria);

      const estadoTxt = av.percent === 100 ? "Completada" : "En proceso";
      const $txt = document.getElementById("avanceAuditoria");
      if ($txt) $txt.textContent = `${estadoTxt} — ${av.percent}% (${av.done}/${av.total})`;

      const $bar = document.getElementById("avanceBar");
      if ($bar) {
        $bar.style.width = av.percent + "%";
        $bar.setAttribute("aria-valuenow", String(av.percent));
        $bar.classList.remove("bg-success", "bg-warning", "bg-danger");
        $bar.classList.add(
          av.percent === 100 ? "bg-success" :
            av.percent >= 25 ? "bg-warning" :
              "bg-danger"
        );
      }
      applyProgressTooltip($txt, av);
      applyProgressTooltip($bar, av);
    } catch (e) { }

    const grid = document.getElementById("auditoriasGrid");
    if (!grid) return;
    grid.innerHTML = "";

    function cardHTML(title, bodyHtml, opts = {}) {
      const { span = 'half' } = opts; // 'half' = col-12 col-lg-6, 'full' = col-12
      const colCls = span === 'full' ? 'col-12' : 'col-12 col-lg-6';
      return `
        <div class="${colCls}">
          <div class="card audit-card position-relative">
            <div class="card-body fancy-border">
              <h6 class="audit-card-title mb-2 ">${esc(title)}</h6>
              <div class="audit-meta ">${bodyHtml}</div>
            </div>
          </div>
        </div>`;
    }
    function cardHTMLTurnos(title, bodyHtml, opts = {}) {
      const { span = 'half' } = opts; // 'half' = col-12 col-lg-6, 'full' = col-12
      const colCls = span === 'full' ? 'col-12' : 'col-12 col-lg-4';
      return `
        <div class="${colCls}">
          <div class="card audit-card position-relative">
            <div class="card-body fancy-border">
              <h6 class="audit-card-title mb-2 ">${esc(title)}</h6>
              <div class="audit-meta ">${bodyHtml}</div>
            </div>
          </div>
        </div>`;
    }
    function phaseBadgeHtml(phase) {
      if (!phase) phase = "Pendiente";
      const p = String(phase).trim();
      if (p === "Autorizado") return `<p class="text-success">${esc(p)}</p>`;
      if (p === "Rechazado") return `<p class="text-danger">${esc(p)}</p>`;
      return `<p class="text-warning">${esc(p)}</p>`;
    }
    function statusFor(node) {
      if (!node) return "status-absent";
      if (Array.isArray(node)) {
        if (node.length === 0) return "status-absent";
        for (let i = 0; i < node.length; i++) {
          const it = node[i];
          if (it && String(it.fase_autorizacion ?? "").trim() === "Autorizado") return "status-autorizado";
        }
        return "status-pending";
      }
      const phase = node.fase_autorizacion ? String(node.fase_autorizacion).trim() : null;
      return phase ? (phase === "Autorizado" ? "status-autorizado" : "status-pending") : "status-absent";
    }
    // === Estilos para la cuadrícula de acciones (se inyectan una sola vez) ===
    function injectActionGridStyles() {
      if (document.getElementById('action-grid-styles')) return;
      const css = `
        .action-grid{
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
          gap: 12px;
          font-size: 13px;
          width: 100%;
        }
        @media (max-width: 576px){
          .action-grid{ grid-template-columns: 1fr; gap: 10px; }
        }
        
        .action-item{
          border-radius: 8px;
          padding: 10px;
          font-size: 13px;
          background: #FFFFFF;
        }
        .action-item .num{
          font-weight: 700;
          font-size: 13px;
          color: #111827;
          margin-bottom: 4px;
          overflow-wrap: anywhere;
        }

        .status-ok   { color: #10b981; fontSize: 700; font-size: 13.5px; }
        .status-warn { color: #f59e0b; font-weight: 700; font-size: 13.5px; }
        .status-bad  { color: #b91f1f; font-weight: 700; font-size: 13.5px; }
      `;
      const s = document.createElement('style');
      s.id = 'action-grid-styles';
      s.appendChild(document.createTextNode(css));
      document.head.appendChild(s);
    }

    // === Normaliza a array seguro ===
    function toArraySafe(v) {
      if (!v) return [];
      return Array.isArray(v) ? v : [v];
    }

    // === Aplana relaciones de acciones (igual que en tu PDF) ===
    // Devuelve [{numero, fase}] a partir de acciones y su relación hija (relKey)
    function flattenRelations(actions, relKey) {
      const items = [];
      toArraySafe(actions).forEach(act => {
        const baseNum = act?.numero ?? act?.consecutivo ?? ('#' + (act?.id ?? ''));
        const rel = act?.[relKey];
        if (!rel) return;

        if (Array.isArray(rel)) {
          rel.forEach(r => {
            items.push({
              numero: String(r?.numero ?? baseNum),
              fase: String((r?.fase_autorizacion ?? act?.fase_autorizacion ?? 'Pendiente')).trim() || 'Pendiente',
            });
          });
        } else if (typeof rel === 'object') {
          items.push({
            numero: String(rel?.numero ?? baseNum),
            fase: String((rel?.fase_autorizacion ?? act?.fase_autorizacion ?? 'Pendiente')).trim() || 'Pendiente',
          });
        } else {
          items.push({ numero: String(baseNum), fase: String(rel) });
        }
      });
      return items;
    }

    // === Badge inline compacto (sin <p>) para usar dentro de la grilla ===
    function phaseBadgeInline(phase) {
      const p = String(phase || 'Pendiente').trim();
      if (p == 'Autorizado') return `<span class="status-ok">${esc(p)}</span>`;
      if (p == 'Rechazado') return `<span class="status-bad">${esc(p)}</span>`;
      return `<span class="status-warn">${esc(p)}</span>`;
    }

    // === Inserta card de acciones SOLO si hay elementos (sin "No hay ...") ===
    // Reutiliza getGroupStatus(actions, relKey) para el dot (status-breath)
    function insertActionsCardIfAny(title, actions, relKey, gridEl, cardHTML, opts = {}) {
      const items = flattenRelations(actions, relKey);
      if (!items.length) return;

      const status = getGroupStatus(actions, relKey);
      const span = opts.span || 'half'; // 'half' por default

      let body = '';
      body += `<div class="d-flex align-items-start gap-3">`;
      body += `<div class="status-breath ${status}" aria-hidden="true"></div>`;
      body += `<div style="width:100%">`;
      body += `<div class="audit-field"><b>Total ${esc(title)}:</b><span class="field-value"> ${items.length}</span></div>`;
      body += `<div class="action-grid">`;
      items.forEach(it => {
        body += `<div class="action-item">
                  <div class="num">No. de acción: ${esc(it.numero)}</div>
                  <div>Estatus: ${phaseBadgeInline(it.fase)}</div>
                </div>`;
      });
      body += `</div>`;
      body += `</div>`;
      body += `</div>`;

      gridEl.insertAdjacentHTML("beforeend", cardHTML(title, body, { span }));
    }

    function getGroupStatus(actions, relKey) {
      if (!actions || actions.length === 0) return "status-absent";
      let totalRelations = 0;
      let totalAuthorized = 0;
      for (const a of actions) {
        const rel = a[relKey];
        if (!rel) continue;
        if (Array.isArray(rel)) {
          for (const r of rel) {
            totalRelations++;
            if (r && String(r.fase_autorizacion ?? "").trim() === "Autorizado") totalAuthorized++;
          }
        } else {
          totalRelations++;
          if (rel && String(rel.fase_autorizacion ?? "").trim() === "Autorizado") totalAuthorized++;
        }
      }
      if (totalRelations === 0) return "status-absent";
      return totalAuthorized === totalRelations ? "status-autorizado" : "status-pending";
    }
    // === Estilos de info-grid (una sola vez) ===
    function injectInfoGridStyles() {
      if (document.getElementById('info-grid-styles')) return;
      const css = `
        .info-grid{
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
          gap: 12px;
          font-size: 13px;
          width: 100%;
        }
        .info-tile{
          border-radius: 8px;
          padding: 10px;
          font-size: 13px;
          background: #FFFFFF;
        }
        .info-tile .tile-title{
          font-size: 13px;
          font-weight: 700; color: #111827; margin-bottom: 4px;
        }
      `;
      const s = document.createElement('style');
      s.id = 'info-grid-styles';
      s.appendChild(document.createTextNode(css));
      document.head.appendChild(s);
    }

    // === Tile para un grupo (p.ej. "Recomendaciones" o "Pliegos") ===
    // items: array de nodos con fase_autorizacion (string)
    function renderInfoGroupTile(groupTitle, items) {
      const list = Array.isArray(items) ? items : (items ? [items] : []);
      const total = list.length;
      if (!total) return ``; // si no hay, la baldosa no aparece

      // Conteo por estatus
      const counts = list.reduce((acc, it) => {
        const p = String(it?.fase_autorizacion || 'Pendiente').trim();
        acc[p] = (acc[p] || 0) + 1;
        return acc;
      }, {});

      const chips = Object.entries(counts).map(([phase, cnt]) => {
        // usa tu badge inline actual para color (ok/warn/bad)
        return `<span class="badge-chip"> ${phaseBadgeInline(phase)} · ${cnt}</span>`;
      }).join('');

      return `
        <div class="info-tile">
          <div class="tile-title">${esc(groupTitle)}</div>
          <div>Total: <b>${total}</b></div>
          <div class="badges">${chips}</div>
        </div>
      `;
    }


    // Radicación
    const radStatus = statusFor(aud.radicacion);
    const radBody = aud.radicacion
      ? `
        <div class="audit-field"><b>Número de expediente:</b> ${esc(aud.radicacion.numero_expediente ?? "-")}</div>
        <div class="audit-field"><b>Oficio de notificación de acuerdos:</b> ${esc(aud.radicacion.oficio_acuerdo ?? "")}</div>
        <div class="audit-field"><b>Estatus de la Auditoría:</b> ${phaseBadgeHtml(esc(aud.radicacion.fase_autorizacion ?? "Pendiente"))}</div>
      `
      : "<div>No registrada</div>";
    const radHtml = `<div class="d-flex align-items-start gap-3"><div class="status-breath ${radStatus}" aria-hidden="true"></div><div>${radBody}</div></div>`;
    //grid.insertAdjacentHTML("beforeend", cardHTML("Radicación", radHtml));
    grid.insertAdjacentHTML("beforeend", cardHTML("Radicación", radHtml, { span: 'half' }));

    // Comparecencia
    const compStatus = statusFor(aud.comparecencia);
    const compBody = aud.comparecencia
      ? `
        <div class="audit-field"><b>Acta de comparecencia:</b><span class="field-value"> ${esc(aud.comparecencia.numero_acta ?? "-")}</span></div>
        <div class="audit-field"><b>Estatus:</b><span class="field-value"> ${phaseBadgeHtml(esc(aud.comparecencia.fase_autorizacion ?? "Pendiente"))}</span></div>
      `
      : "<div>No registrada</div>";
    const compHtml = `<div class="d-flex align-items-start gap-3"><div class="status-breath ${compStatus}" aria-hidden="true"></div><div>${compBody}</div></div>`;
    //grid.insertAdjacentHTML("beforeend", cardHTML("Comparecencia", compHtml));
    grid.insertAdjacentHTML("beforeend", cardHTML("Comparecencia", compHtml, { span: 'half' }));

    grid.insertAdjacentHTML(
      "beforeend",
      `<div class="col-12 mb-4"><hr class="fancy-border"><div class="my-5"><strong>Primer análisis</strong></div><hr class="fancy-border"></div>`
    );

    // Acuerdos
    const acuerdosList = [];
    if (aud.AC) Array.isArray(aud.AC) ? acuerdosList.push(...aud.AC) : acuerdosList.push(aud.AC);
    let recCount = 0;
    if (aud.acuerdoconclusion) {
      if (Array.isArray(aud.acuerdoconclusion)) {
        recCount = aud.acuerdoconclusion.length;
        acuerdosList.push(...aud.acuerdoconclusion);
      } else {
        recCount = 1;
        acuerdosList.push(aud.acuerdoconclusion);
      }
    }
    let pliegosCount = 0;
    if (aud.acuerdoconclusionpliegos) {
      if (Array.isArray(aud.acuerdoconclusionpliegos)) {
        pliegosCount = aud.acuerdoconclusionpliegos.length;
        acuerdosList.push(...aud.acuerdoconclusionpliegos);
      } else {
        pliegosCount = 1;
        acuerdosList.push(aud.acuerdoconclusionpliegos);
      }
    }

    injectInfoGridStyles();

    const recsAC = toArraySafe(aud.acuerdoconclusion);
    const plisAC = toArraySafe(aud.acuerdoconclusionpliegos);
    //const acuerdosList = [...recsAC, ...plisAC];
    const acuerdosStatus = statusFor(acuerdosList);
    const acuerdosHtml = `
                            <div class="d-flex align-items-start gap-3">
                              <div class="status-breath ${acuerdosStatus}" aria-hidden="true"></div>
                              <div style="width:100%">
                                <div class="info-grid">
                                  ${renderInfoGroupTile('Recomendaciones', recsAC)} 
                                  ${renderInfoGroupTile('Pliegos', plisAC)}
                                </div>
                              </div>
                            </div>
                          `;

    // Inserta la card a ancho completo
    grid.insertAdjacentHTML(
      "beforeend",
      cardHTML("Acuerdos de conclusión", acuerdosHtml ?? "Sin registros", { span: 'full' })
    );

    //grid.insertAdjacentHTML("beforeend",cardHTML("Acuerdos de conclusión", `<div class="d-flex align-items-start gap-3"><div class="status-breath ${acuerdosStatus}" aria-hidden="true"></div><div>${acuerdosHtml}</div></div>`));

    // === Acciones en cuadrícula (sin "No hay ...") ===
    injectActionGridStyles();
    const actionGroups = [
      { title: "Recomendaciones", actions: aud.accionesrecomendaciones || [], relKey: "recomendaciones" },
      { title: "Pliegos de observación", actions: aud.accionespo || [], relKey: "pliegosobservacion" },
      { title: "Solicitudes de aclaración", actions: aud.accionessolacl || [], relKey: "solicitudesaclaracion" },
      { title: "PRAS", actions: aud.accionespras || [], relKey: "pras" }
    ];

    // ¿Cuántas categorías NO están vacías?
    const nonEmptyCount = actionGroups.reduce((acc, g) => {
      const items = flattenRelations(g.actions, g.relKey);
      return acc + (items.length > 0 ? 1 : 0);
    }, 0);

    // Regla: si solo hay 1, esa card va 'full'; en otro caso, 'half'
    const spanFor = (nonEmptyCount === 1) ? 'full' : 'half';

    // Inserta cada grupo (solo si trae datos)
    actionGroups.forEach(g => {
      insertActionsCardIfAny(g.title, g.actions, g.relKey, grid, cardHTML, { span: spanFor });
    });
    //insertActionsCardIfAny("Recomendaciones", aud.accionesrecomendaciones || [], "recomendaciones", grid, cardHTML);
    //insertActionsCardIfAny("Pliegos de observación", aud.accionespo || [], "pliegosobservacion", grid, cardHTML);
    //insertActionsCardIfAny("Solicitudes de aclaración", aud.accionessolacl || [], "solicitudesaclaracion", grid, cardHTML);
    //insertActionsCardIfAny("PRAS", aud.accionespras || [], "pras", grid, cardHTML);

    // Informes
    const informesList = [];
    if (aud.informes) Array.isArray(aud.informes) ? informesList.push(...aud.informes) : informesList.push(aud.informes);
    let isrecCount = 0;
    if (aud.informeprimeraetapa) {
      if (Array.isArray(aud.informeprimeraetapa)) {
        isrecCount = aud.informeprimeraetapa.length;
        informesList.push(...aud.informeprimeraetapa);
      } else {
        isrecCount = 1;
        informesList.push(aud.informeprimeraetapa);
      }
    }
    let ispliegosCount = 0;
    if (aud.informepliegos) {
      if (Array.isArray(aud.informepliegos)) {
        ispliegosCount = aud.informepliegos.length;
        informesList.push(...aud.informepliegos);
      } else {
        ispliegosCount = 1;
        informesList.push(aud.informepliegos);
      }
    }
    function phaseLabel(node) {
      if (!node) return "Pendiente";
      if (Array.isArray(node)) {
        if (node.length === 0) return "Pendiente";
        const phases = [...new Set(node.map((n) => (n && n.fase_autorizacion ? String(n.fase_autorizacion).trim() : "Pendiente")))];
        return phases.length === 1 ? phases[0] : phases.join(", ");
      }
      return node.fase_autorizacion ? String(node.fase_autorizacion).trim() : "Pendiente";
    }
    const informesRecs = toArraySafe(aud.informeprimeraetapa);
    const informesPliegos = toArraySafe(aud.informepliegos);
    //const informesList   = [...informesRecs, ...informesPliegos];
    const informesStatus = statusFor(informesList);

    const informesHtml = `
      <div class="d-flex align-items-start gap-3">
        <div class="status-breath ${informesStatus}" aria-hidden="true"></div>
        <div style="width:100%">
          <div class="info-grid">
            ${renderInfoGroupTile('Recomendaciones', informesRecs)}
            ${renderInfoGroupTile('Pliegos', informesPliegos)}
          </div>
        </div>
      </div>
    `;

    grid.insertAdjacentHTML(
      "beforeend",
      cardHTML("Informes", informesHtml, { span: 'full' })
    );
    //grid.insertAdjacentHTML("beforeend",cardHTML("Informes", `<div class="d-flex align-items-start gap-3"><div class="status-breath ${informesStatus}" aria-hidden="true"></div><div>${informesHtml}</div></div>`));

    // Separador y Turnos
    grid.insertAdjacentHTML(
      "beforeend",
      `<div class="col-12 mb-4"><hr class="fancy-border"><div class="my-5"><strong>Turnos</strong></div><hr class="fancy-border"></div>`
    );

    const TurnoUIStatus = statusFor(aud.turnoui);
    const TurnoOICStatus = statusFor(aud.turnooic);
    const TurnoArchivoStatus = statusFor(aud.turnoarchivo);

    const TurnoUIBody = aud.turnoui
      ? `
        <div class="audit-field"><b>Número de expediente:</b> ${esc(aud.turnoui.numero_turno_ui ?? "-")}</div>
        <div class="audit-field"><b>Estatus del turno:</b> ${phaseBadgeHtml(esc(aud.turnoui.fase_autorizacion ?? "Pendiente"))}</div>`
      : "<div>No registrada</div>";
    const TurnoUIHtml = `<div class="d-flex align-items-start gap-3"><div class="status-breath ${TurnoUIStatus}" aria-hidden="true"></div><div>${TurnoUIBody}</div></div>`;
    grid.insertAdjacentHTML("beforeend", cardHTMLTurnos("Turno UI", TurnoUIHtml));

    const TurnoOICBody = aud.turnooic
      ? `
        <div class="audit-field"><b>Número de expediente:</b> ${esc(aud.turnooic.numero_turno_oic ?? "-")}</div>
        <div class="audit-field"><b>Estatus del turno:</b> ${phaseBadgeHtml(esc(aud.turnooic.fase_autorizacion ?? "Pendiente"))}</div>`
      : "<div>No registrada</div>";
    const TurnoOICHtml = `<div class="d-flex align-items-start gap-3"><div class="status-breath ${TurnoOICStatus}" aria-hidden="true"></div><div>${TurnoOICBody}</div></div>`;
    grid.insertAdjacentHTML("beforeend", cardHTMLTurnos("Turno OIC", TurnoOICHtml));

    const TurnoArchivoBody = aud.turnoarchivo
      ? `
        <div class="audit-field"><b>Número de expediente:</b> ${esc(aud.turnoarchivo.numero_turno_archivo ?? "-")}</div>
        <div class="audit-field"><b>Estatus del turno:</b> ${phaseBadgeHtml(esc(aud.turnoarchivo.fase_autorizacion ?? "Pendiente"))}</div>`
      : "<div>No registrada</div>";
    const TurnoArchivoHtml = `<div class="d-flex align-items-start gap-3"><div class="status-breath ${TurnoArchivoStatus}" aria-hidden="true"></div><div>${TurnoArchivoBody}</div></div>`;
    grid.insertAdjacentHTML("beforeend", cardHTMLTurnos("Turno Archivo", TurnoArchivoHtml));

    const btnOcultar = document.getElementById("btnOcultarPanel");
    
    if (btnOcultar)
      btnOcultar.addEventListener("click", () => {
          if (panel) panel.classList.add("d-none");
          resetAvanceCard();
          clearPdfAuditState();
      });
    try {
      panel?.scrollIntoView({ behavior: "smooth", block: "start" });
    } catch (e) { }

    window.__pdfAuditId = aud?.id || '';
    window.__pdfAuditName = aud?.numero_auditoria || '';
    const avTxt = document.getElementById('avanceAuditoria')?.textContent || '';
    window.__pdfAuditAvanceText = avTxt;


  }
  // ====== INIT ======
  (function init() {
    // Estilos del treemap
    try { injectNeonSkinStyles(); } catch (e) { }

    // Oculta tarjeta de filtros hasta que el usuario seleccione depto
    try { toggleAuditFilters(false); } catch (e) { }
    //Flujo para JD
    
    if (userRole == 'JD' && userDeptId) {
        initJefeDepartamentoView();
        return;
      }


    // Tarjetas de "Encargados" (A/B) – muestran director por dirección
    try { setEncargadosDireccionCard("A"); } catch (e) { }
    try { setEncargadosDireccionCard("B"); } catch (e) { }

    // Gauges SOLO si existe el contenedor en el DOM (según permisos/Blade)
    if (document.getElementById("grafica_depto_auditoriasA")) {
      renderDeptSolidGauge("dirA", "grafica_depto_auditoriasA", { useSideInfo: true });
    }
    if (document.getElementById("grafica_deptos")) {
      renderDeptSolidGauge("dirB", "grafica_deptos", { useSideInfo: true });
    }

    // Limpia treemap inicial
    const container = document.getElementById("container");
    if (container) {
      container.innerHTML = '<div id="chartInner" class="treemap-neon" style="min-height: 80px;"></div>';
    }
    
  })();
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
})();