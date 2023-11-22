<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\SolicitudesAclaracion;
use App\Models\SolicitudesAclaracionAnexos;
use Illuminate\Http\Request;
use File;

class SolicitudesAclaracionAnexoController extends Controller
{
    protected $model;
    public function __construct(SolicitudesAclaracionAnexos $model)
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
        $anexos = SolicitudesAclaracionAnexos::where('solicitudaclaracion_id',getSession('solicitudesaclaracionatencion_id'))->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $solicitud = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));

        return view('solicitudesaclaracionanexo.index', compact('anexos','auditoria','accion','solicitud','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anexo = new SolicitudesAclaracionAnexos();
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $solicitud = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));

        return view('solicitudesaclaracionanexo.form', compact('anexo','auditoria','solicitud','accion'));
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
        $solicitud = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));

        $request->merge([
            'solicitudaclaracion_id' => getSession('solicitudesaclaracionatencion_id'),
            'usuario_creacion_id' => auth()->id(),
        ]);

        SolicitudesAclaracionAnexos::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('solicitudesaclaracionanexos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SolicitudesAclaracion $anexo)
    {        
        $anexos = SolicitudesAclaracionAnexos::where('solicitudaclaracion_id',$anexo->id)->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $solicitud = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));

        return view('solicitudesaclaracionanexo.show', compact('anexos','auditoria','accion','solicitud'));
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
    public function destroy(SolicitudesAclaracionAnexos $anexo)
    {
        File::delete($anexo->archivo);
        $anexo->delete();
        $this->actualizaProgresivo();
        setMessage('El registro ha sido eliminado');

        return redirect()->route('solicitudesaclaracionanexos.index');    
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

    public function anexos(SolicitudesAclaracion $solicitud)
    {
        $anexos = SolicitudesAclaracionAnexos::where('solicitudaclaracion_id',$solicitud->id)->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $solicitud = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));

        return view('solicitudesaclanexos.show', compact('anexos','auditoria','accion','solicitud'));
    }
}
