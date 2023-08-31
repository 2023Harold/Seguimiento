<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaAccion;
use App\Models\Recomendaciones;
use Illuminate\Http\Request;

class RecomendacionesAtencionController extends Controller
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
        $accion=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;
        $recomendacion=new Recomendaciones();
        //$responsable = $accion->analista;
        //dd($accion->analista);
        /*$usuariodirectorio=UserDirectorio::where('entidad_fiscalizable_id',$auditoria->entidad_fiscalizable_id)
                                           ->where('cargo_asociado', 'Contralor Interno')
                                           ->where('siglas_cargo_asociado', 'OIC')
                                           ->where('estatus', 'Activo')->first();
        $nombreuseroic=null;
        if (!empty($usuariodirectorio)) {
            $nombreuseroic=$usuariodirectorio->name.' '.$usuariodirectorio->primer_apellido.' '.$usuariodirectorio->segundo_apellido;
        }*/     

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
