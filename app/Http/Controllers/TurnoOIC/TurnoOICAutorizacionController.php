<?php

namespace App\Http\Controllers\TurnoOIC;

use App\Http\Controllers\Controller;
use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\TurnoOIC;
use App\Models\User;
use Illuminate\Http\Request;

class TurnoOICAutorizacionController extends Controller
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
    public function edit(TurnoOIC $auditoria)
    {
        $turnooic=$auditoria;
        $auditoria=$auditoria->auditoria;
       
        return view('turnooicautorizacion.form', compact('turnooic','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AprobarFlujoAutorizacionRequest $request, TurnoOIC $auditoria)
    {
        $this->normalizarDatos($request);
        $turnooic=$auditoria;

        Movimientos::create([
            'tipo_movimiento' => 'Autorización del Turno al Órgano Interno de Control',
            'accion' => 'TurnoOIC',
            'accion_id' => $turnooic->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);
        $auditoria = Auditoria::find($turnooic->auditoria_id);
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

        if ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 3);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        $turnooic->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'Se ha autorizado el Turno al Órgano Interno de Control de la auditoría con exito.' :
            'El rechazo ha sido registrado.'
        );
        $url = route('turnooic.index');
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave( $turnooic).'/Aut')->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion); 
        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del Turno al OIC';
            $mensaje = '<strong>Estimado(a) '.$lider->name.', '.$lider->puesto.':</strong><br>'
                            .auth()->user()->titular->name.', '.auth()->user()->titular->puesto.
                            '; ha aprobado la autorización del Turno al Órgano Interno de Control de la auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se requiere realice la autorización oportuna de la misma.';
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), $turnooic->usuarioCreacion->unidad_administrativa_id, $turnooic->usuarioCreacion->id);
            //auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($director->name,$director->puesto,$turnooic->auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($director->name,$director->puesto,$auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id, GenerarLlave($turnooic).'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($jefe->name,$jefe->puesto,$auditoria->numero_auditoria), now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave($turnooic).'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($turnooic).'/Consulta',$url);

        }else {
            
            $titulo = 'Rechazo del Turno al OIC ';
            $mensaje = '<strong>Estimado(a) '.$lider->name.', '.$lider->puesto.':</strong><br>'
                            .'Ha sido rechazado el Turno al Órgano Interno de Control de auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a autorización.';
            
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), $turnooic->usuarioCreacion->unidad_administrativa_id, $turnooic->usuarioCreacion->id);
            //auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$turnooic->auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($turnooic).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id, GenerarLlave($turnooic).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name,$jefe->puesto,$auditoria->numero_auditoria), now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave($turnooic).'/Rechazo',$url);

    }
    return redirect()->route('turnooic.index');
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
                    .'Ha sido rechazado el registro de la radicación de auditoría No. '.$numeroauditoria.'.';       

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
