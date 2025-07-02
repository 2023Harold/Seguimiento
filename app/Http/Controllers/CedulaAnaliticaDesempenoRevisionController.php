<?php

namespace App\Http\Controllers;

use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Cedula;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;

class CedulaAnaliticaDesempenoRevisionController extends Controller
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
    public function edit(Cedula $cedula)
    {     
        
        // $cedula=$auditoria;
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        
        return view('cedulaanaliticadesempenorevision.form',compact('auditoria','cedula'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AprobarFlujoAutorizacionRequest $request, Cedula $cedula)
    {
        // dd(1);
        $this->normalizarDatos($request);
        // $cedula=$auditoria;
        $mensaje = "";
 
        Movimientos::create([
            'tipo_movimiento' => 'Autorización de la Cédula',
            'accion' => 'Cedula',
            'accion_id' => $cedula->id,
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

        $cedula->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'Se ha autorizado la cédula de la auditoría con exito.' :
            'El rechazo ha sido registrado.'
        );

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización de la cédula de la auditoría No. '.$cedula->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$cedula->usuarioCreacion->name.', '.$cedula->usuarioCreacion->puesto.':</strong><br>'
                            .'Se ha aprobado la autorización de la cedula de la auditoría No. '.$cedula->auditoria->numero_auditoria;                          
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $cedula->usuarioCreacion->unidad_administrativa_id, $cedula->usuarioCreacion->id);
        }else {
            
            $titulo = 'Rechazo de la cédula de la auditoría No. '.$cedula->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$cedula->usuarioCreacion->name.', '.$cedula->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazada la cédula de la auditoría No. '.$cedula->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a autorización.';
            
                            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $cedula->usuarioCreacion->unidad_administrativa_id, $cedula->usuarioCreacion->id);
                            
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
                    .'Ha sido rechazado el registro de la cedula de auditoría No. '.$numeroauditoria.'.';       

        return $mensaje;
    }

    private function mensajeAprobado(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido autorizado el registro de la cedula de la auditoría No. '.$numeroauditoria.
                    ', por parte del Titular.';       

        return $mensaje;
    }

}
