<?php

namespace App\Http\Controllers\SolicitudAclaraciones;

use App\Http\Controllers\Controller;
use App\Models\SolicitudesAclaracion;
use App\Models\SolicitudesAclaracionDocumento;
use Illuminate\Http\Request;

class SolicitudesAclaracionDocumentosController extends Controller
{
    protected $model;

    public function __construct(SolicitudesAclaracionDocumento $model)
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

        return view('solicitudesaclaraciondocumentos.index', compact('documentos', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documento = new SolicitudesAclaracionDocumento();

        $accion = 'Agregar';

        return view('solicitudesaclaraciondocumentos.form', compact('documento', 'accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $solicitud = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));

        $request->merge([
            'solicitudaclaracion_id' => getSession('solicitudesaclaracionatencion_id'),
        ]);
        //$ruta = env('APP_RUTA_MINIO').'Auditorias/' . strtoupper(Str::slug($recomendacion->accion->auditoria->numero_auditoria)).'/Documentos';
        //mover_archivos_minio($request, ['archivo'], null, $ruta);
        //mover_archivos($request, ['archivo']);
        SolicitudesAclaracionDocumento::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('solicitudesaclaraciondocumentos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SolicitudesAclaracion $documento)
    {
        $solicitud = $documento;
        $accion=$solicitud->accion;
        $auditoria=$accion->auditoria;

        return view('solicitudesaclaraciondocumentos.show',compact('solicitud','accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SolicitudesAclaracion $documento)
    {
        $solicitud = $documento;
        $accion=$solicitud->accion;
        $auditoria=$accion->auditoria;

        return view('solicitudesaclaraciondocumentos.form',compact('solicitud','accion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SolicitudesAclaracion $documento)
    {
      $documento->update($request->all());
      setMessage("Se ha actualizado el listado de documentos.");

      return redirect()->route('solicitudesaclaracionatencion.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SolicitudesAclaracionDocumento $documento)
    {
        $documento->delete();
        $this->actualizaProgresivo();
        setMessage('El registro ha sido eliminado');

        return redirect()->route('solicitudesaclaraciondocumentos.index');
    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->where('solicitudaclaracion_id', getSession('solicitudesaclaracionatencion_id'))->orderBy('consecutivo');

        if ($request->filled('consecutivo')) {
            $query = $query->where('consecutivo',$request->consecutivo);
        }
        if ($request->filled('nombre_archivo')) {
           $query = $query->whereRaw('LOWER(nombre_documento) LIKE (?) ',["%{$request->nombre_archivo}%"]);
        }
        return $query;
    }

    public function actualizaProgresivo()
    {
        $numeroSiguiente = 1;
        $modelName = $this->model;
        $er_records = $modelName::where('solicitudaclaracion_id', getSession('solicitudesaclaracionatencion_id'));
        $er_records = $er_records->orderBy('id')->get();
        foreach ($er_records as $er_record) {
            $er_record->update(['consecutivo' => $numeroSiguiente]);
            $numeroSiguiente++;
        }
    }
}
