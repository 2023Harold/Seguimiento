<?php


namespace App\Http\Controllers\Cedulas;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\SUTIC\EntidadFiscalizableIntra;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CedulaInicialController extends Controller
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
        $auditoria = Auditoria::find(getSession('auditoria_id'));   
        
        $accionesanalistasFaltantes=AuditoriaAccion::whereNull('aprobar_cedini_analista')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesanalistasListos=AuditoriaAccion::whereNotNull('aprobar_cedini_analista')->where('segauditoria_id',$auditoria->id)->get();  
        $accionesLideresFaltantes=AuditoriaAccion::whereNull('aprobar_cedini_lider')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesLideresListos=AuditoriaAccion::whereNotNull('aprobar_cedini_lider')->where('segauditoria_id',$auditoria->id)->get();  
        $accionesJefesFaltantes=AuditoriaAccion::whereNull('aprobar_cedini_jefe')->where('segauditoria_id',$auditoria->id)->get();      
        $accionesJefesListos=AuditoriaAccion::whereNotNull('aprobar_cedini_jefe')->where('segauditoria_id',$auditoria->id)->get();       
        $analistasF=array_unique($accionesanalistasFaltantes->pluck('analista_asignado_id', 'id')->toArray());
        $analistasL=array_unique($accionesanalistasListos->pluck('analista_asignado_id', 'id')->toArray());        
        $lideresF=array_unique($accionesLideresFaltantes->pluck('lider_asignado_id', 'id')->toArray());
        $lideresL=array_unique($accionesLideresListos->pluck('lider_asignado_id', 'id')->toArray());        
        $jefesF=array_unique($accionesJefesFaltantes->pluck('departamento_asignado_id', 'id')->toArray());
        $jefesL=array_unique($accionesJefesListos->pluck('departamento_asignado_id', 'id')->toArray());    

        $raccionesanalistasFaltantes=AuditoriaAccion::whereNull('aprobar_cedrec_analista')->where('segauditoria_id',$auditoria->id)->get();      
        $raccionesanalistasListos=AuditoriaAccion::whereNotNull('aprobar_cedrec_analista')->where('segauditoria_id',$auditoria->id)->get();  
        $raccionesLideresFaltantes=AuditoriaAccion::whereNull('aprobar_cedrec_lider')->where('segauditoria_id',$auditoria->id)->get();      
        $raccionesLideresListos=AuditoriaAccion::whereNotNull('aprobar_cedrec_lider')->where('segauditoria_id',$auditoria->id)->get();  
        $raccionesJefesFaltantes=AuditoriaAccion::whereNull('aprobar_cedrec_jefe')->where('segauditoria_id',$auditoria->id)->get();      
        $raccionesJefesListos=AuditoriaAccion::whereNotNull('aprobar_cedrec_jefe')->where('segauditoria_id',$auditoria->id)->get();       
        $ranalistasF=array_unique($raccionesanalistasFaltantes->pluck('analista_asignado_id', 'id')->toArray());
        $ranalistasL=array_unique($raccionesanalistasListos->pluck('analista_asignado_id', 'id')->toArray());        
        $rlideresF=array_unique($raccionesLideresFaltantes->pluck('lider_asignado_id', 'id')->toArray());
        $rlideresL=array_unique($raccionesLideresListos->pluck('lider_asignado_id', 'id')->toArray());        
        $rjefesF=array_unique($raccionesJefesFaltantes->pluck('departamento_asignado_id', 'id')->toArray());
        $rjefesL=array_unique($raccionesJefesListos->pluck('departamento_asignado_id', 'id')->toArray());   

        $prasaccionesanalistasFaltantes=AuditoriaAccion::whereNull('aprobar_cedpras_analista')->where('segauditoria_id',$auditoria->id)->get();      
        $prasaccionesanalistasListos=AuditoriaAccion::whereNotNull('aprobar_cedpras_analista')->where('segauditoria_id',$auditoria->id)->get();  
        $prasaccionesLideresFaltantes=AuditoriaAccion::whereNull('aprobar_cedpras_lider')->where('segauditoria_id',$auditoria->id)->get();      
        $prasaccionesLideresListos=AuditoriaAccion::whereNotNull('aprobar_cedpras_lider')->where('segauditoria_id',$auditoria->id)->get();  
        $prasaccionesJefesFaltantes=AuditoriaAccion::whereNull('aprobar_cedpras_jefe')->where('segauditoria_id',$auditoria->id)->get();      
        $prasaccionesJefesListos=AuditoriaAccion::whereNotNull('aprobar_cedpras_jefe')->where('segauditoria_id',$auditoria->id)->get();       
        $prasanalistasF=array_unique($prasaccionesanalistasFaltantes->pluck('analista_asignado_id', 'id')->toArray());
        $prasanalistasL=array_unique($prasaccionesanalistasListos->pluck('analista_asignado_id', 'id')->toArray());        
        $praslideresF=array_unique($prasaccionesLideresFaltantes->pluck('lider_asignado_id', 'id')->toArray());
        $praslideresL=array_unique($prasaccionesLideresListos->pluck('lider_asignado_id', 'id')->toArray());        
        $prasjefesF=array_unique($prasaccionesJefesFaltantes->pluck('departamento_asignado_id', 'id')->toArray());
        $prasjefesL=array_unique($prasaccionesJefesListos->pluck('departamento_asignado_id', 'id')->toArray());   

        $caaccionesanalistasFaltantes=AuditoriaAccion::whereNull('aprobar_cedana_analista')->where('segauditoria_id',$auditoria->id)->get();      
        $caaccionesanalistasListos=AuditoriaAccion::whereNotNull('aprobar_cedana_analista')->where('segauditoria_id',$auditoria->id)->get();  
        $caaccionesLideresFaltantes=AuditoriaAccion::whereNull('aprobar_cedana_lider')->where('segauditoria_id',$auditoria->id)->get();      
        $caaccionesLideresListos=AuditoriaAccion::whereNotNull('aprobar_cedana_lider')->where('segauditoria_id',$auditoria->id)->get();  
        $caaccionesJefesFaltantes=AuditoriaAccion::whereNull('aprobar_cedana_jefe')->where('segauditoria_id',$auditoria->id)->get();      
        $caaccionesJefesListos=AuditoriaAccion::whereNotNull('aprobar_cedana_jefe')->where('segauditoria_id',$auditoria->id)->get();       
        $caanalistasF=array_unique($caaccionesanalistasFaltantes->pluck('analista_asignado_id', 'id')->toArray());
        $caanalistasL=array_unique($caaccionesanalistasListos->pluck('analista_asignado_id', 'id')->toArray());        
        $calideresF=array_unique($caaccionesLideresFaltantes->pluck('lider_asignado_id', 'id')->toArray());
        $calideresL=array_unique($caaccionesLideresListos->pluck('lider_asignado_id', 'id')->toArray());        
        $cajefesF=array_unique($caaccionesJefesFaltantes->pluck('departamento_asignado_id', 'id')->toArray());
        $cajefesL=array_unique($caaccionesJefesListos->pluck('departamento_asignado_id', 'id')->toArray());    
        
        $cadesaccionesanalistasFaltantes=AuditoriaAccion::whereNull('aprobar_cedanades_analista')->where('segauditoria_id',$auditoria->id)->get();      
        $cadesaccionesanalistasListos=AuditoriaAccion::whereNotNull('aprobar_cedanades_analista')->where('segauditoria_id',$auditoria->id)->get();  
        $cadesaccionesLideresFaltantes=AuditoriaAccion::whereNull('aprobar_cedanades_lider')->where('segauditoria_id',$auditoria->id)->get();      
        $cadesaccionesLideresListos=AuditoriaAccion::whereNotNull('aprobar_cedanades_lider')->where('segauditoria_id',$auditoria->id)->get();  
        $cadesaccionesJefesFaltantes=AuditoriaAccion::whereNull('aprobar_cedanades_jefe')->where('segauditoria_id',$auditoria->id)->get();      
        $cadesaccionesJefesListos=AuditoriaAccion::whereNotNull('aprobar_cedanades_jefe')->where('segauditoria_id',$auditoria->id)->get();       
        $cadesanalistasF=array_unique($cadesaccionesanalistasFaltantes->pluck('analista_asignado_id', 'id')->toArray());
        $cadesanalistasL=array_unique($cadesaccionesanalistasListos->pluck('analista_asignado_id', 'id')->toArray());        
        $cadeslideresF=array_unique($cadesaccionesLideresFaltantes->pluck('lider_asignado_id', 'id')->toArray());
        $cadeslideresL=array_unique($cadesaccionesLideresListos->pluck('lider_asignado_id', 'id')->toArray());        
        $cadesjefesF=array_unique($cadesaccionesJefesFaltantes->pluck('departamento_asignado_id', 'id')->toArray());
        $cadesjefesL=array_unique($cadesaccionesJefesListos->pluck('departamento_asignado_id', 'id')->toArray());

        $resultado=[
            'cg_seguimiento'=>[
                'analistasF'=>$analistasF,
                'analistasL'=>$analistasL,
                'lideresF'=>$lideresF,
                'lideresL'=>$lideresL,
                'jefesF'=>$jefesF,
                'jefesL'=>$jefesL,
            ],
            'cg_recomendaciones'=>[
                'analistasF'=>$ranalistasF,
                'analistasL'=>$ranalistasL,
                'lideresF'=>$rlideresF,
                'lideresL'=>$rlideresL,
                'jefesF'=>$rjefesF,
                'jefesL'=>$rjefesL,
            ],
            'cg_pras'=>[
                'analistasF'=>$prasanalistasF,
                'analistasL'=>$prasanalistasL,
                'lideresF'=>$praslideresF,
                'lideresL'=>$praslideresL,
                'jefesF'=>$prasjefesF,
                'jefesL'=>$prasjefesL,
            ],
            'ca_seguimiento'=>[
                'analistasF'=>$caanalistasF,
                'analistasL'=>$caanalistasL,
                'lideresF'=>$calideresF,
                'lideresL'=>$calideresL,
                'jefesF'=>$cajefesF,
                'jefesL'=>$cajefesL,
            ],
            'ca_desempeno'=>[
                'analistasF'=>$cadesanalistasF,
                'analistasL'=>$cadesanalistasL,
                'lideresF'=>$cadeslideresF,
                'lideresL'=>$cadeslideresL,
                'jefesF'=>$cadesjefesF,
                'jefesL'=>$cadesjefesL,
            ],
        ];

               
        return view('cedulainicial.index', compact('auditoria', 'request','resultado'));
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
    public function edit(Auditoria $auditoria)
    {        
        $entidadregistrada = EntidadFiscalizableIntra::where('PkCveEntFis',$auditoria->id)->first();
        $entidad = $entidadregistrada->NomEntFis;
        $tipo_entidad = $entidadregistrada->Ambito;

        $totalrecomendaciones= (empty($auditoria->totalrecomendacion)?0:$auditoria->totalrecomendacion->count());
        $totalpras = (empty($auditoria->totalpras)?0:$auditoria->totalpras->count());
        $totalsolacl = (empty($auditoria->totalsolacl)?0:$auditoria->totalsolacl->count());
        $totalpliegos = (empty($auditoria->totalpliegos)?0:$auditoria->totalpliegos->count());

       $params = [
            'entidad' => $entidad,
            'tipoentidad' => $tipo_entidad,
            'total_rec' => $totalrecomendaciones,
            'total_pra' => $totalpras,
            'total_sol' => $totalsolacl,
            'total_plie' => $totalpliegos,
        ];

        $datosConstancia = [
            'nombreConstancia' => 'Fiscalizacion/Seguimiento/CedulaInicial',
            'parametros' => $params,
            'where' => base64_encode(Str::random(5).$auditoria->id.Str::random(5)),
        ];

        $params['where'] = $auditoria->id;

        $preconstancia = reporte($auditoria->id, 'Fiscalizacion/Seguimiento/CedulaInicial', $params, 'pdf');
        $archivorutaxml = reporte($auditoria->id, 'Fiscalizacion/Seguimiento/CedulaInicial', $params, 'xml');
        $b64archivoxml = chunk_split(base64_encode(file_get_contents(base_path().'/public/'.$archivorutaxml)));       

        return view('cedulainicial.form', compact('auditoria', 'preconstancia', 'b64archivoxml', 'datosConstancia', 'archivorutaxml'));
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
private function normalizarDatos(Request $request)
    {
        if ($request->estatus == 'Aprobado') {
            $request['motivo_rechazo'] = null;
        }

        return $request;
    }




    public function setQuery(Request $request)
    {
         $query = $this->model;
         
        if(in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())){     
            $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
            $query = $query->where('fase_autorizacion','Autorizado');
        } 

        if(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())){     
            $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
            $query = $query->where('direccion_asignada_id',$unidadAdministrativa);
        }
        
        $query = $query->whereHas('acciones', function($q){
            if(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){     
                $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
                $q = $q->where('departamento_asignado_id',$unidadAdministrativa)->orWhere('departamento_encargado_id',$unidadAdministrativa);
            } 
            if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){    
                $userLider=auth()->user(); 
                $q = $q->where('lider_asignado_id',$userLider->id);
            }
            if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){           
                $userAnalista=auth()->user(); 
                $q = $q->where('analista_asignado_id',$userAnalista->id);
            }            
            if(in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
               in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())||
               in_array("Secretaria Técnica", auth()->user()->getRoleNames()->toArray())){                 
                $q = $q->whereNotNull('analista_asignado_id');
            }
        });        

        
        
                
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
}
