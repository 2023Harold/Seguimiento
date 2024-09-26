<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\TurnoAcuseArchivo;
use Illuminate\Http\Request;

class TurnoArchivoController extends Controller
{
    protected $model;

    public function __construct(TurnoAcuseArchivo $model)

    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auditoria = Auditoria :: find(getSession('auditoria_id'));
        $turnoarchivo=TurnoAcuseArchivo::where('auditoria_id',getSession('auditoria_id'))->first();
        

        return view ('turnoarchivo.index', compact('request','auditoria','turnoarchivo'));

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));               
        $turnoarchivo = new TurnoAcuseArchivo();
       
        return view('turnoarchivo.form', compact('auditoria','turnoarchivo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      mover_archivos($request, ['turno_archivo']);
      $request['auditoria_id']= getSession('auditoria_id');
      $turnoarchivo  = TurnoAcuseArchivo::create($request->all());

      setMessage("Los datos se han guardado correctamente.");

      return redirect() -> route('turnoarchivo.index');



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
