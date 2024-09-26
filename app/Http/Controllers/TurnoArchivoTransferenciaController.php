<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\TurnoArchivoTransferencia;
use Illuminate\Http\Request;

class TurnoArchivoTransferenciaController extends Controller
{
    protected $model;
    public function __construct(TurnoArchivoTransferencia $model)
       {
           $this -> model = $model;
       } 
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auditoria = Auditoria :: find(getSession('auditoria_id'));
        $turnotransferencia=TurnoArchivoTransferencia::where('auditoria_id',getSession('auditoria_id'))->first(); 
        //dd($turnotransferencia);  


        return view ('turnotransferencia.index', compact('request','auditoria', 'turnotransferencia'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));               
        $turnotransferencia = new TurnoArchivoTransferencia();
       
        return view('turnotransferencia.form', compact('auditoria','turnotransferencia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
       //dd(getSession('auditoria_id'));
      $request['auditoria_id']= getSession('auditoria_id');
      mover_archivos($request, ['turnotransferencia']);
     $turnotransferencia  = TurnoArchivoTransferencia::create($request->all());

      setMessage("Los datos se han guardado correctamente.");

      return redirect() -> route('turnotransferencia.index');
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
    public function edit($id)
    {
        //
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
         //dd(getSession('auditoria_id'));
      $request['auditoria_id']= getSession('auditoria_id');
      mover_archivos($request, ['turnotransferencia']);
     $turnotransferencia  = TurnoArchivoTransferencia::create($request->all());
      
        setMessage('Los datos se han guardado correctamente');

        return redirect()->route('turnotransferencia.edit', $turnotransferencia);
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
