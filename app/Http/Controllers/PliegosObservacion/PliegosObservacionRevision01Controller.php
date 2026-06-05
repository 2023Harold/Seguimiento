<?php

namespace App\Http\Controllers\PliegosObservacion;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\AuditoriaUsuarios;
use App\Models\Movimientos;
use App\Models\PliegosObservacion;
use DB;
use Illuminate\Http\Request;

class PliegosObservacionRevision01Controller extends Controller
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

        return view('pliegosobservacionrevision01.form', compact('pliegosobservacion','auditoria','accion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,PliegosObservacion $pliegosobservacion)
    {
        $auditoria = Auditoria::find($pliegosobservacion->auditoria_id);
        $staffA = AuditoriaUsuarios::select('segusers.id','segusers.name','segusers.puesto', 'segusers.unidad_administrativa_id', 'segusers.siglas_rol', 'segusers.estatus',   
                                            DB::raw("(case when(segusers.id = segauditoria_usuarios.staff_id) THEN segusers.name ELSE NULL END) AS staffAsignado01"),
                                            )->join('segusers', 'segusers.id', '=', 'segauditoria_usuarios.staff_id')->where('auditoria_id', $auditoria->id)->get()->toArray();
        if(getSession('cp')==2022){
            $jefe=$pliegosobservacion->accion->depaasignado;

            $analista=$pliegosobservacion->accion->analista;
        }else{
            $jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp;
        }
        //$jefe=auth()->user()->jefe;
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

        if (strlen($pliegosobservacion->nivel_autorizacion) == 3 || strlen($pliegosobservacion->nivel_autorizacion) == 4) {
            $nivel_autorizacion = $pliegosobservacion->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 5);
        }

        $pliegosobservacion->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En revisión' : 'Rechazado']);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a revisión del superior.' :
            'El rechazo ha sido registrado.'
        );
        $url = route('pliegosobservacionatencion.index');
        $cuenta_publicaSession = getSession('cp');
        $usaEquipo = usaEquipoTrabajo(); // guardamos en variable para reutilizar

        if ($usaEquipo) {
            $notificacion=auth()->user()->todasNotificacionesNuevas()->where('estatus', 'Pendiente')->where('llave',GenerarLlave($pliegosobservacion).'/RevL')->first();
            $notificacionRechazo=auth()->user()->todasNotificacionesNuevas()->where('estatus', 'Pendiente')->where('llave',GenerarLlave($pliegosobservacion).'/Rechazo')->first();
            $registroLider = AuditoriaUsuarios::where('auditoria_id', $auditoria->id)->where('rol_code', 'Lider')->where('estatus', 'Activo')->first();
            $equipoId = $registroLider->equipo_id ?? null;
            $liderIndividual = null; // ya no se usa para notificar
        } else {
            $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $pliegosobservacion).'/RevL')->first();
            $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($pliegosobservacion)."/Rechazo")->first();
            $lider_asignadoCP = ($cuenta_publicaSession != 2022) ? $auditoria->lidercp : $auditoria->lider;
        }
        auth()->user()->NotMarcarLeido($notificacion);
        auth()->user()->NotMarcarLeido($notificacionRechazo);

        if ($request->estatus == 'Aprobado') {
            $pliegosobservacion->update([ 'nivel_autorizacion' => $nivel_autorizacion]);
            $titulo = 'Revisión del registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$jefe->name.', '.$jefe->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado el registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la revisión oportuna en el módulo Seguimiento.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave( $pliegosobservacion).'/RevJD', $url);
        } else {
            $titulo = 'Rechazo del registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$pliegosobservacion->userCreacion->name.', '.$pliegosobservacion->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            if ($usaEquipo) {
                $registroAnalista = AuditoriaUsuarios::where('auditoria_id', $auditoria->id)->where('rol_code', 'Analista')->where('estatus', 'Activo')->first();
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), null, null,GenerarLlave($pliegosobservacion). '/Rechazo', $url,$auditoria->id, $registroAnalista->equipo_id ?? null,'Analista');
            }else{
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), $pliegosobservacion->userCreacion->unidad_administrativa_id, $pliegosobservacion->userCreacion->id, GenerarLlave($pliegosobservacion).'/Rechazo', $url);
            }
        }
        foreach ($staffA as $staff) {
            if (!empty($staff['id'])) {
                $tituloStaff = 'Revisión del registro de atención de la pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;

                $mensajeStaff = '<strong>Estimado(a) '.$staff['name'].', '.$staff['puesto'].':</strong><br>'
                                .auth()->user()->name.', '.auth()->user()->puesto.
                                '; ha aprobado el registro de atención de la pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.
                                ', por lo que se le notifica para su conocimiento.';

                auth()->user()->insertNotificacion($tituloStaff, $mensajeStaff, now(), $staff['unidad_administrativa_id'], $staff['id'], GenerarLlave($pliegosobservacion).'/Consulta', $url);
            }
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
}
