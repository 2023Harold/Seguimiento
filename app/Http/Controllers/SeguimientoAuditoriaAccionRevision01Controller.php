<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaAccion;
use App\Models\Movimientos;
use Illuminate\Http\Request;

class SeguimientoAuditoriaAccionRevision01Controller extends Controller
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
    public function edit(AuditoriaAccion $accion)
    {        
        $auditoria = $accion->auditoria;


        return view('seguimientoauditoriaaccionrevision01.form', compact('auditoria','accion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AuditoriaAccion $accion)
    {        
        $this->normalizarDatos($request);

        Movimientos::create([
            'tipo_movimiento' => 'Revisión de la acción del registro de la auditoría',
            'accion' => 'Revisión Acción Registro Auditoría',
            'accion_id' => $accion->id,
            'estatus' => $request->estatus,
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        $accion->update(['revision_lider' => $request->estatus == 'Aprobado' ? 'Aprobado' : 'Rechazado']);

        setMessage('Se ha revisado correctamenta la accion');
        $auditoria=$accion->auditoria;

        return redirect()->route('seguimientoauditoriarevision01.edit',$auditoria);

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
