<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Recomendaciones;
use App\Models\RecomendacionesContestacion;
use Illuminate\Http\Request;
use File;

class RecomendacionesAtencionContestacionController extends Controller
{
    protected $model;

    public function __construct(RecomendacionesContestacion $model)
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
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));

        return view('recomendacionesatencioncontestacion.index', compact('contestaciones','auditoria','accion','recomendacion','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contestacion = new RecomendacionesContestacion();
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));

        return view('recomendacionesatencioncontestacion.form', compact('contestacion','auditoria','recomendacion','accion'));
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
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        
        $request->merge([
            'recomendacion_id' => getSession('recomendacioncalificacion_id'),
            'usuario_creacion_id' => auth()->id(),
        ]);
       
        RecomendacionesContestacion::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('recomendacionescontestaciones.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Recomendaciones $contestacion)
    {
        $contestaciones = RecomendacionesContestacion::where('recomendacion_id',$contestacion->id)->paginate(10); 
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));

        return view('recomendacionesatencioncontestacion.show', compact('contestaciones','auditoria','accion','recomendacion'));        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RecomendacionesContestacion $contestacion)
    {        
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));

        return view('recomendacionesatencioncontestacion.form', compact('contestacion','auditoria','recomendacion','accion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecomendacionesContestacion $contestacion)
    {
        mover_archivos($request, ['oficio_contestacion'],$contestacion);        
        $request->merge([          
            'usuario_modificacion_id' => auth()->id(),
        ]);       
        $contestacion->update($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('recomendacionescontestaciones.index');        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecomendacionesContestacion $contestacion)
    {
        File::delete($contestacion->oficio_contestacion);
        $contestacion->delete();
        $this->actualizaProgresivo();
        setMessage('El registro ha sido eliminado');

        return redirect()->route('recomendacionescontestaciones.index');
    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->where('recomendacion_id', getSession('recomendacioncalificacion_id'))->orderBy('consecutivo');

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
        $er_records = $modelName::where('recomendacion_id', getSession('recomendacioncalificacion_id'));
        $er_records = $er_records->orderBy('id')->get();
        foreach ($er_records as $er_record) {
            $er_record->update(['consecutivo' => $numeroSiguiente]);
            $numeroSiguiente++;
        }
    }

    public function oficiosrecomendacion(Recomendaciones $recomendacion)
    {
        $contestaciones = RecomendacionesContestacion::where('recomendacion_id',$recomendacion->id)->paginate(10); 
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));

        return view('recomendacionesatencioncontestacionoficios.show', compact('contestaciones','auditoria','accion','recomendacion'));        

      
    }
}
