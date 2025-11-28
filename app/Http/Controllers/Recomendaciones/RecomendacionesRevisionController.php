<?php

namespace App\Http\Controllers\Recomendaciones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\Notificacion;
use App\Models\Recomendaciones;
use App\Models\User;
use Illuminate\Http\Request;

class RecomendacionesRevisionController extends Controller
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
        $auditoria = Auditoria::find($recomendacion->auditoria_id);
        $accion=AuditoriaAccion::find($recomendacion->accion_id);
        return view('recomendacionesatencionrevision.form', compact('recomendacion','auditoria','accion'));
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
        $auditoria = Auditoria::find($recomendacion->auditoria_id); 
        $director=User::where('unidad_administrativa_id',substr($recomendacion->userCreacion->unidad, 0, 4).'00')->where('siglas_rol','DS')->first();
        $asistenteATUS=User::where('siglas_rol','ATUS')->first();
       
        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Revisión de atención de la recomendación',
            'accion' => 'Recomendación',
            'accion_id' => $recomendacion->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        if (strlen($recomendacion->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $recomendacion->nivel_autorizacion;
        } elseif ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 5);
        }

        $recomendacion->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En validación' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a validación del superior.' :
            'El rechazo ha sido registrado.'
        );

        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $recomendacion).'/RevJD')->first();
        $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($recomendacion)."/Rechazo")->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);
        $url = route('recomendacionesatencion.index');
        if ($request->estatus == 'Aprobado') {
            $recomendacion->update([ 'nivel_autorizacion' => $nivel_autorizacion]);
            
            $titulo = 'Validación del registro de atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$director->name.', '.$director->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado el registro de atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la validación oportuna en el módulo Seguimiento.';
                        
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $director->unidad_administrativa_id, $director->id, GenerarLlave( $recomendacion).'/ValD', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeComentario($asistenteATUS->name,$asistenteATUS->puesto, $recomendacion), now(), $asistenteATUS->unidad_administrativa_id, $asistenteATUS->id, GenerarLlave( $recomendacion).'/Consulta', $url); 
        } else {
            $titulo = 'Rechazo del registro de atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$recomendacion->userCreacion->name.', '.$recomendacion->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $recomendacion->userCreacion->unidad_administrativa_id, $recomendacion->userCreacion->id, GenerarLlave( $recomendacion).'/Rechazo', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($recomendacion->accion->lider->name,$recomendacion->accion->lider->puesto,$recomendacion), now(), $recomendacion->accion->lider->unidad_administrativa_id, $recomendacion->accion->lider->id, GenerarLlave( $recomendacion).'/Rechazo',$url);
        }

        return redirect()->route('recomendacionesatencion.index');
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

    private function mensajeComentario(String $nombre, String $puesto, Recomendaciones $recomendacion)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado el registro de atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;

        /*$mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Se registro un comentario por parte del '.auth()->user()->puesto.'; '.auth()->user()->name.', por lo que se debe atender.'; */   
        return $mensaje;
    }

    private function mensajeRechazo(String $nombre, String $puesto, Recomendaciones $recomendacion)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
        .'Ha sido rechazado el registro de atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;

        return $mensaje;
    }

    private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }
}
