<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\CatalogoUMAS;
use App\Models\Comparecencia;
use App\Models\ListadoEntidades;
use App\Models\Movimientos;
use App\Models\Radicacion;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Luecano\NumeroALetras\NumeroALetras;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;


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
        mover_archivos($request, ['oficio_acuerdo','oficio_designacion'], null);
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
        mover_archivos($request, ['oficio_acuerdo','oficio_designacion'], $radicacion);
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
        $datenow = Carbon::now();
        $datenow01 = date('Y', strtotime($datenow));

        $ent = $auditoria->entidad_fiscalizable;
        $frac ='';
        if($auditoria->entidadFiscalizable->Ambito = 'Estatal'){
            if (stripos($ent, 'poder') !== false) {
                $frac = 'fracción I.';
            }elseif(stripos($ent, 'órganos autónomos') !== false) {
                $frac = 'fracción III.';
            }elseif(stripos($ent, 'organismo auxiliar') !== false){
                $frac = 'fracción IV.';
            }elseif(stripos($ent, 'fideicomiso') !== false){
                $frac = 'fracción V.';
            }else{
                $frac = '';
            }
            $txt1 = '';
            $ambito01 = 5;

        }elseif($auditoria->entidadFiscalizable->Ambito = 'Municipal'){
            if (stripos($ent, 'fideicomiso') !== false) {
                $frac = 'fracción V.';
            }elseif(stripos($ent, 'organismo auxiliar') !== false) {
                $frac = 'fracción IV.';
            }elseif(stripos($ent, 'municipios') !== false){
                $frac = 'fracción II.';
            }else{
                $frac = '';
            }
            $txt1 = '115 fracción IV penúltimo párrafo';
            $ambito01=3;
        }
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

        $UMA = CatalogoUMAS::where('ejercicio', $datenow01)->select('texto')->first();

        $UMATEXT = $UMA->texto;
        $SiRecomendaciones = "";
        $SiRecomendaciones01 ='';
        $SiRecomendaciones02 ='';
        $SiRecomendaciones03 ='';
        $SiRecomendaciones04 ='';
        $SiPRAS="";
        $SiPRAS01="";
        $SiPRAS02="";
        $SiPliegos01="";
        $Orden = '';
        $Orden01 = '';
        $Orden02 = '';

        if($auditoria->acto_fiscalizacion=='Inversión Física'){
            $Orden = 'CUARTO.';
            if(count($auditoria->accionesrecomendaciones)>0){
                $SiRecomendaciones = '54 Bis';
                $SiRecomendaciones01 = 'y XXIII Bis';
                $SiRecomendaciones03 = 'y del Proceso de Atención a las Recomendaciones ';
                $SiRecomendaciones04 = '; asimismo, se informe de las mejoras realizadas y las acciones emprendidas con relación a las recomendaciones de mérito, o en su caso, justifique su improcedencia, con el apercibimiento de que en caso de no dar cumplimiento en el plazo concedido, se entenderán por no atendidas ni justificadas dichas recomendaciones';
            }
            if(count($auditoria->accionespras)>0){
                $SiPRAS="XIX, XXV";
                $SiPRAS01 = $Orden.' Con fundamento en lo previsto en los artículos 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México; 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y; 23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, túrnese por oficio al Órgano Interno de Control de '. $nombreEntidad. ' o a su equivalente, las Promociones de Responsabilidad Administrativa Sancionatoria (PRAS) que se desprenden de los resultados obtenidos del acto de fiscalización de mérito, así como, su soporte documental correspondiente en copias certificadas, para el efecto de que dicha autoridad continúe con las investigaciones pertinentes y promueva las acciones procedentes. ';
                $SiPRAS02="y al Órgano Interno de Control de".$nombreEntidad;
                $Orden = 'QUINTO.';
            }
            if($Orden == 'QUINTO.'){
                $Orden01 = 'SEXTO.';
                $Orden02 = 'SEPTIMO.';
            }elseif($Orden == 'CUARTO.'){
                $Orden01 = 'QUINTO.';
                $Orden02 = 'SEXTO.';
            }

        }elseif($auditoria->acto_fiscalizacion=='Legalidad'){
            $Orden = 'QUINTO.';
            if(count($auditoria->accionesrecomendaciones)>0){
                $SiRecomendaciones = '54 Bis';
                $SiRecomendaciones01 = 'y XXIII Bis';
            }
            if(count($auditoria->accionespo)){
                $SiPliegos01 = $Orden.' Se ordena el inicio de la Etapa de Aclaración  de las observaciones subsistentes en materia de Legalidad y, que se encuentran detalladas en el Informe de Auditoría; por lo cual, con fundamento en lo dispuesto en el artículo 54 fracción I de la Ley de Fiscalización Superior del Estado de México, se concede a la entidad fiscalizada un plazo de 30 (Treinta) días hábiles contados a partir del día '. $day02. ' de '. $mes02. ' y que fenece el '. $day03. ' de '. $mes03. ', a efecto de que se presenten los elementos, documentos y datos fehacientes que aclaren o solventen el contenido de las acciones  de cuenta, o en su caso, manifieste lo que a su derecho convenga';
                $Orden = 'SEXTO.';
            }
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


        $relacion4=[
            'horastxt'=> $horaMin,
            'mintxt'=> $minutosMin,
            'fechacomparecenciatxt'=>$fechacomparecencia,
            'fechainicioaclaraciontxt'=>$fechainicioaclaracion,
            'fechaterminoaclaraciontxt'=>$fechaterminoaclaracion,
            'plazomaximo'=>$plazomaxMin,
            'fechaactual'=>$fechaactual,
        ];

        $datosConstancia = [
            'nombrereporte' => 'radicacionconstancia',
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
        $day02 = date('d', strtotime($auditoria->comparecencia->fecha_inicio_aclaracion));
        $day03 = date('d', strtotime($auditoria->comparecencia->fecha_termino_aclaracion));


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
        if($auditoria->acto_fiscalizacion=='Inversión Física')
        {
            $template=new TemplateProcessor('bases-word/PAC/INVERSION_FISICA/LIDER/1. AR_01.docx'); //*
            /**Se usan los mismos valores que en radicacionpdf por si se añaden despues algunos, ya estan solo que elimine los que no se usan */
            $template->setValue('entidad_fiscalizable', $auditoria->entidad_fiscalizable);
            $template->setValue('fecha_txt', $fechacomparecencia);
            $template->setValue('day01', $day01);
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

            if($auditoria->entidadFiscalizable->Ambito = 'Estatal'){
                $ambito01 = 5;
            }else{
                $ambito01=3;
            }
            $template->setValue('ambito01', $ambito01);
            $template->setValue('day02', $day02);
            $template->setValue('day03', $day03);
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
            if($auditoria->entidadFiscalizable->Ambito = 'Estatal'){
                $ambito01 = 5;
            }else{
                $ambito01=3;
            }
            $template->setValue('ambito01', $ambito01);
            $template->setValue('day02', $day02);
            $template->setValue('day03', $day03);
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
                if($auditoria->entidadFiscalizable->Ambito = 'Estatal'){
                    $ambito01 = 5;
                }else{
                    $ambito01=3;
                }
                $template->setValue('ambito01', $ambito01);
                $template->setValue('day02', $day02);
                $template->setValue('day03', $day03);
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
            if($auditoria->entidadFiscalizable->Ambito = 'Estatal'){
                $ambito01 = 5;
            }else{
                $ambito01=3;
            }
            $template->setValue('ambito01', $ambito01);
            $template->setValue('day02', $day02);
            $template->setValue('day03', $day03);
            $template->setValue('cierre',$auditoria->radicacion->fecha_cierre_auditoria);
            $template->setValue('iniciales',$iniciales);


            $nombreword='AR';/** */

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
