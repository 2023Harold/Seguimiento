<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\SolicitudesAclaracion;
use Illuminate\Http\Request;

class SolicitudesAclaracionAnalisisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SolicitudesAclaracion $solicitud)
    {
        $accion=AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;

        
        // $promocion=$accion->promocion;
        return view('solicitudesaclaracionanalisis.show',compact('solicitud','accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SolicitudesAclaracion $solicitud)
    {
        $accion=AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));
        $auditoria=$accion->auditoria;
        $promocion=CatalogoTipoAccion::whereNotIn ('id',[1])->get()->pluck('descripcion','id')->prepend('Seleccione una opción', '');

        return view('solicitudesaclaracionanalisis.form',compact('solicitud','accion','auditoria','promocion'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SolicitudesAclaracion $solicitud)
    {
        if ($request->calificacion_sugerida=='Solventada') {
            $request['monto_solventado'] = $solicitud->accion->monto_aclarar;
        }else if($request->calificacion_sugerida=='No Solventada'){
            $request['monto_solventado'] = 0.00;
        }else{
            $monto_aclarar = $solicitud->accion->monto_aclarar;
            $montostrpesos = str_replace('$','',$request->monto_solventado);
            $montostrcomas = str_replace(',','',$montostrpesos);
            $monto=(float) $montostrcomas;
            //dd($monto,$request->monto_solventado);
            if($monto==0.00){
                setMessage('El monto solventado debe ser mayor a $0.00.','error');

                return back()->withInput();
            }
            if($monto > $monto_aclarar){
                setMessage('El monto solventado debe ser menor al monto por aclarar','error');

                return back()->withInput();
            }
            $request['monto_solventado'] = $monto;
        }
            $montostrpesospromo = str_replace('$','',$request->monto_promocion);
            $montostrcomaspromo = str_replace(',','',$montostrpesospromo);
            $montopromo=(float) $montostrcomaspromo;

            $request['monto_promocion'] = $montopromo;

            $request = $this->normalizarDatos($request,$solicitud->accion);     


        $solicitud->update($request->all());
        setMessage("Se ha actualizado el análisis.");

        return redirect()->route('solicitudesaclaracionanexos.index');
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

    public function normalizarDatos(Request $request, AuditoriaAccion $accion)
    {
        if($request->calificacion_sugerida=='Solventada'){
            $request['monto_solventado'] = $accion->monto_aclarar;
            $request['promocion'] = null;
            $request['monto_promocion'] = null;
        }
        if($request->calificacion_sugerida=='No Solventada'){
            $request['monto_solventado'] = null;
            $request['monto_promocion'] = $accion->monto_aclarar;

            if($request->promocion==2){
                $request['monto_promocion'] = null;
            }
        }
        if($request->calificacion_sugerida=='Solventada Parcialmente'){  
            $request['monto_promocion'] = $accion->monto_aclarar-$request['monto_solventado'];       
            if($request->promocion==2){
                $request['monto_promocion'] = null;
            }
        }

        return $request;
        
    }
}
