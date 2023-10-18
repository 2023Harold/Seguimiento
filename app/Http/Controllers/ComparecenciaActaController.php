<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Comparecencia;
use Illuminate\Http\Request;

class ComparecenciaActaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $comparecencia = $auditoria->comparecencia;      
               
        return view('comparecenciaacta.show', compact('auditoria','comparecencia'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipo_identificacion= [null=>'','INE'=>'INE','Pasaporte'=>'Pasaporte','Cedula profesional'=>'Cédula profesional','Cartilla militar'=>'Cartilla militar','Gafete institucional'=>'Gafete institucional'];
        return view('comparecencia.form', compact('tipo_identificacion'));
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
    public function show(Comparecencia $comparecencia)
    {
        $auditoria=$comparecencia->auditoria;

        return view('comparecenciaacta.show', compact('comparecencia', 'auditoria'));
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
        $tipo_identificacion= [null=>'','INE'=>'INE','Pasaporte'=>'Pasaporte','Cedula profesional'=>'Cédula profesional','Cartilla militar'=>'Cartilla militar','Gafete institucional'=>'Gafete institucional'];
        $tipo_identificacion1= [null=>'','INE'=>'INE','Pasaporte'=>'Pasaporte','Cedula profesional'=>'Cédula profesional','Cartilla militar'=>'Cartilla militar','Gafete institucional'=>'Gafete institucional'];
        $tipo_identificacion2= [null=>'','INE'=>'INE','Pasaporte'=>'Pasaporte','Cedula profesional'=>'Cédula profesional','Cartilla militar'=>'Cartilla militar','Gafete institucional'=>'Gafete institucional'];


        return view('comparecenciaacta.form', compact('comparecencia', 'auditoria','tipo_identificacion','tipo_identificacion1','tipo_identificacion2'));
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
        //mover_archivos_minio($request, ['cedula_general'], null, $ruta);
        mover_archivos($request, ['oficio_acta','oficio_designacion']);
        $comparecencia->update($request->all());
        setMessage('El acta se han guardado correctamente');

        return redirect()->route('comparecencia.index');
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
