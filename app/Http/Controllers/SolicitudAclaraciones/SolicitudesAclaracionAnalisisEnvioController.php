<?php

namespace App\Http\Controllers\SolicitudAclaraciones;

use App\Http\Controllers\Controller;
use App\Models\Movimientos;
use App\Models\Recomendaciones;
use App\Models\SolicitudesAclaracion;
use App\Models\SolicitudesAclaracionContestacion;
use Illuminate\Http\Request;

class SolicitudesAclaracionAnalisisEnvioController extends Controller
{
    public function edit(SolicitudesAclaracion $solicitud)
    {
        $request=new Request();
        if(empty($solicitud->listado_documentos)){
            setMessage('No se ha capturado información en el apartado de listado de documentos.','error');

            return back()->withInput();
        }
        /*$contestaciones = SolicitudesAclaracionContestacion::where('solicitudaclaracion_id',$solicitud->id)->get();
        if($contestaciones->count()==0){
            setMessage('No se ha capturado información en el apartado de contestaciones.','error');

            return back()->withInput();
        }*/
        $request['concluido']='Si';

        $solicitud->update($request->all());

        Movimientos::create([
            'tipo_movimiento' => 'Registro de la atención de la solicitud de aclaración.',
            'accion' => 'Solicitud de Aclaración',
            'accion_id' => $solicitud->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        if (strlen($solicitud->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $solicitud->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        $solicitud->update(['fase_autorizacion' =>  'En revisión 01', 'nivel_autorizacion' => $nivel_autorizacion]);

        $titulo = 'Revisión del registro de la atención de la solicitud de acalaración de la acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado (a) ' . $solicitud->accion->lider->name . ', ' . $solicitud->accion->lider->puesto . ':</strong><br>
                    Ha sido registrada la atención de la solicitud de acalaración de la acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria . ', por parte del ' .
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $solicitud->accion->lider->unidad_administrativa_id,$solicitud->accion->lider->id);

        setMessage('Se han enviado la información de la atención de la solicitud de acalaración a revisión');

        return redirect()->route('solicitudesaclaracionatencion.index');
    }

}
