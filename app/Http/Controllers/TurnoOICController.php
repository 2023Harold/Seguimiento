<?php

namespace App\Http\Controllers;
use App\Models\Auditoria;
use App\Models\TurnoOIC;

use Illuminate\Http\Request;

class TurnoOICController extends Controller
{
    protected $model;
    public function __construct(TurnoOIC $model)
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
        $turnooic=TurnoOIC::where('auditoria_id',getSession('auditoria_id'))->first();   
        

        return view ('turnooic.index', compact('request','auditoria','turnooic'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));               
        $turnooic = new TurnoOIC();
       
        return view('turnooic.form', compact('auditoria','turnooic'));
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
     
      mover_archivos($request, ['turno_oic']);
      $request['auditoria_id']= getSession('auditoria_id');
      $turnooic  = TurnoOIC::create($request->all());

      setMessage("Los datos se han guardado correctamente.");

        return redirect() -> route('turnooic.index');
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
