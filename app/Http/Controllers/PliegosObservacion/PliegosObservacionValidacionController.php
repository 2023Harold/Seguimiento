<?php

namespace App\Http\Controllers\PliegosObservacion;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\PliegosObservacion;
use App\Models\User;
use Illuminate\Http\Request;

class PliegosObservacionValidacionController extends Controller
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
        $accion=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $auditoria = $accion->auditoria;

        return view('pliegosobservacionvalidacion.form', compact('pliegosobservacion','auditoria','accion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PliegosObservacion $pliegosobservacion)
    {
        $titular=auth()->user()->titular;
        $jefe=User::where('unidad_administrativa_id', substr($pliegosobservacion->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();

        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Validación de la atención del pliego de observación',
            'accion' => 'Pliegos de observación',
            'accion_id' => $pliegosobservacion->id,
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

        $pliegosobservacion->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En autorización' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a autorización del superior.' :
            'El rechazo ha sido registrado.'
        );
        $url = route('pliegosobservacionatencion.index');
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave($pliegosobservacion).'/ValD')->first();
        $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($pliegosobservacion)."/Rechazo")->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);


        if ($request->estatus == 'Aprobado') {
            $pliegosobservacion->update([ 'nivel_autorizacion' => $nivel_autorizacion]);

            $titulo = 'Autorización del registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;

            $mensaje = '<strong>Estimado(a) '.$titular->name.', '.$titular->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado el registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la autorización oportuna en el módulo Seguimiento.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $titular->unidad_administrativa_id, $titular->id, GenerarLlave($pliegosobservacion).'/Aut',$url);
        } else {
            $titulo = 'Rechazo del registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$pliegosobservacion->userCreacion->name.', '.$pliegosobservacion->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $pliegosobservacion->userCreacion->unidad_administrativa_id, $pliegosobservacion->userCreacion->id,GenerarLlave($pliegosobservacion).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($pliegosobservacion->accion->lider->name,$pliegosobservacion->accion->lider->puesto,$pliegosobservacion), now(), $pliegosobservacion->accion->lider->unidad_administrativa_id, $pliegosobservacion->accion->lider->id, GenerarLlave($pliegosobservacion).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name,$jefe->puesto,$pliegosobservacion), now(), $jefe->unidad_administrativa_id, $jefe->id,GenerarLlave($pliegosobservacion).'/Rechazo',$url);
        }

        return redirect()->route('pliegosobservacionatencion.index');
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

     private function mensajeRechazo(String $nombre, String $puesto, PliegosObservacion $pliego)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
        .'Ha sido rechazado el registro de atención del pliego de observación de la Acción No. '.$pliego->accion->numero.' de la Auditoría No. '.$pliego->accion->auditoria->numero_auditoria;

        return $mensaje;
    }
}
