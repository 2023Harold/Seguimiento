<?php

namespace App\Http\Controllers;

use App\Models\AcuerdoConclusion;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Models\ListadoEntidades;
use App\Models\CatalogoUnidadesAdministrativas;

class AcuerdoConclusionController extends Controller
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
				
       
        return view('acuerdoconclusion.form', compact('auditoria','acuerdoconclusion','fechaacuerdo'));
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
        $auditoria = Auditoria::find(getSession('auditoria_id'));    
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
				
       
        return view('acuerdoconclusion.form', compact('auditoria','acuerdoconclusion','fechaacuerdo'));
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
    private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }

    // public function auditoria(Auditoria $auditoria)
    // {
    //     setSession('acuerdo_auditoria_id',$auditoria->id);

    //     return redirect()->route('acuerdoconclusion.create');
    // }

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

    public function export(Request $request){
        $auditoria=Auditoria::find(getSession('auditoria_id')); 

        $formatter = new NumeroALetras();
        $plazomax=$formatter->toString($auditoria->radicacion->plazo_maximo);
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[(now()->format('n')) - 1];

        $fechaactual=fechaaletra(now());
        $plazomaxMax = ucwords($plazomax);
        $plazomaxMin = ucwords(strtolower($plazomaxMax));
        $fecha_hora=fecha(optional($auditoria->comparecencia)->fecha_comparecencia) . ' ' . date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_inicio)) . (empty($auditoria->comparecencia->hora_comparecencia_termino)?"":"-".date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_termino)));
        $date01 = fecha(optional($auditoria->comparecencia)->fecha_comparecencia);

        $day02 = date('d', strtotime($auditoria->comparecencia->fecha_inicio_aclaracion));
        $mes02 = $meses[($auditoria->comparecencia->fecha_inicio_aclaracion->format('n')) - 1];
        $anio02 = date('Y', strtotime($auditoria->comparecencia->fecha_inicio_aclaracion));

        $day03 = date('d', strtotime($auditoria->comparecencia->fecha_termino_aclaracion));
        $mes03 = $meses[($auditoria->comparecencia->fecha_termino_aclaracion->format('n')) - 1];
        $anio03 = date('Y', strtotime($auditoria->comparecencia->fecha_termino_aclaracion));
        $fecha_acta = fecha(optional($auditoria->radicacion)->fecha_acta);
        $fecha_oficio_acuerdo = fecha(optional($auditoria->radicacion)->fecha_oficio_acuerdo);

        

        $iniciales='';
        $inicialesLM='MAOV';
        $inicialesA="";
        $inicialesD='';
        $inicialesJD='';

        //$nombre=auth()->user()->name;
        $nD = $auditoria->directorasignado->name;
        $nJD = $auditoria->jefedepartamentoencargado->name;
        $nA = $auditoria->analistacp->name;

         $nD=explode(' ',$nD);
         foreach($nD as $parte){
            $inicialesD=$inicialesD.substr($parte, 0,1);
         }

        $nJD=explode(' ',$nJD);
            foreach($nJD as $parte){
                $inicialesJD=$inicialesJD.substr($parte, 0,1);
        }
        $nA=explode(' ',$nA);
            foreach($nA as $parte){
                $inicialesA=$inicialesA.substr($parte, 0,1);
        }
        $iniciales= $inicialesLM."/".$inicialesD."/".$inicialesA."/".$inicialesJD;


         $numeroAuditoria = Auditoria::find(getSession('auditoria_id'));

        if ($numeroAuditoria) {
        $entidad = ListadoEntidades::where('no_auditoria', $numeroAuditoria->numero_auditoria)
            ->where('cuenta_publica', $numeroAuditoria->cuenta_publica)
            ->select('entidades', 'textos_doc')
            ->first();

            if ($entidad) {
                $nombreEntidad = $entidad->entidades;
                $textoDocumento = $entidad->textos_doc;

            }
        }

        if(empty($auditoria->acuerdoconclusion->domicilio)){
            $remitente_domicilio = "";
        }else{
            $remitente_domicilio = $auditoria->acuerdoconclusion->domicilio;
        }

        $tipo = $request->query('tipo'); // tipo para identificar el archivo solo aplica para legalidad
        $siRecomendaciones01 = "";
        $siRecomendaciones02 = "";
        $siRecomendaciones03 = "";
        $siRecomendaciones04 = "";
        $siRecomendaciones05 = "";
        $siPliegos01 ="";
        $siPliegos02 ="";
        $siPliegos03 ="";
        

        /**///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
        if($auditoria->acto_fiscalizacion=='Legalidad'){
            if ($tipo === 'AC_EA') {
                if(count($auditoria->accionesrecomendaciones)>0){
                    $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones correspondientes";
                    $siRecomendaciones02 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones, o en su caso, justificara su improcedencia dentro del plazo de ". $auditoria->radicacion->plazo_maximo. " (".$plazomaxMin.") días, plazo que fue convenido con el Órgano Superior de Fiscalización del Estado de México, detallado en el Acta de Reunión de Resultados Finales y Cierre de Auditoría ". $auditoria->radicacion->acta_cierre_auditoria.", 
                                            con el apercibimiento de que para el caso de no dar cumplimento a dicho requerimiento, se estaría a lo dispuesto en el artículo 59 fracción II de la Ley de Fiscalización Superior del Estado de México, ello, con independencia de las sanciones administrativas y penales que, en términos de las leyes en dichas materias, resultaren aplicables";            
                    $siRecomendaciones03 = "Por otro lado, con relación al Proceso de Atención a las Recomendaciones, una vez agotado el plazo convenido con el Órgano Superior de Fiscalización del Estado de México señalado en el primer párrafo del presente acuerdo, se informará por cuerda separada a esa entidad fiscalizada";
                    $siRecomendaciones04 = "y 54 Bis";
                }    
                
                $template=new TemplateProcessor('bases-word/AC/LEGALIDAD/AC_EA_01.docx');
                $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
                $template->setValue('segtipo_auditoria',$auditoria->tipo_auditoria->descripcion);
                $template->setValue('periodo',$auditoria->periodo_revision);
                $template->setValue('numero_orden',$auditoria->numero_orden);
                $template->setValue('fecha_oficio_acuerdo',$fecha_oficio_acuerdo);
                $template->setValue('oficio_numero', $auditoria->radicacion->oficio_acuerdo);
                $template->setValue('siRecomendaciones01',$siRecomendaciones01);
                $template->setValue('siRecomendaciones02',$siRecomendaciones02);
                $template->setValue('day03', $day03);
                $template->setValue('mes03', $mes03);
                $template->setValue('day02', $day02);
                $template->setValue('mes02', $mes02);
                $template->setValue('siRecomendaciones03', $siRecomendaciones03);
                $template->setValue('siRecomendaciones04', $siRecomendaciones04);
                $template->setValue('fechahoy', $fechaactual);
                $template->setValue('fecha_comparencia', $date01);
                $template->setValue('iniciales',$iniciales);
                $template->setValue('entidad',$textoDocumento);
                $template->setValue('entidad01',$nombreEntidad);
                $template->setValue('direccion_asig',$auditoria->direccion_asignada);
                $template->setValue('departamento_asig',$auditoria->departamento_encargado);
                $template->setValue('anio03', $anio03);
                $template->setValue('anio02', $anio02);
                
                $nombreword='AC. EA';/** */
            
            }elseif($tipo === 'AC_PAR'){
                if(count($auditoria->accionespo)){
                    $siPliegos01 = "de la Etapa de Aclaración";
                    $siPliegos02 = "con el objeto de que en un plazo de 30 (Treinta) días hábiles, solventara, aclarara o manifestara lo que a su derecho conviniera en relación al contenido de las acciones aludidas";
                    $siPliegos03 = ", 54";
                }
                $template=new TemplateProcessor('bases-word/AC/LEGALIDAD/AC_PAR_01.docx');
                $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
                $template->setValue('segtipo_auditoria',$auditoria->tipo_auditoria->descripcion);
                $template->setValue('periodo',$auditoria->periodo_revision);
                $template->setValue('numero_orden',$auditoria->numero_orden);
                $template->setValue('fecha_oficio_acuerdo',$fecha_oficio_acuerdo);
                $template->setValue('oficio_numero', $auditoria->radicacion->oficio_acuerdo);
                $template->setValue('plazo', $auditoria->radicacion->plazo_maximo);
                $template->setValue('plazoMaximoletra', $plazomaxMin);
                $template->setValue('numero_acta', $auditoria->radicacion->acta_cierre_auditoria);
                $template->setValue('day03', $day03);
                $template->setValue('mes03', $mes03);
                $template->setValue('fechahoy', $fechaactual);
                $template->setValue('iniciales',$iniciales);
                $template->setValue('entidad',$textoDocumento);
                $template->setValue('direccion_asig',$auditoria->direccion_asignada);
                $template->setValue('departamento_asig',$auditoria->departamento_encargado);
                $template->setValue('siPliegos01',$siPliegos01);
                $template->setValue('siPliegos02',$siPliegos02);
                $template->setValue('fecha_acta',$fecha_acta);
                $template->setValue('siPliegos03',$siPliegos03);
                $template->setValue('anio03', $anio03);

                $nombreword='AC. PAR';/** */
            }

            $template->saveAs($nombreword.'.docx');/** */

        }elseif($auditoria->acto_fiscalizacion=='Desempeño'){

            $template=new TemplateProcessor('bases-word/AC/DESEMPEÑO/AC_PAR_01.docx');
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('numero_orden',$auditoria->numero_orden);
            $template->setValue('fecha_oficio_acuerdo',$fecha_oficio_acuerdo);
            $template->setValue('oficio_numero', $auditoria->radicacion->oficio_acuerdo);
            $template->setValue('plazo', $auditoria->radicacion->plazo_maximo);
            $template->setValue('plazoMaximoletra', $plazomaxMin);
            $template->setValue('numero_acta', $auditoria->radicacion->acta_cierre_auditoria);
            $template->setValue('day03', $day03);
            $template->setValue('mes03', $mes03);
            $template->setValue('fechahoy', $fechaactual);
            $template->setValue('iniciales',$iniciales);
            $template->setValue('entidad',$textoDocumento);
            $template->setValue('entidad01',$nombreEntidad);
            $template->setValue('direccion_asig',$auditoria->direccion_asignada);
            $template->setValue('departamento_asig',$auditoria->departamento_encargado);
            $template->setValue('fecha_acta',$fecha_acta);
            $template->setValue('anio03', $anio03);

            $nombreword='AC. PAR';/** */

            $template->saveAs($nombreword.'.docx');/** */

        }elseif($auditoria->acto_fiscalizacion=='Cumplimiento Financiero'){
            if(count($auditoria->accionesrecomendaciones)>0){
                $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones correspondientes";
                $siRecomendaciones02 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones que le fueron formuladas, o en su caso, justificara su improcedencia";            
                $siRecomendaciones03 = "y al Proceso de Atención a las Recomendaciones";
                $siRecomendaciones04 = "y 54 Bis";
                $siRecomendaciones05 = ", XV";
            } 
            $template=new TemplateProcessor('bases-word/AC/CUMPLIMIENTO_FINANCIERO/AC_01.docx');
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('numero_orden',$auditoria->numero_orden);
            $template->setValue('fecha_oficio_acuerdo',$fecha_oficio_acuerdo);
            $template->setValue('direccion_asig',$auditoria->direccion_asignada);
            $template->setValue('departamento_asig',$auditoria->departamento_encargado);
            $template->setValue('entidad',$textoDocumento);
            $template->setValue('oficio_numero', $auditoria->radicacion->oficio_acuerdo);
            $template->setValue('siRecomendaciones01', $siRecomendaciones01);
            $template->setValue('siRecomendaciones02', $siRecomendaciones02);
            $template->setValue('day03', $day03);
            $template->setValue('mes03', $mes03);
            $template->setValue('anio03', $anio03);
            $template->setValue('day02', $day02);
            $template->setValue('mes02', $mes02);
            $template->setValue('anio02', $anio02);
            $template->setValue('entidad01',$nombreEntidad);
            $template->setValue('fecha_comparencia', $date01);
            $template->setValue('siRecomendaciones03', $siRecomendaciones03);
            $template->setValue('siRecomendaciones04', $siRecomendaciones04);
            $template->setValue('siRecomendaciones05', $siRecomendaciones05);
            $template->setValue('fechahoy', $fechaactual);
            $template->setValue('iniciales',$iniciales);

            $nombreword='AC';/** */

            $template->saveAs($nombreword.'.docx');/** */

        }elseif($auditoria->acto_fiscalizacion=='Inversión Física'){
            if(count($auditoria->accionesrecomendaciones)>0){
                $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones correspondientes";
                $siRecomendaciones02 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones que le fueron formuladas, o en su caso, justificara su improcedencia";            
                $siRecomendaciones03 = "y al Proceso de Atención a las Recomendaciones";
                $siRecomendaciones04 = "y 54 Bis";
                $siRecomendaciones05 = ", XV";
            } 
            $template=new TemplateProcessor('bases-word/AC/INVERSION_FISICA/AC_01.docx');
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('numero_orden',$auditoria->numero_orden);
            $template->setValue('fecha_oficio_acuerdo',$fecha_oficio_acuerdo);
            $template->setValue('direccion_asig',$auditoria->direccion_asignada);
            $template->setValue('departamento_asig',$auditoria->departamento_encargado);
            $template->setValue('entidad',$textoDocumento);
            $template->setValue('oficio_numero', $auditoria->radicacion->oficio_acuerdo);
            $template->setValue('siRecomendaciones01', $siRecomendaciones01);
            $template->setValue('siRecomendaciones02', $siRecomendaciones02);
            $template->setValue('day03', $day03);
            $template->setValue('mes03', $mes03);
            $template->setValue('anio03', $anio03);
            $template->setValue('day02', $day02);
            $template->setValue('mes02', $mes02);
            $template->setValue('anio02', $anio02);
            $template->setValue('entidad01',$nombreEntidad);
            $template->setValue('fecha_comparencia', $date01);
            $template->setValue('siRecomendaciones03', $siRecomendaciones03);
            $template->setValue('siRecomendaciones04', $siRecomendaciones04);
            $template->setValue('siRecomendaciones05', $siRecomendaciones05);
            $template->setValue('fechahoy', $fechaactual);
            $template->setValue('iniciales',$iniciales);

            $nombreword='AC';/** */

            $template->saveAs($nombreword.'.docx');/** */
        }

        

        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);/** */   
    }



    public function exportOFAC(){
        $auditoria=Auditoria::find(getSession('auditoria_id')); 

        $formatter = new NumeroALetras();
        $plazomax=$formatter->toString($auditoria->radicacion->plazo_maximo);
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[(now()->format('n')) - 1];

        $fechaactual=fechaaletra(now());
        $plazomaxMax = ucwords($plazomax);
        $plazomaxMin = ucwords(strtolower($plazomaxMax));
        $fecha_hora=fecha(optional($auditoria->comparecencia)->fecha_comparecencia) . ' ' . date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_inicio)) . (empty($auditoria->comparecencia->hora_comparecencia_termino)?"":"-".date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_termino)));

        $fecha_acta = fecha(optional($auditoria->radicacion)->fecha_acta);
        $fecha_oficio_acuerdo = fecha(optional($auditoria->radicacion)->fecha_oficio_acuerdo);

        $iniciales='';
        $inicialesLM='MAOV';
        $inicialesA="";
        $inicialesD='';
        $inicialesJD='';

        //$nombre=auth()->user()->name;
        $nD = $auditoria->directorasignado->name;
        $nJD = $auditoria->jefedepartamentoencargado->name;
        $nA = $auditoria->analistacp->name;

         $nD=explode(' ',$nD);
         foreach($nD as $parte){
            $inicialesD=$inicialesD.substr($parte, 0,1);
         }

        $nJD=explode(' ',$nJD);
            foreach($nJD as $parte){
                $inicialesJD=$inicialesJD.substr($parte, 0,1);
        }
        $nA=explode(' ',$nA);
            foreach($nA as $parte){
                $inicialesA=$inicialesA.substr($parte, 0,1);
        }
        $iniciales= $inicialesLM."/".$inicialesD."/".$inicialesA."/".$inicialesJD;
        $numeroAuditoria = Auditoria::find(getSession('auditoria_id'));

        if ($numeroAuditoria) {
        $entidad = ListadoEntidades::where('no_auditoria', $numeroAuditoria->numero_auditoria)
            ->where('cuenta_publica', $numeroAuditoria->cuenta_publica)
            ->select('entidades', 'textos_doc')
            ->first();

            if ($entidad) {
                $nombreEntidad = $entidad->entidades;
                $textoDocumento = $entidad->textos_doc;

            }
        }

        if(empty($auditoria->acuerdoconclusion->domicilio)){
            $remitente_domicilio = "";
        }else{
            $remitente_domicilio = $auditoria->acuerdoconclusion->domicilio;
        }

        $fecha_acuerdoLetra=fechaaletra($auditoria->comparecencia->fecha_oficio_acuerdo);
        $siRecomendaciones01 = "";


        /**///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
        if($auditoria->acto_fiscalizacion=='Legalidad'){
                $template=new TemplateProcessor('bases-word/AC/LEGALIDAD/OF_AC_01.docx');
                $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
                $template->setValue('periodo',$auditoria->periodo_revision);
                $template->setValue('numero_orden',$auditoria->numero_orden);
                $template->setValue('fecha_oficio_acuerdo',$fecha_oficio_acuerdo);
                $template->setValue('oficio_numero', $auditoria->radicacion->oficio_acuerdo);
                $template->setValue('iniciales',$iniciales);
                $template->setValue('entidad',$textoDocumento);
                $template->setValue('entidad01',$nombreEntidad);
                $template->setValue('direccion_asig',$auditoria->direccion_asignada);
                $template->setValue('departamento_asig',$auditoria->departamento_encargado);
                $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
                $template->setValue('anio',date("Y"));
                $template->setValue('mes',$mes);
                $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
                $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
                $template->setValue('remitente_domicilio',$remitente_domicilio);
                $template->setValue('fecha_oficioAcuerdo', $fecha_oficio_acuerdo);
                $template->setValue('fecha_acuerdoLetra', $fecha_acuerdoLetra);
                $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
                $nombreword='OF. AC';/** */

            $template->saveAs($nombreword.'.docx');/** */

        }elseif($auditoria->acto_fiscalizacion=='Desempeño'){
            $template=new TemplateProcessor('bases-word/AC/DESEMPEÑO/OF_AC_PAR_01.docx');
            $template->setValue('departamento_asig',$auditoria->departamento_encargado);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('numero_orden',$auditoria->numero_orden);
            $template->setValue('fecha_oficio_acuerdo',$fecha_oficio_acuerdo);
            $template->setValue('anio',date("Y"));
            $template->setValue('mes',$mes);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('remitente_domicilio',$remitente_domicilio);
            $template->setValue('fecha_oficioAcuerdo', $fecha_oficio_acuerdo);
            $template->setValue('fecha_acuerdoLetra', $fecha_acuerdoLetra);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('oficio_numero', $auditoria->radicacion->oficio_acuerdo);
            $template->setValue('entidad',$textoDocumento);
            $template->setValue('iniciales',$iniciales);
            $template->setValue('direccion_asig',$auditoria->direccion_asignada);
            $template->setValue('departamento_asig',$auditoria->departamento_encargado);

            $nombreword='OF. AC PAR';/** */

            $template->saveAs($nombreword.'.docx');/** */

        }elseif($auditoria->acto_fiscalizacion=='Cumplimiento Financiero'){
            if(count($auditoria->accionesrecomendaciones)>0){
                $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones";
            } 

            $template=new TemplateProcessor('bases-word/AC/CUMPLIMIENTO_FINANCIERO/OF_AC_01.docx');
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('numero_orden',$auditoria->numero_orden);
            $template->setValue('fecha_oficio_acuerdo',$fecha_oficio_acuerdo);
            $template->setValue('direccion_asig',$auditoria->direccion_asignada);
            $template->setValue('departamento_asig',$auditoria->departamento_encargado);
            $template->setValue('anio',date("Y"));
            $template->setValue('mes',$mes);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('oficio_numero', $auditoria->radicacion->oficio_acuerdo);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('remitente_domicilio',$remitente_domicilio);
            $template->setValue('siRecomendaciones01',$siRecomendaciones01);
            $template->setValue('fecha_acuerdoLetra', $fecha_acuerdoLetra);
            $template->setValue('entidad',$textoDocumento);
            $template->setValue('iniciales',$iniciales);



            $nombreword='OF. AC';/** */

            $template->saveAs($nombreword.'.docx');/** */

        }elseif($auditoria->acto_fiscalizacion=='Inversión Física'){
            if(count($auditoria->accionesrecomendaciones)>0){
                $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones ";
            } 
            $template=new TemplateProcessor('bases-word/AC/INVERSION_FISICA/OF_AC_01.docx');
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('numero_orden',$auditoria->numero_orden);
            $template->setValue('fecha_oficio_acuerdo',$fecha_oficio_acuerdo);
            $template->setValue('direccion_asig',$auditoria->direccion_asignada);
            $template->setValue('departamento_asig',$auditoria->departamento_encargado);
            $template->setValue('anio',date("Y"));
            $template->setValue('mes',$mes);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('oficio_numero', $auditoria->radicacion->oficio_acuerdo);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('remitente_domicilio',$remitente_domicilio);
            $template->setValue('siRecomendaciones01',$siRecomendaciones01);
            $template->setValue('fecha_acuerdoLetra', $fecha_acuerdoLetra);
            $template->setValue('entidad',$textoDocumento);
            $template->setValue('iniciales',$iniciales);

            $nombreword='OF. AC';/** */

            $template->saveAs($nombreword.'.docx');/** */
        }


        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);/** */   
    }
}
