<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\TurnoContestacion;
use App\Models\TurnoOIC;
use Illuminate\Http\Request;

class TurnoContestacionOICController extends Controller
{
    protected $model;
    public function __construct(TurnoContestacion $model)
       {
           $this -> model = $model;
       }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         //$contestturno= getSession('contestturnoui_id');
        $turno = TurnoOIC::find(getSession('contestturnooic_id'));
        $auditoria = Auditoria::find($turno->auditoria_id);
        $request['turno_id'] = $turno->id;
        $contestaciones = $this->setQuery($request)->paginate(25);


        return view ('turnocontestacionesoic.index', compact('request', 'auditoria','turno','contestaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $turno=TurnoOIC::find(getSession('contestturnooic_id'));
      $auditoria = Auditoria ::find($turno->auditoria_id);
      $turnocontestacionesoic = new TurnoContestacion();

        return view('turnocontestacionesoic.form', compact('auditoria','turno','turnocontestacionesoic'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        mover_archivos($request, ['archivo_contestacion']);
        $request['turno_id']= getSession('contestturnooic_id');
        $request['tipo_turno']= 'TurnoOIC';
        $request['usuario_creacion_id'] = auth()->user()->id;
        TurnoContestacion::create($request->all());

        setMessage('Contestación registrada correctamente');

        return redirect()->route('turnocontestacionesoic.index');
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
    public function edit(TurnoContestacion $turnocontestacionesoic)
    {
      $turno=TurnoOIC::find(getSession('contestturnooic_id'));
      $auditoria = Auditoria :: find($turno->auditoria_id);

      return view('turnocontestacionesoic.form', compact('auditoria','turno','turnocontestacionesoic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TurnoContestacion $turnocontestacionesoic)
    {
        $turno=TurnoOIC::find(getSession('contestturnooic_id'));
        $auditoria = Auditoria :: find($turno->auditoria_id);
        $request['usuario_modificacion_id'] = auth()->user()->id;

        $turnocontestacionesoic->update($request->all());

        setMessage('Contestación actualizada correctamente');

        return redirect()->route('turnocontestacionesoic.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TurnoContestacion $turnocontestacionesoic)
    {
        $turnocontestacionesoic->delete();
         setMessage('Contestación eliminada correctamente');

         return redirect()->route('turnocontestacionesoic.index');
    }

    public function setQuery(Request $request)
    {
        $query = $this->model;
        $query = $query->orderBy('id','DESC');
        $query = $query->where('turno_id', getSession('contestturnooic_id'));
        $query = $query->where('tipo_turno', 'TurnoOIC');

        return $query;
    }
}
