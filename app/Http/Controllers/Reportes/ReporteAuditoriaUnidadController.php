<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class ReporteAuditoriaUnidadController extends Controller
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
        $ids  = [122110, 122120, 122130, 122210, 122220, 122230];

        $departamentosconsulta = CatalogoUnidadesAdministrativas::whereIn('id', $ids)
            ->pluck('descripcion','id')
            ->toArray();

        $departamentos = ['' => ''] + $departamentosconsulta;

        $direccion     = $request->direccionaud;
        $cuentaPublica = getSession('cp');

        // Auditorías de la cuenta pública actual
        $auditorias = Auditoria::where('cuenta_publica', $cuentaPublica)->get();

        // Direcciones
        $auditoriasPorDireccionA = Auditoria::where('cuenta_publica', $cuentaPublica)->where('direccion_asignada_id', 122100)->get();
        $auditoriasPorDireccionB = Auditoria::where('cuenta_publica', $cuentaPublica)->where('direccion_asignada_id', 122200)->get();
        
        //Departamentos Auditorias
        $auditoriasA1 = Auditoria::where('cuenta_publica', $cuentaPublica)->where('departamento_encargado_id', 122110)->get();
        $auditoriasA2 = Auditoria::where('cuenta_publica', $cuentaPublica)->where('departamento_encargado_id', 122120)->get();
        $auditoriasA3 = Auditoria::where('cuenta_publica', $cuentaPublica)->where('departamento_encargado_id', 122130)->get();
        $auditoriasB1 = Auditoria::where('cuenta_publica', $cuentaPublica)->where('departamento_encargado_id', 122210)->get();
        $auditoriasB2 = Auditoria::where('cuenta_publica', $cuentaPublica)->where('departamento_encargado_id', 122220)->get();
        $auditoriasB3 = Auditoria::where('cuenta_publica', $cuentaPublica)->where('departamento_encargado_id', 122230)->get();
        


        $countDept = fn($col, $id) => $col->where('departamento_encargado_id', $id)->count();

        // Helper para crear nodo hoja (departamento) con "value mínimo 1" y "real" para mostrar
        $mkDept = function ($name, $parentId, $dirName, $deptId, $dirId, $count) {
            return [
                'id'     => strtolower("{$parentId}_{$deptId}"),
                'name'   => $name,
                'parent' => $parentId,
                'value'  => max(1, $count), // asegura que siempre se vea el bloque
                'real'   => $count,         // el conteo real para etiquetas/tooltip
                'dir'    => $dirName,
                'deptId' => $deptId,
                'dirId'  => $dirId
            ];
        };

        $treemapData = [
            // Raíz y Direcciones (padres)
            ['id' => 'dir',  'name' => 'Direcciones'],

            ['id' => 'dirA', 'name' => 'Dirección de Seguimiento "A"', 'parent' => 'dir'],
            $mkDept($departamentosconsulta[122110] ?? 'A1', 'dirA', 'Dirección de Seguimiento "A"', 122110, 122100, $countDept($auditoriasPorDireccionA, 122110)),
            $mkDept($departamentosconsulta[122120] ?? 'A2', 'dirA', 'Dirección de Seguimiento "A"', 122120, 122100, $countDept($auditoriasPorDireccionA, 122120)),
            $mkDept($departamentosconsulta[122130] ?? 'A3', 'dirA', 'Dirección de Seguimiento "A"', 122130, 122100, $countDept($auditoriasPorDireccionA, 122130)),

            ['id' => 'dirB', 'name' => 'Dirección de Seguimiento "B"', 'parent' => 'dir'],
            $mkDept($departamentosconsulta[122210] ?? 'B1', 'dirB', 'Dirección de Seguimiento "B"', 122210, 122200, $countDept($auditoriasPorDireccionB, 122210)),
            $mkDept($departamentosconsulta[122220] ?? 'B2', 'dirB', 'Dirección de Seguimiento "B"', 122220, 122200, $countDept($auditoriasPorDireccionB, 122220)),
            $mkDept($departamentosconsulta[122230] ?? 'B3', 'dirB', 'Dirección de Seguimiento "B"', 122230, 122200, $countDept($auditoriasPorDireccionB, 122230)),
        ];

        // ===== Grilla de auditorías por departamento =====
        $auditoriasGrid = $auditorias
            ->whereIn('departamento_encargado_id', $ids)
            ->groupBy('departamento_encargado_id')
            ->map(function ($grupo) {
                return $grupo->map(function ($a) {
                    return [
                        'id'      => $a->id,
                        'numero_auditoria'   => $a->numero_auditoria ?? 'N/D',
                        'entidad_fiscalizable' => $a->entidad_fiscalizable ?? 'N/D',
                        // agrega el nombre real de la entidad tomado desde el accessor/relación
                        'nombre_entidad' => $a->nombreentidad->entidades ?? ($a->entidad_fiscalizable ?? 'N/D'),
                        'acto_fiscalizacion' => $a->acto_fiscalizacion ?? 'N/D',
                        
                    ];
                })->values();
            })->toArray();
        
        $DirBAsignado = User::where('unidad_administrativa_id',122200)->where('siglas_rol','DS')->where('estatus','Activo')->first();
        $DirAAsignado = User::where('unidad_administrativa_id',122100)->where('siglas_rol','DS')->where('estatus','Activo')->first();
        
        //dd($DirBAsignado->name,$DirAAsignado->name);
        // Dentro de index()
        $directoresPorDireccion = [
            'dirA' => $DirAAsignado->name ?? 'Director A',
            'dirB' => $DirBAsignado->name ?? 'Director B',
        ];
        $JDA1 = User::where('unidad_administrativa_id',122110)->where('siglas_rol','JD')->where('estatus','Activo')->first();
        $JDA2 = User::where('unidad_administrativa_id',122120)->where('siglas_rol','JD')->where('estatus','Activo')->first();
        $JDA3 = User::where('unidad_administrativa_id',122130)->where('siglas_rol','JD')->where('estatus','Activo')->first();
        $JDB1 = User::where('unidad_administrativa_id',122210)->where('siglas_rol','JD')->where('estatus','Activo')->first();
        $JDB2 = User::where('unidad_administrativa_id',122220)->where('siglas_rol','JD')->where('estatus','Activo')->first();
        $JDB3 = User::where('unidad_administrativa_id',122230)->where('siglas_rol','JD')->where('estatus','Activo')->first();
        // Jefes por departamento (clave: id del depto)
        $jefesPorDepartamento = [
            122110 => $JDA1->name ?? 'Jefe A1',
            122120 => $JDA2->name ?? 'Jefe A2',
            122130 => $JDA3->name ?? 'Jefe A3',
            122210 => $JDB1->name ?? 'Jefe B1',
            122220 => $JDB2->name ?? 'Jefe B2',
            122230 => $JDB3->name ?? 'Jefe B3',
        ];

        $auditoriaSeleccionada = [];

        return view('Reportes.reportesauditoriaunidad.index', compact(
            'request', 'departamentos', 'treemapData', 'auditoriasGrid', 'direccion','auditorias','auditoriasPorDireccionA',
            'auditoriasPorDireccionB', 'directoresPorDireccion', 'jefesPorDepartamento', 'auditoriaSeleccionada'
        ));
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
        dd("show");

        return view('Reportes.reportesauditoriaunidad.show', compact('auditoria',));
    }

    /**
     * Devuelve detalles de una auditoría en JSON (usado por el JS del reporte)
     */
    public function detalleAuditoria($id)
    {
        //todos estos datos los envio a la vista en formato json
        $auditoria = Auditoria::with([
            'radicacion',
            'comparecencia',
            'AC',
            //'a_c',
            'acuerdoconclusion',
            'acuerdoconclusionpliegos',
            // acciones (filas de segauditoria_acciones)
            'accionesrecomendaciones.recomendaciones',
            'accionespras.pras',
            'accionespo.pliegosobservacion',
            'accionessolacl.solicitudesaclaracion',
            // otros
            'segpras',
            'informes',
            'informeprimeraetapa',
            'informepliegos',
            'turnoui',
            'turnooic',
            'turnoarchivo',
            'movimientos'
        ])->findOrFail($id);

        return response()->json($auditoria);
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
