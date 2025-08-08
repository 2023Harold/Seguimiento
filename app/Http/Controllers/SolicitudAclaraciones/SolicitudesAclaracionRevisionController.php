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

class SolicitudesAclaracionRevisionController extends Controller
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


        return view('solicitudesaclaracionrevision.form', compact('solicitud','auditoria','accion'));
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
        $director=auth()->user()->director;
        $asistenteATUS=User::where('siglas_rol','ATUS')->first();

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

        if (strlen($solicitud->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $solicitud->nivel_autorizacion;
        } elseif ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 5);
        }

        $solicitud->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En validación' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a validación del superior.' :
            'El rechazo ha sido registrado.'
        );
		$idUser = auth()->user()->id;
		$notificacion=auth()->user()->notificaciones()->where('llave',"AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$idUser}/RevJD")->first();
			if(!empty($notificacion)&& ($notificacion->llave == "AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$idUser}/RevJD")&& ($notificacion->estatus == 'Pendiente')){
                $notificacion = Notificacion::find($notificacion->id);
                $notificacion->update(['estatus' => 'Leído']);
            }
        if ($request->estatus == 'Aprobado') {
            $solicitud->update([ 'nivel_autorizacion' => $nivel_autorizacion]);
            $titulo = 'Validación del registro de la atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$director->name.', '.$director->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado el registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la validación oportuna en el módulo Seguimiento.';
			$llave = "AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$director->id}/ValD";
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $director->unidad_administrativa_id, $director->id,$llave);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($asistenteATUS->name,$asistenteATUS->puesto, $solicitud), now(), $asistenteATUS->unidad_administrativa_id, $asistenteATUS->id); 

        } else {
            $titulo = 'Rechazo del registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$solicitud->userCreacion->name.', '.$solicitud->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
			$llave = "AUD-{$solicitud->auditoria_id}/AudAc-{$solicitud->accion_id}/ACC{$solicitud->id}/USER-{$solicitud->userCreacion->id}/Rechazo";
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $solicitud->userCreacion->unidad_administrativa_id, $solicitud->userCreacion->id,$llave);
            //auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($solicitud->accion->lider->name,$solicitud->accion->lider->puesto,$solicitud), now(), $solicitud->accion->lider->unidad_administrativa_id, $solicitud->accion->lider->id);
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

    private function mensajeRechazo(String $nombre, String $puesto, SolicitudesAclaracion $solicitud)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
        .'Ha sido rechazado el registro de atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;

        return $mensaje;
    }

    private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
		
    }
	
	private function mensajeComentario(String $nombre, String $puesto, SolicitudesAclaracion $solicitud)
    {

        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado el registro de atención de la recomendación de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;

        /*$mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Se registro un comentario por parte del '.auth()->user()->puesto.'; '.auth()->user()->name.', por lo que se debe atender.'; */   
        return $mensaje;
    }
}
