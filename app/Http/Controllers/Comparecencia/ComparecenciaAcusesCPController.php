<?php

namespace App\Http\Controllers\Comparecencia;

use App\Http\Controllers\Controller;
use App\Models\Comparecencia;
use Illuminate\Http\Request;

class ComparecenciaAcusesCPController extends Controller
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
        mover_archivos($request, ['oficio_acuerdo'], null);
        $request['usuario_creacion_id'] = auth()->user()->id;
        $request['auditoria_id']=getSession('radicacion_auditoria_id');     
        $comparecencia = Comparecencia::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comparecencia $comparecencia)
    {
        $auditoria = $comparecencia->auditoria; 

        return view('comparecenciaacusecp.show', compact('auditoria', 'comparecencia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Comparecencia $comparecencia)
    {
        $auditoria=$comparecencia->auditoria;

        return view('comparecenciaacusecp.form', compact('comparecencia', 'auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comparecencia $comparecencia)
    {
        $request['usuario_modificacion_id'] = auth()->id();
        //$ruta = env('APP_RUTA_MINIO').'Auditorias/' . strtoupper(Str::slug($comparecencia->auditoria->numero_auditoria)).'/Documentos';
        //mover_archivos_minio($request, ['oficio_recepcion', 'oficio_acuse'], null, $ruta);
        mover_archivos($request, ['oficio_recepcion','oficio_acuerdo','oficio_acuse']);
        $comparecencia->update($request->all());
        setMessage('Los acuses se han guardado correctamente');

        return redirect()->route('radicacion.index');
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
