<?php

namespace App\Http\Controllers;

use App\Models\AcuerdoConclusion;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use Illuminate\Http\Request;
use App\Models\ListadoEntidades;
use Luecano\NumeroALetras\NumeroALetras;
use PhpOffice\PhpWord\TemplateProcessor;

class AcuerdoConclusionCPController extends Controller
{
    protected $model;
    public function __construct(AcuerdoConclusion $model)

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
        $acuerdoconclusion=AcuerdoConclusion::where('auditoria_id',getSession('auditoria_id'))->first();
        //$acciones = $this -> setQuery($request)-> orderBy('id')->paginate(30);

        return view ('acuerdoconclusion.index', compact('request', 'auditoria'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $tipo='recomendaciones';
        $acuerdoconclusion = new AcuerdoConclusion();
		$fechaacuerdo=now();
            
		if($auditoria->acto_fiscalizacion=='Desempeño'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_proceso,1);
		}
		if($auditoria->acto_fiscalizacion=='Legalidad'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_aclaracion,1);
		}
		if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_aclaracion,1);
		}
		if($auditoria->acto_fiscalizacion=='Inversión Física'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_aclaracion,1);
		}

        return view('acuerdoconclusioncp.form', compact('auditoria','acuerdoconclusion','fechaacuerdo','tipo'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        mover_archivos($request, ['acuerdo_conclusion']);
        $request['usuario_creacion_id']=auth()->user()->id;
        $request['auditoria_id']= getSession('auditoria_id');
        $acuerdoconclusion  = AcuerdoConclusion::create($request->all());

        setMessage("Los datos se han guardado correctamente.");

        return redirect() -> route('acuerdoconclusion.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AcuerdoConclusion $acuerdoconclusion)
    {
        $auditoria=$acuerdoconclusion->auditoria;

        return view('acuerdoconclusion.show', compact('acuerdoconclusion', 'auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AcuerdoConclusion $auditoria)
    {
        $acuerdoconclusion=$auditoria;
        $tipo=$acuerdoconclusion->tipo;                 
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $comparecencia=$auditoria->comparecencia;
        $fechaacuerdo=now();

		if($auditoria->acto_fiscalizacion=='Desempeño'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_proceso,1);
		}
		if($auditoria->acto_fiscalizacion=='Legalidad'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_aclaracion,1);
		}
		if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_aclaracion,1);
		}
		if($auditoria->acto_fiscalizacion=='Inversión Física'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_aclaracion,1);
		}

        $request['usuario_creacion_id'] = auth()->user()->id;
        return view('acuerdoconclusioncp.form', compact('auditoria','acuerdoconclusion','fechaacuerdo','tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AcuerdoConclusion $auditoria)
    {
        $acuerdoconclusion=$auditoria;
        mover_archivos($request, ['acuerdo_conclusion'],$acuerdoconclusion);
        $request['usuario_modificacion_id']=auth()->user()->id;
        $acuerdoconclusion->update($request->all());

        setMessage("Los datos se han actualizado correctamente.");

        return redirect() -> route('acuerdoconclusion.index');
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

    public function acuerdoconclusionpliegos()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));     
        $tipo='pliegos';          
        $request['usuario_creacion_id'] = auth()->user()->id;
        $acuerdoconclusion = new AcuerdoConclusion();
		$fechaacuerdo=now();
        

		if($auditoria->acto_fiscalizacion=='Desempeño'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_proceso,1);
		}
		if($auditoria->acto_fiscalizacion=='Legalidad'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_aclaracion,1);
		}
		if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_aclaracion,1);
		}
		if($auditoria->acto_fiscalizacion=='Inversión Física'){
			$fechaacuerdo=fechadias($auditoria->comparecencia->fecha_termino_aclaracion,1);
		}

        return view('acuerdoconclusioncp.form', compact('auditoria','acuerdoconclusion','tipo'));

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
        setSession('acuerdo_auditoria_id',$auditoria->id);

        return redirect()->route('acuerdoconclusion.create');
    }

    public function setQuery(Request $request)
    {
        $query = new Auditoria;
        $query = $query->whereNotNull('fase_autorizacion')
           ->where('fase_autorizacion','Autorizado');

       if(in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
          in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())||
          in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())){



       }elseif(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())){

           $query = $query->whereNotNull('fase_autorizacion')
                       ->where('fase_autorizacion','Autorizado')
                       ->whereNotNull('direccion_asignada_id')
                       ->where('direccion_asignada_id',auth()->user()->unidad_administrativa_id);
       }elseif(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){
           $query = $query->whereNotNull('departamento_encargado_id')
                       ->where('departamento_encargado_id',auth()->user()->unidad_administrativa_id);
       }

       if ($request->filled('numero_auditoria')) {
            $numeroAuditoria=strtolower($request->numero_auditoria);
            $query = $query->whereRaw('LOWER(numero_auditoria) LIKE (?) ',["%{$numeroAuditoria}%"]);
        }

       if ($request->filled('entidad_fiscalizable')) {
           $entidadFiscalizable=strtolower($request->entidad_fiscalizable);
           $query = $query->whereRaw('LOWER(entidad_fiscalizable) LIKE (?) ',["%{$entidadFiscalizable}%"]);
       }

       if ($request->filled('acto_fiscalizacion')) {
           $actoFiscalizacion=strtolower($request->acto_fiscalizacion);
           $query = $query->whereRaw('LOWER(acto_fiscalizacion) LIKE (?) ',["%{$actoFiscalizacion}%"]);
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
        // $template->setValue();
        // $templete->setValue();

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
