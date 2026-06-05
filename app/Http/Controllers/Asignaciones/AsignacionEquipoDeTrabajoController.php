<?php

namespace App\Http\Controllers\Asignaciones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\AuditoriaUsuarios;
use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\EquiposDeTrabajo;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Else_;

class AsignacionEquipoDeTrabajoController extends Controller
{
    protected $model;

    public function __construct(Auditoria $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

        public function index(Request $request)
        {
            $auditorias = $this->setQuery($request)->orderBy('id')->paginate(30);
            $rol = ['LIDER' => 'Líder de Proyecto','ANALISTA' => 'Analista',];
            //$usuariosDisponibles = User::orderBy('name')->pluck('name','id')->toArray();
            $ids = [122110, 122120, 122130, 122210, 122220, 122230];
            $departamentosconsulta = CatalogoUnidadesAdministrativas::whereIn('id', $ids)->pluck('descripcion','id')->toArray();
            $departamentos = ['' => ''] + $departamentosconsulta;
            $liderconsulta = User::where('siglas_rol', 'LP')->pluck('name', 'id')->toArray();
            $lideres = $liderconsulta;
            $analistaconsulta = User::where('siglas_rol', 'ANA')->pluck('name', 'id')->toArray();
            $analistas = ['' => ''] + $analistaconsulta;
            return view('Asignaciones.asignacionequipo.index',compact('request', 'auditorias','lideres','analistas'));
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd("create equipos");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd("store");
        $request->validate([
            'auditoria_id' => 'required|exists:segauditorias,id',
            'rol_code'     => 'required|string',
            'user_id'      => 'required|exists:segusers,id',
        ]);


        $existeActivo = AuditoriaUsuarios::where('auditoria_id', $request->auditoria_id)
            ->where('rol_code', $request->rol_code)
            ->where('user_id', $request->user_id)
            ->where('estatus', 'Activo')
            ->exists();

        if ($existeActivo) {
            return response()->json(['ok' => false, 'message' => 'Este usuario ya está asignado a este rol.'], 422);
        }

        $registro = AuditoriaUsuarios::create([
            'auditoria_id' => $request->auditoria_id,
            'user_id'      => $request->user_id,
            'rol_code'     => $request->rol_code,
            'estatus'      => 'Activo',
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Integrante agregado correctamente.',
            'user' => [
                'id'   => $registro->id,
                'name' => $registro->usuarioequipo->name,
                'destroy_url' => route('asignarequipotrabajo.destroy', $registro),
            ],
        ]);

        delSession('accionasignacion');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Auditoria $auditoria,Request $request)
    {
        $acc = getSession('accionasignacion');
        //dd($auditoria,$acc);
        $asignaciones = $auditoria->auditoriausuarios()->where('estatus', 'Activo')->orderBy('rol_code')->get();
        $lideres = usuariocp(auth()->user()->jefe->unidad_administrativa_id)->where('siglas_rol','LP')->where('estatus', 'Activo')->get()->pluck('name', 'id')->prepend('Seleccionar una opción', '');
        $analistas = usuariocp(auth()->user()->jefe->unidad_administrativa_id)->where('siglas_rol','ANA')->where('estatus', 'Activo')->get()->pluck('name', 'id')->prepend('Seleccionar una opción', '');
        $accion="asignar";

        return view('Asignaciones.asignacionequipo.form',compact('auditoria', 'accion','lideres','analistas','acc'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Auditoria $auditoria)
    {
        $jefeUnidadAdministrativa = auth()->user()->unidadAdministrativa->id;
        $equipo = EquiposDeTrabajo::where('departamento_encargado_id',$jefeUnidadAdministrativa)->first();

        if(getSession('accionasignacion') == 'Asignar Lider'){
            $registro = AuditoriaUsuarios::where('auditoria_id', $auditoria->id)
                ->where('rol_code', 'Lider')
                ->where('user_id', $request->lider_asignado_id)
                ->first();

            if ($registro && $registro->estatus == 'Activo'){
                setMessage("Este lider ya esta asignado y activo en la auditoría.",'error');
                return back();
            }

            $auditoria->update(['lidercp_id' => $request->lider_asignado_id]);
            $userLider=User::find($request->lider_asignado_id);
            /*
            if ($registro) {
                // Ya existe pero estaba Inactivo → reactivamos
                $registro->update([
                    'estatus' => 'Activo',
                    'equipo_id' => $equipo->id,
                ]);
                Movimientos::create([
                    'tipo_movimiento' => 'Reasignación de auditoría'.$auditoria->numero_auditoria.' a líder '.$userLider->name,
                    'accion'          => 'Asignación de auditoría',
                    'accion_id'       => $registro->id,
                    'estatus'         => 'Activo',
                    'usuario_creacion_id' => auth()->id(),
                ]);
            } else {
                // No existe → se crea
                $registro = AuditoriaUsuarios::create([
                    'auditoria_id' => $auditoria->id,
                    'user_id'      => $request->lider_asignado_id,
                    'rol_code'     => 'Lider',
                    'estatus'      => 'Activo',
                    'equipo_id'    => $equipo->id,
                ]);
                Movimientos::create([
                    'tipo_movimiento' => 'Asignación de auditoría '.$auditoria->numero_auditoria.' a líder '.$userLider->name,
                    'accion'          => 'Asignación de auditoría',
                    'accion_id'       => $registro->id,
                    'estatus'         => 'Activo',
                    'usuario_creacion_id' => auth()->id(),
                    'usuario_asignado_id' => auth()->id(),
                ]);
            }*/
            $registro = AuditoriaUsuarios::updateOrCreate(
                [
                    'auditoria_id' => $auditoria->id,
                    'user_id'      => $request->lider_asignado_id,
                    'rol_code'     => 'Lider',
                ],
                [
                    'estatus'   => 'Activo',
                    'equipo_id' => $equipo->id,
                ]
            );
            if ($registro->wasRecentlyCreated) {
                // Se creó por primera vez
                Movimientos::create([
                    'tipo_movimiento' => 'Asignación de auditoría '.$auditoria->numero_auditoria.' a líder '.$userLider->name,
                    'accion'          => 'Asignación de Lider a la auditoría',
                    'accion_id'       => $registro->id,
                    'estatus'         => 'Activo',
                    'usuario_creacion_id' => auth()->id(),
                    'usuario_asignado_id' => auth()->id(),
                    'auditoria_id' => $auditoria->id,
                ]);
            } else {
                // Se reactivó
                Movimientos::create([
                    'tipo_movimiento' => 'Reasignación de auditoría '.$auditoria->numero_auditoria.' a líder '.$userLider->name,
                    'accion'          => 'Asignación de Lider a la auditoría',
                    'accion_id'       => $registro->id,
                    'estatus'         => 'Activo',
                    'usuario_creacion_id' => auth()->id(),
                    'usuario_asignado_id' => auth()->id(),
                    'auditoria_id' => $auditoria->id,
                ]);
            }

            $titulo = 'Asignación de la auditoría '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) ' . $userLider->name . ', ' . $userLider->puesto . '.</strong><br>Se le ha asignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', para su revisión.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $userLider->unidad_adscripcion_id, $userLider->id);

        }else{
            $registro = AuditoriaUsuarios::where('auditoria_id', $auditoria->id)
                ->where('rol_code', 'Analista')
                ->where('user_id', $request->analista_asignado_id)
                ->first();

            if ($registro && $registro->estatus == 'Activo'){
                setMessage("Este analista ya esta asignado y activo en la auditoría.",'error');
                return back();
            }
            $auditoria->update(['analistacp_id' => $request->analista_asignado_id]);
            $userAnalista=User::find($request->analista_asignado_id);

            $registro = AuditoriaUsuarios::updateOrCreate(
                [
                    'auditoria_id' => $auditoria->id,
                    'user_id'      => $request->analista_asignado_id,
                    'rol_code'     => 'Analista',
                ],
                [
                    'estatus'   => 'Activo',
                    'equipo_id' => $equipo->id,
                ]
            );
            if ($registro->wasRecentlyCreated) {
                // Se creó por primera vez
                Movimientos::create([
                    'tipo_movimiento' => 'Asignación de auditoría '.$auditoria->numero_auditoria.' a analista '.$userAnalista->name,
                    'accion'          => 'Asignación de Analista a la auditoría',
                    'accion_id'       => $registro->id,
                    'estatus'         => 'Activo',
                    'usuario_creacion_id' => auth()->id(),
                    'usuario_asignado_id' => auth()->id(),
                    'auditoria_id' => $auditoria->id,
                ]);
            } else {
                // Se reactivó
                Movimientos::create([
                    'tipo_movimiento' => 'Reasignación de auditoría'.$auditoria->numero_auditoria.' a analista '.$userAnalista->name,
                    'accion'          => 'Asignación de Analista a la auditoría',
                    'accion_id'       => $registro->id,
                    'estatus'         => 'Activo',
                    'usuario_creacion_id' => auth()->id(),
                    'usuario_asignado_id' => auth()->id(),
                    'auditoria_id' => $auditoria->id,
                ]);
            }

            $titulo = 'Asignación de la auditoría '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) ' . $userAnalista->name . ', ' . $userAnalista->puesto . '.</strong><br>Se le ha asignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', para su revisión.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $userAnalista->unidad_adscripcion_id, $userAnalista->id);
        }

        delSession('accionasignacion');
        setMessage("Usuario asignado correctamente al equipo de trabajo");

        return redirect()->route('asignacionlideranalista.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuditoriaUsuarios $user)
    {
        dd("Destroy");
        $user->update(['estatus' => 'Inactivo']);
        setMessage('Integrante removido del equipo.');

        return back();
    }
    public function eliminar(AuditoriaUsuarios $user, Auditoria $auditoria)
    {
        $user->update(['estatus' => 'Inactivo']);
        if($user->rol_code == 'Lider'){
            Movimientos::create([
                    'tipo_movimiento' => $user->rol_code .': '. $user->usuarioequipo->name .' removido de la auditoría '.$auditoria->numero_auditoria,
                    'accion'          => 'Asignación de Lider a la auditoría',
                    'accion_id'       => $user->id,
                    'estatus'         => 'Activo',
                    'usuario_creacion_id' => auth()->id(),
                    'usuario_asignado_id' => auth()->id(),
                    'auditoria_id' => $auditoria->id,
                ]);
        }elseif($user->rol_code == 'Analista'){
            Movimientos::create([
                    'tipo_movimiento' => $user->rol_code .': '. $user->usuarioequipo->name .' removido de la auditoría '.$auditoria->numero_auditoria,
                    'accion'          => 'Asignación de Analista a la auditoría',
                    'accion_id'       => $user->id,
                    'estatus'         => 'Activo',
                    'usuario_creacion_id' => auth()->id(),
                    'usuario_asignado_id' => auth()->id(),
                    'auditoria_id' => $auditoria->id,
                ]);
        }

        setMessage('Integrante removido del equipo.');
        //$ana->delete();
        return redirect()->route('asignacionlideranalista.index');
    }
    public function setQuery(Request $request)
    {
         $query = $this->model;
         $query = $query->whereNotNull('fase_autorizacion')->where('fase_autorizacion','Autorizado');
         $query = $query->where('cuenta_publica',getSession('cp'));

        if(in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
           in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())||
           in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())){

            $query = $query->whereNotNull('fase_autorizacion')
            ->where('fase_autorizacion','Autorizado');

        }elseif(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())){
            $query = $query->whereNotNull('fase_autorizacion')
                        ->where('fase_autorizacion','Autorizado')
                        ->whereNotNull('direccion_asignada_id')
                        ->where('direccion_asignada_id',auth()->user()->unidad_administrativa_id);

        }elseif(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){
            if(getSession('cp')!=2022){
                $query = $query->where('departamento_encargado_id',getSession('cp_ua'));
            }else{
                $query = $query->whereHas('acciones', function($q){
                    $q->where('departamento_asignado_id',auth()->user()->unidad_administrativa_id);
                });
            }
        }

        if ($request->filled('numero_auditoria')) {
             $numeroAuditoria=strtolower($request->numero_auditoria);
             $query = $query->whereRaw('LOWER(numero_auditoria) LIKE (?) ',["%{$numeroAuditoria}%"]);
         }

        if ($request->filled('entidad_fiscalizable')) {
            $entidadFiscalizable=strtolower($request->entidad_fiscalizable);
            $query = $query->whereRaw('LOWER(entidad_fiscalizable) LIKE (?) ',["%{$entidadFiscalizable}%"]);
        }

        if ($request->filled('acto_fiscalizacion')) {
            $actoFiscalizacion=strtolower($request->acto_fiscalizacion);
            $query = $query->whereRaw('LOWER(acto_fiscalizacion) LIKE (?) ',["%{$actoFiscalizacion}%"]);
        }
        if ($request->filled('asignaciones') && $request->input('asignaciones') !='Todas') {
            if($request->asignaciones=='Asignadas'){
                $query = $query->whereNotNull('direccion_asignada_id');
            }elseif($request->asignaciones=='Pendientes'){
                $query = $query->whereNull('direccion_asignada_id');
            }
        }

        return $query;
    }

    public function lider(Auditoria $auditoria){
        setSession('accionasignacion','Asignar Lider');
        return redirect()->route('asignarequipotrabajo.edit', $auditoria);
    }
    public function analista(Auditoria $auditoria){
        setSession('accionasignacion','Asignar Analista');
        return redirect()->route('asignarequipotrabajo.edit', $auditoria);
    }

    public function sincronizarTodo2()
    {
        $jefeUnidadAdministrativa = auth()->user()->unidadAdministrativa->id;
        $equipo = EquiposDeTrabajo::where('departamento_encargado_id',$jefeUnidadAdministrativa)->first();

        $auditorias = Auditoria::whereNotNull('fase_autorizacion')
            ->where('fase_autorizacion', 'Autorizado')
            ->where('cuenta_publica', getSession('cp'))
            ->get();

        foreach ($auditorias as $auditoria) {
            /*
            if ($auditoria->direccion_asignada_id) {

                $director = User::where('unidad_administrativa_id', $auditoria->direccion_asignada_id)->where('siglas_rol', 'DS')->where('estatus', 'Activo')->first();
                if ($director) {
                    AuditoriaUsuarios::firstOrCreate(
                        ['auditoria_id' => $auditoria->id,
                            'user_id'      => $director->id,
                            'rol_code'     => 'DIRECTOR',
                        ],
                        ['estatus' => 'Activo']
                    );
                }
            }
            if ($auditoria->departamento_encargado_id) {
                $jefe = User::where('unidad_administrativa_id', $auditoria->departamento_encargado_id)->where('siglas_rol', 'JD')->where('estatus', 'Activo')->first();
                if ($jefe) {
                    AuditoriaUsuarios::firstOrCreate(
                        ['auditoria_id' => $auditoria->id,
                            'user_id'      => $jefe->id,
                            'rol_code'     => 'JEFE',
                        ],
                        ['estatus' => 'Activo']
                    );
                }
            }*/
             if ($auditoria->lidercp_id) {
                AuditoriaUsuarios::firstOrCreate(
                    ['auditoria_id' => $auditoria->id,
                        'user_id'      => $auditoria->lidercp_id,
                        'rol_code'     => 'Lider',
                        'equipo_id'     => $equipo->id,
                    ],
                    ['estatus' => 'Activo']
                );
            }
            if ($auditoria->analistacp_id) {
                AuditoriaUsuarios::firstOrCreate(
                    ['auditoria_id' => $auditoria->id,
                        'user_id'      => $auditoria->analistacp_id,
                        'rol_code'     => 'Analista',
                        'equipo_id'     => $equipo->id,
                    ],
                    ['estatus' => 'Activo']
                );
            }
        }
        setMessage('Sincronización completada correctamente.');
        return redirect()->route('asignacionlideranalista.index');
    }
    public function sincronizarTodo()
    {
        try {

            $jefeUnidadAdministrativa = auth()->user()->unidadAdministrativa->id;
            $equipo = EquiposDeTrabajo::where('departamento_encargado_id',$jefeUnidadAdministrativa)->first();

            $auditorias = Auditoria::whereNotNull('fase_autorizacion')
                ->where('fase_autorizacion', 'Autorizado')
                ->where('cuenta_publica', getSession('cp'))
                ->get();

            foreach ($auditorias as $auditoria) {

                if ($auditoria->lidercp_id) {
                    AuditoriaUsuarios::firstOrCreate([
                        'auditoria_id' => $auditoria->id,
                        'user_id' => $auditoria->lidercp_id,
                        'rol_code' => 'Lider',
                        'equipo_id' => $equipo->id,
                    ], ['estatus' => 'Activo']);
                }

                if ($auditoria->analistacp_id) {
                    AuditoriaUsuarios::firstOrCreate([
                        'auditoria_id' => $auditoria->id,
                        'user_id' => $auditoria->analistacp_id,
                        'rol_code' => 'Analista',
                        'equipo_id' => $equipo->id,
                    ], ['estatus' => 'Activo']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Sincronización completada correctamente.'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error interno',
                'error' => $e->getMessage() // opcional debug
            ], 500);
        }
    }
}
