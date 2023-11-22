<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Recomendaciones;
use App\Models\RecomendacionesAnexos;
use Illuminate\Http\Request;
use File;


class RecomendacionesAnexoController extends Controller
{
    protected $model;

    public function __construct(RecomendacionesAnexos $model)
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
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));

        return view('recomendacionesanexos.index', compact('anexos','auditoria','accion','recomendacion','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anexo = new RecomendacionesAnexos();
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));

        return view('recomendacionesanexos.form', compact('anexo','auditoria','recomendacion','accion'));
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
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));

        $request->merge([
            'recomendacion_id' => getSession('recomendacioncalificacion_id'),
            'usuario_creacion_id' => auth()->id(),
        ]);
        RecomendacionesAnexos::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('recomendacionesanexos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Recomendaciones $anexo)
    {
        $anexos = RecomendacionesAnexos::where('recomendacion_id', getSession('recomendacioncalificacion_id'))->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $recomendacion= Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        //d($pliegosobservacion);

        return view('recomendacionesanexos.show', compact('anexos','auditoria','accion','recomendacion'));
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
    public function destroy(RecomendacionesAnexos $anexo)
    {
        File::delete($anexo->archivo);
        $anexo->delete();

        $this->actualizaProgresivo();
        setMessage('El registro ha sido eliminado');

        return redirect()->route('recomendacionesanexos.index');
    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->where('recomendacion_id', getSession('recomendacioncalificacion_id'))->orderBy('consecutivo');

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
        $er_records = $modelName::where('recomendacion_id', getSession('recomendacioncalificacion_id'));
        $er_records = $er_records->orderBy('id')->get();
        foreach ($er_records as $er_record) {
            $er_record->update(['consecutivo' => $numeroSiguiente]);
            $numeroSiguiente++;
        }
    }

    public function anexos(Recomendaciones $recomendacion)
    {
        $anexos = RecomendacionesAnexos::where('recomendacion_id',$recomendacion->id)->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
       

        return view('recomendacionanexos.show', compact('anexos','auditoria','accion','recomendacion'));
    }

}
