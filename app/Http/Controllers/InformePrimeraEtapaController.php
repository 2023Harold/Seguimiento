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
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToArray;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\NumberFormatter;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpParser\Node\Stmt\Else_;
use SebastianBergmann\Type\TrueType;
use Carbon\Carbon;



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
        $formatter = new NumeroALetras();
        $plazomax=$formatter->toString($auditoria->radicacion->plazo_maximo);
        $plazomaxMax = ucwords($plazomax);
        $plazomaxMin = ucwords(strtolower($plazomaxMax));
        $fechaactual=fechaaletra(now());

        $tipo = $request->query('tipo'); // tipo para identificar el archivo solo aplica para legalidad
        $siRecomendaciones01 = ""; $siRecomendaciones02 = ""; $siRecomendaciones03 = "";$siRecomendaciones04 = ""; $siRecomendaciones05 = ""; $siRecomendaciones06 = ""; $siRecomendaciones07 = ""; $siRecomendaciones08 = ""; $siPliegos01 =""; $siPliegos02 =""; $siPliegos03 =""; $siPliegos04 =""; $siPliegos05 =""; $siPliegos06 =""; $siPliegos07 =""; $siPliegos08 =""; $siPliegos09 = ""; $siPliegos10 = "";
        $siSolAc01 = ""; $siSolAc02 = ""; $siSolAc03 = ""; $siSolAc04 = ""; $siSolAc05 = ""; $siSolAc06 = ""; $siSolAc07 = ""; $siSolAc08 = ""; $siSolAc09 = ""; $siSolAc10 = "";
        $horaMin=''; $minutosMin='';
        $fechacomparecencia=''; $fechainicioaclaracion=''; $fechaterminoaclaracion='';
        $SiPRAS=""; $siPRAS01=""; $siPRAS02="";
        $day02 = ""; $mes02 = "";$day03 = ""; $mes03 =  "";
        $fecha_PAA = "";$fecha_orden = "";
        $AnalisisAntecede01 = '';
        $accionSolventada01 = ""; $accionSolventada02 = ""; $accionSolventada03 = ""; $accionSolventada04 = ""; $accionSolventada05 = ""; $accionSolventada06 = ""; $accionSolventada07 = ""; $accionSolventada08 = ""; $accionSolventada09 = ""; $accionSolventada10 = "";
        $rec01 = "";$rec02 = ""; $fojasU = ""; $fojasULetras = ""; $remitente = "";  $remitente_cargo = ""; $remitente_domicilio =  ""; $oficio_numero_informe = ""; $plazo01 = $auditoria->radicacion->plazo_maximo;
        if ($auditoria) {
            $entidad = ListadoEntidades::where('no_auditoria', $auditoria->numero_auditoria)->where('cuenta_publica', $auditoria->cuenta_publica)->select('entidades', 'textos_doc')->first();
                if ($entidad) {
                    $nombreEntidad = $entidad->entidades;
                    $textoDocumento = $entidad->textos_doc;
                }
            }
        
        $ent = $auditoria->entidad_fiscalizable;
        $frac ='';
        if($auditoria->entidadFiscalizable->Ambito = 'Estatal'){
            $nombre_ccp='</w:t><w:br/><w:t>Luis David Fernández Araya.';
            $info_ccp= 'C.c.p.  '.$nombre_ccp.' Encargado del Despacho de la Subsecretaría de Control y Auditoría de la Secretaría de la Contraloría del Gobierno del Estado de México. </w:t><w:br/><w:t>';
            $infodom_ccp='Domicilio: Av. Primero de Mayo, número 1731, Esquina Robert Bosch, Colonia Zona Industrial, C.P. 50071, Toluca, México.</w:t><w:br/><w:t>';
            $info=$info_ccp.' '.$infodom_ccp;
            $ambito01 = "";
            if (stripos($ent, 'poder') !== false) {
                $frac = 'fracción I.';
            }elseif(stripos($ent, 'órganos autónomos') !== false) {
                $frac = 'fracción III.';
            }elseif(stripos($ent, 'organismo auxiliar') !== false){
                $frac = 'fracción IV.';
            }elseif(stripos($ent, 'fideicomiso') !== false){
                $frac = 'fracción V.';
            }
            
        }elseif($auditoria->entidadFiscalizable->Ambito = 'Municipal'){
            $ambito01 = "115 fracción IV penúltimo párrafo,";
            if (stripos($ent, 'fideicomiso') !== false) {
                $frac = 'fracción V.';
            }elseif(stripos($ent, 'organismo auxiliar') !== false) {
                $frac = 'fracción IV.';
            }elseif(stripos($ent, 'municipios') !== false){
                $frac = 'fracción II.';
            }
        }

        $fecha_oficio_acuerdo = fecha(optional($auditoria->radicacion)->fecha_oficio_acuerdo);
        $segsolac = AuditoriaAccion::select('segauditoria_acciones.consecutivo',/*TABLA segauditoria_acciones*/
                                                'segauditoria_acciones.tipo', 'segauditoria_acciones.accion', 'segauditoria_acciones.numero', 'segauditoria_acciones.plazo_recomendacion', 'segauditoria_acciones.monto_aclarar', 'segauditoria_acciones.normativa_infringida',
                                                DB::raw("expresar_en_letras.numero_a_letras(segauditoria_acciones.monto_aclarar) AS monto_aclarar_letras"), 
    /*TABLA segsolicitudes_acl_contestaciones*/ DB::raw("(case when(segsolicitudes_acl_contestaciones.oficio_contestacion IS NULL) THEN 'En ese orden de ideas, esta Unidad de Seguimiento hace constar que durante el plazo concedido para el desahogo de la Etapa de Aclaración, la entidad fiscalizada no presentó información, documentación o consideraciones relacionadas con la observación de mérito.' ELSE null END) AS sicontestacion01"),
                                                'segsolicitudes_acl_contestaciones.numero_oficio', 
                                                //'segsolicitudes_acl_contestaciones.fecha_oficio_contestacion', 
                                                //'segsolicitudes_acl_contestaciones.fecha_recepcion_oficialia',
                                                DB::raw("TO_CHAR(segsolicitudes_acl_contestaciones.fecha_oficio_contestacion, 'DD/MM/YYYY') AS fecha_oficio_contestacion"),
                                                DB::raw("TO_CHAR(segsolicitudes_acl_contestaciones.fecha_recepcion_oficialia, 'DD/MM/YYYY') AS fecha_recepcion_oficialia"), 
                                                'segsolicitudes_acl_contestaciones.folio_correspondencia', 'segsolicitudes_acl_contestaciones.nombre_remitente', 'segsolicitudes_acl_contestaciones.cargo_remitente', 
                                                'segsolicitudes_aclaracion.calificacion_sugerida',/*TABLA segsolicitudes_aclaracion*/
                                                'segsolicitudes_aclaracion.analisis', 'segsolicitudes_aclaracion.conclusion', 'segsolicitudes_aclaracion.listado_documentos',
                                                DB::raw("(case when(segsolicitudes_aclaracion.promocion IS NOT NULL AND segsolicitudes_aclaracion.promocion = 3 AND segsolicitudes_aclaracion.calificacion_sugerida = 'No Solventada' )  THEN 'En ese sentido, con fundamento en lo dispuesto por los artículos 54 fracción III de la Ley de Fiscalización Superior del Estado de México y; 23 fracciones XIX y XLIV y 47 fracciones XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, en relación con el diverso 7 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios, se procede a formular el siguiente Pliego de Observaciones identificado con clave de acción '||segauditoria_acciones.numero||'.' ELSE null END) AS sicalificacionsugerida01"),
                                                DB::raw("(case when(segsolicitudes_aclaracion.calificacion_sugerida = 'Solventada') THEN 'En ese sentido, con fundamento en lo dispuesto por los artículos 54 fracción III de la Ley de Fiscalización Superior del Estado de México y; 23 fracciones XIX y XLIV y; 47 fracciones XII y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, se determina que la Solicitud de Aclaración ha quedado aclarado y solventado.' ELSE NULL END) AS sicalificacionsugerida02"),

                                                DB::raw("(case when(segsolicitudes_aclaracion.promocion = 3) THEN segauditoria_acciones.numero ELSE NULL END) AS numsolacpo"),
                                                DB::raw("(case when(segsolicitudes_aclaracion.promocion = 2) THEN segauditoria_acciones.numero ELSE NULL END) AS numporecomen"),
                                                DB::raw("(case when(segsolicitudes_aclaracion.promocion = 4) THEN segauditoria_acciones.numero ELSE NULL END) AS numprompras"),

                                                DB::raw("(case when(segsolicitudes_aclaracion.calificacion_sugerida = 'Solventada') THEN segauditoria_acciones.numero ELSE NULL END) AS tsolac01")
                                                )  

                            ->join('segsolicitudes_aclaracion', 'segsolicitudes_aclaracion.accion_id', '=', 'segauditoria_acciones.id')
                            ->leftJoin('segsolicitudes_acl_contestaciones', 'segsolicitudes_acl_contestaciones.solicitudaclaracion_id',"=",'segsolicitudes_aclaracion.id')
                            ->where('auditoria_id', $auditoria->id)
                            ->get()
                            ->toArray();
        $segsolac= array_map("unserialize", array_unique(array_map('serialize',$segsolac)));
        $NumSolacPromPo = collect($segsolac)->pluck('numsolacpo')->filter()->implode(', ');
        $CantSolacPromPo = count(collect($segsolac)->pluck('numsolacpo')->filter());
        $NumSolacPromRecomendaciones = collect($segsolac)->pluck('numporecomen')->filter()->implode(', ');
        $NumSolacPromPRAS = collect($segsolac)->pluck('numprompras')->filter()->implode(', ');
        $tsolac01 = collect($segsolac)->pluck('tsolac01')->filter()->implode(', ');
        

        $segpliego = AuditoriaAccion::select('segauditoria_acciones.tipo',/*TABLA segauditoria_acciones*/
                                                'segauditoria_acciones.accion', 'segauditoria_acciones.numero', 'segauditoria_acciones.plazo_recomendacion', 'segauditoria_acciones.monto_aclarar', 'segauditoria_acciones.normativa_infringida',
                                                DB::raw("expresar_en_letras.numero_a_letras(segauditoria_acciones.monto_aclarar) AS monto_aclarar_letras"), 
   /*TABLA segpliegos_observacion_contestacion*/DB::raw("(case when(segpliegos_observacion_contestacion.oficio_contestacion IS NULL) THEN 'En ese orden de ideas, esta Unidad de Seguimiento hace constar que durante el plazo concedido para el desahogo de la Etapa de Aclaración, la entidad fiscalizada no presentó información, documentación o consideraciones relacionadas con la observación de mérito.' ELSE null END) AS sicontestacion01"),
                                                'segpliegos_observacion_contestacion.numero_oficio', 
                                                DB::raw("TO_CHAR(segpliegos_observacion_contestacion.fecha_oficio_contestacion, 'DD/MM/YYYY') AS fecha_oficio_contestacion"),
                                                DB::raw("TO_CHAR(segpliegos_observacion_contestacion.fecha_recepcion_oficialia, 'DD/MM/YYYY') AS fecha_recepcion_oficialia"), 
                                                'segpliegos_observacion_contestacion.folio_correspondencia', 'segpliegos_observacion_contestacion.nombre_remitente', 'segpliegos_observacion_contestacion.cargo_remitente', 
                                                'segpliegos_observacion.calificacion_sugerida',/*TABLA segpliegos_observacion*/
                                                'segpliegos_observacion.analisis', 'segpliegos_observacion.conclusion','segpliegos_observacion.listado_documentos',
                                                DB::raw("(case when(segpliegos_observacion.calificacion_sugerida = 'No Solventado') THEN 'Por tanto, se tiene como no aclarado ni solventado para este Órgano Superior de Fiscalización del Estado de México, el Pliego de Observaciones con clave de acción '||segauditoria_acciones.numero||'; en consecuencia, con fundamento en el artículo 47 fracciones XII, XVIII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; el Pliego de Observaciones será turnado a la autoridad investigadora de este Órgano Técnico, a efecto de que se inicie el procedimiento administrativo de investigación a que haya lugar, en términos de la Ley General de Responsabilidades Administrativas, la Ley de Responsabilidades Administrativas del Estado de México y Municipios y demás disposiciones jurídicas aplicables.' ELSE NULL END) AS sicalificacionsugerida01"),
                                                DB::raw("(case when(segpliegos_observacion.calificacion_sugerida = 'Solventado') THEN 'En ese sentido, con fundamento en lo dispuesto por los artículos 54 fracción III de la Ley de Fiscalización Superior del Estado de México y; 23 fracciones XIX y XLIV y; 47 fracciones XII y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, se determina que el Pliego de Observaciones ha quedado aclarado y solventado.' ELSE NULL END) AS sicalificacionsugerida02"),

                                                DB::raw("(case when(segpliegos_observacion.promocion = 4) THEN segauditoria_acciones.numero ELSE NULL END) AS numpopras"),
                                                DB::raw("(case when(segpliegos_observacion.promocion = 2) THEN segauditoria_acciones.numero ELSE NULL END) AS numporecomen"),

                                                DB::raw("(case when(segpliegos_observacion.calificacion_sugerida = 'Solventado') THEN segauditoria_acciones.numero ELSE NULL END) AS tpo01")
                                                )
                                             
                                ->join('segpliegos_observacion', 'segpliegos_observacion.accion_id', '=', 'segauditoria_acciones.id')
                                ->leftJoin('segpliegos_observacion_contestacion', 'segpliegos_observacion_contestacion.pliegosobservacion_id',"=",'segpliegos_observacion.id')
                                ->where('auditoria_id', $auditoria->id)
                                ->get()->toArray();
        $segpliego= array_map("unserialize", array_unique(array_map('serialize',$segpliego)));
        $accionesSolAcPo = array_merge($segsolac,$segpliego);
        $NumPoPromPRAS = collect($segpliego)->pluck('numpopras')->filter()->implode(', ');
        $NumPoPromRecomendaciones = collect($segpliego)->pluck('numporecomen')->filter()->implode(', ');
        $tpo01 = collect($segpliego)->pluck('tpo01')->filter()->implode(', ');

        $segrecomendacion = AuditoriaAccion::select('segauditoria_acciones.accion',/*TABLA segauditoria_acciones*/
                                                'segauditoria_acciones.numero', 'segauditoria_acciones.plazo_recomendacion', 'segauditoria_acciones.monto_aclarar','segauditoria_acciones.normativa_infringida',
                                                DB::raw("expresar_en_letras.numero_a_letras(segauditoria_acciones.monto_aclarar) AS monto_aclarar_letras"), 
    /*TABLA segrecomendaciones_contestaciones*/ DB::raw("(case when(segrecomendaciones_contestaciones.oficio_contestacion IS NULL) THEN 'En ese orden de ideas, esta Unidad de Seguimiento hace constar que durante el plazo concedido para el desahogo del Proceso de Atención a Recomendaciones, la entidad fiscalizada no presentó información, documentación o consideraciones relacionadas con la Recomendación de mérito.' ELSE null END) AS sicontestacion01"),
                                                'segrecomendaciones_contestaciones.numero_oficio', 
                                                DB::raw("TO_CHAR(segrecomendaciones_contestaciones.fecha_oficio_contestacion, 'DD/MM/YYYY') AS fecha_oficio_contestacion"),
                                                DB::raw("TO_CHAR(segrecomendaciones_contestaciones.fecha_recepcion_oficialia, 'DD/MM/YYYY') AS fecha_recepcion_oficialia"), 
                                                'segrecomendaciones_contestaciones.folio_correspondencia', 'segrecomendaciones_contestaciones.nombre_remitente', 'segrecomendaciones_contestaciones.cargo_remitente', 
                                                'segrecomendaciones.calificacion_sugerida',/*TABLA segrecomendaciones*/
                                                'segrecomendaciones.analisis', 'segrecomendaciones.conclusion', 'segrecomendaciones.listado_documentos',
                                                DB::raw("(case when(segrecomendaciones.calificacion_sugerida = 'Atendida') THEN 'En ese sentido, con fundamento en lo dispuesto por los artículos 54 fracción III de la Ley de Fiscalización Superior del Estado de México y; 23 fracciones XIX y XLIV y; 47 fracciones XII y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, se determina que XXX ha quedado aclarado y solventado.' ELSE NULL END) AS sicalificacionsugerida01"),
                                                DB::raw("(case when(segrecomendaciones.calificacion_sugerida = 'Atendida') THEN segauditoria_acciones.numero ELSE NULL END) AS tr01"),
                                                DB::raw("(case when(segrecomendaciones.calificacion_sugerida = 'No Atendida') THEN 'Por tanto se tiene como no atendida para este Órgano Superior de Fiscalización del Estado de México, la Recomendación con clave de acción XXX; en consecuencia, con fundamento en los artículos 8 fracción XXVII de la Ley de Fiscalización Superior del Estado de México y 23 fracciones XIX y XLIV y 47 fracción XV segundo párrafo y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; la Recomendación será turnada al Órgano Interno de Control de XXX o su equivalente, para el efecto de que dicha autoridad de control interno XXX promueva las acciones procedentes que garanticen su atención y cumplimiento.' ELSE NULL END)  AS sicalificacionsugerida02" ),
                                             
                                            )
                                             
                                ->join('segrecomendaciones', 'segrecomendaciones.accion_id', '=', 'segauditoria_acciones.id')
                                ->leftJoin('segrecomendaciones_contestaciones', 'segrecomendaciones_contestaciones.recomendacion_id',"=",'segrecomendaciones.id')
                                ->where('auditoria_id', $auditoria->id)
                                ->get()->toArray();
        $segrecomendacion= array_map("unserialize", array_unique(array_map('serialize',$segrecomendacion)));
        $accionesRecomendaciones = array_merge($segrecomendacion);
        $tr01 = collect($segrecomendacion)->pluck('tr01')->filter()->implode(', ');

        if ($auditoria) {
            $entidad = ListadoEntidades::where('no_auditoria', $auditoria->numero_auditoria)->where('cuenta_publica', $auditoria->cuenta_publica)->select('entidades', 'textos_doc')->first();
                if ($entidad) {
                    $nombreEntidad = $entidad->entidades;
                    $entidad01 = $nombreEntidad;
                    $textoDocumento = $entidad->textos_doc;
                }
            }

        if(count($auditoria->totalsolventadorecomendacion)>0 || count($auditoria->totalsolventadopras)>0 ||count($auditoria->totalsolventadosolacl)>0 ||count($auditoria->totalsolventadopliegos)>0 ){
                $accionSolventada01 = "Finalmente, con fundamento en los artículos 16, 116 fracción II sexto párrafo y 134 segundo y quinto párrafos de la Constitución Política de los Estados Unidos Mexicanos; 34, 61 fracciones XXXII, XXXIII y XXXIV y 129 penúltimo párrafo de la Constitución Política del Estado Libre y Soberano de México; 94 fracción I y 95 de la Ley Orgánica del Poder Legislativo del Estado Libre y Soberano de México; 1, 3, 4 {$frac} , 5, 6, 7, 8, 9, 21, 42 Bis, 53, 54 {$siRecomendaciones07} de la Ley de Fiscalización Superior del Estado de México; 4, 6 fracciones XVIII y XXXVII, 23 y 47 fracciones III, XII, XVI y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; el Titular de la Unidad de Seguimiento, determina:";
                if(count($auditoria->totalNOsolventadorecomendacion)>0 || count($auditoria->totalNOsolventadosolacl)>0 || count($auditoria->totalNOsolventadopliegos)>0 ){   
                    $accionSolventada03 = "Que en términos del acuerdo que antecede, se determina la conclusión del seguimiento a dichas observaciones.";   
                    $accionSolventada04 = "";
                    $accionSolventada08 = "Por lo antes expuesto, con fundamento en los artículos 16, 116 fracción II sexto párrafo y 134 segundo y quinto párrafos de la Constitución Política de los Estados Unidos Mexicanos; 34, 61 fracciones XXXII, XXXIII y XXXIV y 129 penúltimo párrafo de la Constitución Política del Estado Libre y Soberano de México; 94 fracción I y 95 de la Ley Orgánica del Poder Legislativo del Estado Libre y Soberano de México; 1, 3, 4 {$frac} , 5, 6, 7, 8, 9, 21, 53, 54 {$siRecomendaciones07} de la Ley de Fiscalización Superior del Estado de México; 4, 6 fracciones XVIII y XXXVII, 23 y 47 fracciones III, XII, XVI y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; el Titular de la Unidad de Seguimiento; determina lo siguiente:";
                    $accionSolventada09 = "PRIMERO. Se emite y autoriza el presente Informe de Seguimiento.";
                    $accionSolventada10 = "SEGUNDO. Notifíquese por oficio a la entidad fiscalizada en el domicilio ubicado en {$remitente_domicilio}.";
                }
                $accionSolventada02 = "PRIMERO. Se emite y autoriza el presente Informe de Seguimiento.";
                $accionSolventada03 = "SEGUNDO. Que las observaciones descritas e identificadas con clave número " .collect($tsolac01.', '.$tpo01.', '.$tr01.', '). "han quedado aclaradas y solventadas/atendidas.";   
                $accionSolventada04 = "TERCERO. Que en términos del numeral que antecede, se determina la conclusión del seguimiento a los resultados obtenidos en la Auditoría de Cumplimiento Financiero, practicada {$textoDocumento}, por el período comprendido del {$auditoria->periodo_revision} y ordenada mediante oficio número {$auditoria->numero_orden}. ";
                $accionSolventada05 = "Lo anterior, sin que implique la liberación de responsabilidades que pudieran llegarse a determinar con posterioridad por las autoridades de control y/o fiscalización federales o estatales que efectúen en el ámbito de su competencia; o bien, de aquellas que pudieran resultar de auditorías o revisiones que en ejercicio de sus atribuciones realice esta entidad estatal de fiscalización, al mismo período o períodos diferentes.";
                $accionSolventada06 = "CUARTO. Archívese el Expediente Técnico de Auditoría y el de la Etapa de Aclaración para su guarda y custodia en la Unidad de Seguimiento del Órgano Superior de Fiscalización del Estado de México, únicamente por lo que hace a las observaciones que han quedado totalmente aclaradas y Solventadas/Atendidas .";
                $accionSolventada07 = "QUINTO. Notifíquese por oficio a la entidad fiscalizada en el domicilio ubicado en {$remitente_domicilio}.";
                $accionSolventada08 = "";$accionSolventada09 = "";$accionSolventada10 = "";
            }
        $PAAnum = getSession('cp')+1;
            $ncT = User::select('segusers.name', 'segusers.puesto')->where('siglas_rol','TUS')->get();
            $nT = $ncT->pluck('name')->toArray();

        if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero'){
            if(count($auditoria->accionesrecomendaciones)>0){
                $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones correspondientes";
                $siRecomendaciones02 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones que le fueron formuladas, o en su caso, justificara su improcedencia";
                $siRecomendaciones03 = "No obstante, esta instancia de fiscalización vigilará y corroborará a través de las siguientes revisiones técnicas, que los compromisos y acciones en materia de la presente, se estén cumpliendo conforme a la responsabilidad que tiene encomendada {$nombreEntidad}.";
                $siRecomendaciones04 = "En ese sentido, con fundamento en lo dispuesto por los artículos 23 fracciones XIX y XLIV y; 47 fracciones III, XII, XV y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se determina que la recomendación ha quedado atendida.";            
                if(count($auditoria->totalNOsolventadorecomendacion)>0){
                    $recNoAt = collect($auditoria->totalNOsolventadorecomendacion)->pluck('numero')->filter()->implode(', ');
                    $siRecomendaciones05 = "DE LAS RECOMENDACIONES NO ATENDIDAS ";
                    $siRecomendaciones06 = "Por cuanto hace a las Recomendaciones identificadas con las claves de acción: ${recNoAt} , se determinó que las mismas no fueron atendidas ; por lo que con fundamento en los artículos 8 fracción XXVII de la Ley de Fiscalización Superior del Estado de México y 23 fracciones XIX y XLIV y 47 fracción XV segundo párrafo y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; los resultados obtenidos del acto de fiscalización de mérito, así como el soporte documental, se enviarán al Órgano Interno de Control de ${nombreEntidad} o su equivalente, para el efecto de que dicha autoridad de control interno {$auditoria->entidadFiscalizable->Ambito} promueva las acciones procedentes que garanticen su atención y cumplimiento, por lo cual, dichas recomendaciones se integrarán  en un expedientillo para el seguimiento correspondiente.";
                }
                $siRecomendaciones07 = "y 54 Bis";
            }
            if(count($auditoria->accionespras)>0){
                $siPRAS01 ="Adicional a lo anterior y en términos de lo previsto en los artículos 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México; 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y;
                             23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, por medio del diverso número". $pras->numero_oficio .", se turnaron por cuerda separada al Órgano Interno de Control competente, 
                             las Promociones de Responsabilidad Administrativa Sancionatoria, para que se continúe con la investigación pertinente y promueva las acciones procedentes, ordenándose formar expedientillo relativo a las Promociones de Responsabilidad Administrativa Sancionatoria.";
                if($pras->estatus_cumplimiento == 'No Atendido'){
                    $siPRAS02= "Así pues, agotado el plazo para la atención de las observaciones a que se alude en el segundo párrafo del presente apartado, sin que a la fecha de emisión del presente se tenga evidencia documental ingresada por parte de la entidad fiscalizada, se llegó a la conclusión de los siguientes: ===================================";
                }
            }
            if(count($auditoria->accionespo)>0){
                if(count($auditoria->totalNOsolventadopliegos)>0){
                    $CantpoNoSol = count($auditoria->totalNOsolventadopliegos);
                    $CantpoLetras= $formatter->toString($CantpoNoSol);
                    $siPliegos01 = "DE LOS PLIEGOS DE OBSERVACIONES QUE NO FUERON SOLVENTADOS Y EL MONTO NO ACLARADO";
                    $siPliegos02 = "Derivado de lo descrito en el numeral I del apartado que nos antecede de los Resultados Finales del Seguimiento a la Etapa de Aclaración, se determinaron {$CantpoNoSol} (${CantpoLetras}) Pliegos de Observaciones no aclarados ni solventados, mismos que ascienden a la cantidad total de" .count($auditoria->totalpliegos).".";
                    $siPliegos03 = "En ese orden de ideas el Expediente Técnico de Auditoría y el de la Etapa de Aclaración, se remitirán a la Unidad de Investigación del Órgano Superior de Fiscalización del Estado de México, para que se inicie el procedimiento de investigación correspondiente, en términos de la Ley General de Responsabilidades Administrativas, la Ley de Responsabilidades Administrativas del Estado de México y Municipios y demás disposiciones jurídicas aplicables.";
                }
                
                if (collect($NumPoPromPRAS)->filter()->isNotEmpty()) {/**isNotEmpty se usa para saber si la coleccion no esta vacia */ 
                    $siPliegos04 = " DE LOS PLIEGOS DE OBSERVACIONES FORMULADOS A PROMOCIONES DE RESPONSABILIDAD ADMINISTRATIVA SANCIONATORIA";
                    $siPliegos05 = "Por cuanto hace a los Pliegos de Observaciones identificados con clave de acción: ${NumPoPromPRAS}, se determinó que los mismos no fueron aclarados ni solventados, sin embargo, tomando en consideración que la esencia de la observación se vincula con posibles irregularidades de responsabilidad administrativa no graves; con fundamento en los artículos 13 fracción IX, 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México en relación con los diversos 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y; 23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se formula la Promoción de Responsabilidad Administrativa Sancionatoria identificada con clave de acción: XXX, turnándose al Órgano Interno de Control de ${nombreEntidad} o su equivalente, para que realice las investigaciones pertinentes y en su caso, inicie el procedimiento administrativo correspondiente.";
                }
                if(collect($NumPoPromRecomendaciones)->filter()->isNotEmpty()){
                    $siPliegos06 = "DE LOS PLIEGOS DE OBSERVACIONES PROMOVIDOS A RECOMENDACIONES";
                    $siPliegos07 = "En términos de lo previsto en los artículos 53 fracción II, 54 y 54 Bis de la Ley de Fiscalización Superior del Estado de México, los Pliegos de Observaciones que derivaron en recomendaciones y que se identifican con número {$NumPoPromRecomendaciones} vinculadas con la presente auditoría, serán integradas en un expedientillo para el seguimiento correspondiente. ";
                    $siPliegos08 = "Cabe señalar que la información y documentación que se exhiba, deberá presentarse certificada en medio impreso y digital.";
                }
            }
            if(count($auditoria->accionessolacl)>0){
                if(collect($NumSolacPromPo)->filter()->isNotEmpty()){
                    $CantSolacPromPo = count(collect($NumSolacPromPo));
                    $LetrasSolacpromPo= $formatter->toString($CantSolacPromPo);
                    $siSolAc01 = "DE LAS SOLICITUDES DE ACLARACIÓN FORMULADAS A PLIEGOS DE OBSERVACIONES";
                    $siSolAc02 = "Ahora bien, en relación a lo descrito en el numeral I del apartado denominado “Resultados Finales” del Seguimiento a la Etapa de Aclaración, se determinaron {$CantSolacPromPo} ({$LetrasSolacpromPo}) Solicitudes de Aclaración que no fueron aclaradas ni solventadas y que se promovieron a Pliegos de Observaciones identificados con número XXX y XXX, que ascienden a la cantidad total de XXXX.";
                    $siSolAc03 = "Por lo anterior, con fundamento en el artículo 54 fracciones I y III de la citada Ley de Fiscalización, se concede un plazo de 30 (Treinta) días hábiles contados a partir del día hábil siguiente al en que surta efectos la notificación del presente, para que esa entidad fiscalizada presente los elementos, documentos y datos fehacientes que aclaren, solventen o en su caso, atiendan, el contenido de las observaciones pendientes por agotar en la Etapa de Aclaración descritas en el apartado que antecede identificado con el numeral I, o en su caso, se manifieste lo que a su derecho convenga.";
                    $siSolAc04 = "En ese sentido, para el caso de no dar cumplimiento al requerimiento descrito en el párrafo que antecede, los citados Pliegos de Observaciones se tendrán por no aclarados ni solventados y por consiguiente, se dará vista a la autoridad competente para los efectos a que haya lugar.";
                    $siSolAc05 = "Cabe señalar que la información y documentación que exhiba la entidad fiscalizada con relación a las observaciones pendientes por agotar la Etapa de Aclaración, deberá presentarse debidamente certificada en medio impreso y digital.";
                }
                if(collect($NumSolacPromRecomendaciones)->filter()->isNotEmpty()){
                    $siSolAc06 = "DE LAS SOLICITUDES DE ACLARACIÓN PROMOVIDAS A RECOMENDACIONES";
                    $siSolAc07 = "En términos de lo previsto en los artículos 53 fracción II, 54 y 54 Bis de la Ley de Fiscalización Superior del Estado de México, las Solicitudes de Aclaración que derivaron en recomendaciones identificadas con número ${NumSolacPromRecomendaciones}  y que se encuentran vinculadas con la presente auditoría, serán integradas  en un expedientillo para el seguimiento correspondiente.";
                    $siSolAc08 = "Cabe señalar que la información y documentación que exhiba la entidad fiscalizada con relación a las recomendaciones en comento, deberá presentarse debidamente certificada en medio impreso y digital.";
                }
                if(collect($NumSolacPromPRAS)->filter()->isNotEmpty()){
                    $siSolAc09 = "DE LAS SOLICITUDES DE ACLARACIÓN PROMOVIDAS A PROMOCIONES DE RESPONSABILIDAD ADMINISTRATIVA SANCIONATORIA ";
                    $siSolAc010 = "Por cuanto hace a la Solicitud de Aclaración identificada con clave de acción: ${NumSolacPromPRAS} , se determinó que no fue aclarada ni solventada, sin embargo tomando en consideración que la esencia de la observación  se vincula con posibles irregularidades de responsabilidad administrativa no graves; con fundamento en los artículos 13 fracción IX, 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México en relación con los diversos 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y; 23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se formula la Promoción de Responsabilidad Administrativa Sancionatoria identificada con clave de acción: XXX ;  turnándose al Órgano Interno de Control de ${nombreEntidad} o su equivalente, para que realice las investigaciones pertinentes y en su caso, inicie el procedimiento administrativo correspondiente.";
                }
            }

            $template=new TemplateProcessor('bases-word/IS/CUMPLIMIENTO_FINANCIERO/IS_01.docx');   
            $template->setValue('direccion_asig',$auditoria->direccion_asignada);
            $template->setValue('departamento_asig',$auditoria->departamento_encargado);
            $template->setValue('entidad01',$nombreEntidad);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('PAAnum',getSession('cp')+1);
            $template->setValue('fechaPAA',$fecha_PAA);
            $template->setValue('numero_orden',$auditoria->numero_orden);
            $template->setValue('fecha_orden',$fecha_orden);
            $template->setValue('entidad',$textoDocumento);

            $template->setValue('fecha_oficio_acuerdo',$fecha_oficio_acuerdo);
            $template->setValue('oficio_numero', $auditoria->radicacion->oficio_acuerdo);
            $template->setValue('siRecomendaciones01', $siRecomendaciones01);
            $template->setValue('siRecomendaciones02', $siRecomendaciones02);
            trim($template->setValue('SiPRAS01', $siPRAS01));
            trim($template->setValue('SiPRAS02', $siPRAS02));

            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('siRecomendaciones03', $siRecomendaciones03);
            $template->setValue('siRecomendaciones04', $siRecomendaciones04);
            $template->cloneBlock('block_solacpo', count($auditoria->accionessolaclpo), true, false, $accionesSolAcPo);
            $template->cloneBlock('block_recomendaciones', count($auditoria->accionesrecomendaciones), true, false, $accionesRecomendaciones);
            $template->setValue('siPliegos01',$siPliegos01);
            $template->setValue('siPliegos02',$siPliegos02);
            $template->setValue('siPliegos03',$siPliegos03);
            $template->setValue('entidad01',$nombreEntidad);
            $template->setValue('siPliegos04',$siPliegos04);
            $template->setValue('siPliegos05',$siPliegos05);
            $template->setValue('siPliegos06',$siPliegos06);
            $template->setValue('siPliegos07',$siPliegos07);
            $template->setValue('siPliegos08',$siPliegos08);
            $template->setValue('siSolAc01',$siSolAc01);
            $template->setValue('siSolAc02',$siSolAc02);
            $template->setValue('siSolAc03',$siSolAc03);
            $template->setValue('siSolAc04',$siSolAc04);
            $template->setValue('siSolAc05',$siSolAc05);
            $template->setValue('siSolAc06',$siSolAc06);
            $template->setValue('siSolAc07',$siSolAc07);
            $template->setValue('siSolAc08',$siSolAc08);
            $template->setValue('siSolAc09',$siSolAc09);
            $template->setValue('siSolAc10',$siSolAc10);
            $template->setValue('siRecomendaciones05', $siRecomendaciones05);
            $template->setValue('siRecomendaciones06', $siRecomendaciones06);
            $template->setValue('accionSolventada01', $accionSolventada01);
            $template->setValue('accionSolventada02', $accionSolventada02);
            $template->setValue('accionSolventada03', $accionSolventada03);
            $template->setValue('accionSolventada04', $accionSolventada04);
            $template->setValue('accionSolventada05', $accionSolventada05);
            $template->setValue('accionSolventada06', $accionSolventada06);
            $template->setValue('accionSolventada07', $accionSolventada07);
            $template->setValue('accionSolventada08', $accionSolventada08);
            $template->setValue('accionSolventada09', $accionSolventada09);
            $template->setValue('accionSolventada10', $accionSolventada10);
            $template->setValue('fechaInformeLetras', $fechaactual);
            $template->setValue('nT', $nT[0]);
            $template->setValue('nD', $auditoria->directorasignado->name);
            $template->setValue('cD',$auditoria->directorasignado->puesto);
            $template->setValue('nJD', $auditoria->jefedepartamentoencargado->name);
            $template->setValue('cJD',  'Jefe de '.$auditoria->departamento_encargado);
            $template->setValue('nLP', $auditoria->lidercp->name);
            $template->setValue('nA', $auditoria->analistacp->name);

            $nombreword='IS';
            $template->saveAs($nombreword.'.docx');
        }    

        //DESEMPEÑO

        if($auditoria->acto_fiscalizacion=='Desempeño'){
            if(count($auditoria->accionesrecomendaciones)>0){
                $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones correspondientes";
                $siRecomendaciones02 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones que le fueron formuladas, o en su caso, justificara su improcedencia";
                $siRecomendaciones03 = "No obstante, esta instancia de fiscalización vigilará y corroborará a través de las siguientes revisiones técnicas, que los compromisos y acciones en materia de la presente, se estén cumpliendo conforme a la responsabilidad que tiene encomendada {$nombreEntidad}.";
                $siRecomendaciones04 = "En ese sentido, con fundamento en lo dispuesto por los artículos 23 fracciones XIX y XLIV y; 47 fracciones III, XII, XV y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se determina que la recomendación ha quedado atendida.";            
                if(count($auditoria->totalNOsolventadorecomendacion)>0){
                    $recNoAt = collect($auditoria->totalNOsolventadorecomendacion)->pluck('numero')->filter()->implode(', ');
                    $siRecomendaciones05 = "DE LAS RECOMENDACIONES NO ATENDIDAS ";
                    $siRecomendaciones06 = "Por cuanto hace a las Recomendaciones identificadas con las claves de acción: ${recNoAt} , se determinó que las mismas no fueron atendidas ; por lo que con fundamento en los artículos 8 fracción XXVII de la Ley de Fiscalización Superior del Estado de México y 23 fracciones XIX y XLIV y 47 fracción XV segundo párrafo y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; los resultados obtenidos del acto de fiscalización de mérito, así como el soporte documental, se enviarán al Órgano Interno de Control de ${nombreEntidad} o su equivalente, para el efecto de que dicha autoridad de control interno {$auditoria->entidadFiscalizable->Ambito} promueva las acciones procedentes que garanticen su atención y cumplimiento, por lo cual, dichas recomendaciones se integrarán  en un expedientillo para el seguimiento correspondiente.";
                    $siRecomendaciones08 = "Con referencia al cumplimiento del requerimiento para la atención de las recomendaciones identificadas con clave número: ${recNoAt}, que no fueron atendidas por parte de la entidad fiscalizada; se remitirán a la Unidad de Asuntos Jurídicos del Órgano Superior de Fiscalización del Estado de México, para que aplique el medio de apremio que corresponda, en términos de la Ley General de Responsabilidades Administrativas, la Ley de Responsabilidades Administrativas del Estado de México y Municipios, la Ley de Fiscalización Superior del Estado de México y demás disposiciones jurídicas aplicables.";

                }
                $siRecomendaciones07 = "y 54 Bis";
            }
            //Si contestación extemporanea

            if(count($auditoria->accionespras)>0){
                $SiPRAS01 ="En ese tenor, po lo que hace al oficio número" . $pras->numero_oficio . "suscrito por XXX, en su cáracter de XXX, durante la administración pública XXX, durante la administración pública XXX, presentado en la fecha XXX ante el Departamentp de Oficilia de partes del Órgano Superior de Fiscalización del Estado de Mexico, con asignación de folio XXX, del sistema de Gestión de Correspondencia, se advierte que se presentó de manera extemporánea con el objeto de dar la atención a las observaciones en cuestión; por lo que mediante oficio número XXXXX, el día XXXX, se hizo del conocimiento a la entidad fiscalizada, que el mismo no será considerado para su valoración.";
                if($pras->estatus_cumplimiento == 'No Atendido'){
                    $SiPRAS02= "Así pues, una vez agotado el plazo convenido correspondiente al Proceso de Atención a las Recomendaciones descrito en el segundo párrafo del presente apartado, sin que a la fecha de emisión del presente se tenga evidencia documental ingresada por parte de la entidad fiscalizada, se llegó a la conclusión de los siguientes: ===================================";
                  }
            }
            if(count($auditoria->accionespo)>0){
                if(count($auditoria->totalNOsolventadopliegos)>0){
                    $CantpoNoSol = count($auditoria->totalNOsolventadopliegos);
                    $CantpoLetras= $formatter->toString($CantpoNoSol);
                    $siPliegos01 = "DE LOS PLIEGOS DE OBSERVACIONES QUE NO FUERON SOLVENTADOS Y EL MONTO NO ACLARADO";
                    $siPliegos02 = "Derivado de lo descrito en el numeral I del apartado que nos antecede de los Resultados Finales del Seguimiento a la Etapa de Aclaración, se determinaron {$CantpoNoSol} (${CantpoLetras}) Pliegos de Observaciones no aclarados ni solventados, mismos que ascienden a la cantidad total de" .count($auditoria->totalpliegos).".";
                    $siPliegos03 = "En ese orden de ideas el Expediente Técnico de Auditoría y el de la Etapa de Aclaración, se remitirán a la Unidad de Investigación del Órgano Superior de Fiscalización del Estado de México, para que se inicie el procedimiento de investigación correspondiente, en términos de la Ley General de Responsabilidades Administrativas, la Ley de Responsabilidades Administrativas del Estado de México y Municipios y demás disposiciones jurídicas aplicables.";
                }
                
                if (collect($NumPoPromPRAS)->filter()->isNotEmpty()) {/**isNotEmpty se usa para saber si la coleccion no esta vacia */ 
                    $siPliegos04 = " DE LOS PLIEGOS DE OBSERVACIONES FORMULADOS A PROMOCIONES DE RESPONSABILIDAD ADMINISTRATIVA SANCIONATORIA";
                    $siPliegos05 = "Por cuanto hace a los Pliegos de Observaciones identificados con clave de acción: ${NumPoPromPRAS}, se determinó que los mismos no fueron aclarados ni solventados, sin embargo, tomando en consideración que la esencia de la observación se vincula con posibles irregularidades de responsabilidad administrativa no graves; con fundamento en los artículos 13 fracción IX, 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México en relación con los diversos 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y; 23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se formula la Promoción de Responsabilidad Administrativa Sancionatoria identificada con clave de acción: XXX, turnándose al Órgano Interno de Control de ${nombreEntidad} o su equivalente, para que realice las investigaciones pertinentes y en su caso, inicie el procedimiento administrativo correspondiente.";
                }
                if(collect($NumPoPromRecomendaciones)->filter()->isNotEmpty()){
                    $siPliegos06 = "DE LOS PLIEGOS DE OBSERVACIONES PROMOVIDOS A RECOMENDACIONES";
                    $siPliegos07 = "En términos de lo previsto en los artículos 53 fracción II, 54 y 54 Bis de la Ley de Fiscalización Superior del Estado de México, los Pliegos de Observaciones que derivaron en recomendaciones y que se identifican con número {$NumPoPromRecomendaciones} vinculadas con la presente auditoría, serán integradas en un expedientillo para el seguimiento correspondiente. ";
                    $siPliegos08 = "Cabe señalar que la información y documentación que se exhiba, deberá presentarse certificada en medio impreso y digital.";
                }
            }
            if(count($auditoria->accionessolacl)>0){
                if(collect($NumSolacPromPo)->filter()->isNotEmpty()){
                    $CantSolacPromPo = count(collect($NumSolacPromPo));
                    $LetrasSolacpromPo= $formatter->toString($CantSolacPromPo);
                    $siSolAc01 = "DE LAS SOLICITUDES DE ACLARACIÓN FORMULADAS A PLIEGOS DE OBSERVACIONES";
                    $siSolAc02 = "Ahora bien, en relación a lo descrito en el numeral I del apartado denominado “Resultados Finales” del Seguimiento a la Etapa de Aclaración, se determinaron {$CantSolacPromPo} ({$LetrasSolacpromPo}) Solicitudes de Aclaración que no fueron aclaradas ni solventadas y que se promovieron a Pliegos de Observaciones identificados con número XXX y XXX, que ascienden a la cantidad total de XXXX.";
                    $siSolAc03 = "Por lo anterior, con fundamento en el artículo 54 fracciones I y III de la citada Ley de Fiscalización, se concede un plazo de 30 (Treinta) días hábiles contados a partir del día hábil siguiente al en que surta efectos la notificación del presente, para que esa entidad fiscalizada presente los elementos, documentos y datos fehacientes que aclaren, solventen o en su caso, atiendan, el contenido de las observaciones pendientes por agotar en la Etapa de Aclaración descritas en el apartado que antecede identificado con el numeral I, o en su caso, se manifieste lo que a su derecho convenga.";
                    $siSolAc04 = "En ese sentido, para el caso de no dar cumplimiento al requerimiento descrito en el párrafo que antecede, los citados Pliegos de Observaciones se tendrán por no aclarados ni solventados y por consiguiente, se dará vista a la autoridad competente para los efectos a que haya lugar.";
                    $siSolAc05 = "Cabe señalar que la información y documentación que exhiba la entidad fiscalizada con relación a las observaciones pendientes por agotar la Etapa de Aclaración, deberá presentarse debidamente certificada en medio impreso y digital.";
                }
                if(collect($NumSolacPromRecomendaciones)->filter()->isNotEmpty()){
                    $siSolAc06 = "DE LAS SOLICITUDES DE ACLARACIÓN PROMOVIDAS A RECOMENDACIONES";
                    $siSolAc07 = "En términos de lo previsto en los artículos 53 fracción II, 54 y 54 Bis de la Ley de Fiscalización Superior del Estado de México, las Solicitudes de Aclaración que derivaron en recomendaciones identificadas con número ${NumSolacPromRecomendaciones}  y que se encuentran vinculadas con la presente auditoría, serán integradas  en un expedientillo para el seguimiento correspondiente.";
                    $siSolAc08 = "Cabe señalar que la información y documentación que exhiba la entidad fiscalizada con relación a las recomendaciones en comento, deberá presentarse debidamente certificada en medio impreso y digital.";
                }
                if(collect($NumSolacPromPRAS)->filter()->isNotEmpty()){
                    $siSolAc09 = "DE LAS SOLICITUDES DE ACLARACIÓN PROMOVIDAS A PROMOCIONES DE RESPONSABILIDAD ADMINISTRATIVA SANCIONATORIA ";
                    $siSolAc010 = "Por cuanto hace a la Solicitud de Aclaración identificada con clave de acción: ${NumSolacPromPRAS} , se determinó que no fue aclarada ni solventada, sin embargo tomando en consideración que la esencia de la observación  se vincula con posibles irregularidades de responsabilidad administrativa no graves; con fundamento en los artículos 13 fracción IX, 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México en relación con los diversos 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y; 23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se formula la Promoción de Responsabilidad Administrativa Sancionatoria identificada con clave de acción: XXX ;  turnándose al Órgano Interno de Control de ${nombreEntidad} o su equivalente, para que realice las investigaciones pertinentes y en su caso, inicie el procedimiento administrativo correspondiente.";
                }
            }
            $ncT = User::select('segusers.name', 'segusers.puesto')->where('siglas_rol','TUS')->get();
            $nT = $ncT->pluck('name')->toArray();

            $replacements = array(
                array('entidad' => "{$nombreEntidad}", 'periodo'=> "{$auditoria->periodo_revision}", 'PAAnum' => "{$PAAnum}", 'numero_orden' => "{$auditoria->numero_orden}", 'entidad01' => "{$textoDocumento}", 'fecha_oficio_acuerdo' => "{$fecha_oficio_acuerdo}", 'oficio_numero' => "{$auditoria->oficio_numero}", 'siPliegos01' => "{$siPliegos01}", 'siRecomendaciones01' => "{$siRecomendaciones01}", 'siPliegos02' => "{$siPliegos02}", 'siRecomendaciones02' => "{$siRecomendaciones02}", 'SiPRAS01' => "{$siPRAS01}", 'siRecomendaciones03' => "{$siRecomendaciones03}", 'siRecomendaciones04' => "{$siRecomendaciones04}",'plazo01'=> "{$plazo01}",'plazomaxMin'=> "{$plazomaxMin}", 'acta_cierre_auditoria' => "{$auditoria->radicacion->acta_cierre_auditoria}" ),

                );
            $replacements02 = array(
                array('siPliegos03' => "{$siPliegos03}", 'siPliegos04'=> "{$siPliegos04}", 'siPliegos05'=> "{$siPliegos05}", 'siPliegos06'=> "{$siPliegos06}", 'siPliegos07'=> "{$siPliegos07}",'siPliegos08'=> "{$siPliegos08}",'siPliegos09'=> "{$siPliegos09}",'siPliegos10'=> "{$siPliegos10}", 'siRecomendaciones05' => "{$siRecomendaciones05}", 'siRecomendaciones06' => "{$siRecomendaciones06}", 'siRecomendaciones08' => "{$siRecomendaciones08}", 'accionSolventada01' => "{$accionSolventada01}", 'accionSolventada02' => "{$accionSolventada02}", 'accionSolventada03' => "{$accionSolventada03}", 'accionSolventada04' => "{$accionSolventada04}", 'accionSolventada05' => "{$accionSolventada05}", 'accionSolventada06' => "{$accionSolventada06}", 'accionSolventada07' => "{$accionSolventada07}", 'accionSolventada08' => "{$accionSolventada08}", 'accionSolventada09' => "{$accionSolventada09}", 'accionSolventada10' => "{$accionSolventada10}", 'fechaInformeLetras' => "{$fechaactual}"),

                );
           //dd($replacements02);
            

            $template=new TemplateProcessor('bases-word\IS\DESEMPEÑO/IS_PAR_01.docx');  

            $template->cloneBlock('block', 1, true, false, $replacements);
            $template->cloneBlock('block_solacpo', count($auditoria->accionessolaclpo), true, false, $accionesSolAcPo);
            $template->cloneBlock('block_recomendaciones', count($auditoria->accionesrecomendaciones), true, false, $accionesRecomendaciones);
            $template->setValue('siRecomendaciones03', $siRecomendaciones03);
            $template->setValue('siRecomendaciones04', $siRecomendaciones04);
            
            $template->cloneBlock('block_analisis', 1, true, false, $replacements02);

            $template->setValue('nT', $nT[0]);


            $template->setValue('nD', $auditoria->directorasignado->name);
            $template->setValue('cD',$auditoria->directorasignado->puesto);
            $template->setValue('nJD', $auditoria->jefedepartamentoencargado->name);
            $template->setValue('cJD',  'Jefe de '.$auditoria->departamento_encargado);
            $template->setValue('nLP', $auditoria->lidercp->name);
            $template->setValue('nA', $auditoria->analistacp->name);

            $nombreword='IS';
            $template->saveAs($nombreword.'.docx');
        }                
        
        /**LEGALIDAD */
        if($auditoria->acto_fiscalizacion=='Legalidad'){
            if(count($auditoria->accionespo)>0){
                 $siPliegos01 = "de la Etapa de Aclaración";
                 $siPliegos02 = "en un plazo de 30 (Treinta) días hábiles, solventara, aclarara o manifestara lo que a su derecho conviniera en relación al contenido de los Pliegos de Observaciones aludidos; ";
                 if(count($auditoria->totalNOsolventadopliegos)>0){
                    $CantpoNoSol = count($auditoria->totalNOsolventadopliegos);
                    $CantpoLetras= $formatter->toString($CantpoNoSol);
                    $siPliegos03 = "DE LOS PLIEGOS DE OBSERVACIONES QUE NO FUERON SOLVENTADOS Y EL MONTO NO ACLARADO";
                    $siPliegos04 = "Derivado de lo descrito en el numeral I del apartado que nos antecede de los Resultados Finales del Seguimiento a la Etapa de Aclaración, se determinaron {$CantpoNoSol} (${CantpoLetras}) Pliegos de Observaciones no aclarados ni solventados, mismos que ascienden a la cantidad total de " .count($auditoria->totalpliegos).".";
                    $siPliegos05 = "En ese orden de ideas el Expediente Técnico de Auditoría y el de la Etapa de Aclaración, se remitirán a la Unidad de Investigación del Órgano Superior de Fiscalización del Estado de México, para que se inicie el procedimiento de investigación correspondiente, en términos de la Ley General de Responsabilidades Administrativas, la Ley de Responsabilidades Administrativas del Estado de México y Municipios y demás disposiciones jurídicas aplicables.";
                }
                
                if (collect($NumPoPromPRAS)->filter()->isNotEmpty()) {/**isNotEmpty se usa para saber si la coleccion no esta vacia */ 
                    $siPliegos06 = " DE LOS PLIEGOS DE OBSERVACIONES FORMULADOS A PROMOCIONES DE RESPONSABILIDAD ADMINISTRATIVA SANCIONATORIA";
                    $siPliegos07 = "Por cuanto hace a los Pliegos de Observaciones identificados con clave de acción: ${NumPoPromPRAS}, se determinó que los mismos no fueron aclarados ni solventados, sin embargo, tomando en consideración que la esencia de la observación se vincula con posibles irregularidades de responsabilidad administrativa no graves; con fundamento en los artículos 13 fracción IX, 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México en relación con los diversos 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y; 23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se formula la Promoción de Responsabilidad Administrativa Sancionatoria identificada con clave de acción: XXX, turnándose al Órgano Interno de Control de ${nombreEntidad} o su equivalente, para que realice las investigaciones pertinentes y en su caso, inicie el procedimiento administrativo correspondiente.";
                }
                if(collect($NumPoPromRecomendaciones)->filter()->isNotEmpty()){
                    $siPliegos08 = "DE LOS PLIEGOS DE OBSERVACIONES PROMOVIDOS A RECOMENDACIONES";
                    $siPliegos09 = "En términos de lo previsto en los artículos 53 fracción II, 54 y 54 Bis de la Ley de Fiscalización Superior del Estado de México, los Pliegos de Observaciones que derivaron en recomendaciones y que se identifican con número {$NumPoPromRecomendaciones} vinculadas con la presente auditoría, serán integradas en un expedientillo para el seguimiento correspondiente. ";
                    $siPliegos10 = "Cabe señalar que la información y documentación que se exhiba, deberá presentarse certificada en medio impreso y digital.";
                }
            }
            if(count($auditoria->accionespras)>0){
                $siPRAS01 ="Adicional a lo anterior y en términos de lo previsto en los artículos 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México; 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y;
                             23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, por medio del diverso número". $pras->numero_oficio .", se turnaron por cuerda separada al Órgano Interno de Control competente, 
                             las Promociones de Responsabilidad Administrativa Sancionatoria, para que se continúe con la investigación pertinente y promueva las acciones procedentes, ordenándose formar expedientillo relativo a las Promociones de Responsabilidad Administrativa Sancionatoria.";
                if($pras->estatus_cumplimiento == 'No Atendido'){
                    $siPRAS02= "Así pues, agotado el plazo para la atención de las observaciones a que se alude en el segundo párrafo del presente apartado, sin que a la fecha de emisión del presente se tenga evidencia documental ingresada por parte de la entidad fiscalizada, se llegó a la conclusión de los siguientes: ===================================";
                }
            }
            if(count($auditoria->accionesrecomendaciones)>0){
                $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones correspondientes";
                $siRecomendaciones02 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones , o en su caso, justificara su improcedencia dentro del plazo de ". $auditoria->radicacion->plazo_maximo. " (".$plazomaxMin.") días hábiles, plazo que fue convenido con el Órgano Superior de Fiscalización del Estado de México, detallado en el Acta de Reunión de Resultados Finales y Cierre de Auditoría ". $auditoria->radicacion->acta_cierre_auditoria.", con el apercibimiento de que para el caso de no dar cumplimento a dicho requerimiento, se estaría a lo dispuesto en el artículo 59 fracción II de la Ley de Fiscalización Superior del Estado de México, ello, con independencia de las sanciones administrativas y penales que, en términos de las leyes en dichas materias, resultaren aplicables .";
                $siRecomendaciones03 = "No obstante, esta instancia de fiscalización vigilará y corroborará a través de las siguientes revisiones técnicas, que los compromisos y acciones en materia de la presente, se estén cumpliendo conforme a la responsabilidad que tiene encomendada {$nombreEntidad}.";
                $siRecomendaciones04 = "En ese sentido, con fundamento en lo dispuesto por los artículos 23 fracciones XIX y XLIV y; 47 fracciones III, XII, XV y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se determina que la recomendación ha quedado atendida.";                  
                $rec01 = "ESTADO QUE GUARDAN LAS RECOMENDACIONES  DE CUENTA:"; 
                $rec02 = "Con relación al Proceso de Atención a las Recomendaciones , una vez agotados los plazos convenidos con el Órgano Superior de Fiscalización del Estado de México señalados en el segundo párrafo del apartado de ANTECEDENTES del presente Informe de Seguimiento, se informará por cuerda separada a esa entidad fiscalizada. ";
                if(count($auditoria->totalNOsolventadorecomendacion)>0){
                    //$siRecomendaciones03 = "Por tanto, se tiene como no atendida para este Órgano Superior de Fiscalización del Estado de México, la Recomendación con clave de acción XXX; en consecuencia, con fundamento en los artículos 8 fracción XXVII de la Ley de Fiscalización Superior del Estado de México y 23 fracciones XIX y XLIV y 47 fracción XV segundo párrafo y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; la Recomendación será turnada al Órgano Interno de Control de XXX o su equivalente, para el efecto de que dicha autoridad de control interno XXX promueva las acciones procedentes que garanticen su atención y cumplimiento."; 
                    $recNoAt = collect($auditoria->totalNOsolventadorecomendacion)->pluck('numero')->filter()->implode(', ');
                    $siRecomendaciones05 = "DE LAS RECOMENDACIONES NO ATENDIDAS ";
                    $siRecomendaciones06 = "Por cuanto hace a las Recomendaciones identificadas con las claves de acción: ${recNoAt} , se determinó que las mismas no fueron atendidas ; por lo que con fundamento en los artículos 8 fracción XXVII de la Ley de Fiscalización Superior del Estado de México y 23 fracciones XIX y XLIV y 47 fracción XV segundo párrafo y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; los resultados obtenidos del acto de fiscalización de mérito, así como el soporte documental, se enviarán al Órgano Interno de Control de ${nombreEntidad} o su equivalente, para el efecto de que dicha autoridad de control interno {$auditoria->entidadFiscalizable->Ambito} promueva las acciones procedentes que garanticen su atención y cumplimiento, por lo cual, dichas recomendaciones se integrarán  en un expedientillo para el seguimiento correspondiente.";
                    $siRecomendaciones08 = "Con referencia al cumplimiento del requerimiento para la atención de las recomendaciones identificadas con clave número: ${recNoAt}, que no fueron atendidas por parte de la entidad fiscalizada; se remitirán a la Unidad de Asuntos Jurídicos del Órgano Superior de Fiscalización del Estado de México, para que aplique el medio de apremio que corresponda, en términos de la Ley General de Responsabilidades Administrativas, la Ley de Responsabilidades Administrativas del Estado de México y Municipios, la Ley de Fiscalización Superior del Estado de México y demás disposiciones jurídicas aplicables.";
                }
                $siRecomendaciones07 = "y 54 Bis";
            }
            
            $replacements = array(
                array('entidad' => "{$nombreEntidad}", 'periodo'=> "{$auditoria->periodo_revision}", 'PAAnum' => "{$PAAnum}", 'numero_orden' => "{$auditoria->numero_orden}", 'entidad01' => "{$textoDocumento}", 'fecha_oficio_acuerdo' => "{$fecha_oficio_acuerdo}", 'oficio_numero' => "{$auditoria->oficio_numero}", 'siPliegos01' => "{$siPliegos01}", 'siRecomendaciones01' => "{$siRecomendaciones01}", 'siPliegos02' => "{$siPliegos02}", 'siRecomendaciones02' => "{$siRecomendaciones02}", 'SiPRAS01' => "{$siPRAS01}", 'siRecomendaciones03' => "{$siRecomendaciones03}", 'siRecomendaciones04' => "{$siRecomendaciones04}", ),

                );
            $replacements02 = array(
                array('siPliegos03' => "{$siPliegos03}", 'siPliegos04'=> "{$siPliegos04}", 'siPliegos05'=> "{$siPliegos05}", 'siPliegos06'=> "{$siPliegos06}", 'siPliegos07'=> "{$siPliegos07}",'siPliegos08'=> "{$siPliegos08}",'siPliegos09'=> "{$siPliegos09}",'siPliegos10'=> "{$siPliegos10}", 'siRecomendaciones05' => "{$siRecomendaciones05}", 'siRecomendaciones06' => "{$siRecomendaciones06}", 'siRecomendaciones08' => "{$siRecomendaciones08}", 'accionSolventada01' => "{$accionSolventada01}", 'accionSolventada02' => "{$accionSolventada02}", 'accionSolventada03' => "{$accionSolventada03}", 'accionSolventada04' => "{$accionSolventada04}", 'accionSolventada05' => "{$accionSolventada05}", 'accionSolventada06' => "{$accionSolventada06}", 'accionSolventada07' => "{$accionSolventada07}", 'accionSolventada08' => "{$accionSolventada08}", 'accionSolventada09' => "{$accionSolventada09}", 'accionSolventada10' => "{$accionSolventada10}", 'fechaInformeLetras' => "{$fechaactual}"),

                );

            if($tipo == 'IS_EA_PAR'){
                $template=new TemplateProcessor('bases-word/IS/LEGALIDAD/IS_EA_Y_PAR_01.docx');

                $template->cloneBlock('block', 1, true, false, $replacements);
                $template->cloneBlock('block_solacpo', count($auditoria->accionessolaclpo), true, false, $accionesSolAcPo);
                $template->cloneBlock('block_recomendaciones', count($auditoria->accionesrecomendaciones), true, false, $accionesRecomendaciones);
                $template->setValue('siRecomendaciones03', $siRecomendaciones03);
                $template->setValue('siRecomendaciones04', $siRecomendaciones04);
                $template->cloneBlock('blockAnalisis', 1, true, false, $replacements02);
                $template->setValue('nT', $nT[0]);
                $template->setValue('nD', $auditoria->directorasignado->name);
                $template->setValue('cD',$auditoria->directorasignado->puesto);
                $template->setValue('nJD', $auditoria->jefedepartamentoencargado->name);
                $template->setValue('cJD',  'Jefe de '.$auditoria->departamento_encargado);
                $template->setValue('nLP', $auditoria->lidercp->name);
                $template->setValue('nA', $auditoria->analistacp->name);

                $nombreword='IS. EA Y PAR';
                $template->saveAs($nombreword.'.docx');
            }
            if($tipo == 'IS_PAR'){
                $template=new TemplateProcessor('bases-word/IS/LEGALIDAD/IS_PAR_01.docx');

                $template->cloneBlock('block', 1, true, false, $replacements);
                $template->cloneBlock('block_solacpo', count($auditoria->accionessolaclpo), true, false, $accionesSolAcPo);
                $template->cloneBlock('block_recomendaciones', count($auditoria->accionesrecomendaciones), true, false, $accionesRecomendaciones);
                $template->setValue('siRecomendaciones03', $siRecomendaciones03);
                $template->setValue('siRecomendaciones04', $siRecomendaciones04);
                $template->cloneBlock('blockAnalisis', 1, true, false, $replacements02);
                $template->setValue('nT', $nT[0]);
                $template->setValue('nD', $auditoria->directorasignado->name);
                $template->setValue('cD',$auditoria->directorasignado->puesto);
                $template->setValue('nJD', $auditoria->jefedepartamentoencargado->name);
                $template->setValue('cJD',  'Jefe de '.$auditoria->departamento_encargado);
                $template->setValue('nLP', $auditoria->lidercp->name);
                $template->setValue('nA', $auditoria->analistacp->name);

                $nombreword='IS. PAR';
                $template->saveAs($nombreword.'.docx');
            }
            if($tipo == 'IS_EA'){

                $template=new TemplateProcessor('bases-word/IS/LEGALIDAD/IS_EA_01.docx');
                $template->cloneBlock('block', 1, true, false, $replacements);
                $template->cloneBlock('block_solacpo', count($auditoria->accionessolaclpo), true, false, $accionesSolAcPo);
                $template->setValue('siRecomendaciones03', $siRecomendaciones03);
                $template->setValue('siRecomendaciones04', $siRecomendaciones04);
                $template->cloneBlock('blockAnalisis', 1, true, false, $replacements02);
                $template->setValue('rec01', $rec01);
                $template->setValue('rec02', $rec02);
                $template->setValue('nT', $nT[0]);
                $template->setValue('nD', $auditoria->directorasignado->name);
                $template->setValue('cD',$auditoria->directorasignado->puesto);
                $template->setValue('nJD', $auditoria->jefedepartamentoencargado->name);
                $template->setValue('cJD',  'Jefe de '.$auditoria->departamento_encargado);
                $template->setValue('nLP', $auditoria->lidercp->name);
                $template->setValue('nA', $auditoria->analistacp->name);

                $nombreword='IS. EA';
                $template->saveAs($nombreword.'.docx');
            }
        }

        if($auditoria->acto_fiscalizacion == 'Inversión Física'){

            if(count($auditoria->accionespo)>0){
                 $siPliegos01 = "de la Etapa de Aclaración";
                 $siPliegos02 = "en un plazo de 30 (Treinta) días hábiles, solventara, aclarara o manifestara lo que a su derecho conviniera en relación al contenido de los Pliegos de Observaciones aludidos; ";
                 if(count($auditoria->totalNOsolventadopliegos)>0){
                    $CantpoNoSol = count($auditoria->totalNOsolventadopliegos);
                    $CantpoLetras= $formatter->toString($CantpoNoSol);
                    $siPliegos03 = "DE LOS PLIEGOS DE OBSERVACIONES QUE NO FUERON SOLVENTADOS Y EL MONTO NO ACLARADO";
                    $siPliegos04 = "Derivado de lo descrito en el numeral I del apartado que nos antecede de los Resultados Finales del Seguimiento a la Etapa de Aclaración, se determinaron {$CantpoNoSol} (${CantpoLetras}) Pliegos de Observaciones no aclarados ni solventados, mismos que ascienden a la cantidad total de " .count($auditoria->totalpliegos).".";
                    $siPliegos05 = "En ese orden de ideas el Expediente Técnico de Auditoría y el de la Etapa de Aclaración, se remitirán a la Unidad de Investigación del Órgano Superior de Fiscalización del Estado de México, para que se inicie el procedimiento de investigación correspondiente, en términos de la Ley General de Responsabilidades Administrativas, la Ley de Responsabilidades Administrativas del Estado de México y Municipios y demás disposiciones jurídicas aplicables.";
                }
                
                if (collect($NumPoPromPRAS)->filter()->isNotEmpty()) {/**isNotEmpty se usa para saber si la coleccion no esta vacia */ 
                    $siPliegos06 = " DE LOS PLIEGOS DE OBSERVACIONES FORMULADOS A PROMOCIONES DE RESPONSABILIDAD ADMINISTRATIVA SANCIONATORIA";
                    $siPliegos07 = "Por cuanto hace a los Pliegos de Observaciones identificados con clave de acción: ${NumPoPromPRAS}, se determinó que los mismos no fueron aclarados ni solventados, sin embargo, tomando en consideración que la esencia de la observación se vincula con posibles irregularidades de responsabilidad administrativa no graves; con fundamento en los artículos 13 fracción IX, 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México en relación con los diversos 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y; 23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se formula la Promoción de Responsabilidad Administrativa Sancionatoria identificada con clave de acción: XXX, turnándose al Órgano Interno de Control de ${nombreEntidad} o su equivalente, para que realice las investigaciones pertinentes y en su caso, inicie el procedimiento administrativo correspondiente.";
                }
                if(collect($NumPoPromRecomendaciones)->filter()->isNotEmpty()){
                    $siPliegos08 = "DE LOS PLIEGOS DE OBSERVACIONES PROMOVIDOS A RECOMENDACIONES";
                    $siPliegos09 = "En términos de lo previsto en los artículos 53 fracción II, 54 y 54 Bis de la Ley de Fiscalización Superior del Estado de México, los Pliegos de Observaciones que derivaron en recomendaciones y que se identifican con número {$NumPoPromRecomendaciones} vinculadas con la presente auditoría, serán integradas en un expedientillo para el seguimiento correspondiente. ";
                    $siPliegos10 = "Cabe señalar que la información y documentación que se exhiba, deberá presentarse certificada en medio impreso y digital.";
                }
            }
            if(count($auditoria->accionespras)>0){
                $siPRAS01 ="Adicional a lo anterior y en términos de lo previsto en los artículos 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México; 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y;
                             23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, por medio del diverso número". $pras->numero_oficio .", se turnaron por cuerda separada al Órgano Interno de Control competente, 
                             las Promociones de Responsabilidad Administrativa Sancionatoria, para que se continúe con la investigación pertinente y promueva las acciones procedentes, ordenándose formar expedientillo relativo a las Promociones de Responsabilidad Administrativa Sancionatoria.";
                if($pras->estatus_cumplimiento == 'No Atendido'){
                    $siPRAS02= "Así pues, agotado el plazo para la atención de las observaciones a que se alude en el segundo párrafo del presente apartado, sin que a la fecha de emisión del presente se tenga evidencia documental ingresada por parte de la entidad fiscalizada, se llegó a la conclusión de los siguientes: ===================================";
                }
            }
            if(count($auditoria->accionesrecomendaciones)>0){
                $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones correspondientes";
                $siRecomendaciones02 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones , o en su caso, justificara su improcedencia dentro del plazo de ". $auditoria->radicacion->plazo_maximo. " (".$plazomaxMin.") días hábiles, plazo que fue convenido con el Órgano Superior de Fiscalización del Estado de México, detallado en el Acta de Reunión de Resultados Finales y Cierre de Auditoría ". $auditoria->radicacion->acta_cierre_auditoria.", con el apercibimiento de que para el caso de no dar cumplimento a dicho requerimiento, se estaría a lo dispuesto en el artículo 59 fracción II de la Ley de Fiscalización Superior del Estado de México, ello, con independencia de las sanciones administrativas y penales que, en términos de las leyes en dichas materias, resultaren aplicables .";
                $siRecomendaciones03 = "No obstante, esta instancia de fiscalización vigilará y corroborará a través de las siguientes revisiones técnicas, que los compromisos y acciones en materia de la presente, se estén cumpliendo conforme a la responsabilidad que tiene encomendada {$nombreEntidad}.";
                $siRecomendaciones04 = "En ese sentido, con fundamento en lo dispuesto por los artículos 23 fracciones XIX y XLIV y; 47 fracciones III, XII, XV y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se determina que la recomendación ha quedado atendida.";                  
                $rec01 = "ESTADO QUE GUARDAN LAS RECOMENDACIONES  DE CUENTA:"; 
                $rec02 = "Con relación al Proceso de Atención a las Recomendaciones , una vez agotados los plazos convenidos con el Órgano Superior de Fiscalización del Estado de México señalados en el segundo párrafo del apartado de ANTECEDENTES del presente Informe de Seguimiento, se informará por cuerda separada a esa entidad fiscalizada. ";
                if(count($auditoria->totalNOsolventadorecomendacion)>0){
                    //$siRecomendaciones03 = "Por tanto, se tiene como no atendida para este Órgano Superior de Fiscalización del Estado de México, la Recomendación con clave de acción XXX; en consecuencia, con fundamento en los artículos 8 fracción XXVII de la Ley de Fiscalización Superior del Estado de México y 23 fracciones XIX y XLIV y 47 fracción XV segundo párrafo y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; la Recomendación será turnada al Órgano Interno de Control de XXX o su equivalente, para el efecto de que dicha autoridad de control interno XXX promueva las acciones procedentes que garanticen su atención y cumplimiento."; 
                    $recNoAt = collect($auditoria->totalNOsolventadorecomendacion)->pluck('numero')->filter()->implode(', ');
                    $siRecomendaciones05 = "DE LAS RECOMENDACIONES NO ATENDIDAS ";
                    $siRecomendaciones06 = "Por cuanto hace a las Recomendaciones identificadas con las claves de acción: ${recNoAt} , se determinó que las mismas no fueron atendidas ; por lo que con fundamento en los artículos 8 fracción XXVII de la Ley de Fiscalización Superior del Estado de México y 23 fracciones XIX y XLIV y 47 fracción XV segundo párrafo y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; los resultados obtenidos del acto de fiscalización de mérito, así como el soporte documental, se enviarán al Órgano Interno de Control de ${nombreEntidad} o su equivalente, para el efecto de que dicha autoridad de control interno {$auditoria->entidadFiscalizable->Ambito} promueva las acciones procedentes que garanticen su atención y cumplimiento, por lo cual, dichas recomendaciones se integrarán  en un expedientillo para el seguimiento correspondiente.";
                    $siRecomendaciones08 = "Con referencia al cumplimiento del requerimiento para la atención de las recomendaciones identificadas con clave número: ${recNoAt}, que no fueron atendidas por parte de la entidad fiscalizada; se remitirán a la Unidad de Asuntos Jurídicos del Órgano Superior de Fiscalización del Estado de México, para que aplique el medio de apremio que corresponda, en términos de la Ley General de Responsabilidades Administrativas, la Ley de Responsabilidades Administrativas del Estado de México y Municipios, la Ley de Fiscalización Superior del Estado de México y demás disposiciones jurídicas aplicables.";
                }
                $siRecomendaciones07 = "y 54 Bis";
            }
            if(count($auditoria->accionessolacl)>0){
                if(collect($NumSolacPromPo)->filter()->isNotEmpty()){
                    $CantSolacPromPo = count(collect($NumSolacPromPo));
                    $LetrasSolacpromPo= $formatter->toString($CantSolacPromPo);
                    $siSolAc01 = "DE LAS SOLICITUDES DE ACLARACIÓN FORMULADAS A PLIEGOS DE OBSERVACIONES";
                    $siSolAc02 = "Ahora bien, en relación a lo descrito en el numeral I del apartado denominado “Resultados Finales” del Seguimiento a la Etapa de Aclaración, se determinaron {$CantSolacPromPo} ({$LetrasSolacpromPo}) Solicitudes de Aclaración que no fueron aclaradas ni solventadas y que se promovieron a Pliegos de Observaciones identificados con número XXX y XXX, que ascienden a la cantidad total de XXXX.";
                    $siSolAc03 = "Por lo anterior, con fundamento en el artículo 54 fracciones I y III de la citada Ley de Fiscalización, se concede un plazo de 30 (Treinta) días hábiles contados a partir del día hábil siguiente al en que surta efectos la notificación del presente, para que esa entidad fiscalizada presente los elementos, documentos y datos fehacientes que aclaren, solventen o en su caso, atiendan, el contenido de las observaciones pendientes por agotar en la Etapa de Aclaración descritas en el apartado que antecede identificado con el numeral I, o en su caso, se manifieste lo que a su derecho convenga.";
                    $siSolAc04 = "En ese sentido, para el caso de no dar cumplimiento al requerimiento descrito en el párrafo que antecede, los citados Pliegos de Observaciones se tendrán por no aclarados ni solventados y por consiguiente, se dará vista a la autoridad competente para los efectos a que haya lugar.";
                    $siSolAc05 = "Cabe señalar que la información y documentación que exhiba la entidad fiscalizada con relación a las observaciones pendientes por agotar la Etapa de Aclaración, deberá presentarse debidamente certificada en medio impreso y digital.";
                }
                if(collect($NumSolacPromRecomendaciones)->filter()->isNotEmpty()){
                    $siSolAc06 = "DE LAS SOLICITUDES DE ACLARACIÓN PROMOVIDAS A RECOMENDACIONES";
                    $siSolAc07 = "En términos de lo previsto en los artículos 53 fracción II, 54 y 54 Bis de la Ley de Fiscalización Superior del Estado de México, las Solicitudes de Aclaración que derivaron en recomendaciones identificadas con número ${NumSolacPromRecomendaciones}  y que se encuentran vinculadas con la presente auditoría, serán integradas  en un expedientillo para el seguimiento correspondiente.";
                    $siSolAc08 = "Cabe señalar que la información y documentación que exhiba la entidad fiscalizada con relación a las recomendaciones en comento, deberá presentarse debidamente certificada en medio impreso y digital.";
                }
                if(collect($NumSolacPromPRAS)->filter()->isNotEmpty()){
                    $siSolAc09 = "DE LAS SOLICITUDES DE ACLARACIÓN PROMOVIDAS A PROMOCIONES DE RESPONSABILIDAD ADMINISTRATIVA SANCIONATORIA ";
                    $siSolAc010 = "Por cuanto hace a la Solicitud de Aclaración identificada con clave de acción: ${NumSolacPromPRAS} , se determinó que no fue aclarada ni solventada, sin embargo tomando en consideración que la esencia de la observación  se vincula con posibles irregularidades de responsabilidad administrativa no graves; con fundamento en los artículos 13 fracción IX, 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México en relación con los diversos 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y; 23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se formula la Promoción de Responsabilidad Administrativa Sancionatoria identificada con clave de acción: XXX ;  turnándose al Órgano Interno de Control de ${nombreEntidad} o su equivalente, para que realice las investigaciones pertinentes y en su caso, inicie el procedimiento administrativo correspondiente.";
                }
            }
            
            $replacements = array(
                array('entidad' => "{$nombreEntidad}", 'periodo'=> "{$auditoria->periodo_revision}", 'PAAnum' => "{$PAAnum}", 'numero_orden' => "{$auditoria->numero_orden}", 'entidad01' => "{$textoDocumento}", 'fecha_oficio_acuerdo' => "{$fecha_oficio_acuerdo}", 'oficio_numero' => "{$auditoria->oficio_numero}", 'siPliegos01' => "{$siPliegos01}", 'siRecomendaciones01' => "{$siRecomendaciones01}", 'siPliegos02' => "{$siPliegos02}", 'siRecomendaciones02' => "{$siRecomendaciones02}", 'SiPRAS01' => "{$siPRAS01}", 'siRecomendaciones03' => "{$siRecomendaciones03}", 'siRecomendaciones04' => "{$siRecomendaciones04}", ),

                );
            $replacements02 = array(
                array('siPliegos01' => "{$siPliegos01}", 'siPliegos02' => "{$siPliegos02}", 'siPliegos03' => "{$siPliegos03}", 'siPliegos04'=> "{$siPliegos04}", 'siPliegos05'=> "{$siPliegos05}", 'siPliegos06'=> "{$siPliegos06}", 'siPliegos07'=> "{$siPliegos07}",'siPliegos08'=> "{$siPliegos08}",'siPliegos09'=> "{$siPliegos09}",'siPliegos10'=> "{$siPliegos10}", 'siRecomendaciones05' => "{$siRecomendaciones05}", 'siRecomendaciones06' => "{$siRecomendaciones06}", 'siRecomendaciones08' => "{$siRecomendaciones08}", 'accionSolventada01' => "{$accionSolventada01}", 'accionSolventada02' => "{$accionSolventada02}", 'accionSolventada03' => "{$accionSolventada03}", 'accionSolventada04' => "{$accionSolventada04}", 'accionSolventada05' => "{$accionSolventada05}", 'accionSolventada06' => "{$accionSolventada06}", 'accionSolventada07' => "{$accionSolventada07}", 'accionSolventada08' => "{$accionSolventada08}", 'accionSolventada09' => "{$accionSolventada09}", 'accionSolventada10' => "{$accionSolventada10}", 'fechaInformeLetras' => "{$fechaactual}",
                      'siSolAc01' => "{$siSolAc01}", 'siSolAc02' => "{$siSolAc02}", 'siSolAc03' => "{$siSolAc03}", 'siSolAc04' => "{$siSolAc04}", 'siSolAc05' => "{$siSolAc05}", 'siSolAc06' => "{$siSolAc06}", 'siSolAc07' => "{$siSolAc07}", 'siSolAc08' => "{$siSolAc08}", 'siSolAc09' => "{$siSolAc09}", 'siSolAc10' => "{$siSolAc10}",  ),

                );

            $template=new TemplateProcessor('bases-word/IS/INVERSIÓN_FISICA/IS_01.docx');

            $template->cloneBlock('block', 1, true, false, $replacements);
            $template->cloneBlock('block_solacpo', count($auditoria->accionessolaclpo), true, false, $accionesSolAcPo);
            $template->cloneBlock('block_recomendaciones', count($auditoria->accionesrecomendaciones), true, false, $accionesRecomendaciones);
            $template->setValue('siRecomendaciones03', $siRecomendaciones03);
            $template->setValue('siRecomendaciones04', $siRecomendaciones04);
            $template->cloneBlock('blockAnalisis', 1, true, false, $replacements02);
            $template->setValue('nT', $nT[0]);
            $template->setValue('nD', $auditoria->directorasignado->name);
            $template->setValue('cD',$auditoria->directorasignado->puesto);
            $template->setValue('nJD', $auditoria->jefedepartamentoencargado->name);
            $template->setValue('cJD',  'Jefe de '.$auditoria->departamento_encargado);
            $template->setValue('nLP', $auditoria->lidercp->name);
            $template->setValue('nA', $auditoria->analistacp->name);

            $nombreword='IS';
            $template->saveAs($nombreword.'.docx');
        }
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
     }        


     //OFICIO
     public function exportOFIS(Request $request){
        $siRecomendaciones01 = ""; $siRecomendaciones02 = ""; $siRecomendaciones03 = ""; $siRecomendaciones04 = ""; $siRecomendaciones05 = ""; $siPRAS01 = ""; $siPliegoNoS01 = "";
        $siPrimeraEtapa01 = ""; $siSegundaEtapa01 = ""; $siPlazoSegundaEtapa = ""; $inicialesLM='MAOV'; $inicialesA=""; $inicialesD=''; $inicialesJD=''; $oficio_numero_informe = "";
        $fojasU = ""; $fojasULetras = ""; $remitente=""; $remitente_cargo = "";$remitente_domicilio = ""; $siEtapaAclaracion01 =""; $siEtapaAclaracion02 =""; $siPo01 = "";
        $nombreword = "OF";
        $auditoria=Auditoria::find(getSession('auditoria_id')); 
        $pras = Segpras::where('accion_id',getSession('prasauditoriaaccion_id'))->get();
        $formatter = new NumeroALetras();
        $plazomax=$formatter->toString($auditoria->radicacion->plazo_maximo);
        $plazomaxMax = ucwords($plazomax);
        $plazomaxMin = ucwords(strtolower($plazomaxMax));

        $tipo = $request->query('tipo'); // tipo para identificar el archivo solo aplica para legalidad
        $ent = $auditoria->entidad_fiscalizable;
        $frac ='';
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[(now()->format('n')) - 1];
        
        if($auditoria->entidadFiscalizable->Ambito = 'Estatal'){
            $nombre_ccp='</w:t><w:br/><w:t>Luis David Fernández Araya.';
            $info_ccp= 'C.c.p.  '.$nombre_ccp.' Encargado del Despacho de la Subsecretaría de Control y Auditoría de la Secretaría de la Contraloría del Gobierno del Estado de México. </w:t><w:br/><w:t>';
            $infodom_ccp='Domicilio: Av. Primero de Mayo, número 1731, Esquina Robert Bosch, Colonia Zona Industrial, C.P. 50071, Toluca, México.</w:t><w:br/><w:t>';
            $info=$info_ccp.' '.$infodom_ccp;
            $ambito01 = "";
            if (stripos($ent, 'poder') !== false) {
                $frac = 'fracción I.';
            }elseif(stripos($ent, 'órganos autónomos') !== false) {
                $frac = 'fracción III.';
            }elseif(stripos($ent, 'organismo auxiliar') !== false){
                $frac = 'fracción IV.';
            }elseif(stripos($ent, 'fideicomiso') !== false){
                $frac = 'fracción V.';
            }
            
        }elseif($auditoria->entidadFiscalizable->Ambito = 'Municipal'){
            $ambito01 = "115 fracción IV penúltimo párrafo,";
            if (stripos($ent, 'fideicomiso') !== false) {
                $frac = 'fracción V.';
            }elseif(stripos($ent, 'organismo auxiliar') !== false) {
                $frac = 'fracción IV.';
            }elseif(stripos($ent, 'municipios') !== false){
                $frac = 'fracción II.';
            }
        }
        
        if ($auditoria) {
            $entidad = ListadoEntidades::where('no_auditoria', $auditoria->numero_auditoria)->where('cuenta_publica', $auditoria->cuenta_publica)->select('entidades', 'textos_doc')->first();
                if ($entidad) {
                    $nombreEntidad = $entidad->entidades;
                    $textoDocumento = $entidad->textos_doc;
                }
            }
        
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

        $fecha_oficio = fecha(optional($auditoria->radicacion)->fecha_notificacion);
        $numero_oficio = $auditoria->radicacion->numero_acuerdo;
        
        if(empty($auditoria->informepliegos)){
            $fojasU =$auditoria->informeprimeraetapa->numero_fojas;
            $fojasULetras = ucwords(strtolower($formatter->toString($auditoria->informeprimeraetapa->numero_fojas)));
            $remitente =  $auditoria->informeprimeraetapa->nombre_titular_informe;
            $remitente_cargo =  $auditoria->informeprimeraetapa->cargo_titular_informe;
            $remitente_domicilio =  $auditoria->informeprimeraetapa->domicilio_informe;
            $oficio_numero_informe = $auditoria->informeprimeraetapa->numero_informe;
        }elseif(empty($auditoria->informeprimeraetapa)){
            $fojasU =$auditoria->informepliegos->numero_fojas;
            $fojasULetras = ucwords(strtolower($formatter->toString($auditoria->informepliegos->numero_fojas)));
            $remitente =  $auditoria->informepliegos->nombre_titular_informe;
            $remitente_cargo =  $auditoria->informepliegos->cargo_titular_informe;
            $remitente_domicilio =  $auditoria->informepliegos->domicilio_informe;  
            $oficio_numero_informe = $auditoria->informepliegos->numero_informe;
        }

        if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero' || $auditoria->acto_fiscalizacion=='Inversión Física'){

            if(count($auditoria->accionesrecomendaciones)>0){ /**PUEDO PONERLO TANTO PARA IF Y CF */
                $siRecomendaciones01 = "y 54 Bis";
                $siRecomendaciones02 = "y XXIII Bis,";
                $siRecomendaciones03 = " X,";
                $siRecomendaciones04 = "y del Proceso de Atención a las Recomendaciones correspondientes, ";
                $siRecomendaciones05 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones , o en su caso, justificara su improcedencia ; ";
            }
            if(count($auditoria->accionespras)>0){
                $siPRAS01 = ", XIX";
            }
            if(count($auditoria->totalNOsolventadopliegos)>0){
                $siPliegoNoS01 = "XVIII ";
            }

            if((count($auditoria->accionespo)>0) || (count($auditoria->accionesrecomendaciones)>0) && ('siPrimeraEtapa' == 'siPrimeraEtapa')){
                $siPrimeraEtapa01 = "Derivado del seguimiento al Informe de Auditoría correspondiente a la Auditoría de {$auditoria->acto_fiscalizacion}, practicada {$textoDocumento}, por el período comprendido del {$auditoria->periodo_revision}; en fecha ${fecha_oficio} le fue notificado a la entidad fiscalizada, el oficio número ${numero_oficio}, por medio del cual, se le hizo del conocimiento la emisión del Acuerdo de Radicación respectivo y se le citó a comparecencia para el efecto de que en ella se puntualizaran las observaciones de mérito y se pusiera a la vista el Expediente Técnico para su consulta e informarle de la apertura de la Etapa de Aclaración {$siRecomendaciones04}, con el objeto de que en un plazo de 30 (Treinta) días hábiles, solventara, aclarara o manifestara lo que a su derecho conviniera en relación al contenido de las acciones aludidas; ${siRecomendaciones05}; en ese sentido, remito a usted el Informe de Seguimiento por el que se notifica la situación que guardan las citadas observaciones, constante de {$fojasU} ({$fojasULetras}) fojas útiles.";
            }
            if('siSegundaEtapa01'=='no'){
                $siSegundaEtapa01 = "En seguimiento al oficio número X {oficioNumero_informe}X y notificado en fecha X {fecha_not_informe}X a esa entidad fiscalizada respecto de la Auditoría de {$auditoria->acto_fiscalizacion}, practicada a XXXX, por el período comprendido del XXX; remito a usted el Informe de Seguimiento por el que se notifica la situación que guardan las observaciones, constante de XXX fojas útiles.";
                $siPlazoSegundaEtapa = "Cabe señalar que las observaciones mencionadas en el párrafo que antecede, se encuentran sustentadas con las constancias agregadas en los expedientes técnicos respectivos, mismos que la entidad fiscalizada a través de sus titulares, o en su caso, representantes legales o enlaces debidamente autorizados, pueden consultar en las oficinas que ocupa esta Unidad, sito en Avenida José María Pino Suárez Sur, núms. 104, 106 y 108, Colonia Cinco de Mayo, Toluca, Estado de México, C.P. 50090, con cita que deberá ser agendada en el número de teléfono (722) 167 8450 (opción 3).";
            }

            $replacements = array(
                array('remitente' => "{$remitente}", 'remitente_cargo' => "{$remitente_cargo}", 'remitente_domicilio' => "{$remitente_domicilio}", 'ambito01' =>$ambito01 , 'frac'=>$frac,'siRecomendaciones01' => "{$siRecomendaciones01}", 'siRecomendaciones02' => "{$siRecomendaciones02}",'siPRAS01' => "{$siPRAS01}", 'siRecomendaciones03' => "{$siRecomendaciones03}",
                    'siPliegoNoS01' => "{$siPliegoNoS01}",'siPrimeraEtapa01' => "{$siPrimeraEtapa01}", 'siRecomendaciones04' => "{$siRecomendaciones04}",'siRecomendaciones05' => "{$siRecomendaciones05}", 'siSegundaEtapa01' => "{$siSegundaEtapa01}",'siPlazoSegundaEtapa' => "{$siPlazoSegundaEtapa}"),

                );
                     //array('customer_name' => 'Superman', 'customer_address' => 'Metropolis').
            
            $template=new TemplateProcessor('bases-word/IS/CUMPLIMIENTO_FINANCIERO/Of_IS_01.docx');     
            $template->setValue('direccion_asig',$auditoria->direccion_asignada);
            $template->setValue('departamento_asig',$auditoria->departamento_encargado);
            $template->setValue('anio',date("Y"));
            $template->setValue('dia', Carbon::now()->day);
            $template->setValue('mes',$mes);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('oficio_numero_informe', $oficio_numero_informe);
            $template->setValue('info', $info);
            $template->setValue('inicialesJD', $inicialesJD);
            $template->setValue('inicialesLM', $inicialesLM);
            $template->setValue('inicialesA', $inicialesA);
            $template->setValue('inicialesD', $inicialesD);

            
            $template->cloneBlock('block', 1, true, false, $replacements);

            
            $nombreword='Of. IS';
            $template->saveAs($nombreword.'.docx');
        }
        elseif($auditoria->acto_fiscalizacion=='Desempeño'){

            $replacements = array(
                array('remitente' => "{$remitente}", 'remitente_cargo' => "{$remitente_cargo}", 'remitente_domicilio' => "{$remitente_domicilio}", 'ambito01' =>$ambito01 , 'frac'=>$frac,'siPRAS01' => "{$siPRAS01}",
                        'entidad' => "{$nombreEntidad}", 'periodo'=> "{$auditoria->periodo_revision}", 'fecha_noti'=> "{$fecha_oficio}", 'oficio_numero01' => "{$numero_oficio}", 'fojasU'=>"{$fojasU}", 'fojasULetras' => "{$fojasULetras}"),
                );

            $template=new TemplateProcessor('bases-word/IS/DESEMPEÑO/Of_IS_PAR_01.docx');     
            $template->setValue('direccion_asig',$auditoria->direccion_asignada);
            $template->setValue('departamento_asig',$auditoria->departamento_encargado);
            $template->setValue('anio',date("Y"));
            $template->setValue('dia', Carbon::now()->day);
            $template->setValue('mes',$mes);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('oficio_numero_informe', $oficio_numero_informe);
            

            
            $template->setValue('plazo', $auditoria->radicacion->plazo_maximo);
            $template->setValue('plazoMaximoletra', $plazomaxMin);
            $template->setValue('acta_cierre_auditoria',$auditoria->radicacion->acta_cierre_auditoria);

            $template->setValue('info', $info);
            $template->setValue('inicialesJD', $inicialesJD);
            $template->setValue('inicialesLM', $inicialesLM);
            $template->setValue('inicialesA', $inicialesA);
            $template->setValue('inicialesD', $inicialesD);
            // $template->setValue('calificacion_sugerida',$calificacion_sugerida);
            // $template->setValue('analisis',$analisis);
            // $template->setValue('conclusion',$conclusion);            
            
           
            
            $template->cloneBlock('block', 1, true, false, $replacements);




            
            $nombreword='Of. IS PAR';
            $template->saveAs($nombreword.'.docx');
        }else{
            if($auditoria->acto_fiscalizacion=='Legalidad'){
                if(count($auditoria->accionesrecomendaciones)>0){ /**PUEDO PONERLO TANTO PARA IF Y CF */
                    $siRecomendaciones01 = "y 54 Bis";
                    $siRecomendaciones03 = " X,";
                }
                if(count($auditoria->accionespras)>0){
                    $siPRAS01 = ", XIX";
                }
                if(count($auditoria->accionespo)>0){
                    $siPo01 = "de la Etapa de Aclaración del Pliego de Observaciones";
                }
                if(count($auditoria->totalNOsolventadopliegos)>0){
                    $siPliegoNoS01 = "XVIII ";
                }
                if("Si etapa de aclaracion"=="no"){
                    $siEtapaAclaracion01 = "en un plazo de 30 (Treinta) días hábiles, solventara, aclarara o manifestara lo que a su derecho conviniera en relación al contenido de los Pliegos de Observaciones;";
                    $siEtapaAclaracion02 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones, o en su caso, justificara su improcedencia dentro del plazo convenido;";
                }
                if(count($auditoria->informes)>0 && "No hay segunda Etapa"=="No hay segunda Etapa"){
                    $siUnicoInforme01 = "Derivado del seguimiento al Informe de Auditoría correspondiente a la Auditoría de Legalidad, practicada {$textoDocumento} , por el período comprendido del {$auditoria->periodo_revision}; en fecha ${fecha_oficio}  le fue notificado a la entidad fiscalizada, el oficio número ${numero_oficio} , por medio del cual, se le hizo del conocimiento la emisión del Acuerdo de Radicación respectivo y se le citó a comparecencia para el efecto de que en ella se puntualizaran las observaciones  de mérito y se pusiera a la vista el Expediente Técnico para su consulta e informarle de la apertura ${siPo01} ${siRecomendaciones04} , con el objeto de que ${siEtapaAclaracion01}  ${siEtapaAclaracion02} en ese sentido, remito a usted el Informe de Seguimiento por el que se notifica la situación que guardan las observaciones, constante de {$fojasU} ({$fojasULetras}) fojas útiles.";
                }                   
                if('siSegundaEtapa01'=='no'){
                    $siSegundaEtapa01 = "En seguimiento al oficio número X {oficioNumero_informe}X y notificado en fecha X {fecha_not_informe}X a esa entidad fiscalizada respecto de la Auditoría de {$auditoria->acto_fiscalizacion}, practicada a XXXX, por el período comprendido del XXX; remito a usted el Informe de Seguimiento por el que se notifica la situación que guardan las observaciones, constante de XXX fojas útiles.";
                    $siPlazoSegundaEtapa = "Cabe señalar que las observaciones mencionadas en el párrafo que antecede, se encuentran sustentadas con las constancias agregadas en los expedientes técnicos respectivos, mismos que la entidad fiscalizada a través de sus titulares, o en su caso, representantes legales o enlaces debidamente autorizados, pueden consultar en las oficinas que ocupa esta Unidad, sito en Avenida José María Pino Suárez Sur, núms. 104, 106 y 108, Colonia Cinco de Mayo, Toluca, Estado de México, C.P. 50090, con cita que deberá ser agendada en el número de teléfono (722) 167 8450 (opción 3).";
                }

                $replacements = array(
                    array('remitente' => "{$remitente}", 'remitente_cargo' => "{$remitente_cargo}", 'remitente_domicilio' => "{$remitente_domicilio}", 'ambito01' =>$ambito01 , 'frac'=>$frac,'siPRAS01' => "{$siPRAS01}",'siRecomendaciones01' => "{$siRecomendaciones01}",
                    'siRecomendaciones03' => "{$siRecomendaciones03}",'siPliegoNoS01' => "{$siPliegoNoS01}" , 'siUnicoInforme01' => "{$siUnicoInforme01}", 'siSegundaEtapa01' => "{$siSegundaEtapa01}",'siPlazoSegundaEtapa' => "{$siPlazoSegundaEtapa}"),
                    );
                
                $template=new TemplateProcessor('bases-word/IS/LEGALIDAD/Of_IS_01.docx');
                $template->setValue('direccion_asig',$auditoria->direccion_asignada);
                $template->setValue('departamento_asig',$auditoria->departamento_encargado);
                $template->setValue('anio',date("Y"));
                $template->setValue('dia', Carbon::now()->day);
                $template->setValue('mes',$mes);
                $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
                $template->setValue('numero_auditoria',$auditoria->numero_auditoria); 
                $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
                $template->setValue('oficio_numero_informe', $oficio_numero_informe);
                $template->setValue('info', $info);
                $template->setValue('inicialesJD', $inicialesJD);
                $template->setValue('inicialesLM', $inicialesLM);
                $template->setValue('inicialesA', $inicialesA);
                $template->setValue('inicialesD', $inicialesD);

                $template->cloneBlock('block', 1, true, false, $replacements);

                $nombreword='Of. IS';
                $template->saveAs($nombreword.'.docx');
            }
            
        }


        
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
     } 
}
