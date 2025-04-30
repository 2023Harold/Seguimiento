<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\TurnoArchivoTransferencia;
use Illuminate\Http\Request;

class TurnoArchivoTransferenciaController extends Controller
{
    protected $model;
    public function __construct(TurnoArchivoTransferencia $model)
       {
           $this -> model = $model;
       }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auditoria = Auditoria :: find(getSession('auditoriatat_id'));
        $turnoarchivotransferencia=TurnoArchivoTransferencia::where('auditoria_id',getSession('auditoriatat_id'))->first();
        // //dd($turnotransferencia);


        return view ('turnotransferencia.index', compact('request','auditoria', 'turnoarchivotransferencia'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd(1);
        $auditoria = Auditoria::find(getSession('auditoriatat_id'));
        // dd($auditoria,getSession('auditoriatat_id' ));

        $turnoarchivotransferencia = new TurnoArchivoTransferencia();
        return view('turnotransferencia.form', compact('auditoria','turnoarchivotransferencia'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    // dd($auditoria);
  
    mover_archivos($request, ['TurnoTransferencia']);
    $request['usuario_creacion_id'] = auth()->user()->id;
    $request['usuario_modificacion_id'] = auth()->user()->id;
    $request['auditoria_id'] = getSession('auditoriatat_id');
    $turnoarchivotransferencia  = TurnoArchivoTransferencia::create($request->all());
    // dd($turnoarchivotransferencia);
      setMessage("Los datos del archivo de transferencia se han guardado correctamente.");

      return redirect() -> route('turnoarchivotransferencia.index');
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
    public function edit(TurnoArchivoTransferencia $auditoria)
    {
        $turnoarchivotransferencia=$auditoria;
        $auditoria=$auditoria->auditoria;
        return view('turnotransferencia.form', compact('turnoarchivotransferencia', 'auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TurnoArchivoTransferencia $auditoria)
    {
        // dd(getSession('auditoria_id'));
      $turnoarchivotransferencia=$auditoria;
    //   $request['auditoria_id']= getSession('auditoria_id');
      mover_archivos($request, ['TurnoTransferencia']);
      $request['usuario_modificacion_id'] = auth()->user()->id;
      $turnoarchivotransferencia->update($request->all());
    // dd($turnotransferencia);
      $auditoria=$turnoarchivotransferencia->auditoria;
        setMessage('Los datos de archivo trasferencia se han guardado correctamente');

        return redirect() -> route('turnoarchivotransferencia.index');
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
    private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }
    public function auditoria(Auditoria $auditoria)
    {
        setSession('turnotransferencia_auditoria_id',$auditoria->id);

        return redirect()->route('turnoarchivotransferencia.create');
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
    private function mensajeRechazo(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .'Ha sido rechazado el registro del Turno acuse envío archivo de la auditoría No. '.$numeroauditoria.'.';

        return $mensaje;
    }
    private function mensajeAprobado(String $nombre, String $puesto, String $numeroauditoria)
    {
        $mensaje = '<strong>Estimado(a) '.$nombre.', '.$puesto.':</strong><br>'
                    .' Ha sido autorizado el registro de radicación de la auditoría No. '.$numeroauditoria.
                    ', por parte del Titular.';

        return $mensaje;
    }

    }
