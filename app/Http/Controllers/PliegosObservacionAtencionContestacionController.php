<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\PliegosContestacion;
use App\Models\PliegosObservacion;
use Illuminate\Http\Request;
use File;

class PliegosObservacionAtencionContestacionController extends Controller
{
    protected $model;

    public function __construct(PliegosContestacion $model)
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
        $contestaciones = $this->setQuery($request)->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $pliegosobservacion= PliegosObservacion::find(getSession('pliegosobservacioncalificacion_id'));

        return view('pliegosatencioncontestacion.index', compact('contestaciones','auditoria','accion','pliegosobservacion','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contestacion = new PliegosContestacion();
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $pliegosobservacion = PliegosObservacion::find(getSession('pliegosobservacionatencion_id'));

        return view('pliegosatencioncontestacion.form', compact('contestacion','auditoria','pliegosobservacion','accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        mover_archivos($request, ['oficio_contestacion']);
        $pliegosobservacion = PliegosObservacion::find(getSession('pliegosobservacionatencion_id'));

        $request->merge([
            'pliegosobservacion_id' => getSession('pliegosobservacionatencion_id'),
            'usuario_creacion_id' => auth()->id(),
        ]);
        PliegosContestacion::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('pliegosobservacionatencioncontestacion.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PliegosObservacion $contestacion)
    {
        $contestaciones = PliegosContestacion::where('pliegosobservacion_id',$contestacion->id)->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $pliegosobservacion = PliegosObservacion::find(getSession('pliegosobservacionatencion_id'));

        return view('pliegosatencioncontestacion.show', compact('contestaciones','auditoria','accion','pliegosobservacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PliegosContestacion $contestacion)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $pliegosobservacion = PliegosObservacion::find(getSession('pliegosobservacionatencion_id'));

        return view('pliegosatencioncontestacion.form', compact('contestacion','auditoria','pliegosobservacion','accion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PliegosContestacion $contestacion)
    {
        mover_archivos($request, ['oficio_contestacion'],$contestacion);
        $request->merge([
            'usuario_modificacion_id' => auth()->id(),
        ]);
        $contestacion->update($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('pliegosobservacionatencioncontestacion.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Responsecontestacion
     */
    public function destroy(PliegosContestacion $contestacion)
    {
        File::delete($contestacion->oficio_contestacion);
        $contestacion->delete();
        $this->actualizaProgresivo();
        setMessage('El registro ha sido eliminado');

        return redirect()->route('pliegosobservacionatencioncontestacion.index');
    }
    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->where('pliegosobservacion_id', getSession('pliegosobservacionatencion_id'))->orderBy('consecutivo');

        if ($request->filled('consecutivo')) {
            $query = $query->where('consecutivo',$request->consecutivo);
        }
        if ($request->filled('nombre_documento')) {
           $query = $query->whereRaw('LOWER(nombre_documento) LIKE (?) ',["%{$request->nombre_documento}%"]);
        }
        return $query;
    }

    public function actualizaProgresivo()
    {
        $numeroSiguiente = 1;
        $modelName = $this->model;
        $er_records = $modelName::where('pliegosobservacion_id', getSession('pliegosobservacionatencion_id'));
        $er_records = $er_records->orderBy('id')->get();
        foreach ($er_records as $er_record) {
            $er_record->update(['consecutivo' => $numeroSiguiente]);
            $numeroSiguiente++;
        }
    }

    public function oficiospliegosobservacion(PliegosObservacion $pliegosobservacion)
    {
        $contestaciones = PliegosContestacion::where('pliegosobservacion_id',$pliegosobservacion->id)->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));

        return view('pliegosatencioncontestacionoficios.show', compact('contestaciones','auditoria','accion','pliegosobservacion'));
    }


}
