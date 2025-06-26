<?php

namespace App\Http\Controllers;

use App\Models\CatalogoTipologia;
use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use Illuminate\Http\Request;

class TipologiaAccionEnvioController extends Controller
{
      protected $model;

    public function __construct(AuditoriaAccion $model)
    {
             $this->model = $model;
    }
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
        $tipologia=$accion;
        Movimientos::create([
            'tipo_movimiento' => 'Registro de la Tipologia',
                'accion' => 'CatalogoTipologia',
                'accion_id' => $accion->tipologia_id,
                'estatus' => 'Aprobado',
                'usuario_creacion_id' => auth()->id(),
                'usuario_asignado_id' => auth()->id(),
            ]);
    
            $tipologia->update(['fase_autorizacion' =>  'En revisión']);
    
            $titulo = 'Revisión de la tipología';
            $mensaje = '<strong>Estimado (a) ' . $tipologia->auditoria->lider->name . ', ' . $tipologia->auditoria->lider->puesto . ':</strong><br>
                        Ha sido registrada la tipologia de la accion No.'. $accion->numero .', de la auditoría No. ' . $tipologia->auditoria->numero_auditoria . ', por parte del ' .
                        auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la revisión.';
    
            //auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->lider->unidad_administrativa_id,auth()->user()->lider->id);
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $tipologia->auditoria->lider->unidad_administrativa_id,$tipologia->auditoria->lider->id);
            setMessage('Se ha enviado el turno a la UI, a revisión');
    
        return redirect()->route('agregaracciones.index');
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
