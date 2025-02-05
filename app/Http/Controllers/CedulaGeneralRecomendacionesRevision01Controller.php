<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Cedula;
use App\Models\Movimientos;
use Illuminate\Http\Request;

class CedulaGeneralRecomendacionesRevision01Controller extends Controller
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
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $nombre=$cedula->cedula;
        
        return view('cedgralrecomendacionrevision01.form',compact('nombre','auditoria','cedula'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cedula $cedula)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        $accioneslideres=AuditoriaAccion::whereNull('aprobar_cedrec_lider')->where('segauditoria_id',$auditoria->id)->get();   
        $lideres=array_unique($accioneslideres->pluck('lider_asignado_id', 'id')->toArray()); 

        if(count($lideres)>1){           
            if($request->estatus=='Aprobado'){
                AuditoriaAccion::where('lider_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedrec_lider'=>'Si']);
            }else{               
                AuditoriaAccion::where('segauditoria_id',$auditoria->id)->update(['aprobar_cedrec_lider'=>null,'aprobar_cedrec_analista'=>null]);
                $cedula->update(['fase_autorizacion' => 'Rechazado']);
            }
            $this->normalizarDatos($request);

            Movimientos::create([
                'tipo_movimiento' => 'Revisión de la Cédula General de Recomendación',
                'accion' => 'Cédula General Recomendación',
                'accion_id' => $auditoria->id,
                'estatus' => $request->estatus,
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
                'motivo_rechazo' => $request->motivo_rechazo,
            ]);

            setMessage($request->estatus == 'Aprobado' ?
                'La aprobación ha sido registrada.' :
                'El rechazo ha sido registrado.'
            );
            //Mensajes de rechazo
        }else{
            $jefe=auth()->user()->jefe;
            $this->normalizarDatos($request);

            Movimientos::create([
                'tipo_movimiento' => 'Revisión de la Cédula General de Recomendación',
                'accion' => 'Cédula General Recomendación',
                'accion_id' => $auditoria->id,
                'estatus' => $request->estatus,
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
                'motivo_rechazo' => $request->motivo_rechazo,
            ]);       

            if (strlen($cedula->nivel_autorizacion) == 3 || strlen($cedula->nivel_autorizacion) == 4) {
                $nivel_autorizacion = $cedula->nivel_autorizacion;
            } else {
                $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 5);
            }

            $cedula->update(['fase_autorizacion' => $request->estatus == 'Aprobado' ? 'En revisión' : 'Rechazado']);
            setMessage($request->estatus == 'Aprobado' ?
                'La aprobación ha sido registrada y se ha enviado a revisión del superior.' :
                'El rechazo ha sido registrado.'
            );

            if ($request->estatus == 'Aprobado') {
                AuditoriaAccion::where('lider_asignado_id',auth()->user()->id)->where('segauditoria_id',$auditoria->id)->update(['aprobar_cedrec_lider'=>'Si']);
                $cedula->update([ 'nivel_autorizacion' => $nivel_autorizacion]);

                $titulo = 'Revisión de la Cédula General General de Recomendación de la Auditoría No. '.$auditoria->numero_auditoria;

                $mensaje = '<strong>Estimado(a) '.$jefe->name.', '.$jefe->puesto.':</strong><br>'
                                .auth()->user()->name.', '.auth()->user()->puesto.
                                '; se ha aprobado el registro de la Cédula General General de Recomendación de la Auditoría No. '.$auditoria->numero_auditoria.
                                ', por lo que se requiere realice la revisión oportuna en el módulo Seguimiento.';
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), $jefe->unidad_administrativa_id, $jefe->id);
            } else {       
                AuditoriaAccion::where('segauditoria_id',$auditoria->id)->update(['aprobar_cedrec_lider'=>null,'aprobar_cedrec_analista'=>null]);   
               
                $titulo = 'Rechazo de la Cédula General General de Recomendación de la Auditoría No. '.$auditoria->numero_auditoria;
                $mensaje = '<strong>Estimado(a) '.$cedula->userCreacion->name.', '.$cedula->userCreacion->puesto.':</strong><br>'
                                .'Ha sido rechazado el registro de la Cédula General General de Recomendación de la Auditoría No. '.$auditoria->numero_auditoria.
                                ', por lo que se debe atender los comentarios y enviar la información corregida nuevamente a revisión.';
                auth()->user()->insertNotificacion($titulo, $mensaje, now(), $cedula->userCreacion->unidad_administrativa_id, $cedula->userCreacion->id);
            }
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
}
