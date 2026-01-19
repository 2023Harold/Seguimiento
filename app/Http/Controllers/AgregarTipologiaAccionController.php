<?php

namespace App\Http\Controllers;

use App\Models\CatalogoTipologia;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use Illuminate\Http\Request;

class AgregarTipologiaAccionController extends Controller
{
    protected $model;

    public function __construct(AuditoriaAccion $model)
    {
             $this->model = $model;
    }
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
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd('');
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
    public function edit(AuditoriaAccion $accion)
    {
        $auditoria= Auditoria::find(getSession('auditoria_id'));
        
        
        //dd($accion->auditoria);
        // $auditoria=$tipologia->auditoria;                  
        //$accion = 'Agregar';
        
 
        return view('agregartipologiaaccion.form', compact('accion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Auditoriaaccion $accion)
    {
        // dd($request);    
        $request['usuario_modificacion_id'] = auth()->id();      
        $request['tipo_auditoria_id'] = $accion->auditoria->tipo_auditoria_id;       
        $n_tipologia= CatalogoTipologia :: create ($request->all());                   
        
        $request2 = new Request();
        $request2 ['tipologia_id']= $n_tipologia->id;
        $accion->update($request2->all());        
        // $tipologia = new CatalogoTipologia();
        setMessage('Se ha agregado la tipología correctamente');

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
