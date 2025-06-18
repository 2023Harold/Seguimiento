<?php

namespace App\Http\Controllers\Recomendaciones;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Recomendaciones;
use Illuminate\Http\Request;

class RecomendacionesAcusesController extends Controller
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
    public function show(Recomendaciones $recomendacion)
    {
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        $accion=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));

        return view('recomendacionesatencionacuses.show', compact('auditoria', 'accion','recomendacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Recomendaciones $recomendacion)
    {
        $auditoria = Auditoria::find(getSession('recomendacionesauditoria_id'));
        $accion=AuditoriaAccion::find(getSession('recomendacionesauditoriaaccion_id'));


        return view('recomendacionesatencionacuses.form', compact('auditoria', 'accion','recomendacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recomendaciones $recomendacion)
    {
        $request['usuario_modificacion_id'] = auth()->id();
        //$ruta = env('APP_RUTA_MINIO').'Auditorias/' . strtoupper(Str::slug($comparecencia->auditoria->numero_auditoria)).'/Documentos';
        //mover_archivos_minio($request, ['oficio_recepcion', 'oficio_acuse'], null, $ruta);
        mover_archivos($request, ['oficio_comprobante', 'oficio_acuse']);
        $recomendacion->update($request->all());
        setMessage('Los acuses se han guardado correctamente');

        return redirect()->route('recomendacionesatencion.index');
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
