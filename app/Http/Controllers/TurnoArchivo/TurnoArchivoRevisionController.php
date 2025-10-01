<?php

namespace App\Http\Controllers\TurnoArchivo;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\TurnoAcuseArchivo;
use App\Models\User;
use Illuminate\Http\Request;

class TurnoArchivoRevisionController extends Controller
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
       
        return view('turnoarchivorevision.form', compact('turnoarchivo','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TurnoAcuseArchivo $auditoria)
    {
        $this->normalizarDatos($request);
        $turnoarchivo=$auditoria;
        Movimientos::create([
            'tipo_movimiento' => 'Revisión del Turno Acuse Envío Archivo',
            'accion' => 'TurnoArchivo',
            'accion_id' => $turnoarchivo->id,
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

        $turnoarchivo->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En validación' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'La Revisión ha sido registrada y se ha enviado a validación del superior.' :
            'El rechazo ha sido registrado.'
        );
        $asistenteATUS=User::where('siglas_rol','ATUS')->first();
        $auditoria = Auditoria::find($turnoarchivo->auditoria_id);
        if(getSession('cp')==2022){
            $lider=$auditoria->accion->lider;
            $analista=$auditoria->accion->analista;
            $director=User::where('unidad_administrativa_id',substr($analista->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();
            $jefe=$auditoria->accion->depaasignado;
        }else{
            $director = $auditoria->directorasignado;
            $jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp;
            $lider = $auditoria->lidercp; 
        }

        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $turnoarchivo).'/RevJD')->first();
        $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($turnoarchivo)."/Rechazo")->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);
        $url = route('turnoarchivo.index');

        if ($request->estatus == 'Aprobado') {
            $titulo = ' Turno Acuse Envío de la auditoría No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.auth()->user()->director->name.', '.auth()->user()->director->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; ha aprobado la revisión del Turno Acuse Envío Archivo, por lo que se requiere realice la validación oportuna de la misma.';
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id, auth()->user()->director->id);
            auth()->user()->insertNotificacion('Validación'.$titulo, $mensaje, now(), $director->unidad_administrativa_id, $director->id, GenerarLlave($turnoarchivo).'/ValD', $url);
            auth()->user()->insertNotificacion('Revisión'.$titulo, $this->mensajeNotificacion($asistenteATUS->name,$asistenteATUS->puesto, $auditoria->numero_auditoria), now(), $asistenteATUS->unidad_administrativa_id, $asistenteATUS->id,GenerarLlave($turnoarchivo).'/Consulta', $url); 
            
        }else {
            
            $titulo = 'Rechazo del Turno Acuse Envío de la auditoría No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$analista->name.', '.$analista->puesto.':</strong><br>'
                            .'Ha sido rechazado el Turno acuse envío archivo de la auditoría No. '.$turnoarchivo->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a validación.';
            
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), $turnoarchivo->usuarioCreacion->unidad_administrativa_id, $turnoarchivo->usuarioCreacion->id);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $analista->unidad_administrativa_id, $analista->id, GenerarLlave($turnoarchivo).'/Rechazo', $url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($lider->name,$lider->puesto,$auditoria->numero_auditoria), now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($turnoarchivo).'/Rechazo', $url);
            
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
    private function mensajeNotificacion(String $nombre, String $puesto, $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'se ha aprobado el registro del del Turno acuse envío archivo de la Auditoría No. '.$numeroauditoria.', por lo que se debe revisar.';
        return $mensaje;
    }
}
