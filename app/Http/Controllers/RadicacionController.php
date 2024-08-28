<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Comparecencia;
use App\Models\Movimientos;
use App\Models\Radicacion;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

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

    public function export(){
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

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[(now()->format('n')) - 1];

        $template=new TemplateProcessor('bases-word/IA_AR.docx');
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

        $nombreword='AIAR';

        $template->saveAs($nombreword.'.docx');

        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
    }
  
  
    public function exportOIC(){
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

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $meses[(now()->format('n')) - 1];

        $template=new TemplateProcessor('bases-word/AR_OIC.docx');
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



        $nombreword='AROIC';

        $template->saveAs($nombreword.'.docx');

        return response()->download($nombreword.'.docx')->deleteFileAfterSend(true);
    }
}
