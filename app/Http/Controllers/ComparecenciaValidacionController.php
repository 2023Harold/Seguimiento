<?php

namespace App\Http\Controllers;

use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Comparecencia;
use App\Models\Movimientos;
use Illuminate\Http\Request;

class ComparecenciaValidacionController extends Controller
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
        $auditoria=$comparecencia->auditoria;
        $comparecencia=$auditoria->comparecencia;
       
         return view('comparecenciavalidacion.form', compact('comparecencia','auditoria'));
         
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AprobarFlujoAutorizacionRequest $request, Comparecencia $comparecencia)
    {
        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Validación de la comparecencia',
            'accion' => 'Comparecencia',
            'accion_id' => $comparecencia->id,
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

        $comparecencia->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'Se ha autorizado la comparecencia de la auditoría con exito.' :
            'El rechazo ha sido registrado.'
        );

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Autorización de la comparecencia de la auditoría No. '.$comparecencia->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.auth()->user()->titular->name.', '.auth()->user()->titular->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; ha aprobado la validación de la comparecencia de la auditoría No. '.$comparecencia->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la autorización oportuna de la misma.';
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->titular->unidad_administrativa_id, auth()->user()->titular->id);
        }else {
            
            $titulo = 'Rechazo de la comparecencia de la auditoría No. '.$comparecencia->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$comparecencia->usuarioCreacion->name.', '.$comparecencia->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado la comparecencia de auditoría No. '.$comparecencia->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a validación.';
            
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $comparecencia->usuarioCreacion->unidad_administrativa_id, $comparecencia->usuarioCreacion->id);
        }

         return redirect()->route('comparecenciaacta.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
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
