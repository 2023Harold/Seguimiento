<?php

namespace App\Http\Controllers\Informe;

use App\Models\Auditoria;
use App\Models\InformePrimeraEtapa;
use App\Models\Movimientos;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InformeAcusesController extends Controller
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
    public function show(InformePrimeraEtapa $informe)
    {
        $tipo=$informe->tipo;                 
        $auditoria = Auditoria::find(getSession('auditoria_id'));        
       
        return view('informeacuses.show', compact('auditoria','informe','tipo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(InformePrimeraEtapa $informe)
    {
        $tipo=$informe->tipo;                 
        $auditoria = Auditoria::find(getSession('auditoria_id'));        
       
        return view('informeacuses.form', compact('auditoria','informe','tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InformePrimeraEtapa $informe)
    {
        mover_archivos($request, ['acuse_envio','acuse_notificacion']);
        $request['usuario_modificacion_id'] = auth()->user()->id;        
        $request['auditoria_id']=getSession('auditoria_id');            
        $informe->update($request->all());
        $auditoria = Auditoria::find(getSession('auditoria_id'));     


        setMessage("Los datos se han actualizado correctamente.");
        return redirect() -> route('informeprimeraetapa.index',compact('auditoria','informe'));

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
