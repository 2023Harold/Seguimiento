<?php

namespace App\Http\Controllers;

use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;

class SeguimientoAuditoriaAutorizacionController extends Controller
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
        // $denunciado = DenunciadoSujeto::findOrFail(getSession('diligencia_denunciado_id'));
        // $params = [
        //     'nombre_movimiento' => 'Cita del denunciante.',
        //     'autoriza' => auth()->user()->name,
        //     'cargo' => auth()->user()->puesto,
        // ];

        // $datosConstancia = [
        //     'nombreConstancia' => 'Investigacion/Seguimiento/citardenunciantecitaconstancia',
        //     'parametros' => $params,
        //     'where' => base64_encode(Str::random(5).$cita->id.Str::random(5)),
        // ];

        // $params['where'] = $cita->id;
        
        // $preconstancia = reporte($cita->id, 'Investigacion/Seguimiento/citardenunciantecitaconstancia', $params, 'pdf');
        // $archivorutaxml = reporte($cita->id, 'Investigacion/Seguimiento/citardenunciantecitaconstancia', $params, 'xml');
        // $b64archivoxml = chunk_split(base64_encode(file_get_contents(base_path().'/public/'.$archivorutaxml)));
        $preconstancia=null;
        $b64archivoxml=null;
        $datosConstancia=null;
       

        return view('seguimientoauditoriaautorizacion.form', compact('auditoria', 'preconstancia', 'b64archivoxml', 'datosConstancia'));
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
        $director=User::where('unidad_administrativa_id',substr($auditoria->unidad_administrativa_registro, 0, 4).'00')->where('siglas_rol','DS')->first();
       
        $this->normalizarDatos($request);


        //$ruta = env('APP_RUTA_MINIO').'Expedientes/' . strtoupper(Str::slug($cita->denunciado->expediente->carpeta_expediente)).'/Constancias';
        //$constancia = guardarConstanciasFirmadas($cita, 'constancia', $request, null, $ruta);

        Movimientos::create([
            'tipo_movimiento' => 'Autorización del registro de la auditoría',
            'accion' => 'Registro de la auditoría',
            'accion_id' => $auditoria->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        if ($request->estatus == 'Rechazado') {            
            foreach ($auditoria->acciones as $accionrevision) 
            {
                $accionrevision->update(['revision_jefe'=>'Rechazado']);
            }           
        }

        // $auditoria->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado','constancia' => $constancia->constancia_pdf]);
        $auditoria->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado']);

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del registro de la auditoria No. '.$auditoria->numero_auditoria;
            
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($auditoria->usuarioCreacion->name,$auditoria->usuarioCreacion->puesto,$auditoria->numero_auditoria), now(), $auditoria->usuarioCreacion->unidad_administrativa_id, $auditoria->usuarioCreacion->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($director->name,$director->puesto,$auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id);
            
            setMessage('Se ha autorizado el registro de la auditoría con exito.');

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
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id);
            
            setMessage('Se ha rechazado el registro de la auditoría con exito.');
        }
        

        // return redirect()->route('constancia.mostrarConstancia', ['constancia'=>$constancia, 'rutaCerrar'=>'citardenunciantecita.index']);
        return view('layouts.close');
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

    private function mensajeAprobado(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido autorizado el registro de la auditoría No. '.$numeroauditoria.
                    ', por parte del Titular.';       

        return $mensaje;
    }
}
