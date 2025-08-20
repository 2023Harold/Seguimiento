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

class RecomendacionesValidacionController extends Controller
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
        return view('recomendacionesatencionvalidacion.form', compact('recomendacion','auditoria','accion'));
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
        $titular=auth()->user()->titular;
        
        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Validación de la atención de la recomendación de la recomendación',
            'accion' => 'Recomendación',
            'accion_id' => $recomendacion->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);
        
        if(getSession('cp')==2022){
            $jefe=User::where('unidad_administrativa_id', substr($recomendacion->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();
            $lider=$recomendacion->accion->lider;
            $analista=$recomendacion->accion->analista;
        }else{
            $jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp;
            $lider = $auditoria->lidercp; 
        }

        $url = route('recomendacionesatencion.index');
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave($recomendacion).'/ValD')->first();
        $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($recomendacion)."/Rechazo")->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);

        if ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 3);
            $recomendacion->update([ 'nivel_autorizacion' => $nivel_autorizacion]);

            $titulo = 'Autorización del registro de la atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$titular->name.', '.$titular->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; ha aprobado el registro de la atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la autorización oportuna en el módulo Seguimiento.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $titular->unidad_administrativa_id, $titular->id, GenerarLlave($recomendacion).'/Aut',$url);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
            $titulo = 'Rechazo del registro de la atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$recomendacion->userCreacion->name.', '.$recomendacion->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de la atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';

            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $recomendacion->userCreacion->unidad_administrativa_id, $recomendacion->userCreacion->id, GenerarLlave($recomendacion).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($lider->name,$lider->puesto,$recomendacion), now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($recomendacion).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name,$jefe->puesto,$recomendacion), now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave($recomendacion).'/Rechazo',$url);
        }
        $recomendacion->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En autorización' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
			setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a autorización del superior.' :
            'El rechazo ha sido registrado.'
        );
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

    private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }

     private function mensajeRechazo(String $nombre, String $puesto, Recomendaciones $recomendacion)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
        .'Ha sido rechazado el registro de atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;

        return $mensaje;
    }
}
