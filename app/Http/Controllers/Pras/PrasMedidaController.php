<?php

namespace App\Http\Controllers\Pras;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Segpras;
use Illuminate\Http\Request;

class PrasMedidaController extends Controller
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
    public function show(Segpras $pras)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('prasauditoriaaccion_id'));

        return view('prasmedidas.show', compact('auditoria', 'accion','pras'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Segpras $pras)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $accion = AuditoriaAccion::find(getSession('prasauditoriaaccion_id'));

        return view('prasmedidas.form', compact('auditoria', 'accion','pras'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Segpras $pras)
    {
        $request['usuario_modificacion_id'] = auth()->id();
        //$ruta = env('APP_RUTA_MINIO').'Auditorias/' . strtoupper(Str::slug($comparecencia->auditoria->numero_auditoria)).'/Documentos';
        //mover_archivos_minio($request, ['oficio_recepcion', 'oficio_acuse'], null, $ruta);
        mover_archivos($request, ['oficio_medida_apremio']);
        $pras->update($request->all());
        setMessage('Se han guardado los cambios correctamente.');

        return redirect()->route('prasturno.index');
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
