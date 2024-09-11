<?php

namespace App\Http\Controllers;

use App\Models\AcuerdoConclusion;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class AcuerdoConclusionController extends Controller
{
    protected $model;
    public function __construct(AuditoriaAccion $model)

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
        $acciones = $this -> setQuery($request)-> orderBy('id')->paginate(30);

        return view ('acuerdoconclusion.index', compact('request','acciones', 'auditoria'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));               
        $acuerdoconclusion = new AcuerdoConclusion();
       
        return view('acuerdoconclusion.form', compact('auditoria','acuerdoconclusion'));
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

        $template=new TemplateProcessor('bases-word/A_conclusion.docx');
        $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
        $template->setValue('segtipo_auditoria',$auditoria->tipo_auditoria->descripcion);
        $template->setValue('periodo_fiscalizado',$auditoria->periodo_revision);
        $template->setValue('numero_orden',$auditoria->numero_orden);
        $template->setValue('fecha_oficio_acuerdo',$auditoria->radicacion->fecha_oficio_acuerdo);
        $template->setValue();
        $templete->setValue();

        $nombreword='A_conclusion';/** */

        $template->saveAs($nombreword.'.docx');/** */

        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);/** */   
    }



    public function exportOFAC(){
        $template=new TemplateProcessor('bases-word/OF_AC.docx'); 
        $nombreword='OF_AC';/** */

        $template->saveAs($nombreword.'.docx');/** */

        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);/** */   
    }
}
