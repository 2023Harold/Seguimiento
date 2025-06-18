<?php

namespace App\Http\Controllers\TurnoArchivo;

use App\Http\Controllers\Controller;
use App\Models\Movimientos;
use App\Models\Auditoria;
use App\Models\TurnoArchivoTransferencia;
use Illuminate\Http\Request;

class TurnoArchivoTransferenciaEnvioController extends Controller
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
    public function edit(TurnoArchivoTransferencia $auditoria)
    {
        // setSession ('auditoriatat_id', $auditoria->id);
        $turnoarchivotransferencia=$auditoria;
        Movimientos::create([
            'tipo_movimiento' => 'Registro del turno archivo',
                'accion' => 'TurnoTransferencia',
                'accion_id' => $turnoarchivotransferencia->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
                'usuario_modificacion_id' => auth()->id(),
            ]);
    
            $turnoarchivotransferencia->update(['fase_autorizacion' =>  'En revisión']);
    
            $titulo = 'Revisión de los datos de turno archivo transferencia';
            $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                        Ha sido registrado el turno transferencia de la auditoría No. ' . $turnoarchivotransferencia->auditoria->numero_auditoria . ', por parte del ' .
                        auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);
            setMessage('Se ha enviado el turno archivo transferencia a Revisión');
    
        return redirect()->route('turnoarchivotransferencia.index');
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
