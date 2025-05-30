<?php

namespace App\Http\Controllers;

use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\AcuerdoConclusion;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;

class AcuerdoConclusionAutorizacionController extends Controller
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
    public function edit(AcuerdoConclusion $auditoria)
    {
        $acuerdoconclusion=$auditoria;
        $auditoria=$auditoria->auditoria;
       
         return view('acuerdoconclusionautorizacion.form', compact('acuerdoconclusion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AprobarFlujoAutorizacionRequest $request, AcuerdoConclusion $auditoria)
    {
        $this->normalizarDatos($request);
        $acuerdoconclusion=$auditoria;
        $mensaje = "";
 
        Movimientos::create([
            'tipo_movimiento' => 'Autorización del acuerdo de conclusión',
            'accion' => 'AcuerdoConclusion',
            'accion_id' => $acuerdoconclusion->id,
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

        $acuerdoconclusion->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'Se ha autorizado el Acuerdo de Conclusión de la auditoría con exito.' :
            'El rechazo ha sido registrado.'
        );

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del Acuerdo de conclusión de la auditoría No. '.$acuerdoconclusion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$acuerdoconclusion->usuarioCreacion->name.', '.$acuerdoconclusion->usuarioCreacion->puesto.':</strong><br>'
                            .'Se ha aprobado la autorización del Acuerdo de Conclusión de la auditoría No. '.$acuerdoconclusion->auditoria->numero_auditoria;                          
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $acuerdoconclusion->usuarioCreacion->unidad_administrativa_id, $acuerdoconclusion->usuarioCreacion->id);
        }else {
            
        //     $titulo = 'Rechazo Acuerdo de Conclusión de la auditoría No. '.$acuerdoconclusion->auditoria->numero_auditoria;
        //     $mensaje = '<strong>Estimado(a) '.$acuerdoconclusion->usuarioCreacion->name.', '.$acuerdoconclusion->usuarioCreacion->puesto.':</strong><br>'
        //                     .'Ha sido rechazado el Acuerdo de Conclusión de la auditoría No. '.$acuerdoconclusion->auditoria->numero_auditoria.
        //                     ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a autorización.';
            
        //                     auth()->user()->insertNotificacion($titulo, $mensaje, now(), $acuerdoconclusion->usuarioCreacion->unidad_administrativa_id, $acuerdoconclusion->usuarioCreacion->id);
                            
        }
    return redirect()->route('acuerdoconclusion.index');
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
