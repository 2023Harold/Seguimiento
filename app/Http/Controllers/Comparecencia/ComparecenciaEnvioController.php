<?php

namespace App\Http\Controllers\Comparecencia;

use App\Http\Controllers\Controller;
use App\Models\Comparecencia;
use App\Models\Movimientos;
use Illuminate\Http\Request;

class ComparecenciaEnvioController extends Controller
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
    public function edit(Comparecencia $comparecencia)
    {
        // $comparecencia->update($request->all());
  
        Movimientos::create([
          'tipo_movimiento' => 'Registro de la comparecencia',
              'accion' => 'Comparecencia',
              'accion_id' => $comparecencia->id,
              'estatus' => 'Aprobado',
              'usuario_creacion_id' => auth()->id(),
              'usuario_asignado_id' => auth()->id(),
          ]);
  
          $comparecencia->update(['fase_autorizacion' =>  'En validación']);
  
          $titulo = 'Validación de los datos de comparecencia';
          $mensaje = '<strong>Estimado (a) ' . auth()->user()->director->name . ', ' . auth()->user()->director->puesto . ':</strong><br>
                      Ha sido registrada la comparecencia de la auditoría No. ' . $comparecencia->auditoria->numero_auditoria . ', por parte del ' .
                      auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la validación.';
  
          auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id,auth()->user()->director->id);
          setMessage('Se ha enviado la comparecencia a validación');
  
      return redirect()->route('comparecenciaacta.index');
      
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
