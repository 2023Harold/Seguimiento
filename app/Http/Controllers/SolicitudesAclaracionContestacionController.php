<?php

namespace App\Http\Controllers;

use App\Models\SolicitudesAclaracion;
use Illuminate\Http\Request;

class SolicitudesAclaracionContestacionController extends Controller
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
        $solicitud = new SolicitudesAclaracion();
             
        $accion = 'Registrar';

        return view('solicitudesaclaracioncontestacion.form', compact('solicitud', 'accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        mover_archivos($request, ['oficio_atencion']);
        $request->merge([
            'accion_id' => getSession('solicitudauditoriaaccion_id'),
            'usuario_creacion_id' => auth()->user()->id,
        ]);
     
        SolicitudesAclaracion::create($request->all());
        
        setMessage('El registro ha sido agregado');

        return redirect()->route('solicitudesaclaracioncontestacion.show',0); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($solicitud)
    {
        return view('solicitudesaclaracioncontestacion.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SolicitudesAclaracion $solicitud)
    {
        $accion = 'Registrar';

        return view('solicitudesaclaracioncontestacion.form', compact('solicitud', 'accion'));
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
        mover_archivos($request, ['oficio_atencion'],$solicitud);
        $request->merge([
            'accion_id' => getSession('solicitudauditoriaaccion_id'),
            'usuario_creacion_id' => auth()->user()->id,
        ]);     
        
        setMessage('El registro ha sido modificado');

        return redirect()->route('solicitudesaclaracioncontestacion.show',0); 
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
