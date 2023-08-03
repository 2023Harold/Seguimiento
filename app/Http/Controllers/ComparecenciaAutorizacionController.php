<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Comparecencia;
use App\Models\Movimientos;
use App\Models\Radicacion;
use App\Models\User;

class ComparecenciaAutorizacionController extends Controller
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
    public function edit(Comparecencia $comparecencia)
    {
        $auditoria = $comparecencia->auditoria;
        $params = [
            'nombre_movimiento' => 'Registro de la comparecencia.',
            'director' => $comparecencia->auditoria->directorasignado->name,
            'autoriza' => auth()->user()->name,
            'cargo' => auth()->user()->puesto,
        ];

        $datosConstancia = [
            'nombreConstancia' => 'Fiscalizacion/Seguimiento/comparecenciaconstancia',
            'parametros' => $params,
            'where' => base64_encode(Str::random(5).$comparecencia->id.Str::random(5)),
        ];

        $params['where'] = $comparecencia->id;

         $preconstancia = reporte($comparecencia->id, 'Fiscalizacion/Seguimiento/comparecenciaconstancia', $params, 'pdf');
         $archivorutaxml = reporte($comparecencia->id, 'Fiscalizacion/Seguimiento/comparecenciaconstancia', $params, 'xml');
         $b64archivoxml = chunk_split(base64_encode(file_get_contents(base_path().'/public/'.$archivorutaxml)));

         return view('comparecenciaautorizacion.form', compact('comparecencia','auditoria', 'preconstancia', 'b64archivoxml', 'datosConstancia', 'archivorutaxml'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comparecencia $comparecencia)
    {
        $this->normalizarDatos($request);
        //$ruta = env('APP_RUTA_MINIO').'Expedientes/' . strtoupper(Str::slug($cierre->denunciado->expediente->carpeta_expediente)).'/Constancias';
        $constancia = guardarConstanciasFirmadas($comparecencia, 'constancia_comparecencia', $request, 'constancia');

        Movimientos::create([
            'tipo_movimiento' => 'Autorización de la comparecencia',
            'accion' => 'Comparecencia',
            'accion_id' => $comparecencia->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);       
       
        $comparecencia->update([
            'fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado',
            'constancia' => $constancia->constancia_pdf,
        ]);

        
        $director=User::where('unidad_administrativa_id',substr($comparecencia->auditoria->unidad_administrativa_registro, 0, 4).'00')->where('siglas_rol','DS')->first();
        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del registro de la comparecencia auditoria No. '.$comparecencia->auditoria->numero_auditoria;
            
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($comparecencia->usuarioCreacion->name,$comparecencia->usuarioCreacion->puesto,$comparecencia->auditoria->numero_auditoria), now(), $comparecencia->usuarioCreacion->unidad_administrativa_id, $comparecencia->usuarioCreacion->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeAprobado($director->name,$director->puesto,$comparecencia->auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id);
            
            setMessage('Se ha autorizado el registro de la comparecencia de la auditoría con exito.');
        } else {
            $titulo = 'Rechazo del registro de comparecencia de la auditoría No. '.$comparecencia->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$comparecencia->usuarioCreacion->name.', '.$comparecencia->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de la comparecencia de auditoría No. '.$comparecencia->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $comparecencia->usuarioCreacion->unidad_administrativa_id, $comparecencia->usuarioCreacion->id);           
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($director->name,$director->puesto,$comparecencia->auditoria->numero_auditoria), now(), $director->unidad_administrativa_id, $director->id);
            
            setMessage('Se ha rechazado el registro de la radicación de la auditoría con exito.');
        }

        return redirect()->route('constancia.mostrarConstancia', ['constancia'=>$constancia, 'rutaCerrar'=>'comparecencia.index']);
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
                    .'Ha sido rechazado el registro de la comparecencia de auditoría No. '.$numeroauditoria.'.';       

        return $mensaje;
    }

    private function mensajeAprobado(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido autorizado el registro de comparecencia de la auditoría No. '.$numeroauditoria.
                    ', por parte del Titular.';       

        return $mensaje;
    }
}
