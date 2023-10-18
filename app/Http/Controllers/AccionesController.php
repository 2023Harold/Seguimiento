<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditoriaAccionRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\CatalogoTipoAuditoria;
use App\Rules\RecomendacionRegistroRule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AccionesController extends Controller
{
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $acciones =  $this->setQuery($request)->orderBy('consecutivo')->paginate(30);     
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Todas', 0);        
        $monto_aclarar=$this->setQuery($request)->orderBy('monto_aclarar');
       
        return view('seguimientoauditoriaaccion.index', compact('acciones', 'request', 'auditoria','tiposaccion','monto_aclarar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $numeroconsecutivo=AuditoriaAccion::where('segauditoria_id',$auditoria->id)->get()->count()+1;
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Seleccionar una opción', '');
        $accion = new AuditoriaAccion();
        $actosfiscalizacion=CatalogoTipoAuditoria::whereIn('id',[1,2,3,4])->pluck('descripcion', 'id')->prepend('Seleccionar una opción', '');

        return view('seguimientoauditoriaaccion.form', compact('numeroconsecutivo','tiposaccion','accion','auditoria','actosfiscalizacion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $this->setValidator($request)->validate(); 
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $this->normalizarDatos($request);       
        mover_archivos($request, ['cedula'], null);
        $accion  = AuditoriaAccion::create($request->all());        
        setMessage('El registro ha sido agregado');

        return redirect()->route('seguimientoauditoriaacciones.index');
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
        return view('seguimientoauditoriaaccion.showacciones',compact('accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AuditoriaAccion $accion)
    {        
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $numeroconsecutivo=$accion->consecutivo;
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id');
        $actosfiscalizacion=CatalogoTipoAuditoria::whereIn('id',[1,2,3,4])->pluck('descripcion', 'id')->prepend('Seleccionar una opción', '');
        

        return view('seguimientoauditoriaaccion.form', compact('numeroconsecutivo','tiposaccion','accion','auditoria','actosfiscalizacion'));
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
       
        setMessage('La acción se ha modificado correctamente.');

        return redirect()->route('seguimientoauditoriaacciones.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuditoriaAccion $accion)
    {
        //
    }

    public function setQuery(Request $request)
    {
         $query = $this->model;

         $query = $query->where('segauditoria_id',getSession('auditoria_id'));
                
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
        $request['segauditoria_id'] = getSession('auditoria_id');
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

        $er_records = $modelName::where('auditoria_id', getSession('auditoria_id'));

        $er_records = $er_records->orderBy('id')->get();

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

        return view('seguimientoauditoriaaccion.show',compact('accion','auditoria'));
    }
}
