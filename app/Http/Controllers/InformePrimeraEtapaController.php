<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformePrimeraEtapaRequest;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\InformePrimeraEtapa;
use App\Models\PliegosObservacion;
use App\Models\Recomendaciones;
use App\Models\ListadoEntidades;
use App\Models\Segpras;
use App\Models\SolicitudesAclaracion;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\NumberFormatter;
use PhpOffice\PhpWord\TemplateProcessor;



class InformePrimeraEtapaController extends Controller
{
    protected $model;

    public function __construct(InformePrimeraEtapa $model)
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
        
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $informeprimeraetapa=InformePrimeraEtapa::where('auditoria_id',getSession('auditoria_id'))->first();
        

        return view('informeprimeraetapa.index', compact('request','informeprimeraetapa', 'auditoria'));
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
        // $consecutivo=InformePrimeraEtapa::where('segauditoria_id',$auditoria->id)->whereNull('eliminado')->get()->count()+1;
        $informeprimeraetapa = new InformePrimeraEtapa();
        $request['usuario_creacion_id'] = auth()->user()->id;
       
        return view('informeprimeraetapa.form', compact('auditoria','informeprimeraetapa','tipo'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        mover_archivos($request, ['InformePrimeraEtapa']);
        $request['usuario_creacion_id'] = auth()->user()->id;
        $request['auditoria_id']=getSession('auditoria_id');            
        InformePrimeraEtapa::create($request->all());
        setMessage("Los datos se han guardado correctamente.");

        return redirect() -> route('informeprimeraetapa.index');
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
    public function edit(InformePrimeraEtapa $auditoria)
    {
        // dd($auditoria);
      
        $informeprimeraetapa=$auditoria;       
        $tipo=$informeprimeraetapa->tipo;                 
        $auditoria = Auditoria::find(getSession('auditoria_id'));        
       
        return view('informeprimeraetapa.form', compact('auditoria','informeprimeraetapa','tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InformePrimeraEtapa $auditoria)
    { 
        $informeprimeraetapa=$auditoria;
        mover_archivos($request, ['InformePrimeraEtapa']);
        $request['usuario_modificacion_id'] = auth()->user()->id;        
        $request['auditoria_id']=getSession('auditoria_id');            
        $auditoria->update($request->all());
        $auditoria=$auditoria->auditoria;



        setMessage("Los datos se han actualizado correctamente.");
        return redirect() -> route('informeprimeraetapa.index',compact('auditoria','informeprimeraetapa'));

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

    
    public function informepliegos()
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));     
        $tipo='pliegos';          
        $informeprimeraetapa = new InformePrimeraEtapa();
        $request['usuario_creacion_id'] = auth()->user()->id;
      
       
        return view('informeprimeraetapa.form', compact('auditoria','informeprimeraetapa','tipo'));

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
        setSession('informeprimeraetapa_auditoria_id',$auditoria->id);

        return redirect()->route('informeprimeraetapa.create');
    }
     public function export(Request $request){
        $auditoria=Auditoria::find(getSession('auditoria_id')); 
        $pras = Segpras::where('accion_id',getSession('prasauditoriaaccion_id'))->get();


        $tipo = $request->query('tipo'); // tipo para identificar el archivo solo aplica para legalidad
        $siRecomendaciones01 = "";
        $siRecomendaciones02 = "";
        $siRecomendaciones03 = "";
        $siRecomendaciones04 = "";
        $siRecomendaciones05 = "";
        $siPliegos01 ="";
        $siPliegos02 ="";
        $siPliegos03 ="";
        $iniciales='';
        $inicialesLM='MAOV';
        $inicialesA="";
        $inicialesD='';
        $inicialesJD='';
        $horaMin='';
        $minutosMin='';
        $fechacomparecencia='';
        $fechainicioaclaracion='';
        $fechaterminoaclaracion='';
        $SiPRAS="";
        $SiPRAS01="";
        $SiPRAS02="";
        $Orden = '';
        $Orden01 = '';
        $Orden02 = '';
        $nombre_ccp='';
        $info_ccp='';
        $infodom_ccp='';
        $info='';
        $day02 = "";
        $mes02 = "";
        $day03 = "";
        $mes03 =  "";

        if ($auditoria) {
            $entidad = ListadoEntidades::where('no_auditoria', $auditoria->numero_auditoria)->where('cuenta_publica', $auditoria->cuenta_publica)->select('entidades', 'textos_doc')->first();
                if ($entidad) {
                    $nombreEntidad = $entidad->entidades;
                    $textoDocumento = $entidad->textos_doc;
                }
            }
        $fecha_oficio_acuerdo = fecha(optional($auditoria->radicacion)->fecha_oficio_acuerdo);
        

        if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero'){
            if(count($auditoria->accionesrecomendaciones)>0){
                $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones correspondientes";
                $siRecomendaciones02 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones que le fueron formuladas, o en su caso, justificara su improcedencia";
                $siRecomendaciones03 = "y al Proceso de Atención a las Recomendaciones";
                $siRecomendaciones04 = "y 54 Bis";
                $siRecomendaciones05 = ", XV";
            }
            if(count($auditoria->accionespras)>0){
                $SiPRAS01 ="Adicional a lo anterior y en términos de lo previsto en los artículos 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México; 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y;
                             23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, por medio del diverso número". $pras->numero_oficio .", se turnaron por cuerda separada al Órgano Interno de Control competente, 
                             las Promociones de Responsabilidad Administrativa Sancionatoria, para que se continúe con la investigación pertinente y promueva las acciones procedentes, ordenándose formar expedientillo relativo a las Promociones de Responsabilidad Administrativa Sancionatoria.";
                if($pras->estatus_cumplimiento == 'No Atendido'){
                    $SiPRAS02= "Así pues, agotado el plazo para la atención de las observaciones a que se alude en el segundo párrafo del presente apartado, sin que a la fecha de emisión del presente se tenga evidencia documental ingresada por parte de la entidad fiscalizada, se llegó a la conclusión de los siguientes: ===================================";
                }
            }
            if(count($auditoria->accionespo)>0){
                $siPliegos01 = "de la Etapa de Aclaración";
                $siPliegos02 = "con el objeto de que en un plazo de 30 (Treinta) días hábiles, solventara, aclarara o manifestara lo que a su derecho conviniera en relación al contenido de las acciones aludidas";
                $siPliegos03 = ", 54";
            }

            $template=new TemplateProcessor('bases-word/IS/CUMPLIMIENTO_FINANCIERO/IS_01.docx');   
            $template->setValue('direccion_asig',$auditoria->direccion_asignada);
            $template->setValue('departamento_asig',$auditoria->departamento_encargado);
            $template->setValue('entidad01',$nombreEntidad);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('entidad',$textoDocumento);
            $template->setValue('numero_orden',$auditoria->numero_orden);
            $template->setValue('fecha_oficio_acuerdo',$fecha_oficio_acuerdo);
            $template->setValue('oficio_numero', $auditoria->radicacion->oficio_acuerdo);
            $template->setValue('siRecomendaciones01', $siRecomendaciones01);
            $template->setValue('siRecomendaciones02', $siRecomendaciones02);
            $template->setValue('SiPRAS01', $SiPRAS01);
            $template->setValue('SiPRAS02', $SiPRAS02);


            $template->setValue('periodo',$auditoria->periodo_revision);

            $accionesprueba = AuditoriaAccion::where('segauditoria_id', $auditoria->id)->whereIn('segtipo_accion_id',[1,3])->orderBy('consecutivo')->get();
            //$segsolacDatos = SolicitudesAclaracion::where('auditoria_id', $auditoria->id)->get()->toArray();
            //$segpliego = PliegosObservacion::where('auditoria_id', $auditoria->id)->get();
            
            //dd($accionesprueba->toArray(), $segsolac->toArray(), $segpliego->toArray());

            $segsolac = AuditoriaAccion::select('segauditoria_acciones.consecutivo',
                                                        'segauditoria_acciones.tipo',/*TABLA segauditoria_acciones*/
                                                        'segauditoria_acciones.accion',
                                                        'segauditoria_acciones.numero',   
                                                        'segauditoria_acciones.plazo_recomendacion',   
                                                        'segauditoria_acciones.monto_aclarar',   
                                                        'segauditoria_acciones.monto_aclarar',
                                                        DB::raw("expresar_en_letras.numero_a_letras(segauditoria_acciones.monto_aclarar) AS monto_aclarar_letras"), 
            /*TABLA segsolicitudes_acl_contestaciones*/ DB::raw("(case when(segsolicitudes_acl_contestaciones.oficio_contestacion IS NULL) THEN 'En ese orden de ideas, esta Unidad de Seguimiento hace constar que durante el plazo concedido para el desahogo de la Etapa de Aclaración, la entidad fiscalizada no presentó información, documentación o consideraciones relacionadas con la observación de mérito.' ELSE null END) AS sicontestacion01"),
                                                        'segsolicitudes_acl_contestaciones.numero_oficio', 
                                                        //'segsolicitudes_acl_contestaciones.fecha_oficio_contestacion', 
                                                        //'segsolicitudes_acl_contestaciones.fecha_recepcion_oficialia',
                                                        DB::raw("TO_CHAR(segsolicitudes_acl_contestaciones.fecha_oficio_contestacion, 'DD/MM/YYYY') AS fecha_oficio_contestacion"),
                                                        DB::raw("TO_CHAR(segsolicitudes_acl_contestaciones.fecha_recepcion_oficialia, 'DD/MM/YYYY') AS fecha_recepcion_oficialia"), 
                                                        'segsolicitudes_acl_contestaciones.folio_correspondencia', 
                                                        'segsolicitudes_acl_contestaciones.nombre_remitente', 
                                                        'segsolicitudes_acl_contestaciones.cargo_remitente', 
                                                        'segsolicitudes_aclaracion.calificacion_sugerida',/*TABLA segsolicitudes_aclaracion*/
                                                        'segsolicitudes_aclaracion.analisis',
                                                        'segsolicitudes_aclaracion.conclusion',
                                                        DB::raw("(case when(segsolicitudes_aclaracion.analisis IS NULL) THEN '' ELSE null END) AS siContestacion01"),
                                                )        
                                    ->join('segsolicitudes_aclaracion', 'segsolicitudes_aclaracion.accion_id', '=', 'segauditoria_acciones.id')
                                    ->leftJoin('segsolicitudes_acl_contestaciones', 'segsolicitudes_acl_contestaciones.solicitudaclaracion_id',"=",'segsolicitudes_aclaracion.id')
                                    ->where('auditoria_id', $auditoria->id)
                                    ->get()
                                    ->toArray();

            $segsolac= array_map("unserialize", array_unique(array_map('serialize',$segsolac)));

            $segpliego = AuditoriaAccion::select('segauditoria_acciones.consecutivo',
                                                 'segauditoria_acciones.tipo',
                                                 'segauditoria_acciones.numero',

                                                 'segpliegos_observacion.calificacion_sugerida')
                                    ->join('segpliegos_observacion', 'segpliegos_observacion.accion_id', '=', 'segauditoria_acciones.id')
                                    ->where('auditoria_id', $auditoria->id)
                                    ->get()->toArray();
            
            $acciones01 = array_merge($segsolac,$segpliego);
            
            //dd(($acciones01));
            //dd($segpliego->toArray());

            $template->cloneBlock('block_name', count($accionesprueba), true, false, $acciones01);

            $nombreword='IS';
        }    

        
        $template->saveAs($nombreword.'.docx');
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
     }        
     public function exportOFIS(){
        dd("OF ");
     } 
}
