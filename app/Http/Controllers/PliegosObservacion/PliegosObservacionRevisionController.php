<?php

namespace App\Http\Controllers\PliegosObservacion;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\PliegosObservacion;
use App\Models\User;
use Illuminate\Http\Request;

class PliegosObservacionRevisionController extends Controller
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
        $auditoria = Auditoria::find($pliegosobservacion->auditoria_id);
        $accion=AuditoriaAccion::find($pliegosobservacion->accion_id);


        return view('pliegosobservacionrevision.form', compact('pliegosobservacion','auditoria','accion'));
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
        $director=auth()->user()->director;
        $asistenteATUS=User::where('siglas_rol','ATUS')->first();

        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Revisión de atención del pliego de observación',
            'accion' => 'Pliegos de observación',
            'accion_id' => $pliegosobservacion->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        if (strlen($pliegosobservacion->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $pliegosobservacion->nivel_autorizacion;
        } elseif ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 5);
        }

        $pliegosobservacion->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En validación' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a validación del superior.' :
            'El rechazo ha sido registrado.'
        );
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $pliegosobservacion).'/RevJD')->first();
        $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($pliegosobservacion)."/Rechazo")->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);
        $url = route('pliegosobservacionatencion.index');

        if ($request->estatus == 'Aprobado') {
            $pliegosobservacion->update([ 'nivel_autorizacion' => $nivel_autorizacion]);

            $titulo = 'Validación del registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;

            $mensaje = '<strong>Estimado(a) '.$director->name.', '.$director->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado el registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la validación oportuna en el módulo Seguimiento.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $director->unidad_administrativa_id, $director->id, GenerarLlave($pliegosobservacion).'/ValD', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeNotificacion($asistenteATUS->name,$asistenteATUS->puesto, $pliegosobservacion), now(), $asistenteATUS->unidad_administrativa_id, $asistenteATUS->id,GenerarLlave($pliegosobservacion).'/Consulta', $url); 
        } else {
            $titulo = 'Rechazo del registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$pliegosobservacion->userCreacion->name.', '.$pliegosobservacion->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $pliegosobservacion->userCreacion->unidad_administrativa_id, $pliegosobservacion->userCreacion->id, GenerarLlave($pliegosobservacion).'/Rechazo', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($pliegosobservacion->accion->lider->name,$pliegosobservacion->accion->lider->puesto,$pliegosobservacion), now(), $pliegosobservacion->accion->lider->unidad_administrativa_id, $pliegosobservacion->accion->lider->id, GenerarLlave($pliegosobservacion).'/Rechazo', $url);
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

    private function mensajeRechazo(String $nombre, String $puesto, PliegosObservacion $pliegosobservacion)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
        .'Ha sido rechazado el registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;

        return $mensaje;
    }

    private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }
	
	private function mensajeNotificacion(String $nombre, String $puesto, $pliegosobservacion)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'se ha aprobado el registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.', por lo que se debe revisar.';
        return $mensaje;
    }
}
