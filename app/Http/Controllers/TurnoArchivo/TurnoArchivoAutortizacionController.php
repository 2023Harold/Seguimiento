<?php

namespace App\Http\Controllers\TurnoArchivo;

use App\Http\Controllers\Controller;
use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Movimientos;
use App\Models\TurnoAcuseArchivo;
use App\Models\User;
use Illuminate\Http\Request;

class TurnoArchivoAutortizacionController extends Controller
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
        $auditoria=$auditoria->auditoria;
       
        return view('turnoarchivoautorizacion.form', compact('turnoarchivo','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AprobarFlujoAutorizacionRequest $request, TurnoAcuseArchivo $auditoria)
    {
        $this->normalizarDatos($request);
        $turnoarchivo=$auditoria;

        Movimientos::create([
            'tipo_movimiento' => 'Autorización del Turno acuse envío archivo',
            'accion' => 'TurnoArchivo',
            'accion_id' => $turnoarchivo->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        $titular=User::where('unidad_administrativa_id',substr($turnoarchivo->auditoria->unidad_administrativa_registro, 0, 4).'00')->where('siglas_rol','TUS')->first();
        if ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 3);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        $turnoarchivo->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'Se ha autorizado el Turno acuse envío archivo de la auditoría con éxito.' :
            'El rechazo ha sido registrado.'
        );

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del Turno acuse envío archivo de la auditoría No. '.$turnoarchivo->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.auth()->user()->titular->name.', '.auth()->user()->titular->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; ha aprobado la autorización del  Turno acuse envío archivo de la auditoría No. '.$turnoarchivo->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la autorización oportuna de la misma.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->titular->unidad_administrativa_id, auth()->user()->titular->id);
            // auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($titular->name,$titular->puesto,$turnoarchivo->auditoria->numero_auditoria), now(), $titular->unidad_administrativa_id, $director->id);


        }else {
            
            $titulo = 'Rechazo del  Turno acuse envío archivo de la auditoría No. '.$turnoarchivo->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$turnoarchivo->usuarioCreacion->name.', '.$turnoarchivo->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el  Turno acuse envío archivo de la auditoría No. '.$turnoarchivo->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a autorización.';
            
                            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $turnoarchivo->usuarioCreacion->unidad_administrativa_id, $turnoarchivo->usuarioCreacion->id);
                            //auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$turnoarchivo->auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id);
                            
    }
    return redirect()->route('turnoarchivo.index');
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
    private function mensajeRechazo(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Ha sido rechazado el registro del Turno acuse envío archivo de la auditoría No. '.$numeroauditoria.'.';       

        return $mensaje;
    }
    private function mensajeAprobado(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido autorizado el registro de radicación de la auditoría No. '.$numeroauditoria.
                    ', por parte del Titular.';       

        return $mensaje;
    }
}
