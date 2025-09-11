<?php

namespace App\Http\Controllers\Informe;

use App\Http\Controllers\Controller;
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
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpParser\Node\Stmt\Else_;
use SebastianBergmann\Type\TrueType;
use Carbon\Carbon;



class InformeLegalidadController extends Controller
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
        
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

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
    public function edit(InformePrimeraEtapa $auditoria)
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
    public function update(Request $request, InformePrimeraEtapa $auditoria)
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

    public function exportar(){
        //$phpWord->addTitleStyle(1, ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]); /**ESTO SIRVE PARA DAR ESTILOS GLOBALES A LOS TITULOS */
        
        $auditoria=Auditoria::find(getSession('auditoria_id')); 
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        if ($auditoria) {
            $entidad = ListadoEntidades::where('no_auditoria', $auditoria->numero_auditoria)->where('cuenta_publica', $auditoria->cuenta_publica)->select('entidades', 'textos_doc')->first();
                if ($entidad) {
                    $nombreEntidad = $entidad->entidades;
                    $entidad01 = $nombreEntidad;
                    $textoDocumento = $entidad->textos_doc;
                }
            }

        // Obtener los datos de la base
        $segrecomendacion = AuditoriaAccion::select(
            'segauditoria_acciones.accion',
            'segauditoria_acciones.numero',
            'segauditoria_acciones.plazo_recomendacion',
            'segauditoria_acciones.monto_aclarar',
            'segauditoria_acciones.normativa_infringida',
            DB::raw("expresar_en_letras1.numero_a_letras(segauditoria_acciones.monto_aclarar) AS monto_aclarar_letras"),
            DB::raw("(CASE WHEN segrecomendaciones_contestaciones.oficio_contestacion IS NULL THEN 'En ese orden de ideas...' ELSE NULL END) AS sicontestacion01"),
            'segrecomendaciones_contestaciones.numero_oficio',
            DB::raw("TO_CHAR(segrecomendaciones_contestaciones.fecha_oficio_contestacion, 'DD/MM/YYYY') AS fecha_oficio_contestacion"),
            DB::raw("TO_CHAR(segrecomendaciones_contestaciones.fecha_recepcion_oficialia, 'DD/MM/YYYY') AS fecha_recepcion_oficialia"),
            'segrecomendaciones_contestaciones.folio_correspondencia',
            'segrecomendaciones_contestaciones.nombre_remitente',
            'segrecomendaciones_contestaciones.cargo_remitente',
            'segrecomendaciones.calificacion_sugerida',
            'segrecomendaciones.analisis',
            'segrecomendaciones.conclusion',
            'segrecomendaciones.listado_documentos',
            DB::raw("(CASE WHEN segrecomendaciones.calificacion_sugerida = 'Atendida' THEN 'En ese sentido, con fundamento en...' ELSE NULL END) AS sicalificacionsugerida01"),
            DB::raw("(CASE WHEN segrecomendaciones.calificacion_sugerida = 'Atendida' THEN segauditoria_acciones.numero ELSE NULL END) AS tr01"),
            DB::raw("(CASE WHEN segrecomendaciones.calificacion_sugerida = 'No Atendida' THEN 'Por tanto se tiene como no atendida...' ELSE NULL END) AS sicalificacionsugerida02")
        )
        ->join('segrecomendaciones', 'segrecomendaciones.accion_id', '=', 'segauditoria_acciones.id')
        ->leftJoin('segrecomendaciones_contestaciones', 'segrecomendaciones_contestaciones.recomendacion_id', '=', 'segrecomendaciones.id')
        ->where('auditoria_id', $auditoria->id)
        ->get();

        $segpliego = AuditoriaAccion::select('segauditoria_acciones.tipo',/*TABLA segauditoria_acciones*/
                                                'segauditoria_acciones.accion', 'segauditoria_acciones.numero', 'segauditoria_acciones.plazo_recomendacion', 'segauditoria_acciones.monto_aclarar', 'segauditoria_acciones.normativa_infringida',
                                                DB::raw("expresar_en_letras1.numero_a_letras(segauditoria_acciones.monto_aclarar) AS monto_aclarar_letras"), 
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
                                ->toSql();
		dd($segpliego);
		
        $table = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFF',
            //'alignment' => JcTable::CENTER,
        ]);

        $table->addRow();
        $table->addCell(2000)->addText("Entidad Fiscalizada:",['bold' => true]);
        //$table->addCell(4000)->addText($nombreEntidad);

        $table->addCell(3000, ['valign' => 'center'])->addText('Contenido centrado y negrita',['bold' => true]);

        $table->addRow();
        $table->addCell(2000)->addText("Tipo de Auditoría:",['bold' => true]);
        $table->addCell(4000)->addText("Auditoría de Legalidad:");

        $table->addRow();
        $table->addCell(2000)->addText("Período Fiscalizado:");
        $table->addCell(4000)->addText($auditoria->periodo_revision);

        $section->addText('ANTECEDENTES',['bold' => true], ['alignment' => Jc::CENTER] // alineación
        );

        //DD($segpliego);
        // Recorrer y agregar al documento
        foreach ($segpliego as $item) {
            $X = nl2br($item->listado_documentos); 

            $xe=explode("\n",$item->listado_documentos);

            //dd($item);
            $section->addText("Número de acción: {$item->numero}", ['italic' => true]);
            $section->addText("Acción: {$item->accion}");
            $section->addText("Plazo de recomendación: {$item->plazo_recomendacion}");
            $section->addText("Monto a aclarar: {$item->monto_aclarar} ({$item->monto_aclarar_letras})");

            foreach($xe as $ex){
                $section->addText("{$ex}");
            }
            

            
                $section->addText($item->sicontestacion01, ['italic' => true]);
            

            $section->addTextBreak(1); // Salto de línea

            // Mostrar calificación
            $section->addText("Calificación sugerida: {$item->calificacion_sugerida}", ['bold' => true]);

            if ($item->sicalificacionsugerida01) {
                $section->addText($item->sicalificacionsugerida01);
            }
            if ($item->sicalificacionsugerida02) {
                $section->addText($item->sicalificacionsugerida02);
            }

            $section->addTextBreak(2); // Salto más grande entre recomendaciones
        }

        // Guardar y descargar
        $fileName = 'informe_legalidad.docx';
        $tempPath = storage_path($fileName);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);

    }

     public function export(Request $request){
        //dd('export legalidad');
        $auditoria=Auditoria::find(getSession('auditoria_id')); 
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
        $fecha_PAA = fechaaletra(optional($auditoria->paa)->fecha_paa); $fecha_orden = fechaaletra($auditoria->created_at);
        $AnalisisAntecede01 = '';
        $oficio_numero = optional($auditoria->radicacion)->oficio_acuerdo;
        $periodoLetras = convertirPeriodo($auditoria->periodo_revision);
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
        $frac ='XXXXXXXXXXXXXXXXXX';
        $ambito01 = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
        $entidad01 = $nombreEntidad;
        $entidadtexto = $textoDocumento;
        $nombreEntidad = $entidad01;
        $textoDocumento = $entidadtexto;

        if (stripos($ent, 'poder') !== false) {
            $frac = 'fracción I.';
        }elseif(stripos($ent, 'órganos autónomos') !== false) {
            $frac = 'fracción III.';
        }elseif(stripos($ent, 'organismo auxiliar') !== false){
            $frac = 'fracción IV.';
        }elseif(stripos($ent, 'fideicomiso') !== false){
            $frac = 'fracción V.';
        }
            
        //}else
        if($auditoria->entidadFiscalizable->Ambito == "Municipal"){
            $ambito01 = "115 fracción IV penúltimo párrafo,";
        }
            
        if (stripos($ent, 'fideicomiso') !== false && stripos($ent, 'Municipal') !== false) {
            $frac = 'fracción V.';
        }elseif(stripos($ent, 'organismo auxiliar') !== false && stripos($ent, 'Municipal') !== false) {
            $frac = 'fracción IV.';
        }elseif(stripos($ent, 'municipios') !== false && stripos($ent, 'Municipal') !== false){
            $frac = 'fracción II.';
        }

        $fecha_oficio_acuerdo = fechaaletra(optional($auditoria->radicacion)->fecha_oficio_acuerdo);
        $segsolac = AuditoriaAccion::select('segauditoria_acciones.consecutivo',/*TABLA segauditoria_acciones*/
                    DB::raw("UPPER(segauditoria_acciones.tipo) AS tipo_mayus"),
                                                'segauditoria_acciones.tipo', 'segauditoria_acciones.accion', 'segauditoria_acciones.numero', 'segauditoria_acciones.plazo_recomendacion', 'segauditoria_acciones.monto_aclarar', 'segauditoria_acciones.normativa_infringida',
                                                DB::raw("expresar_en_letras1.numero_a_letras(segauditoria_acciones.monto_aclarar) AS monto_aclarar_letras"), 
    /*TABLA segsolicitudes_acl_contestaciones*/ /*DB::raw("(case when(segsolicitudes_acl_contestaciones.oficio_contestacion IS NULL) THEN 'En ese orden de ideas, esta Unidad de Seguimiento hace constar que durante el plazo concedido para el desahogo de la Etapa de Aclaración, la entidad fiscalizada no presentó información, documentación o consideraciones relacionadas con la observación de mérito.' ELSE null END) AS sicontestacion01"),
                                                'segsolicitudes_acl_contestaciones.numero_oficio', 
                                                //'segsolicitudes_acl_contestaciones.fecha_oficio_contestacion', 
                                                //'segsolicitudes_acl_contestaciones.fecha_recepcion_oficialia',
                                                DB::raw("TO_CHAR(segsolicitudes_acl_contestaciones.fecha_oficio_contestacion, 'DD/MM/YYYY') AS fecha_oficio_contestacion"),
                                                DB::raw("TO_CHAR(segsolicitudes_acl_contestaciones.fecha_recepcion_oficialia, 'DD/MM/YYYY') AS fecha_recepcion_oficialia"), 
                                                'segsolicitudes_acl_contestaciones.folio_correspondencia', 'segsolicitudes_acl_contestaciones.nombre_remitente', 'segsolicitudes_acl_contestaciones.cargo_remitente', 
                                                */
                                                'segsolicitudes_aclaracion.calificacion_sugerida',/*TABLA segsolicitudes_aclaracion*/
                                                DB::raw("UPPER(segsolicitudes_aclaracion.calificacion_sugerida) AS calificacion_sugerida_mayus"),
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
                            ->where('auditoria_id', $auditoria->id)->orderBy('segauditoria_acciones.consecutivo')
                            ->get()
                            ->toArray();
        $segsolac= array_map("unserialize", array_unique(array_map('serialize',$segsolac)));
        $NumSolacPromPo = collect($segsolac)->pluck('numsolacpo')->filter()->implode(', ');
        $CantSolacPromPo = count(collect($segsolac)->pluck('numsolacpo')->filter());
        $NumSolacPromRecomendaciones = collect($segsolac)->pluck('numporecomen')->filter()->implode(', ');
        $NumSolacPromPRAS = collect($segsolac)->pluck('numprompras')->filter()->implode(', ');
        $tsolac01 = collect($segsolac)->pluck('tsolac01')->filter()->implode(', ');

        $segpliego = AuditoriaAccion::select('segauditoria_acciones.tipo',/*TABLA segauditoria_acciones*/
                                                DB::raw("UPPER(segauditoria_acciones.tipo) AS tipo_mayus"),
                                                'segauditoria_acciones.accion', 'segauditoria_acciones.numero', 'segauditoria_acciones.plazo_recomendacion', 'segauditoria_acciones.monto_aclarar', 'segauditoria_acciones.normativa_infringida',
                                                DB::raw("expresar_en_letras1.numero_a_letras(segauditoria_acciones.monto_aclarar) AS monto_aclarar_letras"), 
   /*TABLA segpliegos_observacion_contestacion*//*DB::raw("(case when(segpliegos_observacion_contestacion.oficio_contestacion IS NULL) THEN 'En ese orden de ideas, esta Unidad de Seguimiento hace constar que durante el plazo concedido para el desahogo de la Etapa de Aclaración, la entidad fiscalizada no presentó información, documentación o consideraciones relacionadas con la observación de mérito.' ELSE null END) AS sicontestacion01"),
                                                'segpliegos_observacion_contestacion.numero_oficio', 
                                                DB::raw("TO_CHAR(segpliegos_observacion_contestacion.fecha_oficio_contestacion, 'DD/MM/YYYY') AS fecha_oficio_contestacion"),
                                                DB::raw("TO_CHAR(segpliegos_observacion_contestacion.fecha_recepcion_oficialia, 'DD/MM/YYYY') AS fecha_recepcion_oficialia"), 
                                                'segpliegos_observacion_contestacion.folio_correspondencia', 'segpliegos_observacion_contestacion.nombre_remitente', 'segpliegos_observacion_contestacion.cargo_remitente', 
                                                */
                                                'segpliegos_observacion.calificacion_sugerida',/*TABLA segpliegos_observacion*/
                                                DB::raw("UPPER(segpliegos_observacion.calificacion_sugerida) AS calificacion_sugerida_mayus"),
                                                'segpliegos_observacion.analisis', 'segpliegos_observacion.conclusion','segpliegos_observacion.listado_documentos',
                                                DB::raw("(case when(segpliegos_observacion.calificacion_sugerida = 'No Solventado') THEN 'Por tanto, se tiene como no aclarado ni solventado para este Órgano Superior de Fiscalización del Estado de México, el Pliego de Observaciones con clave de acción '||segauditoria_acciones.numero||'; en consecuencia, con fundamento en el artículo 47 fracciones XII, XVIII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; el Pliego de Observaciones será turnado a la autoridad investigadora de este Órgano Técnico, a efecto de que se inicie el procedimiento administrativo de investigación a que haya lugar, en términos de la Ley General de Responsabilidades Administrativas, la Ley de Responsabilidades Administrativas del Estado de México y Municipios y demás disposiciones jurídicas aplicables.' ELSE NULL END) AS sicalificacionsugerida01"),
                                                DB::raw("(case when(segpliegos_observacion.calificacion_sugerida = 'Solventado') THEN 'En ese sentido, con fundamento en lo dispuesto por los artículos 54 fracción III de la Ley de Fiscalización Superior del Estado de México y; 23 fracciones XIX y XLIV y; 47 fracciones XII y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, se determina que el Pliego de Observaciones ha quedado aclarado y solventado.' ELSE NULL END) AS sicalificacionsugerida02"),

                                                DB::raw("(case when(segpliegos_observacion.promocion = 4) THEN segauditoria_acciones.numero ELSE NULL END) AS numpopras"),
                                                DB::raw("(case when(segpliegos_observacion.promocion = 2) THEN segauditoria_acciones.numero ELSE NULL END) AS numporecomen"),

                                                DB::raw("(case when(segpliegos_observacion.calificacion_sugerida = 'Solventado') THEN segauditoria_acciones.numero ELSE NULL END) AS tpo01")
                                                )
                                             
                                ->join('segpliegos_observacion', 'segpliegos_observacion.accion_id', '=', 'segauditoria_acciones.id')
                                ->leftJoin('segpliegos_observacion_contestacion', 'segpliegos_observacion_contestacion.pliegosobservacion_id',"=",'segpliegos_observacion.id')
                                ->where('auditoria_id', $auditoria->id)->orderBy('segauditoria_acciones.consecutivo')
                                ->get()->toArray();
		//dd($segpliego);
        $segpliego= array_map("unserialize", array_unique(array_map('serialize',$segpliego)));

        $accionesSolAcPo = array_merge($segsolac,$segpliego);

        $NumPoPromPRAS = collect($segpliego)->pluck('numpopras')->filter()->implode(', ');
        $NumPoPromRecomendaciones = collect($segpliego)->pluck('numporecomen')->filter()->implode(', ');
        $tpo01 = collect($segpliego)->pluck('tpo01')->filter()->implode(', ');

        

        $segrecomendacion = AuditoriaAccion::select('segauditoria_acciones.accion',/*TABLA segauditoria_acciones*/
                                                'segauditoria_acciones.numero', 'segauditoria_acciones.plazo_recomendacion', 'segauditoria_acciones.monto_aclarar','segauditoria_acciones.normativa_infringida',
                                                DB::raw("expresar_en_letras1.numero_a_letras(segauditoria_acciones.monto_aclarar) AS monto_aclarar_letras"), 
    /*TABLA segrecomendaciones_contestaciones*/ /*DB::raw("(case when(segrecomendaciones_contestaciones.oficio_contestacion IS NULL) THEN 'En ese orden de ideas, esta Unidad de Seguimiento hace constar que durante el plazo concedido para el desahogo del Proceso de Atención a Recomendaciones, la entidad fiscalizada no presentó información, documentación o consideraciones relacionadas con la Recomendación de mérito.' ELSE null END) AS sicontestacion01"),
                                                'segrecomendaciones_contestaciones.numero_oficio', 
                                                DB::raw("TO_CHAR(segrecomendaciones_contestaciones.fecha_oficio_contestacion, 'DD/MM/YYYY') AS fecha_oficio_contestacion"),
                                                DB::raw("TO_CHAR(segrecomendaciones_contestaciones.fecha_recepcion_oficialia, 'DD/MM/YYYY') AS fecha_recepcion_oficialia"), 
                                                'segrecomendaciones_contestaciones.folio_correspondencia', 'segrecomendaciones_contestaciones.nombre_remitente', 'segrecomendaciones_contestaciones.cargo_remitente', 
                                                */
                                                'segrecomendaciones.calificacion_sugerida',/*TABLA segrecomendaciones*/
                                                DB::raw("UPPER(segrecomendaciones.calificacion_sugerida) AS calificacion_sugerida_mayus"),
                                                'segrecomendaciones.analisis', 'segrecomendaciones.conclusion', 'segrecomendaciones.listado_documentos',
                                                DB::raw("(case when(segrecomendaciones.calificacion_sugerida = 'Atendida') THEN 'En ese sentido, con fundamento en lo dispuesto por los artículos 54 fracción III de la Ley de Fiscalización Superior del Estado de México y; 23 fracciones XIX y XLIV y; 47 fracciones XII y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, se determina que XXX ha quedado aclarado y solventado.' ELSE NULL END) AS sicalificacionsugerida01"),
                                                DB::raw("(case when(segrecomendaciones.calificacion_sugerida = 'Atendida') THEN segauditoria_acciones.numero ELSE NULL END) AS tr01"),
                                                DB::raw("(case when(segrecomendaciones.calificacion_sugerida = 'No Atendida') THEN 'Por tanto se tiene como no atendida para este Órgano Superior de Fiscalización del Estado de México, la Recomendación con clave de acción XXX; en consecuencia, con fundamento en los artículos 8 fracción XXVII de la Ley de Fiscalización Superior del Estado de México y 23 fracciones XIX y XLIV y 47 fracción XV segundo párrafo y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; la Recomendación será turnada al Órgano Interno de Control de XXX o su equivalente, para el efecto de que dicha autoridad de control interno XXX promueva las acciones procedentes que garanticen su atención y cumplimiento.' ELSE NULL END)  AS sicalificacionsugerida02" ),
                                             
                                            )
                                             
                                ->join('segrecomendaciones', 'segrecomendaciones.accion_id', '=', 'segauditoria_acciones.id')
                                ->leftJoin('segrecomendaciones_contestaciones', 'segrecomendaciones_contestaciones.recomendacion_id',"=",'segrecomendaciones.id')
                                ->where('auditoria_id', $auditoria->id)->orderBy('segauditoria_acciones.consecutivo')
                                ->get()->toArray();
        $segrecomendacion= array_map("unserialize", array_unique(array_map('serialize',$segrecomendacion)));
        $accionesRecomendaciones = array_merge($segrecomendacion);
        $tr01 = collect($segrecomendacion)->pluck('tr01')->filter()->implode(', ');
        $PlazoAcciones = AuditoriaAccion::where('segauditoria_id', $auditoria->id)
                ->max('segauditoria_acciones.plazo_recomendacion');
        // Extraer el número al inicio 
        preg_match('/^\d+/', $PlazoAcciones,$matches);
        $numero = isset($matches[0]) ? (int) $matches[0] : 0;

        // Extraer el resto del texto 
        $resto = trim(substr($PlazoAcciones, strlen($matches[0])));

        $numeroEnLetras = $formatter->toString($numero);
        $PlazoAccionesLetras = $numeroEnLetras . '' . $resto; 
        $PlazoAccionesLetrasMin =ucwords(strtolower($PlazoAccionesLetras));

        if ($auditoria) {
            $entidad = ListadoEntidades::where('no_auditoria', $auditoria->numero_auditoria)->where('cuenta_publica', $auditoria->cuenta_publica)->select('entidades', 'textos_doc')->first();
                if ($entidad) {
                    $nombreEntidad = $entidad->entidades;
                    $entidad01 = $nombreEntidad;
                    $textoDocumento = $entidad->textos_doc;
                }
            }

        $TSP=0;
        $TSPS=0;
        $TSPNS=0;
        //SOLICITUDA DE ACLACION
        foreach ($auditoria->totalsolacl as $solicitud) {
            $TSP=$TSP+$solicitud->monto_aclarar;
            $TSPS=$TSPS+((!empty($solicitud->solicitudesaclaracion)&&!empty($solicitud->solicitudesaclaracion->monto_solventado))?$solicitud->solicitudesaclaracion->monto_solventado:0);
            $TSPNS=$TSPNS+($solicitud->monto_aclarar-((!empty($solicitud->solicitudesaclaracion)&&!empty($solicitud->solicitudesaclaracion->monto_solventado))?$solicitud->solicitudesaclaracion->monto_solventado:0));
        }

        $TPP=0;
        $TPPS=0;
        $TPPNS=0;
        //PLIEGOS
        foreach ($auditoria->totalpliegos as $pliego) {
            $TPP=$TPP+$pliego->monto_aclarar;
            $TPPS=$TPPS+((!empty($pliego->pliegosobservacion)&&!empty($pliego->pliegosobservacion->monto_solventado))?$pliego->pliegosobservacion->monto_solventado:0);
            $TPPNS=$TPPNS+($pliego->monto_aclarar-((!empty($pliego->pliegosobservacion)&&!empty($pliego->pliegosobservacion->monto_solventado))?$pliego->pliegosobservacion->monto_solventado:0));
        }
        //TOTAL SOLICITUD DE ACALACION Y PLIEGOS
        $TAP=$TSP+$TPP;
        $TAPS=$TSPS+$TPPS;
        $TAPNS=$TSPNS+$TPPNS;

        $TPPNSLetras=$formatter->toInvoice($TPPNS);
		//dd($TPPNSLetras,4);
        $PAAnum = getSession('cp')+1;
            $ncT = User::select('segusers.name', 'segusers.puesto')->where('siglas_rol','TUS')->get();
            $nT = $ncT->pluck('name')->toArray();     
        
        /**LEGALIDAD */
        if($auditoria->acto_fiscalizacion=='Legalidad'){
            if(count($auditoria->accionespo)>0){
                 $siPliegos01 = "de la Etapa de Aclaración";
                 $siPliegos02 = "en un plazo de 30 (Treinta) días hábiles, solventara, aclarara o manifestara lo que a su derecho conviniera en relación al contenido de los Pliegos de Observaciones aludidos; ";
                 if(count($auditoria->totalNOsolventadopliegos)>0){
                    $CantpoNoSol = count($auditoria->totalNOsolventadopliegos);
                    $CantpoLetras= $formatter->toString($CantpoNoSol);
					//dd($CantpoLetras);
                    $siPliegos03 = "DE LOS PLIEGOS DE OBSERVACIONES QUE NO FUERON SOLVENTADOS Y EL MONTO NO ACLARADO";
                    $siPliegos04 = "Derivado de lo descrito en el numeral I del apartado que nos antecede de los Resultados Finales del Seguimiento a la Etapa de Aclaración, se determinaron {$CantpoNoSol} "."(".ucwords(strtolower($CantpoLetras)).")"." Pliegos de Observaciones no aclarados ni solventados, mismos que ascienden a la cantidad total de $".number_format( $TPPNS, 2). " ( ". $TPPNSLetras." ).";
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
                             23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, por medio del diverso número". optional($auditoria->segpras)->numero_oficio .", se turnaron por cuerda separada al Órgano Interno de Control competente, 
                             las Promociones de Responsabilidad Administrativa Sancionatoria, para que se continúe con la investigación pertinente y promueva las acciones procedentes, ordenándose formar expedientillo relativo a las Promociones de Responsabilidad Administrativa Sancionatoria.";
                if(optional($auditoria->segpras)->estatus_cumplimiento == 'No Atendido'){
                    $siPRAS02= "Así pues, agotado el plazo para la atención de las observaciones a que se alude en el segundo párrafo del presente apartado, sin que a la fecha de emisión del presente se tenga evidencia documental ingresada por parte de la entidad fiscalizada, se llegó a la conclusión de los siguientes: ===================================";
                }
            }
            if(count($auditoria->accionesrecomendaciones)>0){
                $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones correspondientes";
                $siRecomendaciones02 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones , o en su caso, justificara su improcedencia dentro del plazo de ". $PlazoAcciones."(".$PlazoAccionesLetrasMin.") días hábiles, plazo que fue convenido con el Órgano Superior de Fiscalización del Estado de México, detallado en el Acta de Reunión de Resultados Finales y Cierre de Auditoría ". $auditoria->radicacion->acta_cierre_auditoria.", con el apercibimiento de que para el caso de no dar cumplimento a dicho requerimiento, se estaría a lo dispuesto en el artículo 59 fracción II de la Ley de Fiscalización Superior del Estado de México, ello, con independencia de las sanciones administrativas y penales que, en términos de las leyes en dichas materias, resultaren aplicables .";
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
                $accionSolventada04 = "TERCERO. Que en términos del numeral que antecede, se determina la conclusión del seguimiento a los resultados obtenidos en la Auditoría de Cumplimiento Financiero, practicada {$textoDocumento}, por el período comprendido del {$periodoLetras} y ordenada mediante oficio número {$auditoria->numero_orden}. ";
                $accionSolventada05 = "Lo anterior, sin que implique la liberación de responsabilidades que pudieran llegarse a determinar con posterioridad por las autoridades de control y/o fiscalización federales o estatales que efectúen en el ámbito de su competencia; o bien, de aquellas que pudieran resultar de auditorías o revisiones que en ejercicio de sus atribuciones realice esta entidad estatal de fiscalización, al mismo período o períodos diferentes.";
                $accionSolventada06 = "CUARTO. Archívese el Expediente Técnico de Auditoría y el de la Etapa de Aclaración para su guarda y custodia en la Unidad de Seguimiento del Órgano Superior de Fiscalización del Estado de México, únicamente por lo que hace a las observaciones que han quedado totalmente aclaradas y Solventadas/Atendidas .";
                $accionSolventada07 = "QUINTO. Notifíquese por oficio a la entidad fiscalizada en el domicilio ubicado en {$remitente_domicilio}.";
                $accionSolventada08 = "";$accionSolventada09 = "";$accionSolventada10 = "";
            }

            $replacements = array(
                array('entidad' => "{$nombreEntidad}", 'periodo'=> "{$auditoria->periodo_revision}", 'PAAnum' => "{$PAAnum}", 'fechaPAA' => "{$fecha_PAA}", 'numero_orden' => "{$auditoria->numero_orden}", 'fecha_orden' => "{$fecha_orden}", 'entidad01' => "{$textoDocumento}",'periodoLetras'=> "{$periodoLetras}", 'fecha_oficio_acuerdo' => "{$fecha_oficio_acuerdo}", 'oficio_numero' => "{$oficio_numero}", 'siPliegos01' => "{$siPliegos01}", 'siRecomendaciones01' => "{$siRecomendaciones01}", 'siPliegos02' => "{$siPliegos02}", 'siRecomendaciones02' => "{$siRecomendaciones02}", 'SiPRAS01' => "{$siPRAS01}", 'siRecomendaciones03' => "{$siRecomendaciones03}", 'siRecomendaciones04' => "{$siRecomendaciones04}", ),

                );
            $replacements02 = array(
                array('siPliegos03' => "{$siPliegos03}", 'siPliegos04'=> "{$siPliegos04}", 'siPliegos05'=> "{$siPliegos05}", 'siPliegos06'=> "{$siPliegos06}", 'siPliegos07'=> "{$siPliegos07}",'siPliegos08'=> "{$siPliegos08}",'siPliegos09'=> "{$siPliegos09}",'siPliegos10'=> "{$siPliegos10}", 'siRecomendaciones05' => "{$siRecomendaciones05}", 'siRecomendaciones06' => "{$siRecomendaciones06}", 'siRecomendaciones08' => "{$siRecomendaciones08}", 'accionSolventada01' => "{$accionSolventada01}", 'accionSolventada02' => "{$accionSolventada02}", 'accionSolventada03' => "{$accionSolventada03}", 'accionSolventada04' => "{$accionSolventada04}", 'accionSolventada05' => "{$accionSolventada05}", 'accionSolventada06' => "{$accionSolventada06}", 'accionSolventada07' => "{$accionSolventada07}", 'accionSolventada08' => "{$accionSolventada08}", 'accionSolventada09' => "{$accionSolventada09}", 'accionSolventada10' => "{$accionSolventada10}", 'fechaInformeLetras' => "{$fechaactual}"),

                );
            if($auditoria->directorasignado->puesto == 'Director de la Dirección de Seguimiento "B"'){
                $cD = 'Director de Seguimiento "B"';
            }else{
                $cD = 'Director de Seguimiento "A"';  
            }
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
                $template->setValue('cD',$cD);
                $template->setValue('nJD', $auditoria->jefedepartamentoencargado->name);
                $template->setValue('cJD',  $auditoria->jefedepartamentoencargado->puesto);
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
                $template->setValue('cD',$cD);
                $template->setValue('nJD', $auditoria->jefedepartamentoencargado->name);
                $template->setValue('cJD',  $auditoria->jefedepartamentoencargado->puesto);
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
                $template->setValue('cD',$cD);
                $template->setValue('nJD', $auditoria->jefedepartamentoencargado->name);
                $template->setValue('cJD',  $auditoria->jefedepartamentoencargado->puesto);
                $template->setValue('nLP', $auditoria->lidercp->name);
                $template->setValue('nA', $auditoria->analistacp->name);

                $nombreword='IS. EA';
                $template->saveAs($nombreword.'.docx');
            }
        }
         return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
     }
     
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

        if ($auditoria) {
            $entidad = ListadoEntidades::where('no_auditoria', $auditoria->numero_auditoria)->where('cuenta_publica', $auditoria->cuenta_publica)->select('entidades', 'textos_doc')->first();
                if ($entidad) {
                    $nombreEntidad = $entidad->entidades;
                    $textoDocumento = $entidad->textos_doc;
                }
            }
        
        $ent = $auditoria->entidad_fiscalizable;
        $frac ='XXXXXXXXXXXXXXXXXX';
        $ambito01 = "";
        $entidad01 = $nombreEntidad;
        $entidadtexto = $textoDocumento;
        $nombreEntidad = $entidad01;
        $textoDocumento = $entidadtexto;

        $tipo = $request->query('tipo'); // tipo para identificar el archivo solo aplica para legalidad
        $meses = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
        $mes = $meses[(now()->format('n')) - 1];
        
        if (stripos($ent, 'poder') !== false) {
            $frac = 'fracción I.';
        }elseif(stripos($ent, 'órganos autónomos') !== false) {
            $frac = 'fracción III.';
        }elseif(stripos($ent, 'organismo auxiliar') !== false){
            $frac = 'fracción IV.';
        }elseif(stripos($ent, 'fideicomiso') !== false){
            $frac = 'fracción V.';
        }
        if(stripos($ent, 'Municipal') !== false){
            $ambito01 = "115 fracción IV penúltimo párrafo,";
            if (stripos($ent, 'fideicomiso') !== false) {
                $frac = 'fracción V.';
            }elseif(stripos($ent, 'organismo auxiliar') !== false ) {
                $frac = 'fracción IV.';
            }elseif(stripos($ent, 'municipios') !== false ){
                $frac = 'fracción II.';
            }
        }
            
        

        $nD = $auditoria->directorasignado->name;
        $nJD = $auditoria->jefedepartamentoencargado->name;
        //$nA = $auditoria->analistacp->name;
        $StaffAsignadoId = optional($auditoria->Staff)->first()->staff_id;
        $StaffAsignado = User::where('id', $StaffAsignadoId)->first();
        $nS = $StaffAsignado->name;

        $inicialesD = Iniciales($nD);
        $inicialesJD = Iniciales($nJD);
        $inicialesStaff = Iniciales($nS);


        //$fecha_oficio = fecha(optional($auditoria->radicacion)->fecha_notificacion);
        $fecha_oficio = fechaaletra(optional($auditoria->radicacion)->fecha_oficio_acuerdo);
        $numero_oficio = $auditoria->radicacion->oficio_acuerdo;
        
        if(!empty($auditoria->informeprimeraetapa)){
            $fojasU =$auditoria->informeprimeraetapa->numero_fojas;
            $fojasULetras = ucwords(strtolower($formatter->toString($auditoria->informeprimeraetapa->numero_fojas)));
            $remitente =  $auditoria->informeprimeraetapa->nombre_titular_informe;
            $remitente_cargo =  $auditoria->informeprimeraetapa->cargo_titular_informe;
            $remitente_domicilio =  $auditoria->informeprimeraetapa->domicilio_informe;
            $periodo_gestion =  $auditoria->informeprimeraetapa->periodo_gestion;
            $oficio_numero_informe = $auditoria->informeprimeraetapa->numero_informe;
        }elseif(!empty($auditoria->informepliegos)){
            $fojasU =$auditoria->informepliegos->numero_fojas;
            $fojasULetras = ucwords(strtolower($formatter->toString($auditoria->informepliegos->numero_fojas)));
            $remitente =  $auditoria->informepliegos->nombre_titular_informe;
            $remitente_cargo =  $auditoria->informepliegos->cargo_titular_informe;
            $remitente_domicilio =  $auditoria->informepliegos->domicilio_informe;  
            $periodo_gestion =  $auditoria->informepliegos->periodo_gestion;  
            $oficio_numero_informe = $auditoria->informepliegos->numero_informe;
        }
        
        if($auditoria->acto_fiscalizacion=='Legalidad'){
                if(count($auditoria->accionesrecomendaciones)>0){ /**PUEDO PONERLO TANTO PARA IF Y CF */
                    $siRecomendaciones01 = "y 54 Bis";
                    $siRecomendaciones03 = " X,";
                    $siRecomendaciones04 = " y del Proceso de Atención a las Recomendaciones correspondientes";
                    $siEtapaAclaracion02 = "así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones, o en su caso, justificara su improcedencia dentro del plazo convenido;";

                }
                if(count($auditoria->accionespras)>0){
                    $siPRAS01 = ", XIX";
                }
                if(count($auditoria->accionespo)>0){
                    $siPo01 = "de la Etapa de Aclaración del Pliego de Observaciones";
                    $siEtapaAclaracion01 = "en un plazo de 30 (Treinta) días hábiles, solventara, aclarara o manifestara lo que a su derecho conviniera en relación al contenido de los Pliegos de Observaciones;";

                }
                if(count($auditoria->totalNOsolventadopliegos)>0){
                    $siPliegoNoS01 = "XVIII ";
                }

                if(count($auditoria->informes)>0 && "No hay segunda Etapa"=="No hay segunda Etapa"){
                    $siUnicoInforme01 = "Derivado del seguimiento al Informe de Auditoría correspondiente a la Auditoría de {$auditoria->acto_fiscalizacion}, practicada {$textoDocumento} , por el período comprendido del ".convertirPeriodo($auditoria->periodo_revision)."; en fecha ${fecha_oficio}  le fue notificado a la entidad fiscalizada, el oficio número ${numero_oficio} , por medio del cual, se le hizo del conocimiento la emisión del Acuerdo de Radicación respectivo y se le citó a comparecencia para el efecto de que en ella se puntualizaran las observaciones  de mérito y se pusiera a la vista el Expediente Técnico para su consulta e informarle de la apertura ${siPo01} ${siRecomendaciones04}, con el objeto de que ${siEtapaAclaracion01}  ${siEtapaAclaracion02} en ese sentido, remito a usted el Informe de Seguimiento por el que se notifica la situación que guardan las observaciones, constante de {$fojasU} ({$fojasULetras}) fojas útiles.";
                }                   
                if('siSegundaEtapa01'=='no'){
                    $siSegundaEtapa01 = "En seguimiento al oficio número X {oficioNumero_informe}X y notificado en fecha X {fecha_not_informe}X a esa entidad fiscalizada respecto de la Auditoría de {$auditoria->acto_fiscalizacion}, practicada a XXXX, por el período comprendido del XXX; remito a usted el Informe de Seguimiento por el que se notifica la situación que guardan las observaciones, constante de XXX fojas útiles.";
                    $siPlazoSegundaEtapa = "Cabe señalar que las observaciones mencionadas en el párrafo que antecede, se encuentran sustentadas con las constancias agregadas en los expedientes técnicos respectivos, mismos que la entidad fiscalizada a través de sus titulares, o en su caso, representantes legales o enlaces debidamente autorizados, pueden consultar en las oficinas que ocupa esta Unidad, sito en Avenida José María Pino Suárez Sur, núms. 104, 106 y 108, Colonia Cinco de Mayo, Toluca, Estado de México, C.P. 50090, con cita que deberá ser agendada en el número de teléfono (722) 167 8450 (opción 3).";
                }

                $replacements = array(
                    array('remitente' => "{$remitente}", 'remitente_cargo' => "{$remitente_cargo}", 'entidad01' => "{$entidad01}", 'remitente_domicilio' => "{$remitente_domicilio}", 'periodo_gestion' => "{$periodo_gestion}", 'ambito01' =>$ambito01 , 'frac'=>$frac,'siPRAS01' => "{$siPRAS01}",'siRecomendaciones01' => "{$siRecomendaciones01}",
                    'siRecomendaciones03' => "{$siRecomendaciones03}",'siPliegoNoS01' => "{$siPliegoNoS01}" , 'siUnicoInforme01' => "{$siUnicoInforme01}", 'siSegundaEtapa01' => "{$siSegundaEtapa01}",'siPlazoSegundaEtapa' => "{$siPlazoSegundaEtapa}", 'numero_orden' => "{$auditoria->numero_orden}",),
                    );
                
                $template=new TemplateProcessor('bases-word/IS/LEGALIDAD/Of_IS_01.docx');
                $template->setValue('anio',date("Y"));
                $template->setValue('dia', Carbon::now()->day);
                $template->setValue('mes',$mes);
                $template->setValue('orden_auditoria',$auditoria->numero_orden);
                $template->setValue('numero_auditoria',$auditoria->numero_auditoria); 
                $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
                $template->setValue('oficio_numero_informe', $oficio_numero_informe);
                $template->setValue('inicialesJD', $inicialesJD);
                $template->setValue('inicialesLM', $inicialesLM);
                $template->setValue('inicialesStaff', $inicialesStaff);
                $template->setValue('inicialesD', $inicialesD);

                $template->cloneBlock('block', 1, true, false, $replacements);

                $nombreword='Of. IS';
                $template->saveAs($nombreword.'.docx');
            }
            return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
    }
}
