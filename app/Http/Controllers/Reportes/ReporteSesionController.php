<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\User;
use App\Models\UserSessionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class ReporteSesionController extends Controller
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
        $data = $this->buildData($request);
        return view('reportes.reportesesion.index', $data);

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
    public function show(Request $request, $id)
    {
        $usuario  = User::findOrFail($id);
        $visibles = $this->usuariosVisibles()->pluck('id');
        abort_unless($visibles->contains($usuario->id), 403);

        $sesiones = UserSessionLog::where('user_id', $usuario->id)->orderBy('login_at', 'desc')->paginate(20);

        $stats = [
            'total'        => UserSessionLog::where('user_id', $usuario->id)->count(),
            'dur_promedio' => $this->formatDuration(
                (int) UserSessionLog::where('user_id', $usuario->id)->whereNotNull('duration_seconds')->avg('duration_seconds')
            ),
            'ultima' => UserSessionLog::where('user_id', $usuario->id)->latest('login_at')->first()?->login_at,
        ];
        $query = UserSessionLog::where('user_id', $usuario->id);

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('login_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('login_at', '<=', $request->fecha_fin);
        }

        if ($request->filled('tipo_cierre')) {
            $query->where('logout_type', $request->tipo_cierre);
        }

        $sesiones = $query->orderBy('login_at', 'desc')->paginate(20);

        return view('reportes.reportesesion.show', compact('usuario', 'sesiones', 'stats'));
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

    // ─────────────────────────────────────────────────────────────────────────
    // EXPORT PDF
    // ─────────────────────────────────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        $data = $this->buildData($request);
        return view('reportes.reportesesion.pdf', $data);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EXPORT EXCEL
    // ─────────────────────────────────────────────────────────────────────────
    public function exportExcel(Request $request)
    {
        $data     = $this->buildData($request);
        $filename = 'reporte_sesiones_' . now()->format('Ymd_His') . '.xls';

        return response()->view('reportes.reportesesion.excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Cache-Control', 'max-age=0');
    }
    public function exportPdfShow(Request $request, $id)
    {
        $data = $this->buildShowData($request, $id);
        return view('reportes.reportesesion.archivos_show.pdf', $data);
    }

    public function exportExcelShow(Request $request, $id)
    {
        $data = $this->buildShowData($request, $id);

        $filename = 'reporte_sesion_usuario_' . now()->format('Ymd_His') . '.xls';

        return response()
            ->view('reportes.reportesesion.archivos_show.excel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
    private function buildShowData(Request $request, $id): array
    {
        $usuario = User::findOrFail($id);
        if ($request->filled('mes') && $request->filled('anio')) {
            $fechaInicio = Carbon::createFromDate($request->anio, $request->mes, 1)->startOfMonth();
            $fechaFin    = $fechaInicio->copy()->endOfMonth();
        } else {
            $fechaInicio = $request->filled('fecha_inicio') ? Carbon::parse($request->fecha_inicio)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
            $fechaFin = $request->filled('fecha_fin') ? Carbon::parse($request->fecha_fin)->endOfDay() : Carbon::now()->endOfDay();
        }
        $query = UserSessionLog::where('user_id', $usuario->id);

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('login_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('login_at', '<=', $request->fecha_fin);
        }

        if ($request->filled('tipo_cierre')) {
            $query->where('logout_type', $request->tipo_cierre);
        }

        $sesiones = $query->orderBy('login_at', 'desc')->get();

        $stats = [
            'total' => $sesiones->count(),
            'dur_promedio' => $this->formatDuration(
                (int) $sesiones->whereNotNull('duration_seconds')->avg('duration_seconds')
            ),
            'ultima' => optional($sesiones->first())->login_at,
        ];

        return compact('usuario', 'sesiones', 'stats', 'fechaInicio', 'fechaFin');
    }

     // ─────────────────────────────────────────────────────────────────────────
    // Roles que NUNCA aparecen en el reporte de ningún otro
    // ─────────────────────────────────────────────────────────────────────────
    private const ROLES_EXCLUIDOS_DE_TABLA = ['TUS', 'ATI', 'ADMIN', 'AS'];

    // ─────────────────────────────────────────────────────────────────────────
    // Campo cp_ua según cuenta pública activa en sesión
    // ─────────────────────────────────────────────────────────────────────────
    private function campoCpUa(): string
    {
        $cp = getSession('cp') ?? 2024;
        return "cp_ua{$cp}";
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Determina qué usuarios puede ver el usuario autenticado
    // Retorna un Builder de User ya filtrado por jerarquía
    // ─────────────────────────────────────────────────────────────────────────
    private function usuariosVisibles(): \Illuminate\Database\Eloquent\Builder
    {
        $yo    = Auth::user();
        $campo = $this->campoCpUa();
        $ua    = $yo->$campo;

        // Base: usuarios activos, nunca los roles de sistema
        $query = User::query()->where('estatus', 'Activo')->whereNotIn('siglas_rol', self::ROLES_EXCLUIDOS_DE_TABLA);

        switch ($yo->siglas_rol) {

            // ── TITULAR: ve a todos excepto ATI/TUS/ADMIN ─────────────────────
            case 'TUS':
            case 'ATUS':
                // Sin restricción adicional (el whereNotIn ya los excluye)
                break;

            // ── ATI: igual que Titular (misma vista, mismo scope) ─────────────
            case 'ATI':
            case 'ADMIN':
                // Sin restricción adicional
                break;

            // ── DIRECTOR: ve su dirección + sus departamentos ─────────────────
            case 'DS':
                $deptos = CatalogoUnidadesAdministrativas::where('direccion_id', $ua)->pluck('id');
                $uaIds = $deptos->push($ua);
                $query->whereIn($campo, $uaIds);
                break;

            // ── UC (usuario de consulta): ve AMBAS direcciones completas ──────
            // Es un DS con acceso de solo-lectura a todo, sin restricción de UA
            case 'UC':
                // Sin restricción de unidad — ve lo mismo que TUS
                // pero solo hasta DS/JD/LP/ANA/STAFF (ya excluidos TUS/ATI)
                break;

            // ── JEFE DE DEPARTAMENTO: ve LP, ANA y STAFF de su depto ──────────
            case 'JD':
                $query->where($campo, $ua)->whereIn('siglas_rol', ['LP', 'ANA', 'STAFF']);
                break;

            // ── Cualquier otro: solo a sí mismo ──────────────────────────────
            default:
                $query->where('id', $yo->id);
                break;
        }

        return $query;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Construye los datos del reporte
    // ─────────────────────────────────────────────────────────────────────────
    private function buildData(Request $request): array
    {
        $yo    = Auth::user();
        $campo = $this->campoCpUa();

        // ── Rango de fechas ───────────────────────────────────────────────────
        // Si viene mes/año los usamos, si no usamos fecha_inicio / fecha_fin,
        // si no hay nada usamos los últimos 30 días.
        if ($request->filled('mes') && $request->filled('anio')) {
            $fechaInicio = Carbon::createFromDate($request->anio, $request->mes, 1)->startOfMonth();
            $fechaFin    = $fechaInicio->copy()->endOfMonth();
        } else {
            $fechaInicio = $request->filled('fecha_inicio') ? Carbon::parse($request->fecha_inicio)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
            $fechaFin = $request->filled('fecha_fin') ? Carbon::parse($request->fecha_fin)->endOfDay() : Carbon::now()->endOfDay();
        }

        // ── Query base de usuarios visibles ───────────────────────────────────
        $usuariosQuery = $this->usuariosVisibles();

        // Filtros adicionales por request
        if ($request->filled('direccion_id') && in_array($yo->siglas_rol, ['TUS', 'ATI', 'ADMIN', 'UC','ATUS'])) {
            $deptos = CatalogoUnidadesAdministrativas::where('direccion_id', $request->direccion_id)->pluck('id');
            $uaIds  = $deptos->push((int) $request->direccion_id);
            $usuariosQuery->whereIn($campo, $uaIds);
        }

        if ($request->filled('departamento_id')) {
            $usuariosQuery->where($campo, $request->departamento_id);
        }

        if ($request->filled('siglas_rol')) {
            $usuariosQuery->where('siglas_rol', $request->siglas_rol);
        }

        if ($request->filled('nombre')) {
            $usuariosQuery->where('name', 'like', '%' . $request->nombre . '%');
        }

        $usuarios = $usuariosQuery->select('id', 'name', 'siglas_rol', $campo . ' as ua_id', 'email')->orderBy('name')->get();

        $userIds = $usuarios->pluck('id');

        // ── Sesiones del período ──────────────────────────────────────────────
        $sesiones = UserSessionLog::whereIn('user_id', $userIds)->whereBetween('login_at', [$fechaInicio, $fechaFin])->with('user:id,siglas_rol')
                                    ->orderBy('login_at', 'desc')->get();

        // ── KPI: métricas globales ────────────────────────────────────────────
        $totalUsuarios     = $usuarios->count();
        $usuariosConSesion = $sesiones->pluck('user_id')->unique()->count();
        $usuariosSinSesion = $totalUsuarios - $usuariosConSesion;
        $totalSesiones     = $sesiones->count();
        $durPromS          = $sesiones->whereNotNull('duration_seconds')->avg('duration_seconds') ?? 0;
        $duracionPromedioFmt = $this->formatDuration((int) $durPromS);

        // ── Gráfica 1: distribución de usuarios por rol ───────────────────────
        // Agrupa los usuarios del reporte (no sesiones) por rol
        $usuariosPorRol = $usuarios->groupBy('siglas_rol')->map(fn($g) => $g->count());

        // ── Gráfica 2: actividad HOY ──────────────────────────────────────────
        $hoyInicio      = now()->startOfDay();
        $sesionesHoy    = UserSessionLog::whereIn('user_id', $userIds)->where('login_at', '>=', $hoyInicio)->pluck('user_id')->unique();
        $activosHoy     = $sesionesHoy->count();
        $inactivosHoy   = $totalUsuarios - $activosHoy;

        // ── Gráfica 3: sesiones por día en el período ─────────────────────────

        $sesionesPorFecha = $sesiones->groupBy(fn($s) => $s->login_at->format('d/m/Y'))->map(fn($g) => $g->count()) ->sortKeys();
        $sesionesPorFecha = $sesionesPorFecha->sortKeysUsing(function ($a, $b) {
            return Carbon::createFromFormat('d/m/Y', $a)->timestamp <=> Carbon::createFromFormat('d/m/Y', $b)->timestamp;
        });

        $fechasGrafica  = $sesionesPorFecha->keys()->values()->toArray();
        $totalesGrafica = $sesionesPorFecha->values()->toArray();

        // ── Resumen por usuario (tabla principal) ─────────────────────────────
        $resumenUsuarios = $usuarios->map(function ($u) use ($sesiones) {
            $sus     = $sesiones->where('user_id', $u->id);
            $ultima  = $sus->sortByDesc('login_at')->first();
            $durProm = $sus->whereNotNull('duration_seconds')->avg('duration_seconds') ?? 0;

            return [
                'id'             => $u->id,
                'name'           => $u->name,
                'siglas_rol'     => $u->siglas_rol,
                'ua_id'          => $u->ua_id,
                'total_sesiones' => $sus->count(),
                'ultima_sesion'  => $ultima?->login_at,
                'ultimo_logout'  => $ultima?->logout_at,
                'dur_promedio'   => $this->formatDuration((int) $durProm),
                'dur_promedio_s' => (int) $durProm,
                'activo_hoy'     => $sus->where('login_at', '>=', now()->startOfDay())->count() > 0,
            ];
        });

        // ── Catálogos para filtros ────────────────────────────────────────────
        $direcciones = CatalogoUnidadesAdministrativas::whereIn('id', [122100, 122200])->pluck('descripcion', 'id');

        $departamentos = collect();
        if (in_array($yo->siglas_rol, ['TUS', 'ATI', 'ADMIN', 'UC', 'ATUS'])) {
            $departamentos = CatalogoUnidadesAdministrativas::whereNotNull('direccion_id')->pluck('descripcion', 'id');
        } elseif ($yo->siglas_rol === 'DS') {
            $departamentos = CatalogoUnidadesAdministrativas::where('direccion_id', $yo->$campo)->pluck('descripcion', 'id');
        }

        $unidades = CatalogoUnidadesAdministrativas::pluck('descripcion', 'id');

        $rolesDisponibles = [
            'DS'    => 'Director',
            'ATUS'  => 'Asistente de Titular',
            'UC'    => 'Usuario de Consulta',
            'JD'    => 'Jefe de Departamento',
            'LP'    => 'Líder de Proyecto',
            'ANA'   => 'Analista',
            'STAFF' => 'Staff',
        ];

        // Años disponibles (desde 2026 en adelante, o desde el primer registro)
        $anioMin  = 2026;
        $anioMax  = now()->year;
        $anios    = range($anioMin, $anioMax);
        $meses    = [1=>'Enero', 2=>'Febrero', 3=>'Marzo', 4=>'Abril', 5=>'Mayo', 6=>'Junio', 7=>'Julio', 8=>'Agosto',
                     9=>'Septiembre', 10=>'Octubre', 11=>'Noviembre', 12=>'Diciembre',];

        $sesionesPeriodo = $sesiones->pluck('user_id')->unique();
        $activosPeriodo  = $sesionesPeriodo->count();
        $inactivosPeriodo = $totalUsuarios - $activosPeriodo;
        //grafica sesiones por tipo de cierre
        $sesionesPorTipo = $sesiones->groupBy(fn($s) => $s->logout_type ?? 'unknown')->map(fn($g) => $g->count());

        //grafica sesiones por semana (semana actual vs semana anterior)
        $inicioSemanaActual = now()->startOfWeek();
        $finSemanaActual    = now()->endOfWeek();

        $inicioSemanaAnterior = now()->subWeek()->startOfWeek();
        $finSemanaAnterior    = now()->subWeek()->endOfWeek();
        $semanaActual = UserSessionLog::whereIn('user_id', $userIds)->whereBetween('login_at', [$inicioSemanaActual, $finSemanaActual])->count();

        $semanaAnterior = UserSessionLog::whereIn('user_id', $userIds)->whereBetween('login_at', [$inicioSemanaAnterior, $finSemanaAnterior])->count();
        $labelSemanaActual = $inicioSemanaActual->format('d M') . ' - ' . $finSemanaActual->format('d M');
        $labelSemanaAnterior = $inicioSemanaAnterior->format('d M') . ' - ' . $finSemanaAnterior->format('d M');

        $sinDatos = $sesiones->isEmpty();

        return compact('usuarios', 'resumenUsuarios', 'sesiones', 'totalUsuarios', 'usuariosConSesion', 'usuariosSinSesion','totalSesiones',
                        'duracionPromedioFmt', 'usuariosPorRol', 'activosHoy', 'inactivosHoy','fechasGrafica', 'totalesGrafica',
                        'direcciones', 'departamentos', 'rolesDisponibles','unidades', 'fechaInicio', 'fechaFin','anios', 'meses', 'yo',
                        'sesionesPeriodo', 'activosPeriodo', 'inactivosPeriodo', 'sesionesPorTipo', 'semanaActual', 'semanaAnterior', 'sinDatos',
                        'labelSemanaActual', 'labelSemanaAnterior');
    }
    // Helper: formatea segundos → "1h 23m 45s"
    private function formatDuration(int $s): string
    {
        if ($s <= 0) return 'N/A';
        $h = intdiv($s, 3600);
        $m = intdiv($s % 3600, 60);
        $seg = $s % 60;
        return "{$h}h {$m}m {$seg}s";
    }

}
