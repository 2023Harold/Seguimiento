<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReporteNotificacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $data = $this->buildReporteData($request);
        return view('Reportes.reportenotificaciones.index', $data);
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
        $notificacion = Notificacion::with([
            'usuarioDestinatario'        => fn($q) => $q->select('id', 'name'),
            'usuarioCreacion'     => fn($q) => $q->select('id', 'name'),
            'usuarioActualizacion'=> fn($q) => $q->select('id', 'name'),
        ])->findOrFail($id);

        // Quitamos unidadAdministrativa del with hasta corregir el modelo.
        // Se puede obtener directo si se necesita:
        // $unidad = CatalogoUnidadesAdministrativas::find($notificacion->unidad_administrativa_id);

        return view('Reportes.reportenotificaciones.show', compact('notificacion'));
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

    // EXPORT PDF  → abre la vista pdf en el navegador (Ctrl+P / imprimir)
    public function exportPdf(Request $request)
    {
        $data = $this->buildReporteData($request);
        return view('Reportes.reportenotificaciones.pdf', $data);
    }

    // EXPORT EXCEL  → responde con una tabla HTML que Excel lee nativamente
    // ─────────────────────────────────────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        $data     = $this->buildReporteData($request);
        $filename = 'reporte_notificaciones_' . now()->format('Ymd_His') . '.xls';

        return response()
            ->view('Reportes.reportenotificaciones.excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Cache-Control', 'max-age=0');
    }
    public function goto($id)
    {
        $notificacion = Notificacion::findOrFail($id);

        if ($notificacion->llave && $notificacion->url) {
            urlSession($notificacion); // tu helper que setea sesiones
            return redirect($notificacion->url);
        }

        return redirect()->route('reportenotificaciones.show', $id)
            ->with('error', 'Esta notificación no tiene una URL de destino.');
    }
     // ─────────────────────────────────────────────────────────────────────────
    // Lógica de filtrado reutilizada en index, exportPdf y exportExcel
    // ─────────────────────────────────────────────────────────────────────────
    private function buildReporteData(Request $request): array
    {
        $usuarioActual = Auth::user();

        $fechaInicio = $request->filled('fecha_inicio')
            ? Carbon::parse($request->fecha_inicio)->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();

        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : Carbon::now()->endOfDay();

        // ── Query principal ───────────────────────────────────────────────────
        $notificacionesQuery = Notificacion::where('usuario_creacion_id', $usuarioActual->id);

        if ($request->filled('fecha_inicio')) {
            $notificacionesQuery->whereRaw(
                "TRUNC(created_at) >= TO_DATE(?, 'YYYY-MM-DD')",
                [$request->fecha_inicio]
            );
        }
        if ($request->filled('fecha_fin')) {
            $notificacionesQuery->whereRaw(
                "TRUNC(created_at) <= TO_DATE(?, 'YYYY-MM-DD')",
                [$request->fecha_fin]
            );
        }
        if ($request->filled('tipo_destinatario')) {
            if ($request->tipo_destinatario === 'individual') {
                $notificacionesQuery->whereNotNull('destinatario_id')->whereNull('equipo_id');
            } elseif ($request->tipo_destinatario === 'equipo') {
                $notificacionesQuery->whereNotNull('equipo_id');
            }
        }

        $notificaciones = $notificacionesQuery->with([
            'usuarioDestinatario' => fn($q) => $q->select('id', 'name'),
        ])->get();

        // ── Métricas ──────────────────────────────────────────────────────────
        $totalNotificaciones        = $notificaciones->count();
        $notificacionesIndividuales = $notificaciones
            ->filter(fn($n) => !is_null($n->destinatario_id) && is_null($n->equipo_id))
            ->count();
        $notificacionesEquipo   = $notificaciones->whereNotNull('equipo_id')->count();
        $notificacionesNoLeidas = $notificaciones->where('estatus', 'Pendiente')->count();
        $notificacionesLeidas   = $totalNotificaciones - $notificacionesNoLeidas;
        $porcentajeLeidas       = $totalNotificaciones > 0
            ? round(($notificacionesLeidas / $totalNotificaciones) * 100, 2)
            : 0;

        // ── Gráfica de línea ──────────────────────────────────────────────────
        $graficaQuery = DB::table('segnotificaciones')
            ->where('usuario_creacion_id', $usuarioActual->id)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin]);

        if ($request->filled('tipo_destinatario')) {
            if ($request->tipo_destinatario === 'individual') {
                $graficaQuery->whereNotNull('destinatario_id')->whereNull('equipo_id');
            } elseif ($request->tipo_destinatario === 'equipo') {
                $graficaQuery->whereNotNull('equipo_id');
            }
        }

        $estadisticasPorFecha = $graficaQuery
            ->select(
                DB::raw('TRUNC(created_at) as fecha'),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN estatus != 'Pendiente' THEN 1 ELSE 0 END) as leidas")
            )
            ->groupBy(DB::raw('TRUNC(created_at)'))
            ->orderBy(DB::raw('TRUNC(created_at)'))
            ->get();

        $fechasData  = $estadisticasPorFecha->map(fn($r) => Carbon::parse($r->fecha)->format('d/m/Y'))->toArray();
        $totalesData = $estadisticasPorFecha->pluck('total')->map(fn($v) => (int) $v)->toArray();
        $leidasData  = $estadisticasPorFecha->pluck('leidas')->map(fn($v) => (int) $v)->toArray();

        return compact(
            'notificaciones',
            'totalNotificaciones',
            'notificacionesIndividuales',
            'notificacionesEquipo',
            'notificacionesLeidas',
            'notificacionesNoLeidas',
            'porcentajeLeidas',
            'fechasData',
            'totalesData',
            'leidasData',
            'fechaInicio',
            'fechaFin'
        );
    }
}
