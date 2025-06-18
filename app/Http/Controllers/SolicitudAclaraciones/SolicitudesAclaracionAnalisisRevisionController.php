<?php

namespace App\Http\Controllers\SolicitudAclaraciones;

use App\Http\Controllers\Controller;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\SolicitudesAclaracion;
use Illuminate\Http\Request;

class SolicitudesAclaracionAnalisisRevisionController extends Controller
{
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

        return view('solicitudesaclaracionanalisisrevision.form',compact('solicitud','accion','auditoria'));
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
        $jefe=auth()->user()->jefe;

        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Revisión de la actualización del análisis',
            'accion' => 'Solicitud de Aclaración',
            'accion_id' => $solicitud->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);       

        $solicitud->update(['fase_revision' => $request->estatus == 'Aprobado' ? 'Revisión Jefe' : 'Rechazado']);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada.' :
            'El rechazo ha sido registrado.'
        );


        if ($request->estatus == 'Aprobado') {           
            $titulo = 'Revisión del análisis de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
                   
            $mensaje = '<strong>Estimado(a) '.$jefe->name.', '.$jefe->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado la actualización del análisis de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.
                            '.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $jefe->unidad_administrativa_id, $jefe->id);
        } else {           
            $titulo = 'Rechazo del análisis de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$solicitud->userCreacion->name.', '.$solicitud->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el del análisis de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $solicitud->userCreacion->unidad_administrativa_id, $solicitud->userCreacion->id);
        }

        return redirect()->route('solicitudesaclaracionatencion.index');
    }

    private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }
    
}
