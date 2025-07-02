<?php

namespace App\Http\Controllers\Revisiones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Revisiones;
use Illuminate\Http\Request;

class RevisionesPliegosAtencionController extends Controller
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
        $comentario = new Revisiones();
             
        $accion = 'Agregar';

        return view('comentarios.revisionespliegosatencion.form', compact('comentario', 'accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comentario = Revisiones::find(getSession('comentario_id'));       
        $comentario->update(['estatus'=>'Atendido']);
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $request->merge([
            'id_revision'=>getSession('comentario_id'),
            'de_usuario_id'=>auth()->user()->id,
            'para_usuario_id'=>intval($accion->analista_asignado_id),
            'accion'=>'Pliego de Observación',
            'accion_id'=>$accion->id,            
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
    public function edit(Revisiones $comentario, Request $request)
    {
        $accion = 'Atender';
        $acciones=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $tipo = $comentario->tipo; // tipo para identificar el archivo solo aplica para 
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('comentarios.revisionesatencion.form', compact('comentario', 'accion','tipo','auditoria','acciones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Revisiones $comentario,)
    { 
        $comentario->update(['estatus'=>'Atendido']);
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $request->merge([
            'id_revision'=>$comentario->id,
            'de_usuario_id'=>auth()->user()->id,
            'para_usuario_id'=>intval($accion->analista_asignado_id),
            'accion'=>'Recomendación',
            'accion_id'=>$accion->id,            
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

    private function mensajeComentario(String $nombre, String $puesto)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Se ha dado atención al comentario, realizando las modificaciones pertinentes según lo indicado.';    
        return $mensaje;
    }
}
