<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaAccion;
use App\Models\SolicitudesAclaracion;
use Illuminate\Http\Request;

class SolicitudesAclaracionAnalisisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show(SolicitudesAclaracion $solicitud)
    {
        $accion=AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;
        return view('solicitudesaclaracionanalisis.show',compact('solicitud','accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SolicitudesAclaracion $solicitud)
    {
        $accion=AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;

        return view('solicitudesaclaracionanalisis.form',compact('solicitud','accion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SolicitudesAclaracion $solicitud)
    {
        $solicitud->update($request->all());
        setMessage("Se ha actualizado el análisis.");

        return redirect()->route('solicitudesaclaracionatencion.index');
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
