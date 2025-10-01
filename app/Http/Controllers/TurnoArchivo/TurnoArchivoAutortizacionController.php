<?php

namespace App\Http\Controllers\TurnoArchivo;

use App\Http\Controllers\Controller;
use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Auditoria;
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
        $auditoria = Auditoria::find(getSession('auditoria_id'));
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
        $url = route('turnoarchivo.index');
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave($turnoarchivo).'/Aut')->first();
        $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($turnoarchivo)."/Rechazo")->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);


        if ($request->estatus == 'Aprobado') {
            $titulo = 'Turno acuse envío archivo de la auditoría No. '.$turnoarchivo->auditoria->numero_auditoria .' Autorizado';
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($analista->name,$analista->puesto,$auditoria->numero_auditoria), now(), $analista->unidad_administrativa_id, $analista->id, GenerarLlave($turnoarchivo).'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($director->name,$director->puesto,$auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id, GenerarLlave($turnoarchivo).'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($jefe->name,$jefe->puesto,$auditoria->numero_auditoria), now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave($turnoarchivo).'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($lider->name,$lider->puesto,$auditoria->numero_auditoria), now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($turnoarchivo).'/Consulta',$url);

        }else {
            
            $titulo = 'Rechazo del Turno acuse envío archivo de la auditoría No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$analista->name.', '.$analista->puesto.':</strong><br>'
                            .'Se rechazo el Turno acuse envío archivo, por lo que se debe atender los comentarios y enviar la información corregida nuevamente a autorización.';
            
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), $turnoarchivo->usuarioCreacion->unidad_administrativa_id, $turnoarchivo->usuarioCreacion->id);
            //auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$turnoarchivo->auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $analista->unidad_administrativa_id, $analista->id, GenerarLlave($turnoarchivo).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id, GenerarLlave($turnoarchivo).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name,$jefe->puesto,$auditoria->numero_auditoria), now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave($turnoarchivo).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($lider->name,$lider->puesto,$auditoria->numero_auditoria), now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($turnoarchivo).'/Rechazo',$url);
          
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
                    .'Se rechazo el registro del Turno acuse envío archivo de la auditoría No. '.$numeroauditoria.'.';       

        return $mensaje;
    }
    private function mensajeAprobado(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Se autorizo el registro del Turno acuse envío archivo de la auditoría No. '.$numeroauditoria.
                    ', por parte del Titular.';       

        return $mensaje;
    }
}
