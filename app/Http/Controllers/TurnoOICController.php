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
    public function edit(TurnoOIC $auditoria)
    {
        $turnooic = $auditoria;
        $auditoria = Auditoria::find(getSession('auditoria_id'));                       
       
        return view('turnooic.form', compact('auditoria','turnooic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TurnoOIC $auditoria)
    {
        $turnooic=$auditoria;
        mover_archivos($request,['turnooic',$turnooic]);
        $turnooic->update($request->all());
        setMessage("Los datos se han actualizado correctamente.");
        return redirect() -> route('turnooic.index',compact('auditoria','turnooic'));
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
        setSession('turnooic_auditoria_id',$auditoria->id);

        return redirect()->route('turnooic.create');
    }
    public function export(){
        $auditoria=Auditoria::find(getSession('auditoria_id')); 
        $template=new TemplateProcessorMod('bases-word/TurnoOIC.docx');       
        $nombreword='Of. R_OICs';
        $template->saveAs($nombreword.'.docx');

        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
    }
}
