<?php

namespace App\Http\Controllers;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\PliegosObservacion;
use Illuminate\Http\Request;

class PliegosObservacionAtencionAnalisisController extends Controller
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
    public function show(PliegosObservacion $pliegosobservacion)
    {
        $accion=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $auditoria=$accion->auditoria;

        return view('pliegosatencionanalisis.show',compact('pliegosobservacion','accion','auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PliegosObservacion $pliegosobservacion)
    {
        $accion=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $auditoria=$accion->auditoria;
        $promocion=CatalogoTipoAccion::whereNotIn ('id',[3,1])->get()->pluck('descripcion','id')->prepend('Seleccione una opción', '');

        return view('pliegosatencionanalisis.form',compact('pliegosobservacion','accion','auditoria','promocion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PliegosObservacion $pliegosobservacion)
    {
        if ($request->calificacion_sugerida=='Solventado') {
            $request['monto_solventado'] = $pliegosobservacion->accion->monto_aclarar;
        }else if($request->calificacion_sugerida=='No Solventado'){
            $request['monto_solventado'] = 0.00;
        }else{
            $monto_aclarar = $pliegosobservacion->accion->monto_aclarar;
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
            $request = $this->normalizarDatos($request,$pliegosobservacion->accion);  


        $pliegosobservacion->update($request->all());
        setMessage("Se ha actualizado el análisis.");

        return redirect()->route('pliegosobservacionatencion.index');
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
        if($request->calificacion_sugerida=='Solventado'){
            $request['monto_solventado'] = $accion->monto_aclarar;
            $request['promocion'] = null;
            $request['monto_promocion'] = null;
        }
        if($request->calificacion_sugerida=='No Solventado'){
            $request['monto_solventado'] = null;
            $request['monto_promocion'] = $accion->monto_aclarar;

            if($request->promocion==2){
                $request['monto_promocion'] = null;
            }
        }
        if($request->calificacion_sugerida=='Solventado Parcialmente'){            
            $request['monto_promocion'] = $accion->monto_aclarar-$request['monto_solventado']; 
            if($request->promocion==2){
                $request['monto_promocion'] = null;
            }
        }

        return $request;
        
    }
}
