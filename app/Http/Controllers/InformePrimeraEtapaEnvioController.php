<?php

namespace App\Http\Controllers;

use App\Models\InformePrimeraEtapa;
use App\Models\Movimientos;
use Illuminate\Http\Request;

class InformePrimeraEtapaEnvioController extends Controller
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
    public function edit(InformePrimeraEtapa $auditoria)
    {
        $informeprimeraetapa=$auditoria;
        Movimientos::create([
            'tipo_movimiento' => 'Registro del Informe Primera Etapa',
                'accion' => 'InformePrimeraEtapa',
                'accion_id' => $informeprimeraetapa->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
            ]);
    
            $informeprimeraetapa->update(['fase_autorizacion' =>  'En validación']);
    
            $titulo = 'Validación de los datos del Informe Primera Etapa';
            $mensaje = '<strong>Estimado (a) ' . auth()->user()->director->name . ', ' . auth()->user()->director->puesto . ':</strong><br>
                        Ha sido registrado el Informe Primera Etapa  de la auditoría No. ' . $informeprimeraetapa->auditoria->numero_auditoria . ', por parte del ' .
                        auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la validación.';
    
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id,auth()->user()->director->id);
            setMessage('Se ha enviado el Informe Primera Etapa, a validación');
    
        return redirect()->route('informeprimeraetapa.index');
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
