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
      $request['usuario_creacion_id']= auth()->user()->id;
      $request['usuario_modificacion_id']= auth()->user()->id;
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
    public function edit(TurnoAcuseArchivo $auditoria)
    {
        $turnoarchivo=$auditoria;
        $auditoria=$auditoria->auditoria;


        return view('turnoarchivo.form', compact('turnoarchivo', 'auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,TurnoAcuseArchivo $auditoria)
    {
        $turnoarchivo=$auditoria;
        mover_archivos($request, ['turno_archivo'],$turnoarchivo);
        $turnoarchivo->update($request->all());
        $auditoria=$turnoarchivo->auditoria;
        setMessage("Los datos se han actualizado correctamente.");
  
        return redirect() -> route('turnoarchivo.index');
  
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
    public function auditoria(Auditoria $auditoria)
    {
        setSession('turno_archivo_auditoria_id',$auditoria->id);

        return redirect()->route('turno_archivo.create');
    }
    
    private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }

    // public function export(){
    //     $auditoria=Auditoria::find(getSession('auditoria_id')); 
    //     $template=new TemplateProcessorMod('bases-word/TurnoOIC.docx');       
    //     $nombreword='Of. R_OICs';
    //     $template->saveAs($nombreword.'.docx');

    //     return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
    // }
   

}
