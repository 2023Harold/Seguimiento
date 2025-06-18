<?php

namespace App\Http\Controllers\Pras;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\Segpras;
use Illuminate\Http\Request;

class PrasTurnoRevisionController extends Controller
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

        return view('prasturnosrevision.form', compact('auditoria','accion','pras'));
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

        Movimientos::create([
            'tipo_movimiento' => 'Revisión del turno del PRAS',
            'accion' => 'PRAS',
            'accion_id' => $pras->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        if (strlen($pras->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $pras->nivel_autorizacion;
        } elseif ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 5);
        }

        $pras->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En validación' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a validación del superior.' :
            'El rechazo ha sido registrado.'
        );

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Validación del registro del turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.auth()->user()->director->name.', '.auth()->user()->director->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado la revisión del registro del turno del PRAS de la accion No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria.
                            ', por lo que se requiere realice la validación oportuna en el apartado de PRAS.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id, auth()->user()->director->id);
        } else {
            $titulo = 'Rechazo del registro del turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$pras->userCreacion->name.', '.$pras->userCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro del turno del PRAS  de la Acción No. '.$pras->accion->numero.' de la Auditoría No. '.$pras->accion->auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';

            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $pras->userCreacion->unidad_administrativa_id, $pras->userCreacion->id);
            //auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($auditoria->lider->name,$auditoria->lider->puesto,$auditoria->numero_auditoria), now(), $auditoria->lider->unidad_administrativa_id, $auditoria->lider->id);
        }

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
}
