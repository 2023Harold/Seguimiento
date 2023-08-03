<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use Illuminate\Http\Request;

class AsignacionDepartamentoEncargadoController extends Controller
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
    public function edit(Auditoria $auditoria)
    {
              
        $unidades = auth()->user()->unidadAdministrativa->departamentos->prepend('Seleccionar una opción', '');         
        $acciondep ='Asignación del Departamento Encargado de la Auditoría';   
             
               
        return view('asignaciondepartamentoencargado.form', compact('auditoria','unidades','acciondep'));  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Auditoria $auditoria)
    {
        
            $auditoria->update($request->all());
            
            $titulo = 'Asignación de auditoría';
            $mensaje = '<strong>Estimado(a) ' . $request->nombre . ', ' . $request->cargo . '.</strong><br>Se le ha asignado la auditoría No.  ' . $auditoria->numero_auditoria . ', por parte del Titular, por lo que se requiere realice la radicación y comparecencia.';
            auth()->user()->insertNotificacion($titulo, $mensaje, now(), $request->departamento_encargado_id, $request->usuario_id);
            
            setMessage('Se ha realizado la asignación del encargado de la auditoría correctamente.');           

        return redirect()->route('asignaciondepartamento.index',$auditoria);  
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
