<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Comparecencia;
use App\Models\Movimientos;
use App\Models\Radicacion;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Luecano\NumeroALetras\NumeroALetras;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;


class RadicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $auditoria = Auditoria::find(getSession('auditoria_id'));

        return view('radicacion.index', compact('auditoria', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria=Auditoria::find(getSession('radicacion_auditoria_id'));
        $radicacion = new Radicacion();
        $comparecencia = new Comparecencia();
        $accion = 'Agregar';

        return view('radicacion.form', compact('radicacion','auditoria','accion','comparecencia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        mover_archivos($request, ['oficio_designacion'], null);
        $request['usuario_creacion_id'] = auth()->user()->id;
        $request['auditoria_id']=getSession('radicacion_auditoria_id');
        $request['fecha_inicio_aclaracion'] = addBusinessDays($request->fecha_comparecencia, 1);
        $request['fecha_termino_aclaracion'] = addBusinessDays($request->fecha_inicio_aclaracion, 30);
        

        $radicacion = Radicacion::create($request->all());
        $comparecencia = Comparecencia::create($request->all());

        
        
        

        //$ruta = env('APP_RUTA_MINIO').'Auditorias/' . strtoupper(Str::slug($auditoria->numero_auditoria)).'/Documentos';
        //mover_archivos_minio($request, ['oficio_comparecencia'], null, $ruta);

        /*Movimientos::create([
            'tipo_movimiento' => 'Registro de la radicación',
            'accion' => 'Radicación',
            'accion_id' => $radicacion->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        if (strlen($radicacion->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $radicacion->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        $radicacion->update(['fase_autorizacion' =>  'En validación', 'nivel_autorizacion' => $nivel_autorizacion]);

        $titulo = 'Validación de los datos de radicación';
        $mensaje = '<strong>Estimado (a) ' . auth()->user()->director->name . ', ' . auth()->user()->director->puesto . ':</strong><br>
                    Ha sido registrada la radicación de la auditoría No. ' . $radicacion->auditoria->numero_auditoria . ', por parte del ' .
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la validación.';

        auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id,auth()->user()->director->id);
        */
        setMessage('Los datos de la radicación se han registrado exitosamente, continua agendado la comparecencia.');

        return redirect()->route('comparecenciaagenda.edit',$comparecencia);
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
    public function edit(Radicacion $radicacion)
    {
        $auditoria=$radicacion->auditoria;
        $accion = 'Editar';
        $comparecencia=$auditoria->comparecencia;

        return view('radicacion.form', compact('radicacion','auditoria','accion','comparecencia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Radicacion $radicacion)
    {
        mover_archivos($request, ['oficio_designacion'], $radicacion);
        $request['usuario_modificacion_id'] = auth()->user()->id;
        $radicacion->update($request->all());
        $auditoria=$radicacion->auditoria;
        $comparecencia=$auditoria->comparecencia;
        $comparecencia->update($request->all());

        //$fecha_expediente_turnado->

        /*Movimientos::create([
            'tipo_movimiento' => 'Registro de la radicación',
            'accion' => 'Radicación',
            'accion_id' => $radicacion->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        $radicacion->update(['fase_autorizacion' =>  'En validación']);

        $titulo = 'Validación de los datos de radicación';
        $mensaje = '<strong>Estimado (a) ' . auth()->user()->director->name . ', ' . auth()->user()->director->puesto . ':</strong><br>
                    Ha sido registrada la radicación de la auditoría No. ' . $radicacion->auditoria->numero_auditoria . ', por parte del ' .
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la validación.';

        auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id,auth()->user()->director->id);
        */
        setMessage('La radicación se ha modificado correctamente, continua agendando la comparecencia.');

        return redirect()->route('comparecenciaagenda.edit',$comparecencia);
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

    public function auditoria(Auditoria $auditoria)
    {
        setSession('radicacion_auditoria_id',$auditoria->id);

        return redirect()->route('radicacion.create');
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

    public function export(){/** */
        $auditoria=Auditoria::find(getSession('auditoria_id'));       

        $entidades=explode(' - ',$auditoria->entidad_fiscalizable);

         $txtentidad=null;

         if (count($entidades)>1) {
            if ($entidades[1]=='MUNICIPIOS') {
                $bar = ucwords($entidades[2]);       
                $bar = ucwords(strtolower($bar));

                $txtentidad='Municipio de '.$bar;
            }
         }
        $iniciales='';
        $nombre=auth()->user()->name;
        $esquemanombres=explode(' ',$nombre);
         foreach($esquemanombres as $parte){
            $iniciales=$iniciales.substr($parte, 0,1);
         }

         

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[(now()->format('n')) - 1];

        $nombre_ccp='';
        $info_ccp='';
        $infodom_ccp='';
        $info='';
        if ($auditoria->entidadFiscalizable->Ambito=='Estatal') {
            $nombre_ccp='</w:t><w:br/><w:t>Luis David Fernández Araya';
            $info_ccp='Subsecretario de Control y Evaluación de la Secretaría de la Contraloría del Gobierno del Estado de México. </w:t><w:br/><w:t>';
            $infodom_ccp='Domicilio: Av. Primero de Mayo, número 1731, Esquina Robert Bosch, Colonia Zona Industrial, C.P. 50071, Toluca, México.</w:t><w:br/><w:t>';
            $info=$info_ccp.' '.$infodom_ccp;
        }
        if($auditoria->acto_fiscalizacion=='Inversión Física')
        {
            $template=new TemplateProcessor('bases-word/PAC/INVERSION_FISICA/LIDER/2. Of. IA AR.docx'); //*
            $template->setValue('anio',date("Y"));
            $template->setValue('mes',$mes);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
            $template->setValue('entidad',$txtentidad);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);       
            $template->setValue('nombre_ccp',$nombre_ccp);
            $template->setValue('info_ccp',$info_ccp);
            $template->setValue('infodom_ccp',$infodom_ccp);
            $template->setValue('info',$info);
            $template->setValue('iniciales',$iniciales);
            $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
            
            $nombreword='AIAR';/** */

        $template->saveAs($nombreword.'.docx');/** */
        }
         if($auditoria->acto_fiscalizacion=='Legalidad'){
            $template=new TemplateProcessor('bases-word/PAC/LEGALIDAD/LIDER/2. Of. IA AR.docx'); //*
            $template->setValue('anio',date("Y"));
            $template->setValue('mes',$mes);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
            $template->setValue('entidad',$txtentidad);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);       
            $template->setValue('nombre_ccp',$nombre_ccp);
            $template->setValue('info_ccp',$info_ccp);
            $template->setValue('infodom_ccp',$infodom_ccp);
            $template->setValue('info',$info);
            $template->setValue('iniciales',$iniciales);
            $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
            
            $nombreword='AIAR';/** */

        $template->saveAs($nombreword.'.docx');/** */
            }
            if($auditoria->acto_fiscalizacion=='Desempeño')
            {
                $template=new TemplateProcessor('bases-word/PAC/DESEMPEÑO/LIDER/2. Of. IA AR.docx'); //*
                $template->setValue('anio',date("Y"));
                $template->setValue('mes',$mes);
                $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
                $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
                $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
                $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
                $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
                $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
                $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
                $template->setValue('entidad',$txtentidad);
                $template->setValue('periodo',$auditoria->periodo_revision);
                $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);       
                $template->setValue('nombre_ccp',$nombre_ccp);
                $template->setValue('info_ccp',$info_ccp);
                $template->setValue('infodom_ccp',$infodom_ccp);
                $template->setValue('info',$info);
                $template->setValue('iniciales',$iniciales);
                $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
                
                $nombreword='AIAR';/** */
    
            $template->saveAs($nombreword.'.docx');/** */
            }    
            if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero')
        {
            $template=new TemplateProcessor('bases-word/PAC/CUMPLIMIENTO_FINANCIERO/LIDER/2. Of. IA AR.docx'); //*
            $template->setValue('anio',date("Y"));
            $template->setValue('mes',$mes);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
            $template->setValue('entidad',$txtentidad);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);       
            $template->setValue('nombre_ccp',$nombre_ccp);
            $template->setValue('info_ccp',$info_ccp);
            $template->setValue('infodom_ccp',$infodom_ccp);
            $template->setValue('info',$info);
            $template->setValue('iniciales',$iniciales);
            $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
            
            $nombreword='AIAR';/** */

        $template->saveAs($nombreword.'.docx');/** */
        }
        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);/** */
    }/** */
  
  
    public function exportOIC(){
        $auditoria=Auditoria::find(getSession('auditoria_id'));

        $entidades=explode(' - ',$auditoria->entidad_fiscalizable);

         $txtentidad=null;

         if (count($entidades)>1) {
            if ($entidades[1]=='MUNICIPIOS') {
                $bar = ucwords($entidades[2]);       
                $bar = ucwords(strtolower($bar));

                $txtentidad='Municipio de '.$bar;
            }else{
                //$bar = ucwords($entidades[2]);       
                $bar = ucwords(strtolower($auditoria->entidad_fiscalizable));

                $txtentidad=$bar;
            }
         }

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[(now()->format('n')) - 1];
        $prassp='';
    
        if (count($auditoria->accionespras)>0) {
            if(count($auditoria->accionespras)==1){
                $prassp='la Promoción de Responsabilidad Administrativa Sancionatoria (PRAS) identificada ';
            }else{
                $prassp='las Promociones de Responsabilidad Administrativa Sancionatoria (PRAS) identificadas ';
            }

           
        }

        $nombre_ccp='';
        $info_ccp='';
        $infodom_ccp='';
        $info='';
        if ($auditoria->entidadFiscalizable->Ambito=='Estatal') {
            $nombre_ccp='</w:t><w:br/><w:t>Luis David Fernández Araya';
            $info_ccp='Subsecretario de Control y Evaluación de la Secretaría de la Contraloría del Gobierno del Estado de México. </w:t><w:br/><w:t>';
            $infodom_ccp='Domicilio: Av. Primero de Mayo, número 1731, Esquina Robert Bosch, Colonia Zona Industrial, C.P. 50071, Toluca, México.</w:t><w:br/><w:t>';
            $info=$info_ccp.' '.$infodom_ccp;
        }

        $iniciales='';
        $nombre=auth()->user()->name;
        $esquemanombres=explode(' ',$nombre);
         foreach($esquemanombres as $parte){
            $iniciales=$iniciales.substr($parte, 0,1);
         }

         if($auditoria->acto_fiscalizacion=='Inversión Física')
    {
        $template=new TemplateProcessor('bases-word/PAC/INVERSION_FISICA/LIDER/3. Of. AR_OIC´s.docx');
        $template->setValue('anio',date("Y"));
        $template->setValue('mes',$mes);
        $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
        $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
        $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
        $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
        $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
        $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
        $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
        $template->setValue('entidad',$txtentidad);
        $template->setValue('periodo',$auditoria->periodo_revision);
        $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);
        $template->setValue('totalpras',$auditoria);
        $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
        $template->setValue('prassp',$prassp);
        $template->setValue('claves',count($auditoria->accionespras));
        $template->setValue('nombre_ccp',$nombre_ccp);
        $template->setValue('info_ccp',$info_ccp);
        $template->setValue('infodom_ccp',$infodom_ccp);
        $template->setValue('info',$info);
        $template->setValue('iniciales',$iniciales);
        $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);


        $nombreword='AROIC';

        $template->saveAs($nombreword.'.docx');
    }
    if($auditoria->acto_fiscalizacion=='Legalidad')
    {    $template=new TemplateProcessor('bases-word/PAC/LEGALIDAD/LIDER/3. Of. AR_OIC´s.docx');
        $template->setValue('anio',date("Y"));
        $template->setValue('mes',$mes);
        $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
        $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
        $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
        $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
        $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
        $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
        $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
        $template->setValue('entidad',$txtentidad);
        $template->setValue('periodo',$auditoria->periodo_revision);
        $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);
        $template->setValue('totalpras',$auditoria);
        $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
        $template->setValue('prassp',$prassp);
        $template->setValue('claves',count($auditoria->accionespras));
        $template->setValue('nombre_ccp',$nombre_ccp);
        $template->setValue('info_ccp',$info_ccp);
        $template->setValue('infodom_ccp',$infodom_ccp);
        $template->setValue('info',$info);
        $template->setValue('iniciales',$iniciales);
        $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);


        $nombreword='AROIC';    
        $template->saveAs($nombreword.'.docx');
    }
    if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero')
    {    $template=new TemplateProcessor('bases-word/PAC/CUMPLIMIENTO_FINANCIERO/LIDER/3. Of. AR_OICs.docx');
        $template->setValue('anio',date("Y"));
        $template->setValue('mes',$mes);
        $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
        $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
        $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
        $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);

        $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
        $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
        $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
        $template->setValue('entidad',$txtentidad);
        $template->setValue('periodo',$auditoria->periodo_revision);
        $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);
        $template->setValue('totalpras',$auditoria);
        $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
        $template->setValue('prassp',$prassp);
        $template->setValue('claves',count($auditoria->accionespras));
        $template->setValue('nombre_ccp',$nombre_ccp);
        $template->setValue('info_ccp',$info_ccp);
        $template->setValue('infodom_ccp',$infodom_ccp);
        $template->setValue('info',$info);
        $template->setValue('iniciales',$iniciales);
        $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);


        $nombreword='AROIC';    
        $template->saveAs($nombreword.'.docx');
    }
    // else{
        
    // }

        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
    }
	
	
	public function radicacionpdf(Radicacion $radicacionpdf)
    {
		$radicacion=$radicacionpdf;
        $horaMin='';
        $minutosMin='';
        $fechacomparecencia='';
        $fechainicioaclaracion='';
        $fechaterminoaclaracion='';
        $auditoria=Auditoria::find(getSession('auditoria_id'));       
        $formatter = new NumeroALetras();

        $entidades=explode(' - ',$auditoria->entidad_fiscalizable);

         $txtentidad=null;

         if (count($entidades)>1) {
            if ($entidades[1]=='MUNICIPIOS') {
                $bar = ucwords($entidades[2]);       
                $bar = ucwords(strtolower($bar));

                $txtentidad='Municipio de '.$bar;
            }
         }
        $iniciales='';
        $nombre=auth()->user()->name;
        $esquemanombres=explode(' ',$nombre);
         foreach($esquemanombres as $parte){
            $iniciales=$iniciales.substr($parte, 0,1);
         }

         

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[(now()->format('n')) - 1];

        

        $fecha_hora=fecha(optional($auditoria->comparecencia)->fecha_comparecencia) . ' ' . date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_inicio)) . (empty($auditoria->comparecencia->hora_comparecencia_termino)?"":"-".date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_termino)));
        $date01 = fecha(optional($auditoria->comparecencia)->fecha_comparecencia);
        $day01 = date('d', strtotime($date01)); 
        $mes01 = $meses[($auditoria->comparecencia)->fecha_comparecencia->format('n') - 1]; 
        $day02 = date('d', strtotime($auditoria->comparecencia->fecha_inicio_aclaracion));
        $mes02 = $meses[($auditoria->comparecencia->fecha_inicio_aclaracion->format('n')) - 1]; 
        $day03 = date('d', strtotime($auditoria->comparecencia->fecha_termino_aclaracion));
        $mes03 = $meses[($auditoria->comparecencia->fecha_termino_aclaracion->format('n')) - 1];
        
        
        $hora01 =  date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_inicio));
        $cierre = $auditoria->radicacion->fecha_cierre_auditoria;


        $nombre_ccp='';
        $info_ccp='';
        $infodom_ccp='';
        $info='';
        if ($auditoria->entidadFiscalizable->Ambito=='Estatal') {
            $nombre_ccp='</w:t><w:br/><w:t>Luis David Fernández Araya';
            $info_ccp='Subsecretario de Control y Evaluación de la Secretaría de la Contraloría del Gobierno del Estado de México. </w:t><w:br/><w:t>';
            $infodom_ccp='Domicilio: Av. Primero de Mayo, número 1731, Esquina Robert Bosch, Colonia Zona Industrial, C.P. 50071, Toluca, México.</w:t><w:br/><w:t>';
            $info=$info_ccp.' '.$infodom_ccp;
        }
		
		
        $auditoria=$radicacion->auditoria;
		$horas=explode(':',$auditoria->comparecencia->hora_comparecencia_inicio); 
        $formatter = new NumeroALetras();
		if(empty($auditoria->comparecencia->fecha_comparecencia)){        
            $hora = $formatter->toString($horas[0]);
            $minutos = $formatter->toString($horas[1]);
        
            $horaMax = ucwords($hora);             
            $horaMin = ucwords(strtolower($horaMax));
        
            $minutosMax = ucwords($minutos);            
            $minutosMin = ucwords(strtolower($minutosMax));

            $fechacomparecencia=fechaaletra($auditoria->comparecencia->fecha_comparecencia);        
            $fechainicioaclaracion=fechaaletra($auditoria->comparecencia->fecha_inicio_aclaracion);       
            $fechaterminoaclaracion=fechaaletra($auditoria->comparecencia->fecha_termino_aclaracion);
		}

        $formatterPM = new NumeroALetras();
        $plazomax=$formatter->toString($radicacion->plazo_maximo);

        $plazomaxMax = ucwords($plazomax);            
        $plazomaxMin = ucwords(strtolower($plazomaxMax));

        $fechaactual=fechaaletra(now());

        if($auditoria->entidadFiscalizable->Ambito = 'Estatal'){
            $ambito01 = 5;
        }else{
            $ambito01=3;
        }

        $relacion4=[
            'horastxt'=> $horaMin,
            'mintxt'=> $minutosMin,
            'fechacomparecenciatxt'=>$fechacomparecencia,
            'fechainicioaclaraciontxt'=>$fechainicioaclaracion,
            'fechaterminoaclaraciontxt'=>$fechaterminoaclaracion,
            'plazomaximo'=>$plazomaxMin,
            'fechaactual'=>$fechaactual,
            'hora01'=> $hora01,
            'day01'=>$day01,
            'mes01'=>$mes01,
            'day02'=>$day02,
            'mes02'=>$mes02,
            'day03'=>$day03,
            'mes03'=>$mes03,
            'cierre'=>$cierre,
            'ambito01'=> $ambito01,
            'iniciales' => $iniciales,
        ];
        
        if($auditoria->acto_fiscalizacion == 'Legalidad'){
            $datosConstancia = [
                'nombrereporte' => 'radicacionreportes.radicacionlegalidad',
                'auditoriaseleccionada'=>base64_encode(Str::random(5).$radicacion->auditoria_id.Str::random(5)),
                'accionseleccionada'=>'',            
                'modelo_principal'=>['tbl'=>$radicacion->getTable(),'vinculo'=>base64_encode(Str::random(5).$radicacion->id.Str::random(5))],
                'relacion1'=>null,
                'relacion2'=>null,
                'relacion3'=>null,
                'relacion4'=>$relacion4,  
                'firmante'=>auth()->user()->name,
                'firmante_puesto'=>auth()->user()->puesto,  
            ];
        }elseif($auditoria->acto_fiscalizacion == 'Cumplimiento Financiero'){
            $datosConstancia = [
                'nombrereporte' => 'radicacionreportes.radicacioncumplimientofinanciero',
                'auditoriaseleccionada'=>base64_encode(Str::random(5).$radicacion->auditoria_id.Str::random(5)),
                'accionseleccionada'=>'',            
                'modelo_principal'=>['tbl'=>$radicacion->getTable(),'vinculo'=>base64_encode(Str::random(5).$radicacion->id.Str::random(5))],
                'relacion1'=>null,
                'relacion2'=>null,
                'relacion3'=>null,
                'relacion4'=>$relacion4,  
                'firmante'=>auth()->user()->name,
                'firmante_puesto'=>auth()->user()->puesto,  
            ];
        }elseif ($auditoria->acto_fiscalizacion == 'Inversión Física') {
            $datosConstancia = [
                'nombrereporte' => 'radicacionreportes.radicacioninversionfisica',
                'auditoriaseleccionada'=>base64_encode(Str::random(5).$radicacion->auditoria_id.Str::random(5)),
                'accionseleccionada'=>'',            
                'modelo_principal'=>['tbl'=>$radicacion->getTable(),'vinculo'=>base64_encode(Str::random(5).$radicacion->id.Str::random(5))],
                'relacion1'=>null,
                'relacion2'=>null,
                'relacion3'=>null,
                'relacion4'=>$relacion4,  
                'firmante'=>auth()->user()->name,
                'firmante_puesto'=>auth()->user()->puesto,  
            ];
        }else{
            $datosConstancia = [
                'nombrereporte' => 'radicacionreportes.radicaciondesempeño',
                'auditoriaseleccionada'=>base64_encode(Str::random(5).$radicacion->auditoria_id.Str::random(5)),
                'accionseleccionada'=>'',            
                'modelo_principal'=>['tbl'=>$radicacion->getTable(),'vinculo'=>base64_encode(Str::random(5).$radicacion->id.Str::random(5))],
                'relacion1'=>null,
                'relacion2'=>null,
                'relacion3'=>null,
                'relacion4'=>$relacion4,  
                'firmante'=>auth()->user()->name,
                'firmante_puesto'=>auth()->user()->puesto,  
            ];
        }

        $pdf=reportepdfprevio($datosConstancia['nombrereporte'],1,'Temporal',
                                 base64_encode(Str::random(5).$radicacion->auditoria_id.Str::random(5)),
                                 '',
                                 ['tbl'=>$radicacion->getTable(),'vinculo'=>base64_encode(Str::random(5).$radicacion->id.Str::random(5))],
                                 null,null,null,$relacion4,'','','','','','',''); 

		return $pdf->stream('prueba.pdf');
    }

    public function radicacionWord()
    {
        $horaMin='';
        $minutosMin='';
        $fechacomparecencia='';
        $fechainicioaclaracion='';
        $fechaterminoaclaracion='';
        $auditoria=Auditoria::find(getSession('auditoria_id'));       
        $formatter = new NumeroALetras();

        $entidades=explode(' - ',$auditoria->entidad_fiscalizable);

         $txtentidad=null;

         if (count($entidades)>1) {
            if ($entidades[1]=='MUNICIPIOS') {
                $bar = ucwords($entidades[2]);       
                $bar = ucwords(strtolower($bar));

                $txtentidad='Municipio de '.$bar;
            }
         }
        $iniciales='';
        $nombre=auth()->user()->name;
        $esquemanombres=explode(' ',$nombre);
         foreach($esquemanombres as $parte){
            $iniciales=$iniciales.substr($parte, 0,1);
         }

         

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[(now()->format('n')) - 1];

        $horas=explode(':',$auditoria->comparecencia->hora_comparecencia_inicio); 
        if(empty($auditoria->comparecencia->fecha_comparecencia)){

            $hora = $formatter->toString($horas[0]);
            $minutos = $formatter->toString($horas[1]);
          
            $horaMax = ucwords($hora);             
            $horaMin = ucwords(strtolower($horaMax));
           
            $minutosMax = ucwords($minutos);            
            $minutosMin = ucwords(strtolower($minutosMax));
    
            $fechacomparecencia=fechaaletra($auditoria->comparecencia->fecha_comparecencia);        
            $fechainicioaclaracion=fechaaletra($auditoria->comparecencia->fecha_inicio_aclaracion);       
            $fechaterminoaclaracion=fechaaletra($auditoria->comparecencia->fecha_termino_aclaracion);
            }

        $fecha_hora=fecha(optional($auditoria->comparecencia)->fecha_comparecencia) . ' ' . date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_inicio)) . (empty($auditoria->comparecencia->hora_comparecencia_termino)?"":"-".date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_termino)));
        $date01 = fecha(optional($auditoria->comparecencia)->fecha_comparecencia);
        $day01 = date('d', strtotime($date01)); 
        $mes01 = $meses[($auditoria->comparecencia)->fecha_comparecencia->format('n') - 1]; 
        $day02 = date('d', strtotime($auditoria->comparecencia->fecha_inicio_aclaracion));
        $mes02 = $meses[($auditoria->comparecencia->fecha_inicio_aclaracion->format('n')) - 1]; 
        $day03 = date('d', strtotime($auditoria->comparecencia->fecha_termino_aclaracion));
        $mes03 = $meses[($auditoria->comparecencia->fecha_termino_aclaracion->format('n')) - 1];
        
        
        $hora01 =  date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_inicio));

        $formatterPM = new NumeroALetras();
        $plazomax=$formatter->toString($auditoria->radicacion->plazo_maximo);

        $plazomaxMax = ucwords($plazomax);            
        $plazomaxMin = ucwords(strtolower($plazomaxMax));

        $fechaactual=fechaaletra(now());
        $cierre = $auditoria->radicacion->fecha_cierre_auditoria;


        $nombre_ccp='';
        $info_ccp='';
        $infodom_ccp='';
        $info='';
        if ($auditoria->entidadFiscalizable->Ambito=='Estatal') {
            $nombre_ccp='</w:t><w:br/><w:t>Luis David Fernández Araya';
            $info_ccp='Subsecretario de Control y Evaluación de la Secretaría de la Contraloría del Gobierno del Estado de México. </w:t><w:br/><w:t>';
            $infodom_ccp='Domicilio: Av. Primero de Mayo, número 1731, Esquina Robert Bosch, Colonia Zona Industrial, C.P. 50071, Toluca, México.</w:t><w:br/><w:t>';
            $info=$info_ccp.' '.$infodom_ccp;
        }
        if($auditoria->entidadFiscalizable->Ambito = 'Estatal'){
            $ambito01 = 5;
        }else{
            $ambito01=3;
        }
        if($auditoria->acto_fiscalizacion=='Inversión Física')
        {
            $template=new TemplateProcessor('bases-word/PAC/INVERSION_FISICA/LIDER/1. AR_01.docx'); //*
            /**Se usan los mismos valores que en radicacionpdf por si se añaden despues algunos, ya estan solo que elimine los que no se usan */
            $template->setValue('entidad_fiscalizable', $auditoria->entidad_fiscalizable);
            $template->setValue('fecha_txt', $fechacomparecencia);
            $template->setValue('day01', $day01);
            $template->setValue('mes01', $mes01);
            $template->setValue('hora01', $hora01);
            $template->setValue('plazo', $auditoria->radicacion->plazo_maximo);
            $template->setValue('plazoMaximoletra', $plazomaxMin);
            $template->setValue('fechahoy', $fechaactual);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('cierre',$auditoria->radicacion->fecha_cierre_auditoria);
           
            $template->setValue('ambito01', $ambito01);
            $template->setValue('day02', $day02);
            $template->setValue('mes02', $mes02);
            $template->setValue('day03', $day03);
            $template->setValue('mes03', $mes03);
            $template->setValue('iniciales',$iniciales);

            
            $nombreword='AR';/** */

        $template->saveAs($nombreword.'.docx');/** */
        }
         if($auditoria->acto_fiscalizacion=='Legalidad'){
            $template=new TemplateProcessor('bases-word/PAC/LEGALIDAD/LIDER/1. AR_01.docx'); //*
            /**Se usan los mismos valores que en radicacionpdf por si se añaden despues algunos, ya estan solo que elimine los que no se usan */
            $template->setValue('entidad_fiscalizable', $auditoria->entidad_fiscalizable);
            $template->setValue('fecha_txt', $fechacomparecencia);
            $template->setValue('day01', $day01);
            $template->setValue('mes01', $mes01);
            $template->setValue('hora01', $hora01);
            $template->setValue('plazo', $auditoria->radicacion->plazo_maximo);
            $template->setValue('plazoMaximoletra', $plazomaxMin);
            $template->setValue('fechahoy', $fechaactual);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('periodo',$auditoria->periodo_revision);

            $template->setValue('ambito01', $ambito01);
            $template->setValue('day02', $day02);
            $template->setValue('mes02', $mes02);
            $template->setValue('day03', $day03);
            $template->setValue('mes03', $mes03);
            $template->setValue('cierre',$auditoria->radicacion->fecha_cierre_auditoria);
            $template->setValue('iniciales',$iniciales);
            
            $nombreword='AR';/** */

        $template->saveAs($nombreword.'.docx');/** */
            }
            if($auditoria->acto_fiscalizacion=='Desempeño')
            {
                $template=new TemplateProcessor('bases-word/PAC/DESEMPEÑO/LIDER/1. AR_01.docx'); //*

                /**Se usan los mismos valores que en radicacionpdf por si se añaden despues algunos, ya estan solo que elimine los que no se usan */
                $template->setValue('entidad_fiscalizable', $auditoria->entidad_fiscalizable);
                $template->setValue('fecha_txt', $fechacomparecencia);
                $template->setValue('day01', $day01);
                $template->setValue('mes01', $mes01);
                $template->setValue('hora01', $hora01);
                $template->setValue('plazo', $auditoria->radicacion->plazo_maximo);
                $template->setValue('plazoMaximoletra', $plazomaxMin);
                $template->setValue('fechahoy', $fechaactual);
                $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
                $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
                $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
                $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
                $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
                $template->setValue('periodo',$auditoria->periodo_revision);

                $template->setValue('ambito01', $ambito01);
                $template->setValue('day02', $day02);
                $template->setValue('mes02', $mes02);
                $template->setValue('day03', $day03);
                $template->setValue('mes01', $mes03);
                $template->setValue('cierre',$auditoria->radicacion->fecha_cierre_auditoria);
                $template->setValue('iniciales',$iniciales);
                
                $nombreword='AR';/** */
    
            $template->saveAs($nombreword.'.docx');/** */
            }    
            if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero')
        {
            $template=new TemplateProcessor('bases-word/PAC/CUMPLIMIENTO_FINANCIERO/LIDER/1. AR_01.docx'); //*
            /**Se usan los mismos valores que en radicacionpdf por si se añaden despues algunos, ya estan solo que elimine los que no se usan */
            $template->setValue('entidad_fiscalizable', $auditoria->entidad_fiscalizable);
            $template->setValue('fecha_txt', $fechacomparecencia);
            $template->setValue('day01', $day01);
            $template->setValue('mes01', $mes01);
            $template->setValue('hora01', $hora01);
            $template->setValue('plazo', $auditoria->radicacion->plazo_maximo);
            $template->setValue('plazoMaximoletra', $plazomaxMin);
            $template->setValue('fechahoy', $fechaactual);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('ambito01', $ambito01);
            $template->setValue('day02', $day02);
            $template->setValue('mes02', $mes02);
            $template->setValue('day03', $day03);
            $template->setValue('mes03', $mes03);
            $template->setValue('cierre',$auditoria->radicacion->fecha_cierre_auditoria);
            $template->setValue('iniciales',$iniciales);

            
            $nombreword='AR';/** */  

        $template->saveAs($nombreword.'.docx');/** */
        }
        
		return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);/** */
    }

    public function radicacionWordOF()
    {
        $horaMin='';
        $minutosMin='';
        $fechacomparecencia='';
        $fechainicioaclaracion='';
        $fechaterminoaclaracion='';
        $auditoria=Auditoria::find(getSession('auditoria_id'));       
        $formatter = new NumeroALetras();

        $entidades=explode(' - ',$auditoria->entidad_fiscalizable);

         $txtentidad=null;

         if (count($entidades)>1) {
            if ($entidades[1]=='MUNICIPIOS') {
                $bar = ucwords($entidades[2]);       
                $bar = ucwords(strtolower($bar));

                $txtentidad='Municipio de '.$bar;
            }
         }
        $iniciales='';
        $nombre=auth()->user()->name;
        $esquemanombres=explode(' ',$nombre);
         foreach($esquemanombres as $parte){
            $iniciales=$iniciales.substr($parte, 0,1);
         }

         

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[(now()->format('n')) - 1];

        $horas=explode(':',$auditoria->comparecencia->hora_comparecencia_inicio); 
        if(empty($auditoria->comparecencia->fecha_comparecencia)){

            $hora = $formatter->toString($horas[0]);
            $minutos = $formatter->toString($horas[1]);
          
            $horaMax = ucwords($hora);             
            $horaMin = ucwords(strtolower($horaMax));
           
            $minutosMax = ucwords($minutos);            
            $minutosMin = ucwords(strtolower($minutosMax));
    
            $fechacomparecencia=fechaaletra($auditoria->comparecencia->fecha_comparecencia);        
            $fechainicioaclaracion=fechaaletra($auditoria->comparecencia->fecha_inicio_aclaracion);       
            $fechaterminoaclaracion=fechaaletra($auditoria->comparecencia->fecha_termino_aclaracion);
            }

        $fecha_hora=fecha(optional($auditoria->comparecencia)->fecha_comparecencia) . ' ' . date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_inicio)) . (empty($auditoria->comparecencia->hora_comparecencia_termino)?"":"-".date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_termino)));
        $date01 = fecha(optional($auditoria->comparecencia)->fecha_comparecencia);
        $day01 = date('d', strtotime($date01)); 
        $mes01 = $meses[($auditoria->comparecencia)->fecha_comparecencia->format('n') - 1]; 
        $day02 = date('d', strtotime($auditoria->comparecencia->fecha_inicio_aclaracion));
        $mes02 = $meses[($auditoria->comparecencia->fecha_inicio_aclaracion->format('n')) - 1]; 
        $day03 = date('d', strtotime($auditoria->comparecencia->fecha_termino_aclaracion));
        $mes03 = $meses[($auditoria->comparecencia->fecha_termino_aclaracion->format('n')) - 1];
        
        $hora01 =  date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_inicio));

        $formatterPM = new NumeroALetras();
        $plazomax=$formatter->toString($auditoria->radicacion->plazo_maximo);

        $plazomaxMax = ucwords($plazomax);            
        $plazomaxMin = ucwords(strtolower($plazomaxMax));

        $fechaactual=fechaaletra(now());
        $cierre = $auditoria->radicacion->fecha_cierre_auditoria;


        $nombre_ccp='';
        $info_ccp='';
        $infodom_ccp='';
        $info='';
        if ($auditoria->entidadFiscalizable->Ambito=='Estatal') {
            $nombre_ccp='</w:t><w:br/><w:t>Luis David Fernández Araya';
            $info_ccp='Subsecretario de Control y Evaluación de la Secretaría de la Contraloría del Gobierno del Estado de México. </w:t><w:br/><w:t>';
            $infodom_ccp='Domicilio: Av. Primero de Mayo, número 1731, Esquina Robert Bosch, Colonia Zona Industrial, C.P. 50071, Toluca, México.</w:t><w:br/><w:t>';
            $info=$info_ccp.' '.$infodom_ccp;
        }

        if($auditoria->entidadFiscalizable->Ambito=='Estatal'){
            $txt1 = '';
        }else{
            $txt1 = '115 fracción IV penúltimo párrafo';
        }
        if($auditoria->entidadFiscalizable->Ambito = 'Estatal'){
            $ambito01 = 5;
        }else{
            $ambito01=3;
        }

        if($auditoria->acto_fiscalizacion=='Inversión Física')
        {
            $template=new TemplateProcessor('bases-word/PAC/INVERSION_FISICA/LIDER/2. Of. AR_01.docx'); //*
            /**Se usan los mismos valores que en radicacionpdf por si se añaden despues algunos, ya estan solo que elimine los que no se usan */
            $template->setValue('entidad_fiscalizable', $auditoria->entidad_fiscalizable);
            $template->setValue('fecha_txt', $fechacomparecencia);
            $template->setValue('day01', $day01);
            $template->setValue('mes01', $mes01);
            $template->setValue('hora01', $hora01);
            $template->setValue('plazo', $auditoria->radicacion->plazo_maximo);
            $template->setValue('plazoMaximoletra', $plazomaxMin);
            $template->setValue('fechahoy', $fechaactual);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('cierre',$auditoria->radicacion->fecha_cierre_auditoria);

            $template->setValue('ambito01', $ambito01);
            $template->setValue('day02', $day02);
            $template->setValue('day03', $day03);
            $template->setValue('iniciales',$iniciales);

            $template->setValue('anio',date("Y"));
            $template->setValue('mes',$mes);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
            $template->setValue('entidad',$txtentidad);
            $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);
            $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
            $template->setValue('claves',count($auditoria->accionespras));
            $template->setValue('nombre_ccp',$nombre_ccp);
            $template->setValue('info_ccp',$info_ccp);
            $template->setValue('infodom_ccp',$infodom_ccp);
            $template->setValue('info',$info);
            $template->setValue('fecha_oficioAcuerdo', $auditoria->radicacion->fecha_oficio_acuerdo);
            $template->setValue('mes02', $mes02);
            $template->setValue('mes03', $mes03);
            $template->setValue('txt1',$txt1);

            $nombreword='Of. AR';/** */

        $template->saveAs($nombreword.'.docx');/** */
        }
         if($auditoria->acto_fiscalizacion=='Legalidad'){
            $template=new TemplateProcessor('bases-word/PAC/LEGALIDAD/LIDER/2. Of. AR_01.docx'); //*
            /**Se usan los mismos valores que en radicacionpdf por si se añaden despues algunos, ya estan solo que elimine los que no se usan */
            $template->setValue('entidad_fiscalizable', $auditoria->entidad_fiscalizable);
            $template->setValue('fecha_txt', $fechacomparecencia);
            $template->setValue('day01', $day01);
            $template->setValue('mes01', $mes01);
            $template->setValue('hora01', $hora01);
            $template->setValue('plazo', $auditoria->radicacion->plazo_maximo);
            $template->setValue('plazoMaximoletra', $plazomaxMin);
            $template->setValue('fechahoy', $fechaactual);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('periodo',$auditoria->periodo_revision);

            $template->setValue('ambito01', $ambito01);
            $template->setValue('day02', $day02);
            $template->setValue('day03', $day03);
            $template->setValue('cierre',$auditoria->radicacion->fecha_cierre_auditoria);
            $template->setValue('iniciales',$iniciales);

            $template->setValue('anio',date("Y"));
            $template->setValue('mes',$mes);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
            $template->setValue('entidad',$txtentidad);
            $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);
            $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
            $template->setValue('claves',count($auditoria->accionespras));
            $template->setValue('nombre_ccp',$nombre_ccp);
            $template->setValue('info_ccp',$info_ccp);
            $template->setValue('infodom_ccp',$infodom_ccp);
            $template->setValue('info',$info);
            $template->setValue('fecha_oficioAcuerdo', $auditoria->radicacion->fecha_oficio_acuerdo);
            $template->setValue('mes02', $mes02);
            $template->setValue('mes03', $mes03);
            $template->setValue('txt1',$txt1);
            
            $nombreword='Of. AR';/** */

        $template->saveAs($nombreword.'.docx');/** */
            }
            if($auditoria->acto_fiscalizacion=='Desempeño')
            {
                $template=new TemplateProcessor('bases-word/PAC/DESEMPEÑO/LIDER/2. Of. AR_01.docx'); //*

                /**Se usan los mismos valores que en radicacionpdf por si se añaden despues algunos, ya estan solo que elimine los que no se usan */
                $template->setValue('entidad_fiscalizable', $auditoria->entidad_fiscalizable);
                $template->setValue('fecha_txt', $fechacomparecencia);
                $template->setValue('day01', $day01);
                $template->setValue('mes01', $mes01);
                $template->setValue('hora01', $hora01);
                $template->setValue('plazo', $auditoria->radicacion->plazo_maximo);
                $template->setValue('plazoMaximoletra', $plazomaxMin);
                $template->setValue('fechahoy', $fechaactual);
                $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
                $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
                $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
                $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
                $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
                $template->setValue('periodo',$auditoria->periodo_revision);

                $template->setValue('ambito01', $ambito01);
                $template->setValue('day02', $day02);
                $template->setValue('day03', $day03);
                $template->setValue('cierre',$auditoria->radicacion->fecha_cierre_auditoria);
                $template->setValue('iniciales',$iniciales);
                
                $template->setValue('anio',date("Y"));
                $template->setValue('mes',$mes);
                $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
                $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
                $template->setValue('entidad',$txtentidad);
                $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);
                $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
                $template->setValue('claves',count($auditoria->accionespras));
                $template->setValue('nombre_ccp',$nombre_ccp);
                $template->setValue('info_ccp',$info_ccp);
                $template->setValue('infodom_ccp',$infodom_ccp);
                $template->setValue('info',$info);
                $template->setValue('fecha_oficioAcuerdo', $auditoria->radicacion->fecha_oficio_acuerdo);
                $template->setValue('mes02', $mes02);
                $template->setValue('mes03', $mes03);
                $template->setValue('txt1',$txt1);

                $nombreword='Of. AR';/** */
    
            $template->saveAs($nombreword.'.docx');/** */
            }    
            if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero')
            {
                $template=new TemplateProcessor('bases-word/PAC/CUMPLIMIENTO_FINANCIERO/LIDER/2. Of. AR_01.docx'); //*
                /**Se usan los mismos valores que en radicacionpdf por si se añaden despues algunos, ya estan solo que elimine los que no se usan */
                $template->setValue('entidad_fiscalizable', $auditoria->entidad_fiscalizable);
                $template->setValue('fecha_txt', $fechacomparecencia);
                $template->setValue('day01', $day01);
                $template->setValue('mes01', $mes01);
                $template->setValue('hora01', $hora01);
                $template->setValue('plazo', $auditoria->radicacion->plazo_maximo);
                $template->setValue('plazoMaximoletra', $plazomaxMin);
                $template->setValue('fechahoy', $fechaactual);
                $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
                $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
                $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
                $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
                $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
                $template->setValue('periodo',$auditoria->periodo_revision);

                $template->setValue('ambito01', $ambito01);
                $template->setValue('day02', $day02);
                $template->setValue('day03', $day03);
                $template->setValue('cierre',$auditoria->radicacion->fecha_cierre_auditoria);
                $template->setValue('iniciales',$iniciales);

                $template->setValue('anio',date("Y"));
                $template->setValue('mes',$mes);
                $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
                $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
                $template->setValue('entidad',$txtentidad);
                $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);
                $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
                $template->setValue('claves',count($auditoria->accionespras));
                $template->setValue('nombre_ccp',$nombre_ccp);
                $template->setValue('info_ccp',$info_ccp);
                $template->setValue('infodom_ccp',$infodom_ccp);
                $template->setValue('info',$info);
                $template->setValue('fecha_oficioAcuerdo', $auditoria->radicacion->fecha_oficio_acuerdo);
                $template->setValue('mes02', $mes02);
                $template->setValue('mes03', $mes03);
                $template->setValue('txt1',$txt1);

                $nombreword='Of. AR';/** */  

            $template->saveAs($nombreword.'.docx');/** */
            }
        
		return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);/** */
    }
    
    public function concluir(Radicacion $radicacion)
    {      

        Movimientos::create([
            'tipo_movimiento' => 'Registro de la radicación',
            'accion' => 'Radicación',
            'accion_id' => $radicacion->id,
            'estatus' => 'Aprobado',
            'usuario_creacion_id' => auth()->id(),
            'usuario_asignado_id' => auth()->id(),
        ]);

        if (strlen($radicacion->nivel_autorizacion) == 3) {
            $nivel_autorizacion = $radicacion->nivel_autorizacion;
        } else {
            $nivel_autorizacion = substr(auth()->user()->unidad_administrativa_id, 0, 4);
        }

        $radicacion->update(['fase_autorizacion' =>  'En validación', 'nivel_autorizacion' => $nivel_autorizacion]);

        $titulo = 'Validación de los datos de radicación';
        $mensaje = '<strong>Estimado (a) ' . auth()->user()->director->name . ', ' . auth()->user()->director->puesto . ':</strong><br>
                    Ha sido registrada la radicación de la auditoría No. ' . $radicacion->auditoria->numero_auditoria . ', por parte del ' .
                    auth()->user()->puesto.' '.auth()->user()->name . ', por lo que se requiere realice la validación.';

        auth()->user()->insertNotificacion($titulo, $mensaje, now(), auth()->user()->director->unidad_administrativa_id,auth()->user()->director->id);
        
    }
	
	 public function exportar_ar() 
    {
        $auditoria=Auditoria::find(getSession('auditoria_id'));       

        $entidades=explode(' - ',$auditoria->entidad_fiscalizable);

         $txtentidad=null;

         if (count($entidades)>1) {
            if ($entidades[1]=='MUNICIPIOS') {
                $bar = ucwords($entidades[2]);       
                $bar = ucwords(strtolower($bar));

                $txtentidad='Municipio de '.$bar;
            }
         }
        $iniciales='';
        $nombre=auth()->user()->name;
        $esquemanombres=explode(' ',$nombre);
         foreach($esquemanombres as $parte){
            $iniciales=$iniciales.substr($parte, 0,1);
         }

         

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[(now()->format('n')) - 1];

        $nombre_ccp='';
        $info_ccp='';
        $infodom_ccp='';
        $info='';
        if ($auditoria->entidadFiscalizable->Ambito=='Estatal') {
            $nombre_ccp='</w:t><w:br/><w:t>Luis David Fernández Araya';
            $info_ccp='Subsecretario de Control y Evaluación de la Secretaría de la Contraloría del Gobierno del Estado de México. </w:t><w:br/><w:t>';
            $infodom_ccp='Domicilio: Av. Primero de Mayo, número 1731, Esquina Robert Bosch, Colonia Zona Industrial, C.P. 50071, Toluca, México.</w:t><w:br/><w:t>';
            $info=$info_ccp.' '.$infodom_ccp;
        }
        if($auditoria->acto_fiscalizacion=='Inversión Física')
        {
            $template=new TemplateProcessor('bases-word/PAC/INVERSION_FISICA/LIDER/1. AR.docx'); //*
            $template->setValue('anio',date("Y"));
            $template->setValue('mes',$mes);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
            $template->setValue('entidad',$txtentidad);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);       
            $template->setValue('nombre_ccp',$nombre_ccp);
            $template->setValue('info_ccp',$info_ccp);
            $template->setValue('infodom_ccp',$infodom_ccp);
            $template->setValue('info',$info);
            $template->setValue('iniciales',$iniciales);
            $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
            
            $nombreword='AR';/** */

        $template->saveAs($nombreword.'.docx');/** */
        }
         if($auditoria->acto_fiscalizacion=='Legalidad'){
            $template=new TemplateProcessor('bases-word/PAC/LEGALIDAD/LIDER/1. AR.docx'); //*
            $template->setValue('anio',date("Y"));
            $template->setValue('mes',$mes);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
            $template->setValue('entidad',$txtentidad);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);       
            $template->setValue('nombre_ccp',$nombre_ccp);
            $template->setValue('info_ccp',$info_ccp);
            $template->setValue('infodom_ccp',$infodom_ccp);
            $template->setValue('info',$info);
            $template->setValue('iniciales',$iniciales);
            $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
            
            $nombreword='AR';/** */

        $template->saveAs($nombreword.'.docx');/** */
            }
            if($auditoria->acto_fiscalizacion=='Desempeño')
            {
                $template=new TemplateProcessor('bases-word/PAC/DESEMPEÑO/LIDER/1. AR.docx'); //*
                $template->setValue('anio',date("Y"));
                $template->setValue('mes',$mes);
                $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
                $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
                $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
                $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
                $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
                $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
                $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
                $template->setValue('entidad',$txtentidad);
                $template->setValue('periodo',$auditoria->periodo_revision);
                $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);       
                $template->setValue('nombre_ccp',$nombre_ccp);
                $template->setValue('info_ccp',$info_ccp);
                $template->setValue('infodom_ccp',$infodom_ccp);
                $template->setValue('info',$info);
                $template->setValue('iniciales',$iniciales);
                $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
                
                $nombreword='AR';/** */
    
            $template->saveAs($nombreword.'.docx');/** */
            }    
            if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero')
        {
            $template=new TemplateProcessor('bases-word/PAC/CUMPLIMIENTO_FINANCIERO/LIDER/1. AR.docx'); //*
            $template->setValue('anio',date("Y"));
            $template->setValue('mes',$mes);
            $template->setValue('orden_auditoria',$auditoria->radicacion->num_memo_recepcion_expediente);
            $template->setValue('numero_auditoria',$auditoria->numero_auditoria);
            $template->setValue('numero_expediente',$auditoria->radicacion->numero_expediente);
            $template->setValue('numero_oficio',$auditoria->radicacion->numero_acuerdo);
            $template->setValue('remitente',$auditoria->comparecencia->nombre_titular);
            $template->setValue('remitente_cargo',$auditoria->comparecencia->cargo_titular);
            $template->setValue('remitente_domicilio',$auditoria->comparecencia->notificacion_estados);
            $template->setValue('entidad',$txtentidad);
            $template->setValue('periodo',$auditoria->periodo_revision);
            $template->setValue('tipo_auditoria',$auditoria->tipo_auditoria->descripcion);       
            $template->setValue('nombre_ccp',$nombre_ccp);
            $template->setValue('info_ccp',$info_ccp);
            $template->setValue('infodom_ccp',$infodom_ccp);
            $template->setValue('info',$info);
            $template->setValue('iniciales',$iniciales);
            $template->setValue('ambito',$auditoria->entidadFiscalizable->Ambito);
            
            $nombreword='AR';/** */  

        $template->saveAs($nombreword.'.docx');/** */
        }
        
		return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);/** */
        
    }
}
