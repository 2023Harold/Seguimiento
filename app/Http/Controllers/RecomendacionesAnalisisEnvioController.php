<?php

namespace App\Http\Controllers;

use App\Models\Movimientos;
use App\Models\Recomendaciones;
use Illuminate\Http\Request;

class RecomendacionesAnalisisEnvioController extends Controller
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
    public function edit(Recomendaciones $recomendacion)
    {
        $recomendacion->update(['fase_revision'=>'Pendiente']);

        Movimientos::create([
            'tipo_movimiento' => 'Actualización del análisis',
            'accion' => 'Recomendación',
            'accion_id' => $recomendacion->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        $titulo = 'Revisión del análisis de la recomendación de la acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria;
        $mensaje = '<strong>Estimado (a) ' . $recomendacion->accion->lider->name . ', ' . $recomendacion->accion->lider->puesto . ':</strong><br>
                    Se ha actualizado el análisis de la recomendación de la acción No. '.$recomendacion->accion->numero.' de la Auditoría No. '.$recomendacion->accion->auditoria->numero_auditoria . ', por parte del ' . 
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';

        setMessage('Se ha enviado la notificación al líder de proyecto para la revisión del análisis.');

        auth()->user()->insertNotificacion($titulo, $mensaje, now(), $recomendacion->accion->lider->unidad_administrativa_id,$recomendacion->accion->lider->id);

        return redirect()->route('recomendacionesatencion.index');
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
