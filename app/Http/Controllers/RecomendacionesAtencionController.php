<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecomendacionesRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\Recomendaciones;
use App\Models\User;
use Illuminate\Http\Request;

class RecomendacionesAtencionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $recomendaciones = Recomendaciones::where('accion_id',getSession('recomendacionesauditoriaaccion_id'))->get();
           
        return view('recomendacionesatencion.index',compact('recomendaciones','auditoria','accion','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accion=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;
        $recomendacion=new Recomendaciones();
      

        return view('recomendacionesatencion.form',compact('recomendacion','accion','auditoria'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        mover_archivos($request, ['oficio_contestacion']);
        //$firmante = User::where('unidad_administrativa_id', '122000')->first();
        $request->merge([
            'auditoria_id' => $auditoria->id,
            'usuario_creacion_id' => auth()->id(),
            'accion_id'=>getSession('recomendacionesauditoriaaccion_id'),
            'consecutivo' => 1,
            'departamento_responsable_id'=> auth()->user()->unidad_administrativa_id,
        ]);
        $recomendacion= Recomendaciones::create($request->all());
        setSession('recomendacioncalificacion_id',$recomendacion->id);

        setMessage('Se han guardado los datos correctamente');

        return redirect()->route('recomendacionescalificacion.edit',$recomendacion);        
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
    public function edit(Recomendaciones $recomendacion)
    {
        $accion=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;     

        return view('recomendacionesatencion.form',compact('recomendacion','accion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recomendaciones $recomendacion)
    {
           
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        mover_archivos($request, ['oficio_remision'], $recomendacion);
     
        $request->merge([
            'auditoria_id' => $auditoria->id,
            'usuario_creacion_id' => auth()->id(),
            'accion_id'=>getSession('recomendacionesauditoriaaccion_id'),
            'consecutivo' => 1,
            'departamento_responsable_id'=> auth()->user()->unidad_administrativa_id,
        ]);
         $recomendacion->update($request->all());
         setSession('recomendacioncalificacion_id',$recomendacion->id);

       

        setMessage('Se han guardado los datos correctamente');

     
        return redirect()->route('recomendacionescalificacion.edit',$recomendacion);
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
