<?php

namespace App\Http\Controllers\TurnoUI;

use App\Http\Controllers\Controller;
use App\Http\Requests\AprobarFlujoAutorizacionRequest;
use App\Models\Auditoria;
use App\Models\Movimientos;
use App\Models\TurnoUI;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class TurnoUIController extends Controller
{
    
    protected $model;
    public function __construct(TurnoUI $model)
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
        $turnoui=TurnoUI::where('auditoria_id',getSession('auditoria_id'))->first();
       
        return view ('turnoui.index', compact('request', 'auditoria','turnoui'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));               
        $turnoui = new TurnoUI();
       
        return view('turnoui.form', compact('auditoria','turnoui'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        mover_archivos($request, ['turno_ui']);
        $request['auditoria_id']= getSession('auditoria_id');
        $request['usuario_creacion_id'] = auth()->user()->id;
        $turnoui  = TurnoUI::create($request->all());

        setMessage("Los datos se han guardado correctamente.");

        return redirect() -> route('turnoui.index');
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
    public function edit(TurnoUI $auditoria)
    {
		
        $turnoui=$auditoria;
        $auditoria=$turnoui->auditoria;
		//dd($auditoria);
		
       
        return view('turnoui.form', compact('auditoria','turnoui'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,TurnoUI $auditoria)
    {
        $turnoui=$auditoria;
        mover_archivos($request, ['turno_ui'],$auditoria);
        $request['usuario_modificacion_id'] = auth()->user()->id;
        //dd($request,$auditoria);
        $auditoria->update($request->all());
        $auditoria=$auditoria->auditoria;
        setMessage("Los datos se han actualizado correctamente.");
  
        return redirect() -> route('turnoui.index',compact('auditoria','turnoui'));
  
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
        setSession('turnoui_auditoria_id',$auditoria->id);

        return redirect()->route('turnoui.create');
    }
    public function export(){
        $auditoria=Auditoria::find(getSession('auditoria_id')); 
        $template=new TemplateProcessorMod('bases-word/TurnoUI.docx');       
        $nombreword='TurnoUI';
        $template->saveAs($nombreword.'.docx');

        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
    }    
   
}
