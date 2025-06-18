<?php

namespace App\Http\Controllers\PliegosObservacion;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\PliegosAnexos;
use App\Models\PliegosObservacion;
use Illuminate\Http\Request;
use File;

class PliegosObservacionAnexoController extends Controller
{
    protected $model;

    public function __construct(PliegosAnexos $model)
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
        $anexos = $this->setQuery($request)->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $pliegosobservacion= PliegosObservacion::find(getSession('pliegosobservacionatencion_id'));
        //d($pliegosobservacion);

        return view('pliegosobservacionanexos.index', compact('anexos','auditoria','accion','pliegosobservacion','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anexo = new PliegosAnexos();
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $pliegosobservacion = PliegosObservacion::find(getSession('pliegosobservacionatencion_id'));

        return view('pliegosobservacionanexos.form', compact('anexo','auditoria','pliegosobservacion','accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        mover_archivos($request, ['archivo']);
        $pliegosobservacion = PliegosObservacion::find(getSession('pliegosobservacionatencion_id'));

        $request->merge([
            'pliegosobservacion_id' => getSession('pliegosobservacionatencion_id'),
            'usuario_creacion_id' => auth()->id(),
        ]);
        PliegosAnexos::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('pliegosobservacionanexos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PliegosObservacion $anexo)
    {
        $anexos = PliegosAnexos::where('pliegosobservacion_id', getSession('pliegosobservacionatencion_id'))->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $pliegosobservacion= PliegosObservacion::find(getSession('pliegosobservacionatencion_id'));
        //d($pliegosobservacion);

        return view('pliegosobservacionanexos.show', compact('anexos','auditoria','accion','pliegosobservacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PliegosAnexos $anexo)
    {
        File::delete($anexo->archivo);
        $anexo->delete();

        $this->actualizaProgresivo();
        setMessage('El registro ha sido eliminado');

        return redirect()->route('pliegosobservacionanexos.index');
    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->where('pliegosobservacion_id', getSession('pliegosobservacionatencion_id'))->orderBy('consecutivo');

        if ($request->filled('consecutivo')) {
            $query = $query->where('consecutivo',$request->consecutivo);
        }
        if ($request->filled('nombre_archivo')) {
           $query = $query->whereRaw('LOWER(nombre_archivo) LIKE (?) ',["%{$request->nombre_archivo}%"]);
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

    public function anexos(PliegosObservacion $pliegosobservacion)
    {
        $anexos = PliegosAnexos::where('pliegosobservacion_id',$pliegosobservacion->id)->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
       

        return view('pliegosobsanexos.show', compact('anexos','auditoria','accion','pliegosobservacion'));
    }
}
