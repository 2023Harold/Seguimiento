<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\User;
use Illuminate\Http\Request;

class AsignacionDepartamentoController extends Controller
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
               
        return view('asignaciondepartamento.index', compact('auditorias', 'request'));
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
        $unidades = auth()->user()->unidadAdministrativa->departamentos->prepend('Seleccionar una opción', '');         
        $acciondep ='Asignación';   
        $departamentoasignado = null;           
        $acciones =  AuditoriaAccion::where('segauditoria_id',$auditoria->id)->orderBy('id')->get();
               
        return view('asignaciondepartamento.form', compact('auditoria','unidades','acciondep','departamentoasignado','acciones'));        
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
        if($request->accion=='Reasignación'){
            $accion=AuditoriaAccion::find($request->accion_id);

            $request['reasignacion_departamento']='Si';
            $accion->update($request->all());

            $titulo = 'Reasignación de la acción';
            $mensaje = '<strong>Estimado(a) ' . $request->nombre . ', ' . $request->cargo . '.</strong><br>Se le ha reasignado la acción No. '.$accion->numero.' de la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', por lo que se requiere realice la asignación oportuna del equipo de analistas y lider de proyecto, en el módulo de Asignación.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $request->departamento_asignado_id, $request->usuario_id);

            setMessage('Se ha realizado la reasignación del departamento correctamente.');


        }else{
            foreach ($request->accion_id as $id) {

                $departamento=CatalogoUnidadesAdministrativas::where('id',$request->get('departamento_asignado_id_'.$id))->first();
                $accion=AuditoriaAccion::find($id);
                $user=User::where('unidad_administrativa_id',$departamento->id)->first();
    
                $requestaccion= new Request();
                $requestaccion['departamento_asignado_id']=$departamento->id;
                $requestaccion['departamento_asignado']=$departamento->descripcion;
                $accion->update($requestaccion->all());

                $titulo = 'Asignación de la accion No. '.$accion->numero.'  de la auditoría '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado(a) ' . $user->name . ', ' . $user->puesto . '.</strong><br>Se le ha asignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del '.auth()->user()->puesto. ' '.auth()->user()->name.', por lo que se requiere realice la asignación oportuna del equipo de analistas y lider de proyecto, en el módulo de Asignación.';
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), $departamento->id, $user->id);       

            }

            setMessage('Se ha realizado la asignación del departamento correctamente.');
            
            $auditoria->update(['asignacion_departamentos'=>'Si']);
        }

        return redirect()->route('asignaciondepartamento.index',$auditoria);  
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

    public function setQuery(Request $request)
    {
         $query = $this->model;         
        
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

    public function accionesConsulta(Auditoria $auditoria)
    {
        $movimiento='departamentoconsultar';       
        $acciones = AuditoriaAccion::where('segauditoria_id',$auditoria->id)->paginate(30);   
        $request = new Request(); 
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Todas', 0);     
       
        return view('seguimientoauditoriaaccion.index', compact('acciones', 'request', 'auditoria','movimiento','tiposaccion'));
    }

    
    public function getJefeDepartamento(Request $request)
    {
        $datos = [];
        $usuario = [];
        $cargosasociados = [];

        $users = User::where('unidad_administrativa_id', $request->unidadid)->get();     

        if (!empty($users) && count($users) > 0) {
            foreach ($users as $user) {
                $usuario[] = ['id' => $user->id, 'nombre' => $user->name,'puesto'=> $user->puesto,'unidad'=>$request->unidad];
            }
        }       

        $datos[1] = $usuario;

        return response()->json($datos);
    }

    public function reasignar(AuditoriaAccion $accion)
    {              
        $auditoria = Auditoria::find($accion->segauditoria_id);
        $unidades = auth()->user()->unidadAdministrativa->departamentos->prepend('Seleccionar una opción', ''); 
        $acciondep='Reasignación';     
        
        $departamentoasignado=User::where('unidad_administrativa_id',$auditoria->departamento_asignado_id)->first();
               
        return view('reasignaciondepartamento.form', compact('auditoria','unidades','acciondep','departamentoasignado','accion'));        
    }
}
