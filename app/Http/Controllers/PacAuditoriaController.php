<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class PacAuditoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auditoria= Auditoria::find(getSession('auditoria_id'));
        $siglas_rol=auth()->user()->siglas_rol;
        
        return view("pacauditoria.index",compact('siglas_rol', 'auditoria'));
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

        $preconstancia = reporte(1, 'Fiscalizacion/Seguimiento/PacAuditoria/Lider/ofiaar', $params, 'docx');       

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
}
