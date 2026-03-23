<?php

namespace App\Http\Controllers\Buzones;

use App\Http\Controllers\Controller;
use App\Models\CatalogoUnidadesAdministrativas;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Auditoria;


class BuzonSeguimiento extends Controller
{
    protected $model;

    public function __construct(Auditoria $model)
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
        $auditorias = $this->setQuery($request)->orderByRaw("TO_NUMBER(REGEXP_SUBSTR(numero_auditoria, '\\d+'))");
        $auditorias = $auditorias->paginate(30);
        $ids = [122110, 122120, 122130, 122210, 122220, 122230];
        $departamentosconsulta = CatalogoUnidadesAdministrativas::whereIn('id', $ids)->pluck('descripcion','id')->toArray();
        $departamentos = ['' => ''] + $departamentosconsulta;
        $liderconsulta = User::where('siglas_rol', 'LP')->pluck('name', 'id')->toArray();
        $lideres = ['' => ''] + $liderconsulta;
        $analistaconsulta = User::where('siglas_rol', 'ANA')->pluck('name', 'id')->toArray();
        $analistas = ['' => ''] + $analistaconsulta;

        return view('Buzones.buzonseguimiento.index', compact('auditorias', 'request', 'departamentos', 'lideres', 'analistas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, )
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $buzon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
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
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        dd("destroy");

    }
	

    
    public function setQuery(Request $request) 
    {
         $query = $this->model;
		 $query = $query->where('cuenta_publica',getSession('cp'));
        
         if(in_array("Staff Juridico", auth()->user()->getRoleNames()->toArray())){
            $query = $query->whereHas('auditoriausuarios', function($q){
               
                $q->where("staff_id", auth()->user()->id); 
                
            });
         }
         if(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){
            //$query = $query->where('departamento_encargado_id',auth()->user()->unidad_administrativa_id);
            if(getSession('cp')!=2023 || getSession('cp')!=2024 ){
                $query = $query->where(function ($queryJDE) {
                    $queryJDE->where('departamento_encargado_id', auth()->user()->unidad_administrativa_id)
                    ->orWhere(function ($queryJDA) {
                        $queryJDA->whereHas('acciones', function($q){
                                $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
                                $q = $q->whereNotNull('segauditorias.fase_autorizacion')->whereRaw('LOWER(departamento_asignado_id) LIKE (?) ',["%{$unidadAdministrativa}%"])->whereNotNull('segauditorias.nivel_autorizacion');
    
                        });
                    });
    
                });    
            }else{
                $query = $query->where(function ($queryJDE) {
                    $queryJDE->where('departamento_encargado_id', auth()->user()->cp_ua2023 || auth()->user()->cp_ua2024 );
                });
            }
         }
         if(getSession('cp')==2022){
			//dd(2022);
            $query = $query->whereHas('acciones', function($q){
                if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){
                    $q = $q->where('analista_asignado_id',auth()->user()->id);
                }
                if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){
                    $userLider=auth()->user();
                    $q = $q->whereRaw('LOWER(lider_asignado_id) LIKE (?) ',["%{$userLider->id}%"])->whereNotNull('segauditorias.fase_autorizacion');
                }

            });
        }else{            
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

        if(auth()->user()->siglas_rol == 'UC'){
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
        if ($request->filled('direccionaud') && $request->input('direccionaud') !='Todas') {
            //$direccionaud=$request->input('direccionaud');
            $query = $query->whereRaw('LOWER(direccion_asignada_id) LIKE (?) ',["%{$request->input('direccionaud')}%"]);
        }
        if ($request->filled('departamentoasig')) {
            $query = $query->whereRaw('LOWER(departamento_encargado_id) LIKE (?) ',["%{$request->input('departamentoasig')}%"]);
        }
        if ($request->filled('liderasig')) {
            $query = $query->whereRaw('LOWER(lidercp_id) LIKE (?) ',["%{$request->input('liderasig')}%"]);
        }
        if ($request->filled('analistaasig')) {
            $query = $query->whereRaw('LOWER(analistacp_id) LIKE (?) ',["%{$request->input('analistaasig')}%"]);
        }
        return $query;
    }



}
