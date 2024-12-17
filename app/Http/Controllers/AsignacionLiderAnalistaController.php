<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\User;
use Illuminate\Http\Request;

class AsignacionLiderAnalistaController extends Controller
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

        return view('asignacionlideranalista.index', compact('auditorias', 'request'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Auditoria $auditoria)
    {
        $lideres = usuariocp(auth()->user()->jefe->unidad_administrativa_id)->where('siglas_rol','LP')->get()->pluck('name', 'id')->prepend('Seleccionar una opción', '');
        $analistas = usuariocp(auth()->user()->jefe->unidad_administrativa_id)->where('siglas_rol','ANA')->get()->pluck('name', 'id')->prepend('Seleccionar una opción', '');
        $accion="asignar";

        return view('asignacionlideranalista.form', compact('auditoria','lideres','analistas','accion'));
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
        if(getSession('cp')==2023){

            if($request->acciond=='reasignarlider'){

                $userLider=User::find($request->lider_asignado_id);
                $requestlider=new Request();
                    $requestlider['lidercp_id']=$request->lider_asignado_id;
                
                    $auditoria->update($request->all());
    
                    $titulo = 'Reasignación de la auditoría '.$auditoria->numero_auditoria;
                    $mensaje = '<strong>Estimado(a) ' . $userLider->name . ', ' . $userLider->puesto . '.</strong><br>Se le ha reasignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', para su revisión.';
                    auth()->user()->insertNotificacion($titulo, $mensaje, now(), $userLider->unidad_adscripcion_id, $userLider->id);
             
                setMessage('Se ha realizado la reasignación del lider de proyecto correctamente.');
    
            }elseif($request->acciond=='reasignaranalista'){
    
                $userAnalista=User::find($request->analista_asignado_id);
    
                    $requestanalista=new Request();
                    $requestanalista['analistacp_id']=$request->analista_asignado_id;;
                    
                    $auditoria->update($requestanalista->all());
    
                    $titulo = 'Reasignación de la auditoría '.$auditoria->numero_auditoria;
                    $mensaje = '<strong>Estimado(a) ' . $userAnalista->name . ', ' . $userAnalista->puesto . '.</strong><br>Se le ha reasignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', para darle seguimiento a las acciones asignadas.';
                    auth()->user()->insertNotificacion($titulo, $mensaje, now(), $userAnalista->unidad_adscripcion_id, $userAnalista->id);                
                setMessage('Se ha realizado la reasignación del analista correctamente.');
    
            }else{
                
                $userLider=User::find($request->lider_asignado_id);
                $userAnalista=User::find($request->analista_asignado_id);
    
                $requestasignacion=new Request();
                $requestasignacion['analistacp_id']=$request->analista_asignado_id;;
                $requestasignacion['lidercp_id']=$request->lider_asignado_id;;
                    
                    $auditoria->update($requestasignacion->all());
    
                    $titulo = 'Asignación de la auditoría '.$auditoria->numero_auditoria;
                    $mensaje = '<strong>Estimado(a) ' . $userLider->name . ', ' . $userLider->puesto . '.</strong><br>Se le ha asignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', para su revisión.';
                    auth()->user()->insertNotificacion($titulo, $mensaje, now(), $userLider->unidad_adscripcion_id, $userLider->id);
    
                    $titulo = 'Asignación de la auditoría '.$auditoria->numero_auditoria;
                    $mensaje = '<strong>Estimado(a) ' . $userAnalista->name . ', ' . $userAnalista->puesto . '.</strong><br>Se le ha asignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', para darle seguimiento a las acciones asignadas.';
                    auth()->user()->insertNotificacion($titulo, $mensaje,
                    now(), $userAnalista->unidad_adscripcion_id, $userAnalista->id);
    
                setMessage('Se ha realizado la asignación del lider y analista correctamente.');
                $auditoria->update(['asignacion_lider_analista'=>'Si']);
            }
        }else{
        if($request->acciond=='reasignarlider'){

            $userLider=User::find($request->lider_asignado_id);
            foreach ($auditoria->accionesDepartamento  as $accion) {
                $requestlider=new Request();
                $requestlider['lider_anterior_id']=$accion->lider_asignado_id;
                $requestlider['reasignacion_lider']='Si';
                $requestlider['lider_asignado_id']=$request->lider_asignado_id;
                $requestlider['lider_asignado']=$request->lider_asignado;

                $accion->update($requestlider->all());

                $titulo = 'Reasignación de la accion No. '.$accion->numero.'  de la auditoría '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado(a) ' . $userLider->name . ', ' . $userLider->puesto . '.</strong><br>Se le ha reasignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', para su revisión.';
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), $userLider->unidad_adscripcion_id, $userLider->id);
            }
            setMessage('Se ha realizado la reasignación del lider de proyecto correctamente.');

        }elseif($request->acciond=='reasignaranalista'){

            $userAnalista=User::find($request->analista_asignado_id);

            foreach ($auditoria->accionesDepartamento  as $accion) {
                $requestanalista=new Request();
                $requestanalista['analista_anterior_id']=$accion->analista_asignado_id;
                $requestanalista['reasignacion_analista']='Si';
                $requestanalista['analista_asignado_id']=$request->analista_asignado_id;
                $requestanalista['analista_asignado']=$request->analista_asignado;

                $accion->update($requestanalista->all());

                $titulo = 'Reasignación de la accion No. '.$accion->numero.'  de la auditoría '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado(a) ' . $userAnalista->name . ', ' . $userAnalista->puesto . '.</strong><br>Se le ha reasignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', para darle seguimiento a las acciones asignadas.';
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), $userAnalista->unidad_adscripcion_id, $userAnalista->id);
            }
            setMessage('Se ha realizado la reasignación del analista correctamente.');

        }else{

            $userLider=User::find($request->lider_asignado_id);
            $userAnalista=User::find($request->analista_asignado_id);

            foreach ($auditoria->accionesDepartamento  as $accion) {
                $accion->update($request->all());

                $titulo = 'Asignación de la accion No. '.$accion->numero.'  de la auditoría '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado(a) ' . $userLider->name . ', ' . $userLider->puesto . '.</strong><br>Se le ha asignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', para su revisión.';
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), $userLider->unidad_adscripcion_id, $userLider->id);

                $titulo = 'Asignación de la accion No. '.$accion->numero.'  de la auditoría '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado(a) ' . $userAnalista->name . ', ' . $userAnalista->puesto . '.</strong><br>Se le ha asignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', para darle seguimiento a las acciones asignadas.';
                auth()->user()->insertNotificacion($titulo, $mensaje,
                now(), $userAnalista->unidad_adscripcion_id, $userAnalista->id);

            }

            setMessage('Se ha realizado la asignación del lider y analista correctamente.');
            $auditoria->update(['asignacion_lider_analista'=>'Si']);
        }
        }
        return redirect()->route('asignacionlideranalista.index');
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

    public function reasignarlider(Auditoria $auditoria)
    {
        $lideres=usuariocp(auth()->user()->director->unidad_administrativa_id)->where('siglas_rol','LP')->get()->pluck('name', 'id')->prepend('Seleccionar una opción', '');
        $analistas=usuariocp(auth()->user()->unidad_administrativa_id)->where('siglas_rol','ANA')->get()->pluck('name', 'id')->prepend('Seleccionar una opción', '');
        $accion="reasignarlider";
        return view('asignacionlideranalista.form', compact('auditoria','lideres','analistas','accion'));
    }

    public function reasignaranalista(Auditoria $auditoria)
    {
        $lideres=usuariocp(auth()->user()->director->unidad_administrativa_id)->where('siglas_rol','LP')->get()->pluck('name', 'id')->prepend('Seleccionar una opción', '');
        $analistas=usuariocp(auth()->user()->unidad_administrativa_id)->where('siglas_rol','ANA')->get()->pluck('name', 'id')->prepend('Seleccionar una opción', '');
        $accion="reasignaranalista";

        return view('asignacionlideranalista.form', compact('auditoria','lideres','analistas','accion'));
    }

    public function accionesConsulta(Auditoria $auditoria)
    {
        $movimiento='lideranalistaconsultar';
        $acciones = AuditoriaAccion::where('segauditoria_id',$auditoria->id)->paginate(30);
        $request = new Request();
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Todas', 0);

        return view('seguimientoauditoriaaccion.index', compact('acciones', 'request', 'auditoria','movimiento','tiposaccion'));
    }

    public function consulta(Auditoria $auditoria)
    {
        $movimiento='lideranalistaconsultar';
        $acciones = AuditoriaAccion::where('segauditoria_id',$auditoria->id)->paginate(30);
        $request = new Request();
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Todas', 0);

        return view('asignacionlideranalistaconsulta.index', compact('acciones', 'request', 'auditoria','movimiento','tiposaccion'));
    }

    public function setQuery(Request $request)
    {
         $query = $this->model;
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
            if(getSession('cp')==2023){
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

        return $query;
    }

    public function getLider(Request $request)
    {
        $datos = [];
        $usuario = [];
        $cargosasociados = [];

        $users = User::where('id',$request->userid)->get();

        if (!empty($users) && count($users) > 0) {
            foreach ($users as $user) {
                $usuario[] = ['id' => $user->id, 'nombre' => $user->name,'puesto'=> $user->puesto,'unidad'=>$request->unidad];
            }
        }

        $datos[1] = $usuario;

        return response()->json($datos);
    }

    public function getAnalista(Request $request)
    {
        $datos = [];
        $usuario = [];
        $cargosasociados = [];

        $users = User::where('id',$request->useranalistaid)->get();

        if (!empty($users) && count($users) > 0) {
            foreach ($users as $user) {
                $usuario[] = ['id' => $user->id, 'nombre' => $user->name,'puesto'=> $user->puesto,'unidad'=>$request->unidad];
            }
        }

        $datos[1] = $usuario;

        return response()->json($datos);
    }

}
