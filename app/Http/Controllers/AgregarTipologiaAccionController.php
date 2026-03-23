<?php

namespace App\Http\Controllers;

use App\Models\CatalogoTipologia;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\TipologiasAccion;
use App\Models\User;
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
        $auditoria = Auditoria::find(getSession('auditoriacp_id'));
        $accion = AuditoriaAccion::findOrFail($request->accion_id);
        
        $t = CatalogoTipologia::findOrFail($request->tipologia_id);

        TipologiasAccion::create([
            'accion_id' => $accion->id,
            'auditoria_id' => $auditoria->id,
            'tipologia' => $t->tipologia,
            'usuario_creacion_id' => auth()->id(),
        ]);

        setMessage('Se ha agregado la tipología correctamente');

        return view('layouts.closeAcciones');
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
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Seleccionar una opción', '');
        $tipologias= CatalogoTipologia::all()->pluck('tipologia', 'id')->prepend('Seleccionar una opción', '');
        $tipologia = new TipologiasAccion();
        $acc = 'Agregar';
 
        return view('agregartipologiaaccion.form', compact('acc','accion','auditoria','tipologia','tipologias','tiposaccion'));
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
        dd("404 Update Tipologia");    
        $request['usuario_creacion_id'] = auth()->id();      
        $request['tipo_auditoria_id'] = $accion->auditoria->tipo_auditoria_id;       
        $tipologia= TipologiasAccion :: create ($request->all());                   
        
        setMessage('Se ha agregado la tipología correctamente');

        return view('layouts.close');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipologiasAccion $accion)
    {
        //
    }

    public function eliminar(TipologiasAccion $tipologia){
        $tipologia->delete();
       
        setMessage('Se ha eliminado la tipologia correctamente.');
        
        return redirect()->route('agregaracciones.create');
    }
}
