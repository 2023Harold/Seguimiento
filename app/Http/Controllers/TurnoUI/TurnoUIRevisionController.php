<?php

namespace App\Http\Controllers\TurnoUI;

use App\Http\Controllers\Controller;

use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\TurnoUI;
use App\Models\User;
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
        $url = route('turnoui.index');
         Movimientos::create([
           'tipo_movimiento' => 'Revisión del Turno a la Unidad de Investigación',
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
        $auditoria = Auditoria::find($turnoui->auditoria_id);
        if(getSession('cp')==2022){
            $jefe=User::where('unidad_administrativa_id', substr($auditoria->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();
            $lider=$auditoria->accion->lider;
            $analista=$auditoria->accion->analista;
        }else{
            $jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp;
            $lider = $auditoria->lidercp; 
        }
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $turnoui).'/RevJD')->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $NotificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave( $turnoui).'/Rechazo')->first();
        $LeerNotificacionRechazo = auth()->user()->NotMarcarLeido($NotificacionRechazo);
        if ($request->estatus == 'Aprobado') {
            $titulo = 'Validación del Turno UI de la auditoría No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.auth()->user()->director->name.', '.auth()->user()->director->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; ha aprobado la revisión del Turno a la Unidad de Investigacion de la auditoría No. '.$turnoui->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la validación oportuna de la misma.';
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id, auth()->user()->director->id);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id, auth()->user()->director->id, GenerarLlave($turnoui).'/ValD',$url);
        }else {
            $titulo = 'Rechazo del Turno UI de la auditoría No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$lider->name.', '.$lider->puesto.':</strong><br>'
                            .'Ha sido rechazado el Turno a la Unidad de Investigación de auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a validación.';
            
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), $turnoui->usuarioCreacion->unidad_administrativa_id, $turnoui->usuarioCreacion->id);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $lider->unidad_administrativa_id, $lider->id,GenerarLlave($turnoui).'/Rechazo',$url);

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
