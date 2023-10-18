<?php

namespace App\Http\Controllers;

use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use Illuminate\Http\Request;

class SeguimientoAuditoriaRevisionController extends Controller
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
    public function show(AuditoriaAccion $auditoria)
    {
        $accion = $auditoria;
        $auditoria = $accion->auditoria;

        return view('seguimientoauditoriarevision.show',compact('accion','auditoria')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Auditoria $auditoria)
    {
        //$denunciado = DenunciadoSujeto::findOrFail(getSession('diligencia_denunciado_id'));

        return view('seguimientoauditoriarevision.form', compact('auditoria'));
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
        if ($request->estatus == 'Aprobado' && count($auditoria->accionesrechazadasjefe)>0) {
            setMessage('Si hay acciones rechazadas no se puede aprobar la auditoría.','error');

            return back()->withInput();
        } 

        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Revisión del registro de la auditoría',
            'accion' => 'Registro de la auditoría',
            'accion_id' => $auditoria->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        if(empty($auditoria->fase_autorizacion))
        {        
            foreach ($auditoria->acciones as $accionrevision) 
            {
                $accionrevision->update(['revision_jefe'=>'En revisión']);
            }
        }

        if (strlen($auditoria->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $auditoria->nivel_autorizacion;
        } elseif ($request->estatus == 'Aprobado') {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 5);
        }

        $auditoria->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En validación' : 'Rechazado', 'nivel_autorizacion' => $nivel_autorizacion]);
        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a validación del superior.' :
            'El rechazo ha sido registrado.'
        );

        if ($request->estatus == 'Aprobado') {
            $titulo = 'Validación del registro de la auditoria No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.auth()->user()->director->name.', '.auth()->user()->director->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado la revisión del registro de la auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se requiere realice la validación oportuna en el módulo Seguimiento.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id, auth()->user()->director->id);
        } else {
            $auditoria->update(['registro_concluido'=>'No']);
            $titulo = 'Rechazo del registro de la auditoria No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$auditoria->usuarioCreacion->name.', '.$auditoria->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
           
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $auditoria->usuarioCreacion->unidad_administrativa_id, $auditoria->usuarioCreacion->id);
            auth()->user()->insertNotificacion($titulo, $this->mensajeRechazo($auditoria->lider->name,$auditoria->lider->puesto,$auditoria->numero_auditoria), now(), $auditoria->lider->unidad_administrativa_id, $auditoria->lider->id);
        }

        return redirect()->route('seguimientoauditoria.index');
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


}
