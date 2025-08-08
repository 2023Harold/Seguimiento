<?php

namespace App\Http\Controllers\Recomendaciones;

use App\Http\Controllers\Controller;
use App\Models\Movimientos;
use App\Models\Notificacion;
use App\Models\Recomendaciones;
use App\Models\RecomendacionesContestacion;
use Illuminate\Http\Request;

class RecomendacionesAnalisisEnvioController extends Controller
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
    public function edit(Recomendaciones $recomendacion)
    {
        //dd($recomendacion);
        $request=new Request();
        if(empty($recomendacion->listado_documentos)){
            setMessage('No se ha capturado información en el apartado de listado de documentos.','error');

            return back()->withInput();
        }
        /*$contestaciones = RecomendacionesContestacion::where('recomendacion_id',$recomendacion->id)->get();
        if($contestaciones->count()==0){
            setMessage('No se ha capturado información en el apartado de contestaciones.','error');

            return back()->withInput();
        }*/
        $request['concluido']='Si';
        $recomendacion->update($request->all());

        $mov = Movimientos::create([
            'tipo_movimiento' => 'Registro de la atención de la recomendación',
            'accion' => 'Recomendación',
            'accion_id' => $recomendacion->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        if (strlen($recomendacion->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $recomendacion->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        $recomendacion->update(['fase_autorizacion' =>  'En revisión 01', 'nivel_autorizacion' => $nivel_autorizacion]);

        $titulo = 'Revisión del registro de la atención de la recomendación de la acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado (a) ' . $recomendacion->accion->lider->name . ', ' . $recomendacion->accion->lider->puesto . ':</strong><br>
                    Ha sido registrada la atención de la recomendación de la acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria . ', por parte del ' .
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

        //AUDITORIA - AUDITORIA ACCION - AUDITORIA ACCION CONSECUTIVA - USUARIO DESTINATARIO - TIPO (REVISION01 REVISION VALIDACION AUTORIZACION)
        $llave = "AUD-{$recomendacion->auditoria_id}/AudAc-{$recomendacion->accion_id}/ACC{$recomendacion->id}/USER-{$recomendacion->accion->lider->id}/RevL";
        
        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $recomendacion->accion->lider->unidad_administrativa_id,$recomendacion->accion->lider->id, $llave);

        setMessage('Se han enviado la información de la recomendación a revisión');
        
        return redirect()->route('recomendacionesatencion.index');
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
