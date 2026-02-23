<?php

namespace App\Http\Controllers\Asignaciones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\AuditoriaUsuarios;
use App\Models\CatalogoTipoAccion;
use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;


class AsignacionLiderAnalistaExtraController extends Controller
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

        $auditoria = Auditoria::find(getSession('auditoria_id_anaextra'));

        $accions="Asignación";
        $AnalistaExtra = $auditoria->analistacpextra;
        // Obtener los registros de la tabla segauditorias_usuarios relacionados con la auditoría

        return view('Asignaciones.asignacionanalistaextra.index', compact('auditoria', 'accions','AnalistaExtra'));
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
        //dd("store anaextra",$request);
        $auditoria = Auditoria::find(getSession('auditoria_id_anaextra'));
        $anlistasAU = $auditoria->analistacpextra;
        foreach($anlistasAU as $anaext){
            if($anaext->analista_id == $request->analista_id){
                setMessage('El analista ya se encuentra aignado a la auditoria.','error');

                return redirect()->route('asignacionlideranalistaextra.index');  
            }
        }
        $request['auditoria_id']=getSession('auditoria_id_anaextra');
        $request['estatus']="Activo";

        $analistaAsignado = AuditoriaUsuarios::create($request->all());

        setMessage('Se asigno a un analista adicional a la auditoria correctamente.');

        return redirect()->route('asignacionlideranalistaextra.index');
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Auditoria $auditoria)
    {
        $auditoriaUsuarios = new AuditoriaUsuarios();
        $unidades = auth()->user()->unidadAdministrativa->departamentos->prepend('Seleccionar una opción', '');      
        $analistas = usuariocp(auth()->user()->jefe->unidad_administrativa_id)->where('siglas_rol','ANA')->where('estatus', 'Activo')->where('id','!=',$auditoria->analistacp_id)->get()->pluck('name', 'id')->prepend('Seleccionar una opción', '')->toArray();
  
        $accions ='Asignación';   
        $departamentoasignado = null;           
        $acciones =  AuditoriaAccion::where('segauditoria_id',$auditoria->id)->whereNull('eliminado')->orderBy('id')->get();
        $staffasignada = null;

        // Obtener los usuarios STAFF de la dirección asignada
        $staff = User::where('unidad_administrativa_id', $auditoria->direccion_asignada_id)
            ->where('siglas_rol', 'STAFF')
			->where('estatus', 'Activo')
            ->pluck('name', 'id')
            ->toArray();

        //return view('asignacionstaffjuridico.form', compact('auditoria', 'unidades', 'accion', 'directorasignado','staffasignada', 'staff'));
        return view('Asignaciones.asignacionanalistaextra.form', compact('auditoria','unidades','accions','departamentoasignado','acciones','auditoriaUsuarios','analistas'));        


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
        dd($request);
        $existe = AuditoriaUsuarios::where('auditoria_id', $auditoria->id)->where('staff_id', $request->staff_juridico_id)->exists();

        if ($request->accionstaff == 'Asignación') {

            if ($existe) {
                setMessage('Este staff ya está asignado a esta auditoría.', "error");
            }else{

             // Si no existe, lo insertamos en segauditoria_usuarios
             AuditoriaUsuarios::create([
                'auditoria_id' => $auditoria->id,
                'staff_id' => $request->staff_juridico_id
            ]);

            setMessage('Se ha asignado el staff correctamente.');
            }


        } elseif ($request->accionstaff == 'Reasignación') {
            if ($existe) {
                setMessage('Este staff ya está asignado a esta auditoría.', "error");
            }else{

                // Si no existe, lo insertamos en segauditoria_usuarios
                /**AuditoriaUsuarios::Update([
                    'auditoria_id' => $auditoria->id,
                    'staff_id' => $request->staff_juridico_id
                ]);*/

                AuditoriaUsuarios::where('auditoria_id', $auditoria->id)
                ->update(['staff_id' => $request->staff_juridico_id]);


                setMessage('Se ha reasignado el staff correctamente.');
            }
        }
        
        return redirect()->route('asignacionstaff.consultar', $auditoria);
    }
    /**
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

        return redirect()->route('asignacionstaffjuridico.index');

    }
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuditoriaUsuarios $ana)
    {
        //
    }

    public function eliminar(AuditoriaUsuarios $ana)
    {
        //dd("eliminar anaextra",$ana);
        //$registro = AuditoriaUsuarios::findOrFail($id);
        $ana->delete();
        setMessage('Se ha eliminado el analista adicional asignado correctamente.');
        return redirect()->route('asignacionlideranalistaextra.index');
    }

    public function cambiar(Request $request, AuditoriaUsuarios $ana)
    {
        // Opcional: valida permisos y pertenencia
        $estatus = $request->query('estatus'); // e.g. Activo|Inactivo|null

        if ($estatus === null) {
            // Toggle si no especifican un valor
            $estatus = $ana->estatus === 'Activo' ? 'Inactivo' : 'Activo';
        } else {
            // Sanitiza si viene el valor
            if (!in_array($estatus, ['Activo', 'Inactivo'], true)) {
                // Responder acorde al tipo de petición
                if ($request->wantsJson()) {
                    return response()->json(['message' => 'Valor de estatus inválido.'], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                setMessage('Valor de estatus inválido.', 'error');
                return back();
            }
        }

        $ana->estatus = $estatus;
        $ana->save();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Estatus actualizado correctamente.',
                'estatus' => $ana->estatus,
                'id'      => $ana->id,
            ]);
        }

        setMessage("Estatus actualizado a {$ana->estatus}.");
        return back();
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

    public function getAna(Request $request)
    {
        $usuario = User::where('id', $request->user_id)->first(['id', 'name', 'puesto']); // Asegúrate de incluir 'puesto'

        if ($usuario) {
            return response()->json([$usuario]); // Devuelve un array con el usuario
        }

        return response()->json([], 404); // Respuesta vacía si no se encuentra
    }


}
