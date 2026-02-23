@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('reportesauditoriaunidad.index') }}
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

@endsection

