<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\PliegosObservacion;
use App\Models\PliegosDocumento;
use App\Models\Revisiones;
use Illuminate\Http\Request;

class RespuestaComentariosPliegosController extends Controller
{
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
        
     $documento = $this->setQuery($request)->paginate(50);   
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
    public function edit(Revisiones $comentario,Request $request)
    {
    //    dd($documento);
        setSession('comentarioAsis_id',$comentario->id);
        // dd($comentario);
        $pliegos= PliegosObservacion::find(getSession('pliegosobservacionatencion_id'));        
        $accion=$pliegos->accion;
        $accion2 = 'Atender';
        $acciones=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $tipo = $comentario->tipo; // tipo para identificar el ar

        return view('respuestacomentariospliegos.form',compact('pliegos','accion','accion2','comentario','tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $comentario = Revisiones::find(getSession('comentarioAsis_id'));        
        $pliegos = PliegosObservacion::find(getSession('pliegosobservacionatencion_id'));
        $pliegos->update($request->all());        
        $comentario->update(['estatus'=>'Atendido']);        
        $accion2 = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        
        $request->merge([
            'id_revision'=>$comentario->id,
            'de_usuario_id'=>auth()->user()->id,
            'para_usuario_id'=>intval($accion2->analista_asignado_id),
            'accion'=>'Pliego de Observación',
            'accion_id'=>$accion2->id,            
            'usuario_creacion_id'=>auth()->user()->id,
        ]);                  
        Revisiones::create($request->all());        
        setMessage('se atendio el comentario correctamente.');        
        
        return view('layouts.close');
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
