<?php

namespace App\Http\Controllers;

use App\Models\Movimientos;
use App\Models\Recomendaciones;
use App\Models\SolicitudesAclaracion;
use Illuminate\Http\Request;

class SolicitudesAclaracionAnalisisEnvioController extends Controller
{
    public function edit(SolicitudesAclaracion $solicitud)
    {
        $solicitud->update(['fase_revision'=>'Pendiente']);

        Movimientos::create([
            'tipo_movimiento' => 'Actualización del análisis',
            'accion' => 'Solicitud de Aclaración',
            'accion_id' => $solicitud->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        $titulo = 'Revisión del análisis de la solicitud de aclaración de la acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado (a) ' . $solicitud->accion->lider->name . ', ' . $solicitud->accion->lider->puesto . ':</strong><br>
                    Se ha actualizado el análisis de la recomendación de la acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria . ', por parte del ' . 
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

        setMessage('Se ha enviado la notificación al líder de proyecto para la revisión del análisis.');

        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $solicitud->accion->lider->unidad_administrativa_id,$solicitud->accion->lider->id);

        return redirect()->route('solicitudesaclaracionatencion.index');
    }

}
