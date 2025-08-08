<?php

namespace App\Http\Controllers\Cedulas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cedula;
use App\Models\Auditoria;
use App\Models\Revisiones;
use Illuminate\Http\Request;
use App\Models\AuditoriaAccion;

class AgregarCedulaInicialController extends Controller
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
    public function create(Request $request)
    {
       
        // $auditoria= Auditoria::find(getSession('auditoria_id'));
       
        $tipo = $request->query('tipo');
        $cedula = new Cedula();

        return view('agregarcedula.form', compact('cedula', 'tipo','request'));   
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        if ($request->tipo=='Cédula Analítica Desempeño') {
            $tipo='Analítica Desempeño';
        }elseif($request->tipo=='Cédula General Recomendación'){
            $tipo='General Recomendación';
        }elseif($request->tipo=='Cédula General PRAS'){
            $tipo='General PRAS';
        }elseif($request->tipo=='Cédula Analítica'){
            $tipo='Analítica';
        }elseif($request->tipo=='Cédula General Seguimiento'){
            $tipo='General Seguimiento';
        }        
        mover_archivos($request, ['cedula_cargada']);
        $request['auditoria_id']=getSession('auditoria_id'); 
        $request['usuario_creacion_id'] = auth()->user()->id;
        $request['usuario_modificacion_id'] = auth()->user()->id;
        $request['cedula_tipo'] = $tipo;
        $cedula = Cedula::create($request->all());           
        // dd($cedula);
        setMessage('Se ha cargado correctamente la cédula.');

        return view('layouts.close');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Cedula $cedula)
    {        
        $auditoria = $cedula->auditoria; 

        return view('agregarcedula.show', compact( 'auditoria','cedula'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Cedula $cedula)
    {   
        
        // dd($cedula);         
        $auditoria=$cedula->auditoria;
        $tipo = $request->query('tipo');
        return view('agregarcedula.form', compact('cedula','auditoria','tipo', 'request'));   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cedula $cedula)
    {
        mover_archivos($request, ['cedula_cargada'],$cedula);
        $request['usuario_modificacion_id'] = auth()->id();
        $cedula->update($request->all());
        setMessage('La cédula se ha actualizado correctamente');
        // dd($request);
         return view('layouts.close');
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

    private function mensajeComentario(String $nombre, String $puesto)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Se registro un comentario por parte del '.auth()->user()->puesto.'; '.auth()->user()->name.', por lo que se debe atender.';    

        return $mensaje;
    }
}
