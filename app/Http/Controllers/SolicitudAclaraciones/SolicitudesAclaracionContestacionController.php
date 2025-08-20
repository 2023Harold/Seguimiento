<?php

namespace App\Http\Controllers\SolicitudAclaraciones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\SolicitudesAclaracion;
use App\Models\SolicitudesAclaracionContestacion;
use Illuminate\Http\Request;
use File;
use App\Models\FolioCrr;

class SolicitudesAclaracionContestacionController extends Controller
{
    protected $model;
    public function __construct(SolicitudesAclaracionContestacion $model)
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
        $accion = AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $solicitud= SolicitudesAclaracion::find(getSession('solicitudesaclaracioncalificacion_id'));
        $sol_contestacion = SolicitudesAclaracion::with(['contestaciones.remitentes'])->where('accion_id', getSession('solicitudesauditoriaaccion_id'))->get();
        //dd($sol_contestacion->toArray());

        return view('solicitudesaclaracioncontestacion.index', compact('contestaciones','auditoria','accion','solicitud','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contestacion = new SolicitudesAclaracionContestacion();
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $solicitud = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));
        $folios = FolioCrr::where('auditoria_id',getSession('auditoria_id'))->pluck('folio', 'id')->prepend('Seleccionar',null);

        return view('solicitudesaclaracioncontestacion.form', compact('contestacion','auditoria','solicitud','accion','folios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $folio = FolioCrr::find($request->foliocrr_id);
        mover_archivos($request, ['oficio_contestacion']);
        $solicitud = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));

        $request->merge([
            'solicitudaclaracion_id' => getSession('solicitudesaclaracionatencion_id'),
            'usuario_creacion_id' => auth()->id(),
            'fecha_oficio_contestacion'=>$folio->fecha_oficio_contestacion,
            'numero_oficio'=>$folio->numero_oficio,
            'nombre_remitente'=>$folio->nombre_remitente,
            'cargo_remitente'=>$folio->cargo_remitente,
            'fecha_recepcion_oficialia'=>$folio->fecha_recepcion_oficialia,
            'folio_correspondencia'=>$folio->folio,
            'fecha_recepcion_seguimiento'=>$folio->fecha_recepcion_us
        ]);

        SolicitudesAclaracionContestacion::create($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('solicitudesaclaracioncontestacion.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SolicitudesAclaracion $contestacion)
    {
        $contestaciones = SolicitudesAclaracionContestacion::where('solicitudaclaracion_id',$contestacion->id)->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $solicitud = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));
        
        $sol_contestacion = SolicitudesAclaracion::with(['contestaciones.remitentes'])->where('accion_id', getSession('solicitudesauditoriaaccion_id'))->get();
        // dd($sol_contestacion);

        return view('solicitudesaclaracioncontestacion.show', compact('contestaciones','auditoria','accion','solicitud', 'sol_contestacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SolicitudesAclaracionContestacion $contestacion)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $solicitud = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));
        $folios = FolioCrr::where('auditoria_id',getSession('auditoria_id'))->pluck('folio', 'id');

        return view('solicitudesaclaracioncontestacion.form', compact('contestacion','auditoria','solicitud','accion','folios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SolicitudesAclaracionContestacion $contestacion)
    {
        $folio = FolioCrr::find($request->foliocrr_id);
        mover_archivos($request, ['oficio_contestacion'],$contestacion);
        $request->merge([
            'usuario_modificacion_id' => auth()->id(),
            'fecha_oficio_contestacion'=>$folio->fecha_oficio_contestacion,
            'numero_oficio'=>$folio->numero_oficio,
            'nombre_remitente'=>$folio->nombre_remitente,
            'cargo_remitente'=>$folio->cargo_remitente,
            'fecha_recepcion_oficialia'=>$folio->fecha_recepcion_oficialia,
            'folio_correspondencia'=>$folio->folio,
            'fecha_recepcion_seguimiento'=>$folio->fecha_recepcion_us
        ]);
        $contestacion->update($request->all());
        $this->actualizaProgresivo();
        setMessage('El registro ha sido agregado');

        return redirect()->route('solicitudesaclaracioncontestacion.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SolicitudesAclaracionContestacion $contestacion)
    {
        File::delete($contestacion->oficio_contestacion);
        $contestacion->delete();
        $this->actualizaProgresivo();
        setMessage('El registro ha sido eliminado');

        return redirect()->route('solicitudesaclaracioncontestacion.index');
    }

    private function setQuery($request)
    {
        $query = $this->model;
        $query = $query->where('solicitudaclaracion_id', getSession('solicitudesaclaracionatencion_id'))->orderBy('consecutivo');

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
        $er_records = $modelName::where('solicitudaclaracion_id', getSession('solicitudesaclaracionatencion_id'));
        $er_records = $er_records->orderBy('id')->get();
        foreach ($er_records as $er_record) {
            $er_record->update(['consecutivo' => $numeroSiguiente]);
            $numeroSiguiente++;
        }
    }

    public function oficiossolicitudes(SolicitudesAclaracion $solicitud)
    {
        $contestaciones = SolicitudesAclaracionContestacion::where('solicitudaclaracion_id',$solicitud->id)->paginate(10);
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $solicitud = SolicitudesAclaracion::find(getSession('solicitudesaclaracionatencion_id'));

        return view('solicitudesaclaracioncontestacionoficios.show', compact('contestaciones','auditoria','accion','solicitud'));
    }

}
