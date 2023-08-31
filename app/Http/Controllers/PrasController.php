<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use Illuminate\Http\Request;

class PrasController extends Controller
{
    
        protected $model;
    
        public function __construct(Auditoria $model)
        {
            $this->model = $model;
        }
    
        public function index(Request $request)
        {
            $auditorias = $this->setQuery($request)->orderBy('id')->paginate(30);
            $acciones = AuditoriaAccion::where('segtipo_accion_id',4)->paginate(30);
                   
            return view('pras.index', compact('auditorias', 'acciones','request'));
        }
    
    /**compo
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   

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
        setSession('prasauditoria_id',$auditoria->id);        
        
        return redirect()->route('prasacciones.index');        
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
         $query = $query->whereHas('comparecencia', function($q){
            $q->whereNotNull('oficio_acta');
        });  
         $query = $query->whereHas('acciones', function($q){
            $q->where('segtipo_accion_id',4);
        });  

         
        if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){           
            $query = $query->where('usuario_creacion_id',auth()->id());
        }

        if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){            
            $query = $query->whereHas('acciones', function($q){
                $q->where('lider_asignado_id',auth()->user()->id);
            });
        }       

        if(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){     
            $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
            $query = $query->whereNotNull('fase_autorizacion')->whereRaw('LOWER(unidad_administrativa_registro) LIKE (?) ',["%{$unidadAdministrativa}%"])->whereNotNull('nivel_autorizacion');
        }

        if(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())||
           in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())||
           in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
           in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())){                 
            $query = $query->whereNotNull('fase_autorizacion');
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
}
