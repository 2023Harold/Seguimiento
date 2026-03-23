@extends('layouts.app')

@section('breadcrums')
	{{ Breadcrumbs::render('reportesregistrosauditorias.index', $auditorias) }}
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12 mt-2">
			<div class="card">
				<div class="card-header">
					<h1 class="card-title">
						<a href="{{ route('home') }}"><i
								class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>&nbsp;
						Reportes Registros de Auditorias
					</h1>
				</div>
				<div class="card-body">

					<div class="card">
						<div class="card-body text-center bg-light">

							<!-- ====== AUDITORÍAS (Spinner + número animado) ====== -->
							<div class="mb-4">
								<h5 class="card-title mb-3">
									<span id="auditorias-label">Auditorías</span>
								</h5>
								<div class="d-flex align-items-center justify-content-center" style="gap: 14px;">
									<div id="auditorias-spinner" class="spinner-border text-primary" role="status"
										style="width:2.5rem;height:2.5rem;">
										<span class="sr-only">Cargando...</span>
									</div>
									<div>
										<div id="auditorias-total" class="display-6 fw-bold">0</div>
										<div class="text-muted small">Total</div>
									</div>
								</div>
							</div>
							<hr />

							<!-- ====== FILA: Radicaciones / Comparecencias / Acuerdo de conclusión ====== -->
							<div class="row">
								<div class="col-md-6">
									<div class="card">
										<div class="card-body bg-secondary">
											<h5 class="card-title text-primary mb-2">Radicaciones</h5>
											<div id="chart-radicaciones" style="height: 220px;"></div>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="card">
										<div class="card-body bg-secondary">
											<h5 class="card-title text-primary mb-2">Comparecencias</h5>
											<div id="chart-comparecencias" style="height: 220px;"></div>
										</div>
									</div>
								</div>
							</div>
							<!-- ====== ACUERDOS DE CONCLUSIÓN - por tipo ====== -->
							<div class="row mt-3">
								<div class="col-md-12">
									<div class="card">
										<div class="card-body bg-secondary">
											<h5 class="card-title text-primary mb-3">
												Acuerdo de conclusión: <span
													class="badge bg-primary">{{ $acuerdoconclusion }}</span>
											</h5>
											<div class="row">
												<div class="col-md-6 mb-3">
													<div class=" bg-light rounded">
														<div class="fw-semibold mb-1">Recomendaciones</div>
														<div id="chart-acuerdo-rec" style="height: 240px;"></div>
													</div>
												</div>
												<div class="col-md-6 mb-3">
													<div class=" bg-light rounded">
														<div class="fw-semibold mb-1">Pliegos</div>
														<div id="chart-acuerdo-po" style="height: 240px;"></div>
													</div>
												</div>
											</div>
										</div> <!-- /card-body -->
									</div>
								</div>
							</div>


							<!-- ====== INFORMES DE AUDITORÍAS ====== -->
							<!-- ====== INFORMES DE AUDITORÍAS ====== -->
							<div class="row mt-3">
								<div class="col-md-12">
									<div class="card">
										<div class="card-body bg-secondary">
											<h5 class="card-title text-primary mb-3">
												Informes de Auditorías: <span
													class="badge bg-primary">{{ $informes }}</span>
											</h5>
											<div class="row">
												<div class="col-md-6 mb-3">
													<div class="p-2 bg-light rounded">
														<div class="fw-semibold mb-1">Recomendaciones</div>
														<div id="chart-informes-rec" style="height: 240px;"></div>
													</div>
												</div>
												<div class="col-md-6 mb-3">
													<div class="p-2 bg-light rounded">
														<div class="fw-semibold mb-1">Pliegos</div>
														<div id="chart-informes-po" style="height: 240px;"></div>
													</div>
												</div>
											</div>
										</div> <!-- /card-body -->
									</div>
								</div>
							</div>


							<!-- ====== FILA: Turnos UI / OIC / Archivo ====== -->
							<div class="row mt-3">
								<div class="col-md-4">
									<div class="card">
										<div class="card-body bg-secondary">
											<h5 class="card-title text-primary mb-2">Turnos UI</h5>
											<div id="chart-turnosui" style="height: 220px;"></div>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="card">
										<div class="card-body bg-secondary">
											<h5 class="card-title text-primary mb-2">Turnos OIC</h5>
											<div id="chart-turnooic" style="height: 220px;"></div>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="card">
										<div class="card-body bg-secondary">
											<h5 class="card-title text-primary mb-2">Turnos a Archivo</h5>
											<div id="chart-turnotat" style="height: 220px;"></div>
										</div>
									</div>
								</div>
							</div>

						</div> <!-- /card-body bg-light -->
					</div> <!-- /card -->

				</div>
			</div>
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

	@php
		$chartData = [
			'auditorias' => $auditorias,
			'radicaciones' => $radicaciones,
			'comparecencias' => $comparecencias,
			'acuerdoconclusion' => $acuerdoconclusion,
			'turnosui' => $turnosui,
			'turnooic' => $turnooic,
			'turnotat' => $turnotat,

			// Informes (por tipo)
			'informes' => $informes,
			'informesRec' => $informesRec,
			'informesPO' => $informesPO,

			// Acuerdos de conclusión (por tipo)
			'acuerdosRec' => $acuerdosRec,
			'acuerdosPO' => $acuerdosPO,
		];
	  @endphp

	<script>
		// Disponibiliza los datos
		window.ReporteAuditorias = @json($chartData);

		// ========= Helpers comunes =========
		function animateCounter(el, to, duration = 1200) {
			const start = 0; const diff = to - start; let t0 = null;
			const step = (ts) => {
				if (!t0) t0 = ts;
				const p = Math.min((ts - t0) / duration, 1);
				el.textContent = Math.floor(start + diff * p).toLocaleString('es-MX');
				if (p < 1) requestAnimationFrame(step);
			};
			requestAnimationFrame(step);
		}

		// Verifica que el contenedor exista
		function safeRender(id, drawFn) {
			const el = document.getElementById(id);
			if (!el) {
				console.warn('No existe el contenedor:', id);
				return false;
			}
			return drawFn(el);
		}

		// Espera a que el contenedor tenga tamaño (p.ej. si estaba oculto)
		function whenVisible(id, cb, tries = 20) {
			const el = document.getElementById(id);
			if (!el) return;
			const hasSize = el.offsetWidth > 0 && el.offsetHeight > 0;
			if (hasSize) cb();
			else if (tries > 0) setTimeout(() => whenVisible(id, cb, tries - 1), 150);
			else console.warn('Contenedor sin tamaño (no visible):', id);
		}

		// Render de gauge con validaciones
		function renderGauge(containerId, title, value, maxHint) {
			whenVisible(containerId, function () {
				safeRender(containerId, function () {
					const hasSG = Highcharts.seriesTypes && Highcharts.seriesTypes.solidgauge;
					if (!hasSG) {
						// Fallback simple a columna si por algún motivo no cargó el módulo
						Highcharts.chart(containerId, {
							chart: { type: 'column', backgroundColor: 'transparent' },
							title: { text: null },
							xAxis: { categories: [title] },
							yAxis: { min: 0, title: { text: null } },
							legend: { enabled: false },
							series: [{ name: 'Total', data: [value || 0], colorByPoint: true }],
							credits: { enabled: false }
						});
						return true;
					}

					const maxVal = Math.max(value || 0, maxHint || 10);
					Highcharts.chart(containerId, {
						chart: { type: 'solidgauge', backgroundColor: 'transparent' },
						title: null,
						tooltip: { enabled: false },
						pane: {
							center: ['50%', '60%'], size: '100%',
							startAngle: -90, endAngle: 90,
							background: { outerRadius: '100%', innerRadius: '60%', shape: 'arc' }
						},
						yAxis: {
							min: 0, max: maxVal, lineWidth: 0, tickAmount: 2,
							labels: { y: 16, style: { color: '#000' } },
							stops: [[0.2, '#DF5353'], [0.6, '#DDDF0D'], [0.9, '#55BF3B']]
						},
						plotOptions: {
							solidgauge: {
								dataLabels: {
									y: -10, borderWidth: 0, useHTML: true,
									format:
										'<div style="text-align:center">' +
										'<span style="font-size:22px;color:#000">{y}</span><br/>' +
										'<span style="font-size:12px;color:#000">Total</span>' +
										'</div>'
								}
							}
						},
						series: [{ name: title, data: [value || 0] }],
						credits: { enabled: false }
					});
					return true;
				});
			});
		}

		// ========= Inicialización (DOM listo) =========
		document.addEventListener('DOMContentLoaded', function () {
			const D = window.ReporteAuditorias || {
				auditorias: 0, radicaciones: 0, comparecencias: 0, acuerdoconclusion: 0,
				turnosui: 0, turnooic: 0, turnotat: 0,
				informes: 0, informesRec: 0, informesPO: 0,
				acuerdosRec: 0, acuerdosPO: 0
			};

			// Spinner + contador (Auditorías) — SE MANTIENE
			const spin = document.getElementById('auditorias-spinner');
			const cnt = document.getElementById('auditorias-total');
			setTimeout(() => {
				if (spin) spin.style.display = 'none';
				if (cnt) animateCounter(cnt, D.auditorias);
			}, 400);

			// Gauges (uno por contenedor)
			renderGauge('chart-radicaciones', 'Radicaciones', D.radicaciones);
			renderGauge('chart-comparecencias', 'Comparecencias', D.comparecencias);
			renderGauge('chart-acuerdo', 'Acuerdo de conclusión', D.acuerdoconclusion);

			// Informes por tipo
			renderGauge('chart-informes-rec', 'Recomendaciones', D.informesRec);
			renderGauge('chart-informes-po', 'Pliegos', D.informesPO);

			// Acuerdos por tipo
			renderGauge('chart-acuerdo-rec', 'Acuerdos: Recomendaciones', D.acuerdosRec);
			renderGauge('chart-acuerdo-po', 'Acuerdos: Pliegos', D.acuerdosPO);

			// Turnos
			renderGauge('chart-turnosui', 'Turnos UI', D.turnosui);
			renderGauge('chart-turnooic', 'Turnos OIC', D.turnooic);
			renderGauge('chart-turnotat', 'Turnos a Archivo', D.turnotat);
		});
	</script>
@endsection