<?php

namespace App\Http\Controllers;

use App\Models\Movimientos;
use App\Models\TurnoArchivoTransferencia;
use Illuminate\Http\Request;

class TurnoArchivoTransferenciaRevisionController extends Controller
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
    public function edit(TurnoArchivoTransferencia $auditoria)
    {
        $turnoarchivotransferencia=$auditoria;
        $auditoria=$auditoria->auditoria;
       
        return view('turnotransferenciarevision.form', compact('turnoarchivotransferencia','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TurnoArchivoTransferencia $auditoria)
    {
        $this->normalizarDatos($request);
        $turnoarchivotransferencia=$auditoria;
       Movimientos::create([
           'tipo_movimiento' => 'Revisión del Turno Acuse Envío Archivo',
           'accion' => 'TurnoTransferencia',
           'accion_id' => $turnoarchivotransferencia->id,
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

    $turnoarchivotransferencia->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En validación' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
    setMessage($request->estatus == 'Aprobado' ?
        'La Revisión ha sido registrada y se ha enviado a autorización del superior.' :
        'El rechazo ha sido registrado.'
    );

    if ($request->estatus == 'Aprobado') {
        $titulo = 'Revisión del Turno archivo transferencia de la auditoría No. '.$turnoarchivotransferencia->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado(a) '.auth()->user()->director->name.', '.auth()->user()->director->puesto.':</strong><br>'
                        .auth()->user()->name.', '.auth()->user()->puesto.
                        '; ha aprobado la revisión del Turno archivo transferencia de la auditoría No. '.$turnoarchivotransferencia->auditoria->numero_auditoria.
                        ', por lo que se requiere realice la validación oportuna de la misma.';
        auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id, auth()->user()->director->id);
    }else {
        
        $titulo = 'Rechazo del Turno Acuse Envío de la auditoría No. '.$turnoarchivotransferencia->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado(a) '.$turnoarchivotransferencia->usuarioCreacion->name.', '.$turnoarchivotransferencia->usuarioCreacion->puesto.':</strong><br>'
                        .'Ha sido rechazado el Turno acuse envío archivo de la auditoría No. '.$turnoarchivotransferencia->auditoria->numero_auditoria.
                        ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a validación.';
        
        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $turnoarchivotransferencia->usuarioCreacion->unidad_administrativa_id, $turnoarchivotransferencia->usuarioCreacion->id);
    }  

        return redirect()->route('turnoarchivotransferencia.index');
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
}
