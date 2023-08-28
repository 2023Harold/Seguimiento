<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuditoriaAccionRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccionesController extends Controller
{
    protected $model;

    public function __construct(AuditoriaAccion $model)
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
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $acciones =  $this->setQuery($request)->orderBy('id')->paginate(30);     
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

        return view('seguimientoauditoriaaccion.form', compact('numeroconsecutivo','tiposaccion','accion','auditoria'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuditoriaAccionRequest $request)
    {
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
    public function edit(AuditoriaAccion $accion)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $numeroconsecutivo=$accion->consecutivo;
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id');
        

        return view('seguimientoauditoriaaccion.form', compact('numeroconsecutivo','tiposaccion','accion','auditoria'));
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
        $request['tipo'] = $tiposaccion->descripcion;
        $request['segauditoria_id'] = getSession('auditoria_id');
        $request['usuario_actualizacion_id'] = auth()->id();
        $request['accion'] = str_replace("\r\n", "</br>",$request->accion);

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
}
