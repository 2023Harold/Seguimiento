<?php

namespace App\Http\Controllers\Recomendaciones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\Notificacion;
use App\Models\Recomendaciones;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecomendacionesAutorizacionController extends Controller
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
    public function edit(Recomendaciones $recomendacion)
    {
        $auditoria = Auditoria::find($recomendacion->auditoria_id);
        $accion=AuditoriaAccion::find($recomendacion->accion_id);

        // $datosConstancia = [           
        //     'nombrereporte' => 'atencionrecomendacionconstancia',
        //     'auditoriaseleccionada'=>base64_encode(Str::random(5).$recomendacion->auditoria_id.Str::random(5)),
        //     'accionseleccionada'=>base64_encode(Str::random(5).$recomendacion->accion_id.Str::random(5)),            
        //     'modelo_principal'=>['tbl'=>$recomendacion->getTable(),'vinculo'=>base64_encode(Str::random(5).$recomendacion->id.Str::random(5))],
        //     'relacion1'=>['tbl_rel'=>$recomendacion->constestaciones[0]->getTable(),'col_rel'=>'recomendacion_id'],
        //     'relacion2'=>null,
        //     'relacion3'=>null, 
        //     'firmante'=>auth()->user()->name,
        //     'firmante_puesto'=>auth()->user()->puesto,          
        // ];       

        // $b64archivoxml=reportepdf($datosConstancia['nombrereporte'],1,'Temporal',
        //                          base64_encode(Str::random(5).$recomendacion->auditoria_id.Str::random(5)),
        //                          base64_encode(Str::random(5).$recomendacion->accion_id.Str::random(5)),
        //                          ['tbl'=>$recomendacion->getTable(),'vinculo'=>base64_encode(Str::random(5).$recomendacion->id.Str::random(5))],
        //                          ['tbl_rel'=>$recomendacion->constestaciones[0]->getTable(),'col_rel'=>'recomendacion_id'],
        //                          null,null,'','','','','','','');

        // $preconstancia ='/storage/temporales/'.$datosConstancia['nombrereporte'] .'.pdf';

        //return view('recomendacionesatencionautorizacion.form', compact('recomendacion', 'accion', 'auditoria', 'preconstancia', 'b64archivoxml', 'datosConstancia'));
         return view('recomendacionesatencionautorizacion.form', compact('recomendacion', 'accion', 'auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recomendaciones $recomendacion)
    {
        $auditoria = Auditoria::find($recomendacion->auditoria_id);
        if(getSession('cp')==2022){
            $jefe=User::where('unidad_administrativa_id', substr($recomendacion->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();
            $lider=$recomendacion->accion->lider;
            $analista=$recomendacion->accion->analista;
        }else{
            $jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp;
            $lider = $auditoria->lidercp; 
        }
        $auditoria = Auditoria::find($recomendacion->auditoria_id);
        $this->normalizarDatos($request);
        //$ruta = env('APP_RUTA_MINIO').'Expedientes/' . strtoupper(Str::slug($cierre->denunciado->expediente->carpeta_expediente)).'/Constancias';
        //$constancia = guardarConstanciasFirmadas($recomendacion, 'constancia_atencion_recomendacion', $request, 'constancia');

        Movimientos::create([
            'tipo_movimiento' => 'Autorización de la calificación y conclusión de la atención de la recomendación',
            'accion' => 'Recomendación',
            'accion_id' => $recomendacion->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        $recomendacion->update([
            'fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado',
            //'constancia_autorizacion' => $constancia->constancia_pdf,
        ]);


        $director=User::where('unidad_administrativa_id',substr($recomendacion->userCreacion->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();
        /** Leer notifiacion si hay  **/
        $url = route('recomendacionesatencion.index');
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave($recomendacion).'/Aut')->first();
        $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($recomendacion)."/Rechazo")->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);
        /** fin Leer notifiacion si hay  **/
        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del registro de la atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;
            //auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($recomendacion->accion->depaasignado->name,$recomendacion->accion->depaasignado->puesto,$recomendacion), now(), $recomendacion->accion->depaasignado->unidad_administrativa_id, $recomendacion->accion->depaasignado->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($director->name,$director->puesto,$recomendacion), now(), $director->unidad_administrativa_id, $director->id, GenerarLlave($recomendacion).'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($jefe->name, $jefe->puesto, $recomendacion),now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave($recomendacion).'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($lider->name, $lider->puesto, $recomendacion),now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($recomendacion).'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($recomendacion->userCreacion->name,$recomendacion->userCreacion->puesto,$recomendacion), now(), $recomendacion->userCreacion->unidad_administrativa_id, $recomendacion->userCreacion->id,GenerarLlave($recomendacion).'/Consulta',$url);
            setMessage('Se ha autorizado la atención de la recomendación con exito.');
        } else {
            $titulo = 'Rechazo del registro de la atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$recomendacion->userCreacion->name.', '.$recomendacion->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de la atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';

            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), $recomendacion->accion->depaasignado->unidad_administrativa_id, $recomendacion->accion->depaasignado->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$recomendacion), now(), $director->unidad_administrativa_id, $director->id,GenerarLlave($recomendacion).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name, $jefe->puesto, $recomendacion),now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave($recomendacion).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($lider->name, $lider->puesto, $recomendacion),now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($recomendacion).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $recomendacion->userCreacion->unidad_administrativa_id, $recomendacion->userCreacion->id, GenerarLlave($recomendacion).'/Rechazo',$url);
            setMessage('Se ha rechazado el registro de la atencion con exito.');
        }

        //return redirect()->route('constancia.mostrarConstancia', ['constancia'=>$constancia, 'rutaCerrar'=>'recomendacionesatencion.index']);
        return redirect()->route('recomendacionesatencion.index');
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

    private function mensajeRechazo(String $nombre, String $puesto, Recomendaciones $recomendacion)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Ha sido rechazado el el registro de la atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria.'.';

        return $mensaje;
    }

    private function mensajeAprobado(String $nombre, String $puesto, Recomendaciones $recomendacion)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido autorizado el registro de la atención de la recomendación de la Acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria.
                    ', por parte del Titular.';

        return $mensaje;
    }
}
