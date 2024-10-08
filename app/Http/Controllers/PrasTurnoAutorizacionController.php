<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\Segpras;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PrasTurnoAutorizacionController extends Controller
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
    public function edit(Segpras $pras)
    {
       
         $auditoria = Auditoria::find(getSession('auditoria_id'));
         $accion = AuditoriaAccion::find(getSession('prasauditoriaaccion_id'));

        //  $datosConstancia = [           
        //     'nombrereporte' => 'prasauditoriaconstancia',
        //     'auditoriaseleccionada'=>base64_encode(Str::random(5).$pras->auditoria_id.Str::random(5)),
        //     'accionseleccionada'=>base64_encode(Str::random(5).$pras->accion_id.Str::random(5)),            
        //     'modelo_principal'=>['tbl'=>$pras->getTable(),'vinculo'=>base64_encode(Str::random(5).$pras->id.Str::random(5))],
        //     'relacion1'=>null,
        //     'relacion2'=>null,
        //     'relacion3'=>null,
        //     'firmante'=>auth()->user()->name,
        //     'firmante_puesto'=>auth()->user()->puesto,          
        // ];

        // $b64archivoxml=reportepdf($datosConstancia['nombrereporte'],1,'Temporal',
        //                          base64_encode(Str::random(5).$pras->auditoria_id.Str::random(5)),
        //                          base64_encode(Str::random(5).$pras->accion_id.Str::random(5)),
        //                          ['tbl'=>$pras->getTable(),'vinculo'=>base64_encode(Str::random(5).$pras->id.Str::random(5))],
        //                          null,null,null,'','','','','',auth()->user()->name, auth()->user()->puesto);
        
        // $preconstancia ='/storage/temporales/'.$datosConstancia['nombrereporte'] .'.pdf';

        //return view('prasturnosautorizacion.form', compact('pras', 'accion', 'auditoria', 'preconstancia', 'b64archivoxml', 'datosConstancia'));
        return view('prasturnosautorizacion.form', compact('pras', 'accion', 'auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Segpras $pras)
    {
        $this->normalizarDatos($request);
        //$ruta = env('APP_RUTA_MINIO').'Expedientes/' . strtoupper(Str::slug($cierre->denunciado->expediente->carpeta_expediente)).'/Constancias';
        //$constancia = guardarConstanciasFirmadas($pras, 'constancia_turno_pras', $request, 'constancia');

        Movimientos::create([
            'tipo_movimiento' => 'Autorización del turno del PRAS',
            'accion' => 'PRAS',
            'accion_id' => $pras->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        $pras->update([
            'fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado',
            //'constancia_turno' => $constancia->constancia_pdf,
        ]);


        $director=User::where('unidad_administrativa_id',substr($pras->userCreacion->unidad_administrativa_id, 0, 4).'00')->where('siglas_rol','DS')->first();
        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del registro del turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria;

            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($pras->userCreacion->name,$pras->userCreacion->puesto,$pras), now(), $pras->userCreacion->unidad_administrativa_id, $pras->userCreacion->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($director->name,$director->puesto,$pras), now(), $director->unidad_administrativa_id, $director->id);

            setMessage('Se ha autorizado el registro del turno del PRAS con exito.');
        } else {
            $titulo = 'Rechazo del registro del turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$pras->userCreacion->name.', '.$pras->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro del turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';

            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $pras->userCreacion->unidad_administrativa_id, $pras->userCreacion->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$pras), now(), $director->unidad_administrativa_id, $director->id);

            setMessage('Se ha rechazado el registro del turno del PRAS con exito.');
        }

        //return redirect()->route('constancia.mostrarConstancia', ['constancia'=>$constancia, 'rutaCerrar'=>'prasturno.index']);
        return redirect()->route('prasturno.index');
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

    private function mensajeRechazo(String $nombre, String $puesto, Segpras $pras)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Ha sido rechazado el registro del turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria.'.';

        return $mensaje;
    }

    private function mensajeAprobado(String $nombre, String $puesto, Segpras $pras)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido autorizado el registro del turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria.
                    ', por parte del Titular.';

        return $mensaje;
    }
}
