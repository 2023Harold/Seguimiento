<?php

namespace App\Http\Controllers;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\TurnoUI;
use Illuminate\Http\Request;

class TurnoUIRevisionController extends Controller
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
    public function edit( TurnoUI $auditoria)
    {
        $turnoui=$auditoria;
        $auditoria=$auditoria->auditoria;
       
        return view('turnouirevision.form', compact('turnoui','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TurnoUI $auditoria)
    {
       $this->normalizarDatos($request);
       $turnoui=$auditoria;
       Movimientos::create([
           'tipo_movimiento' => 'Validación del Turno a la Unidad de Investigación',
           'accion' => 'TurnoUI',
           'accion_id' => $turnoui->id,
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

    $turnoui->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En validación' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
    setMessage($request->estatus == 'Aprobado' ?
        'La Validación ha sido registrada y se ha enviado a autorización del superior.' :
        'El rechazo ha sido registrado.'
    );

    if ($request->estatus == 'Aprobado') {
        $titulo = 'Autorización del Turno de la Unidad de Investigación de la auditoría No. '.$turnoui->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado(a) '.auth()->user()->titular->name.', '.auth()->user()->titular->puesto.':</strong><br>'
                        .auth()->user()->name.', '.auth()->user()->puesto.
                        '; ha aprobado la revisión del Turno a la Unidad de Investigacion de la auditoría No. '.$turnoui->auditoria->numero_auditoria.
                        ', por lo que se requiere realice la autorización oportuna de la misma.';
        auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->titular->unidad_administrativa_id, auth()->user()->titular->id);
    }else {
        
        $titulo = 'Rechazo de la radicación de la auditoría No. '.$turnoui->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado(a) '.$turnoui->usuarioCreacion->name.', '.$turnoui->usuarioCreacion->puesto.':</strong><br>'
                        .'Ha sido rechazado el Turno a la Unidad de Investigación de auditoría No. '.$turnoui->auditoria->numero_auditoria.
                        ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a validación.';
        
        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $turnoui->usuarioCreacion->unidad_administrativa_id, $turnoui->usuarioCreacion->id);
    }  

        return redirect()->route('turnoui.index');
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
                    .'Ha sido rechazado el registro del Turno a la Unidad de Investigación de auditoría No. '.$numeroauditoria.'.';       

        return $mensaje;
    }
}
