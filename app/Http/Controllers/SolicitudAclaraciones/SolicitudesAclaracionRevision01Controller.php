<?php

namespace App\Http\Controllers\SolicitudAclaraciones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\Notificacion;
use App\Models\SolicitudesAclaracion;
use Illuminate\Http\Request;

class SolicitudesAclaracionRevision01Controller extends Controller
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
    public function edit(SolicitudesAclaracion $solicitud)
    {
        $auditoria = Auditoria::find($solicitud->auditoria_id);
        $accion=AuditoriaAccion::find($solicitud->accion_id);
        //$accion=$solicitud->accion;

        return view('solicitudesaclaracionrevision01.form', compact('solicitud','auditoria','accion'));
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
		$auditoria = Auditoria::find($solicitud->auditoria_id); 
        if(getSession('cp')==2022){
            $jefe=User::where('unidad_administrativa_id', substr($solicitud->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();
        }else{
            $jefe = $auditoria->jefedepartamentoencargado;
        }

        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Revisión de atención de la solicitud de aclaración',
            'accion' => 'Solicitud de Aclaración',
            'accion_id' => $solicitud->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        if (strlen($solicitud->nivel_autorizacion) == 3 || strlen($solicitud->nivel_autorizacion) == 4) {
            $nivel_autorizacion = $solicitud->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 5);
        }

        $solicitud->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En revisión' : 'Rechazado']);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a revisión del superior.' :
            'El rechazo ha sido registrado.'
        );

		$idUser = auth()->user()->id;
		$notificacion=auth()->user()->notificaciones()->where('llave',"AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$idUser}/RevL")->first();
		
		$notificacionRechazo=auth()->user()->notificaciones()->where('llave',"AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$idUser}/Rechazo")->first();
		if(!empty($notificacion)&& ($notificacion->llave == "AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$idUser}/RevL")&& ($notificacion->estatus == 'Pendiente')){
            $notificacion = Notificacion::find($notificacion->id);
			$notificacion->update(['estatus' => 'Leído']);
			if(!empty($notificacionRechazo)&& ($notificacionRechazo->llave == "AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$idUser}/Rechazo")&&($notificacionRechazo->estatus == 'Pendiente')){
				$notificacionRechazo = Notificacion::find($notificacionRechazo->id);
				$notificacionRechazo->update(['estatus' => 'Leído']);
			}
        }
        if ($request->estatus == 'Aprobado') {
            $solicitud->update([ 'nivel_autorizacion' => $nivel_autorizacion]);

            $titulo = 'Revisión del registro de atención de la recomendación de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;

            $mensaje = '<strong>Estimado(a) '.$jefe->name.', '.$jefe->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado el registro de solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la revisión oportuna en el módulo Seguimiento.';
			$llave = "AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$jefe->id}/RevJD";
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $jefe->unidad_administrativa_id, $jefe->id,$llave);
        } else {
            $solicitud->update(['concluido'=>'No']);
            $titulo = 'Rechazo del registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$solicitud->userCreacion->name.', '.$solicitud->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de atención de la la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
			$llave = "AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$solicitud->userCreacion->id}/Rechazo";
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $solicitud->userCreacion->unidad_administrativa_id, $solicitud->userCreacion->id,$llave);
        }

        return redirect()->route('solicitudesaclaracionatencion.index');
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

    private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }
}
