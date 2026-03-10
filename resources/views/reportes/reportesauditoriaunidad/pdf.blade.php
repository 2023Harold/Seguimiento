<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reporte Auditorías</title>
  <style>
    /* ========= Paleta y tipografías ========= */
    html, body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 11px;
      color: #132a29;
      line-height: 1.35;
    }

    /* ========= Página y fondo ========= */
    body {
      background-color: #FAFAFB;
      margin: 0;
      padding: 0;
    }
    .page-wrap { padding: 10mm; }
    @page { margin: 10mm; }

    /* ========= Títulos y separadores ========= */
    h1, h2, h3, h4, h5 {
      margin: 0 0 6px 0;
      color: #111827;
    }
    h4 { font-size: 16px; }
    h5 { font-size: 13px; }
    .muted { color: #6b7280; }
    .hr { height: 2px; background: #BB945C; border: 0; margin: 8px 0 10px 0; }

    /* ========= Columnas (inline-block, Dompdf-friendly) ========= */
    .columns { font-size: 0; }
    .col {
      display: inline-block;
      vertical-align: top;
      width: 49%;
      font-size: 11px; /* restaurar tamaño */
    }

    /* ========= Cards ========= */
    .card {
      border: 1px solid #E5E7EB;
      border-radius: 8px;
      padding: 10px;
      background-color: #FFFFFF;
      margin-bottom: 8px;
      page-break-inside: avoid; break-inside: avoid;
    }
    .card-ribbon{ border-left: 6px solid #960048;
                  border-radius: 15px;
                  padding-left: 8px; 
                }
    .fancy-border { border: 2px solid #BB945C; }
    .section { margin-bottom: 10px; }
    .section-title {
      background: #FFFFFF;
      border: 1px solid #E5E7EB;
      border-left: 6px solid #BB945C;
      padding: 6px 8px;
      border-radius: 6px;
      font-weight: 700;
      margin-bottom: 6px;
    }
    .kpi {
      display: inline-block;
      padding: 3px 6px;
      border-radius: 4px;
      background: #F3F4F6;
      color: #111827;
      font-weight: 700;
      font-size: 10px;
    }

    /* ========= Badges & Dots ========= */
    .badge {
      display: inline-block;
      font-size: 10px;
      padding: 2px 6px;
      border-radius: 4px;
      border: 1px solid transparent;
      font-weight: 700;
    }
    .badge-ok   { background: #D1FAE5; color: #065F46; border-color: #A7F3D0; }
    .badge-warn { background: #FEF3C7; color: #92400E; border-color: #FDE68A; }
    .badge-bad  { background: #FEE2E2; color: #991B1B; border-color: #FCA5A5; }

    .dot {
      display: inline-block; width: 10px; height: 10px; border-radius: 50%;
      margin-right: 5px; border: 1px solid #E5E7EB;
    }
    .dot-ok   { background: #10b981; }
    .dot-warn { background: #f59e0b; }
    .dot-bad  { background: #ef4444; }

    /* ========= Tablas ========= */
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 8px;
      background: #ffffff;
      border: 1px solid #E5E7EB;
      page-break-inside: avoid; break-inside: avoid;
    }
    .table th, .table td {
      border: 1px solid #E5E7EB;
      padding: 6px;
      font-size: 10px;
      text-align: left;
      vertical-align: top;
    }
    .table thead th {
      background: #F3F4F6;
      color: #111827;
      font-weight: 700;
    }
    .table tbody tr:nth-child(2n) td { background: #FAFAFB; }

    /* ========= Imágenes (charts base64) ========= */
    .img-chart {
      width: 100%;
      max-height: 430px;
    }

    /* ========= Utilidades ========= */
    .mb-0 { margin-bottom: 0 !important; }
    .mb-4 { margin-bottom: 4px !important; }
    .mb-6 { margin-bottom: 6px !important; }
    .mb-8 { margin-bottom: 8px !important; }
    .mt-6 { margin-top: 6px !important; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    /* “Mini-card” dentro de cada celda de la rejilla de acciones */
  .mini {
    border: 1px solid #E5E7EB;
    border-radius: 6px;
    padding: 6px;
    background: #FFFFFF;
  }

  /* Tabla de rejilla con columnas fijas (Dompdf-friendly) */
  .grid-table { width: 100%; border-collapse: collapse; }
  .grid-table td {
    vertical-align: top;
    padding: 4px;
    /* sin border en celdas para que luzca la mini-card */
  }

  /* Opcional: título centrado de la card de rejilla */
  .grid-title {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-left: 6px solid #BB945C;
    padding: 6px 8px;
    border-radius: 6px;
    font-weight: 700;
    margin-bottom: 6px;
    text-align: left; /* cámbialo a center si lo prefieres centrado */
  }

  </style>
</head>

<body>
  <div class="page-wrap">
    <h4>Reporte de Auditorías</h4>
    <div class="muted mb-6">Generado: {{ now()->format('Y-m-d H:i') }}</div>
    <hr class="hr" />

    {{-- ===== Card TOTAL ===== --}}
    <div class="card fancy-border">
      <div class="card-ribbon">
        <div style="font-size:14px; font-weight:700;">
          Total auditorías: <span class="">{{ $totalAuditorias }}</span>
          — Cuenta pública {{ $cuentaPublica }}
        </div>
      </div>
    </div>

    {{-- ===== Encargados A/B ===== --}}
    <div class="columns">
      <div class="col">
        <div class="card fancy-border">
          <div class="card-ribbon">Encargados</div>
          <div><b>Dirección:</b> {{ $dirA ?? 'Dirección de Seguimiento "A"' }}</div>
          <div><b>Director:</b> {{ $directorA ?? '—' }}</div>
        </div>
      </div>
      <div class="col">
        <div class="card fancy-border">
          <div class="card-ribbon">Encargados</div>
          <div><b>Dirección:</b> {{ $dirB ?? 'Dirección de Seguimiento "B"' }}</div>
          <div><b>Director:</b> {{ $directorB ?? '—' }}</div>
        </div>
      </div>
    </div>

    {{-- ===== Avance ===== --}}
    <div class="card fancy-border">
      <div class="card-ribbon">Porcentaje de avance de la Auditoría</div>
      <div class="mb-4"><b>Auditoría:</b> {{ $auditName ?: '—' }}</div>
      <div style="font-size: 16px; font-weight: 800;">
        {{ $progress ? ($progress['percent'] . '% (' . $progress['done'] . '/' . $progress['total'] . ')') : ($auditAvance ?: '—') }}
      </div>
    </div>

    {{-- ===== Gráficas (imágenes base64) ===== --}}
    <div class="card-ribbon">Avance por departamento</div>
    <div class="columns">
      <div class="col">
        <div class="card fancy-border">
          <b>Dirección A</b><br>
          @if(!empty($gaugeA))
            <img src="{{ $gaugeA }}" class="img-chart" alt="Gauge A">
          @else
            <div class="muted">Sin imagen del gauge A</div>
          @endif
        </div>
      </div>
      <div class="col">
        <div class="card fancy-border">
          <b>Dirección B</b><br>
          @if(!empty($gaugeB))
            <img src="{{ $gaugeB }}" class="img-chart" alt="Gauge B">
          @else
            <div class="muted">Sin imagen del gauge B</div>
          @endif
        </div>
      </div>
    </div>
    {{--
    <div class="card fancy-border">
      <div class="section-title">Treemap</div>
      @if(!empty($treemap))
        <img src="{{ $treemap }}" class="img-chart" alt="Treemap">
      @else
        <div class="muted">Sin treemap</div>
      @endif
    </div>
    --}}

    {{-- ===== Tabla del depto seleccionado ===== --}}
    {{--
    @if(!empty($deptId) && $auditoriasDept && $auditoriasDept->count())
      <div class="section-title">Auditorías — {{ $dirName }} / {{ $deptName }}</div>
      <table class="table">
        <thead>
          <tr>
            <th style="width:10%;">ID</th>
            <th style="width:25%;">No. Auditoría</th>
            <th>Entidad fiscalizable</th>
            <th style="width:28%;">Acto de fiscalización</th>
          </tr>
        </thead>
        <tbody>
          @foreach($auditoriasDept as $a)
            <tr>
              <td>{{ $a->id }}</td>
              <td>{{ $a->numero_auditoria ?? 'N/D' }}</td>
              <td>{{ $a->entidad_fiscalizable ?? 'N/D' }}</td>
              <td>{{ $a->acto_fiscalizacion ?? 'N/D' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif--}}

    {{-- ===== Panel COMPLETO de la auditoría ===== --}}
    @if($auditoria)
      <div class="card-ribbon" style="margin-top:10px;">
        Seguimiento Auditoría — {{ $dirName }} / {{ $deptName }}
      </div>
      <div class="muted mb-6">Se muestran los datos más relevantes de la auditoría seleccionada.</div>

      @php
        // --- Helpers existentes ---
        $asArray = function ($v): array {
          if (!$v) return [];
          if ($v instanceof \Illuminate\Support\Collection) return $v->values()->all();
          return is_array($v) ? $v : [$v];
        };

        $phaseLabel = function ($node) use ($asArray) {
          if (!$node) return 'Pendiente';
          $arr = $asArray($node);
          if (!count($arr)) return 'Pendiente';
          if (count($arr) === 1 && is_object($arr[0]) && property_exists($arr[0], 'fase_autorizacion')) {
            return trim((string) ($arr[0]->fase_autorizacion ?? 'Pendiente'));
          }
          $phases = [];
          foreach ($arr as $it) { $phases[] = trim((string) data_get($it, 'fase_autorizacion', 'Pendiente')); }
          $phases = array_values(array_unique($phases));
          return implode(', ', $phases);
        };

        $badge = function ($fase) {
          
          if ($fase == 'Autorizado') return '<span style="color:#10b981">Autorizado</span>';
          if ($fase == 'Rechazado')  return '<span style="color:#991B1B ">Rechazado</span>';
          return '<span style="color:#f59e0b">' . htmlspecialchars($fase, ENT_QUOTES, 'UTF-8') . '</span>';
        };

        $statusDot = function ($node) use ($asArray) {
          $arr = $asArray($node);
          if (!count($arr)) return '<span style="color:#991B1B "></span>';
          $anyAuth = false;
          foreach ($arr as $it) {
            $fase = trim((string) data_get($it, 'fase_autorizacion', ''));
            if ($fase === 'Autorizado') { $anyAuth = true; break; }
          }
          return $anyAuth ? '<span style="color:#10b981"></span>' : '<span style="color:#f59e0b"></span>';
        };

        // --- NUEVO: aplana una lista de acciones con su relación hija ---
        //   p.ej. flattenActions($auditoria->accionesrecomendaciones, 'recomendaciones')
        $flattenActions = function($actions, string $relKey) use ($asArray) {
          $items = [];
          foreach ($asArray($actions) as $act) {
            $rels = $asArray(data_get($act, $relKey)); // hijos (p.ej. recomendaciones)
            if (count($rels)) {
              foreach ($rels as $r) {
                $numero = data_get($r, 'numero')
                      ?? data_get($act, 'numero')
                      ?? data_get($act, 'consecutivo')
                      ?? ('#' . (data_get($act, 'id') ?? ''));
                $fase   = data_get($r, 'fase_autorizacion') ?? data_get($act, 'fase_autorizacion');
                $items[] = ['numero' => (string)$numero, 'fase' => (string)$fase];
              }
            }
          }
          return $items;
        };

        // --- NUEVO: pinta una rejilla en N columnas, cada ítem en una mini-card ---
        $renderActionsGrid = function(array $items, string $title, int $cols = 3) use ($badge) {
          if (!count($items)) return '';
          $width = floor(100 / $cols) . '%';

          // chunk en filas
          $rows = array_chunk($items, $cols);

          $html  = '<div class="card fancy-border">';
          $html .=   '<div class="card-ribbon">'. e($title) .'</div>';
          $html .=   '<table class="grid-table"><tbody>';

          foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $it) {
              $numero = htmlspecialchars($it['numero'], ENT_QUOTES, 'UTF-8');
              $html .= '<td style="width:'. $width .';">
                          <div class="mini">
                            <div><b>No. de acción:</b> '. $numero .'</div>
                            <div><b>Estatus:</b> '. $badge($it['fase']) .'</div>
                          </div>
                        </td>';
            }
            // celdas vacías para completar la fila
            if (count($row) < $cols) {
              for ($i = count($row); $i < $cols; $i++) {
                $html .= '<td style="width:'. $width .'">&nbsp;</td>';
              }
            }
            $html .= '</tr>';
          }

          $html .=   '</tbody></table>';
          $html .= '</div>';
          return $html;
        };
      @endphp

      {{-- Radicación / Comparecencia --}}
        <div class="columns">
          <div class="col">
            <div class="card fancy-border">
              <div class="card-ribbon" style="font-weight:700;margin-bottom:6px;">Radicación</div>
              {!! $statusDot($auditoria->radicacion) !!}
              @if($auditoria->radicacion)
                <div><b>Número de expediente:</b> {{ $auditoria->radicacion->numero_expediente ?? '-' }}</div>
                <div><b>Oficio de notificación de acuerdos:</b> {{ $auditoria->radicacion->oficio_acuerdo ?? '' }}</div>
                @if ($auditoria->radicacion->fase_autorizacion == "Autorizado")
                  <div><b>Estatus:</b> <span style="color: #10b981">Autorizado</span></div>
                @elseif($auditoria->radicacion->fase_autorizacion != "Autorizado" && $auditoria->radicacion->fase_autorizacion != "Rechazado")
                  <div><b>Estatus:</b> <span style="color: #f59e0b">{!! $auditoria->radicacion->fase_autorizacion !!}</span></div>
                @elseif($auditoria->radicacion->fase_autorizacion == "Rechazado")
                  <div><b>Estatus:</b> <span style="color: #991B1B">Rechazado</span></div>
                @endif
              @else
                <div>No registrada</div>
              @endif
            </div>
          </div>
          <div class="col">
            <div class="card fancy-border">
              <div class="card-ribbon" style="font-weight:700;margin-bottom:6px;">Comparecencia</div>
              {!! $statusDot($auditoria->comparecencia) !!}
              @if($auditoria->comparecencia)
                <div><b>Acta de comparecencia:</b> {{ $auditoria->comparecencia->numero_acta ?? '-' }}</div>
                @if ($auditoria->comparecencia->fase_autorizacion == "Autorizado")
                  <div><b>Estatus:</b> <span style="color: #10b981">Autorizado</span></div>
                @elseif($auditoria->comparecencia->fase_autorizacion != "Autorizado" && $auditoria->comparecencia->fase_autorizacion != "Rechazado")
                  <div><b>Estatus:</b> <span style="color: #f59e0b">{!! $auditoria->comparecencia->fase_autorizacion !!}</span></div>
                @elseif($auditoria->comparecencia->fase_autorizacion == "Rechazado")
                  <div><b>Estatus:</b> <span style="color: #991B1B">Rechazado</span></div>
                @endif
              @else
                <div>No registrada</div>
              @endif
            </div>
          </div>
        </div>

      <div class=""><strong>Primer análisis</strong></div>
      <hr class="hr" />

      {{-- Acuerdos de conclusión --}}
      @php
        $acList = [];
        if ($auditoria->AC) $acList = array_merge($acList, is_array($auditoria->AC)? $auditoria->AC : [$auditoria->AC]);
        if ($auditoria->acuerdoconclusion)       $acList = array_merge($acList, is_array($auditoria->acuerdoconclusion)? $auditoria->acuerdoconclusion : [$auditoria->acuerdoconclusion]);
        if ($auditoria->acuerdoconclusionpliegos)$acList = array_merge($acList, is_array($auditoria->acuerdoconclusionpliegos)? $auditoria->acuerdoconclusionpliegos : [$auditoria->acuerdoconclusionpliegos]);
        $acRecCount = $auditoria->acuerdoconclusion ? (is_array($auditoria->acuerdoconclusion)? count($auditoria->acuerdoconclusion):1) : 0;
        $acPlCount  = $auditoria->acuerdoconclusionpliegos ? (is_array($auditoria->acuerdoconclusionpliegos)? count($auditoria->acuerdoconclusionpliegos):1) : 0;
      @endphp
      <div class="card fancy-border">
        <div class="card-ribbon"  style="font-weight:700;margin-bottom:6px;">Acuerdos de conclusión</div>
        {!! $statusDot($acList) !!}
        @if(count($acList))
          <div><b>Total acuerdos:</b> {{ count($acList) }}</div>
          @if($acRecCount)
            <div><b>Recomendaciones:</b> {{ $acRecCount }}</div>
              @if ($auditoria->acuerdoconclusion->fase_autorizacion == "Autorizado")
                <div><b>Estatus:</b> <span style="color: #10b981">Autorizado</span></div>
              @elseif($auditoria->acuerdoconclusion->fase_autorizacion != "Autorizado" && $auditoria->acuerdoconclusion->fase_autorizacion != "Rechazado")
                <div><b>Estatus:</b> <span style="color: #f59e0b">{!! $auditoria->acuerdoconclusion->fase_autorizacion !!}</span></div>
              @elseif($auditoria->acuerdoconclusion->fase_autorizacion == "Rechazado")
                <div><b>Estatus:</b> <span style="color: #991B1B">Rechazado</span></div>
              @endif
          @endif
          @if($acPlCount)
            <div><b>Pliegos:</b> {{ $acPlCount }}</div>
            @if ($auditoria->acuerdoconclusionpliegos->fase_autorizacion == "Autorizado")
                <div><b>Estatus:</b> <span style="color: #10b981">Autorizado</span></div>
              @elseif($auditoria->acuerdoconclusionpliegos->fase_autorizacion != "Autorizado" && $auditoria->acuerdoconclusionpliegos->fase_autorizacion != "Rechazado")
                <div><b>Estatus:</b> <span style="color: #f59e0b">{!! $auditoria->acuerdoconclusionpliegos->fase_autorizacion !!}</span></div>
              @elseif($auditoria->acuerdoconclusionpliegos->fase_autorizacion == "Rechazado")
                <div><b>Estatus:</b> <span style="color: #991B1B">Rechazado</span></div>
              @endif
          @endif
        @else
          <div>No hay acuerdos registrados</div>
        @endif
      </div>

      {{-- ===== Rejillas de acciones (ocultan card si no hay datos) ===== --}}
      @php
        $recItems  = $flattenActions($auditoria->accionesrecomendaciones ?? [], 'recomendaciones');
        $poItems   = $flattenActions($auditoria->accionespo ?? [],               'pliegosobservacion');
        $solItems  = $flattenActions($auditoria->accionessolacl ?? [],           'solicitudesaclaracion');
        $prasItems = $flattenActions($auditoria->accionespras ?? [],             'pras');
      @endphp

      {!! count($recItems)  ? $renderActionsGrid($recItems,  'Recomendaciones',      3) : '' !!}
      {!! count($poItems)   ? $renderActionsGrid($poItems,   'Pliegos de observación',3) : '' !!}
      {!! count($solItems)  ? $renderActionsGrid($solItems,  'Solicitudes de aclaración',3) : '' !!}
      {!! count($prasItems) ? $renderActionsGrid($prasItems, 'PRAS',                   3) : '' !!}

      {{-- Informes --}}
      @php
        $inList = [];
        if ($auditoria->informes)              $inList = array_merge($inList, is_array($auditoria->informes)? $auditoria->informes : [$auditoria->informes]);
        if ($auditoria->informeprimeraetapa)  $inList = array_merge($inList, is_array($auditoria->informeprimeraetapa)? $auditoria->informeprimeraetapa : [$auditoria->informeprimeraetapa]);
        if ($auditoria->informepliegos)       $inList = array_merge($inList, is_array($auditoria->informepliegos)? $auditoria->informepliegos : [$auditoria->informepliegos]);
        $isrec = $auditoria->informeprimeraetapa ? (is_array($auditoria->informeprimeraetapa)? count($auditoria->informeprimeraetapa):1) : 0;
        $ispl  = $auditoria->informepliegos ? (is_array($auditoria->informepliegos)? count($auditoria->informepliegos):1) : 0;
      @endphp
      <div class="card fancy-border">
        <div class="card-ribbon" style="font-weight:700;margin-bottom:6px;">Informes</div>
        {!! $statusDot($inList) !!}
        @if(count($inList))
          <div><b>Total Informes:</b> {{ count($inList) }}</div>
          @if($isrec)
            <div><b>Recomendaciones:</b> {{ $isrec }}</div>
            <div><b>Estatus:</b> {!! $badge($phaseLabel($auditoria->informeprimeraetapa ?? null)) !!}</div>
          @endif
          @if($ispl)
            <div><b>Pliegos:</b> {{ $ispl }}</div>
            <div><b>Estatus:</b> {!! $badge($phaseLabel($auditoria->informepliegos ?? null)) !!}</div>
          @endif
        @else
          <div>No hay informes registrados</div>
        @endif
      </div>

      {{-- Turnos --}}
      <div class=" "><strong>Turnos</strong></div>
      <hr class="hr" />
      <div class="columns">
        <div class="col">
          <div class="card fancy-border">
            <div class="card-ribbon" style="font-weight:700;margin-bottom:6px;">Turno UI</div>
            {!! $statusDot($auditoria->turnoui ?? null) !!}
            @if($auditoria->turnoui)
              <div><b>Número de expediente:</b> {{ $auditoria->turnoui->numero_turno_ui ?? '-' }}</div>
              <div><b>Estatus del turno:</b> {!! $badge($auditoria->turnoui->fase_autorizacion ?? 'Pendiente') !!}</div>
            @else
              <div>No registrada</div>
            @endif
          </div>
        </div>
        <div class="col">
          <div class="card fancy-border">
            <div class="card-ribbon" style="font-weight:700;margin-bottom:6px;">Turno OIC</div>
            {!! $statusDot($auditoria->turnooic ?? null) !!}
            @if($auditoria->turnooic)
              <div><b>Número de expediente:</b> {{ $auditoria->turnooic->numero_turno_oic ?? '-' }}</div>
              <div><b>Estatus del turno:</b> {!! $badge($auditoria->turnooic->fase_autorizacion ?? 'Pendiente') !!}</div>
            @else
              <div>No registrada</div>
            @endif
          </div>
        </div>
      </div>

      <div class="card fancy-border">
        <div class="card-ribbon" style="font-weight:700;margin-bottom:6px;">Turno Archivo</div>
        {!! $statusDot($auditoria->turnoarchivo ?? null) !!}
        @if($auditoria->turnoarchivo)
          <div><b>Número de expediente:</b> {{ $auditoria->turnoarchivo->numero_turno_archivo ?? '-' }}</div>
          <div><b>Estatus del turno:</b> {!! $badge($auditoria->turnoarchivo->fase_autorizacion ?? 'Pendiente') !!}</div>
        @else
          <div>No registrada</div>
        @endif
      </div>
    @endif
  </div>
</body>
</html>