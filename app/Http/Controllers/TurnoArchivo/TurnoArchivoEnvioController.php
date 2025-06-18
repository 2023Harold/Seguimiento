<?php

namespace App\Http\Controllers\TurnoArchivo;

use App\Http\Controllers\Controller;
use App\Models\Movimientos;
use App\Models\TurnoAcuseArchivo;
use Illuminate\Http\Request;

class TurnoArchivoEnvioController extends Controller
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
    public function edit(TurnoAcuseArchivo $auditoria)
    {
       $turnoarchivo=$auditoria;
        Movimientos::create([
            'tipo_movimiento' => 'Registro del Turno Acuse Envío Archivo',
                'accion' => 'TurnoArchivo',
                'accion_id' => $turnoarchivo->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
            ]);

            $turnoarchivo->update(['fase_autorizacion' =>  'En revisión01']);

            $titulo = 'Revisión de los datos de turno archivo';
            $mensaje = '<strong>Estimado (a) ' . auth()->user()->lider->name . ', ' . auth()->user()->lider->puesto . ':</strong><br>
                        Ha sido registrada la turno archivo de la auditoría No. ' . $turnoarchivo->auditoria->numero_auditoria . ', por parte del ' .
                        auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->lider->unidad_administrativa_id,auth()->user()->lider->id);
            setMessage('Se ha enviado el turno archivo a Revisión');

        return redirect()->route('turnoarchivo.index');
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
