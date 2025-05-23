<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatalogoTipologia;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;



class TipologiaAccionesController extends Controller
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
    public function create(Auditoria $auditoria)
    {
        //dd($auditoria);

        $tipologia = new CatalogoTipologia();             
        $accion = 'Agregar';
        
 
        return view('tipologias.form', compact('tipologia','accion','auditoria'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
        // $accion = AuditoriaAccion::find(getSession('tipologiaacciones_id'));
        //    dd($accion);
        //dd($request);

        $auditoria = Auditoria::where('id',$request->tipo_auditoria_id)->first();
        dd($auditoria);
               
        $request['usuario_creacion_id'] = auth()->user()->id;

        
        // $request['tipologia_auditoria_id']=getSession('auditoria_id');
        $tipologia = CatalogoTipologia::create($request->all());
        return view('agregaracciones.index', compact('tipologia','auditoria'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Auditoria $auditoria)
    {
        // $auditoria = Auditoria::find(getSession('auditoria_id'));
        // dd($auditoria);
        
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
