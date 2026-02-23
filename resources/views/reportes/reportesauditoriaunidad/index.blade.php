@extends('layouts.app')

@section('breadcrums')
    {{Breadcrumbs::render('reporteauditoriaunidad.index') }}
@endsection

@section('content')
<style>
    tr:hover {
        background-color: #CAD5E2 !important;
    }

    #container {
        min-height: 620px;
    }

    .highcharts-breadcrumbs text {
        font-size: 13px !important;
    }

    .highcharts-data-label text {
        cursor: pointer;
    }

    .highcharts-point:hover {
        opacity: 0.9;
    }

    .badge {
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 6px;
    }

    /* Estilos para la cuadrícula de auditorías */
    #auditoriasPanel .card {
        border: 1px linear-gradient(135deg, #ffffff 0%, #fbfbfe 100%)
    }

    #auditoriasPanel .audit-card-title {
        font-weight: 600;
        font-size: .95rem;
        line-height: 1.2;
        min-height: 2.1em;
    }

    #auditoriasPanel .audit-meta {
        font-size: .8rem;
        color: #6b7280;
    }

    @keyframes breath {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.45);
            opacity: 0.9;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .audit-card {
        border-radius: 10px;
        overflow: hidden;
        border: 0;
        background: linear-gradient(135deg, #ffffff 0%, #fbfbfe 100%);
        box-shadow: 0 6px 18px rgba(16, 24, 40, 0.06);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .audit-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
        background: linear-gradient(180deg, #960048, #BB945C);
        border-top-left-radius: 18px;
        border-bottom-left-radius: 18px;
    }

    .audit-card-title {
        font-weight: 600;
        font-size: 1rem;
        color: #111827;
    }

    .audit-meta {
        font-size: 0.95rem;
        color: #374151;
        line-height: 1.45;
    }

    .audit-meta .small {
        font-size: 0.95rem;
        color: #6b7280;
    }

    .audit-meta .badge {
        font-size: 0.95rem;
        padding: .36rem .6rem;
        border-radius: .375rem;
    }

    .audit-meta .audit-field {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
        display: flex;
        align-items: baseline;
        gap: 8px;
    }

    .audit-meta .audit-field .field-value {
        font-weight: 400;
        color: #374151;
        margin-left: 0;
    }

    .audit-meta b {
        color: #111827;
        font-weight: 600;
    }

    .status-breath {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.06);
        flex: 0 0 auto;
        margin-top: 4px;
    }

    .status-autorizado {
        background: #10b981;
        box-shadow: 0 0 12px rgba(16, 185, 129, 0.28);
    }

    .status-pending {
        background: #f59e0b;
        box-shadow: 0 0 12px rgba(245, 158, 11, 0.18);
    }

    .status-absent {
        background: #ef4444;
        box-shadow: 0 0 12px rgba(239, 68, 68, 0.12);
    }

    @keyframes card-breath {
        0% {
            transform: translateY(0)
        }

        50% {
            transform: translateY(-2px)
        }

        100% {
            transform: translateY(0)
        }
    }


    /* ====== Ajustes generales para que NUNCA se salga el texto ====== */
    #auditoriasPanel .audit-meta,
    #auditoriasPanel .audit-meta .audit-field,
    #auditoriasPanel .audit-meta .audit-field .field-value,
    #auditoriasPanel .audit-card-title,
    #auditoriasPanel .audit-meta .small,
    #auditoriasPanel .audit-meta .badge {
        overflow-wrap: anywhere;
        /* permite cortar palabras muy largas */
        word-break: break-word;
        /* fuerza quiebre si hace falta */
        hyphens: auto;
        /* añade guion si el idioma lo permite */
    }

    #auditoriasPanel .audit-meta .audit-field {
        align-items: flex-start;
        /* por si hay 2 líneas en el valor */
    }

    /* Evita márgenes verticales innecesarios dentro de las cards */
    #auditoriasPanel .audit-meta p {
        margin: 0;
        /* phaseBadgeHtml usará <span>, pero por si quedó <p> */
    }

    /* El “semáforo” (bolita) se hace un poco más pequeño en móvil */
    @media (max-width: 576px) {
        #auditoriasPanel .audit-card {
            border-radius: 10px;
        }

        #auditoriasPanel .audit-card .card-body {
            padding: 12px 12px;
            /* menos padding para ganar espacio */
        }

        #auditoriasPanel .audit-card-title {
            font-size: 0.95rem;
            /* ~15.2px */
            line-height: 1.25;
            margin-bottom: .5rem;
        }

        /* Texto principal dentro de la card */
        #auditoriasPanel .audit-meta {
            font-size: .78rem;
            /* ~12.5px */
            line-height: 1.35;
        }

        #auditoriasPanel .audit-meta .audit-field {
            font-size: .78rem;
            /* ~12.5px */
            margin-bottom: 6px;
            gap: 6px;
        }

        #auditoriasPanel .audit-meta .audit-field .field-value {
            font-size: .78rem;
        }

        #auditoriasPanel .audit-meta .badge,
        #auditoriasPanel .audit-meta .small {
            font-size: .72rem;
            /* ~11.5px */
        }

        /* Reducir el “gap” horizontal del bloque con el semáforo + texto */
        #auditoriasPanel .d-flex.align-items-start.gap-3 {
            gap: 8px !important;
            /* era ~1rem; bajamos a 8px */
        }

        /* Semáforo más chico */
        #auditoriasPanel .status-breath {
            width: 10px;
            height: 10px;
            margin-top: 2px;
        }

        /* Que los títulos y números largos nunca se desborden */
        #auditoriasPanel .audit-meta b,
        #auditoriasPanel .audit-meta .field-value,
        #auditoriasPanel .audit-meta .text-muted {
            overflow-wrap: anywhere;
            word-break: break-word;
        }
    }

    /* Tablet (por si quieres ajuste intermedio) */
    @media (min-width: 577px) and (max-width: 768px) {
        #auditoriasPanel .audit-card .card-body {
            padding: 14px 14px;
        }

        #auditoriasPanel .audit-card-title {
            font-size: 1rem;
        }

        #auditoriasPanel .audit-meta,
        #auditoriasPanel .audit-meta .audit-field {
            font-size: .85rem;
        }

        #auditoriasPanel .audit-meta .badge,
        #auditoriasPanel .audit-meta .small {
            font-size: .78rem;
        }
    }

    .fancy-border {
        border: 2px solid #BB945C;
        box-shadow: 1px 1px 10px 1px rgba(239, 193, 139, .95);
    }

    .tooltip .tooltip-inner {
        max-width: 420px;
        text-align: left;
        font-size: 14PX;
    }

    /* --- Swapping squares spinner (loader chico de la card) --- */
    .swapping-squares-spinner,
    .swapping-squares-spinner * {
        box-sizing: border-box;
    }

    .swapping-squares-spinner {
        height: 65px;
        width: 65px;
        position: relative;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        margin: 8px auto;
    }

    .swapping-squares-spinner .square {
        height: calc(65px * 0.25 / 1.3);
        width: calc(65px * 0.25 / 1.3);
        animation-duration: 1000ms;
        /* Si quieres tu dorado en vez de rosa, cambia #ff1d5e por #BB945C */
        border: calc(65px * 0.04 / 1.3) solid #BB945C;
        margin-right: auto;
        margin-left: auto;
        position: absolute;
        animation-iteration-count: infinite;
    }

    .swapping-squares-spinner .square:nth-child(1) {
        animation-name: swapping-squares-animation-child-1;
        animation-delay: 500ms;
    }

    .swapping-squares-spinner .square:nth-child(2) {
        animation-name: swapping-squares-animation-child-2;
        animation-delay: 0ms;
    }

    .swapping-squares-spinner .square:nth-child(3) {
        animation-name: swapping-squares-animation-child-3;
        animation-delay: 500ms;
    }

    .swapping-squares-spinner .square:nth-child(4) {
        animation-name: swapping-squares-animation-child-4;
        animation-delay: 0ms;
    }

    @keyframes swapping-squares-animation-child-1 {
        50% {
            transform: translate(150%, 150%) scale(2, 2);
        }
    }

    @keyframes swapping-squares-animation-child-2 {
        50% {
            transform: translate(-150%, 150%) scale(2, 2);
        }
    }

    @keyframes swapping-squares-animation-child-3 {
        50% {
            transform: translate(-150%, -150%) scale(2, 2);
        }
    }

    @keyframes swapping-squares-animation-child-4 {
        50% {
            transform: translate(150%, -150%) scale(2, 2);
        }
    }

    /* === Layout del gauge + panel derecho (robusto y fluido) === */
    .dept-gauge-wrap{
    display:flex;
    align-items:stretch;
    gap:14px;
    min-height: 260px;                /* que nunca colapse en altura */
    }

    /* OJO: el item FLEX real es el <figure>, NO el #grafica_deptos. 
    Si <figure> colapsa a 0px, el SVG queda "en blanco". */
    .dept-gauge-wrap .highcharts-figure{
        flex: 0 1 360px;                  /* base ~360px, encoge si no cabe */
        min-width: 280px;                 /* nunca bajes de aquí */
        margin: 0;                        /* quita margen default del figure */
    }

    #grafica_deptos{
        min-width: 260px;
        min-height: 260px;                /* área mínima para que Highcharts pinte */
    }

    .dept-gauge-info{
        flex: 1 1 220px;                  /* el panel derecho ocupa el resto */
        min-width: 200px;                 /* ancho mínimo saludable */
        display:flex; flex-direction:column; justify-content:center;
        border-left: 1px solid rgba(187,148,92,.25);
        padding-left: 12px;
        color: #132a29;
    }

    /* Títulos y KPIs */
    .dept-gauge-info h6{ margin:0 0 6px 0; font-weight:700; }
    .dept-gauge-info .kpi{ display:flex; align-items:baseline; gap:8px; margin-bottom:6px; }
    .dept-gauge-info .kpi .big{ font-size: clamp(1.2rem, 2.2vw, 1.7rem); font-weight: 800; }
    .dept-gauge-info .muted{ color:#6b7280; }

    /* En pantallas medianas, dale más espacio al figure */
    @media (max-width: 1200px){
    .dept-gauge-wrap .highcharts-figure{ flex-basis: 320px; min-width: 260px; }
    }

    /* En tablet / móvil: apila (gauge arriba, panel abajo) */
    @media (max-width: 768px){
        .dept-gauge-wrap{ flex-direction: column; }
        .dept-gauge-info{
            border-left: 0; 
            border-top:1px solid rgba(187,148,92,.25);
            padding-top: 10px; 
            padding-left:0; 
        }
        .dept-gauge-wrap .highcharts-figure{
            flex-basis: auto; min-width: 100%;
        }
    }


    .dept-gauge-wrap.is-stacked{
    flex-direction: column;
    }
    .dept-gauge-wrap.is-stacked .dept-gauge-info{
    border-left: 0;
    border-top:1px solid rgba(187,148,92,.25);
    padding-top: 10px;
    padding-left:0;
    }
    .dept-gauge-wrap.is-stacked .highcharts-figure{
    flex-basis: auto; 
    min-width: 100%;
    }


</style>

<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title w-100">
                    <div class="row w-100">
                        <div class="col-md-11">
                            <a href="{{ route('home') }}"><i
                                    class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>&nbsp;
                            Reportes
                        </div>
                    </div>
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                <div class=" my-4">
                    <!-- Parent row with gutter spacing -->
                    <div class="row g-4">
                        <!-- TOTAL de auditorias-->
                        <div class="col-12 col-lg-12">
                            <div class="card h-60 shadow-sm">
                                <div class="card-body audit-card fancy-border">
                                    <span style="font-size: 24px"> <b>Total auditorías:</b>
                                        <b id="totalAuditorias">{{ $auditoriasPorDireccionA->count() + $auditoriasPorDireccionB->count() }}</b> 
                                        <b> - Cuenta pública {{ getSession('cp')}}</b> 
                                    </span> 
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-2">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body audit-card fancy-border" id="cardEncargadosA" style="font-size: 14px">
                                    <small class="d-block mb-1"><b>Encargados</b></small>
                                    <div id="encargadosDireccionA" class="mb-1"><b>Dirección:</b> <span id="enc_dirA">Dirección de Seguimiento "A"</span></div>
                                    <div id="encargadosDirectorA" class="mb-2"><b>Director:</b> <span id="enc_directorA">—</span></div>
                                    <div id="encargadosDeptoA" class="mb-1 d-none"><b>Departamento:</b> <span id="enc_deptoA">—</span></div>
                                    <div id="encargadosJefeA" class="mb-0 d-none"><b>Jefe:</b> <span id="enc_jefeA">—</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body audit-card fancy-border">
                                    <figure class="highcharts-figure">
                                        <div id="grafica_depto_auditoriasA"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <!-- 2) ENCARGADOS (ACTUALIZABLE POR JS) -->
                        <div class="col-12 col-lg-2">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body audit-card fancy-border" id="cardEncargadosB" style="font-size: 14px">
                                    <small class="d-block mb-1"><b>Encargados</b></small>
                                    <div id="encargadosDireccionB" class="mb-1"><b>Dirección:</b> <span id="enc_dirB">Dirección de Seguimiento "B"</span></div>
                                    <div id="encargadosDirectorB" class="mb-2"><b>Director:</b> <span id="enc_directorB">—</span></div>
                                    <div id="encargadosDeptoB" class="mb-1 d-none"><b>Departamento:</b> <span id="enc_deptoB">—</span></div>
                                    <div id="encargadosJefeB" class="mb-0 d-none"><b>Jefe:</b> <span id="enc_jefeB">—</span></div>
                                </div>
                            </div>
                        </div>
                        <!-- Grafica dinamica de departamentos -->
                        <div class="col-12 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body audit-card fancy-border">
                                    <div id="grafica_deptos_wrap" class="dept-gauge-wrap">
                                        <figure class="highcharts-figure mb-0">
                                            <div id="grafica_deptos"></div>
                                        </figure>
                                        <div id="grafica_deptos_info" class="dept-gauge-info">
                                        <!-- Aquí se mostrará la info dinámica al hover -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 3) % AVANCE (lo vemos al final) -->
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
                    </div>
                </div>
                <br><hr>

                <div id="auditFiltersCard" class=" d-none">
                    {!!BootForm::open(['route' => 'reporteauditoriaunidad.index', 'method' => 'GET', 'id' => 'auditFiltersForm']) !!}
                    <div class="row align-items-end">
                        <div class="col-md-2">
                            {!!BootForm::text('numero_auditoria', "No. auditoría:", old('numero_auditoria', $request->numero_auditoria), ['id' => 'f_numero_auditoria']) !!}
                        </div>
                        <div class="col-md-3">
                            {!!BootForm::text('entidad_fiscalizable', "Entidad fiscalizable:", old('entidad_fiscalizable', $request->entidad_fiscalizable), ['id' => 'f_entidad_fiscalizable']) !!}
                        </div>
                        <div class="col-md-3">
                            {!!BootForm::text('acto_fiscalizacion', "Acto de fiscalización:", old('acto_fiscalizacion', $request->acto_fiscalizacion), ['id' => 'f_acto_fiscalizacion']) !!}
                        </div>
                        <div class="col-md-1 mt-2">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                        <div class="col-md-1 mt-2">
                            <button type="button" id="f_limpiar" class="btn btn-secondary ">Limpiar</button>
                        </div>
                    </div>
                    {!!BootForm::close() !!}
                </div><br>
                <figure class="highcharts-figure mb-4 d-none" id="treemapFigure">
                    <div id="container"></div>
                </figure><br>
                <!-- Panel de cuadrícula de auditorías -->
                <div id="auditoriasPanel" class="d-none">
                    <div class="d-flex align-items-center justify-content-between mb-15">
                        <div>
                            <h5 class="mb-3">
                                <!-- Auditorías — <span id="panelDir"></span> / <span id="panelDept"></span> --->
                                Seguimiento Auditoria — <span id="panelDir"></span> / <span id="panelDept"></span>
                            </h5>
                            <small class="text-muted">Se muestran los datos mas relevantes de la auditoría seleccionada
                                (Para cambiar los datos seleccionar otra auditoría u seleccionar otro
                                departamento).</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" id="btnOcultarPanel">Limpiar</button>
                    </div>
                    <hr class="fancy-border">
                    <div id="auditoriasGrid" class="row g-3"></div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.highcharts.com/11.4.0/highcharts.js"></script>
<script src="https://code.highcharts.com/11.4.0/modules/treemap.js"></script>

<script src="https://code.highcharts.com/11.4.0/highcharts-more.js"></script>
<script src="https://code.highcharts.com/11.4.0/modules/solid-gauge.js"></script>
<script src="https://code.highcharts.com/11.4.0/modules/accessibility.js"></script>



<script>
    window.REPORT_DATA = {
        treemapData: @json($treemapData),
        auditoriasMap: @json($auditoriasGrid),
        currentUserId: @json(auth() -> id()),
        detalleBaseUrl: "{{ url('reportes/auditoria') }}",
        directoresPorDireccion: @json($directoresPorDireccion),
        jefesPorDepartamento: @json($jefesPorDepartamento),
        auditoriaSeleccionada: @json($auditoriaSeleccionada),
    };
</script>
<script
    src="{{ asset('js/reportesauditoriaunidad.js') }}?v={{ filemtime(public_path('js/reportesauditoriaunidad.js')) }}"></script>

@endsection