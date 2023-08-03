<?php

namespace App\Http\Controllers;

use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;

class SeguimientoAuditoriaValidacionController extends Controller
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
    public function edit(Auditoria $auditoria)
    {
        return view('seguimientoauditoriavalidacion.form', compact('auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AprobarFlujoAutorizacionRequest $request, Auditoria $auditoria)
    {
        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Validación del registro de la auditoría',
            'accion' => 'Registro de la auditoría',
            'accion_id' => $auditoria->id,
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

        $auditoria->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En autorización' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a autorización del superior.' :
            'El rechazo ha sido registrado.'
        );
        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del registro de la auditoria No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.auth()->user()->titular->name.', '.auth()->user()->titular->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; ha aprobado la validación del registro de la auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se requiere realice la autorización oportuna de la misma.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->titular->unidad_administrativa_id, auth()->user()->titular->id);
        } else {
            $jefe=User::where('unidad_administrativa_id',$auditoria->unidad_administrativa_registro)->where('siglas_rol','JD')->first();
            $auditoria->update(['registro_concluido'=>'No']);
            $titulo = 'Rechazo del registro de la auditoria No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$auditoria->usuarioCreacion->name.', '.$auditoria->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $auditoria->usuarioCreacion->unidad_administrativa_id, $auditoria->usuarioCreacion->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($auditoria->lider->name,$auditoria->lider->puesto,$auditoria->numero_auditoria), now(), $auditoria->lider->unidad_administrativa_id, $auditoria->lider->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name,$jefe->puesto,$auditoria->numero_auditoria), now(), $jefe->unidad_administrativa_id, $jefe->id);
        
        }

        return redirect()->route('seguimientoauditoria.index');
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
        .'Ha sido rechazado el registro de auditoría No. '.$numeroauditoria.'.';       

        return $mensaje;
    }
}
