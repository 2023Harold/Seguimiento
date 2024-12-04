<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditoriaAccionRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\CatalogoTipoAuditoria;
use App\Models\CatalogoTipologia;
use App\Models\Movimientos;
use App\Rules\RecomendacionRegistroRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AgregarAccionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $validationRules;
    public $attributeNames;
    public $errorMessages;
    protected $model;


    public function __construct(AuditoriaAccion $model)
    {
        $this->validationRules = [];
        $this->attributeNames = [
            'evidencia_recomendacion' => 'evidencia documental que acredite la atención de la recomendación',
            'tipo_recomendacion' => 'tipo de recomendación',
            'tramo_control_recomendacion' => 'tramo de control',
        ];
        $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio.',
        ];

        $this->model = $model;
    }

    public function index(Request $request)
    {
        $auditoria = Auditoria::find(getSession('auditoriaselect_id'));
        $acciones =  $this->setQuery($request)->orderBy('consecutivo')->paginate(30);
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Todas', 0);        
        $monto_aclarar=$this->setQuery($request)->orderBy('monto_aclarar');
        $movimiento=null;

        return view('agregaracciones.index', compact('acciones', 'request', 'auditoria','tiposaccion','monto_aclarar','movimiento'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $auditoria = Auditoria::find(getSession('auditoriaselect_id'));        
        $numeroconsecutivo=AuditoriaAccion::where('segauditoria_id',$auditoria->id)->whereNull('eliminado')->get()->count()+1;
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Seleccionar una opción', '');
        $tipologias= CatalogoTipologia::all()->pluck('tipologia', 'id')->prepend('Seleccionar una opción', '');
        $accion = new AuditoriaAccion();
        $actosfiscalizacion=CatalogoTipoAuditoria::whereIn('id',[1,2,3,4])->pluck('descripcion', 'id')->prepend('Seleccionar una opción', '');

        return view('agregaracciones.form', compact('numeroconsecutivo','tiposaccion','accion','auditoria','actosfiscalizacion','tipologias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Auditoria $auditoria)
    {
        $this->setValidator($request)->validate();       
        $auditoria = Auditoria::find(getSession('auditoriaselect_id'));
        $this->normalizarDatos($request);
        mover_archivos($request, ['cedula'], null);
        $accion  = AuditoriaAccion::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('agregaracciones.index','auditoria');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AuditoriaAccion $accion)
    {
        $auditoria = $accion->auditoria;
        return view('agregaracciones.showacciones',compact('accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AuditoriaAccion $accion)
    {
        $auditoria = Auditoria::find(getSession('auditoriaselect_id'));
        $numeroconsecutivo=$accion->consecutivo;
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id');
        $tipologias= CatalogoTipologia::where('tipo_auditoria_id',$accion->acto_fiscalizacion_id)->pluck('tipologia', 'id')->prepend('Seleccionar una opción', '');
        $actosfiscalizacion=CatalogoTipoAuditoria::whereIn('id',[1,2,3,4])->pluck('descripcion', 'id')->prepend('Seleccionar una opción', '');


        return view('agregaracciones.form', compact('numeroconsecutivo','tiposaccion','accion','auditoria','actosfiscalizacion','tipologias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuditoriaAccionRequest $request, AuditoriaAccion $accion)
    {
        $this->setValidator($request)->validate();
        $this->normalizarDatos($request);
        mover_archivos($request, ['cedula'], $accion);
        $accion->update($request->all());
        $this->actualizaProgresivo();

        setMessage('La acción se ha modificado correctamente.');

        return redirect()->route('agregaracciones.index');
    }
    public function destroy(AuditoriaAccion $accion)
    {
        $accion->update(['eliminado'=>'X']);

        return redirect()->route('agregaracciones.index');
    }

    public function setQuery(Request $request)
    {
         $query = $this->model;

         $query = $query->where('segauditoria_id',getSession('auditoriaselect_id'))->whereNull('eliminado');

        if ($request->filled('consecutivo')) {
            $query = $query->where('consecutivo',$request->consecutivo);
         }

        if ($request->filled('segtipo_accion_id') && $request->segtipo_accion_id!=0) {
            $query = $query->where('segtipo_accion_id',$request->segtipo_accion_id);
        }

        if ($request->filled('numero')) {
            $numeroAcccion=strtolower($request->numero);
            $query = $query->whereRaw('LOWER(numero) LIKE (?) ',["%{$numeroAcccion}%"]);
        }
        if ($request->filled('monto_aclarar')) {
            $query = $query->where('monto_aclarar',$request->monto_aclarar);;
        }


        return $query;
    }

    public function normalizarDatos(Request $request)
    {
        $tiposaccion = CatalogoTipoAccion::find($request->segtipo_accion_id);
        $actosfiscalizacion = CatalogoTipoAuditoria::find($request->acto_fiscalizacion_id);
        $request['tipo'] = $tiposaccion->descripcion;
        $request['segauditoria_id'] = getSession('auditoriaselect_id');
        $request['usuario_actualizacion_id'] = auth()->id();
        $request['accion'] = str_replace("\r\n", "</br>",$request->accion);
        $request['acto_fiscalizacion'] = $actosfiscalizacion->descripcion;

        if ($request->segtipo_accion_id==2) {
            $request['monto_aclarar'] = null;
        }
        return $request;

    }

    public function actualizaProgresivo()
    {
        $numeroSiguiente = 1;
        $modelName = $this->model;

        $er_records = $modelName::where('segauditoria_id', getSession('auditoriaselect_id'))->whereNull('eliminado');

        $er_records = $er_records->orderBy('consecutivo')->get();

        foreach ($er_records as $er_record) {
            $er_record->update(['consecutivo' => $numeroSiguiente]);
            $numeroSiguiente++;
        }
    }

    protected function setValidator(Request $request,AuditoriaAccion $accion = null)
    {
        $this->validationRules['evidencia_recomendacion'] = [new RecomendacionRegistroRule($request->segtipo_accion_id,$request->acto_fiscalizacion_id)];
        $this->validationRules['tipo_recomendacion'] = [new RecomendacionRegistroRule($request->segtipo_accion_id,$request->acto_fiscalizacion_id)];
        $this->validationRules['tramo_control_recomendacion'] = [new RecomendacionRegistroRule($request->segtipo_accion_id,$request->acto_fiscalizacion_id)];
        
        return Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);
    }

    protected function accion(AuditoriaAccion $accion)
    {
        $auditoria = $accion->auditoria;        
        return view('agregaracciones.show',compact('accion','auditoria'));
    }    
    public function auditoriaAcciones(Auditoria $auditoria)
    {
        setSession('auditoria_id',$auditoria->id);

        return  redirect()->route('agregaracciones.index');
    }

    public function concluir(Auditoria $auditoria)
    {
        
            
            if (count($auditoria->acciones)>0)
            {
                foreach ($auditoria->acciones as $accionrechazada)
                {
                    $accionrechazada->update(['fase_revision'=>'En revisión 01']);
                    $accionrechazada->update(['revision_lider'=>'En revisión 01']);
                    $accionrechazada->update(['revision_jefe'=>null]);
                }

                $accionesnuevas=AuditoriaAccion::where('segauditoria_id',getSession('auditoriaselect_id'))->whereNull('fase_revision')->get();

                if (count($accionesnuevas)>0)
                {
                    foreach ($accionesnuevas as $accionnueva)
                    {
                        $accionnueva->update(['fase_revision'=>'En revisión 01']);
                        $accionnueva->update(['revision_lider'=>'En revisión 01']);
                        $accionnueva->update(['revision_jefe'=>null]);
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


        $titulo = 'Registro de auditoría';
        $mensaje = '<strong>Estimado (a) ' . $auditoria->lider->name . ', ' . $auditoria->lider->puesto . ':</strong><br>
                    Ha sido registrada la auditoría No. ' . $auditoria->numero_auditoria . ', por parte del Analista ' .
                    auth()->user()->name . ', por lo que se requiere realice la revisión oportuna de la auditoría.';
        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $auditoria->lider->unidad_administrativa_id,$auditoria->lider->id);

        setMessage('El registro auditoría se ha concluido y enviado a revisión.');


        return  redirect()->route('agregaracciones.index');
    }


}
