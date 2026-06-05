<?php

namespace App\Http\Controllers\TurnoArchivo;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaUsuarios;
use App\Models\Movimientos;
use App\Models\TurnoAcuseArchivo;
use App\Models\User;
use Illuminate\Http\Request;

class TurnoArchivoEnvioController extends Controller
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
    public function edit(TurnoAcuseArchivo $auditoria)
    {
        $turnoarchivo=$auditoria;
        $auditoria = Auditoria::find($turnoarchivo->auditoria_id);
        if(getSession('cp')==2022){
            $lider=$auditoria->accion->lider;
        }else{
            $lider = $auditoria->lidercp; 
        }
        Movimientos::create([
            'tipo_movimiento' => 'Registro del Turno Acuse Envío Archivo',
            'accion' => 'TurnoArchivo',
            'accion_id' => $turnoarchivo->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        $turnoarchivo->update(['fase_autorizacion' =>  'En revisión01']);

        $url = route('turnoarchivo.index');

        $cuenta_publicaSession = getSession('cp');
        $usaEquipo = usaEquipoTrabajo(); // guardamos en variable para reutilizar
        if ($usaEquipo) {
            $NotificacionRechazo=auth()->user()->todasNotificacionesNuevas()->where('estatus', 'Pendiente')->where('llave', GenerarLlave($turnoarchivo).'/Rechazo')->first();
            $registroLider = AuditoriaUsuarios::where('auditoria_id', $auditoria->id)->where('rol_code', 'Lider')->where('estatus', 'Activo')->first();
            $equipoId = $registroLider->equipo_id ?? null;
        } else {
            $NotificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($turnoarchivo).'/Rechazo')->first();
        }
        auth()->user()->NotMarcarLeido($NotificacionRechazo);

        $titulo = 'Revisión de los datos hacia el Turno acuse envio al archivo';
        $mensaje = '<strong>Estimado (a) ' . $lider->name . ', ' . $lider->puesto . ':</strong><br>'.
                    'Ha sido registrada la Turno acuse envio al archivo de la auditoría No. ' . $auditoria->numero_auditoria . ', por parte del ' .
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->lider->unidad_administrativa_id,auth()->user()->lider->id);
        if ($usaEquipo) {
            auth()->user()->insertNotificacion($titulo, $mensaje, now(),null,null, GenerarLlave($turnoarchivo).'/RevL',$url, $auditoria->id, $equipoId,'Lider');
        }else{
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $lider->unidad_administrativa_id,$lider->id,GenerarLlave($turnoarchivo).'/RevL',$url);
        }

        setMessage('Se ha enviado el turno archivo a Revisión');

        return redirect()->route('turnoarchivo.index');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
