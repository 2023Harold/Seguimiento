<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;


class PacController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('pacanalista.index', compact('request'));
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
    /**Analista */

    public function mot()
    {
            $params = [
            'direccion'=>'unidadAdministrativa0', 
            'departamento'=>'unidadAdministrativa', 
            'numero_memo'=>'OSFEM/US/DSX/DSXX/XXX/202X',
            'receptor'=>'A quien va dirigido',
            'cargo_receptor'=>'Cargo de a quién va dirigido',
            'numero_auditoria' =>'numero_auditoria',
            'entidad_fiscalizable' =>'MUNICIPIO',
            'periodo_revision' =>'periodo_revision',
            'numero_memo_atencion'=>'XXXXX',
            'fecha_memo_atencion'=>'XXXXX',
            'numero_resarcitorio'=>'OSFEM/UAJ/XX/XX/XX',
            'numero_recurso_revision'=>'OSFEM/UAJ/XX/XX/XX',
            'numero_acumulado'=>'XXX', 
            'numero_oficio'=>'XXX'
            ];

        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Analista/mot', $params, 'docx');       
        //return response()->download($preconstancia);

        $template=new TemplateProcessor('bases-word/GENERALES/5. ANALISTA/1. MOT.docx');
        $nombreword='1. MOT';
        $template->saveAs($nombreword.'.docx');
        
         return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }

    public function fc()
    {
            $params = [
            'direccion'=>'unidadAdministrativa', 
            'departamento'=>'unidadAdministrativa', 
            'numero_expediente'=>'OSFEM/US/DSX/DSXX/XXX/202X',
            'fojas'=>'150',
            'dias_letra'=>'VEINTE',
            'mes_letra'=>'JUNIO',
            'anio_letra'=>'DOS MIL VEINTICUATRO',
            'nombre_director'=>'director_name',
            'cargo_director'=>'director_puesto',
            'nombre_jefe'=>'jefe_name',
            'cargo_jefe'=>'jefe_puesto',
            ];

        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Analista/fc', $params, 'docx');       
        //return response()->download($preconstancia);
        $template=new TemplateProcessor('bases-word/GENERALES/5. ANALISTA/2. FC.docx');
        $nombreword='2. FC';
        $template->saveAs($nombreword.'.docx');
        
         return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
    
    public function fccd()
    {
        $params = [
            'direccion'=>'director_unidadAdministrativa', 
            'departamento'=>'unidadAdministrativa_descripcion', 
            'numero_expediente'=>'OSFEM/US/DSX/DSXX/XXX/202X',
            'capacidad'=>'1000',
            'fojas_desde'=>'150',
            'fojas_hasta'=>'190',
            'espacio_ocupado'=>'500',
            'dias_letra'=>'veinte',
            'mes_letra'=>'junio',
            'anio_letra'=>'dos mil veinticuatro',
            'nombre_director'=>'director->name',            
            'nombre_jefe'=>'jefe->name',
        ];

        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Analista/fccd', $params, 'docx');       
        //return response()->download($preconstancia);
        $template=new TemplateProcessor('bases-word/GENERALES/5. ANALISTA/3. FC CD.docx');
        $nombreword='3. FC CD';
        $template->saveAs($nombreword.'.docx');
        
         return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);

    }

    /**Lider */

    public function ar()
    {
        $params = [
            'memo_numero'=>'XXXXXXX',
            'presentado_por'=>'XXXXXX',
            'presentado_por2'=>'XXXX',
            'auditoria'=>'XXXXX',
            'practicada_a'=>'XXXX',
            'periodo_comprendido'=>'XXXXXXX',
            'expediente_auditoria'=>'XXXXXX',
            'practicada_a2'=>'XXXX',
            'oficio_numero'=>'3206513065154',
            'periodo_comprendido2'=>'XXXXXXX',
            'numero_progresivo'=>'jasperadmin',
            'control_de'=>'XXXx',
            'cita_a1'=>'XXXXXXX',
            'cita_a2'=>'XXXXXXX',
            'materia_de'=>'XXXX',
            'termino1'=>'XXXXXXXX',
            'termino2'=>'XXXXXXXX',
            'cierre_auditoria'=>'XXXXX',
            'control_de2'=>'XXxxxXXXxx',
            'dia_letra'=>'doce',
            'mes_letra'=>'enero',
            'anio_letra'=>'veinticuatro',
            'lisv'=>'XXXXXX',         

        ];    
		
		$auditoria=Auditoria::find(getSession('auditoria_id'));
		$preconstancia="";
		/*Cumplimiento Financero*/
		if($auditoria->tipo_auditoria_id==1){
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/CUMPLIMIENTO_FINANCIERO/LIDER/1.AR', $params, 'docx');
            $template=new TemplateProcessor('bases-word/1. CUMPLIMIENTO FINANCIERO/1. AR.docx');
            $nombreword='1. AR';
            $template->saveAs($nombreword.'.docx');

		}elseif($auditoria->tipo_auditoria_id==2){
			/*Inversion Fisica*/
            $template=new TemplateProcessor('bases-word/3. INVERSIÓN FÍSICA/1. AR.docx');
            $nombreword='1. AR';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/INVERSION_FISICA/LIDER/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==3){
			/*Desempeño*/
            $template=new TemplateProcessor('bases-word/2. DESEMPEÑO/1. AR.docx');
            $nombreword='1. AR';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/DESEMPEÑO/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==4){
			/*Legalidad*/
            $template=new TemplateProcessor('bases-word/4. LEGALIDAD/1. AR.docx');
            $nombreword='1. AR';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/LEGALIDAD/LIDER/1.AR', $params, 'docx');
		}
              

        //return response()->download($preconstancia);
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
    }

    /**XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX */
    public function ofiaar()
    {

        $params = [
            
            'mes'=>'06',
            'anio'=>'2024',
            'orden_auditoria'=>'XXXXXXX',
            'numero_auditoria'=>'OSFEM/X/XXXX/202X',
            'numero_expediente'=>'OSFEM/X/XXXX/202X',
            'oficio_numero'=>'OSFEM/US/XXX/202X',
            'nombre_notificado'=>'Nombre del notificado',
            'cargo_notificado'=>'Cargo del notificado',
            'domicilio'=>'Domicilio',
            'total_fojas'=>'150',
            'entidad_fiscalizable'=>'Municipio de Acolman',
            'periodo_revision'=>'1 de enero al 31 de diciembre del 2023',
            'dias_letra'=>'veinte',
            'mes_letra'=>'junio',
            'anio_letra'=>'dos mil veinticuatro',
        ];


        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofiaar', $params, 'docx');       

        return response()->download($preconstancia);
        
    }
    
    public function ofaroics()
    {
        $params = [
            'mes'=>'06',
            'anio'=>'2024',
            'orden_auditoria'=>'XXXXXXX',
            'numero_auditoria'=>'OSFEM/X/XXXX/202X',
            'numero_expediente'=>'OSFEM/X/XXXX/202X',
            'oficio_numero'=>'OSFEM/US/XXX/202X',
            'nombre_notificado'=>'Nombre del notificado',
            'cargo_notificado'=>'Cargo del notificado',
            'domicilio'=>'Domicilio',
            'entidad_fiscalizable'=>'Municipio de Acolman',
            'periodo_revision'=>'1 de enero al 31 de diciembre del 2023',
            'orden_auditoria_vinculada'=>'XXXXXXX',
            'autoridad'=>'Municipal o Estatal',
            'plural_singular'=>'las Promociones',
            'total_fojas_utiles'=>'150',
            'total_pras'=>'15',
            'clave'=>'0023612',
            'legajo'=>'30000',
            'fojas_utiles_legajo'=>'100',
            'legajos_copias'=>'10',
            'fojas_utiles_copia'=>'150',
        ];

        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofar_oics',$params, 'docx');       

        $auditoria=Auditoria::find(getSession('auditoria_id'));
		$preconstancia="";
		/*Cumplimiento Financero*/
		if($auditoria->tipo_auditoria_id==1){
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/CUMPLIMIENTO_FINANCIERO/LIDER/1.AR', $params, 'docx');
            $template=new TemplateProcessor('bases-word/1. CUMPLIMIENTO FINANCIERO/3. Of. AR_OIC´s.docx');
            $nombreword='3. Of. AR_OIC´s';
            $template->saveAs($nombreword.'.docx');

		}elseif($auditoria->tipo_auditoria_id==2){
			/*Inversion Fisica*/
            $template=new TemplateProcessor('bases-word/3. INVERSIÓN FÍSICA/3. Of. AR_OIC´s.docx');
            $nombreword='3. Of. AR_OIC´s';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/INVERSION_FISICA/LIDER/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==3){
			/*Desempeño*/
            $template=new TemplateProcessor('bases-word/2. DESEMPEÑO/3. Of. AR_OIC´s.docx');
            $nombreword='3. Of. AR_OIC´s';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/DESEMPEÑO/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==4){
			/*Legalidad*/
            $template=new TemplateProcessor('bases-word/4. LEGALIDAD/3. Of. AR_OIC´s.docx');
            $nombreword='3. Of. AR_OIC´s';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/LEGALIDAD/LIDER/1.AR', $params, 'docx');
		}           
        //return response()->download($preconstancia);
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
    public function ac()
    {
        $params = [
            'direccion'=>'A', 
            'departamento'=>'unidadAdministrativa',            
            'orden_auditoria'=>'XXXXXXX',
            'numero_auditoria'=>'OSFEM/X/XXXX/202X',
            'numero_expediente'=>'OSFEM/X/XXXX/202X',
            'hora'=>'13:00',
            'minutos'=>'30',
            'dia_letra'=>'quince',
            'mes_letra'=>'mayo',
            'anio_letra'=>'dos mil veinticuatro',
            'nombre_director'=>'name',            
            'nombre_jefe'=>'jefe->name',
            'nombre_asistido'=>'nombre de la persona de asisitencia',
            'numero_gafete'=>'0010020MNR3326',
            'nombre_compareciente'=>'Felipe Diaz Ortiz',
            'cargo_compareciente'=>'desarrollador',
            'persona_representada'=>'Maria Nava Jimenez',
            'numero_oficio_poder'=>'01OF852CP',
            'fecha_oficio_poder'=>'22/01/2023',
            'entidad_fiscalizable'=>'Municipio de Acolman',
            'periodo_revision'=>'1 de enero al 31 de diciembre del 2023',
            'testigo_nombre'=>'Santiago Azuara',
            'testigo_cargo'=>'Tesorero',
            'uso_palabra'=>'Maria',
            'manifesto_nombre'=>'Manifesto nombre',
            'manifesto_cargo'=>'Manifesto cargo',
            'nombre_presente'=>'Julio',
            'cargo_presente'=>'Tesorero',
            'clave_presente'=>'001003005009',
            'representado'=>'Representado',
            'representado_cargo'=>'Cargo',
            'correo_representado'=>'correo',
            'copia_certificada'=>'Documento',
            'primero_presentada'=>'xxx',
            'segundo_certificadas'=>'xxx',
            'tercero_representante'=>'(Representante)',
            'tercero_clave'=>'0020040078523',
            'tercero_compareciente'=>'Hugo',
            'quinto_dia_ec'=>'veinte',
            'quinto_mes_ec'=>'mayo',
            'quinto_anio_ec'=>'dos mil veintidós',
            'quinto_entidad_fiscalizable'=>'Municipio de Acolman',
            'quinto_dia_fenece'=>'veinte',
            'quinto_mes_fenece'=>'mayo',
            'quinto_anio_fenece'=>'dos mil veintidós',
            'sexto_num_auditoria'=>'',
            'septimo_fecha'=>'22/05/2023',
            'septimo_numero'=>'002/003/2023',
            'septimo_entidad'=>'Municipio de Acolman',
            'septimo_claveinicial'=>'12566',
            'septimo_clavefinal'=>'12599',
            'unico_vierte'=>'XXXX',
            'unico_autorizado'=>'XXXX',
            'unico_encuanto'=>'XXXX',
            'unico_entidad'=>'XXXX',
            'termino_horas'=>'14',
            'termino_minutos'=>'30',
            'firma_director'=>'director->name',            
            'firma_jefe'=>'jefe->name',
            'firma_lider'=>'lider->name',
            'firma_entidad_fiscalizable'=>'Municipio de Acolman',
            'firma_presente'=>'Daniel',
            'firma_representado'=>'Jose',
            'firma_testigo1_n'=>'Nombre Testigo1',
            'firma_testigo1_c'=>'Cargo Testigo 1',
            'firma_testigo2_n'=>'Nombre Testigo2',
            'firma_testigo2_c'=>'Cargo Testigo',
            'firma_testigo3_n'=>'Nombre Testigo3',
            'firma_testigo4_n'=>'Nombre Testigo4',
            'firma_testigo5_n'=>'Nombre Testigo5',
        ];
        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ac', [], 'docx');       
        $auditoria=Auditoria::find(getSession('auditoria_id'));
		$preconstancia="";
		/*Cumplimiento Financero*/
		if($auditoria->tipo_auditoria_id==1){
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/CUMPLIMIENTO_FINANCIERO/LIDER/1.AR', $params, 'docx');
            $template=new TemplateProcessor('bases-word/1. CUMPLIMIENTO FINANCIERO/4. AC.docx');
            $nombreword='4. AC';
            $template->saveAs($nombreword.'.docx');

		}elseif($auditoria->tipo_auditoria_id==2){
			/*Inversion Fisica*/
            $template=new TemplateProcessor('bases-word/3. INVERSIÓN FÍSICA/4. AC.docx');
            $nombreword='4. AC';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/INVERSION_FISICA/LIDER/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==3){
			/*Desempeño*/
            $template=new TemplateProcessor('bases-word/2. DESEMPEÑO/4. AC.docx');
            $nombreword='4. AC';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/DESEMPEÑO/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==4){
			/*Legalidad*/
            $template=new TemplateProcessor('bases-word/4. LEGALIDAD/4. AC.docx');
            $nombreword='4. AC';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/LEGALIDAD/LIDER/1.AR', $params, 'docx');
		}           
        //return response()->download($preconstancia);
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
    public function ofai()
    {
        $params = [
            'direccion'=>'A',
            'departamento'=>'B',
            'mes'=>'Mayo',
            'dia'=>'21',
            'anio'=>'2024',
            'orden_auditoria'=>'XXXXXXX',
            'numero_auditoria'=>'OSFEM/US/XXX/202X',
            'numero_expediente'=>'OSFEM/US/XXX/202X',
            'oficio_numero'=>'OSFEM/US/XXX/202X',
            'auditoria'=>'  XXX  ',
            'practicada_a'=>'    xxxxxx   ',
            'periodo_comprendido'=>'   XXXXXXXXXXXXXXxxxxxx   ',
            'orden_oficio'=>'XXXXXX',
            'pliegos_clave'=>'XX',
            'notifico_oficio'=>'xx',
            'fecha'=>'XX/XX/XXXX',
            'fojas'=>'XXXX',
            'constante1'=>'xx',
            'constante2'=>'xx',
            'legajos1'=>'XXXXX',
            'legajos2'=>'XXXXX',
            'constante3'=>'xx',
            'constante4'=>'xx',
            'fojas2'=>'XXXX',
            'fojas3'=>'XXXX',
            'lisv'=>'XXX/XXXX/XXX/XXXX',

        ];    
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofai', $params, 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofroics()
    {

        $params = [
            'direccion'=>'A',
            'departamento'=>'B',
            'mes'=>'Mayo',
            'dia'=>'21',
            'anio'=>'2024',
            'orden_auditoria'=>'XXXXXXX',
            'numero_auditoria'=>'OSFEM/US/XXX/202X',
            'numero_expediente'=>'OSFEM/US/XXX/202X',
            'oficio_numero'=>'OSFEM/US/XXX/202X',
            'cargo'=>'XXXXXXX',
            'domicilio'=>'XXXXXXXXXXXXXXXXXXXX',
            'auditoria'=>'  XXX  ',
            'practicada_a'=>'    xxxxxx   ',
            'periodo_comprendido'=>'   XXXXXXXXXXXXXXxxxxxx   ',
            'orden_oficio'=>'XXXXXX',
            'fecha'=>'XX/XX/XXXX',
            'oficio_numero2'=>'xx',
            'resul_cumplimiento'=>'XXXXX',
            'recomen_clave'=>'XX',
            'constante'=>'xx',            
            'legajo'=>'XXXX',
            'lisv'=>'XXX/XXXX/XXX/XXXX',

        ];
        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofr_oics', $params, 'docx');       

        $auditoria=Auditoria::find(getSession('auditoria_id'));
		$preconstancia="";
		/*Cumplimiento Financero*/
		if($auditoria->tipo_auditoria_id==1){
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/CUMPLIMIENTO_FINANCIERO/LIDER/1.AR', $params, 'docx');
            $template=new TemplateProcessor('bases-word/1. CUMPLIMIENTO FINANCIERO/12. Of. R_OIC´s.docx');
            $nombreword='12. Of. R_OIC´s';
            $template->saveAs($nombreword.'.docx');

		}elseif($auditoria->tipo_auditoria_id==2){
			/*Inversion Fisica*/
            $template=new TemplateProcessor('bases-word/3. INVERSIÓN FÍSICA/12. Of. R_OIC´s.docx');
            $nombreword='12. Of. R_OIC´s';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/INVERSION_FISICA/LIDER/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==3){
			/*Desempeño*/
            $template=new TemplateProcessor('bases-word/2. DESEMPEÑO/12. Of. R_OIC´s.docx');
            $nombreword='12. Of. R_OIC´s';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/DESEMPEÑO/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==4){
			/*Legalidad*/
            $template=new TemplateProcessor('bases-word/4. LEGALIDAD/12. Of. R_OIC´s.docx');
            $nombreword='12. Of. R_OIC´s';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/LEGALIDAD/LIDER/1.AR', $params, 'docx');
		}           
        //return response()->download($preconstancia);
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
    }
    public function ofprasoics()
    {

        $params = [
                'direccion'=>'A',
                'departamento'=>'B',
                'mes'=>'Mayo',
                'dia'=>'21',
                'anio'=>'2024',
                'orden_auditoria'=>'XXXXXXX',
                'numero_auditoria'=>'OSFEM/X/XXXX/202X',
                'numero_expediente '=>'OSFEM/US/XXX/202X',
                'oficio_numero'=>'OSFEM/US/XXX/202X',
                'cargo'=>'XXXXXXX',
                'domicilio'=>'XXXXXXXXXXXXXXXXXXXX',
                'auditoria'=>'_XXX__',
                'practicada_a'=>'____xxxxxx_____',
                'periodo_comprendido'=>'___XXXXXXXXXXXXXXxxxxxx___',
                'oficio_entrega'=>'XXXXXX',
                'resul_cumplimiento'=>'XXXXX',
                'informe_auditoria'=>'XXXXXX',
                'pras_clave'=>'XXXX',
                'fiscalizacion_merito'=>'XXX',
                'legajo'=>'XXXX',
                'constante'=>'xx',
                'legajo2'=>'XXXX',
                'lisv'=>'xxxxxxxxxx',
        ];    
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofpras_oics', $params, 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofsc()
    {
        $params = [
            'direccion'=>'A',
            'departamento'=>'B',
            'mes'=>'Mayo',
            'dia'=>'21',
            'anio'=>'2024',
            'orden_auditoria'=>'XXXXX',
            'numero_auditoria'=>'OSFEM/X/XXXX/202X',
            'numero_expediente'=>'OSFEM/US/XXX/202X',
            'oficio_numero'=>'OSFEM/US/XXX/202X',
            'cargo'=>'XXXXX',
            'domicilio'=>'XXXX',
            'auditoria'=>'xxxx',
            'practicada_a'=>'xxxx',
            'periodo_comprendido'=>'XXX',
            'oficio_orden'=>'XXXXXXX',
            'oficio_notifico'=>'OSFEM/XXXX/XXX/XXX/202X',
            'fecha_notifico'=>'xx/xx/xxxx',
            'resul_cumplimiento'=>'XXXXXX',
            'recomendaciones_clave'=>'xxxxxxx',
            'lisv'=>'xxxxx/xxxxxx/xxxxx',
        ];

        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofsc', $params, 'docx');       

        return response()->download($preconstancia);
       

    }
    public function ofuaj()
    {
        $params = [
            'direccion'=>'A',
            'departamento'=>'B',
            'mes'=>'Mayo',
            'dia'=>'21',
            'anio'=>'2024',
            'orden_auditoria'=>'XXX',
            'numero_auditoria'=>'OSFEM/X/XXXX/202X',
            'numero_expediente'=>'OSFEM/US/XXX/202X',            
            'memo_numero'=>'OSFEM/US/XXX/202X',
            'auditoria'=>'XXX',
            'practicada_a'=>'XXXXXX',
            'periodo_comprendido'=>'XXXXX',
            'oficio_orden'=>'XXXXXXXXX',
            'fecha_notifico'=>'xx/xx/xxxx',
            'oficio_entrega'=>'OSFEM/XXXX/XXX/XXX/202X',
            'auditoria2'=>'XXX',
            'practicada_a2'=>'XXXXXX',
            'periodo_comprendido2'=>'XXXXX',
            'oficio_orden2'=>'XXXXXXXXX',
            'fecha_notifico2'=>'xx/xx/xxxx',
            'notifico'=>'XXX',
            'oficio_numero2'=>'OSFEM/XXXX/XXX/XXX/202X',
            'termino_a'=>'XXX',            
            'termino_b'=>'xxxx',         
            'cierre_auditoria'=>'OSFEM/XX',
            'comparecencia_acta'=>'A.A.C. XX/XX',
            'fenecio'=>'xxx',
            'oficio_numero3'=>' OSFEM/US/XXX',                         
            'fecha_notifico3'=>'XX/XX/XXXX',
            'notifico2'=>'XXXX',            
            'informo_a'=>'xxxxx',
            'informo_b'=>'xxxxx',
            'concluyo_dia'=>'XXX',
            'evidencia'=>'xxxxx',
            'apremio_a'=>'XXXXx',
            'recepcion_oficio'=>'xxx',
            'fecha'=>'xx/xx/xxxxs',
            'constante_a'=>'XXX',
            'constante_b'=>'XXX',
            'lisv'=>'XXXXX',          
        ];
        


        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofuaj', $params, 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ac10()
    {
        $params = [
            'direccion'=>'A',
            'departamento'=>'B',            
            'numero_expediente'=>'XXXXXX',
            'auditoria'=>'XXXX',
            'practicada_a'=>'XXXXXX',
            'periodo_comprendido'=>'XXXXX',
            'orden_oficio'=>'XXXXXXXXX',
            'fecha_'=>'xx/xx/xxxx',        
            'oficio_entrega'=>'OSFEM/XXXX/XXX/XXX/202X',
            'plazo_a'=>'XXX',            
            'plazo_b'=>'xxxx',         
            'cierre_auditoria'=>'OSFEM/XXXX',
            'comparecencia_acta'=>'A.A.C. XXX/XXX',
            'fecha_notifico'=>'XX/XX/XXXX',
            'fecha_remision'=>'XX/XX/XXXX',
            'oficio_numero'=>'OSFEM/US/XXX/XXX/XXX/202X',
            'constante_a'=>'XXXX',
            'constante_b'=>'XXXX',
            'fenecio'=>'xxx',
            'oficio_numero2'=>' OSFEM/US/XXX',                
            'fecha_2'=>'XX/XX/XXXX',
            'oficio_numero3'=>'xxxxx',
            'plazo_impro_a'=>'xxxxx',
            'plazo_impro_b'=>'xxxxx',
            'relacion'=>'XXXXXX',
            'fenecio2'=>'XXXXX',
            'dia_letra'=>'veinticinco',
            'mes_letra'=>'junio',
            'anio_letra'=>'veinticuatro',
             'lisv'=>'XXXXX',            
        ];

       // $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ac2', $params, 'docx');       

        $auditoria=Auditoria::find(getSession('auditoria_id'));
		$preconstancia="";
		/*Cumplimiento Financero*/
		if($auditoria->tipo_auditoria_id==1){
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/CUMPLIMIENTO_FINANCIERO/LIDER/1.AR', $params, 'docx');
            $template=new TemplateProcessor('bases-word/1. CUMPLIMIENTO FINANCIERO/5. AC.docx');
            $nombreword='5. AC';
            $template->saveAs($nombreword.'.docx');

		}elseif($auditoria->tipo_auditoria_id==2){
			/*Inversion Fisica*/
            $template=new TemplateProcessor('bases-word/3. INVERSIÓN FÍSICA/5. AC.docx');
            $nombreword='5. AC';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/INVERSION_FISICA/LIDER/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==3){
			/*Desempeño*/
            $template=new TemplateProcessor('bases-word/2. DESEMPEÑO/5. AC PAR.docx');
            $nombreword='5. AC PAR';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/DESEMPEÑO/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==4){
			/*Legalidad*/
            $template=new TemplateProcessor('bases-word/4. LEGALIDAD/6. AC PAR.docx');
            $nombreword='6. AC PAR';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/LEGALIDAD/LIDER/1.AR', $params, 'docx');
		}           
        //return response()->download($preconstancia);
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
    public function acral()
    {
        $params = [
            'direccion'=>'A',
            'departamento'=>'B',
            'numero_expediente'=>'OSFEM/X/XXXX/202X',
            'auditoria'=>'XXXX',
            'oficio_numero'=>'XXX',
            'practicada_a'=>'xxxxx',
            'periodo_comprendido'=>'10/06/2024',
            'oficio_numero'=>'XXXXXX',
            'fecha'=>'28/09/02024',
            'fecha_entrega'=>'  OSFEM/XXXX/XXX/XXX/202X',
            'oficio_entrega'=>'XXXXXXXX',
            'plazo_a'=>'XXXX/xxxx/XXX',
            'plazo_b'=>'XXXX/xxxx/XXX',
            'cierre_auditoria'=>'XXX',
            'acta_comparecencia'=>'A.A.C. XXX/XXX',
            'fenecio'=>'xx/xx/xxxx',
            'numero_asiganado'=>'XXXXXXXXX',
            'entidad_auditada'=>'XXXXX',
            'fecha_cierre'=>'xx/xx/xxxx',
            'plazo_acordado'=>'21 días',
            'fecha_vencimiento'=>'xx/xx/xxxx',
            'dia_letra'=>'veincuatro',
            'mes_letra'=>'junio',
            'anio'=>'dos mil veincuatro',        
            'lisv'=>'  XXX/XXXX/XXX/XXXX',
            
        ];

        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ac_ral', $params, 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofac()
    {

        $params = [
            'direccion'=>'A',
            'departamento'=>'B',
            'mes'=>'junio',
            'dia'=>'12',
            'anio'=>'25',
            'orden_auditoria'=>'XXXXXXX',
            'numero_auditoria'=>'OSFEM/X/XXXX/202X',
            'numero_expediente'=>'OSFEM/X/XXXX/202X',
            'oficio_numero'=>'OSFEM/US/XXX/202X',
            'asunto'=>'10/06/2024',
            'cargo'=>'XXXXXX',
            'domicilio'=>'28/09/02024',
            'dia_letra'=>'XXXX/xxxx/XXX',
            'mes_letra'=>'XXXX/xxxx/XXX',
            'anio_letra'=>'XXXX/xxxx/XXX',
            'lisv'=>'  XXX/XXXX/XXX/XXXX',
            
        ];

        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofac', $params, 'docx');       

        $auditoria=Auditoria::find(getSession('auditoria_id'));
		$preconstancia="";
		/*Cumplimiento Financero*/
		if($auditoria->tipo_auditoria_id==1){
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/CUMPLIMIENTO_FINANCIERO/LIDER/1.AR', $params, 'docx');
            $template=new TemplateProcessor('bases-word/1. CUMPLIMIENTO FINANCIERO/6. OF. AC.docx');
            $nombreword='6. OF. AC';
            $template->saveAs($nombreword.'.docx');

		}elseif($auditoria->tipo_auditoria_id==2){
			/*Inversion Fisica*/
            $template=new TemplateProcessor('bases-word/3. INVERSIÓN FÍSICA/6. OF. AC.docx');
            $nombreword='6. OF. AC';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/INVERSION_FISICA/LIDER/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==3){
			/*Desempeño*/
            $template=new TemplateProcessor('bases-word/2. DESEMPEÑO/6. OF. AC.docx');
            $nombreword='6. OF. AC';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/DESEMPEÑO/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==4){
			/*Legalidad*/
            $template=new TemplateProcessor('bases-word/4. LEGALIDAD/7. OF. AC.docx');
            $nombreword='7. OF. AC';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/LEGALIDAD/LIDER/1.AR', $params, 'docx');
		}           
        //return response()->download($preconstancia);
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
    public function anv()
    {
          $params = [
            'direccion'=>'A',
            'departamento'=>'B',
            'numero_expediente'=>'OSFEM/UAJ/XX/XX/X',
            'oficio_numero'=>'OSFEM/UAJ/DSSSSXXXS',
            'fecha_oficio'=>'10/06/2024',
            'norma_firma'=>'XXXXXXX',
            'cargo_firma'=>'XXXXXX',
            'admon_firma'=>'tdcxxxxx',
            'fecha_oficialia'=>'28/09/02024',
            'folio_correspondencia'=>'XXXX/xxxx/XXX',
            'descripcion'=>'solo es una pequeña descripción',
            'fecha_notifico'=> '12/06/2024',
            'entidad_fiscalizada'=>'nombre:de_la_entidad fiscalizada',
            'admon_cargo'=>'AGDFikjbn',
            'numoficio_auditoria'=>'000481424',
            'tipo_auditoria'=>'jhdbsafjhbujb ',
            'practicada_a'=>'asdlkgfnkjh dfrgiuj',
            'periodo_comprendido'=>'del 09 al 29 de mayo del 2024',
            'numero_orden'=>'2978256528/421581/0155414124',
            'fecha_notifico2'=>'11/06/2024',
            'numero_oficio2'=>'01285/OSFEM/XXXX/2024',
            'constante1'=>'FNBGKSDJFG',
            'constante2'=>'SZDFRGMLÑK',
            'periodo_comprendido2'=>'OZDSNF RGIHU',
            'oficio_numero3'=>'120102202',
            'concluyo_plazo'=>'28/06/2023',
            'vencio_plazo'=>'26/06/2024',
            'fecha_oficio2'=>'20/07/2024',
            'tipo_auditroria2'=>'SADB GFJ',
            'practicada_a2'=>'SDAFKN KJNFDGJ ARFGJ ',
            'periodo_comprendido3'=>'SDAGKJNKJNRF',
            'oficio_numero4'=>'0ADFRGJBABDSERFG',
            'plazo_desahogo'=>'28/09/2024',
            'plazo_convenido'=>'30/09/2024',
            'anexos'=>'LIV 000/XXX/XXX/2024',
            'fecha_oficios'=>'28/07/2024',
            'oficio_numero5'=>'000/XXX/2025/XXX',
            'numero_fojas'=>'5252',
            'num_variosoficios'=>'147474',
            'numero_fojas2'=>'51751',
            'num_varios2'=>'54454545726/25825',
            'numfojas_contiene'=>'186521852',
            'fechas_oficios'=>'15/02/2024',
            'nombre_ingresa'=>'Diana Mejía Martínez',            
            'cargo_ingresa'=>'Titular',
            'admon_cargo2'=>'bdsfjk',
            'nombre_ingresa2'=>'Fernando Mendoza Huertas',
            'cargo_firma3'=>'Secretario',
            'admon_cargo3'=>'administracion',
            'oficio_numero6'=>'00012896412',
            'motivo_auditoria'=>'BSJHFDBHDEFHBFDHBDF JHASDBFJASD FHJGDFS ASFJBSF',
            'practicada_a3'=>'K HND DUJFJJ RJ',
            'periodo_comprendido4'=>'SKJDKJ KJDJUJ KNDFSGJ',
            'dia_letra'=>'veintincinco ',
            'mes_letra'=>'mayo',
            'anio_letra'=>'dos mil veinticuatro',   
            'lisv'=>'LISV/XXX/XXXX/XXX/XXXX',
           
        ];

        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/anv', $params, 'docx');       

        $auditoria=Auditoria::find(getSession('auditoria_id'));
		$preconstancia="";
		/*Cumplimiento Financero*/
		if($auditoria->tipo_auditoria_id==1){
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/CUMPLIMIENTO_FINANCIERO/LIDER/1.AR', $params, 'docx');
            $template=new TemplateProcessor('bases-word/1. CUMPLIMIENTO FINANCIERO/7. AnV.docx');
            $nombreword='7. AnV';
            $template->saveAs($nombreword.'.docx');

		}elseif($auditoria->tipo_auditoria_id==2){
			/*Inversion Fisica*/
            $template=new TemplateProcessor('bases-word/3. INVERSIÓN FÍSICA/7. AnV.docx');
            $nombreword='7. AnV';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/INVERSION_FISICA/LIDER/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==3){
			/*Desempeño*/
            $template=new TemplateProcessor('bases-word/2. DESEMPEÑO/7. AnV.docx');
            $nombreword='7. AnV';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/DESEMPEÑO/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==4){
			/*Legalidad*/
            $template=new TemplateProcessor('bases-word/4. LEGALIDAD/8. AnV.docx');
            $nombreword='8. AnV';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/LEGALIDAD/LIDER/1.AR', $params, 'docx');
		}           
        //return response()->download($preconstancia);
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
    public function ofanv()
    {
        
        $params = [
        'direccion'=>'unidad_admnistrativa',
        'departamento'=>'AAA',
        'dia'=>'19',
        'mes'=>'Junio',
        'anio'=>'2024',
        'numero_auditoria'=> 'OSFEM/X/XXXX/202X',
        'numero_expediente'=> 'OSFEM/X/XXXX/202X',
        'oficio_numero' => 'OSFEM/US/XXX/202X',
        'cargo'=>'XXXXXX',
        'domicilio'=>'XXXXXX',
        'dia_letra'=>'once',
        'mes_letra'=>'junio',
        'anio_letra'=>'veinticuatro',
        'lisv'=>'XXXX/XXXXX/XXX',
        
        ];

        
        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofanv', $params, 'docx');       

        $auditoria=Auditoria::find(getSession('auditoria_id'));
		$preconstancia="";
		/*Cumplimiento Financero*/
		if($auditoria->tipo_auditoria_id==1){
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/CUMPLIMIENTO_FINANCIERO/LIDER/1.AR', $params, 'docx');
            $template=new TemplateProcessor('bases-word/1. CUMPLIMIENTO FINANCIERO/9. OF. AnV_AV.docx');
            $nombreword='9. OF. AnV_AV';
            $template->saveAs($nombreword.'.docx');

		}elseif($auditoria->tipo_auditoria_id==2){
			/*Inversion Fisica*/
            $template=new TemplateProcessor('bases-word/3. INVERSIÓN FÍSICA/9. OF. AnV_AV.docx');
            $nombreword='9. OF. AnV_AV';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/INVERSION_FISICA/LIDER/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==3){
			/*Desempeño*/
            $template=new TemplateProcessor('bases-word/2. DESEMPEÑO/9. OF. AnV_AV.docx');
            $nombreword='9. OF. AnV_AV';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/DESEMPEÑO/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==4){
			/*Legalidad*/
            $template=new TemplateProcessor('bases-word/4. LEGALIDAD/10. OF. AnV_AV.docx');
            $nombreword='10. OF. AnV_AV';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/LEGALIDAD/LIDER/1.AR', $params, 'docx');
		}           
        //return response()->download($preconstancia);
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
    public function av()
    {
        $params = [
        'direccion'=>'unidad_admnistrativa',
        'departamento'=>'AAA',
        'numero_expediente'=>'1241245287',
        'escrito_numero'=>'000240002',
        'fecha_oficio'=>'12/05/2024',
        'nombre_ingresa'=>'Aldo apellido M Apellido P',
        'cargo_ingresa'=>'Titular',
        'admon_cargo'=>'cargo de la administracion',
        'fecha_ingresa'=>'xx/xx/xxxx',
        'folio1_oficio'=>'000001',
        'folio2_oficio'=>'000002',
        'folio3_oficio'=>'000003',
        'informa_que'=>'xxxxxxxxxx',
        'fecha_notifico'=>'XX/XX/XXXX',
        'entidad_fiscalizada'=>'nombre de la Entidad Fiscalizada',
        'admon_cargo2'=>'cargo de la administración',
        'num_oficioauditoria'=>'00000001272756',
        'fecha_notifico2'=>'XX/XX/XXXX',
        'oficio_numero'=>'000/0282/20125/00001',
        'constante1'=>'kszdbfjk',
        'constante2'=>'kdsfnkjsdafn',
        'auditoria'=>'nombre de la auditoria',
        'practicada_a'=>'XXXXXXXXXX',
        'periodo_comprendido'=>'15 días habiles',
        'oficio_orden'=>'000/0000/000',
        'fenecio'=>'XX/XX/XXXX',
        'fenecio_recomendacion'=>'XX/XX/XXXX',
        'anexos'=>'XXXXX',
        'fecha_oficio2'=>'XX/XX/XXXX',
        'oficios_numero'=>'98545',
        'num_fojas'=>'25',
        'numvarios_oficios'=>'245',
        'numvarios_fojas'=>'554',
        'numvarios_oficios2'=>'15',
        'numvarios_fojas2'=>'356',
        'fechas_oficios'=>'XX/XX/XXXX',
        'nombre_ingresa2'=>'Nombre de la persona que ingreso los oficios',
        'cargo_ingresa2'=>'cargo de la persona que ingreso los oficios',
        'admon_cargo3'=>'cargo de la administracion',
        'nombre_acuerdo'=>'nombre de la persona que hizo el acuerdo',
        'cargo_acuerdo'=>'cargo de la persona que hizo el acuerdo',
        'admon_acuerdo'=>'cargo de la administracion de la persona que hizo el acuerdo',
        'oficio_orden2'=>'XX/xxxxxxx/xxxxxx',
        'dia_letra'=>'once',
        'mes_letra'=>'abril',
        'anio_letra'=>'veinticuatro',
        'lisv'=>'livs XXX/XXXX/XXX/XXXX',
        ];

        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/av', $params, 'docx');       

        $auditoria=Auditoria::find(getSession('auditoria_id'));
		/*Cumplimiento Financero*/
		if($auditoria->tipo_auditoria_id==1){
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/CUMPLIMIENTO_FINANCIERO/LIDER/1.AR', $params, 'docx');
            $template=new TemplateProcessor('bases-word/1. CUMPLIMIENTO FINANCIERO/8. AV.docx');
            $nombreword='8. AV';
            $template->saveAs($nombreword.'.docx');

		}elseif($auditoria->tipo_auditoria_id==2){
			/*Inversion Fisica*/
            $template=new TemplateProcessor('bases-word/3. INVERSIÓN FÍSICA/8. AV.docx');
            $nombreword='8. AV';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/INVERSION_FISICA/LIDER/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==3){
			/*Desempeño*/
            $template=new TemplateProcessor('bases-word/2. DESEMPEÑO/8. AV.docx');
            $nombreword='8. AV';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/DESEMPEÑO/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==4){
			/*Legalidad*/
            $template=new TemplateProcessor('bases-word/4. LEGALIDAD/9. AV.docx');
            $nombreword='9. AV';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/LEGALIDAD/LIDER/1.AR', $params, 'docx');
		}           
        //return response()->download($preconstancia);
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
    public function oi()
    {

        $params = [
            
            'direccion' => 'direccion',
            'departamento' => 'departamento',
            'mes' => 'Junio',
            'dia' => '19',
            'anio' => '2024',
            'orden_auditoria' => 'orden_auditoria ejemplo',
            'numero_auditoria' => 'OSFEM/X/XXXX/202X',
            'numero_expediente' => 'OSFEM/X/XXXX/202X',
            'oficio_numero' => 'OSFEM/US/XXX/202X',
            'motivo_auditoria' => 'XXXXX',
            'asunto' => 'XXXX',
            'cargo' => 'cargo ejemplo',
            'domicilio' => 'domicilio ejemplo',
            'nombre_presente' => 'Fernando Mendoza Huertas',
            'fecha' => 'XXXX',
            'hora_lugar1' => 'XXXX',
            'hora_lugar2' => 'XXXX',
            'obra1' => 'XXXX',
            'obra2' => 'XXXX',
            'lugar1' => 'XXXX',
            'lugar2' => 'XXXX',
            'entidad_fiscalizable' => 'XXXXXXXXXX',
            'nombre1' => 'XXXX1',
            'nombre2' => 'XXXX2',
            'cargo1' => 'XXXX',
            'cargo2' => 'XXXX',
            'auditoria' => 'XXXX',
            'practicada' => 'XXXX',
            'periodo_comprendido' => 'XXXX',
            'oficio_nuemro2' => 'XXXX',
            'lisv' => 'XXX/XXXX/XXX/XXXX',

        ];

        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/oi', $params, 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ofriii()
    {
        $params = [
            
            'direccion' => 'direccion',
            'departamento' => 'departamento',
            'mes' => 'Junio',
            'lugar' => 'lugar_ejemplo',
            'dia' => '19',
            'anio' => '2024',
            'orden_auditoria' => 'orden_auditoria ejemplo',
            'numero_auditoria' => 'OSFEM/X/XXXX/202X',
            'numero_expediente' => 'OSFEM/X/XXXX/202X',
            'oficio_numero' => 'OSFEM/US/XXX/202X"',
            'asunto' => 'asunto ejemplo',
            'enlace_designado1' => 'enlace_designado1 ejemplo',
            'enlace_designado2' => 'enlace_designado2 ejemplo',
            'domicilio' => 'domicilio ejemplo',
            'requiere' => 'requiere ejemplo',
            'lisv' => 'XXX/XXXX/XXX/XXXX',
       
        ];

        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ofriii', [], 'docx');       

        return response()->download($preconstancia);
        
    }
    public function ai()
    {
        $params = [
            
            'direccion' => 'direccion',
            'departamento' => 'departamento',
            'expediente_numero' => 'DXXXXXXXX',
            'lugar' => 'lugar_ejemplo',
            'hora_inicio' => '19:00',
            'fecha_inicio' => '21/01/2024',
            'numero_expediente' => 'numero expediente ejemplo',
            'fecha_expedicion' => '28/06/2024',
            'suscrita_por' => 'suscrita nombre',
            'cargo' => 'cargo_nombre',
            'adscrito' => 'adscrito donde',
            'instruye_practicar' => 'instruye_practicar_ejemplo',
            'entidad_fiscalizable' => 'entidad fiscalizable_ejemplo',
            'dependencia' => 'dependencia_ejemplo',
            'nombre_servidor' => 'nombre_servidor ejemplo',
            'adscripcion' => 'adscripcion ejemplo',
            'tipo_identificacion' => 'tipo_identificacion ejemplo',
            'numero_identificacion' => 'numero_identificacion ejemplo',
            'fecha_expedicion2' => 'fecha_expedicion2 ejemplo',
            'periodo_vigencia' => 'periodo_vigencia ejemplo',
            'organismo_expide' => 'organismo_expide ejemplo',
            'suscrita_sellada' => 'suscrita_sellada ejemplo',
            'estatal_municipal' => 'estatal_municipal ejemplo', 
            'representante' => 'representante ejemplo',  
            'nombre' => 'nombre ejemplo',  
            'puesto' => 'puesto ejemplo',  
            'tipo_identificacion2' => 'tipo_identificacion2 ejemplo',              
            'numero_identificacion2' => 'numero_identificacion2 ejemplo',  
            'organismo_expide2' => 'organismo_expide2 ejemplo',  
            'suscrita_sellada2' => 'suscrita_sellada2 ejemplo',  
            'nombre2' => 'nombre2 ejemplo',  
            'puesto2' => 'puesto2 ejemplo',  
            'tipo_identificacion3' => 'tipo_identificacion3 ejemplo',  
            'numero_identificacion3' => 'numero_identificacion3 ejemplo',  
            'suscrita_sellada3' => 'suscrita_sellada3 ejemplo',
            'horas' => 'horas ejemplo',
            'minutos' => 'minutos ejemplo',  
        ];

        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Lider/ai', $params, 'docx');       

        return response()->download($preconstancia);
        
    }

        /**Jefe*/

    public function ofis()
    {
        $params = [
        
            'direccion'=>'unidad_admnistrativa',
            'departamento'=>'AAA',
            'mes'=>'Junio',
            'dia'=>'19',
            'anio'=>'2024',
            'orden_auditoria'=>'XXXXXX',
            'numero_auditoria'=>'OSFEM/X/XXX/202X',
            'numero_expediente'=>'OSFEM/X/XXX/202X',
            'oficio_numero'=>' OSFEM/US/XXX/202X',
            'informe_auditoria'=>'XXXX/xxx/xxxxx',
            'orden_numero'=>'XXXX/xxx/xxxxx',
            'cargo'=>'XXXXXXX ',
            'domicilio'=>'DRFGNIDGIDGIRDGIRDFFDGLDK',
            'auditoria'=>'ÑLPOLOIKK',
            'practicada_a'=>'DFGHTYEJ ',
            'periodo_comprendido'=>' SDRGSDRG',
            'oficio_numero2'=>'XX/XXX/XXXX/XXXX',
            'constante_a'=>' DFARESF',
            'fojas_utiles'=>' GSDRHG',
            'nombre_subsecretario'=>'NOMBRE_SUBSCRETARIO ',
            'lisv'=>' XXX/XXXX/XXX/XXXX',
            

        ];

        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Jefe/ofis', $params, 'docx');       

        $auditoria=Auditoria::find(getSession('auditoria_id'));
		/*Cumplimiento Financero*/
		if($auditoria->tipo_auditoria_id==1){
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/CUMPLIMIENTO_FINANCIERO/LIDER/1.AR', $params, 'docx');
            $template=new TemplateProcessor('bases-word/1. CUMPLIMIENTO FINANCIERO/11. Of. IS.docx');
            $nombreword='11. Of. IS';
            $template->saveAs($nombreword.'.docx');

		}elseif($auditoria->tipo_auditoria_id==2){
			/*Inversion Fisica*/
            $template=new TemplateProcessor('bases-word/3. INVERSIÓN FÍSICA/11. Of. IS.docx');
            $nombreword='11. Of. IS';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/INVERSION_FISICA/LIDER/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==3){
			/*Desempeño*/
            $template=new TemplateProcessor('bases-word/2. DESEMPEÑO/11. Of. IS PAR.docx');
            $nombreword='11. Of. IS PAR';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/DESEMPEÑO/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==4){
			/*Legalidad*/
            $template=new TemplateProcessor('bases-word/4. LEGALIDAD/14. Of. IS.docx');
            $nombreword='14. Of. IS';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/LEGALIDAD/LIDER/1.AR', $params, 'docx');
		}           
        //return response()->download($preconstancia);
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
    public function is()
    {
    $params = [    
       
        'direccion'=>'unidad_admnistrativa',
        'departamento'=>'B',
        'entidad_fiscalizada'=>'nombre_entidad',
        'tipo_auditoria'=>'XXXXXXXXXXXXXXXX',
        'periodo_fiscalizado'=>'XXXXXXXXXX',
        'programa_anual'=>'XXXXXX',
        'amparo_orden'=>'XXXXXXXX',
        'amparo_fecha'=>'XX/XX/XXXXX',
        'fecha'=>'XXXXXX',
        'auditoria'=>'XXXXXX/XXx/XX',
        'practicada_a'=>'XXXXX',
        'periodo_comprendido'=>'XXXXXX',
        'orden_oficio'=>'XXXXXX',
        'en_fecha'=>'XXXXXXXXXXXXXXX',
        'oficio_numero'=>'XXXXXXXXXX',
        'practicada_a2'=>'XXXXXXXXX',
        'periodo_comprendido2'=>'XXXXXXXXX',
        'orden_oficio2'=>'XXXXXXXX',
        'fecha2'=>'XXXXXX',
        'oficio_numero2'=>'XXXXXXX',
        'auditoria2'=>'XXXXXXXXXXX',
        'practicada_a3'=>'XXXXXXXXX',
        'periodo_comprendido3'=>'XXXXXXXXX',
        'orden_oficio3'=>'XXXXXXXXXXXXX',
        'fecha3'=>'XXXXXXXXXXXXXXXXX',
        'oficio_numero3'=>'XXXXXXXXXXXXX',
        'recomendaciones_plazo'=>'XXXXXXXXXXX',
        'convenio'=>'XXXXXXXXXXXXXXX',
        'cierre_auditoria'=>'XXXXXXXXXXXXXXX',
        'acta_comparecencia'=>'XXXXXXXXXX',
        'fecha_notifico'=>'XXXXXXXXXX',
        'oficio_numero4'=>'XXXXXXXXXX',
        'constante'=>'XXXXXXXXXXX',
        'fojas_utiles'=>'XXXXXXXXX',
        'pliegos_numero'=>'XXXXXXXXXX',
        'diverso_numero'=>'XXXXXXXXXXXXXXXX',
        'oficio_suscrito'=>'XXXXXXXXXXX',
        'caracter_de'=>'XXXXXXXXXXXXX',
        'administracion'=>'XXXXXXXXXXX',
        'presentando_fecha'=>'XXXXXXXXXXXXXXX',
        'folio'=>'XXXX/0000/000',
        'recomendaciones_plazo2'=>'XXXXXXXXXXX',
        'convenio2'=>'XXXXXXX',
        'cierre_auditoria2'=>'XXXXX',
        'acta_comparecencia2'=>'XXXXX',
        'fecha_notifico2'=>'XXXXX',
        'oficio_numero5'=>'XXXXXXXX',
        'constante2'=>'XXXXXX',
        'fojas_utiles2'=>'XXXXXX',
        'pliego_numero'=>'XXXXXX',
        'diverso_numero2'=>'XXXXX',
        'oficio_suscrito2'=>'XXXXXX',
        'en_ caracter'=>'XXXXXX',
        'durante_admon'=>'XXXXXX',
        'presentados_fecha'=>'XXXXX',
        'asignacion_folio'=>'XXXXXXX',
        'acuerdo_fecha'=>'XXXXXXXXXX',
        'expediente'=>'XXXXXXXXX',
        'notificado_numero'=>'XXXXXXXXX',
        'fecha_conocimiento'=>'XXXXXXXXXXXX',
        'oficios_a'=>'XXXXXXXXXXXXX',
        'oficios_b'=>'XXXXXXXXXXXXXXXXXX',
        'oficios_c'=>'XXXXXXXXXXXX',
        'crr_a'=>'XXXXXXXXXX',
        'crr_b'=>'XXXXXXXXX',
        'crr_c'=>'XXXXXXXXXXX',
        'resultados'=>'XXXXXXXXXXXXXXXX',
        'clave_accion'=>'XXXXXXXXXXXXXXXXX',
        'tipo_accion'=>'XXXXXXXXX',
        'estado'=>'XXXXXXXXXXX',
        'observacion_promovida'=>'XXXXXXXX',
        'merito_identifico'=>'XXXXXXXXXXXXX',
        'merito_numero'=>'XXXXXXXXXXX',
        'fecha_4'=>'XXXXXXXXXXXXX',
        'presentado_fecha2'=>'XXXXXXXXXXX',
        'numero_crr'=>'XXXXXXXXXXX',
        'senialar_nombrefirma'=>'XXXXXX',
        'senialar_cargofirma'=>'XXXXXXXXXX',
        'periodo_nofunciones'=>'XXXXXXXXXXXXXXXXX',
        'nombre_presenta'=>'XXXXXXXXXX',
        'cargo_presenta'=>'XXXXXXXXXXXX',
        'certificada_pora'=>'XXXXXX',
        'certificada_porb'=>'XXXXXX',
        'certificada_a'=>'XXXXX',
        'certificada_b'=>'XXXXXXX',
        'certificada_c'=>'XXXXXX',
        'analisis'=>'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
        'tipo_accion1'=>'XXXXXX',
        'solventacion_parcial'=>'XXXXXX',
        'toda_vez'=>'XXXXXX',
        'previsto'=>'XXXXXXX',
        'establece'=>'XXXXXX',
        'clave_accion1'=>'0000000',
        'describir_normatividad'=>'XXXXXXXXXXXXXXX',
        'describir_normatividad2'=>'XXXXXXXXXX',
        'clave_accion2'=>'0000000',
        'tipo_accion2'=>'XXXXXXXX',
        'estado2'=>'0000000000',
        'observacion_promovida2'=>'XXXXXXX',
        'merito_identifico2'=>'XXXXXXXXXX',
        'merito_numero2'=>'XXXXXXXXXXXXXXXX',
        'fecha5'=>'XXXXXXXXXXXXXXXXX',
        'presentado_fecha3'=>'XXXXXXXXXXXXXX',
        'numero_crr2'=>'xxxxxxxxxxx',
        'senialar_nombrefirma2'=>'XXXXX',
        'senialar_cargofirma2'=>'XXXXXX',
        'periodo_nofunciones2'=>'XXXXXX',
        'nombre_presenta2'=>'XXXXXX',
        'cargo_presenta2'=>'XXXXXXXX',
        'certificada_por2'=>'XXXXX',
        'certificada_por2a'=>'XXXXXXX',
        'certificada_a2'=>'XXXXXXXXXX',
        'certificada_b2'=>'XXXXXXXXX',
        'certificada_c2'=>'XXXXXXXXX',
        'redactar_analisis'=>'XXXXXXXXX',
        'ya_que'=>'XXXXXXXXXXXXX',
        'entidad_fiscalizada2'=>'XXXXXXXXXXXXX',
        'determinaron'=>'XXXXXXXXXXX',
        'cantidad_total'=>'XXXXXXXXXXX',
        'oficio_numero6'=>'XXXXXXXXXXXXXXXXX',
        'subsistencia_a'=>'XXXXXXXXXXXXXXXXXXX',
        'subsistencia_b'=>'XXXXXXXX',
        'cantidad_total2'=>'XXXXXXXXXX',
        'clave_accion3'=>'XXXXXXXXXXX',
        'clave_accion4'=>'XXXXXXXXXXXX',
        'turno'=>'XXXXXXXXXXX',
        'identifican_numero1'=>'XXXXXXXXXXXXXXXXX',
        'identifican_numero2'=>'XXXXXXXXXXXXXXXXXX',
        'determinaron_a'=>'XXXXXXXXXXXXXXXX',
        'determinaron_b'=>'XXXXXXXXXXXXXX',
        'pliegos_identificados1'=>'XXXXXXXXXXXXX',
        'pliegos_identificados2'=>'XXXXXXXXXXXXXXXXXXXXXXXXXXXX',
        'cantidad_total3'=>'XXXXXXXXXXXXX',
        'recomen_identificadas1'=>'XXXXXXXXXXX',
        'recomen_identificadas2'=>'XXXXXXXXXXXXXXXXXX',
        'clave_accion5'=>'XXXXXXXXXXX',
        'clave_accion6'=>'XXXXXXXXXXXXX',
        'turno2'=>'XXXXXXXXXXXXXXXXXXX',
        'no_atendidas'=>'XXXXXXXXXXXXXXXXXXXXX',
        'clave_accion7'=>'XXXXXXXXXXXXXX',
        'oic_de'=>'XXXXXXXXXXXXXX',
        'clave_numero1'=>'XXXXXXXXXXXXXXXX',
        'clave_numero2'=>'XXXXXXXXXXXXX',
        'clave_numero3'=>'XXXXXXXXXXX',
        'autoriza_dia'=>'XXXXXXXXXXX',
        'autoriza_mes'=>'XXXXXXXXX',
        'autoriza_anio'=>'XXXXXXXX',
        'imprime'=>'XXXXXXXXXXXXXX',
        'firma_titular'=>'Nombre y Firma del titular',
        'firma_director'=>'Nombre y Firma del director',
        'firma_jefe'=>'Nombre y Firma del jefe de departamento',
        'firma_lider'=>'Nombre y Firma del líder de proyecto',
        'firma_analista'=>'nombre y Firma del analista',

    ];
        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Jefe/is',  $params, 'docx');       

        $auditoria=Auditoria::find(getSession('auditoria_id'));
		/*Cumplimiento Financero*/
		if($auditoria->tipo_auditoria_id==1){
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/CUMPLIMIENTO_FINANCIERO/LIDER/1.AR', $params, 'docx');
            $template=new TemplateProcessor('bases-word/1. CUMPLIMIENTO FINANCIERO/10. IS.docx');
            $nombreword='10. IS';
            $template->saveAs($nombreword.'.docx');

		}elseif($auditoria->tipo_auditoria_id==2){
			/*Inversion Fisica*/
            $template=new TemplateProcessor('bases-word/3. INVERSIÓN FÍSICA/10. IS.docx');
            $nombreword='10. IS';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/INVERSION_FISICA/LIDER/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==3){
			/*Desempeño*/
            $template=new TemplateProcessor('bases-word/2. DESEMPEÑO/10. IS PAR.docx');
            $nombreword='10. IS PAR';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/DESEMPEÑO/LIDER/1.AR', $params, 'docx');
		}elseif($auditoria->tipo_auditoria_id==4){
			/*Legalidad*/
            $template=new TemplateProcessor('bases-word/4. LEGALIDAD/13. IS EA Y PAR.docx');
            $nombreword='13. IS EA Y PAR';
            $template->saveAs($nombreword.'.docx');
			//$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/LEGALIDAD/LIDER/1.AR', $params, 'docx');
		}           
        //return response()->download($preconstancia);
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
    public function is2()
    {  
    $params = [            
        'direccion'=>'Seguimiento',
        'departamento'=>'XXX',
        'entidad_fiscalizada'=>'XXXXXXX',
        'tipo_auditoria'=>'XXXXXX',
        'periodo_fiscalizado'=>'XXXXX',
        'auditoria'=>'XXXXXX',
        'practicada_a'=>'XXXXX',
        'periodo_comprendido'=>'XXXXX',
        'oficio_orden'=>'XXXXX',
        'fecha'=>'XXXX',
        'oficio_numero'=>'XXXX/XXX/XXX/202X',
        'constante'=>'XXXX',
        'fojas_utiles'=>'XXXX',
        'pliegos_numero'=>'XXXX',
        'pliegos_numero2'=>'XXXXXXXXXX',
        'suscritos_por'=>'XXXXXX',
        'caracter_de'=>'XXXXXX',
        'administracion'=>'XXXXXXX',
        'presentados_fecha'=>'25/07/2024',
        'asignacion_folio'=>'000000/0000/02000',
        'acuerdo_fecha'=>'XX/XX/XX',
        'expediente'=>'XXXX/XXXXX/XXXXX/XXXX',
        'dia'=>'21',
        'oficios_numeroa'=>'XXXXXXXXX_1',
        'oficios_numerob'=>'XXXXXXXXX_2',
        'oficios_numeroc'=>'XXXXXXXXX_3',
        'crr1'=>'XXXXXXXX',
        'crr2'=>'XXXXXXX',
        'crr3'=>'XXXXXX',
        'resultados'=>'xxxx',
        'clave_accion'=>'XXXXX',
        'entidad_fiscalizada2'=>'XXXXXXX',
        'pliegos_nosolventados'=>'XXXXX',
        'cantidad_total'=>'5458',
        'numoficio_etapa1'=>'XXXX/XXX/XXXX/XXX',
        'subsistencia_de'=>'XXXXXXX',
        'auditoria_num'=>'00000010000111',
        'cantidad_ascendente'=>'XXXXXXX',
        'aclaracion_auditoria'=>'XXXXXXXXX',
        'orden_auditoria'=>'XXXXX',
        'total_pliegos1'=>'4',
        'total_pliegos2'=>'28',
        'cantidad_ascendente2'=>'XXXX',
        'clave_accion2'=>'XXX/XX',
        'clave_accion3'=>'XXXX/XXX',
        'turno_oic'=>'XXXXXX',
        'recomendaciones_numero'=>'XXXXX',
        'recomendaciones_vinculadas'=>'XXXX',
        'clave_recomen'=>'XXXXX',
        'enviar_oic'=>'XXXXX',
        'control_promueva'=>'XXXXX',
        'observacion_clave1'=>'XXXXX',
        'observacion_clave2'=>'XXXXXXXXXX',
        'observacion_clave3'=>'XXXXXXXXX',
        'resultados_auditoria'=>'XXXXXXXXX',
        'practicada_a2'=>'XXXXXXX',
        'periodo_comprendido2'=>'XXXXXXXX',
        'oficio_orden2'=>'XXXXXXX',
        'dia_letra'=>'treinta',
        'mes_letra'=>'junio',
        'anio_letra'=>'veinticuatro',
        'num_tantos'=>'1254',
        'firma_titular'=>'XXXXXXXXX',
        'firma_director'=>'XXXXXXXX',
        'firma_jefe'=>'XXXXXXX',
        'firma_lider'=>'XXXXXXXX',
        'firma_analista'=>'XXXXXXXX',        
    ];    
        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Jefe/is2', $params, 'docx');       

        return response()->download($preconstancia);
        
    }

        /**Dirección */
    public function mda()
    {
        $params = [
        
        'direccion'=>'unidad_admnistrativa',
        'departamento'=>'AAA',
        'dia'=>'19',
        'mes'=>'Junio',
        'anio'=>'2024',
        'oficio_numero'=>'OSFEM/US/XXX/202X',
        'analista'=>'nombre_analista',
        'oficio_ordenauditoria'=>'XXXX/xxx/xxxxx',
        'numero_auditoria'=>'000001651544157',
        'entidad_fiscalizable'=>'nombre_de_la_entidad_fiscalizble',
        'periodo_auditoria'=>'25 días habiles',
        'tipo_auditoria'=>'XXXXXX',
        'legajos'=>'XXXX',
        'fojas'=>'454',
        'informe_auditorias'=>'XXXXX',
        'constante'=>'knfdgkndfg',
        'fojas_utiles'=>'50',
        'jefe_depa'=>'nombre_jefe_departamento',
        'lider_seguimiento'=>'nombre_de_seguimiento',
        'iniciales_dir_jefe'=>'iniciales_director_segumiento y jefe_departamento',
        
        ];

        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Direccion/mda', $params, 'docx');       
        //return response()->download($preconstancia);

        $template=new TemplateProcessor('bases-word/GENERALES/7. SECRETARIA DIRECCIÓN/2. MDA.docx');
        $nombreword='2. MDA';
        $template->saveAs($nombreword.'.docx');
        
         return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
    public function mdi() // mdj
    {
        $params = [
        
        'direccion'=>'unidad_admnistrativa',
        'departamento'=>'AAA',
        'dia'=>'19',
        'mes'=>'Junio',
        'anio'=>'2024',
        'oficio_numero' => 'OSFEM/US/XXX/202X',
        'jefe_depa' => 'XXXXXX',
        'oficio_ordenauditoria'=> 'OSFEM/X/XXXX/202X',
        'numero_auditoria'=> 'OSFEM/X/XXXX/202X',
        'entidad_fiscalizable'=>'XXXXXXX',
        'periodo_auditoria'=>'XXXXXX',
        'tipo_auditoria'=>'XXXXXX',
        'legajos'=>'once',
        'fojas'=>'junio',
        'informe_auditoria'=>'veinticuatro',
        'constante'=> 'AAAAA',
        'fojas_utiles'=>'256',
        'director_seguimiento'=> 'Luis Sierra',        
        'lisv'=>'XXX/XXXX/XXX/XXXX',

        ];

        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Direccion/mdj', $params, 'docx');       
        //return response()->download($preconstancia);
        $template=new TemplateProcessor('bases-word/GENERALES/7. SECRETARIA DIRECCIÓN/1. MDJ.docx');
        $nombreword='2. MDJ';
        $template->saveAs($nombreword.'.docx');
        
         return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
        /**Titular */

    public function aa()
    {
        $params= [
          'direccion'=>'XXXXXX',
          'departamento'=>'b',
          'dia'=>'24',
          'mes'=>'junio',
          'anio'=>'2024',
          'oficio_numero'=>'OSFEM/US/XXX/202X',
          'asunto'=>'XXXXXX',
          'director'=>'XXXXXXX XXXXXX XXXXXXX',
          'oficio_orden'=>'XXXXX/XXX/XXXX/XXX',
          'num_auditoria'=>'XXXX/XXX/XXX/XXXX',
          'entidad'=>'XXXXX',
          'periodo_auditoria'=>'XXXXXX',        
          'tipo_auditoria'=>'XXXXX',
          'legajos'=>'XXXX',
          'fojas'=>'XXXXX',
          'informe_auditoria'=>'XXXXX',
          'constante_a'=>'XXXXX',
          'constante_b'=>'XXXXX',
          'firma'=>'XXXXXX',
          'jefe_nombre'=>'XXXX',
          'ccp'=>'XXXX',
          'lider_proyecto'=>'XXXX',
          'archivo'=>'XXXXXX',
        ];

        //$preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/Pac/Titular/aa', $params,'docx');       
        //return response()->download($preconstancia);
        $template=new TemplateProcessor('bases-word/GENERALES/6. SECRETARIA TITULAR/1. MAA.docx');
        $nombreword='1. MAA';
        $template->saveAs($nombreword.'.docx');
        
         return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
        
    }
}
