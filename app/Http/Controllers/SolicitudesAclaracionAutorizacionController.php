<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\SolicitudesAclaracion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SolicitudesAclaracionAutorizacionController extends Controller
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
    public function edit(SolicitudesAclaracion $solicitud)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion=AuditoriaAccion::find(getSession('solicitudesauditoriaaccion_id'));

        // $datosConstancia = [           
        //     'nombrereporte' => 'atencionsolicitudesaclaracionconstancia',
        //     'auditoriaseleccionada'=>base64_encode(Str::random(5).$solicitud->auditoria_id.Str::random(5)),
        //     'accionseleccionada'=>base64_encode(Str::random(5).$solicitud->accion_id.Str::random(5)),            
        //     'modelo_principal'=>['tbl'=>$solicitud->getTable(),'vinculo'=>base64_encode(Str::random(5).$solicitud->id.Str::random(5))] ,
        //     'relacion1'=>['tbl_rel'=>$solicitud->constestaciones[0]->getTable(),'col_rel'=>'solicitudaclaracion_id'],
        //     'relacion2'=>null,
        //     'relacion3'=>null, 
        //     'firmante'=>auth()->user()->name,
        //     'firmante_puesto'=>auth()->user()->puesto,         
        // ];

        // $b64archivoxml=reportepdf($datosConstancia['nombrereporte'],1,'Temporal',
        // base64_encode(Str::random(5).$solicitud->auditoria_id.Str::random(5)),
        // base64_encode(Str::random(5).$solicitud->accion_id.Str::random(5)),
        // ['tbl'=>$solicitud->getTable(),'vinculo'=>base64_encode(Str::random(5).$solicitud->id.Str::random(5))],
        // ['tbl_rel'=>$solicitud->constestaciones[0]->getTable(),'col_rel'=>'solicitudaclaracion_id'],
        // null,null,'','','','','','','');

        // $preconstancia ='/storage/temporales/'.$datosConstancia['nombrereporte'] .'.pdf';

        //return view('solicitudesaclaracionautorizacion.form', compact('solicitud', 'accion', 'auditoria', 'preconstancia', 'b64archivoxml', 'datosConstancia'));
        return view('solicitudesaclaracionautorizacion.form', compact('solicitud', 'accion', 'auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SolicitudesAclaracion $solicitud)
    {
        $this->normalizarDatos($request);
        //$ruta = env('APP_RUTA_MINIO').'Expedientes/' . strtoupper(Str::slug($cierre->denunciado->expediente->carpeta_expediente)).'/Constancias';
        //$constancia = guardarConstanciasFirmadas($solicitud, 'constancia_atencion_solicitud', $request, 'constancia');

        Movimientos::create([
            'tipo_movimiento' => 'Autorización de la atención de la solicitud de aclaración',
            'accion' => 'Solicitud de Aclaración',
            'accion_id' => $solicitud->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        $solicitud->update([
            'fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado',
            //'constancia_autorizacion' => $constancia->constancia_pdf,
        ]);

        $director=User::where('unidad_administrativa_id',substr($solicitud->userCreacion->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();
        $jefe=$solicitud->accion->depaasignado;
        $lider=$solicitud->accion->lider;
        $analista=$solicitud->accion->analista;

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del registro de la atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;

            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($analista->name,$analista->puesto,$solicitud), now(), $analista->unidad_administrativa_id, $analista->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($director->name,$director->puesto,$solicitud), now(), $director->unidad_administrativa_id, $director->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($jefe->name,$jefe->puesto,$solicitud), now(), $jefe->unidad_administrativa_id, $jefe->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($lider->name,$lider->puesto,$solicitud), now(), $lider->unidad_administrativa_id, $lider->id);

            setMessage('Se ha autorizado el registro de la atención de la solicitud de aclaración.');
        } else {
            $titulo = 'Rechazo del registro de la atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$solicitud->accion->depaasignado->name.', '.$solicitud->accion->depaasignado->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de la atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';

            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $analista->unidad_administrativa_id, $analista->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$solicitud), now(), $director->unidad_administrativa_id, $director->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($jefe->name,$jefe->puesto,$solicitud), now(), $jefe->unidad_administrativa_id, $jefe->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($lider->name,$lider->puesto,$solicitud), now(), $lider->unidad_administrativa_id, $lider->id);

            setMessage('Se ha rechazado el registro de la calificación y conclusión de la atención de la solicitud de aclaración.');
        }

        //return redirect()->route('constancia.mostrarConstancia', ['constancia'=>$constancia, 'rutaCerrar'=>'solicitudesaclaracionatencion.index']);
        return redirect()->route('solicitudesaclaracionatencion.index');
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

    private function mensajeRechazo(String $nombre, String $puesto, SolicitudesAclaracion $solicitud)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Ha sido rechazado el el registro de la atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.'.';

        return $mensaje;
    }

    private function mensajeAprobado(String $nombre, String $puesto, SolicitudesAclaracion $solicitud)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido autorizado el registro de la atención de la solicitud de aclaración de la Acción No. '.$solicitud->accion->numero.' de la Auditoría No. '.$solicitud->accion->auditoria->numero_auditoria.
                    ', por parte del Titular.';

        return $mensaje;
    }

}
