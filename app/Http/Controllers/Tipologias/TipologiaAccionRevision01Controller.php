<?php

namespace App\Http\Controllers\Tipologias;

use App\Http\Controllers\Controller;
use App\Models\CatalogoTipologia;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use Illuminate\Http\Request;


class TipologiaAccionRevision01Controller extends Controller
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
    public function edit(AuditoriaAccion $accion)
    {
        $tipologia=$accion;
        // $auditoria=$auditoria->auditoria;
       
        return view('tipologiaaccionrevision01.form', compact('tipologia','accion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AuditoriaAccion $accion)
    {
       $this->normalizarDatos($request);
        $tipologia=$accion;
        Movimientos::create([
            'tipo_movimiento' => 'Registro de la Tipologia',
            'accion' => 'CatalogoTipologia',
            'accion_id' => $accion->tipologia_id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        if ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 3);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        $tipologia->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'Se ha autorizado la tipologia de la acción con exito.' :
            'El rechazo ha sido registrado.'
        );

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización de la tipologia de la accion No. '.$accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.auth()->user()->lider->name.', '.auth()->user()->lider->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; ha aprobado la autorización de la accion No. '.$accion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la autorización oportuna de la misma.';
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->titular->unidad_administrativa_id, auth()->user()->titular->id);
        }else {
            
            $titulo = 'Rechazo de la tipologia de la acción No. '.$accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$accion->auditoria->numero_auditoria->usuarioCreacion->name.', '.$accion->auditoria->numero_auditoria->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado la tipologia de la acción No. '.$accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $accion->usuarioCreacion->unidad_administrativa_id, $accion->usuarioCreacion->id);
        }

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
                    .'Ha sido rechazado el registro de la tipologia de la auditoría No. '.$numeroauditoria.'.';       

        return $mensaje;
    }
}
