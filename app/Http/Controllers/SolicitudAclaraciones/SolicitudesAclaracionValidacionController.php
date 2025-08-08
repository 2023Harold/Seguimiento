<?php

namespace App\Http\Controllers\SolicitudAclaraciones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\Notificacion;
use App\Models\SolicitudesAclaracion;
use App\Models\User;
use Illuminate\Http\Request;

class SolicitudesAclaracionValidacionController extends Controller
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

        return view('solicitudesaclaracionvalidacion.form', compact('solicitud','auditoria','accion'));
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
        $titular=auth()->user()->titular;
        $jefe=User::where('unidad_administrativa_id', substr($solicitud->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();

        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Validación de atención de la solicitud de aclaración',
            'accion' => 'Solicitud de Aclaración',
            'accion_id' => $solicitud->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        if ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 3);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        $solicitud->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En autorización' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a autorización del superior.' :
            'El rechazo ha sido registrado.'
        );
		$idUser = auth()->user()->id;
		$notificacion=auth()->user()->notificaciones()->where('llave',"AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$idUser}/ValD")->first();
			if(!empty($notificacion)&& ($notificacion->llave == "AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$idUser}/ValD")&& ($notificacion->estatus == 'Pendiente')){
                $notificacion = Notificacion::find($notificacion->id);
                $notificacion->update(['estatus' => 'Leído']);
            }
        if ($request->estatus == 'Aprobado') {
            $solicitud->update([ 'nivel_autorizacion' => $nivel_autorizacion]);
            $titulo = 'Autorización del registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$titular->name.', '.$titular->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado el registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la autorización oportuna en el módulo Seguimiento.';
			$llave = "AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$titular->id}/Aut";
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $titular->unidad_administrativa_id, $titular->id,$llave);
        } else {
            $titulo = 'Rechazo del registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$solicitud->userCreacion->name.', '.$solicitud->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
			 $llave = "AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$solicitud->userCreacion->id}/Rechazo";
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $solicitud->userCreacion->unidad_administrativa_id, $solicitud->userCreacion->id,$llave);
            //auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($solicitud->accion->lider->name,$solicitud->accion->lider->puesto,$solicitud), now(), $solicitud->accion->lider->unidad_administrativa_id, $solicitud->accion->lider->id);
            //auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name,$jefe->puesto,$solicitud), now(), $jefe->unidad_administrativa_id, $jefe->id);
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

     private function mensajeRechazo(String $nombre, String $puesto, SolicitudesAclaracion $solicitud)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
        .'Ha sido rechazado el registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;

        return $mensaje;
    }
}
