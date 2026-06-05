<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\TurnoAcuseArchivo;
use App\Models\TurnoContestacion;
use Illuminate\Http\Request;

class TurnoContestacionArchController extends Controller
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
        $turno = TurnoAcuseArchivo::find(getSession('contestturnoarch_id'));
        $auditoria = Auditoria::find($turno->auditoria_id);
        $request['turno_id'] = $turno->id;
        $contestaciones = $this->setQuery($request)->paginate(25);


        return view ('turnocontestacionesarchivo.index', compact('request', 'auditoria','turno','contestaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $turno=TurnoAcuseArchivo::find(getSession('contestturnoarch_id'));
      $auditoria = Auditoria ::find($turno->auditoria_id);
      $turnocontestacionesarc = new TurnoContestacion();

      return view('turnocontestacionesarchivo.form', compact('auditoria','turno','turnocontestacionesarc'));
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
        $request['turno_id']= getSession('contestturnoarch_id');
        $request['tipo_turno']= 'TurnoAcuseArchivo';
        $request['usuario_creacion_id'] = auth()->user()->id;
        TurnoContestacion::create($request->all());

        setMessage('Contestación registrada correctamente');

        return redirect()->route('turnocontestacionesarc.index');
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
    public function edit(TurnoContestacion $turnocontestacionesarc)
    {
      $turno=TurnoAcuseArchivo::find(getSession('contestturnoarch_id'));
      $auditoria = Auditoria :: find($turno->auditoria_id);

      return view('turnocontestacionesarchivo.form', compact('auditoria','turno','turnocontestacionesarc'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TurnoContestacion $turnocontestacionesarc)
    {
        $turno=TurnoAcuseArchivo::find(getSession('contestturnoarch_id'));
        $auditoria = Auditoria :: find($turno->auditoria_id);
        $request['usuario_modificacion_id'] = auth()->user()->id;

        $turnocontestacionesarc->update($request->all());

        setMessage('Contestación actualizada correctamente');

        return redirect()->route('turnocontestacionesarc.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TurnoContestacion $turnocontestacionesarc)
    {
        $turnocontestacionesarc->delete();
         setMessage('Contestación eliminada correctamente');

         return redirect()->route('turnocontestacionesarc.index');
    }

     public function setQuery(Request $request)
    {
        $query = $this->model;
        $query = $query->orderBy('id','DESC');
        $query = $query->where('turno_id', getSession('contestturnoarch_id'));
        $query = $query->where('tipo_turno', 'TurnoAcuseArchivo');

        return $query;
    }
}
