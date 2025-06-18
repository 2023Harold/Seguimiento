<?php

namespace App\Http\Controllers\Recomendaciones;

use App\Http\Controllers\Controller;
use App\Models\Recomendaciones;
use App\Models\RecomendacionesDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecomendacionesAtencionDocumentosController extends Controller
{
    protected $model;

    public function __construct(RecomendacionesDocumento $model)
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
        $documentos = $this->setQuery($request)->paginate(50);

        return view('recomendacionesatenciondocumentos.index', compact('documentos', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documento = new RecomendacionesDocumento();
             
        $accion = 'Agregar';

        return view('recomendacionesatenciondocumentos.form', compact('documento', 'accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        
        $request->merge([
            'recomendacion_id' => getSession('recomendacioncalificacion_id'),
        ]);
        //$ruta = env('APP_RUTA_MINIO').'Auditorias/' . strtoupper(Str::slug($recomendacion->accion->auditoria->numero_auditoria)).'/Documentos';
        //mover_archivos_minio($request, ['archivo'], null, $ruta);
        //mover_archivos($request, ['archivo']);
        RecomendacionesDocumento::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('recomendacionesdocumentos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Recomendaciones $documento)
    {
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        $accion=$recomendacion->accion;
        $auditoria=$accion->auditoria;        
        
        return view('recomendacionesatenciondocumentos.show',compact('recomendacion','accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Recomendaciones $documento)
    {
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        $accion=$recomendacion->accion;
        $auditoria=$accion->auditoria;

        return view('recomendacionesatenciondocumentos.form',compact('recomendacion','accion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recomendaciones $documento)
    {
        $documento->update($request->all());
        setMessage("Se ha actualizado el listado de documentos.");

        return redirect()->route('recomendacionesatencion.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecomendacionesDocumento $documento)
    {
        $documento->delete();
        $this->actualizaProgresivo();
        setMessage('El registro ha sido eliminado');

        return redirect()->route('recomendacionesdocumentos.index');
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
}
