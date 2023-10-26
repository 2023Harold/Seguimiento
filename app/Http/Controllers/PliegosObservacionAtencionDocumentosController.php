<?php

namespace App\Http\Controllers;

use App\Models\PliegosDocumento;
use App\Models\PliegosObservacion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PliegosObservacionAtencionDocumentosController extends Controller
{
    protected $model;
    public function __construct(PliegosDocumento $model)
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

        return view('pliegosobservaciondocumentos.index', compact('documentos', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documento = new PliegosDocumento();

        $accion = 'Agregar';

        return view('pliegosobservaciondocumentos.form', compact('documento', 'accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pliegosobservacion = PliegosObservacion::find(getSession('pliegosobservacionatencion_id'));

        $request->merge([
            'pliegosobservacion_id' => getSession('pliegosobservacionatencion_id'),
        ]);
        //$ruta = env('APP_RUTA_MINIO').'Auditorias/' . strtoupper(Str::slug($recomendacion->accion->auditoria->numero_auditoria)).'/Documentos';
        //mover_archivos_minio($request, ['archivo'], null, $ruta);
        //mover_archivos($request, ['archivo']);
        PliegosDocumento::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('pliegosobservaciondocumentos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PliegosObservacion $documento)
    {
        $pliegos = $documento;
        $accion=$pliegos->accion;
        $auditoria=$accion->auditoria;

        return view('pliegosobservaciondocumentos.show',compact('pliegos','accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PliegosObservacion $documento)
    {
        $pliegos = $documento;
        $accion=$pliegos->accion;
        $auditoria=$accion->auditoria;

        return view('pliegosobservaciondocumentos.form',compact('pliegos','accion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PliegosObservacion $documento)
    {
      $documento->update($request->all());
      setMessage("Se ha actualizado el listado de documentos.");

      return redirect()->route('pliegosobservacionatencion.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PliegosDocumento $documento)
    {
        $documento->delete();
        $this->actualizaProgresivo();
        setMessage('El registro ha sido eliminado');

        return redirect()->route('pliegosobservaciondocumentos.index');
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
}
