<?php

namespace App\Http\Controllers\Informe;

use App\Http\Controllers\Controller;
use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\InformePrimeraEtapa;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;

class InformePrimeraEtapaAutorizacionController extends Controller
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
    public function edit(InformePrimeraEtapa $auditoria)
    {
        $informeprimeraetapa=$auditoria;
        $auditoria=$auditoria->auditoria;
        // dd($informeprimeraetapa);
       
        return view('informeprimeraetapaautorizacion.form', compact('informeprimeraetapa','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AprobarFlujoAutorizacionRequest $request, InformePrimeraEtapa $auditoria)
    { 
        $this->normalizarDatos($request);
        $informeprimeraetapa=$auditoria;

        if ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 3);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        $informeprimeraetapa->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'Se ha autorizado el Informe Primera Etapa de la auditoría con exito.' :
            'El rechazo ha sido registrado.'
        );

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización del Informe Primera Etapa de'.$informeprimeraetapa->tipo .'de la auditoría No. '.$informeprimeraetapa->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '. auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto .':</strong><br>'
                            .auth()->user()->titular->name.', '.auth()->user()->titular->puesto.
                            '; ha aprobado la autorización del Informe Primera Etapa de'.$informeprimeraetapa->tipo.' de la auditoría No. '.$informeprimeraetapa->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la autorización oportuna de la misma.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $informeprimeraetapa->usuarioCreacion->unidad_administrativa_id, $informeprimeraetapa->usuarioCreacion->id);
            
        }else {
            
            $titulo = 'Rechazo del Informe Primera Etapa de'.$informeprimeraetapa->tipo.' de la auditoría No. '.$informeprimeraetapa->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$informeprimeraetapa->usuarioCreacion->name.', '.$informeprimeraetapa->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el Informe Primera Etapa de auditoría No. '.$informeprimeraetapa->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a autorización.';
            
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $informeprimeraetapa->usuarioCreacion->unidad_administrativa_id, $informeprimeraetapa->usuarioCreacion->id);
                            
    }
    Movimientos::create([
            'tipo_movimiento' => 'Autorización del del Informe Primera Etapa',
            'accion' => 'InformePrimeraEtapa',
            'accion_id' => $informeprimeraetapa->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);
        
        return redirect()->route('informeprimeraetapa.index');
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
                    .'Ha sido rechazado el registro del Informe Primera Etapa de auditoría No. '.$numeroauditoria.'.';       

        return $mensaje;
    }

}
