<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Recomendaciones;
use App\Models\RecomendacionesDocumento;
use App\Models\Revisiones;
use Illuminate\Http\Request;

class RespuestaComentariosRecomendacionesController extends Controller
{

    public function __construct(RecomendacionesDocumento $model)
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
    public function store()
    {
      
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
    public function edit(Recomendaciones $documento,Revisiones $comentario,Request $request)
    {
        // dd($comentario);
        setSession('comentarioAsis_id',$comentario->id);
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        $accion=$recomendacion->accion;
        $accion2 = 'Atender';
        // dd($accion);
        $acciones=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $tipo = $comentario->tipo; // tipo para identificar el ar
        // $auditoria=$accion2->auditoria;


        return view('respuestacomentarios.form',compact('recomendacion','accion','accion2','comentario','tipo'));
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
        $recomendacion = Recomendaciones::find(getSession('recomendacioncalificacion_id'));
        $recomendacion->update($request->all());
        // dd($comentario,$request);
        $comentario->update(['estatus'=>'Atendido']);        
        $accion2 = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        //   dd($accion2);
        $request->merge([
            'id_revision'=>$comentario->id,
            'de_usuario_id'=>auth()->user()->id,
            'para_usuario_id'=>intval($accion2->analista_asignado_id),
            'accion'=>'Recomendación',
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
