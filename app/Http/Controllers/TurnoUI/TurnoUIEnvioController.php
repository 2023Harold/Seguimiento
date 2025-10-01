<?php

namespace App\Http\Controllers\TurnoUI;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\TurnoUI;
use App\Models\User;
use Illuminate\Http\Request;

class TurnoUIEnvioController extends Controller
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
    public function edit(TurnoUI $auditoria)
    {
        
        $turnoui=$auditoria;

        $auditoria = Auditoria::find($turnoui->auditoria_id);
        if(getSession('cp')==2022){
            $jefe=User::where('unidad_administrativa_id', substr($auditoria->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();
            $lider=$auditoria->accion->lider;
        }else{
            $jefe = $auditoria->jefedepartamentoencargado;
            $lider = $auditoria->lidercp; 
        }

        Movimientos::create([
            'tipo_movimiento' => 'Registro del turno a la Unidad de Investigación',
                'accion' => 'TurnoUI',
                'accion_id' => $turnoui->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
            ]);
    
            $turnoui->update(['fase_autorizacion' =>  'En revisión']);
            $NotificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave( $turnoui).'/Rechazo')->first();
            $LeerNotificacionRechazo = auth()->user()->NotMarcarLeido($NotificacionRechazo);

            $url = route('turnoui.index');
    
            $titulo = 'Revisión de los datos del turno a la UI';
            $mensaje = '<strong>Estimado (a) ' . $jefe->name . ', ' . $jefe->puesto . ':</strong><br>'.
                        'Ha sido registrada el turno a la UI de la auditoría No. ' . $auditoria->numero_auditoria . ', por parte del ' .
                        auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $jefe->unidad_administrativa_id,$jefe->id,GenerarLlave( $turnoui).'/RevJD',$url);

            setMessage('Se ha enviado el turno a la UI, a revisión');
    

        return redirect()->route('turnoui.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
