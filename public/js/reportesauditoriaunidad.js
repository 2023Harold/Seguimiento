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
      } catch (e) {}
    }, 150);
  });

  // ===== Utilidades / helpers =====
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
  } = window.REPORT_DATA || {};

  const DETALLE_URL = (detalleBaseUrl || "").replace(/\/+$/, "");
  const _auditProgressCache = new Map(); // id -> { percent, done, total }

  const dirNames = {
    dirA: 'Dirección de Seguimiento "A"',
    dirB: 'Dirección de Seguimiento "B"',
  };
  const deptsByDir = {
    dirA: (baseData || []).filter((p) => p.parent === "dirA"),
    dirB: (baseData || []).filter((p) => p.parent === "dirB"),
  };

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
    return String(val ?? "").trim() === "Autorizado";
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
      console.error("Error obteniendo detalle de auditoría", audId, e);
      return { percent: 0, done: 0, total: 0 }; // fallback
    }
  }
  async function computeDeptMetrics(deptId) {
    const arr = auditoriasMap?.[String(deptId)] || [];
    if (!arr.length) return { avgPercent: 0, completedPercent: 0, total: 0, completed: 0 };
    const avs = await mapWithConcurrency(arr, 5, async (a) => {
      try {
        const av = await getAuditProgressPercent(a.id);
        return av?.percent ?? 0;
      } catch (e) {
        console.warn("No se pudo obtener avance de auditoría", a.id, e);
        return 0;
      }
    });
    const total = avs.length;
    const sum = avs.reduce((acc, p) => acc + (p || 0), 0);
    const completed = avs.filter((p) => p >= 100).length;
    return {
      avgPercent: total ? Math.round(sum / total) : 0,
      completedPercent: total ? Math.round((completed / total) * 100) : 0,
      total,
      completed,
    };
  }
  function computeAuditProgress(aud) {
    let total = 0,
      done = 0;
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
    if (aud.radicacion) {
      breakdown.radicacion.total++;
      total++;
      const ok =
        isAuth(aud.radicacion.fase_autorizacion) &&
        !!(aud.comparecencia && aud.comparecencia.oficio_acuerdo);
      if (ok) {
        breakdown.radicacion.done++;
        done++;
      }
    }
    if (aud.comparecencia) {
      breakdown.comparecencia.total++;
      total++;
      if (isAuth(aud.comparecencia.fase_autorizacion)) {
        breakdown.comparecencia.done++;
        done++;
      }
    }
    toArr(aud.acuerdoconclusion).forEach((rec) => {
      breakdown.acuerdosRecs.total++;
      total++;
      const ok = isAuth(rec?.fase_autorizacion) && !!rec?.oficio_recepcion;
      if (ok) {
        breakdown.acuerdosRecs.done++;
        done++;
      }
    });
    toArr(aud.acuerdoconclusionpliegos).forEach((pl) => {
      breakdown.acuerdosPliegos.total++;
      total++;
      const ok = isAuth(pl?.fase_autorizacion) && !!pl?.oficio_recepcion;
      if (ok) {
        breakdown.acuerdosPliegos.done++;
        done++;
      }
    });
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
    [["turnoui", "turnoUI"], ["turnooic", "turnoOIC"], ["turnoarchivo", "turnoArchivo"]].forEach(([key, bKey]) => {
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
      $d && $d.classList.add("d-none");
      $j && $j.classList.add("d-none");
      $de && $de.replaceChildren(document.createTextNode("—"));
      $je && $je.replaceChildren(document.createTextNode("—"));
    } catch (e) {}
  }
  function setEncargadosDepartamentoCard(side, deptId, deptName) {
    try {
      const jefe = jefesPorDepartamento?.[Number(deptId)];
      const $d = document.getElementById(`encargadosDepto${side}`);
      const $j = document.getElementById(`encargadosJefe${side}`);
      const $de = document.getElementById(`enc_depto${side}`);
      const $je = document.getElementById(`enc_jefe${side}`);
      if ($d) $d.classList.remove("d-none");
      if ($j) $j.classList.remove("d-none");
      $de && $de.replaceChildren(document.createTextNode(deptName || "—"));
      $je && $je.replaceChildren(document.createTextNode(jefe || "—"));
    } catch (e) {}
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
      exporting: { enabled: true },
      series: [
        {
          type: "treemap",
          borderWidth: 10.2,
          borderColor: "#BB945C",
          borderRadius: 10,
          pointPadding: options.pointPadding ?? 0,
          nodePadding: options.nodePadding ?? 2,
          states: {
            hover: { enabled: true, brightness: 0, borderColor: "#fff" },
          },
          dataLabels: {
            enabled: true,
            useHTML: true,
            inside: true,
            allowOverlap: false,
            crop: true,
            overflow: "justify",
            padding: 2,
            style: { textOutline: "none", fontWeight: "600", fontSize: options.labelFontSize || FS.dataLabel, color: "#132a29" },
            formatter: function () {
              const p = this.point;
              const clamp = (txt, max = 140) => {
                txt = String(txt || "").trim();
                return txt.length > max ? txt.slice(0, max - 1) + "…" : txt;
              };
              if (typeof p.real !== "undefined") {
                const total = Highcharts.numberFormat(p.real || 0, 0);
                const title = esc(clamp(p.name, 70));
                return `
                  <div class="hc-label-wrap">
                    <div class="hc-label-inner">
                      <div style="font-weight:700">${title}</div>
                      <div style="opacity:.9">${esc(String(total))}</div>
                    </div>
                  </div>`;
              }
              try {
                const numero = p.numero_auditoria ? esc(clamp(p.numero_auditoria, 30)) : "";
                const entidad = p.nombre_entidad
                  ? esc(clamp(p.nombre_entidad, 110))
                  : p.entidad_fiscalizable
                  ? esc(clamp(p.entidad_fiscalizable, 110))
                  : "";
                const acto = p.acto_fiscalizacion ? esc(clamp(p.acto_fiscalizacion, 80)) : "";
                const lines = [numero, entidad, acto].filter(Boolean);
                const html = lines.map((l) => `<div>${l}</div>`).join("");
                return `<div class="hc-label-wrap"><div class="hc-label-inner">${html}</div></div>`;
              } catch (e) {
                return esc(String(p.name || ""));
              }
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
            style: { color: "#132a29", fontSize: FS.tooltip, maxWidth: FS.tooltipMaxW, whiteSpace: "normal" },
            positioner: function (labelWidth, labelHeight, point) {
              const chart = this.chart || (this && this.chart);
              if (!chart) return { x: 10, y: 10 };
              const cx = point && point.chartX !== undefined ? point.chartX : point && point.plotX ? chart.plotLeft + point.plotX : chart.plotWidth / 2;
              const cy = point && point.chartY !== undefined ? point.chartY : point && point.plotY ? chart.plotTop + point.plotY : chart.plotHeight / 4;
              let x = cx + 12;
              let y = cy - labelHeight / 2;
              if (x + labelWidth > chart.chartWidth - 10) x = cx - labelWidth - 12;
              if (x < 10) x = 10;
              if (y < chart.plotTop + 5) y = chart.plotTop + 5;
              if (y + labelHeight > chart.plotTop + chart.plotHeight - 5) y = chart.plotTop + chart.plotHeight - labelHeight - 5;
              return { x, y };
            },
            pointFormatter: function () {
              try {
                const p = this;
                const shape = p.shapeArgs || {};
                const boxW = shape.width || (p.plotX ? p.plotX : 0);
                const boxH = shape.height || (p.plotY ? p.plotY : 0);
                let textLines = [];
                if (typeof p.real !== "undefined") {
                  textLines = [String(p.name || ""), String(Highcharts.numberFormat(p.real || 0, 0))];
                } else {
                  if (p.numero_auditoria) textLines.push(String(p.numero_auditoria));
                  if (p.nombre_entidad) textLines.push(String(p.nombre_entidad));
                  else if (p.entidad_fiscalizable) textLines.push(String(p.entidad_fiscalizable));
                  if (p.acto_fiscalizacion) textLines.push(String(p.acto_fiscalizacion));
                  if (textLines.length === 0) textLines.push(String(p.name || ""));
                }
                const fontSize = options && options.labelFontSize ? String(options.labelFontSize) : "18px";
                const font = `600 ${fontSize} sans-serif`;
                const longest = textLines.reduce((m, ln) => Math.max(m, measureTextWidth(String(ln), font)), 0);
                const lineHeight = parseInt(fontSize, 10) * 1.15;
                const totalHeight = textLines.length * lineHeight;
                const padding = 8;
                const fits = boxW && longest <= boxW - padding && boxH && totalHeight <= boxH - padding;
                if (fits) {
                  if (p.series && p.series.chart && p.series.chart.tooltip) p.series.chart.tooltip.hide();
                  return "";
                }
                const header = `<div style="font-weight:700;margin-bottom:6px;color:#132a29;">${esc(p.name || "")}</div>`;
                let cuerpo = "";
                if (typeof p.real !== "undefined") {
                  cuerpo += `<div><b>Total:</b> ${Highcharts.numberFormat(p.real || 0, 0)}</div>`;
                } else {
                  const entidad = p.nombre_entidad || p.entidad_fiscalizable;
                  if (entidad) cuerpo += `<br><div><b>Entidad:</b> ${esc(String(entidad))}</div>`;
                  if (p.acto_fiscalizacion) cuerpo += `<br><div><b>Acto:</b> ${esc(String(p.acto_fiscalizacion))}</div>`;
                }
                return `<div style="max-width:${getFontSizes().tooltipMaxW};white-space:normal;color:#132a29;">${header}${cuerpo}</div>`;
              } catch (e) {
                try {
                  this.series.chart.tooltip.hide();
                } catch (err) {}
                return "";
              }
            },
          },
          point: {
            events: {
              click: function () {
                if (onPointClick) onPointClick(this);
              },
              mouseOver: function () {
                try {
                  if (this.graphic && this.graphic.element) {
                    this.graphic.element.classList.add("neon-hover");
                  }
                  const p = this;
                  const dl = p && p.dataLabel;
                  if (dl) {
                    let el = null;
                    if (dl.element) el = dl.element;
                    else if (dl.div) el = dl.div;
                    else if (dl && dl.nodeType) el = dl;
                    if (el && el.scrollWidth !== undefined && el.clientWidth !== undefined) {
                      const overflow = el.scrollWidth > el.clientWidth || el.scrollHeight > el.clientHeight;
                      if (!overflow) {
                        if (p.series && p.series.chart && p.series.chart.tooltip) p.series.chart.tooltip.hide();
                      }
                    }
                  }
                } catch (e) {
                  try {
                    this.series.chart.tooltip.hide();
                  } catch (err) {}
                }
              },
              mouseOut: function () {
                try {
                  if (this.graphic && this.graphic.element) {
                    this.graphic.element.classList.remove("neon-hover");
                  }
                  if (this.series && this.series.chart && this.series.chart.tooltip)
                    this.series.chart.tooltip.hide();
                } catch (e) {}
              },
            },
          },
          data: nodes,
        },
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
          } catch (e) {}
          origDestroy.apply(this, arguments);
        };
      }
    } catch (e) {}
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
      console.warn(`[DeptGauge] No existe #${mountId}`);
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
          <div class="kpi"><span class="muted">Avance:</span><span class="big" style="color:${color}">${m.avgPercent}%</span></div>
          <div class="kpi"><span class="muted">Completadas:</span> ${m.completed} / ${m.total} (${m.completedPercent}%)</div>
        `;
      }

      // Render del gauge
      mount.innerHTML = "";
      const gChart = Highcharts.chart(mountId, {
        chart: {
          type: "solidgauge",
          height: "110%",
          backgroundColor: "transparent",
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
                    try { figure?.scrollIntoView({ behavior: "smooth", block: "start" }); } catch (e) {}
                },
              },
            },
          },
        },
        credits: { enabled: false },
        exporting: { enabled: false },
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
      requestAnimationFrame(() => { try { gChart.reflow(); } catch (e) {} });

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
            try { gChart.reflow(); } catch (e) {}
          });
          ro.observe(wrapEl);
          ro.observe(infoEl2);
          const _destroy = gChart.destroy;
          gChart.destroy = function () {
            try { ro.disconnect(); } catch (e) {}
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
      console.error("Error renderDeptSolidGauge", e);
      mount.innerHTML = prevHTML;
    }
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
  function setAuditoriaSeleccionada(audId, AudName) {
    try {
      document.getElementById("audi_name")?.replaceChildren(document.createTextNode(AudName || "—"));
    } catch (e) {}
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
    } catch (e) {}
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
  const titleHTML = `
    <div class="mb-2">
      <h6 class="mb-1">Auditorías — <span>${esc(dirName)}</span> / <span>${esc(deptName)}</span></h6>
      <hr class="fancy-border">
    </div>
  `;
  renderShell({ titleHTML });

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
      try { sessionStorage.removeItem("auditoriaViewState"); } catch (e) {}
    }
  } catch (e) {}

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
    } catch (e) {}
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
      try { form.reset(); } catch (e) {}
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
      try { newForm.reset(); } catch (e) {}

      activeItems = originalItems.slice();
      totalPages = Math.max(1, Math.ceil(activeItems.length / perPage));
      highlightId = null;
      renderPage(1);
    });
  }

  // ====== Render (treemap + paginación) ======
  function renderPage(page, forceHighlightId) {
    currentPage = page || 1;
    saveViewState(currentPage);

    const start = (currentPage - 1) * perPage;
    const pageItems = activeItems.slice(start, start + perPage);
    if (forceHighlightId) highlightId = forceHighlightId;

    let nodes;
    if (!pageItems.length) {
      nodes = [{ id: "no-data", name: "Sin auditorías", value: 1, color: "#f8d7da" }];
    } else {
      nodes = pageItems.map((a) => ({
        id: "aud" + a.id,
        numero_auditoria: a.numero_auditoria,
        entidad_fiscalizable: a.entidad_fiscalizable,
        nombre_entidad: a.nombre_entidad,
        acto_fiscalizacion: a.acto_fiscalizacion,
        name: a.numero_auditoria ? a.numero_auditoria : "AUD-" + a.id,
        value: 1,
        className: highlightId === "aud" + a.id ? "is-highlight" : "",
      }));
    }

    drawTreemapIn(
      "chartInner",
      `${dirName} / ${deptName}`,
      activeItems.length ? "Auditorías" : "Sin resultados",
      nodes,
      (point) => {
        if (!point || !point.id || String(point.id).startsWith("no-data")) return;
        const audId = String(point.id).replace(/^aud/, "");
        if (!audId) return;
        try { showLoading(); } catch (e) {}
        fetchJson(`${DETALLE_URL}/${audId}`)
          .then((aud) => {
            hideLoading();
            renderAuditDetails(aud, dirName, deptName);
          })
          .catch((err) => {
            hideLoading();
            console.error("Error cargando auditoría", err);
            const panel = document.getElementById("auditoriasPanel");
            if (panel) panel.classList.add("d-none");
            alert("No se pudo cargar el detalle de la auditoría. Verifica tu sesión o la URL del servicio.");
          });
      },
      { layoutAlgorithm: "strip", height: 800, minPointSize: 8, labelFontSize: "16px", pointPadding: 4, nodePadding: 2, labelClamp: 3 }
    );

    // Paginación
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
          const p = parseInt(el.dataset.page, 10) || 1;
          renderPage(p);
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
      const $txt = document.getElementById("avanceAuditoria");
      if ($txt) $txt.textContent = `${av.percent}% (${av.done}/${av.total})`;
      const $bar = document.getElementById("avanceBar");
      if ($bar) {
        $bar.style.width = av.percent + "%";
        $bar.setAttribute("aria-valuenow", String(av.percent));
        $bar.classList.remove("bg-success", "bg-warning", "bg-danger");
        $bar.classList.add(av.percent === 100 ? "bg-success" : av.percent >= 10 ? "bg-warning" : "bg-danger");
      }
      applyProgressTooltip($txt, av);
      applyProgressTooltip($bar, av);
    } catch (e) {}

    const grid = document.getElementById("auditoriasGrid");
    if (!grid) return;
    grid.innerHTML = "";

    function cardHTML(title, bodyHtml) {
      return `
        <div class="col-12 col-md-6">
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
    function renderActionList(actions, relKey, relLabel) {
      if (!actions || !actions.length) return "<div>No hay " + relLabel + "</div>";
      let html = `<div class="audit-field"><b>Total ${relLabel}:</b><span class="field-value"> ${actions.length}</span></div><div class="mt-1">`;
      actions.forEach((act) => {
        const numero = esc(act.numero ?? act.consecutivo ?? "#" + (act.id ?? ""));
        const rel = act[relKey];
        if (rel) {
          if (Array.isArray(rel)) {
            const parts = rel.map(
              (r) => `<div class="audit-field"><b>Estatus:</b><span class="field-value">${phaseBadgeHtml(r.fase_autorizacion ?? "Pendiente")}</span></div>`
            );
            html += `<div class="small mb-1 audit-field "><b>No. de acción:</b>${numero}<div class="small text-muted">${parts.join("<br/>")}</div></div>`;
          } else if (typeof rel === "object") {
            html += `<div class=" small mb-1 audit-field"><b>No. de acción:</b>${numero}</div><div class="audit-field"><b>Estatus:</b>${phaseBadgeHtml(rel.fase_autorizacion ?? "Pendiente")}</div><br>`;
          } else {
            html += `<div class=" small mb-1 audit-field"><b>No. de acción:</b>${numero}<div class="small text-muted">${esc(String(rel))}</div></div>`;
          }
        } else {
          html += `<div class="small mb-1 audit-field"><b><strong>${numero}</strong></b><div class="small text-muted">${phaseBadgeHtml(act.fase_autorizacion)}</div></div>`;
        }
      });
      html += "</div>";
      return html;
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
    grid.insertAdjacentHTML("beforeend", cardHTML("Radicación", radHtml));

    // Comparecencia
    const compStatus = statusFor(aud.comparecencia);
    const compBody = aud.comparecencia
      ? `
        <div class="audit-field"><b>Acta de comparecencia:</b><span class="field-value"> ${esc(aud.comparecencia.numero_acta ?? "-")}</span></div>
        <div class="audit-field"><b>Estatus:</b><span class="field-value"> ${phaseBadgeHtml(esc(aud.comparecencia.fase_autorizacion ?? "Pendiente"))}</span></div>
      `
      : "<div>No registrada</div>";
    const compHtml = `<div class="d-flex align-items-start gap-3"><div class="status-breath ${compStatus}" aria-hidden="true"></div><div>${compBody}</div></div>`;
    grid.insertAdjacentHTML("beforeend", cardHTML("Comparecencia", compHtml));

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
    const acuerdosStatus = statusFor(acuerdosList);
    let acuerdosHtml = "";
    if (acuerdosList.length) {
      acuerdosHtml += `<div class="audit-field"><b>Total acuerdos:</b><span class="field-value"> ${acuerdosList.length}</span></div>`;
      if (recCount)
        acuerdosHtml += `<div class="audit-field"><b>Recomendaciones:</b><span class="field-value"> ${recCount}</span></div>
                         <div class="audit-field"><b>Estatus:</b><span class="field-value"> ${phaseBadgeHtml(aud.acuerdoconclusion?.fase_autorizacion ?? "Pendiente")}</span></div>`;
      if (pliegosCount)
        acuerdosHtml += `<div class="audit-field"><b>Pliegos:</b><span class="field-value"> ${pliegosCount}</span></div>
                         <div class="audit-field"><b>Estatus:</b><span class="field-value"> ${phaseBadgeHtml(aud.acuerdoconclusionpliegos?.fase_autorizacion ?? "Pendiente")}</span></div>`;
    } else {
      acuerdosHtml = "<div>No hay acuerdos registrados</div>";
    }
    grid.insertAdjacentHTML(
      "beforeend",
      cardHTML("Acuerdos de conclusión", `<div class="d-flex align-items-start gap-3"><div class="status-breath ${acuerdosStatus}" aria-hidden="true"></div><div>${acuerdosHtml}</div></div>`)
    );

    // PRAS / Recomendaciones / Pliegos / Solicitudes
    const pras = aud.accionespras || [];
    const prasStatus = getGroupStatus(pras, "pras");
    const prasHtml = renderActionList(pras, "pras", "PRAS");
    grid.insertAdjacentHTML(
      "beforeend",
      cardHTML("PRAS", `<div class="d-flex align-items-start gap-3"><div class="status-breath ${prasStatus}" aria-hidden="true"></div><div>${prasHtml}</div></div>`)
    );

    const recs = aud.accionesrecomendaciones || [];
    const recsStatus = getGroupStatus(recs, "recomendaciones");
    const recsHtml = renderActionList(recs, "recomendaciones", "recomendaciones");
    grid.insertAdjacentHTML(
      "beforeend",
      cardHTML("Recomendaciones", `<div class="d-flex align-items-start gap-3"><div class="status-breath ${recsStatus}" aria-hidden="true"></div><div>${recsHtml}</div></div>`)
    );

    const pos = aud.accionespo || [];
    const posStatus = getGroupStatus(pos, "pliegosobservacion");
    const posHtml = renderActionList(pos, "pliegosobservacion", "pliegos de observación");
    grid.insertAdjacentHTML(
      "beforeend",
      cardHTML("Pliegos de observación", `<div class="d-flex align-items-start gap-3"><div class="status-breath ${posStatus}" aria-hidden="true"></div><div>${posHtml}</div></div>`)
    );

    const sol = aud.accionessolacl || [];
    const solStatus = getGroupStatus(sol, "solicitudesaclaracion");
    const solHtml = renderActionList(sol, "solicitudesaclaracion", "solicitudes");
    grid.insertAdjacentHTML(
      "beforeend",
      cardHTML("Solicitudes de aclaración", `<div class="d-flex align-items-start gap-3"><div class="status-breath ${solStatus}" aria-hidden="true"></div><div>${solHtml}</div></div>`)
    );

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
    const informesStatus = statusFor(informesList);
    let informesHtml = "";
    if (informesList.length) {
      informesHtml += `<div class="audit-field"><b>Total Informes:</b><span class="field-value"> ${aud.informes?.length ?? informesList.length}</span></div>`;
      if (isrecCount)
        informesHtml += `<div class="audit-field"><b>Recomendaciones:</b><span class="field-value"> ${isrecCount}</span></div><div class="audit-field"><b>Estatus:</b><span class="field-value"> ${phaseBadgeHtml(phaseLabel(aud.informeprimeraetapa))}</span></div>`;
      if (ispliegosCount)
        informesHtml += `<div class="audit-field"><b>Pliegos:</b><span class="field-value"> ${ispliegosCount}</span></div><div class="audit-field"><b>Estatus:</b><span class="field-value"> ${phaseBadgeHtml(phaseLabel(aud.informepliegos))}</span></div>`;
    } else {
      informesHtml = "<div>No hay informes registrados</div>";
    }
    grid.insertAdjacentHTML(
      "beforeend",
      cardHTML("Informes", `<div class="d-flex align-items-start gap-3"><div class="status-breath ${informesStatus}" aria-hidden="true"></div><div>${informesHtml}</div></div>`)
    );

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
    grid.insertAdjacentHTML("beforeend", cardHTML("Turno UI", TurnoUIHtml));

    const TurnoOICBody = aud.turnooic
      ? `
        <div class="audit-field"><b>Número de expediente:</b> ${esc(aud.turnooic.numero_turno_oic ?? "-")}</div>
        <div class="audit-field"><b>Estatus del turno:</b> ${phaseBadgeHtml(esc(aud.turnooic.fase_autorizacion ?? "Pendiente"))}</div>`
      : "<div>No registrada</div>";
    const TurnoOICHtml = `<div class="d-flex align-items-start gap-3"><div class="status-breath ${TurnoOICStatus}" aria-hidden="true"></div><div>${TurnoOICBody}</div></div>`;
    grid.insertAdjacentHTML("beforeend", cardHTML("Turno OIC", TurnoOICHtml));

    const TurnoArchivoBody = aud.turnoarchivo
      ? `
        <div class="audit-field"><b>Número de expediente:</b> ${esc(aud.turnoarchivo.numero_turno_archivo ?? "-")}</div>
        <div class="audit-field"><b>Estatus del turno:</b> ${phaseBadgeHtml(esc(aud.turnoarchivo.fase_autorizacion ?? "Pendiente"))}</div>`
      : "<div>No registrada</div>";
    const TurnoArchivoHtml = `<div class="d-flex align-items-start gap-3"><div class="status-breath ${TurnoArchivoStatus}" aria-hidden="true"></div><div>${TurnoArchivoBody}</div></div>`;
    grid.insertAdjacentHTML("beforeend", cardHTML("Turno Archivo", TurnoArchivoHtml));

    const btnOcultar = document.getElementById("btnOcultarPanel");
    if (btnOcultar)
      btnOcultar.addEventListener("click", () => {
        if (panel) panel.classList.add("d-none");
        resetAvanceCard();
      });
    try {
      panel?.scrollIntoView({ behavior: "smooth", block: "start" });
    } catch (e) {}
  }

  // ====== INIT ======
  (function init() {
    injectNeonSkinStyles();
    // Oculta filtros hasta abrir auditorías
    toggleAuditFilters(false);

    setEncargadosDireccionCard("A");
    setEncargadosDireccionCard("B");
    renderDeptSolidGauge("dirA", "grafica_depto_auditoriasA", { useSideInfo: true });
    renderDeptSolidGauge("dirB", "grafica_deptos", { useSideInfo: true });

    // Limpia treemap inicial
    if (container) {
      container.innerHTML = `<div id="chartInner" class="treemap-neon" style="min-height: 80px;"></div>`;
    }
  })();
})();