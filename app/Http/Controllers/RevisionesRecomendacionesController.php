<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaAccion;
use App\Models\Recomendaciones;
use App\Models\Revisiones;
use Illuminate\Http\Request;

class RevisionesRecomendacionesController extends Controller
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
        //dd('hola');
        $comentario = new Revisiones();
             
        $accion = 'Agregar';

        return view('revisiones.form', compact('comentario', 'accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $request->merge([
            'de_usuario_id'=>auth()->user()->id,
            'para_usuario_id'=>intval($accion->analista_asignado_id),
            'accion'=>'Recomendación',
            'accion_id'=>$accion->id,
            'estatus'=>'Pendiente',
            'usuario_creacion_id'=>auth()->user()->id,
        ]);        
        Revisiones::create($request->all());        
        setMessage('se ha agregado el comentario correctamente.');

        return view('layouts.close');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Revisiones $comentario)
    {
        return view('revisiones.show', compact('comentario'));
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
    public function destroy($id)
    {
        //
    }
}
