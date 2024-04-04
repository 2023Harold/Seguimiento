<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;

use App\Models\CatalogoTipologiaAuditoria;
use Illuminate\Http\Request;

class TipologiaAuditoriasController extends Controller
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

        $auditorias = $this->setQuery($request)->orderBy('id')->paginate(30);

        return view('tipologiaauditorias.index', compact('auditorias', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditorias = $this->setQuery($request)->orderBy('id')->paginate(30);
        $tipologias = CatalogoTipologiaAuditoria::all()->pluck('descripcion', 'id')->prepend('Seleccionar una opción', '');
        // dd($tipologias);

        return view('tipologiaauditorias.form', compact('auditorias', 'request','tipologias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd('entro al store de tipologia');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Auditoria $auditoria)
    {


        $tipologias = CatalogoTipologiaAuditoria::all()->pluck('descripcion', 'id')->prepend('Seleccionar una opción', '');
        // dd($tipologias);
        return view('tipologiaauditorias.form', compact('auditoria', 'request','tipologias'));
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

        // dd('entro al update de tipologia');
        $auditoria->update($request->all());


        setMessage('La tipología se ha agregado correctamente.');

        return view ('layouts.close');


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


        if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){
            $query = $query->where('usuario_creacion_id',auth()->id());
        }

        if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){
            $userLider=auth()->user();
            $query = $query->whereRaw('LOWER(lider_proyecto_id) LIKE (?) ',["%{$userLider->id}%"])->whereNotNull('fase_autorizacion');
        }

        if(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){
            $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
            $query = $query->whereNotNull('fase_autorizacion')->whereRaw('LOWER(unidad_administrativa_registro) LIKE (?) ',["%{$unidadAdministrativa}%"])->whereNotNull('nivel_autorizacion');
        }

        if(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())||
           in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())||
           in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
           in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())){
            $unidadAdministrativa=rtrim(auth()->user()->unidad_administrativa_id, 0);
            $query = $query->whereNotNull('fase_autorizacion')->whereRaw('LOWER(unidad_administrativa_registro) LIKE (?) ',["%{$unidadAdministrativa}%"]);
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
        $movimiento='consultar';
        $acciones = AuditoriaAccion::where('segauditoria_id',$auditoria->id)->whereNull('eliminado')->orderBy('consecutivo')->paginate(30);
        $request = new Request();
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Todas', 0);

        return view('seguimientoauditoriaaccion.index', compact('acciones', 'request', 'auditoria','movimiento','tiposaccion'));
    }

    public function getCargosAsociados(Request $request)
    {
        $datos = [];
        $entidades = [];
        $cargosasociados = [];

        $entidadselecionada = EntidadFiscalizableIntra::where('PkCveEntFis', $request->entidadid)->first();
        $entidadesIntra = EntidadFiscalizableIntra::where('FkCveEntFis', $request->entidadid)->where('StsEntFis', 1);

        if ($request->entidadid == 611) {
            $entidadesIntra = $entidadesIntra->whereNotNull('CveEntFis');
        }
        $entidadesIntra = $entidadesIntra->get();

        if (!empty($entidadesIntra) && count($entidadesIntra) > 0) {
            foreach ($entidadesIntra as $entidadIntra) {
                $entidades[] = ['id' => $entidadIntra->PkCveEntFis, 'text' => $entidadIntra->NomEntFis];
            }
        }

        $datos[1] = $entidades;

        return response()->json($datos);
    }

    public function normalizarDatos(Request $request)
    {
       $entidad=EntidadFiscalizableIntra::where('PkCveEntFis',$request->entidad_fiscalizable_id)->first();
       $tipoauditoria=CatalogoTipoAuditoria::find($request->tipo_auditoria_id);
       $entidadCompleta='';

        if ($entidad->NivEntFis == 3){
            $entidadCompleta=$entidad->entidadFiscalizableN1->NomEntFis.' - '.$entidad->entidadFiscalizableN2->NomEntFis.' - '.$entidad->NomEntFis;
        }elseif ($entidad->NivEntFis == 2){
            $entidadCompleta=$entidad->entidadFiscalizableN2->NomEntFis.' - '.$entidad->NomEntFis;
        }elseif ($entidad->NivEntFis == 1){
            $entidadCompleta=$entidad->NomEntFis;
        }

        if(!empty($request->entidad_descripcion)){
            $entidadCompleta=$entidadCompleta.' - '.$request->entidad_descripcion;
        }

       $request['entidad_fiscalizable'] = $entidadCompleta;
       $request['tipo_entidad']=$entidad->Ambito;
       $request['siglas_entidad']=$entidad->SigEntFis;
       $request['ejercicio']=0;
       $request['acto_fiscalizacion']=$tipoauditoria->descripcion;

       return  $request;
    }

    public function auditoriaAcciones(Auditoria $auditoria)
    {
        setSession('auditoria_id',$auditoria->id);

        return  redirect()->route('seguimientoauditoriaacciones.index');
    }

    public function concluir(Auditoria $auditoria)
    {
        if(empty($auditoria->fase_autorizacion))
        {
            foreach ($auditoria->acciones as $accionrevision)
            {
                $accionrevision->update(['fase_revision'=>'En revisión 01']);
            }
        }else{
            if (count($auditoria->accionesrechazadaslider)>0)
            {
                foreach ($auditoria->accionesrechazadaslider as $accionrechazada)
                {
                    $accionrechazada->update(['fase_revision'=>'En revisión 01']);
                    $accionrechazada->update(['revision_lider'=>null]);
                    $accionrechazada->update(['revision_jefe'=>null]);
                }

                $accionesnuevas=AuditoriaAccion::where('segauditoria_id',getSession('auditoria_id'))->whereNull('fase_revision')->get();

                if (count($accionesnuevas)>0)
                {
                    foreach ($accionesnuevas as $accionnueva)
                    {
                        $accionnueva->update(['fase_revision'=>'En revisión 01']);
                        $accionnueva->update(['revision_lider'=>null]);
                        $accionnueva->update(['revision_jefe'=>null]);
                    }
                }
            }
            if (count($auditoria->accionesrechazadasjefe)>0)
            {
                foreach ($auditoria->accionesrechazadasjefe as $accionrechazada)
                {
                    $accionrechazada->update(['fase_revision'=>'En revisión 01']);
                    $accionrechazada->update(['revision_lider'=>null]);
                    $accionrechazada->update(['revision_jefe'=>null]);
                }

                $accionesnuevas=AuditoriaAccion::where('segauditoria_id',getSession('auditoria_id'))->whereNull('fase_revision')->get();

                if (count($accionesnuevas)>0)
                {
                    foreach ($accionesnuevas as $accionnueva)
                    {
                        $accionnueva->update(['fase_revision'=>'En revisión 01']);
                        $accionnueva->update(['revision_lider'=>null]);
                        $accionnueva->update(['revision_jefe'=>null]);
                    }
                }
            }
        }

        Movimientos::create([
            'tipo_movimiento' => 'Registro de la auditoría',
            'accion' => 'Registro de la auditoría',
            'accion_id' => $auditoria->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        if (strlen($auditoria->nivel_autorizacion) == 3 || strlen($auditoria->nivel_autorizacion) == 4) {
            $nivel_autorizacion = $auditoria->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 5);
        }


        $auditoria->update(['registro_concluido' => 'Si', 'fase_autorizacion' => 'En revisión 01']);

        $titulo = 'Registro de auditoría';
        $mensaje = '<strong>Estimado (a) ' . $auditoria->lider->name . ', ' . $auditoria->lider->puesto . ':</strong><br>
                    Ha sido registrada la auditoría No. ' . $auditoria->numero_auditoria . ', por parte del Analista ' .
                    auth()->user()->name . ', por lo que se requiere realice la revisión oportuna de la auditoría.';
        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $auditoria->lider->unidad_administrativa_id,$auditoria->lider->id);

        setMessage('El registro auditoría se ha concluido y enviado a revisión.');



        return  redirect()->route('tipologiaauditorias.index');
    }
}
