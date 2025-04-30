<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\TurnoArchivoTransferencia;
use Illuminate\Http\Request;

class InicioArchivoTransferenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Auditoria $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {
        $auditorias = $this->setQuery($request)->orderBy('id')->paginate(30);

        return view('inicioarchivotransferencia.index', compact('auditorias', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {      
    //
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
    public function edit(Auditoria $auditoria)
    {
        setSession ('auditoriatat_id', $auditoria->id);
        // dd($auditoria);
        if(empty($auditoria->turnoarchivotransferencia)){    
    //    dd(1);    
        return redirect()-> route('turnoarchivotransferencia.create');
        }else{ 
            return redirect()-> route('turnoarchivotransferencia.index');
            dd(2);
        }
        
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
    public function setQuery(Request $request) 
    {
         $query = $this->model;
		 $query = $query->where('cuenta_publica',getSession('cp'));
         $query = $query->whereHas('turnoarchivo');

         if(in_array("Staff Juridico", auth()->user()->getRoleNames()->toArray())){
            $query = $query->whereHas('auditoriausuarios', function($q){
               
                $q->where("staff_id", auth()->user()->id); 
                
            });
         }
         if(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){
            //$query = $query->where('departamento_encargado_id',auth()->user()->unidad_administrativa_id);
            if(getSession('cp')!=2023){
                $query = $query->where(function ($queryJDE) {
                    $queryJDE->where('departamento_encargado_id', auth()->user()->unidad_administrativa_id)
                    ->orWhere(function ($queryJDA) {
                        $queryJDA->whereHas('acciones', function($q){
                                $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
                                $q = $q->whereNotNull('fase_autorizacion')->whereRaw('LOWER(departamento_asignado_id) LIKE (?) ',["%{$unidadAdministrativa}%"])->whereNotNull('nivel_autorizacion');
    
                        });
                    });
    
                });    
            }else{
                $query = $query->where(function ($queryJDE) {
                    $queryJDE->where('departamento_encargado_id', auth()->user()->cp_ua2023);
                });
            }
         }

         if(getSession('cp')!=2023){
            $query = $query->whereHas('acciones', function($q){
                if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){
                    $q = $q->where('analista_asignado_id',auth()->user()->id);
                }
                if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){
                    $userLider=auth()->user();
                    $q = $q->whereRaw('LOWER(lider_asignado_id) LIKE (?) ',["%{$userLider->id}%"])->whereNotNull('fase_autorizacion');
                }
            });
        }
        else{            
            if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){              
                $query = $query->where('analistacp_id',auth()->user()->id);
            }
            if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){              
                $query = $query->where('lidercp_id',auth()->user()->id);
            }
        }
		if(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())){
             $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
             $query = $query->whereRaw('LOWER(direccion_asignada_id) LIKE (?) ',["%{$unidadAdministrativa}%"]);
         }

        if( in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())||
            in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
            in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())){
            $query = $query->whereNotNull('fase_autorizacion')->where('fase_autorizacion','Autorizado');
        }

        if(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())){
            $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
            $query = $query->whereRaw('LOWER(direccion_asignada_id) LIKE (?) ',["%{$unidadAdministrativa}%"]);
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

    public function accionesConsulta(Auditoria $auditoria)
    {
        setSession('acciones_auditoria_id',$auditoria->id);

        return  redirect()->route('auditoriaseguimientoacciones.index');
    }

    public function seleccionarauditoria (Auditoria $auditoria)
    {
        setSession('auditoriacp_id',$auditoria->id);

        return  redirect()->route('agregaracciones.index');
    }

}
