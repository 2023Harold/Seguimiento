<?php

namespace App\Http\Controllers\TurnoUI;

use App\Http\Controllers\Controller;
use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\TurnoUI;
use App\Models\User;
use Illuminate\Http\Request;

class TurnoUIValidacionController extends Controller
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
    public function edit(TurnoUI $auditoria)
    {
        $turnoui=$auditoria;
        $auditoria=$auditoria->auditoria;
       
        return view('turnouivalidacion.form', compact('turnoui','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AprobarFlujoAutorizacionRequest $request, TurnoUI $auditoria)
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
        $url = route('turnoui.index');
        if ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 3);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }
        $auditoria = Auditoria::find($turnoui->auditoria_id);
        $titular=auth()->user()->titular;
        if(getSession('cp')==2022){
            //$director=User::where('unidad_administrativa_id',substr($auditoria->userCreacion->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();
            $analista=$auditoria->accion->analista;
            $lider=$auditoria->accion->lider;
            $jefe=$auditoria->accion->depaasignado;
            $director=User::where('unidad_administrativa_id', substr($analista->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();

        }else{
            $director = $auditoria->directorasignado;
            $jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp;
            $lider = $auditoria->lidercp; 
        }
       $turnoui->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En autorización' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
       setMessage($request->estatus == 'Aprobado' ?
           'La aprobación ha sido registrada y se ha enviado a autorización del superior.' :
           'El rechazo ha sido registrado.'
       );
       
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave($turnoui).'/ValD')->first();
        $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($turnoui)."/Rechazo")->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del Turno UI de la auditoría No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$titular->name.', '.$titular->puesto.':</strong><br>'
                        .auth()->user()->name.', '.auth()->user()->puesto.
                        '; ha aprobado la validación del Turno UI de la auditoría No. '.$auditoria->numero_auditoria.
                        ', por lo que se requiere realice la autorización oportuna de la misma.';
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->titular->unidad_administrativa_id, auth()->user()->titular->id);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $titular->unidad_administrativa_id, $titular->id, GenerarLlave($turnoui).'/Aut',$url);
        }else {
        
            $titulo = 'Rechazo del Turno UI de la auditoría No. '.$turnoui->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$lider->name.', '.$lider->puesto.':</strong><br>'
                        .'Ha sido rechazado el Turno UI de la auditoría No. '.$turnoui->auditoria->numero_auditoria.
                        ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a validación.';
        
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), $turnoui->usuarioCreacion->unidad_administrativa_id, $turnoui->usuarioCreacion->id);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $lider->unidad_administrativa_id, $lider->id,GenerarLlave($turnoui).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name, $jefe->name, $auditoria->numero_auditoria), now(), $jefe->unidad_administrativa_id, $jefe->id,GenerarLlave($turnoui).'/Rechazo',$url);
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
