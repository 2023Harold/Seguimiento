<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
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
    public function setQuery(Request $request)
    {
         $query = $this->model;

         $query = $query->where('segauditoria_id',getSession('auditoria_id'));

         
        if ($request->filled('consecutivo')) {
            $query = $query->where('consecutivo',$request->consecutivo);
         }

        if ($request->filled('tipo')) {
            $query = $query->where('tipo',$request->tipo);
        }
        if ($request->filled('monto_aclarar')) {
            $query = $query->where('monto_aclarar',$request->monto_aclarar);
        }
        return $query;
    }
    public function export(){
        $auditoria=Auditoria::find(getSession('auditoria_id')); 
        $template=new TemplateProcessorMod('bases-word/TurnoUI.docx');       
        $nombreword='OfUI';
        $template->saveAs($nombreword.'.docx');

        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
    }
}
