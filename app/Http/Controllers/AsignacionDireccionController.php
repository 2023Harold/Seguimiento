<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\User;
use Illuminate\Http\Request;

class AsignacionDireccionController extends Controller
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

        return view('asignaciondireccion.index', compact('auditorias', 'request'));

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
        $unidades = (new CatalogoUnidadesAdministrativas())->direcciones->prepend('Seleccionar una opción', '');
        $accion ='Asignación';
        $directorasignado = null;

        return view('asignaciondireccion.form', compact('auditoria','unidades','accion','directorasignado'));
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
        if ($request->accion=='Asignación') {
            $auditoria->update($request->all());

            $titulo = 'Asignación de auditoría';
            $mensaje = '<strong>Estimado(a) ' . $request->nombre . ', ' . $request->cargo . '.</strong><br>Se le ha asignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del Titular, por lo que se requiere realice la asignación oportuna de los departamentos, en el módulo de Asignación.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $request->direccion_asignada_id, $request->usuario_id);

            setMessage('Se ha realizado la asignación de la dirección correctamente .');
        }elseif($request->accion=='Reasignación'){
            $request['reasignacion_direccion']='Si';
            $auditoria->update($request->all());

            $titulo = 'Reasignación de auditoría';
            $mensaje = '<strong>Estimado(a) ' . $request->nombre . ', ' . $request->cargo . '.</strong><br>Se le ha reasignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del Titular, por lo que se requiere realice la asignación oportuna de los departamentos, en el módulo de Asignación.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $request->direccion_asignada_id, $request->usuario_id);

            setMessage('Se ha realizado la reasignación de la dirección correctamente .');
        }

        return redirect()->route('asignaciondireccion.index');
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
         $query = $query->whereNotNull('fase_autorizacion')->where('fase_autorizacion','Autorizado');

        if(in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
           in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())){
            $query = $query->whereNotNull('fase_autorizacion');
        }elseif(in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())){
            $query = $query->whereNotNull('fase_autorizacion')->where('fase_autorizacion','Autorizado');
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
        $movimiento='direccionconsultar';
        $acciones = AuditoriaAccion::where('segauditoria_id',$auditoria->id)->paginate(30);
        $request = new Request();
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Todas', 0);
        setSession('asigancionauditoria',$auditoria->id);

        return view('seguimientoauditoriaaccion.index', compact('acciones', 'request', 'auditoria','movimiento','tiposaccion'));
                    //seguimientoauditoriaaccion.index
    }

    public function getDirector(Request $request)
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

    public function reasignar(Auditoria $auditoria)
    {
        $unidades = (new CatalogoUnidadesAdministrativas())->direcciones;
        $accion='Reasignación';

        $directorasignado=User::where('unidad_administrativa_id',$auditoria->direccion_asignada_id)->first();

        return view('asignaciondireccion.form', compact('auditoria','unidades','accion','directorasignado'));
    }
}
