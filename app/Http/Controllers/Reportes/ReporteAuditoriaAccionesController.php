<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class ReporteAuditoriaAccionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     protected $model;

     public function __construct(Auditoria $model)
     {
         $this->model = $model;
     }

    public function index(Request $request)
    {       
        //$auditorias = $this->setQuery($request)->orderByRaw("TO_NUMBER(REGEXP_SUBSTR(numero_auditoria, 'DESC'))");
        //$auditorias = $auditorias->paginate(30);
        //dd("index ReporteAuditoriaAccionesController");
        /*
        $chart_options = [
            'chart_title' => 'Auditorias por mes',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\Auditoria',
            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
        ];
        $chart = new LaravelChart($chart_options);
		
        return view('reporteaccionesaud.index', compact('request', 'chart'));*/
        $cp = getSession('cp');
        $auditorias = Auditoria::where('cuenta_publica', $cp)
            ->orderBy('numero_auditoria')
            ->get();
        $auditoria = Auditoria::with([
            'acciones',
            'totalrecomendacion',
            'totalpras',
            'totalsolacl',
            'totalpliegos',
            'totalsolventadorecomendacion',
            'totalsolventadopras',
            'totalsolventadosolacl',
            'totalsolventadopliegos',
            'informesAutorizados',
            'acuerdosautorizados',
            'turnoui',
            'turnooic'
        ])
        ->where('cuenta_publica', $cp);

        return view('Reportes.reportesaccionesaud.index', compact('cp', 'request', 'auditorias'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //dd("show ReporteAuditoriaAccionesController ".$id);
        $cp = getSession('cp');
        $auditorias = Auditoria::where('cuenta_publica', $cp)
            ->orderBy('numero_auditoria')
            ->get();

        $auditoria = Auditoria::with([
            'acciones',
            'totalrecomendacion',
            'totalpras',
            'totalsolacl',
            'totalpliegos',
            'totalsolventadorecomendacion',
            'totalsolventadopras',
            'totalsolventadosolacl',
            'totalsolventadopliegos',
            'informesAutorizados',
            'acuerdosautorizados',
            'turnoui',
            'turnooic'
        ])
        ->where('cuenta_publica', $cp)
        ->findOrFail($id);

        $graficaAcciones = [
            'labels' => ['Solicitudes de Aclaración', 'Recomendaciones', 'Pliegos', 'PRAS'],
            'data' => [
                $auditoria->totalsolacl()->count(),
                $auditoria->totalrecomendacion()->count(),
                $auditoria->totalpliegos()->count(),
                $auditoria->totalpras()->count(),
            ]
        ];
        /* ===============================
            STACKED BAR – SOLVENTADAS
        =============================== */
        $stacked = [
            'labels' => ['Recomendaciones', 'PRAS', 'Solicitudes', 'Pliegos'],
            'solventadas' => [
                $auditoria->accionesrecomendacionesautorizadas()->count(),
                $auditoria->accionesprasautorizadas()->count(),
                $auditoria->accionessolaclautorizadas()->count(),
                $auditoria->accionespoautorizadas()->count(),
            ],
            'no_solventadas' => [
                $auditoria->totalNOsolventadorecomendacion()->count(),
                $auditoria->totalNOsolventadopras()->count(),
                $auditoria->totalNOsolventadosolacl()->count(),
                $auditoria->totalNOsolventadopliegos()->count(),
            ],
        ];

        /* ===============================
            LINE – AVANCE DEL PROCESO
        =============================== */
        $accionesAut =$auditoria->accionesrecomendacionesautorizadas()->count()+$auditoria->accionesprasautorizadas()->count()+$auditoria->accionessolaclautorizadas()->count()+$auditoria->accionespoautorizadas()->count();
        $accionesNoAut =$auditoria->accionesrecomendacionesNoAutorizadas()->count()+$auditoria->accionesPrasNoAutorizadas()->count()+$auditoria->accionesSolAclaNoAutorizadas()->count()+$auditoria->accionesPoNoAutorizadas()->count();

        $avance = [
            'labels' => ['Registradas', 'En Proceso','Rechazadas' , 'Autorizadas'],
            'data' => [
                $auditoria->acciones()->count(),
                $accionesNoAut,
                $auditoria->accionesrechazadas()->count(),
                $accionesAut,
            ]
        ];

        /* ===============================
             DONUT – INFORMES
        =============================== */
        $informes = [
            'labels' => ['Autorizados', 'Pendientes'],
            'data' => [
                $auditoria->informesAutorizados()->count(),
                $auditoria->informes()->count() - $auditoria->informesAutorizados()->count(),
            ]
        ];

        /* ===============================
             HORIZONTAL BAR – TURNOS
        =============================== */
        $turnos = [
            'labels' => ['UI', 'OIC', 'Archivo', 'Transferencia'],
            'data' => [
                $auditoria->turnoui ? 1 : 0,
                $auditoria->turnooic ? 1 : 0,
                $auditoria->turnoarchivo ? 1 : 0,
                $auditoria->turnoarchivotransferencia ? 1 : 0,
            ]
        ];
        /* ===============================
             BARRA DE PROGRESO GENERAL
        =============================== */
        $totalAcciones =$auditoria->totalsolacl()->count() +$auditoria->totalrecomendacion()->count() +$auditoria->totalpliegos()->count() +$auditoria->totalpras()->count();

        $accionesAutorizadas =
            $auditoria->accionesrecomendacionesautorizadas()->count() +
            $auditoria->accionesprasautorizadas()->count() +
            $auditoria->accionessolaclautorizadas()->count() +
            $auditoria->accionespoautorizadas()->count();
        
        // --- Autorizadas por tipo ---
        $aut_rec    = $auditoria->accionesrecomendacionesautorizadas()->count();
        $aut_pras   = $auditoria->accionesprasautorizadas()->count();
        $aut_solacl = $auditoria->accionessolaclautorizadas()->count();
        $aut_pliegos= $auditoria->accionespoautorizadas()->count();

        $pen_rec    = max($auditoria->totalrecomendacion()->count() - $aut_rec, 0);
        $pen_pras   = max($auditoria->totalpras()->count() - $aut_pras, 0);
        $pen_solacl = max($auditoria->totalsolacl()->count() - $aut_solacl, 0);
        $pen_pliegos= max($auditoria->totalpliegos()->count() - $aut_pliegos, 0);

        $accionesAutorizadas = $aut_rec + $aut_pras + $aut_solacl + $aut_pliegos;
        $accionesPendientes  = max($totalAcciones - $accionesAutorizadas, 0);

        $porcentaje = $totalAcciones === 0
            ? 100
            : round(($accionesAutorizadas / $totalAcciones) * 100, 2);

        // Escala para convertir counts a % y que la barra completa sume 100
        $scale = $totalAcciones > 0 ? (100 / $totalAcciones) : 0;

        /*Serie para Highcharts tipo “force” pero ambas positivas (izq→der)
        $series = [
            //['name' => 'Completadas', 'data' => [$porcentaje]],     
            ['name' => 'Recomendaciones','data' => [$aut_rec]],            // %
            ['name' => 'PRAS','data' => [$aut_pras]],            // %
            ['name' => 'Solicitudes','data' => [$aut_solacl]],            // %
            ['name' => 'Pliegos','data' => [$aut_pliegos]],            // %
            ['name' => 'Restantes',   'data' => [100 - $porcentaje]],           // %

        ];*/

        
        // Serie agregada: sólo 2 segmentos visibles (Completado y Pendiente)
        $seriesAgg = [
            [
                'name' => 'Completado',
                'data' => [[
                    'y'     => round($accionesAutorizadas * $scale, 4),  // % dentro del 100 total
                    'count' => $accionesAutorizadas,                      // número absoluto
                    'group' => 'Completado'
                ]],
                'color' => '#90144a' // verde
            ],
            [
                'name' => 'Pendiente',
                'data' => [[
                    'y'     => round($accionesPendientes * $scale, 4),   // % dentro del 100 total
                    'count' => $accionesPendientes,
                    'group' => 'Pendiente'
                ]],
                'color' => '#EDDDD4' // amarillo
            ],
        ];

        // Desglose por tipo inside cada segmento (para usarlo SÓLO en tooltip)
        $breakdown = [
            'Pendiente' => [
                ['key' => 'rec',     'label' => 'Recomendaciones','count' => $pen_rec,     'pct' => $scale ? round($pen_rec     * $scale, 2) : 0],
                ['key' => 'pras',    'label' => 'PRAS','count' => $pen_pras,    'pct' => $scale ? round($pen_pras    * $scale, 2) : 0],
                ['key' => 'solacl',  'label' => 'Solicitudes de Aclaración','count' => $pen_solacl, 'pct' => $scale ? round($pen_solacl  * $scale, 2) : 0],
                ['key' => 'pliegos', 'label' => 'Pliegos','count' => $pen_pliegos, 'pct' => $scale ? round($pen_pliegos * $scale, 2) : 0],
            ],
            'Completado' => [
                ['key' => 'rec',     'label' => 'Recomendaciones','count' => $aut_rec,     'pct' => $scale ? round($aut_rec     * $scale, 2) : 0],
                ['key' => 'pras',    'label' => 'PRAS','count' => $aut_pras,    'pct' => $scale ? round($aut_pras    * $scale, 2) : 0],
                ['key' => 'solacl',  'label' => 'Solicitudes de Aclaración','count' => $aut_solacl, 'pct' => $scale ? round($aut_solacl  * $scale, 2) : 0],
                ['key' => 'pliegos', 'label' => 'Pliegos','count' => $aut_pliegos, 'pct' => $scale ? round($aut_pliegos * $scale, 2) : 0],
            ],
            
        ];

        // (Opcional) colores por tipo para usar íconos/bolitas en tooltip
        $paletteByType = [
            'rec'     => '#960048',
            'pras'    => '#C26F8E',
            'solacl'  => '#A66571',
            'pliegos' => '#7F535B',
        ];

        // Pasa también los counts para el tooltip
        $counts = [
            'total'      => $totalAcciones,
            'aut'        => $accionesAutorizadas,
            'pendientes' => $accionesPendientes,
        ];

        // --- Totales por tipo ---
        $tot_rec    = $auditoria->totalrecomendacion()->count();
        $tot_pras   = $auditoria->totalpras()->count();
        $tot_solacl = $auditoria->totalsolacl()->count();
        $tot_pliegos= $auditoria->totalpliegos()->count();

        // --- Pendientes por tipo (totales - autorizadas) ---
        $pen_rec    = max($tot_rec    - $aut_rec,    0);
        $pen_pras   = max($tot_pras   - $aut_pras,   0);
        $pen_solacl = max($tot_solacl - $aut_solacl, 0);
        $pen_pliegos= max($tot_pliegos- $aut_pliegos,0);

        // Escala para llevar conteos a porcentaje dentro de la barra (suma 100)
        $scale = $totalAcciones > 0 ? (100 / $totalAcciones) : 0;

        // Series por tipo y por stack (primero Completado para que salga a la izquierda)
        $seriesPorTipo = [
            // ------ STACK: COMPLETADO ------
            ['name'  => 'Recomendaciones','stack' => 'Completado','data'  => [[ 'y' => round($aut_rec * $scale, 4), 'count' => $aut_rec ]],'typeKey'=> 'rec'],
            ['name'  => 'PRAS','stack' => 'Completado','data'  => [[ 'y' => round($aut_pras * $scale, 4), 'count' => $aut_pras ]],'typeKey'=> 'pras'],
            ['name'  => 'Solicitudes', 'stack' => 'Completado', 'data'  => [[ 'y' => round($aut_solacl * $scale, 4), 'count' => $aut_solacl ]],'typeKey'=> 'solacl'],
            ['name'  => 'Pliegos', 'stack' => 'Completado','data'  => [[ 'y' => round($aut_pliegos * $scale, 4), 'count' => $aut_pliegos ]], 'typeKey'=> 'pliegos'],
            
            // ------ STACK: PENDIENTE ------
            ['name'  => 'Recomendaciones','stack' => 'Pendiente','data'  => [[ 'y' => round($pen_rec * $scale, 4), 'count' => $pen_rec ]],'typeKey'=> 'rec'],
            ['name'  => 'PRAS','stack' => 'Pendiente','data'  => [[ 'y' => round($pen_pras * $scale, 4), 'count' => $pen_pras ]],'typeKey'=> 'pras'],
            ['name'  => 'Solicitudes','stack' => 'Pendiente','data'  => [[ 'y' => round($pen_solacl * $scale, 4), 'count' => $pen_solacl ]],'typeKey'=> 'solacl'],
            ['name'  => 'Pliegos','stack' => 'Pendiente','data'  => [[ 'y' => round($pen_pliegos * $scale, 4), 'count' => $pen_pliegos ]],'typeKey'=> 'pliegos'],
        ];

        // Paleta de colores por tipo (la aplicamos en la vista para que “Pendiente” sea un tono más claro)
        $palette = [
            'rec'    => ['#960048', '#EDDDD4'], // completado, pendiente
            'pras'   => ['#C26F8E', '#EDDDD4'],
            'solacl' => ['#A66571', '#EDDDD4'],
            'pliegos'=> ['#7F535B', '#EDDDD4'],
        ];
           
        /*===============================-*/
        // FASES POR TIPO (para 4 gráficas) – SIMPLE y SIN SQL extra
        // ===============================
        // Recomendaciones
        $recSeries = ['labels' => ['Total','Revisión Lider','Revisión Jefe','En validación','En autorización','Autorizadas',],
            'data' => [
                $auditoria->totalrecomendacion()->count(),                  // totalRec01
                $auditoria->accionesRecomendacionesEnRevision()->count(),   // totalRevRec02
                $auditoria->accionesRecomendacionesEnRevision01()->count(), // totalRev01Rec03
                $auditoria->accionesRecomendacionesEnValidacion()->count(), // totalValRec04
                $auditoria->accionesRecomendacionesEnAutorizacion()->count(), // totalAutRec05
                $auditoria->accionesrecomendacionesautorizadas()->count(),  // totalAutRec06
            ],];
        // Pliegos
        $poSeries = ['labels' => ['Total','Revisión Lider','Revisión Jefe','En validación','En autorización','Autorizadas',],
            'data' => [
                $auditoria->totalpliegos()->count(),        // totalPo01
                $auditoria->accionespoEnRev()->count(),     // totalRevPo02
                $auditoria->accionespoEnRev01()->count(),   // totalRev01Po03
                $auditoria->accionespoEnVal()->count(),     // totalValPo04
                $auditoria->accionespoEnAut()->count(),     // totalAutPo05
                $auditoria->accionespoautorizadas()->count(), // totalPo06
            ],];
        // Solicitudes de Aclaración
        $solSeries = ['labels' => ['Total','Revisión Lider','Revisión Jefe','En validación','En autorización','Autorizadas',],
            'data' => [
                $auditoria->totalsolacl()->count(),
                $auditoria->accionessolaclEnRev()->count(),
                $auditoria->accionessolaclEnRev01()->count(),
                $auditoria->accionessolaclEnVal()->count(),
                $auditoria->accionessolaclEnAut()->count(),
                $auditoria->accionessolaclautorizadas()->count(),
            ],];
        // PRAS
        $prasSeries = ['labels' => ['Total','Revisión Lider','Revisión Jefe','En validación','En autorización','Autorizadas',],
            'data' => [
                $auditoria->totalpras()->count(),
                $auditoria->accionesprasEnRevision()->count(),
                $auditoria->accionesprasEnRevision01()->count(),
                $auditoria->accionesprasEnValidacion()->count(),
                $auditoria->accionesprasEnAutorizacion()->count(),
                $auditoria->accionesprasautorizadas()->count(),
            ],
        ];
        // Paleta para estas líneas (puedes ajustarlo a tu tema)
        $lineColors = [
            'rec'  => '#90144a',
            'po'   => '#7F535B',
            'sol'  => '#A66571',
            'pras' => '#C26F8E',
        ];

        return view('Reportes.reportesaccionesaud.show', compact('auditoria','seriesAgg','paletteByType','breakdown', 'seriesPorTipo', 'palette', 'counts', 'cp', 'graficaAcciones', 'auditorias', 'stacked', 'avance', 'informes', 'turnos', 'porcentaje','recSeries','poSeries','solSeries','prasSeries','lineColors'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
