// reportesauditoriaunidad.js
(function () {
    // ====== Debounce resize que re-aplica tamaños ======
    let chart = null; // <-- declaramos chart antes para el closure del resize
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
                            style: {
                                fontSize: FS.tooltip,
                                maxWidth: FS.tooltipMaxW,
                            },
                        },
                        series: [
                            {
                                dataLabels: {
                                    style: { fontSize: FS.dataLabel },
                                },
                            },
                        ],
                    },
                    false,
                );
                chart.reflow();
                chart.redraw();
            } catch (e) { }
        }, 150);
    });

    // ===== Utilidades =====
    // ========= Helpers para la tarjeta "Encargados" =========
    function setEncargadosDireccion(dirKey) {
        try {
            const dirMap = { dirA: 'Dirección de Seguimiento "A"', dirB: 'Dirección de Seguimiento "B"' };
            const dirTxt = dirMap[dirKey] || '—';
            const dirName = (window.REPORT_DATA && window.REPORT_DATA.directoresPorDireccion) ?
                window.REPORT_DATA.directoresPorDireccion[dirKey] : null;

            // Dirección + Director
            document.getElementById('enc_dir')?.replaceChildren(document.createTextNode(dirTxt));
            document.getElementById('enc_director')?.replaceChildren(document.createTextNode(dirName || '—'));

            // Oculta Depto/Jefe si venías de un depto seleccionado
            document.getElementById('encargadosDepto')?.classList.add('d-none');
            document.getElementById('encargadosJefe')?.classList.add('d-none');
            document.getElementById('enc_depto')?.replaceChildren(document.createTextNode('—'));
            document.getElementById('enc_jefe')?.replaceChildren(document.createTextNode('—'));
        } catch (e) { }
    }

    function setEncargadosDepartamento(deptId, deptName) {
        try {
            const jefe = (window.REPORT_DATA && window.REPORT_DATA.jefesPorDepartamento) ?
                window.REPORT_DATA.jefesPorDepartamento[Number(deptId)] : null;

            // Muestra Depto y Jefe
            const $d = document.getElementById('encargadosDepto');
            const $j = document.getElementById('encargadosJefe');
            if ($d) $d.classList.remove('d-none');
            if ($j) $j.classList.remove('d-none');

            document.getElementById('enc_depto')?.replaceChildren(document.createTextNode(deptName || '—'));
            document.getElementById('enc_jefe')?.replaceChildren(document.createTextNode(jefe || '—'));
        } catch (e) { }
    }
    function setAuditoriaSeleccionada(audId, AudName) {
        try {
            const Aud = (window.REPORT_DATA && window.REPORT_DATA.auditoriaSeleccionada) ?
                window.REPORT_DATA.auditoriaSeleccionada[Number(audId)] : null; 

            document.getElementById('audi_name')?.replaceChildren(document.createTextNode(AudName || '—'));
        } catch (e) { }
    }

    // === NUEVO: URL base para detalle y caché de progreso por auditoría ===
    const DETALLE_URL = (window.REPORT_DATA?.detalleBaseUrl || "").replace(/\/+$/, "");
    const _auditProgressCache = new Map(); // id -> { percent, done, total }

    /** Concurrencia limitada para fetches paralelos */
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

    /** Obtiene % de avance de una auditoría (usa caché en memoria) */
    async function getAuditProgressPercent(audId) {
        if (_auditProgressCache.has(audId)) return _auditProgressCache.get(audId);
        if (!DETALLE_URL) return { percent: 0, done: 0, total: 0 };
        const r = await fetch(`${DETALLE_URL}/${audId}`, { credentials: "same-origin" });
        const aud = await r.json();
        const av = computeAuditProgress(aud); // <- YA EXISTE EN TU CÓDIGO
        _auditProgressCache.set(audId, av);
        return av;
    }

    /** Calcula métricas del departamento: promedio % y % completadas (100%) */
    async function computeDeptMetrics(deptId) {
        const arr = (window.REPORT_DATA?.auditoriasMap?.[String(deptId)] || []);
        if (!arr.length) return { avgPercent: 0, completedPercent: 0, total: 0, completed: 0 };

        // Limita concurrencia a 5 para no saturar
        const avs = await mapWithConcurrency(arr, 5, async (a) => {
            const av = await getAuditProgressPercent(a.id);
            return av?.percent ?? 0;
        });

        const total = avs.length;
        const sum = avs.reduce((acc, p) => acc + (p || 0), 0);
        const completed = avs.filter(p => p >= 100).length;

        return {
            avgPercent: total ? Math.round(sum / total) : 0,
            completedPercent: total ? Math.round((completed / total) * 100) : 0,
            total,
            completed
        };
    }

    function toggleAuditFilters(show) {
        const card = document.getElementById("auditFiltersCard");
        if (!card) return;
        card.classList.toggle("d-none", !show);
    }

    function readFilters() {
        const form = document.getElementById('auditFiltersForm');
        if (!form) return { numero: '', entidad: '', acto: '' };

        const numero = (form.elements['numero_auditoria']?.value || '').trim();
        const entidad = (form.elements['entidad_fiscalizable']?.value || '').trim();
        const acto = (form.elements['acto_fiscalizacion']?.value || '').trim();

        // SIEMPRE modo “solo coincidencias”
        return { numero, entidad, acto, solo: true };
    }

    // helper case-insensitive “includes”
    function ciIncludes(haystack, needle) {
        if (!needle) return true;
        const h = String(haystack || "").toLocaleLowerCase();
        const n = String(needle || "").toLocaleLowerCase();
        return h.includes(n);
    }

    // Ajusta los dataLabels HTML para que jamás se salgan del tile
    function fitHtmlLabels(hcChart, clampLines) {
        const chart = hcChart;
        if (!chart || !chart.series || !chart.series.length) return;

        const s = chart.series[0]; // tu treemap principal
        const bw =
            s && s.options && s.options.borderWidth ? s.options.borderWidth : 0;
        const pad = 8; // padding interior de seguridad

        (s.points || []).forEach((p) => {
            if (!p || !p.shapeArgs || !p.dataLabel) return;

            const sh = p.shapeArgs; // {x, y, width, height}
            const el = p.dataLabel.div || p.dataLabel.element || p.dataLabel;
            if (!el) return;

            const maxW = Math.max(12, sh.width - pad - bw * 1.5);
            const maxH = Math.max(12, sh.height - pad - bw * 1.5);

            // Busca el "inner" que devolvemos desde el formatter
            const inner = el.querySelector(".hc-label-inner");
            if (!inner) return;

            // Limites duros para que no se salga
            inner.style.maxWidth = maxW + "px";
            inner.style.maxHeight = maxH + "px";
            inner.style.overflow = "hidden";
            inner.style.whiteSpace = "normal";
            inner.style.lineHeight = "1.2";
            inner.style.textAlign = "center";
            inner.style.display = "-webkit-box";
            inner.style.webkitBoxOrient = "vertical";
            inner.style.webkitLineClamp = String(clampLines || 3); // 2 líneas para dir/depto, 3 para auditorías

            // El wrapper (flex) para centrar siempre el texto dentro del tile
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

    // ====== NUEVO: Skin neón + fondo animado para el treemap ======
    function injectNeonSkinStyles() {
        const id = "treemap-neon-styles";
        const existing = document.getElementById(id);
        const css = `
    :root{
      /* Fondo (gris sutil) */
      --bg1: #f7f8f9;        /* muy claro */
      --bg2: #ffffff;

      /* Aura gris (usa #88898C convertido a rgba) */
      --aura: rgba(136,137,140,.22);
      --aura-strong: rgba(136,137,140,.34);

      /* Neón dorado (borde + glow) #BB945C */
      --neon: rgba(187,148,92,.75);
      --neon-strong: rgba(187,148,92,1);
      --neon-hover: rgba(239,193,139,.95);  /* #EFC18B para hover */

      /* Tipografías / estados / tooltips */
      --ink: #132a29;
      --muted: #888B86;
      --ok: #20C997;
      --warn: #F59E0B;
      --error: #EF4444;
      --tooltip-bg: rgba(255,255,255,.96);
      --tooltip-fg: #132a29;
    }
    /* === Resaltado de coincidencia (búsqueda) === */
      #chartInner .highcharts-point.is-highlight{
        fill: rgba(239,193,139,.20) !important;         /* dorado claro */
        stroke-width: 4 !important;
        filter:
          drop-shadow(0 0 10px rgba(239,193,139,.70))
          drop-shadow(0 0 24px rgba(239,193,139,.50));
        animation: hi-pulse 1.6s ease-in-out 2;
      }
      @keyframes hi-pulse {
        0%,100% { filter:
          drop-shadow(0 0 10px rgba(239,193,139,.70))
          drop-shadow(0 0 24px rgba(239,193,139,.50)); }
        50% { filter:
          drop-shadow(0 0 14px rgba(239,193,139,.85))
          drop-shadow(0 0 30px rgba(239,193,139,.65)); }
      }


    /* Contenedor con degradado gris MUY sutil + aura animada */
    #chartInner.treemap-neon{
      position: relative;
      overflow: hidden;
      border-radius: 20px;
      background:
        radial-gradient(1200px 800px at var(--mx,50%) var(--my,50%),
          color-mix(in srgb, var(--aura) 10%, transparent),
          color-mix(in srgb, var(--aura) 6%, transparent) 35%,
          color-mix(in srgb, var(--aura) 4%, transparent) 60%,
          transparent 72%),
        linear-gradient(180deg,var(--bg1),var(--bg2));
    }
    #chartInner.treemap-neon::before{
      content: '';
      position: absolute; inset: -2px;
      background:
        conic-gradient(from var(--rot,0deg),
          color-mix(in srgb, var(--aura-strong) 14%, transparent),
          transparent 40%,
          color-mix(in srgb, var(--aura-strong) 12%, transparent) 80%),
        radial-gradient(600px 400px at var(--mx,50%) var(--my,50%),
          color-mix(in srgb, var(--aura-strong) 9%, transparent),
          transparent 60%);
      filter: blur(14px) saturate(1.0);
      animation: treemap-spin 26s linear infinite;  /* lento, sutil */
      pointer-events: none;
      z-index: 0;
    }
    @keyframes treemap-spin { to { --rot: 360deg; } }

    /* Asegurar que el SVG quede arriba */
    #chartInner.treemap-neon .highcharts-container,
    #chartInner.treemap-neon svg{ position: relative; z-index: 1; }

    /* TIPOGRAFÍA GLOBAL (incluye direcciones) */
    #chartInner.treemap-neon,
    #chartInner.treemap-neon * { color: var(--ink); }

    /* Forzar color de label sea cual sea el contraste que ponga Highcharts */
    #chartInner.treemap-neon .highcharts-data-label text,
    #chartInner.treemap-neon .highcharts-data-label span{
      fill: var(--ink) !important;
      color: var(--ink) !important;
    }
    #chartInner.treemap-neon .highcharts-data-label-contrast text,
    #chartInner.treemap-neon .highcharts-data-label-contrast span{
      fill: var(--ink) !important;
      color: var(--ink) !important;
    }

    /* TILES: transparentes + borde dorado más grueso + glow */
    #chartInner.treemap-neon .highcharts-point{
      fill: transparent !important;
      stroke: var(--neon-strong);
      stroke-width: 3.8; /* ↑ más grueso para que se note */
      filter:
        drop-shadow(0 0 6px color-mix(in srgb, var(--neon) 100%, transparent))
        drop-shadow(0 0 14px color-mix(in srgb, var(--neon) 100%, transparent));
      transition: filter .18s ease, stroke-width .18s ease;
    }
    #chartInner.treemap-neon .highcharts-point.neon-hover{
      /* Película sutil + glow dorado más intenso al hover */
      fill: color-mix(in srgb, var(--neon-hover) 8%, transparent) !important;
      stroke-width: 2.0;
      filter:
        drop-shadow(0 0 10px color-mix(in srgb, var(--neon-hover) 85%, transparent))
        drop-shadow(0 0 22px color-mix(in srgb, var(--neon-hover) 60%, transparent));
    }

    /* Motion reduce */
    @media (prefers-reduced-motion: reduce){
      #chartInner.treemap-neon::before{ animation: none; }
    }
    `;
        if (existing) {
            // Si ya existe, reemplaza el contenido (evita quedar “pegado” a una versión vieja).
            existing.textContent = css;
            return;
        }
        const s = document.createElement("style");
        s.id = id;
        s.appendChild(document.createTextNode(css));
        document.head.appendChild(s);
    }

    function esc(s) {
        return String(s).replace(
            /[&<>\"']/g,
            (ch) =>
                ({
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    '"': "&quot;",
                    "'": "&#39;",
                })[ch],
        );
    }
    function sumReal(arr) {
        return (arr || []).reduce((acc, p) => acc + (p.real ?? 0), 0);
    }

    // Measure text helper (canvas) to determine overflow
    const _measureCanvas = (function () {
        const c = document.createElement("canvas");
        return c.getContext ? c.getContext("2d") : null;
    })();
    function measureTextWidth(text, font) {
        if (!_measureCanvas) return text.length * 8;
        _measureCanvas.font = font || "600 16px sans-serif";
        return _measureCanvas.measureText(text).width;
    }

    // ===== Datos desde window.REPORT_DATA (inyectado por la Blade) =====
    const {
        treemapData: baseData = [],
        auditoriasMap = {},
        currentUserId = null,
        detalleBaseUrl = "",
    } = window.REPORT_DATA || {};

    const dirNames = {
        dirA: 'Dirección de Seguimiento "A"',
        dirB: 'Dirección de Seguimiento "B"',
    };
    const dirColors = { dirA: "#A13B71", dirB: "#A13B71" };

    const deptsByDir = {
        dirA: (baseData || []).filter((p) => p.parent === "dirA"),
        dirB: (baseData || []).filter((p) => p.parent === "dirB"),
    };

    const container = document.getElementById("container");

    // Small loading spinner (injected once) and helpers to show/hide it
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
        // Estático (blanco/gris sin animación):
        //document.getElementById('chartInner')?.classList.add('static');
        // Animado:
        //document.getElementById('chartInner')?.classList.remove('static');
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
    // ========= % Avance de la auditoría =========
    function toArr(v) { if (!v) return []; return Array.isArray(v) ? v : [v]; }
    function isAuth(val) { return String(val ?? '').trim() === 'Autorizado'; }
    // ========= Tooltip de avance (detalle por secciones) =========
    function buildProgressTooltip(av) {
        const b = av.breakdown;
        const icon = (d) => (d.total ? (d.done === d.total ? '✅' : '⏳') : '—');
        const row = (label, d) => `<div><b>${label}:</b> ${d.done}/${d.total} ${icon(d)}</div>`;

        return `
        <div style="min-width:260px">
        ${row('Radicación', b.radicacion)}
        ${row('Comparecencia', b.comparecencia)}
        <hr style="margin:6px 0"/>
        ${row('Acuerdos (Recs.)', b.acuerdosRecs)}
        ${row('Acuerdos (Pliegos)', b.acuerdosPliegos)}
        <hr style="margin:6px 0"/>
        ${row('Acciones (Recs.)', b.accionesRecs)}
        ${row('Acciones (PO)', b.accionesPO)}
        ${row('Acciones (Sol.)', b.accionesSol)}
        ${row('Acciones (PRAS)', b.accionesPRAS)}
        <hr style="margin:6px 0"/>
        ${row('Informes (Recs.)', b.informesRecs)}
        ${row('Informes (Pliegos)', b.informesPliegos)}
        <hr style="margin:6px 0"/>
        ${row('Turno UI', b.turnoUI)}
        ${row('Turno OIC', b.turnoOIC)}
        ${row('Turno Archivo', b.turnoArchivo)}
        </div>
    `;
    }

    function applyProgressTooltip(targetEl, av) {
        if (!targetEl) return;
        const html = buildProgressTooltip(av);

        // Limpia instancia previa (evita tooltips duplicados)
        try {
            if (targetEl._avTooltip && typeof targetEl._avTooltip.dispose === 'function') {
                targetEl._avTooltip.dispose();
            }
        } catch (e) { }

        targetEl.setAttribute('data-bs-toggle', 'tooltip');
        targetEl.setAttribute('data-bs-html', 'true');
        targetEl.setAttribute('data-bs-placement', 'bottom');
        targetEl.setAttribute('title', html);

        // Si está Bootstrap 5, inicializa tooltip HTML
        if (window.bootstrap && bootstrap.Tooltip) {
            targetEl._avTooltip = new bootstrap.Tooltip(targetEl, {
                container: 'body',
                trigger: 'hover focus',
                html: true,
                // El contenido lo generamos nosotros (sin entradas de usuario), es seguro desactivar sanitize:
                sanitize: false,
                boundary: 'window'
            });
        } else {
            // Fallback: usa title nativo (sin HTML)
            targetEl.setAttribute(
                'title',
                html.replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim()
            );
        }
    }
    function computeAuditProgress(aud) {
        let total = 0, done = 0;

        const breakdown = {
            radicacion: { total: 0, done: 0 },
            comparecencia: { total: 0, done: 0 },
            acuerdosRecs: { total: 0, done: 0 },
            acuerdosPliegos: { total: 0, done: 0 },
            accionesRecs: { total: 0, done: 0 },
            accionesPO: { total: 0, done: 0 },
            accionesSol: { total: 0, done: 0 },
            accionesPRAS: { total: 0, done: 0 },
            informesRecs: { total: 0, done: 0 }, // informeprimeraetapa
            informesPliegos: { total: 0, done: 0 }, // informepliegos
            turnoUI: { total: 0, done: 0 },
            turnoOIC: { total: 0, done: 0 },
            turnoArchivo: { total: 0, done: 0 }
        };

        // --- Radicación: Autorizado + acuse de comparecencia
        if (aud.radicacion) {
            breakdown.radicacion.total++; total++;
            const ok = isAuth(aud.radicacion.fase_autorizacion)
                && !!(aud.comparecencia && aud.comparecencia.oficio_acuerdo);
            if (ok) { breakdown.radicacion.done++; done++; }
        }

        // --- Comparecencia
        if (aud.comparecencia) {
            breakdown.comparecencia.total++; total++;
            if (isAuth(aud.comparecencia.fase_autorizacion)) {
                breakdown.comparecencia.done++; done++;
            }
        }

        // --- Acuerdos de conclusión (Recomendaciones)
        toArr(aud.acuerdoconclusion).forEach(rec => {
            breakdown.acuerdosRecs.total++; total++;
            const ok = isAuth(rec?.fase_autorizacion) && !!rec?.oficio_recepcion;
            if (ok) { breakdown.acuerdosRecs.done++; done++; }
        });

        // --- Acuerdos de conclusión (Pliegos)
        toArr(aud.acuerdoconclusionpliegos).forEach(pl => {
            breakdown.acuerdosPliegos.total++; total++;
            const ok = isAuth(pl?.fase_autorizacion) && !!pl?.oficio_recepcion;
            if (ok) { breakdown.acuerdosPliegos.done++; done++; }
        });

        // --- Acciones (por tipo, sobre la relación hija con fase)
        function countActionRel(list, relKey, bucketKey) {
            toArr(list).forEach(act => {
                const rel = act?.[relKey];
                if (!rel) return;
                toArr(rel).forEach(r => {
                    breakdown[bucketKey].total++; total++;
                    if (isAuth(r?.fase_autorizacion)) { breakdown[bucketKey].done++; done++; }
                });
            });
        }
        countActionRel(aud.accionesrecomendaciones, 'recomendaciones', 'accionesRecs');
        countActionRel(aud.accionespo, 'pliegosobservacion', 'accionesPO');
        countActionRel(aud.accionessolacl, 'solicitudesaclaracion', 'accionesSol');
        countActionRel(aud.accionespras, 'pras', 'accionesPRAS');

        // --- Informes
        toArr(aud.informeprimeraetapa).forEach(inf => {
            breakdown.informesRecs.total++; total++;
            if (isAuth(inf?.fase_autorizacion)) { breakdown.informesRecs.done++; done++; }
        });
        toArr(aud.informepliegos).forEach(inf => {
            breakdown.informesPliegos.total++; total++;
            if (isAuth(inf?.fase_autorizacion)) { breakdown.informesPliegos.done++; done++; }
        });

        // --- Turnos (UI / OIC / Archivo)
        const turnos = [
            ['turnoui', 'turnoUI'],
            ['turnooic', 'turnoOIC'],
            ['turnoarchivo', 'turnoArchivo']
        ];
        turnos.forEach(([key, bKey]) => {
            const node = aud[key];
            if (!node) return;
            breakdown[bKey].total++; total++;
            if (isAuth(node?.fase_autorizacion)) { breakdown[bKey].done++; done++; }
        });

        const percent = total ? Math.round((done / total) * 100) : 0;
        return { percent, done, total, breakdown };
    }

    function resetAvanceCard() {
        const t = document.getElementById('avanceAuditoria'); if (t) t.textContent = '—';
        const b = document.getElementById('avanceBar');
        const a = document.getElementById('audi_name');if (a) a.textContent = '—';
        if (b) {
            b.style.width = '0%';
            b.setAttribute('aria-valuenow', '0');
            b.classList.remove('bg-success', 'bg-warning', 'bg-danger');
            b.classList.add('bg-warning');
        }
    }
    // ---- Helpers de responsividad ----
    function isMobile() {
        return window.matchMedia("(max-width: 576px)").matches; // Bootstrap xs
    }

    function getFontSizes() {
        const mobile = isMobile();
        return {
            dataLabel: mobile ? "12px" : "16px", // etiquetas dentro de cada nodo
            tooltip: mobile ? "12px" : "14px", // hover
            title: mobile ? "16px" : "20px",
            subtitle: mobile ? "12px" : "14px",
            tooltipMaxW: mobile ? "280px" : "640px",
        };
    }

    // ===== Dibujador genérico de treemap EN UN CONTENEDOR HIJO (labels visibles) =====
    function drawTreemapIn(
        renderToId,
        title,
        subtitle,
        nodes,
        onPointClick,
        options = {},
    ) {
        if (chart) {
            chart.destroy();
            chart = null;
        }
        const FS = getFontSizes();
        chart = Highcharts.chart(renderToId, {
            chart: {
                spacing: [10, 10, 10, 10],
                backgroundColor: "transparent", // ← NUEVO
                plotBackgroundColor: "transparent", // ← NUEVO
                height: options.height || null,
            },

            events: {
                render: function () {
                    // 2 líneas para Direcciones/Departamentos, 3 para Auditorías (puedes pasarlo por options)
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

            title: {
                text: title,
                align: "center",
                style: { fontSize: FS.title, color: "#132a29" },
            },

            subtitle: {
                text: subtitle || "",
                align: "center",
                style: { fontSize: FS.subtitle, color: "#132a29" },
            },

            credits: { enabled: false },
            exporting: { enabled: true },

            series: [
                {
                    type: "treemap",
                    borderWidth: 10.2,
                    borderColor: "#BB945C",
                    borderRadius: 10,
                    pointPadding: options.pointPadding ?? 0, // ← NUEVO
                    nodePadding: options.nodePadding ?? 2, // ← NUEVO
                    states: {
                        hover: {
                            enabled: true,
                            brightness: 0, // ← no oscurecer (conserva fondo)
                            borderColor: "#fff", // borde clarito al hover
                        },
                    },

                    dataLabels: {
                        enabled: true,
                        useHTML: true,
                        inside: true,
                        allowOverlap: false,
                        crop: true,
                        overflow: "justify",
                        padding: 2,
                        style: {
                            textOutline: "none",
                            fontWeight: "600",
                            fontSize: options.labelFontSize || FS.dataLabel,
                            color: "#132a29",
                        },
                        formatter: function () {
                            const p = this.point;
                            // Helper local de truncado muy simple para casos extremos
                            const clamp = (txt, max = 140) => {
                                txt = String(txt || "").trim();
                                return txt.length > max
                                    ? txt.slice(0, max - 1) + "…"
                                    : txt;
                            };

                            // Vista de Direcciones / Departamentos (tienen 'real')
                            if (typeof p.real !== "undefined") {
                                const total = Highcharts.numberFormat(
                                    p.real || 0,
                                    0,
                                );
                                const title = esc(clamp(p.name, 70));
                                return `
                <div class="hc-label-wrap">
                  <div class="hc-label-inner">
                    <div style="font-weight:700">${title}</div>
                    <div style="opacity:.9">${esc(String(total))}</div>
                  </div>
                </div>
              `;
                            }

                            // Vista de Auditorías
                            try {
                                const numero = p.numero_auditoria
                                    ? esc(clamp(p.numero_auditoria, 30))
                                    : "";
                                const entidad = p.nombre_entidad
                                    ? esc(clamp(p.nombre_entidad, 110))
                                    : p.entidad_fiscalizable
                                        ? esc(clamp(p.entidad_fiscalizable, 110))
                                        : "";
                                const acto = p.acto_fiscalizacion
                                    ? esc(clamp(p.acto_fiscalizacion, 80))
                                    : "";
                                const lines = [numero, entidad, acto].filter(
                                    Boolean,
                                );
                                const html = lines
                                    .map((l) => `<div>${l}</div>`)
                                    .join("");
                                return `
                <div class="hc-label-wrap">
                  <div class="hc-label-inner">${html}</div>
                </div>
              `;
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
                        style: {
                            color: "#132a29",
                            fontSize: FS.tooltip,
                            maxWidth: FS.tooltipMaxW,
                            whiteSpace: "normal",
                        },
                        // Positioner: prefer pointer coords; avoid centering over the point
                        positioner: function (labelWidth, labelHeight, point) {
                            const chart = this.chart || (this && this.chart);
                            if (!chart) return { x: 10, y: 10 };
                            const cx =
                                point && point.chartX !== undefined
                                    ? point.chartX
                                    : point && point.plotX
                                        ? chart.plotLeft + point.plotX
                                        : chart.plotWidth / 2;
                            const cy =
                                point && point.chartY !== undefined
                                    ? point.chartY
                                    : point && point.plotY
                                        ? chart.plotTop + point.plotY
                                        : chart.plotHeight / 4;
                            let x = cx + 12;
                            let y = cy - labelHeight / 2;
                            if (x + labelWidth > chart.chartWidth - 10)
                                x = cx - labelWidth - 12;
                            if (x < 10) x = 10;
                            if (y < chart.plotTop + 5) y = chart.plotTop + 5;
                            if (
                                y + labelHeight >
                                chart.plotTop + chart.plotHeight - 5
                            )
                                y =
                                    chart.plotTop +
                                    chart.plotHeight -
                                    labelHeight -
                                    5;
                            return { x, y };
                        },
                        pointFormatter: function () {
                            try {
                                const p = this;

                                // 1) Arma líneas de TEXTO PLANO para medir si cabe el label (no HTML aquí)
                                const shape = p.shapeArgs || {};
                                const boxW =
                                    shape.width || (p.plotX ? p.plotX : 0);
                                const boxH =
                                    shape.height || (p.plotY ? p.plotY : 0);

                                let textLines = [];
                                if (typeof p.real !== "undefined") {
                                    textLines = [
                                        String(p.name || ""),
                                        String(
                                            Highcharts.numberFormat(
                                                p.real || 0,
                                                0,
                                            ),
                                        ),
                                    ];
                                } else {
                                    if (p.numero_auditoria)
                                        textLines.push(
                                            String(p.numero_auditoria),
                                        );
                                    if (p.nombre_entidad)
                                        textLines.push(
                                            String(p.nombre_entidad),
                                        );
                                    else if (p.entidad_fiscalizable)
                                        textLines.push(
                                            String(p.entidad_fiscalizable),
                                        );
                                    if (p.acto_fiscalizacion)
                                        textLines.push(
                                            String(p.acto_fiscalizacion),
                                        );
                                    if (textLines.length === 0)
                                        textLines.push(String(p.name || ""));
                                }

                                const fontSize =
                                    options && options.labelFontSize
                                        ? String(options.labelFontSize)
                                        : "18px";
                                const font = `600 ${fontSize} sans-serif`;
                                const longest = textLines.reduce(
                                    (m, ln) =>
                                        Math.max(
                                            m,
                                            measureTextWidth(String(ln), font),
                                        ),
                                    0,
                                );
                                const lineHeight =
                                    parseInt(fontSize, 10) * 1.15;
                                const totalHeight =
                                    textLines.length * lineHeight;
                                const padding = 8;
                                const fits =
                                    boxW &&
                                    longest <= boxW - padding &&
                                    boxH &&
                                    totalHeight <= boxH - padding;
                                if (fits) {
                                    if (
                                        p.series &&
                                        p.series.chart &&
                                        p.series.chart.tooltip
                                    )
                                        p.series.chart.tooltip.hide();
                                    return "";
                                }

                                // 2) Construye el HTML del tooltip (ya sin contaminar textLines)
                                //const header = `<div style="font-weight:700;margin-bottom:6px;color:#000;">${esc(p.name || '')}</div>`;
                                const header = `<div style="font-weight:700;margin-bottom:6px;color:#132a29;">${esc(p.name || "")}</div>`;
                                let cuerpo = "";
                                if (typeof p.real !== "undefined") {
                                    cuerpo += `<div><b>Total:</b> ${Highcharts.numberFormat(p.real || 0, 0)}</div>`;
                                } else {
                                    const entidad =
                                        p.nombre_entidad ||
                                        p.entidad_fiscalizable;
                                    if (entidad)
                                        cuerpo += `<br><div><b>Entidad:</b> ${esc(String(entidad))}</div>`;
                                    if (p.acto_fiscalizacion)
                                        cuerpo += `<br><div><b>Acto:</b> ${esc(String(p.acto_fiscalizacion))}</div>`;
                                }

                                return `<div style="max-width:${getFontSizes().tooltipMaxW};white-space:normal;color:#132a29;">${header}${cuerpo}</div>`;
                            } catch (e) {
                                try {
                                    this.series.chart.tooltip.hide();
                                } catch (err) { }
                                return "";
                            }
                        },
                    },
                    // Punto: click + control de tooltip en mouseOver
                    point: {
                        events: {
                            click: function () {
                                if (onPointClick) onPointClick(this);
                            },
                            mouseOver: function () {
                                try {
                                    // ===== NUEVO: aplicar clase para glow al hover
                                    if (this.graphic && this.graphic.element) {
                                        this.graphic.element.classList.add(
                                            "neon-hover",
                                        );
                                    }
                                    // ===== Tu lógica actual para tooltips:
                                    const p = this;
                                    const dl = p && p.dataLabel;
                                    if (dl) {
                                        let el = null;
                                        if (dl.element) el = dl.element;
                                        else if (dl.div) el = dl.div;
                                        else if (dl && dl.nodeType) el = dl;
                                        if (
                                            el &&
                                            el.scrollWidth !== undefined &&
                                            el.clientWidth !== undefined
                                        ) {
                                            const overflow =
                                                el.scrollWidth >
                                                el.clientWidth ||
                                                el.scrollHeight >
                                                el.clientHeight;
                                            if (!overflow) {
                                                if (
                                                    p.series &&
                                                    p.series.chart &&
                                                    p.series.chart.tooltip
                                                )
                                                    p.series.chart.tooltip.hide();
                                            }
                                        }
                                    }
                                } catch (e) {
                                    try {
                                        this.series.chart.tooltip.hide();
                                    } catch (err) { }
                                }
                            },
                            mouseOut: function () {
                                try {
                                    // ===== NUEVO: quitar clase de glow
                                    if (this.graphic && this.graphic.element) {
                                        this.graphic.element.classList.remove(
                                            "neon-hover",
                                        );
                                    }
                                    if (
                                        this.series &&
                                        this.series.chart &&
                                        this.series.chart.tooltip
                                    )
                                        this.series.chart.tooltip.hide();
                                } catch (e) { }
                            },
                        },
                    },

                    data: nodes,
                },
            ],

            // ---- Reglas responsive nativas de Highcharts (extra) ----
            responsive: {
                rules: [
                    {
                        condition: { maxWidth: 576 }, // móvil chico
                        chartOptions: {
                            title: { style: { fontSize: "16px" } },
                            subtitle: { style: { fontSize: "12px" } },
                            series: [
                                {
                                    dataLabels: {
                                        style: { fontSize: "12px" },
                                        padding: 1,
                                    },
                                },
                            ],
                            tooltip: {
                                style: { fontSize: "12px", maxWidth: "280px" },
                            },
                        },
                    },
                    {
                        condition: { maxWidth: 768 }, // tablet / móvil grande
                        chartOptions: {
                            title: { style: { fontSize: "18px" } },
                            subtitle: { style: { fontSize: "13px" } },
                            series: [
                                { dataLabels: { style: { fontSize: "14px" } } },
                            ],
                            tooltip: {
                                style: { fontSize: "13px", maxWidth: "400px" },
                            },
                        },
                    },
                ],
            },
        });

        // Prevent tiny empty tooltip boxes: observe tooltip element and hide when empty
        try {
            const chartContainer = chart && chart.container;
            if (chartContainer) {
                const hideEmptyTooltip = () => {
                    const t = chartContainer.querySelector(
                        ".highcharts-tooltip",
                    );
                    if (t) {
                        if (!t.textContent || t.textContent.trim() === "")
                            t.style.display = "none";
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
                        chartContainer.removeEventListener(
                            "mousemove",
                            hideEmptyTooltip,
                        );
                    } catch (e) { }
                    origDestroy.apply(this, arguments);
                };
            }
        } catch (e) {
            /* ignore observer errors */
        }
        attachBgMotion(renderToId);
    }
    // === NUEVO: Estilos simples para el bloque de la gráfica
    function injectDeptProgressStyles() {
        if (document.getElementById("dept-progress-styles")) return;
        const css = `
    #deptProgressWrap{
        margin-bottom: .75rem;
        border-radius: 16px;
        padding: 8px;
        background: linear-gradient(180deg, #fff, #f9fafb);
        box-shadow: 0 0 0 1px rgba(187,148,92,.15), 0 8px 20px rgba(187,148,92,.08);
    }
    #deptProgressChart { min-height: 380px; }
    #deptProgressHeader { display:flex; align-items:center; justify-content:space-between; gap:8px; padding: 6px 8px 2px; color:#132a29; }
    #deptProgressHeader .small { color:#6b7280; }
    `;
        const s = document.createElement('style');
        s.id = "dept-progress-styles";
        s.appendChild(document.createTextNode(css));
        document.head.appendChild(s);
    }
    injectDeptProgressStyles();

    // === Utils de layout dinámico para el gauge ===
    function getRect(el){ return el ? el.getBoundingClientRect() : { width: 0, height: 0 }; }

    /**
    * Decide si debemos apilar (stack) el layout porque NO cabe lado a lado.
    * Reglas:
    * - Usamos: ancho_figure_min + ancho_panel_min + gap  vs  ancho_wrap
    * - Si no cabe => añade .is-stacked
    * - Si cabe => quita .is-stacked
    */
    function autoStackGaugeLayout() {
    const wrap = document.getElementById('grafica_deptos_wrap');
    if (!wrap) return;

    const fig = wrap.querySelector('.highcharts-figure');
    const panel = document.getElementById('grafica_deptos_info');

    // Si algo falta, no forzamos nada
    if (!fig || !panel) return;

    const gap = 14; // el mismo que en CSS
    // Estos "mínimos" deben estar alineados con tu CSS:
    const minFigure = 280; // min-width de figure
    const minPanel  = 200; // min-width del panel
    const need = minFigure + minPanel + gap;

    const wrapW = getRect(wrap).width;

    if (wrapW === 0) return;             // aún no está visible
    if (wrapW < need) {
        if (!wrap.classList.contains('is-stacked')) wrap.classList.add('is-stacked');
    } else {
        if (wrap.classList.contains('is-stacked')) wrap.classList.remove('is-stacked');
    }
    }
    async function renderDeptSolidGauge(dirKey, mountId = 'grafica_deptos') {
        const mount = document.getElementById(mountId);
        if (!mount) { console.warn(`[DeptGauge] No existe #${mountId}`); return; }

        // === Asegura el wrapper y el panel derecho (si no existen, los creamos) ===
        (function ensureWrap() {
            const wrap = document.getElementById('grafica_deptos_wrap');
            const info = document.getElementById('grafica_deptos_info');
            if (wrap && info) return;

            // Creamos el wrap y movemos el div del chart adentro
            const newWrap = document.createElement('div');
            newWrap.id = 'grafica_deptos_wrap';
            newWrap.className = 'dept-gauge-wrap';

            // Si el #grafica_deptos está dentro de <figure>, lo movemos con su figure
            const fig = mount.closest('figure') || mount;
            fig.parentNode.insertBefore(newWrap, fig);
            newWrap.appendChild(fig);

            const side = document.createElement('div');
            side.id = 'grafica_deptos_info';
            side.className = 'dept-gauge-info';
            side.innerHTML = `<div class="muted">Pasa el cursor sobre un anillo para ver los detalles…</div>`;
            newWrap.appendChild(side);
        })();

        const infoEl = document.getElementById('grafica_deptos_info');

        // Loader local (tu swapping squares)
        const prevHTML = mount.innerHTML;
        mount.style.minHeight = '220px';
        mount.innerHTML = `
    <div style="display:flex;align-items:center;justify-content:center;height:100%;">
      <div class="swapping-squares-spinner" aria-label="Cargando">
        <div class="square"></div><div class="square"></div>
        <div class="square"></div><div class="square"></div>
      </div>
    </div>`;

        try {
            if (!window.Highcharts || !Highcharts.chart) return;

            // Toma 3 departamentos (si hay menos, completa con placeholders)
            const baseDepts = (deptsByDir?.[dirKey] || []).slice(0, 3);
            const depts = baseDepts.slice();
            while (depts.length < 3) depts.push({ name: '—', deptId: null });

            // Métricas
            const results = await Promise.all(depts.map(d => d.deptId ? computeDeptMetrics(d.deptId) : Promise.resolve({
                avgPercent: 0, completed: 0, total: 0, completedPercent: 0
            })));

            // Colores corporativos
            const brand = { ink: '#fff', magenta: '#A13B71', gold: '#BB945C', accent: '#960048' };
            const ringColors = [brand.magenta, brand.gold, brand.accent];

            // ===== Helper: actualiza panel derecho =====
            function updateSideInfo(idx) {
            const d = depts[idx] || {};
            const m = results[idx] || { avgPercent: 0, completed: 0, total: 0, completedPercent: 0 };
            const prefix = (dirKey === 'dirA') ? 'A' : 'B';
            const short = `${prefix}${idx + 1}`;
            const color = ringColors[idx % ringColors.length];

            infoEl.innerHTML = `
                <h6>${esc(dirNames?.[dirKey] || '')}</h6>
                <div class="kpi"><strong>${esc(d.name || short)}</strong></div>
                <div class="kpi">
                <span class="muted">Avance:</span>
                <span class="big" style="color:${color}">${m.avgPercent}%</span>
                </div>
                <div class="kpi"><span class="muted">Completadas:</span> ${m.completed} / ${m.total} (${m.completedPercent}%)</div>
            `;
            }


            // ===== Tracks y series al estilo demo (porcentajes fijos) =====
            const paneBackground = [
                { outerRadius: '112%', innerRadius: '88%', backgroundColor: Highcharts.color(ringColors[0]).setOpacity(0.18).get('rgba'), borderWidth: 0 },
                { outerRadius: '87%', innerRadius: '63%', backgroundColor: Highcharts.color(ringColors[1]).setOpacity(0.18).get('rgba'), borderWidth: 0 },
                { outerRadius: '62%', innerRadius: '38%', backgroundColor: Highcharts.color(ringColors[2]).setOpacity(0.18).get('rgba'), borderWidth: 0 }
            ];

            // Qué valor mostrar (puedes cambiar avgPercent -> completedPercent)
            const vals = results.map(m => m.avgPercent); // o m.completedPercent

            // Nombres (si quieres ver el nombre real del depto en panel)
            const names = depts.map((d, i) => d.name || ((dirKey === 'dirA' ? 'A' : 'B') + (i + 1)));

            // Limpiar loader y montar
            mount.innerHTML = '';
            const gChart = Highcharts.chart(mountId, {
                chart: {
                    type: 'solidgauge',
                    height: '110%',
                    backgroundColor: 'transparent',
                    events: {
                    render: function () {
                        const c = this;
                        const probe = c.series?.[0]?.points?.[0];
                        if (!probe?.shapeArgs || isNaN(probe.shapeArgs.r) || isNaN(probe.shapeArgs.innerR)) return;

                        (c.series || []).forEach((ser, i) => {
                        const p = ser.points?.[0]; if (!p?.shapeArgs) return;
                        const sa = p.shapeArgs;
                        const midY = (c.plotHeight / 2) - sa.innerR - ((sa.r - sa.innerR) / 2) + 8;
                        const posX = (c.chartWidth / 2) - 15;
                        const label = `${dirKey === 'dirA' ? 'A' : 'B'}${i + 1}`;

                        if (!ser._fixedLabel) {
                            ser._fixedLabel = c.renderer
                            .text(label, posX, midY)
                            .attr({ zIndex: 10 })
                            .css({ color: brand.ink, fontWeight: 700, fontSize: '12px' })
                            .add(c.series[Math.min(2, c.series.length - 1)].group);
                        } else {
                            ser._fixedLabel.attr({ x: posX, y: midY });
                        }
                        });
                    }
                    }
                },
                title: { text: 'Avance por Depto.', style: { fontSize: '14px', color: brand.ink2, fontWeight: 700 } },
                subtitle: { text: dirNames?.[dirKey] || '', style: { fontSize: '12px', color: brand.ink2 } },

                tooltip: { enabled: false }, // ← SIN tooltip centrado; usamos panel derecho

                pane: { startAngle: 0, endAngle: 360, background: paneBackground },

                yAxis: { min: 0, max: 100, lineWidth: 0, tickPositions: [] },

                plotOptions: {
                    solidgauge: {
                        dataLabels: { enabled: false },
                        linecap: 'round',
                        stickyTracking: false,  // <- hover "real" en el anillo (mejor UX)
                        rounded: true
                    },
                    series: {
                        animation: { duration: 300 },
                        cursor: 'pointer',
                        point: {
                            events: {
                                mouseOver: function () {
                                    const idx = this.series.index;
                                    updateSideInfo(idx);
                                },
                                click: function () {
                                    const idx = this.series.index;
                                    const d = depts[idx];
                                    if (d?.deptId) viewAuditorias(dirKey, d.deptId, dirNames[dirKey], d.name);
                                }
                            }
                        }
                    }
                },

                credits: { enabled: false },
                exporting: { enabled: false },

                series: [
                    {
                        name: names[0],
                        data: [{ color: ringColors[0], radius: '112%', innerRadius: '88%', y: vals[0] }]
                    },
                    {
                        name: names[1],
                        data: [{ color: ringColors[1], radius: '87%', innerRadius: '63%', y: vals[1] }]
                    },
                    {
                        name: names[2],
                        data: [{ color: ringColors[2], radius: '62%', innerRadius: '38%', y: vals[2] }]
                    }
                ]
            });
            
            function setGaugeHeightByWidth(chart, minH=240, maxH=360){
                const cont = chart?.container?.parentElement;
                if (!cont) return;
                const w = cont.getBoundingClientRect().width || 320;
                const h = Math.max(minH, Math.min(maxH, Math.round(w * 0.9))); // 90% del ancho
                chart.update({ chart: { height: h } }, false);
                chart.reflow();
            }

            // Ajusta altura en proporción al ancho
            setGaugeHeightByWidth(gChart);

            // Reflow en el siguiente frame
            requestAnimationFrame(() => { try { gChart.reflow(); } catch(e){} });

            // === NUEVO: auto stack al cargar
            autoStackGaugeLayout();

            // === NUEVO: Observers para responder a cambios reales de tamaño
            try {
            const wrapEl = document.getElementById('grafica_deptos_wrap') || mount.parentElement;
            const infoEl = document.getElementById('grafica_deptos_info');

            const onResize = () => {
                autoStackGaugeLayout();
                setGaugeHeightByWidth(gChart);
                try { gChart.reflow(); } catch(e){}
            };

            if ('ResizeObserver' in window) {
                const ro = new ResizeObserver(onResize);
                if (wrapEl) ro.observe(wrapEl);
                if (infoEl) ro.observe(infoEl);

                // Limpieza
                const _destroy = gChart.destroy;
                gChart.destroy = function(){
                try { ro.disconnect(); } catch(e){}
                _destroy.apply(this, arguments);
                };
            } else {
                // Fallback
                window.addEventListener('resize', onResize, { passive: true });
            }
            } catch(e) {}


            // Muestra por defecto el primero (si quieres otro, cambia a 1 o 2)
            updateSideInfo(0);

        } catch (e) {
            console.error('Error renderDeptSolidGauge', e);
            mount.innerHTML = prevHTML;
        }
    }

    // ===== Render helpers: crean el shell y luego pintan el treemap en #chartInner =====
    function renderShell({ breadcrumbHTML = "", minHeight = 680 } = {}) {
        container.innerHTML = `
      ${breadcrumbHTML}
      <div id="chartInner" class="treemap-neon" style="min-height: ${minHeight}px;"></div>

      <div id="auditPagination" class="mt-2"></div>
    `;
    }

    // ====== NUEVO: activar skin e interacción del fondo
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

    // ===== Vistas =====
    function viewDirecciones() {
        const aTotal = sumReal(deptsByDir.dirA);
        const bTotal = sumReal(deptsByDir.dirB);

        const nodes = [
            {
                id: "dirA",
                name: dirNames.dirA,
                value: Math.max(1, aTotal),
                real: aTotal,
                color: dirColors.dirA,
            },
            {
                id: "dirB",
                name: dirNames.dirB,
                value: Math.max(1, bTotal),
                real: bTotal,
                color: dirColors.dirB,
            },
        ];

        renderShell();
        drawTreemapIn(
            "chartInner",
            "Auditorías por Dirección",
            "Selecciona una dirección",
            nodes,
            (point) => viewDepartamentos(point.id),

            {
                layoutAlgorithm: "sliceAndDice",
                layoutStartingDirection: "vertical",
                height: 280,
                pointPadding: 6,
                nodePadding: 4,
                labelClamp: 2,
            },
        );

        // Limpia la card de la gráfica
        const g = document.getElementById('grafica_deptos');
        if (g) g.innerHTML = '<div class="text-muted small">Selecciona una Dirección para ver el resumen</div>';
    }

    function viewDepartamentos(dirKey) {
        const dirName = dirNames[dirKey];
        const depts = deptsByDir[dirKey] || [];

        const bcHTML = `
            <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" data-bc="root">Direcciones</a></li>
                <li class="breadcrumb-item active" aria-current="page">${esc(dirName)}</li>
            </ol>
            </nav>
        `;

        // 1) Renderiza el shell del treemap (esto NO afecta la card de #grafica_deptos)
        renderShell({ breadcrumbHTML: bcHTML });
        // 2)Tarjeta de encargados
        resetAvanceCard();
        setEncargadosDireccion(dirKey);
        // 3)pinta gauge multi–anillo en tu card del index
        renderDeptSolidGauge(dirKey, 'grafica_deptos');

        // 4) BreadCrumb
        container.querySelector("nav").addEventListener("click", (e) => {
            const a = e.target.closest("a[data-bc]");
            if (!a) return;
            e.preventDefault();
            if (a.dataset.bc === "root") return viewDirecciones();
        });

        // 5) Treemap de departamentos (igual que ya lo tenías)
        const nodes = depts.map((d) => ({
            id: d.id,
            name: d.name,
            value: Math.max(1, d.real || 0),
            real: d.real || 0,
            deptId: d.deptId,
            dirId: d.dirId,
            dirKey: dirKey
        }));

        drawTreemapIn(
            "chartInner",
            dirName,
            "Selecciona un departamento",
            nodes,
            (point) => viewAuditorias(point.dirKey, point.deptId, dirName, point.name),
            { layoutAlgorithm: "squarified", height: 360, pointPadding: 6, nodePadding: 4, labelClamp: 2 }
        );
    }

    function viewAuditorias(dirKey, deptId, dirName, deptName) {
        const bcHTML = `
      <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="#" data-bc="root">Direcciones</a></li>
          <li class="breadcrumb-item"><a href="#" data-bc="dir" data-dir="${esc(dirKey)}">${esc(dirName)}</a></li>
          <li class="breadcrumb-item active" aria-current="page">${esc(deptName)}</li>
        </ol>
        <hr class="fancy-border">
      </nav>
    `;
        renderShell({ breadcrumbHTML: bcHTML });
        resetAvanceCard();
        // Mantén Dirección/Director y ahora añade Departamento/Jefe
        setEncargadosDireccion(dirKey);
        setEncargadosDepartamento(deptId, deptName);

        container.querySelector("nav").addEventListener("click", (e) => {
            const a = e.target.closest("a[data-bc]");
            if (!a) return;
            e.preventDefault();
            if (a.dataset.bc === "root") {
                try {
                    sessionStorage.removeItem("auditoriaViewState");
                } catch (e) { }
                return viewDirecciones();
            }
            if (a.dataset.bc === "dir") return viewDepartamentos(a.dataset.dir);
        });

        const items = auditoriasMap[String(deptId)] || [];
        const originalItems = items.slice(); // fuente inmutable
        let activeItems = items.slice(); // dataset activo para la vista
        const detalleUrl =
            detalleBaseUrl ||
            (window.REPORT_DATA && window.REPORT_DATA.detalleBaseUrl) ||
            "";

        const perPage = 12;
        //const totalPages = Math.max(1, Math.ceil(items.length / perPage));
        let totalPages = Math.max(1, Math.ceil(activeItems.length / perPage));
        let currentPage = 1;

        try {
            const s = JSON.parse(
                sessionStorage.getItem("auditoriaViewState") || "null",
            );
            if (
                s &&
                s.userId === currentUserId &&
                s.pathname === window.location.pathname &&
                String(s.deptId) === String(deptId) &&
                s.page
            ) {
                currentPage = Math.min(
                    Math.max(1, parseInt(s.page, 10) || 1),
                    totalPages,
                );
            } else {
                try {
                    sessionStorage.removeItem("auditoriaViewState");
                } catch (e) { }
            }
        } catch (e) { }

        function saveViewState(page) {
            try {
                sessionStorage.setItem(
                    "auditoriaViewState",
                    JSON.stringify({
                        dirKey,
                        deptId,
                        dirName,
                        deptName,
                        page,
                        pathname: window.location.pathname,
                        userId: currentUserId,
                    }),
                );
            } catch (e) { }
        }
        // === Mostrar y cablear filtros SOLO en esta vista ===
        toggleAuditFilters(true);
        wireUpFilters();
        function wireUpFilters() {
            const form = document.getElementById("auditFiltersForm");
            const btnClear = document.getElementById("f_limpiar");
            if (!form) return;
            if (form.dataset.wired === "1") return; // evita listeners duplicados
            form.dataset.wired = "1";

            form.addEventListener("submit", (ev) => {
                ev.preventDefault();
                const f = readFilters();
                // si no hay criterios → restaurar
                if (!f.numero && !f.entidad && !f.acto) {
                    activeItems = originalItems.slice();
                    totalPages = Math.max(
                        1,
                        Math.ceil(activeItems.length / perPage),
                    );
                    highlightId = null;
                    renderPage(1);
                    return;
                }

                // calcula coincidencias sobre TODOS los items
                const matches = originalItems.filter(
                    (a) =>
                        ciIncludes(a.numero_auditoria, f.numero) &&
                        (ciIncludes(a.entidad_fiscalizable, f.entidad) ||
                            ciIncludes(a.nombre_entidad, f.entidad)) &&
                        ciIncludes(a.acto_fiscalizacion, f.acto),
                );

                if (f.solo) {
                    // Mostrar solo coincidencias (filtrado)
                    activeItems = matches;
                    totalPages = Math.max(
                        1,
                        Math.ceil(activeItems.length / perPage),
                    );
                    highlightId = matches.length ? "aud" + matches[0].id : null;
                    renderPage(1, highlightId);
                } else {
                    // No filtrar dataset; ir a la página donde esté la primera coincidencia y resaltarla
                    if (!matches.length) {
                        // sin coincidencias → no mover dataset
                        highlightId = null;
                        renderPage(currentPage);
                        // opcional: mostrar un aviso sutil
                        console.info("Sin coincidencias con los filtros.");
                        return;
                    }
                    const firstId = matches[0].id;
                    const idx = originalItems.findIndex(
                        (x) => x.id === firstId,
                    );
                    const page = Math.max(1, Math.floor(idx / perPage) + 1);
                    activeItems = originalItems.slice();
                    totalPages = Math.max(
                        1,
                        Math.ceil(activeItems.length / perPage),
                    );
                    highlightId = "aud" + firstId;
                    renderPage(page, highlightId);
                }
            });
            btnClear?.addEventListener("click", () => {
                // limpia inputs
                form.reset();
                document.getElementById("f_solo") &&
                    (document.getElementById("f_solo").checked = false);
                activeItems = originalItems.slice();
                totalPages = Math.max(
                    1,
                    Math.ceil(activeItems.length / perPage),
                );
                highlightId = null;
                renderPage(1);
            });
        }

        let highlightId = null;
        function renderPage(page, forceHighlightId) {
            currentPage = page || 1;
            saveViewState(currentPage);
            const start = (currentPage - 1) * perPage;
            //const pageItems = items.slice(start, start + perPage);
            const pageItems = activeItems.slice(start, start + perPage);
            if (forceHighlightId) highlightId = forceHighlightId;

            let nodes;
            if (!pageItems.length) {
                nodes = [
                    {
                        id: "no-data",
                        name: "Sin auditorías",
                        value: 1,
                        color: "#f8d7da",
                    },
                ];
            } else {
                nodes = pageItems.map((a) => ({
                    id: "aud" + a.id,
                    numero_auditoria: a.numero_auditoria,
                    entidad_fiscalizable: a.entidad_fiscalizable,
                    nombre_entidad: a.nombre_entidad,
                    acto_fiscalizacion: a.acto_fiscalizacion,
                    name: a.numero_auditoria
                        ? a.numero_auditoria
                        : "AUD-" + a.id,
                    value: 1,
                    className:
                        highlightId === "aud" + a.id ? "is-highlight" : "", // ← clase para CSS
                    //color: '#c6ad8a'
                }));
            }

            drawTreemapIn(
                "chartInner",
                `${dirName} / ${deptName}`,
                //items.length ? 'Auditorías' : 'No hay auditorías asignadas para este departamento',
                activeItems.length ? "Auditorías" : "Sin resultados",
                nodes,
                (point) => {
                    if (
                        !point ||
                        !point.id ||
                        String(point.id).startsWith("no-data")
                    )
                        return;
                    const audId = String(point.id).replace(/^aud/, "");
                    if (!audId) return;
                    try {
                        showLoading();
                    } catch (e) { }
                    fetch(`${detalleUrl}/${audId}`, {
                        credentials: "same-origin",
                    })
                        .then((r) => r.json())
                        .then((aud) => {
                            hideLoading();
                            renderAuditDetails(aud, dirName, deptName);
                        })
                        .catch((err) => {
                            hideLoading();
                            console.error("Error cargando auditoría", err);
                        });
                },
                {
                    layoutAlgorithm: "strip",
                    height: 800,
                    minPointSize: 8,
                    labelFontSize: "16px",
                    pointPadding: 4,
                    nodePadding: 2,
                    labelClamp: 3,
                },
            );

            // pagination UI
            const pager = document.getElementById("auditPagination");
            if (!pager) return;
            pager.innerHTML = "";

            if (totalPages > 1) {
                let html =
                    '<nav aria-label="Paginación auditorías"><ul class="pagination pagination-sm">';
                for (let p = 1; p <= totalPages; p++) {
                    html += `<li class="page-item ${p === currentPage ? "active" : ""}"><a href="#" class="page-link" data-page="${p}">${p}</a></li>`;
                }
                html += "</ul></nav>";
                pager.innerHTML = html;
                pager.querySelectorAll(".page-link").forEach((el) =>
                    el.addEventListener("click", (ev) => {
                        ev.preventDefault();
                        const p = parseInt(el.dataset.page, 10) || 1;
                        renderPage(p);
                    }),
                );
            }
        }
        renderPage(currentPage);
    }

    // Renderiza el panel de detalles para una auditoría cuando las seleccionas
    function renderAuditDetails(aud, dirName, deptName) {
        console.log(aud.numero_auditoria);
        const panel = document.getElementById("auditoriasPanel");
        if (panel) panel.classList.remove("d-none");
        const pd = document.getElementById("panelDir"); if (pd) pd.textContent = dirName;
        const pdept = document.getElementById("panelDept"); if (pdept) pdept.textContent = deptName;

        // === % Avance: calcula y pinta ===
        try {
            const av = computeAuditProgress(aud);
            setAuditoriaSeleccionada(aud.id, aud.numero_auditoria);
            // Texto
            const $txt = document.getElementById('avanceAuditoria');
            if ($txt) {
                // Si prefieres 'N/D' cuando no hay nada que contar:
                // $txt.textContent = av.total ? `${av.percent}% (${av.done}/${av.total})` : 'N/D';
                $txt.textContent = `${av.percent}% (${av.done}/${av.total})`;
            }

            // Barra
            const $bar = document.getElementById('avanceBar');
            if ($bar) {
                $bar.style.width = av.percent + '%';
                $bar.setAttribute('aria-valuenow', String(av.percent));
                $bar.classList.remove('bg-success', 'bg-warning', 'bg-danger');
                $bar.classList.add(av.percent === 100 ? 'bg-success' : (av.percent >= 10 ? 'bg-warning' : 'bg-danger'));
            }

            // Tooltip (aplicamos tanto al número como a la barra)
            applyProgressTooltip($txt, av);
            applyProgressTooltip($bar, av);

        } catch (e) { /* no bloquear UI si algo llega raro */ }

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
        </div>
      `;
        }

        function statusFor(node) {
            if (!node) return "status-absent";
            if (Array.isArray(node)) {
                if (node.length === 0) return "status-absent";
                for (let i = 0; i < node.length; i++) {
                    const it = node[i];
                    if (
                        it &&
                        String(it.fase_autorizacion ?? "").trim() ===
                        "Autorizado"
                    )
                        return "status-autorizado";
                }
                return "status-pending";
            }
            const phase = node.fase_autorizacion
                ? String(node.fase_autorizacion).trim()
                : null;
            return phase
                ? phase === "Autorizado"
                    ? "status-autorizado"
                    : "status-pending"
                : "status-absent";
        }

        const radStatus = statusFor(aud.radicacion);
        const radBody = aud.radicacion
            ? `
      <div class="audit-field"><b>Número de expediente:</b> ${esc(aud.radicacion.numero_expediente ?? "-")}</div>
      <div class="audit-field"><b>Oficio de notificación de acuerdos:</b> ${esc(aud.radicacion.oficio_acuerdo ?? "")}</div>
      <div class="audit-field"><b>Estatus de la Auditoría:</b> ${phaseBadgeHtml(esc(aud.radicacion.fase_autorizacion ?? "Pendiente"))}</div>
    `
            : "<div>No registrada</div>";
        const radHtml = `
      <div class="d-flex align-items-start gap-3">
        <div class="status-breath ${radStatus}" aria-hidden="true"></div>
        <div>${radBody}</div>
      </div>
    `;
        grid.insertAdjacentHTML("beforeend", cardHTML("Radicación", radHtml));

        const compStatus = statusFor(aud.comparecencia);
        const compBody = aud.comparecencia
            ? `
      <div class="audit-field"><b>Acta de comparecencia:</b><span class="field-value"> ${esc(aud.comparecencia.numero_acta ?? "-")}</span></div>
      <div class="audit-field"><b>Estatus:</b><span class="field-value"> ${phaseBadgeHtml(esc(aud.comparecencia.fase_autorizacion ?? "Pendiente"))}</span></div>
    `
            : "<div>No registrada</div>";
        const compHtml = `<div class="d-flex align-items-start gap-3"><div class="status-breath ${compStatus}" aria-hidden="true"></div><div>${compBody}</div></div>`;
        grid.insertAdjacentHTML(
            "beforeend",
            cardHTML("Comparecencia", compHtml),
        );

        grid.insertAdjacentHTML(
            "beforeend",
            `
      <div class="col-12 mb-4">
        <hr class="fancy-border">
        <div class="my-5"><strong>Primer análisis</strong></div>
        <hr class="fancy-border">
      </div>
    `,
        );

        const acuerdosList = [];
        if (aud.AC) {
            if (Array.isArray(aud.AC)) acuerdosList.push(...aud.AC);
            else acuerdosList.push(aud.AC);
        }
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
            cardHTML(
                "Acuerdos de conclusión",
                `<div class="d-flex align-items-start gap-3"><div class="status-breath ${acuerdosStatus}" aria-hidden="true"></div><div>${acuerdosHtml}</div></div>`,
            ),
        );

        function phaseBadgeHtml(phase) {
            if (!phase) phase = "Pendiente";
            const p = String(phase).trim();
            if (p === "Autorizado")
                return `<p class="text-success">${esc(p)}</p>`;
            if (p === "Rechazado")
                return `<p class="text-danger">${esc(p)}</p>`;
            return `<p class="text-warning">${esc(p)}</p>`;
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
                        if (
                            r &&
                            String(r.fase_autorizacion ?? "").trim() ===
                            "Autorizado"
                        )
                            totalAuthorized++;
                    }
                } else {
                    totalRelations++;
                    if (
                        rel &&
                        String(rel.fase_autorizacion ?? "").trim() ===
                        "Autorizado"
                    )
                        totalAuthorized++;
                }
            }
            if (totalRelations === 0) return "status-absent";
            return totalAuthorized === totalRelations
                ? "status-autorizado"
                : "status-pending";
        }

        function renderActionList(actions, relKey, relLabel) {
            if (!actions || !actions.length)
                return "<div>No hay " + relLabel + "</div>";
            let html = `<div class="audit-field"><b>Total ${relLabel}:</b><span class="field-value"> ${actions.length}</span></div><div class="mt-1">`;
            actions.forEach((act) => {
                const numero = esc(
                    act.numero ?? act.consecutivo ?? "#" + (act.id ?? ""),
                );
                const rel = act[relKey];
                if (rel) {
                    if (Array.isArray(rel)) {
                        const parts = rel.map(
                            (r) =>
                                `<div class="audit-field"><b>Estatus:</b><span class="field-value">${phaseBadgeHtml(r.fase_autorizacion ?? "Pendiente")}</span></div>`,
                        );
                        html += `<div class="small mb-1 audit-field "><b>No. de acción:</b>${numero}<div class="small text-muted">${parts.join("<br/>")}</div></div>`;
                    } else if (typeof rel === "object") {
                        html += `<div class=" small mb-1 audit-field"><b>No. de acción:</b>${numero}</div><div class="audit-field"><b>Estatus:</b>${phaseBadgeHtml(rel.fase_autorizacion ?? "Pendiente")}</span></div><br>`;
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

        const pras = aud.accionespras || [];
        const prasStatus = getGroupStatus(pras, "pras");
        const prasHtml = renderActionList(pras, "pras", "PRAS");
        grid.insertAdjacentHTML(
            "beforeend",
            cardHTML(
                "PRAS",
                `<div class="d-flex align-items-start gap-3"><div class="status-breath ${prasStatus}" aria-hidden="true"></div><div>${prasHtml}</div></div>`,
            ),
        );

        const recs = aud.accionesrecomendaciones || [];
        const recsStatus = getGroupStatus(recs, "recomendaciones");
        const recsHtml = renderActionList(
            recs,
            "recomendaciones",
            "recomendaciones",
        );
        grid.insertAdjacentHTML(
            "beforeend",
            cardHTML(
                "Recomendaciones",
                `<div class="d-flex align-items-start gap-3"><div class="status-breath ${recsStatus}" aria-hidden="true"></div><div>${recsHtml}</div></div>`,
            ),
        );

        const pos = aud.accionespo || [];
        const posStatus = getGroupStatus(pos, "pliegosobservacion");
        const posHtml = renderActionList(
            pos,
            "pliegosobservacion",
            "pliegos de observación",
        );
        grid.insertAdjacentHTML(
            "beforeend",
            cardHTML(
                "Pliegos de observación",
                `<div class="d-flex align-items-start gap-3"><div class="status-breath ${posStatus}" aria-hidden="true"></div><div>${posHtml}</div></div>`,
            ),
        );

        const sol = aud.accionessolacl || [];
        const solStatus = getGroupStatus(sol, "solicitudesaclaracion");
        const solHtml = renderActionList(
            sol,
            "solicitudesaclaracion",
            "solicitudes",
        );
        grid.insertAdjacentHTML(
            "beforeend",
            cardHTML(
                "Solicitudes de aclaración",
                `<div class="d-flex align-items-start gap-3"><div class="status-breath ${solStatus}" aria-hidden="true"></div><div>${solHtml}</div></div>`,
            ),
        );

        const informesList = [];
        if (aud.informes) {
            if (Array.isArray(aud.informes)) informesList.push(...aud.informes);
            else informesList.push(aud.informes);
        }
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
                const phases = [
                    ...new Set(
                        node.map((n) =>
                            n && n.fase_autorizacion
                                ? String(n.fase_autorizacion).trim()
                                : "Pendiente",
                        ),
                    ),
                ];
                return phases.length === 1 ? phases[0] : phases.join(", ");
            }
            return node.fase_autorizacion
                ? String(node.fase_autorizacion).trim()
                : "Pendiente";
        }

        const informesStatus = statusFor(informesList);
        let informesHtml = "";
        if (informesList.length) {
            informesHtml += `<div class="audit-field"><b>Total Informes:</b><span class="field-value"> ${aud.informes.length}</span></div>`;
            if (isrecCount)
                informesHtml += `<div class="audit-field"><b>Recomendaciones:</b><span class="field-value"> ${isrecCount}</span></div><div class="audit-field"><b>Estatus:</b><span class="field-value"> ${phaseBadgeHtml(phaseLabel(aud.informeprimeraetapa))}</span></div>`;
            if (ispliegosCount)
                informesHtml += `<div class="audit-field"><b>Pliegos:</b><span class="field-value"> ${ispliegosCount}</span></div><div class="audit-field"><b>Estatus:</b><span class="field-value"> ${phaseBadgeHtml(phaseLabel(aud.informepliegos))}</span></div>`;
        } else {
            informesHtml = "<div>No hay informes registrados</div>";
        }
        grid.insertAdjacentHTML(
            "beforeend",
            cardHTML(
                "Informes",
                `<div class="d-flex align-items-start gap-3"><div class="status-breath ${informesStatus}" aria-hidden="true"></div><div>${informesHtml}</div></div>`,
            ),
        );

        grid.insertAdjacentHTML(
            "beforeend",
            `
            <div class="col-12 mb-4">
                <hr class="fancy-border">
                <div class="my-5"><strong>Turnos</strong></div>
                <hr class="fancy-border">
            </div>
            `,);

        const TurnoUIStatus = statusFor(aud.turnoui);
        const TurnoOICStatus = statusFor(aud.turnooic);
        const TurnoArchivoStatus = statusFor(aud.turnoarchivo);
        const TurnoUIBody = aud.turnoui
            ? `
      <div class="audit-field"><b>Número de expediente:</b> ${esc(aud.turnoui.numero_turno_ui ?? "-")}</div>
      <div class="audit-field"><b>Estatus del turno:</b> ${phaseBadgeHtml(esc(aud.turnoui.fase_autorizacion ?? "Pendiente"))}</div>
    `
            : "<div>No registrada</div>";
        const TurnoUIHtml = `
      <div class="d-flex align-items-start gap-3">
        <div class="status-breath ${TurnoUIStatus}" aria-hidden="true"></div>
        <div>${TurnoUIBody}</div>
      </div>
    `;
        grid.insertAdjacentHTML("beforeend", cardHTML("Turno UI", TurnoUIHtml));

        const TurnoOICBody = aud.turnooic
            ? `
      <div class="audit-field"><b>Número de expediente:</b> ${esc(aud.turnooic.numero_turno_oic ?? "-")}</div>
      <div class="audit-field"><b>Estatus del turno:</b> ${phaseBadgeHtml(esc(aud.turnooic.fase_autorizacion ?? "Pendiente"))}</div>
    `
            : "<div>No registrada</div>";
        const TurnoOICHtml = `
      <div class="d-flex align-items-start gap-3">
        <div class="status-breath ${TurnoOICStatus}" aria-hidden="true"></div>
        <div>${TurnoOICBody}</div>
      </div>
    `;
        grid.insertAdjacentHTML(
            "beforeend",
            cardHTML("Turno OIC", TurnoOICHtml),
        );

        const TurnoArchivoBody = aud.turnoarchivo
            ? `
      <div class="audit-field"><b>Número de expediente:</b> ${esc(aud.turnoarchivo.numero_turno_archivo ?? "-")}</div>
      <div class="audit-field"><b>Estatus del turno:</b> ${phaseBadgeHtml(esc(aud.turnoarchivo.fase_autorizacion ?? "Pendiente"))}</div>
    `
            : "<div>No registrada</div>";
        const TurnoArchivoHtml = `
      <div class="d-flex align-items-start gap-3">
        <div class="status-breath ${TurnoArchivoStatus}" aria-hidden="true"></div>
        <div>${TurnoArchivoBody}</div>
      </div>
    `;
        grid.insertAdjacentHTML(
            "beforeend",
            cardHTML("Turno Archivo", TurnoArchivoHtml),
        );

        const btnOcultar = document.getElementById("btnOcultarPanel");
        if (btnOcultar)
            btnOcultar.addEventListener("click", () => {
                if (panel) panel.classList.add("d-none");
                resetAvanceCard();
            });
        try {
            panel.scrollIntoView({ behavior: "smooth", block: "start" });
        } catch (e) { }
    }
    // ===== Inicial =====
    injectNeonSkinStyles();
})();
