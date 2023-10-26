<?php

namespace App\Http\Controllers;

use App\Models\Movimientos;
use App\Models\PliegosObservacion;
use Illuminate\Http\Request;
use App\Models\PliegosContestacion;

class PliegosObservacionAnalisisEnvioController extends Controller
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
    public function edit(PliegosObservacion $pliegosobservacion)
    {
      $request=new Request();
      if(empty($pliegosobservacion->listado_documentos)){
          setMessage('No se ha capturado información en el apartado de listado de documentos.','error');

          return back()->withInput();
      }
      $contestaciones = PliegosContestacion::where('pliegosobservacion_id',$pliegosobservacion->id)->get();
      if($contestaciones->count()==0){
          setMessage('No se ha capturado información en el apartado de contestaciones.','error');

          return back()->withInput();
      }
      $request['concluido']='Si';
      $pliegosobservacion->update($request->all());


      Movimientos::create([
         'tipo_movimiento' => 'Registro de la atención del pliego de observación.',
         'accion' => 'Pliegos de observación',
         'accion_id' => $pliegosobservacion->id,
         'estatus' => 'Aprobado',
         'usuario_creacion_id' => auth()->id(),
         'usuario_asignado_id' => auth()->id(),
     ]);

     if (strlen($pliegosobservacion->nivel_autorizacion) == 3) {
         $nivel_autorizacion = $pliegosobservacion->nivel_autorizacion;
     } else {
         $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
     }

     $pliegosobservacion->update(['fase_autorizacion' =>  'En revisión 01', 'nivel_autorizacion' => $nivel_autorizacion]);

     $titulo = 'Revisión del registro de la atención del pliego de observación de la acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;
     $mensaje = '<strong>Estimado (a) ' . $pliegosobservacion->accion->lider->name . ', ' . $pliegosobservacion->accion->lider->puesto . ':</strong><br>
                 Ha sido registrada la atención del pliego de observación de la acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria . ', por parte del ' .
                 auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

     auth()->user()->insertNotificacion($titulo, $mensaje, now(), $pliegosobservacion->accion->lider->unidad_administrativa_id,$pliegosobservacion->accion->lider->id);

     setMessage('Se han enviado la información de la atención del pliego de observación a revisión');

    return redirect()->route('pliegosobservacionatencion.index');
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
