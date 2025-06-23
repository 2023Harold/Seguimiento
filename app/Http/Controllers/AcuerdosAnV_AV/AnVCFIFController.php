<?php

namespace App\Http\Controllers\AcuerdosAnV_AV;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\ListadoEntidades;
use App\Models\User;
use DB;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Table;


class AnVCFIFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //dd("index acuerdos");
        $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('folios.acuerdosanvav.index', compact('auditoria'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FolioCrr $folio)
    {
        DD(2);
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FolioCrr $folio)
    {
        $auditoria = Auditoria::find(getSession('auditoria_id'));
        
        return view('folios.foliosanexos.form', compact('auditoria','folio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RemitentesFolio $remitente)
    {
        $folioscrr = FolioCrr::where('id', $remitente->folio_id)->first();
        $request['usuario_modificacion_id'] = auth()->id();
        $remitente->update($request->all());

        setMessage('El Remitente del Folio:'.$folioscrr->folio.' ha sido actualizado');

        return redirect()->route('remitentes.index');
		
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


    public function export(){
        $inicialesA=""; $inicialesD=''; $inicialesJD=''; $inicialesLP = ''; $inicialesATUS = '';
        $auditoria=Auditoria::find(getSession('auditoria_id'));
        $fechaactual=fechaaletra(now());
        $fechaactual = strtolower($fechaactual);
        $nATUS = User::select('segusers.name', 'segusers.puesto')->where('siglas_rol','ATUS')->get()->first();
        //$nombre=auth()->user()->name;
        $nD = $auditoria->directorasignado->name;
        $nJD = $auditoria->jefedepartamentoencargado->name;
        $nA = $auditoria->analistacp->name;
        $nLP = $auditoria->lidercp->name;

        $inicialesD = Iniciales($nD);
        $inicialesJD = Iniciales($nJD);
        $inicialesA = Iniciales($nA);
        $inicialesLP = Iniciales($nLP);
        $inicialesATUS = Iniciales($nATUS->name);

        if(count($auditoria->accionesrecomendaciones)>0){
                $siRecomendaciones01 = "y del Proceso de Atención a las Recomendaciones correspondientes";
                
            }
        $entidad = ListadoEntidades::where('no_auditoria', $auditoria->numero_auditoria)->where('cuenta_publica', $auditoria->cuenta_publica)->select('entidades', 'textos_doc')->first();
            $nombreEntidad = $entidad->entidades;
            $entidad01 = $nombreEntidad;
            $textoDocumento = $entidad->textos_doc;

        $periodoLetras = convertirPeriodo($auditoria->periodo_revision);
        //dd($auditoria);

        $phpWord = new PhpWord();
        // Configuración general del documento
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));
        $section = $phpWord->addSection([
                'footerHeight' => 80, 
                'marginBottom' => 20, 
            ]);

        // Definir un estilo con idioma español
        $phpWord->addFontStyle(
            'spanishStyle',
            [
                'name' => 'Regesto Grotesk',
                
                'lang' => Language::ES_ES // Aquí aplicamos el idioma al texto
            ]
        );
        $phpWord->setDefaultFontName('Regesto Grotesk');
        // === ENCABEZADO === //
            $header = $section->addHeader();
            $header->addImage(public_path('assets\img\logosv_2025.png'), [
                'width' => 230,
                'height' => 70,
                'alignment' => Jc::CENTER,
            ]);
            $header->addText('“2025. Bicentenario de la vida municipal en el Estado de México”.', ['bold' => true, 'size' => 8], ['alignment' => 'center']);
            $header->addText('Unidad de Seguimiento', ['size' => 8], ['alignment' => 'center']);
            $header->addText('Número de Expediente: XXXX', ['bold' => true, 'size' => 10], ['alignment' => 'right']);
        // === FIN ENCABEZADO === //

        // === PIE DE PÁGINA ==
            $footer = $section->addFooter();
            $table = $footer->addTable([
                'borderSize' => 0,
                'borderColor' => 'FFFFFF',
                'alignment' => Jc::CENTER,
                'width' => 11200,
                'unit' => 'dxa', 
            ]);
            $table->addRow();
            $table->addCell(11200, ['gridSpan' => 3, 'bgColor' => 'bb945c'])->addText("1", ['size' => 1]);
            $table->addRow();
            $table->addCell(11200, ['gridSpan' => 3, 'bgColor' => '96134b','borderColor' => '96134b','borderTopSize' => 0,])->addText("1", ['size' => 1]);
            
            $table->addRow();
            $cell = $table->addCell(4700, ['valign' => 'center','bgColor' => '96134b','borderColor' => '96134b','borderTopSize' => 0,]);
            $text = $cell->addTextRun(['alignment' => Jc::BOTH, 'indentation' => ['left' => 500]]);
            $text->addText("osfem.gob.mx</w:t><w:br/><w:t>CongresoEdomex.gob.mx",['bold' => true, 'size' => 11,'color' => 'FFFFFF', 'alignment' => Jc::BOTH, 'indentation' => ['left' => 500]]);
            
            $table->addCell(1900, ['valign' => 'center','bgColor' => '96134b','borderColor' => '96134b','borderTopSize' => 0, 'borderLeftSize' => 0,'borderRightSize' => 0])->addImage(public_path('assets/img/indumentaria.png'), ['width' => 30,'height' => 30,'alignment' => Jc::CENTER]);
            
            $cell = $table->addCell(4600, ['valign' => 'center','bgColor' => '96134b','borderColor' => '96134b','borderTopSize' => 0,]);
            $text = $cell->addTextRun(['alignment' => Jc::END, 'indentation' => ['left' => 100, 'right' => 200]]);
            $text->addText("Av. José María Pino Suárez Sur, núms. 104, 106 y 108, Col. Cinco de Mayo, Toluca, Estado de México, C.P. 50090.</w:t><w:br/><w:t>Tel. 722 167 84 50 (Opción 3)",['size' => 8,'color' => 'FFFFFF', 'alignment' => Jc::BOTH, 'indentation' => ['left' => 500, 'right' => 500]]);

            $table->addRow();
            $cell = $table->addCell(11200, ['gridSpan' => 3, 'valign' => 'center','bgColor' => '96134b','borderColor' => '96134b','borderTopSize' => 0,'borderBottomSize' => 0]);
            $text = $cell->addTextRun(['alignment' => Jc::CENTER, 'indentation' => ['left' => 500, 'right' => 500]]);
            $text->addText("Este documento y anexos, en su caso, serán tratados conforme a lo previsto en la Ley de Protección de Datos Personales en Posesión de Sujetos Obligados del Estado de México y Municipios.</w:t><w:br/><w:t>Para mayor información, visite el aviso de privacidad en www.osfem.gob.mx.",['size' => 7,'color' => 'FFFFFF']);
            $table->addRow();
            $cell->addPreserveText('Página {PAGE} de {NUMPAGES}', array('color' => 'white','size' => 8,),array('align' => 'center',));
        // === FIN PIE DE PAGINA === ///

        ///=== BODY ===///
        if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero'){
            // Opcional: estilos
            $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 10, 'name' => 'Regesto Grotesk']);
            $phpWord->addParagraphStyle('justificado', ['alignment' => Jc::BOTH, 'spacing' => 120]);

            $textRun = $section->addTextRun('justificado');
            $textRun->addText("V I S T O ", ['bold' => true]);
            $textRun->addText(" el XXX número XXX, de fecha XXXX, signado por XXX, XXXX, durante la administración XXX y documentación adjunta, vinculado con los autos del expediente al rubro señalado y que fue presentado
            ante el Departamento de Oficialía de Partes de este Órgano Superior de Fiscalización del Estado de México, el XXXX, registrado con el número de folio del Sistema de Gestión de Correspondencia XXX;
            por medio del cual, se presenta información relacionada con la ", []);
            $textRun->addText("Auditoría de Cumplimiento Financiero, practicada a XXXXX, por el período comprendido XXXXXX y ordenada mediante oficio número XXX; ", ['bold' => true]);
            $textRun->addText(" por lo cual, el Titular de la Unidad de Seguimiento de este Órgano Técnico, emite el presente Acuerdo al tenor de los siguientes:", []);

            $section->addText("C O N S I D E R A N D O S", ['bold' => true, 'underline' => 'single'], ['alignment' => 'center']);

            $textRun = $section->addTextRun('justificado');
            $textRun->addText("I. ", ['bold' => true]);
            $textRun->addText("Que la Unidad de Seguimiento es competente para atender el XXX de cuenta en la Etapa de Aclaración ${siRecomendaciones01}, de conformidad con lo dispuesto en los artículos 53 y 54 de la Ley de Fiscalización Superior del Estado de México y; 23 y 47 fracciones III, XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México.", []);

            $textRun = $section->addTextRun('justificado');
            $textRun->addText("II. ", ['bold' => true]);
            $textRun->addText("Que para una mejor comprensión del asunto, se debe precisar que en fecha {XXX}, la Auditoría Especial de Cumplimiento Financiero e Inversión Física, notificó al {XXX}, el oficio número {XXX}, por medio del cual, se le hizo entrega del Informe de Auditoría respectivo, a fin de darle a conocer las observaciones emitidas con motivo de la ");
            $textRun->addText("Auditoría de Cumplimiento Financiero, practicada ${textoDocumento}, por el período comprendido ${periodoLetras} y ordenada mediante oficio número {$auditoria->numero_orden}.", ['bold' => true]);
            
            $textRun = $section->addTextRun('justificado');

            // Definir el estilo de resaltado (amarillo en este caso)
            $highlightStyle = array('bgColor' => 'FFFF00');

            $textRun->addText("III. ", ['bold' => true]);
            $textRun->addText("En ese orden de ideas, el día ".fecha(optional($auditoria->radicacion)->fecha_oficio_acuerdo).", la Unidad de Seguimiento notificó a {XXX}, el oficio número".$auditoria->radicacion->oficio_acuerdo.", por medio del cual, se le hizo del conocimiento la emisión del Acuerdo de Radicación respectivo y se le citó a comparecencia para el efecto de que en ella se");
            $textRun->addText(" puntualizaran las observaciones ",['bgColor' => 'FFFF00'],[]);
            $textRun->addText("de mérito y se pusiera a la vista el Expediente Técnico para su consulta e informarle de la apertura de la Etapa de Aclaración ".$siRecomendaciones01.", con el objeto de que en un plazo de 30 (Treinta) días hábiles, solventara, aclarara o manifestara lo que a su derecho conviniera en relación al contenido de"); 
            $textRun->addText(" las acciones aludidas; ",['bgColor' => 'FFFF00'],[]);
            if(count($auditoria->accionesrecomendaciones)>0){ 
                $textRun->addText(" así como, se precisaran las mejoras realizadas y las acciones emprendidas en relación a ");
                $textRun->addText("recomendaciones determinadas",['bgColor' => 'FFFF00'],[]);
                $textRun->addText(", o en su caso, justificara su improcedencia;");
            } 
            $textRun->addText("plazo que {XXX el día XXXX}.");

            $textRun = $section->addTextRun('justificado');
            $textRun->addText("No obstante lo anterior, en fecha {XXXXX}, se presentó ante el Departamento de Oficialía de Partes de este Órgano Superior de Fiscalización del Estado de México, el {XXX} número"); 
            $textRun->addText("XXXX",['bold' => true]); 
            $textRun->addText("constante de {XXX} fojas útiles, {así como la documentación adjunta}; de fecha {XXXXXX}, signado por {XXXXXX, XXXXX}, {México}, durante la administración {XXXX}, al cual, se le registró en el Sistema de Gestión de Correspondencia con el folio transcrito en el proemio.", []);
            
            $textRun = $section->addTextRun('justificado');
            $textRun->addText("Por lo antes expuesto, se: ");

            $section->addText("A C U E R D A", ['bold' => true], ['alignment' => 'center']);
            /**xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
            $textRun = $section->addTextRun('justificado');
            $textRun->addText("PRIMERO. Téngase por ", ['bold' => true], 'justificado');
            if("si anexos"=="si anexos"){
                $textRun->addText("presentado ", ['bold' => true], 'justificado');
                $textRun->addText("a XXXXXX," ); //nombre quien ingresa los oficios u escritos
            }
            $textRun->addText("XXXXX, México, durante la administración XXXX, con el XXX de cuenta"); 
            if("si anexos"=="si anexos"){
                $textRun->addText("y la documentación adjunta."); 
            }
            $textRun->addText("."); 
            /**xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
            $textRun = $section->addTextRun('justificado');
            $textRun->addText("SEGUNDO. ", ['bold' => true], 'justificado');
            $textRun->addText("En términos del primero párrafo del CONSIDERANDO III del presente Acuerdo, el plazo otorgado a la entidad fiscalizada para que presentara los elementos, documentos y datos fehacientes tendientes a aclarar, solventar o bien para que manifestara lo que a su derecho conviniera en relación al contenido de las acciones aludidas ");
            if(count($auditoria->accionesrecomendaciones)>0){ 
                $textRun->addText("y; a precisar las mejoras realizadas y las acciones emprendidas de las recomendaciones que le fueron determinadas, o en su caso, justificar su improcedencia ; ha quedado fenecido; por lo que el XXX  del que se da cuenta ");
            }
            if("si anexos"=="si anexos"){
                $textRun->addText("y la documentación adjunta, ");
            }
            $textRun->addText("no " ); 
            if("si anexos"=="si anexos"){
                $textRun->addText("serán considerados para su análisis por ser presentados ",['bgColor' => 'FFFF00']);
            }
            $textRun->addText( "de forma extemporánea, sin que ello pudiera considerarse que se deje en estado de indefensión a la entidad, porque habiendo tenido la oportunidad de hacer uso de su derecho, no lo ejerció en tiempo y forma, operando la preclusión en su perjuicio.");
            /**xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
            if("si desahogo"=="si desahogo"){
                $textRun = $section->addTextRun('justificado');
                $textRun->addText("TERCERO. Glósese ", ['bold' => true], 'justificado');
                $textRun->addText("el XXX de cuenta ");
                if("si anexos"=="si anexos"){
                    $textRun->addText("y la documentación adjunta"); 
                }
                $textRun->addText("a los autos del Expediente número XXX. ",);
            }
            /**xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
            if("si UI"=="si UI"){
                $textRun = $section->addTextRun('justificado');
                $textRun->addText("TERCERO. Remítase ", ['bold' => true], 'justificado');
                $textRun->addText("el XXX de cuenta ");
                if("si anexos"=="si anexos"){
                    $textRun->addText("y la documentación adjunta "); 
                }
                $textRun->addText("a la Unidad de Investigación de este Órgano Superior de Fiscalización del Estado de México, para que en el ámbito de su competencia, determine lo conducente en términos de la Ley General de Responsabilidades Administrativas, la Ley de Responsabilidades Administrativas del Estado de México y Municipios, la Ley de Fiscalización Superior del Estado de México y demás disposiciones jurídicas aplicables.");
            }
            
            /**xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
            $textRun = $section->addTextRun('justificado');
            $textRun->addText("CUARTO. Notifíquese ", ['bold' => true], 'justificado');
            $textRun->addText("el presente Acuerdo a la entidad fiscalizada.",);
            /**xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
            $textRun = $section->addTextRun('justificado');
            $textRun->addText("Así lo acordó y firma Luis Ignacio Sierra Villa, Titular de la Unidad de Seguimiento del Órgano Superior de Fiscalización del Estado de México, de conformidad con lo dispuesto en los artículos 21 y 54 de la Ley de Fiscalización Superior del Estado de México; y; 4, 23 fracciones XIX y XLIV y 47 fracciones XII y XX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, a los " .$fechaactual);
            /**xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
            $section->addTextBreak(8); // Inserta dos saltos de línea
            $textRun = $section->addTextRun(['justificado']);
            $textRun->addText("Elaboró: ",['size' => 7]);
            $textRun->addText($inicialesLP,['size' => 7]);	
            $textRun->addText("                            Revisó:  ",['size' => 7]);	
            $textRun->addText($inicialesATUS."/".$inicialesJD."/".$inicialesA,['size' => 7]);
            $textRun->addText("                            Validó: ",['size' => 7]);
            $textRun->addText($inicialesD,['size' => 7]);
            
            $nombreDocumento = "AnV";
        }
        ///=== FIN BODY ===///
                        
        $tempFile = tempnam(sys_get_temp_dir(), 'word');
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        // Descargar
        return response()->download($tempFile, $nombreDocumento.'.docx')->deleteFileAfterSend(true);
    }

}
