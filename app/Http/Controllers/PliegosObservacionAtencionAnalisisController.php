<?php

namespace App\Http\Controllers;
use App\Models\AuditoriaAccion;
use App\Models\PliegosObservacion;
use Illuminate\Http\Request;

class PliegosObservacionAtencionAnalisisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PliegosObservacion $pliegosobservacion)
    {
        $accion=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $auditoria=$accion->auditoria;

        return view('pliegosatencionanalisis.show',compact('pliegosobservacion','accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PliegosObservacion $pliegosobservacion)
    {
        $accion=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $auditoria=$accion->auditoria;

        return view('pliegosatencionanalisis.form',compact('pliegosobservacion','accion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PliegosObservacion $pliegosobservacion)
    {
        $pliegosobservacion->update($request->all());
        setMessage("Se ha actualizado el análisis.");

        return redirect()->route('pliegosobservacionatencion.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
