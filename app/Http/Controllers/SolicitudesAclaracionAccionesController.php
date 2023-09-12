<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use Illuminate\Http\Request;
use App\Models\SolicitudesdeAclaracion;

class SolicitudesAclaracionAccionesController extends Controller
{
   
    protected $model;
    
    public function __construct(AuditoriaAccion $model)
    {
        $this->model = $model;
    } 

    public function index(Request $request)
    {
        $auditoria = Auditoria::find(getSession('solicitudesaclaracionauditoria_id'));    
        $acciones =  $this->setQuery($request)->orderBy('id')->paginate(30);       
        
        return view('solicitudesaclaracionacciones.index', compact('request','acciones', 'auditoria'));
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
    public function edit(AuditoriaAccion $accion)
    {
        setSession('solicitudauditoriaaccion_id',$accion->id);

        if (empty($accion->solicitudesdeaclaracion)) {
            // dd('registrar');
            return redirect()->route('solicitudaclaracionatencion.create');
         }else{
            // dd('consultar');
            return redirect()->route('solixcitudaclaracionatencion.index');
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

         $query = $query->where('segauditoria_id',getSession('solicitudesaclaracionauditoria_id'))->where('segtipo_accion_id',1);
        
         if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){           
            $query = $query->where('analista_asignado_id',auth()->user()->id);
        } 

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
