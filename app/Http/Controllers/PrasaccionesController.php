<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\Segpras;
use Illuminate\Http\Request;

class PrasaccionesController extends Controller
{

    protected $model;
    
        public function __construct(AuditoriaAccion $model)
        {
            $this->model = $model;
        }
    
    
        public function index(Request $request)
        {
        $auditorias = $this->setQuery($request)->orderBy('id')->paginate(30);
        $auditoria = Auditoria::find(getSession('prasauditoria_id'));
        $acciones =  $this->setQuery($request)->orderBy('id')->paginate(30);     
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Todas', 0);        
        $numero_auditoria=$this->setQuery($request)->orderBy('id')->paginate(30);
        $entidad_fiscalizable=$this->setQuery($request);
        $acto_fiscalizacion=$this->setQuery($request);
        $monto_aclarar=$this->setQuery($request);
        
        return view('prasacciones.index', compact('numero_auditoria','request','acciones', 'auditoria','auditorias','monto_aclarar','acto_fiscalizacion' ));
        }
    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accion=AuditoriaAccion::find(getSession('prasaccion_id'));
        $auditoria=$accion->auditoria;
        $pras=new Segpras();

        return view('prasacciones.index',compact('pras','accion','auditoria'));
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
    public function edit(AuditoriaAccion $accion)
    {
     
    setSession('prasauditoriaaccion_id',$accion->id);
    if (empty($accion->pras)) {
        return redirect()->route('prasturno.create');
    } else {
        return redirect()->route('prasturno.edit',$accion->pras);
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
        
    } 
    public function setQuery(Request $request)
    {
         $query = $this->model;

         $query = $query->where('segauditoria_id',getSession('prasauditoria_id'));
                
        if ($request->filled('consecutivo')) {            
            $query = $query->where('consecutivo',$request->consecutivo);
         }

        if ($request->filled('tipo')) {
            $query = $query->where('tipo',$request->tipo);
        }
        if ($request->filled('monto_aclarar')) {
            $query = $query->where('monto_aclarar',$request->monto_aclarar);
        }
        return $query;
    }

  
}
