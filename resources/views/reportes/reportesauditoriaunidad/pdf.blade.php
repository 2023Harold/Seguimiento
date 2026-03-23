<head>
  <style>
    /* ========= Paleta y tipografías ========= */
    html,
    body {
      font-size: 12px;
      color: #132a29;
      line-height: 1.35;
      background-color: #FAFAFB;
      margin: 0;
      padding: 0;
    }

    /* ========= Página y fondo ========= */
    .page-wrap {
      padding: 8mm;
    }

    @page {
      margin: 10mm;
    }

    /* ========= Títulos y separadores ========= */
    h1,h2,h3,h4,h5 {
      margin: 0 0 6px 0;
      color: #111827;
    }

    h4 {
      font-size: 16px;
    }

    h5 {
      font-size: 13px;
    }

    .muted {
      color: #6b7280;
    }

    .hr {
      height: 2px;
      background: #BB945C;
      border: 0;
      margin: 8px 0 10px 0;
    }

    /* ========= Columnas (inline-block, Dompdf-friendly) ========= */
    .columns {
      font-size: 0;
    }

    .col {
      display: inline-block;
      vertical-align: top;
      width: 49%;
      font-size: 11px;
      /* restaurar tamaño */
    }
    .colAB {
      display: inline-block;
      vertical-align: top;
      width: 100%;
      font-size: 14px;
      /* restaurar tamaño */
    }

    /* ========= Cards ========= */
    .card {
      border: 1px solid #E5E7EB;
      border-radius: 8px;
      padding: 10px;
      background-color: #FFFFFF;
      margin-bottom: 8px;
      page-break-inside: avoid;
      break-inside: avoid;
    }
    
    .card-actions {
      page-break-inside: auto !important;
      break-inside: auto !important;
    }


    .card-ribbon {
      border-left: 6px solid #960048;
      border-radius: 15px;
      padding-left: 8px;
    }

    .fancy-border {
      border: 2px solid #BB945C;
    }

    .section {
      margin-bottom: 10px;
    }

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

    .badge-ok {
      background: #D1FAE5;
      color: #065F46;
      border-color: #A7F3D0;
    }

    .badge-warn {
      background: #FEF3C7;
      color: #92400E;
      border-color: #FDE68A;
    }

    .badge-bad {
      background: #FEE2E2;
      color: #991B1B;
      border-color: #FCA5A5;
    }

    .dot {
      display: inline-block;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      margin-right: 5px;
      border: 1px solid #E5E7EB;
    }

    .dot-ok {
      background: #10b981;
    }

    .dot-warn {
      background: #f59e0b;
    }

    .dot-bad {
      background: #ef4444;
    }

    /* ========= Tablas ========= */
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 8px;
      background: #ffffff;
      border: 2px solid #BB945C;
      page-break-inside: auto;
      break-inside: auto;
    }

    .table th,
    .table td {
      border: 1px solid #ac926e;
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

    .table tbody tr:nth-child(2n) td {
      background: #FAFAFB;
    }

    /* ========= Imágenes (charts base64) ========= */
    .img-chart {
      width: 100%;
      height: auto;
      max-height: 430px;
      display: block;
    }

    /* ========= Utilidades ========= */
    .mb-0 {
      margin-bottom: 0 !important;
    }

    .mb-4 {
      margin-bottom: 4px !important;
    }

    .mb-6 {
      margin-bottom: 6px !important;
    }

    .mb-8 {
      margin-bottom: 8px !important;
    }

    .mt-6 {
      margin-top: 6px !important;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    /* “Mini-card” dentro de cada celda de la rejilla de acciones */
    .mini {
      border: 1px solid #E5E7EB;
      border-radius: 6px;
      padding: 6px;
      background: #FFFFFF;
    }

    /* Tabla de rejilla con columnas fijas (Dompdf-friendly) */
    .grid-table {
      width: 100%;
      border-collapse: collapse;
    }

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
      text-align: left;
      /* cámbialo a center si lo prefieres centrado */
    }
    
    @media print {

      /* Mantén encabezado + tabla juntos */
      .print-block {
        break-inside: avoid;
        page-break-inside: avoid;
      }

      /* Evita que el título quede solo al final de página */
      .print-block > h6,
      .print-block > h5 {
        page-break-after: avoid;
      }

      /* La tabla sí puede paginar internamente */
      .print-block table {
        break-inside: auto;
        page-break-inside: auto;
      }

      thead {
        display: table-header-group; /* encabezado en cada página */
      }

      tfoot {
        display: table-footer-group;
      }
    }

  </style>
</head>

<body>
  <div class="page-wrap">
    <h1>Reporte de Auditorías</h1>
    <div class="muted mb-6">Generado: {{ now()->format('Y-m-d H:i') }}</div>
    <hr class="hr" />

    {{-- ===== Card TOTAL ===== --}}
    @if (auth()->user()->siglas_rol != 'JD')
      <div class="card fancy-border">
        <div class="card-ribbon">
          <div style="font-size:14px; font-weight:700;">
            <h2>
            Total auditorías: <span class="">{{ $totalAuditorias }}</span>
            — Cuenta pública {{ $cuentaPublica }}</h2>
          </div>
        </div>
      </div>
    @endif
    {{-- ===== Encargados A/B ===== --}}
    <div class="columns">
      @if(auth()->user()->unidadAdministrativa->id == 122100 )
        <div class="colAB">
          <div class="card fancy-border">
            <div class="card-ribbon"><h2>Encargados</h2></div>
            <div style="font-size: 14px"><b>Director:</b> {{ $directorA ?? '—' }}</div>
            <div style="font-size: 14px"><b>Dirección:</b> {{ $dirA ?? 'Dirección de Seguimiento "A"' }}</div>
            <div style="font-size: 14px"><b>Total de auditorías:</b>{{ $auditoriasPorDireccionA->count() }} <br></div>
          </div>
        </div>
      @endif
      @if(auth()->user()->unidadAdministrativa->id == 122200)
        <div class="colAB">
          <div class="card fancy-border">
            <div class="card-ribbon"><h2>Encargados</h2></div>
            <div style="font-size: 14px"><b>Director:</b> {{ $directorB ?? '—' }}</div>
            <div style="font-size: 14px"><b>Dirección:</b> {{ $dirB ?? 'Dirección de Seguimiento "B"' }}</div>
            <div style="font-size: 14px"><b>Total de auditorías:</b>{{ $auditoriasPorDireccionB->count() }} <br></div>
          </div>
        </div>
      @endif
      @if (auth()->user()->unidadAdministrativa->id == 122000)
        <div class="col">
          <div class="card fancy-border">
            <div class="card-ribbon"><h2>Encargados</h2></div>
            <div style="font-size: 14px"><b>Director:</b> {{ $directorA ?? '—' }}</div>
            <div style="font-size: 14px"><b>Dirección:</b> {{ $dirA ?? 'Dirección de Seguimiento "A"' }}</div>
            <div style="font-size: 14px"><b>Total de auditorías:</b>{{ $auditoriasPorDireccionA->count() }} <br></div>
          </div>
        </div>
        <div class="col">
          <div class="card fancy-border">
            <div class="card-ribbon"><h2>Encargados</h2></div>
            <div style="font-size: 14px"><b>Director:</b> {{ $directorB ?? '—' }}</div>
            <div style="font-size: 14px"><b>Dirección:</b> {{ $dirB ?? 'Dirección de Seguimiento "B"' }}</div>
            <div style="font-size: 14px"><b>Total de auditorías:</b>{{ $auditoriasPorDireccionB->count() }} <br></div>
          </div>
        </div>
      @endif
    </div>
      

    {{-- ===== Gráficas (imágenes base64) ===== --}}
    <div class="card-ribbon"><h1>Avance por departamento </h1> </div>
    <div class="columns">
      @if(auth()->user()->unidadAdministrativa->id == 122100 || auth()->user()->unidadAdministrativa->id == 122000)
        <div class="col">
          <div class="card fancy-border" style="font-size: 18px">
            {{-- <b>Dirección de Seguimiento "A"</b><br> -- --}}
            @if(!empty($gaugeA))
              <img src="{{ $gaugeA }}" class="img-chart" alt="Gauge A">
            @else
              <div class="muted">Sin imagen del gauge A</div>
            @endif
          </div>
        </div>
      @endif
      @if(auth()->user()->unidadAdministrativa->id == 122200 || auth()->user()->unidadAdministrativa->id == 122000)
        <div class="col">
          <div class="card fancy-border" style="font-size: 18px">
            {{-- <b>Dirección B</b><br> - --}}
            @if(!empty($gaugeB))
              <img src="{{ $gaugeB }}" class="img-chart" alt="Gauge B">
            @else
              <div class="muted">Sin imagen del gauge B</div>
            @endif
          </div>
        </div>
      @endif
      @if(auth()->user()->unidadAdministrativa->id == 122100 || auth()->user()->unidadAdministrativa->id == 122000)
      <div class="col">
        <div class="card fancy-border" style="font-size: 15px; height: 305px;">
          @foreach($deptSuma as $d)
            @if($d['direccion'] === 'Dirección de Seguimiento "A"')
              <div class="mb-8">
                {{-- <b>{{ $d['direccion'] }}</b><br>-- --}}
                <b>{{ $d['departamento'] }}</b><br>
                <span class="muted">Total de auditorías:</span>{{ $d['total'] }} <br>
                <span class="muted">Avance:</span> {{ $d['avgPercent'] }}%<br>
                <span class="muted">Completadas:</span>
                {{ $d['completed'] }} / {{ $d['total'] }}
                ({{ $d['completedPercent'] }}%)
                <br>
              </div>
            @endif
          @endforeach
        </div>
      </div>
      @endif
      @if(auth()->user()->unidadAdministrativa->id == 122200 || auth()->user()->unidadAdministrativa->id == 122000)  
        <div class="col">
          <div class="card fancy-border" style="font-size: 15px; height: 305px;">
            @foreach($deptSuma as $d)
              @if($d['direccion'] === 'Dirección de Seguimiento "B"')
                <div class="mb-8">
                  {{-- <b>{{ $d['direccion'] }}</b><br>--}}
                  <b>{{ $d['departamento'] }}</b><br>
                  <span class="muted">Total de auditorías:</span>{{ $d['total'] }} <br>
                  <span class="muted">Avance:</span> {{ $d['avgPercent'] }}%<br>
                  <span class="muted">Completadas:</span>
                  {{ $d['completed'] }} / {{ $d['total'] }}
                  ({{ $d['completedPercent'] }}%)
                  <br>
                </div>
              @endif
            @endforeach
          </div>
        </div>
      @endif
      @if (auth()->user()->siglas_rol == 'JD')
        @php
          $total = count($auditoriasDept);
          $completed = $auditoriasDept->where('progressPercent', 100)->count();
          $avg = round($auditoriasDept->avg('progressPercent'));
          $completedPercent = $total ? round(($completed / $total) * 100) : 0;
        @endphp
        <div class="colAB">
          <div class="card fancy-border mb-3">
            <div class="card-body">

              <h4 class="mb-2">
                {{ $dirName }} / <b>{{ $deptName }} — Cuenta pública {{ $cuentaPublica }} </b>
              </h4>

              <div class="mb-2">
                <b>Total de auditorías del departamento:</b> {{ $total }}
              </div>

              <div class="mb-1">
                <b>Avance promedio del departamento:</b> {{ $avg }}%
              </div>

              
              <div style="margin-top:6px; width:100%; height:10px; background:#E5E7EB; border-radius:4px; overflow:hidden;">
                <div style="
                    width: {{ $avg }}%;
                    height: 100%;
                    background: #10b981;
                "></div>
              </div>

              <div class="mt-2 ">
                Auditorías completadas: {{ $completed }} ({{ $completedPercent }}%)
              </div>
            </div>
          </div>
        </div>
        @endif

    </div>
    <hr class="hr" />
    {{--
    <div class="card fancy-border">
      <div class="section-title">Treemap</div>
      @if(!empty($treemap))
      <img src="{{ $treemap }}" class="img-chart" alt="Treemap">
      @else
      <div class="muted">Sin treemap</div>
      @endif
    </div>
    -- --}}
    {{-- ===== Tabla del depto seleccionado ===== --}}
    @if(!empty($deptId) && $auditoriasDept && $auditoriasDept->count())
      <div class="print-block">
        <div class="card fancy-border" style="margin-top: 40px"><div class="card-ribbon"><h2>Auditorías — {{ $dirName }} / {{ $deptName }}</h2></div></div>
      
        <table class="table"">
          <thead>
            <tr>
              {{--<th style="width:10%;">ID</th>--}}
              <th class="text-center" style="font-size: 14px;"> No. Auditoría</th>
              <th class="text-center" style="font-size: 14px;">Entidad fiscalizable</th>
              <th class="text-center" style="font-size: 14px;">Acto de fiscalización</th>
              <th class="text-center" style="font-size: 14px;">Estatus</th>
            </tr>
          </thead>
          <tbody>
            @foreach($auditoriasDept as $a)
            @php
              $dot =$a->progressTotal == 0 ? 'dot-bad' : ($a->progressPercent == 100 ? 'dot-ok' : 'dot-warn');
            @endphp
              <tr>
                {{--<td>{{ $a->id }}</td>--}}
                <td class="text-center" style="font-size: 14px;">{{ $a->numero_auditoria ?? 'N/D' }}</td>
                <td style="font-size: 14px;">{{ $a->nombreentidad->entidades ?? $a->entidad_fiscalizable }}</td>
                <td style="font-size: 14px;">{{ $a->acto_fiscalizacion ?? 'N/D' }}</td>
                <td style="font-size: 14px;"> 
                  <span class="dot {{ $dot }}"></span>
                  {{ $a->progressPercent }}%
                  ({{ $a->progressDone }}/{{ $a->progressTotal }})
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
    <br>
    {{-- ===== Panel COMPLETO de la auditoría ===== --}}
    @if($auditoria)
      <div class="card-ribbon" style="margin-top:10px;">
        <div class="mb-4" style="font-size: 16px;"><b>Seguimiento Auditoría — Auditoría: </b>{{ $auditName ?: '—' }} </div>
      </div>
      <div class="muted mb-6">Se muestran los datos más relevantes de la auditoría seleccionada.</div><br>
      {{-- ===== Avance ===== --}}
      <div class="card fancy-border">
        <div class="card-ribbon" style="font-size: 16px;"><b>Porcentaje de avance de la Auditoría:</b> {{ $progress ? ($progress['percent'] . '% Actividades(' . $progress['done'] . '/' . $progress['total'] . ')') : ($auditAvance ?: '—') }}</div>
      </div>
      <br>
      @php
        // --- Helpers existentes ---
        $asArray = function ($v): array {
          if (!$v)
            return [];
          if ($v instanceof Collection)
            return $v->values()->all();
          return is_array($v) ? $v : [$v];
        };

        $phaseLabel = function ($node) use ($asArray) {
          if (!$node)
            return 'Pendiente';
          $arr = $asArray($node);
          if (!count($arr))
            return 'Pendiente';
          if (count($arr) === 1 && is_object($arr[0]) && property_exists($arr[0], 'fase_autorizacion')) {
            return trim((string) ($arr[0]->fase_autorizacion ?? 'Pendiente'));
          }
          $phases = [];
          foreach ($arr as $it) {
            $phases[] = trim((string) data_get($it, 'fase_autorizacion', 'Pendiente'));
          }
          $phases = array_values(array_unique($phases));
          return implode(', ', $phases);
        };

        $badge = function ($fase) {

          if ($fase == 'Autorizado')
            return '<span style="color:#10b981">Autorizado</span>';
          if ($fase == 'Rechazado')
            return '<span style="color:#991B1B ">Rechazado</span>';
          return '<span style="color:#f59e0b">' . htmlspecialchars($fase, ENT_QUOTES, 'UTF-8') . '</span>';
        };

        $statusDot = function ($node) use ($asArray) {
          $arr = $asArray($node);
          if (!count($arr))
            return '<span style="color:#991B1B "></span>';
          $anyAuth = false;
          foreach ($arr as $it) {
            $fase = trim((string) data_get($it, 'fase_autorizacion', ''));
            if ($fase === 'Autorizado') {
              $anyAuth = true;
              break;
            }
          }
          return $anyAuth ? '<span style="color:#10b981"></span>' : '<span style="color:#f59e0b"></span>';
        };

        // --- NUEVO: aplana una lista de acciones con su relación hija ---
        $flattenActions = function ($actions, string $relKey) {
            $items = [];
            foreach ($actions ?? [] as $accion) {
                if (!method_exists($accion, $relKey)) {
                    continue;
                }
                $rel = $accion->{$relKey};
                if ($rel instanceof \Illuminate\Support\Collection) {
                    foreach ($rel as $r) {
                        $items[] = [
                            'numero' => (string) ($r->numero ?? $accion->numero ?? ''),
                            'fase'   => (string) ($r->fase_autorizacion ?? 'Pendiente'),
                        ];
                    }
                } elseif (is_object($rel)) {
                    $items[] = [
                        'numero' => (string) ($rel->numero ?? $accion->numero ?? ''),
                        'fase'   => (string) ($rel->fase_autorizacion ?? 'Pendiente'),
                    ];
                }
            }
            return $items;
        };

        // --- NUEVO: pinta una rejilla en N columnas, cada ítem en una mini-card ---
        $renderActionsGrid = function (array $items, string $title, int $cols = 3) use ($badge) {
          if (!count($items))
            return '';
          $width = floor(100 / $cols) . '%';

          // chunk en filas
          $rows = array_chunk($items, $cols);

          $html = '<div class="card fancy-border card-actions">';
          $html .= '<div class="card-ribbon" style="font-size:18px;"> <b>' . e($title) . '</b></div>';
          $html .= '<table class="grid-table"><tbody>';

          foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $it) {
              $numero = htmlspecialchars($it['numero'], ENT_QUOTES, 'UTF-8');
              $html .= '<td style="width:' . $width . ';">
                                      <div class="mini">
                                        <div style="font-size:13px;"><b>No. de acción:</b> ' . $numero . '</div>
                                        <div style="font-size:14px;"><b>Estatus:</b> ' . $badge($it['fase']) . '</div>
                                      </div>
                                    </td>';
            }
            // celdas vacías para completar la fila
            if (count($row) < $cols) {
              for ($i = count($row); $i < $cols; $i++) {
                $html .= '<td style="width:' . $width . '">&nbsp;</td>';
              }
            }
            $html .= '</tr>';
          }
          $html .= '</tbody></table>';
          $html .= '</div>';
          return $html;
        };
      @endphp

      {{-- Radicación / Comparecencia --}}
      <div class="columns">
        <div class="col md-2">
          <div class="card fancy-border">
            <div class="card-ribbon" style="font-size: 16px; margin-bottom:6px;"><b>Radicación</b></div>
            {!! $statusDot($auditoria->radicacion) !!}
            @if($auditoria->radicacion)
              <div style="font-size: 13px;"><b>Número de expediente:</b>{{ $auditoria->radicacion->numero_expediente ?? '-' }}</div>
              <div style="font-size: 13px;"><b>Oficio de notificación de acuerdos:</b> {{ $auditoria->radicacion->oficio_acuerdo ?? '' }}</div>
              @if ($auditoria->radicacion->fase_autorizacion == "Autorizado")
                <div style="font-size: 14px;"><b>Estatus:</b> <span style="color: #10b981">Autorizado</span></div>
              @elseif($auditoria->radicacion->fase_autorizacion != "Autorizado" && $auditoria->radicacion->fase_autorizacion != "Rechazado")
                <div style="font-size: 14px;"><b>Estatus:</b> <span style="color: #f59e0b">{!! $auditoria->radicacion->fase_autorizacion !!}</span></div>
              @elseif($auditoria->radicacion->fase_autorizacion == null)
                <div style="font-size: 14px;"><b>Estatus:</b> <span style="color: #f59e0b">Pendiente</span></div>
              @elseif($auditoria->radicacion->fase_autorizacion == "Rechazado")
                <div style="font-size: 14px;"><b>Estatus:</b> <span style="color: #991B1B">Rechazado</span></div>
              @endif
            @else
              <div style="font-size: 16px;">No registrada</div>
            @endif
          </div>
        </div>
        <div class="col md-2">
          <div class="card fancy-border" style="height: 100px;">
            <div class="card-ribbon" style="font-size: 16px; margin-bottom:6px;"><b>Comparecencia</b></div>
            {!! $statusDot($auditoria->comparecencia) !!}
            @if($auditoria->comparecencia)
              <div style="font-size: 14px;"><b>Acta de comparecencia:</b> {{ $auditoria->comparecencia->numero_acta ?? '-' }}</div>
              @if ($auditoria->comparecencia->fase_autorizacion == "Autorizado")
                <div style="font-size: 14px;"><b>Estatus:</b> <span style="color: #10b981">Autorizado</span></div>
              @elseif($auditoria->comparecencia->fase_autorizacion != "Autorizado" && $auditoria->comparecencia->fase_autorizacion != "Rechazado")
                <div style="font-size: 14px;"><b>Estatus:</b> <span style="color: #f59e0b">{!! $auditoria->comparecencia->fase_autorizacion ?? "Pendiente"!!}</span></div>
              @elseif($auditoria->comparecencia->fase_autorizacion == "Rechazado")
                <div style="font-size: 14px;"><b>Estatus:</b> <span style="color: #991B1B">Rechazado</span></div>
              @endif
            @else
              <div>No registrada</div>
            @endif
          </div>
        </div>
      </div>

      <div class="" style="font-size: 18px;"><strong>Primer análisis</strong></div>
      <hr class="hr" />

      {{-- Acuerdos de conclusión --}}
      @php
        $acList = [];
        if ($auditoria->acuerdoconclusion)
          $acList = array_merge($acList, is_array($auditoria->acuerdoconclusion) ? $auditoria->acuerdoconclusion : [$auditoria->acuerdoconclusion]);
        if ($auditoria->acuerdoconclusionpliegos)
          $acList = array_merge($acList, is_array($auditoria->acuerdoconclusionpliegos) ? $auditoria->acuerdoconclusionpliegos : [$auditoria->acuerdoconclusionpliegos]);
        $acRecCount = $auditoria->acuerdoconclusion ? (is_array($auditoria->acuerdoconclusion) ? count($auditoria->acuerdoconclusion) : 1) : 0;
        $acPlCount = $auditoria->acuerdoconclusionpliegos ? (is_array($auditoria->acuerdoconclusionpliegos) ? count($auditoria->acuerdoconclusionpliegos) : 1) : 0;
      @endphp
      <div class="card fancy-border">
        <div class="card-ribbon" style="font-size:16px; margin-bottom:6px;"><b>Acuerdos de conclusión</b></div>
        @if(count($acList))
          <div style="font-size:14px;"><b>Total acuerdos:</b> {{ count($acList) }}</div>
          
          @if($acRecCount)
            <div class="col">
              <div style="font-size:14px;"><b>Recomendaciones:</b> {{ $acRecCount }}</div>
              @if ($auditoria->acuerdoconclusion->fase_autorizacion == "Autorizado")
                <div style="font-size:14px;"><b>Estatus:</b> <span style="color: #10b981">Autorizado</span></div>
              @elseif($auditoria->acuerdoconclusion->fase_autorizacion != "Autorizado" && $auditoria->acuerdoconclusion->fase_autorizacion != "Rechazado")
                <div style="font-size:14px;"><b>Estatus:</b> <span style="color: #f59e0b">{!! $auditoria->acuerdoconclusion->fase_autorizacion ?? "Pendiente" !!}</span></div>
              @elseif($auditoria->acuerdoconclusion->fase_autorizacion == "Rechazado")
                <div style="font-size:14px;"><b>Estatus:</b> <span style="color: #991B1B">Rechazado</span></div>
              @endif
            </div>
          @endif
          @if($acPlCount)
            <div class="col">
              <div style="font-size:14px;"><b>Pliegos:</b> {{ $acPlCount }}</div>
              @if ($auditoria->acuerdoconclusionpliegos->fase_autorizacion == "Autorizado")
                <div style="font-size:14px;"><b>Estatus:</b> <span style="color: #10b981">Autorizado</span></div>
              @elseif($auditoria->acuerdoconclusionpliegos->fase_autorizacion != "Autorizado" && $auditoria->acuerdoconclusionpliegos->fase_autorizacion != "Rechazado")
                <div style="font-size:14px;"><b>Estatus:</b> <span style="color: #f59e0b">{!! $auditoria->acuerdoconclusionpliegos->fase_autorizacion ?? "Pendiente" !!}</span></div>
              @elseif($auditoria->acuerdoconclusionpliegos->fase_autorizacion == "Rechazado")
                <div style="font-size:14px;"><b>Estatus:</b> <span style="color: #991B1B">Rechazado</span></div>
              @endif
            </div>
          @endif
        @else
          <div style="font-size:16px;">No hay acuerdos registrados</div>
        @endif
      </div>

      {{-- ===== Rejillas de acciones (ocultan card si no hay datos) ===== --}}
      @php
        $recItems = $flattenActions($auditoria->accionesrecomendaciones ?? [], 'recomendaciones');
        $poItems = $flattenActions($auditoria->accionespo ?? [], 'pliegosobservacion');
        $solItems = $flattenActions($auditoria->accionessolacl ?? [], 'solicitudesaclaracion');
        $prasItems = $flattenActions($auditoria->accionespras ?? [], 'pras');
      @endphp
      {!! count($recItems) ? $renderActionsGrid($recItems, 'Recomendaciones', 3) : '' !!}
      {!! count($poItems) ? $renderActionsGrid($poItems, 'Pliegos de observación', 3) : '' !!}
      {!! count($solItems) ? $renderActionsGrid($solItems, 'Solicitudes de aclaración', 3) : '' !!}
      {!! count($prasItems) ? $renderActionsGrid($prasItems, 'PRAS', 3) : '' !!}

      {{-- Informes --}}
      @php
        $inList = [];
        if ($auditoria->informeprimeraetapa)
          $inList = array_merge($inList, is_array($auditoria->informeprimeraetapa) ? $auditoria->informeprimeraetapa : [$auditoria->informeprimeraetapa]);
        if ($auditoria->informepliegos)
          $inList = array_merge($inList, is_array($auditoria->informepliegos) ? $auditoria->informepliegos : [$auditoria->informepliegos]);
        $isrec = $auditoria->informeprimeraetapa ? (is_array($auditoria->informeprimeraetapa) ? count($auditoria->informeprimeraetapa) : 1) : 0;
        $ispl = $auditoria->informepliegos ? (is_array($auditoria->informepliegos) ? count($auditoria->informepliegos) : 1) : 0;
      @endphp
      <div class="card fancy-border">
        <div class="card-ribbon" style="font-size:16px; margin-bottom:6px;"><b>Informes</b></div>
        {!! $statusDot($inList) !!}
        @if(count($inList))
          <div style="font-size:14px;"><b>Total Informes:</b> {{ count($inList) }}</div>
          @if($isrec)
            <div class="col">
              <div style="font-size:14px;"><b>Recomendaciones:</b> {{ $isrec }}</div>
              <div style="font-size:14px;"><b>Estatus:</b> {!! $badge($phaseLabel($auditoria->informeprimeraetapa ?? null)) !!}</div>
            </div>
          @endif
          @if($ispl)
            <div class="col">
              <div style="font-size:14px;"><b>Pliegos:</b> {{ $ispl }}</div>
              <div style="font-size:14px;"><b>Estatus:</b> {!! $badge($phaseLabel($auditoria->informepliegos ?? null)) !!}</div>
            </div>
          @endif
        @else
          <div style="font-size:16px;">No hay informes registrados</div>
        @endif
      </div>

      {{-- Turnos --}}
      <div class=" " style="font-size:18px;"><strong>Turnos</strong></div>
      <hr class="hr" />
      <div class="columns">
        <div class="col">
          <div class="card fancy-border">
            <div class="card-ribbon" style="font-size:16px; margin-bottom:6px;"><b>Turno UI</b></div>
            {!! $statusDot($auditoria->turnoui ?? null) !!}
            @if($auditoria->turnoui)
              <div style="font-size:14px;"><b>Número de expediente:</b> {{ $auditoria->turnoui->numero_turno_ui ?? '-' }}</div>
              <div style="font-size:14px;"><b>Estatus del turno:</b> {!! $badge($auditoria->turnoui->fase_autorizacion ?? 'Pendiente') !!}</div>
            @else
              <div style="font-size:16px;">No registrada</div>
            @endif
          </div>
        </div>
        <div class="col">
          <div class="card fancy-border">
            <div class="card-ribbon" style="font-size:16px; margin-bottom:6px;"><b>Turno OIC</b></div>
            {!! $statusDot($auditoria->turnooic ?? null) !!}
            @if($auditoria->turnooic)
              <div style="font-size:14px;"><b>Número de expediente:</b> {{ $auditoria->turnooic->numero_turno_oic ?? '-' }}</div>
              <div style="font-size:14px;"><b>Estatus del turno:</b> {!! $badge($auditoria->turnooic->fase_autorizacion ?? 'Pendiente') !!}</div>
            @else
              <div style="font-size:16px;">No registrada</div>
            @endif
          </div>
        </div>
      </div>

      <div class="card fancy-border">
        <div class="card-ribbon" style="font-size:16px; margin-bottom:6px;"><b>Turno Archivo</b></div>
        {!! $statusDot($auditoria->turnoarchivo ?? null) !!}
        @if($auditoria->turnoarchivo)
          <div style="font-size:14px;"><b>Número de expediente:</b> {{ $auditoria->turnoarchivo->numero_turno_archivo ?? '-' }}</div>
          <div style="font-size:14px;"><b>Estatus del turno:</b> {!! $badge($auditoria->turnoarchivo->fase_autorizacion ?? 'Pendiente') !!}</div>
        @else
          <div style="font-size:16px;">No registrada</div>
        @endif
      </div>
    @endif
  </div>
</body>