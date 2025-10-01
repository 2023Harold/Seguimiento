<?php

namespace App\Http\Controllers\TurnoOIC;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\TurnoOIC;
use App\Models\User;
use Illuminate\Http\Request;

class TurnoOICEnvioController extends Controller
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
    public function edit(TurnoOIC $auditoria)
    {
        $turnooic=$auditoria;
        $auditoria = Auditoria::find($turnooic->auditoria_id);
        if(getSession('cp')==2022){
            $jefe=User::where('unidad_administrativa_id', substr($auditoria->userCreacion->unidad_administrativa_id, 0, 5).'0')->first();
            $lider=$auditoria->accion->lider;
        }else{
            $jefe = $auditoria->jefedepartamentoencargado;
            $lider = $auditoria->lidercp; 
        }
        Movimientos::create([
            'tipo_movimiento' => 'Registro del Turno al Órgano Interno de Control',
                'accion' => 'TurnoOIC',
                'accion_id' => $turnooic->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
            ]);
    
            $turnooic->update(['fase_autorizacion' =>  'En revisión']);
            $NotificacionRechazo=auth()->user()->notificaciones()->where('llave',GenerarLlave( $turnooic).'/Rechazo')->first();
            $LeerNotificacionRechazo = auth()->user()->NotMarcarLeido($NotificacionRechazo);
            $url = route('turnooic.index');

            $titulo = 'Revisión de los datos del Turno al OIC de la auditoría No. '.$auditoria->numero_auditoria;
            $mensaje = '<strong>Estimado (a) ' . $jefe->name . ', ' . $jefe->puesto . ':</strong><br>
                        Se realizó el registro el turno a la OIC de la auditoría No. ' . $auditoria->numero_auditoria . ', por parte del ' .
                        auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $jefe->unidad_administrativa_id,$jefe->id,GenerarLlave($turnooic).'/RevJD',$url);

            setMessage('Se ha enviado el turno a la OIC, a revisión');
    
        return redirect()->route('turnooic.index');

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
