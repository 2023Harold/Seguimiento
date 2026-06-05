<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\AuditoriaUsuarios;
use App\Models\Movimientos;
use App\Models\User;
use Illuminate\Http\Request;

class AgregarAccionesAutorizacionController extends Controller
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
        return view('agregaraccionesautorizacion.form', compact('accion','auditoria'));
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
        if ($request->estatus == 'Aprobado'){                               
                $accion->update(['fase_revision'=>$request->estatus == 'Aprobado' ? 'Autorizado' : 'Rechazado']);
        }       
        $jefe=User::where('unidad_administrativa_id', substr($auditoria->usuarioCreacion->unidad_administrativa_id, 0, 5).'0')->first();
        $cuenta_publicaSession = getSession('cp');
        if($cuenta_publicaSession !=2022){
            $jefe = $auditoria->jefedepartamentoencargado;
            $analista = $auditoria->analistacp; 
        }else{
            $jefe=usuariocp(substr($accion->usuarioCreacion->unidad, 0, 5).'0')->where('siglas_rol','JD')->first();
            $analista=$accion->analista;
        }
        
        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Revisión del registro de la auditoría',
            'accion' => 'Registro de las acciones',
            'accion_id' => $auditoria->id,
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
            
            if (usaEquipoTrabajo()){
                $registroAnalista = AuditoriaUsuarios::where('auditoria_id', $auditoria->id)->where('rol_code', 'Analista')->where('estatus', 'Activo')->first();

                $titulo = 'Registro de la auditoria No. '.$auditoria->numero_auditoria. ' Autorizada';
                $mensaje = '<strong>Estimado(a) '.$analista->name.', '.$analista->puesto.':</strong><br>'
                                .auth()->user()->name.', '.auth()->user()->puesto.
                                '; se ha autorizado el registro de la auditoría No. '.$auditoria->numero_auditoria.
                                ', por lo que se requiere comience con el apartado de análisis oportuno en el módulo Seguimiento.';
                // Obtener equipo_id del analista activo en esa auditoría
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), null, null,GenerarLlave($accion) . '/Rechazo', NULL,$auditoria->id, $registroAnalista->equipo_id ?? null,'Analista');
            }else{
                $titulo = 'Revisión del registro de la auditoria No. '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado(a) '.$jefe->name.', '.$jefe->puesto.':</strong><br>'
                                .auth()->user()->name.', '.auth()->user()->puesto.
                                '; se ha aprobado el registro de la auditoría No. '.$auditoria->numero_auditoria.
                                ', por lo que se requiere realice la revisión oportuna en el módulo Seguimiento.';
                //auth()->user()->insertNotificacion($titulo, $mensaje, now(), $auditoria->usuarioCreacion->unidad_administrativa_id, $auditoria->usuarioCreacion->id);
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), $jefe->unidad_administrativa_id, $jefe->id);
            }
        } else {

            $auditoria->update(['registro_concluido'=>'No']);
            $titulo = 'Rechazo del registro de la auditoria No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado(a) '.$auditoria->usuarioCreacion->name.', '.$auditoria->usuarioCreacion->puesto.':</strong><br>'
                            .'Ha sido rechazado el registro de auditoría No. '.$auditoria->numero_auditoria.
                            ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
            
            if (usaEquipoTrabajo()){
                // Obtener equipo_id del analista activo en esa auditoría
                $registroAnalista = AuditoriaUsuarios::where('auditoria_id', $auditoria->id)->where('rol_code', 'Analista')->where('estatus', 'Activo')->first();
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), null, null,GenerarLlave($accion) . '/Rechazo', NULL,$auditoria->id, $registroAnalista->equipo_id ?? null,'Analista');
            }else{
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), $auditoria->usuarioCreacion->unidad_administrativa_id, $auditoria->usuarioCreacion->id);
                //auth()->user()->insertNotificacion($titulo, $mensaje, now(),$analista->unidad_administrativa_id,$analista->id,GenerarLlave($accion) . '/Rechazo',$url);
            }
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
