<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\User;
use Illuminate\Http\Request;

class AsignacionStaffJuridicoController extends Controller
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
        //
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
        $accionstaff ='Asignación';   
        $departamentoasignado = null;           
        $acciones =  AuditoriaAccion::where('segauditoria_id',$auditoria->id)->whereNull('eliminado')->orderBy('id')->get();
        $staffasignada = null;

        // Obtener los usuarios STAFF de la dirección asignada
        $staff = User::where('unidad_administrativa_id', $auditoria->direccion_asignada_id)
            ->where('siglas_rol', 'STAFF')
            ->pluck('name', 'id')
            ->toArray();

        //return view('asignacionstaffjuridico.form', compact('auditoria', 'unidades', 'accion', 'directorasignado','staffasignada', 'staff'));
        return view('asignacionstaffjuridico.form', compact('auditoria','unidades','accionstaff','departamentoasignado','acciones', 'staff', 'staffasignada'));        


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
        //dd($request);
        if ($request->accionstaff == 'Asignación') {

            // Obtener el nombre del staff jurídico seleccionado
            $staff = User::find($request->staff_juridico_id);
            $request['staff_asignada'] = $staff ? $staff->name : null;
            $auditoria->update($request->all());

            setMessage('Se ha realizado la asignación del staff juridico correctamente.');
        } elseif ($request->accionstaff == 'Reasignación') {
            // Obtener el nombre del staff jurídico seleccionado
            $staff = User::find($request->staff_juridico_id);
            $request['staff_asignada'] = $staff ? $staff->name : null;

            $request['reasignacion_staff'] = 'Si';

            $auditoria->update($request->all());

            setMessage('Se ha realizado la reasignación del staff juridico correctamente.');
        }

        return redirect()->route('asignaciondepartamento.index');
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

    public function getStaff(Request $request)
    {
        $usuario = User::where('id', $request->user_id)->first(['id', 'name', 'puesto']); // Asegúrate de incluir 'puesto'

        if ($usuario) {
            return response()->json([$usuario]); // Devuelve un array con el usuario
        }

        return response()->json([], 404); // Respuesta vacía si no se encuentra
    }


    public function reasignar(Auditoria $auditoria)
    {              
        //$auditoria = Auditoria::find($accionstaff->segauditoria_id);
        
        if (!$auditoria) {
            dd('No se encontró la auditoría');
        }

        //dd($auditoria);

        $accionstaff='Reasignación';   
        $departamentoasignado = null;           
        $acciones =  AuditoriaAccion::where('segauditoria_id',$auditoria->id)->whereNull('eliminado')->orderBy('id')->get();
        $staffasignada = null;

       // $auditoria = Auditoria::find($accionstaff->segauditoria_id);
        $unidades = auth()->user()->unidadAdministrativa->departamentos->prepend('Seleccionar una opción', ''); 
        $accionstaff='Reasignación';     
        
        // Obtener los usuarios STAFF de la dirección asignada
        $staff = User::where('unidad_administrativa_id', $auditoria->direccion_asignada_id)
            ->where('siglas_rol', 'STAFF')
            ->pluck('name', 'id')
            ->toArray();

               
        return view('asignacionstaffjuridico.form', compact('auditoria','unidades','accionstaff', 'staff', 'staffasignada'));  

    }


}
