<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAuditoria;
use App\Models\Movimientos;
use App\Models\SUTIC\EntidadFiscalizableIntra;
use App\Models\User;
use Illuminate\Http\Request;

class AgregarAccionesRevision01Controller extends Controller
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

        return view('agregaraccionesrevision01.form', compact('accion','auditoria'));
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
        /*if ($request->estatus == 'Aprobado' && count($auditoria->accionesrechazadaslider)>0) {
            setMessage('Si hay acciones rechazadas no se puede aprobar la auditoría.','error');

            return back()->withInput();
        } */

        $accion->update(['revision_lider' => $request->estatus == 'Aprobado' ? 'En revisión' : 'Rechazado']);
        $accion->update(['fase_revision' => $request->estatus == 'Aprobado' ? 'En revisión' : 'Rechazado']);
        $cuenta_publicaSession = getSession('cp');
        if($cuenta_publicaSession !=2022){
            $jefe = auth()->user()->jefe;
            //$jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp; 
            $lider = $auditoria->lidercp; 
        }else{
            $jefe=usuariocp(substr($accion->usuarioCreacion->unidad, 0, 5).'0')->where('siglas_rol','JD')->first();
            $analista=$accion->analista;
            $lider=$accion->lider;
        }
             
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

        $notificacion=auth()->user()->notificaciones()->where('llave', GenerarLlave($accion).'/Rev01')->first();
        $notificacionRechazo=auth()->user()->notificaciones()->where('llave', GenerarLlave($accion).'/Rechazo')->first();
        $LeerNotificacion = auth()->user()->NotMarcarLeido($notificacion);
        $LeerNotificacionR = auth()->user()->NotMarcarLeido($notificacionRechazo);
        

        if ($request->estatus == 'Aprobado') {
            $url =  route("agregaraccionesrevision.edit",$accion);
            $auditoria->update([ 'nivel_autorizacion' => $nivel_autorizacion]);
            $titulo = 'Revisión del registro de la auditoria No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$jefe->name.', '.$jefe->puesto.':</strong><br>'
                            .auth()->user()->name.', '.auth()->user()->puesto.
                            '; se ha aprobado el registro de la acción No.'.$accion->numero.' de la auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se requiere realice la revisión oportuna en el módulo Seguimiento.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $jefe->unidad_administrativa_id, $jefe->id,GenerarLlave($accion).'/Rev',$url);
        } else {
            $url =  route("agregaracciones.index");
            $titulo = 'Rechazo del registro de la acción No.'.$accion->numero.'  auditoria No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$analista->name.', '.$analista->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $analista->unidad_administrativa_id, $analista->id,GenerarLlave($accion).'/Rechazo',$url);
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
