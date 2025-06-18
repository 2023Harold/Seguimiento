<?php

namespace App\Http\Controllers\AcuerdoConclusion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcuerdoConclusion;

class AcuerdoConclusionAcuseCPController extends Controller
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

        mover_archivos($request, ['oficio_acuerdo'], null);
        $request['usuario_creacion_id'] = auth()->user()->id;
        $request['auditoria_id']=getSession('radicacion_auditoria_id');     
        $acuerdoconclusion = AcuerdoConclusion::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AcuerdoConclusion $acuerdoconclusion)
    {

        $auditoria = $acuerdoconclusion->auditoria; 
        // $tipo=$acuerdoconclusion->tipo;                 
        return view('acuerdoconclusionacusecp.show', compact('auditoria', 'acuerdoconclusion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AcuerdoConclusion $acuerdoconclusion)
    {
        $auditoria=$acuerdoconclusion->auditoria;
        $tipo=$acuerdoconclusion->tipo;                 
        return view('acuerdoconclusionacusecp.form', compact('auditoria','acuerdoconclusion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AcuerdoConclusion $acuerdoconclusion)
    {
        $request['usuario_modificacion_id'] = auth()->id();       
        mover_archivos($request, ['oficio_recepcion','oficio_acuerdo','oficio_acuse']);
        $acuerdoconclusion->update($request->all());
        setMessage('Los acuses se han guardado correctamente');

        return redirect()->route('acuerdoconclusion.index');
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
