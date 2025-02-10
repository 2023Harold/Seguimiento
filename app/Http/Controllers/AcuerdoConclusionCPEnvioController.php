<?php

namespace App\Http\Controllers;

use App\Models\AcuerdoConclusion;
use App\Models\Auditoria;
use App\Models\Movimientos;
use Illuminate\Http\Request;

class AcuerdoConclusionCPEnvioController extends Controller
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
    public function edit(AcuerdoConclusion $auditoria)
    {
        $acuerdoconclusion=$auditoria;
        Movimientos::create([
            'tipo_movimiento' => 'Registro del acuerdo de conclusión',
                'accion' => 'AcuerdoConclusion',
                'accion_id' => $acuerdoconclusion->id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_modificacion_id' => auth()->id()
            ]);
    
            $acuerdoconclusion->update(['fase_autorizacion' =>  'En revisión']);
            // dd($acuerdoconclusion->auditoria);

            $titulo = 'Revisión de los datos del acuerdo de conclusión';
            $mensaje = '<strong>Estimado (a) ' . auth()->user()->jefe->name . ', ' . auth()->user()->jefe->puesto . ':</strong><br>
                        Ha sido registrada el acuerdo de conclusión de la auditoría No. ' . $acuerdoconclusion->auditoria->numero_auditoria . ', por parte del ' .
                        auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->jefe->unidad_administrativa_id,auth()->user()->jefe->id);
            setMessage('Se ha enviado el acuerdo de conclusión a revisión');
    
        return redirect()->route('acuerdoconclusion.index');
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
