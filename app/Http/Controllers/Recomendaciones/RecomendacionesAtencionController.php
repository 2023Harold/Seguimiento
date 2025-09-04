<?php

namespace App\Http\Controllers\Recomendaciones;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecomendacionesRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\Recomendaciones;
use App\Models\RecomendacionesContestacion;
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
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));
        $recomendaciones = Recomendaciones::where('accion_id',getSession('recomendacionesauditoriaaccion_id'))->get();        
        $asistente_titular= user:: where ('siglas_rol', 'ATUS')->first();

        if(getSession('cp')==2022){
            $director=User::where('unidad_administrativa_id',substr($recomendaciones->userCreacion->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();
            $jefe=$recomendaciones->accion->depaasignado;
            $lider=$recomendaciones->accion->lider;
            $analista=$recomendaciones->accion->analista;
        }else{
            $director = $auditoria->directorasignado;
            $jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp;
            $lider = $auditoria->lidercp; 
        }
        $LayoutMonto = Recomendaciones::where('auditoria_id',$auditoria->id)->get();
        $sumaMontoSolventadoPo = $LayoutMonto->sum('monto_solventado');
        $restaMontoPo = $auditoria->total() - $sumaMontoSolventadoPo;
        //  dd($asistente_titular);  
        return view('recomendacionesatencion.index',compact('recomendaciones','auditoria','accion','request','asistente_titular','director','sumaMontoSolventadoPo','restaMontoPo'));
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
       //
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
