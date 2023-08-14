<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Comparecencia;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ComparecenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auditorias = $this->setQuery($request)->orderBy('id')->paginate(30);
               
        return view('comparecencia.index', compact('auditorias', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditoria = Auditoria::find(getSession('comparecencia_auditoria_id'));
        $comparecencia = new Comparecencia();      
        $mindate = Carbon::now()->format('Y-m-d');
        $horas = ['' => 'Seleccionar hora'];
        $accion='Agregar';

        return view('comparecencia.form', compact('auditoria', 'comparecencia', 'mindate', 'horas','accion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auditoria = Auditoria::find(getSession('comparecencia_auditoria_id'));
        mover_archivos($request, ['acta_comparecencia']);
        $auditoria = Auditoria::find(getSession('comparecencia_auditoria_id'));
        mover_archivos($request, ['oficio_acreditacion']);


        $request['usuario_creacion_id'] = auth()->id();
        $request['auditoria_id'] = $auditoria->id;   
        //DMM//$request['fecha_inicio_aclaracion'] = addBusinessDays($request->fecha_comparecencia, 1);
        //DMM//$request['fecha_termino_aclaracion'] = addBusinessDays($request->fecha_inicio_aclaracion, 30);

        //$ruta = env('APP_RUTA_MINIO').'Auditorias/' . strtoupper(Str::slug($auditoria->numero_auditoria)).'/Documentos';
        //mover_archivos_minio($request, ['oficio_comparecencia'], null, $ruta);

        $comparecencia = Comparecencia::create($request->all());
        setMessage('Los datos se han guardado correctamente');

        return redirect()->route('comparecencianotificacion.edit', $comparecencia);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comparecencia $comparecencia)
    {     
        $auditoria = $comparecencia->auditoria; 

        return view('comparecencia.show', compact('auditoria', 'comparecencia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Comparecencia $comparecencia)
    {
        $auditoria = $comparecencia->auditoria;     
        $mindate = Carbon::now()->format('Y-m-d');
        $horas = ['' => 'Seleccionar hora'];
        $accion='Editar';       

        return view('comparecencia.form', compact('auditoria', 'comparecencia', 'mindate', 'horas','accion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comparecencia $comparecencia)
    {
        $auditoria = Auditoria::find(getSession('comparecencia_auditoria_id'));
        mover_archivos($request, ['oficio_acta'],$comparecencia);
        $auditoria = Auditoria::find(getSession('comparecencia_auditoria_id'));
        mover_archivos($request, ['oficio_acreditacion'],$comparecencia);

        $request['usuario_modificacion_id'] = auth()->id();      
       /* $request['fecha_inicio_aclaracion'] = addBusinessDays($request->fecha_comparecencia, 1);
        $request['fecha_termino_aclaracion'] = addBusinessDays($request->fecha_inicio_aclaracion, 30);*/
       
        //$ruta = env('APP_RUTA_MINIO').'Auditorias/' . strtoupper(Str::slug($auditoria->numero_auditoria)).'/Documentos';
        //mover_archivos_minio($request, ['oficio_comparecencia'], null, $ruta);

        $comparecencia->update($request->all());
       
        setMessage('Los datos se han guardado correctamente');

        return redirect()->route('comparecencianotificacion.edit', $comparecencia);
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

    public function setQuery(Request $request)
    {        
         $query = new Auditoria;         
        
        if(in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
           in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())||
           in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())){   

            $query = $query->whereNotNull('fase_autorizacion')
            ->where('fase_autorizacion','Autorizado');

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

    public function auditoria(Auditoria $auditoria)
    {
        setSession('comparecencia_auditoria_id',$auditoria->id);

        return redirect()->route('comparecencia.create');
    }
}
