<?php

namespace App\Http\Controllers\PliegosObservacion;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\PliegosDocumento;
use App\Models\PliegosObservacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PliegosObservacionAutorizacionController extends Controller
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
    public function edit(PliegosObservacion $pliegosobservacion)
    {
        $accion=AuditoriaAccion::find(getSession('pliegosobservacionauditoriaaccion_id'));
        $auditoria = $accion->auditoria;

        // $datosConstancia = [           
        //     'nombrereporte' => 'atencionpliegoobservacionconstancia',
        //     'auditoriaseleccionada'=>base64_encode(Str::random(5).$pliegosobservacion->auditoria_id.Str::random(5)),
        //     'accionseleccionada'=>base64_encode(Str::random(5).$pliegosobservacion->accion_id.Str::random(5)),            
        //     'modelo_principal'=>['tbl'=>$pliegosobservacion->getTable(),'vinculo'=>base64_encode(Str::random(5).$pliegosobservacion->id.Str::random(5))],
        //     'relacion1'=>['tbl_rel'=>$pliegosobservacion->constestaciones[0]->getTable(),'col_rel'=>'pliegosobservacion_id'],
        //     'relacion2'=>null,
        //     'relacion3'=>null,
        //     'firmante'=>auth()->user()->name,
        //     'firmante_puesto'=>auth()->user()->puesto,          
        // ];       

        // $b64archivoxml=reportepdf($datosConstancia['nombrereporte'],1,'Temporal',
        //                          base64_encode(Str::random(5).$pliegosobservacion->auditoria_id.Str::random(5)),
        //                          base64_encode(Str::random(5).$pliegosobservacion->accion_id.Str::random(5)),
        //                          ['tbl'=>$pliegosobservacion->getTable(),'vinculo'=>base64_encode(Str::random(5).$pliegosobservacion->id.Str::random(5))],
        //                          ['tbl_rel'=>$pliegosobservacion->constestaciones[0]->getTable(),'col_rel'=>'pliegosobservacion_id'],
        //                          null,null,'','','','','',auth()->user()->name, auth()->user()->puesto);

        // $preconstancia ='/storage/temporales/'.$datosConstancia['nombrereporte'] .'.pdf';


         //return view('pliegosobservacionautorizacion.form', compact('pliegosobservacion', 'accion', 'auditoria', 'preconstancia', 'b64archivoxml', 'datosConstancia' ));
         return view('pliegosobservacionautorizacion.form', compact('pliegosobservacion', 'accion', 'auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PliegosObservacion $pliegosobservacion)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $this->normalizarDatos($request);
        //$ruta = env('APP_RUTA_MINIO').'Expedientes/' . strtoupper(Str::slug($cierre->denunciado->expediente->carpeta_expediente)).'/Constancias';
        //$constancia = guardarConstanciasFirmadas($pliegosobservacion, 'constancia_atencion_pliego', $request, 'constancia');

        Movimientos::create([
            'tipo_movimiento' => 'Autorización de la atención del pliego de observación',
            'accion' => 'Pliegos de observación',
            'accion_id' => $pliegosobservacion->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        $pliegosobservacion->update([
            'fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado',
            //'constancia_autorizacion' => $constancia->constancia_pdf,
        ]);
        if(getSession('cp')==2022){
            $director=User::where('unidad_administrativa_id',substr($pliegosobservacion->userCreacion->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();
            $jefe=$pliegosobservacion->accion->depaasignado;
            $lider=$pliegosobservacion->accion->lider;
            $analista=$pliegosobservacion->accion->analista;
        }else{
            $director = $auditoria->directorasignado;
            $jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp;
            $lider = $auditoria->lidercp; 
        }
        $url = route('pliegosobservacionatencion.index');
        $notificacion=auth()->user()->notificaciones()->where('llave',GenerarLlave($pliegosobservacion).'/Aut')->first();
        $notificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave($pliegosobservacion)."/Rechazo")->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del registro de la calificación y conclusión del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;
            
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($analista->name,$analista->puesto,$pliegosobservacion), now(), $analista->unidad_administrativa_id, $analista->id, GenerarLlave($pliegosobservacion).'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($director->name,$director->puesto,$pliegosobservacion), now(), $director->unidad_administrativa_id, $director->id, GenerarLlave($pliegosobservacion).'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($jefe->name,$jefe->puesto,$pliegosobservacion), now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave($pliegosobservacion).'/Consulta',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($lider->name,$lider->puesto,$pliegosobservacion), now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($pliegosobservacion).'/Consulta',$url);

            setMessage('Se ha autorizado el registro de la calificación y conclusión de la atención del pliego de observación.');
        } else {
            $titulo = 'Rechazo del registro de la calificación y conclusión de la atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$analista->name.', '.$analista->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de la calificación y conclusión de la atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';

            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $analista->unidad_administrativa_id, $analista->id, GenerarLlave($pliegosobservacion).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$pliegosobservacion), now(), $director->unidad_administrativa_id, $director->id, GenerarLlave($pliegosobservacion).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name,$jefe->puesto,$pliegosobservacion), now(), $jefe->unidad_administrativa_id, $jefe->id, GenerarLlave($pliegosobservacion).'/Rechazo',$url);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($lider->name,$lider->puesto,$pliegosobservacion), now(), $lider->unidad_administrativa_id, $lider->id, GenerarLlave($pliegosobservacion).'/Rechazo',$url);


            setMessage('Se ha rechazado el registro de la calificación y conclusión del pliego de observación.');
        }

        //return redirect()->route('constancia.mostrarConstancia', ['constancia'=>$constancia, 'rutaCerrar'=>'pliegosobservacionatencion.index']);
        return redirect()->route('pliegosobservacionatencion.index');
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

    private function mensajeRechazo(String $nombre, String $puesto, PliegosObservacion $pliegosobservacion)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Ha sido rechazado el registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.'.';

        return $mensaje;
    }

    private function mensajeAprobado(String $nombre, String $puesto, PliegosObservacion $pliegosobservacion)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido autorizado el registro de atención del pliego de observación de la Acción No. '.$pliegosobservacion->accion->numero.' de la Auditoría No. '.$pliegosobservacion->accion->auditoria->numero_auditoria.
                    ', por parte del Titular.';

        return $mensaje;
    }
}
