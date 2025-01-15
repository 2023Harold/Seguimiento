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
        $turnoarchivotransferencia=TurnoArchivoTransferencia::where('auditoria_id',getSession('auditoria_id'))->first(); 
        // //dd($turnotransferencia);  


        return view ('turnotransferencia.index', compact('request','auditoria', 'turnoarchivotransferencia'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));               
        $turnoarchivotransferencia = new TurnoArchivoTransferencia();
       
        return view('turnotransferencia.form', compact('auditoria','turnoarchivotransferencia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    //   dd(getSession('auditoria_id'));
    mover_archivos($request, ['TurnoTransferencia']);
    $request['auditoria_id']= getSession('auditoria_id');
    $turnoarchivotransferencia  = TurnoArchivoTransferencia::create($request->all());
    // dd($turnotransferencia);
      setMessage("Los datos del archivo de transferencia se han guardado correctamente.");

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
    public function edit(TurnoArchivoTransferencia $auditoria)
    {
        $turnoarchivotransferencia=$auditoria;
        $auditoria=$auditoria->auditoria;
        return view('turnotransferencia.form', compact('turnoarchivotransferencia', 'auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TurnoArchivoTransferencia $auditoria)
    {
        // dd(getSession('auditoria_id'));
      $request['auditoria_id']= getSession('auditoria_id');
      mover_archivos($request, ['ArchivoTransferencia']);
      $request['usuario_modificacion_id'] = auth()->user()->id;
      $turnoarchivotransferencia  = TurnoArchivoTransferencia::create($request->all());
    // dd($turnotransferencia);  
        setMessage('Los datos de archivo trasferencia se han guardado correctamente');

        return redirect()->route('turnoarchivo.index', $turnoarchivotransferencia);
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
    public function auditoria(Auditoria $auditoria)
    {
        setSession('turnotransferencia_auditoria_id',$auditoria->id);

        return redirect()->route('turnoarchivotransferencia.create');
    }

    }
