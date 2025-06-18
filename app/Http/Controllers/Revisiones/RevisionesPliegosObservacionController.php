<?php

namespace App\Http\Controllers\Revisiones;

use App\Http\Controllers\Controller;
namespace App\Http\Controllers;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\PliegosObservacion;
use App\Models\Revisiones;
use Illuminate\Http\Request;

class RevisionesPliegosObservacionController extends Controller
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
    public function create(Request $request)
    {
        $comentario = new Revisiones();
        $accion = 'Agregar';
        
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $acciones=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $tipo = $request->query('tipo'); // tipo para identificar el archivo solo aplica para 

        //return view('revisionespliegosatencion.form', compact('comentario', 'accion','auditoria','acciones','tipo'));
        return view('comentarios.revisiones.form', compact('comentario', 'accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $accion = AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $request->merge([
            'de_usuario_id'=>auth()->user()->id,
            'para_usuario_id'=>intval($accion->analista_asignado_id),
            'accion'=>'Pliegos de Observación',
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
    public function show($id)
    {
        return view('comentarios.revisiones.show', compact('comentario'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //setSession('comentario_id',$comentario->id);

        return redirect()->route('revisionespliegosatencion.create');
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
