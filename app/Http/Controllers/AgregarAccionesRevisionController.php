<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;

class AgregarAccionesRevisionController extends Controller
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
        $auditoria = Auditoria::find(getSession('auditoriacp_id'));
        return view('agregaraccionesrevision.form', compact('accion','auditoria'));
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
        
        $auditoria = $accion->auditoria;
        if ($request->estatus == 'Aprobado' && count($auditoria->accionesrechazadasjefe)>0) {
            setMessage('Si hay acciones rechazadas no se puede aprobar la auditoría.','error');

            return back()->withInput();
        } 

                                    
        $accion->update(['fase_revision'=>$request->estatus == 'Aprobado' ? 'En validación' : 'Rechazado']);
            
               
       $director = usuariocp(substr($accion->usuarioCreacion->unidad, 0, 4).'00')->where('siglas_rol','DS')->first();
       
      
        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Revisión de la acción del registro de la auditoría',
            'accion' => 'Revisión Acción Registro Auditoría',
            'accion_id' => $accion->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        
        if (strlen($auditoria->nivel_autorizacion) == 3 || strlen($auditoria->nivel_autorizacion) == 4) {
            $nivel_autorizacion = $auditoria->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 5);
        }

        setMessage($request->estatus == 'Aprobado' ?
            'La aprobación ha sido registrada y se ha enviado a revisión del superior.' :
            'El rechazo ha sido registrado.'
        );

        if ($request->estatus == 'Aprobado') {
            $auditoria->update([ 'nivel_autorizacion' => $nivel_autorizacion]);
            $titulo = 'Validación del registro de la acción No.'.$accion->numero.' de la auditoria No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$director->name.', '.$director->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado el registro de la acción No.'.$accion->numero.' de la auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se requiere realice la revisión oportuna en el módulo Seguimiento.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $director->unidad_administrativa_id, $director->id);
        } else {

            $titulo = 'Rechazo de la acción No.'.$accion->numero.' de la auditoria No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$auditoria->usuarioCreacion->name.', '.$auditoria->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de la acción No.'.$accion->numero.'de la auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $auditoria->usuarioCreacion->unidad, $auditoria->usuarioCreacion->id);
        }

        return redirect()->route('agregaracciones.index');

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
    public function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }
}
