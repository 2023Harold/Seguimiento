<?php

namespace App\Http\Controllers\Radicacion;

use App\Http\Controllers\Controller;

use App\Models\Movimientos;
use App\Models\Radicacion;
use Illuminate\Http\Request;

class RadicacionRevisionController extends Controller
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
    public function edit(Radicacion $radicacion)
    {
        $auditoria=$radicacion->auditoria;
        $comparecencia=$auditoria->comparecencia;

         return view('radicacionrevision.form', compact('radicacion','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Radicacion $radicacion)
    {
        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Revisión de la radicación',
            'accion' => 'Radicación',
            'accion_id' => $radicacion->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);        

        if (strlen($radicacion->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $radicacion->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }



        $radicacion->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En validación' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a autorización del superior.' :
            'El rechazo ha sido registrado.'
        );

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Validación de la radicación de la auditoría No. '.$radicacion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.auth()->user()->director->name.', '.auth()->user()->director->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; ha aprobado la validación de la radiación de la auditoría No. '.$radicacion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la validación oportuna de la misma.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id, auth()->user()->director->id);
        }else {
            
            $titulo = 'Rechazo de la radicación de la auditoría No. '.$radicacion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$radicacion->usuarioCreacion->name.', '.$radicacion->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado la radicación de auditoría No. '.$radicacion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a validación.';
            
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $radicacion->usuarioCreacion->unidad_administrativa_id, $radicacion->usuarioCreacion->id);
        }

        return redirect()->route('radicacion.index');
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
}
