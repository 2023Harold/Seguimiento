<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Recomendaciones;
use Illuminate\Http\Request;

class RecomendacionesAccionesController extends Controller
{
    protected $model;
    
    public function __construct(AuditoriaAccion $model)
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
        $acciones =  $this->setQuery($request)->orderBy('id')->paginate(30);       
        
        return view('recomendacionesacciones.index', compact('request','acciones', 'auditoria'));
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
    public function show(AuditoriaAccion $accion)
    {
        $acciones =  $this->setQuery($request)->orderBy('numero')->get()->pluck('id')->toArray();
		$nuevaaccion=AuditoriaAccion::find($idactual);
		
		return redirect()->route('edit',$nuevaaccion);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AuditoriaAccion $accion)
    {
        setSession('recomendacionesauditoriaaccion_id',$accion->id);
        $recomendacion=$accion->recomendaciones;
        if (empty($accion->recomendaciones)) {
			if(auth()->user()->siglas_rol=='ANA'){            
            $auditoria = Auditoria::find(getSession('auditoria_id'));            
            $request=new Request();
            $request->merge([
                'auditoria_id' => $auditoria->id,
                'usuario_creacion_id' => auth()->id(),
                'accion_id'=>getSession('recomendacionesauditoriaaccion_id'),
                'consecutivo' => 1,
                'departamento_responsable_id'=> auth()->user()->unidad_administrativa_id,
                'nombre_responsable'=>$auditoria->comparecencia->nombre_representante,
                'cargo_responsable'=>$auditoria->comparecencia->cargo_representante1,
            ]);
            $recomendacion=Recomendaciones::create($request->all());
				setSession('recomendacioncalificacion_id',$recomendacion->id);
			}else{
				setSession('recomendacioncalificacion_id',null);
			}            
          }else{
			  setSession('recomendacioncalificacion_id',$recomendacion->id);
		  }           

         return redirect()->route('recomendacionesatencion.index');
         
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

         $query = $query->where('segauditoria_id',getSession('auditoria_id'))->whereNull('eliminado')->where('segtipo_accion_id',2);

         if(getSession('cp')==2023){
            $query = $query->where('fase_revision','Autorizado');
         }
        
         if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){           
            $query = $query->where('analista_asignado_id',auth()->user()->id);
         } 
         if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){           
            $query = $query->where('lider_asignado_id',auth()->user()->id);
         } 
         if(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){           
            //$query = $query->where('departamento_asignado_id',auth()->user()->unidad_administrativa_id);
            if(getSession('cp')==2023){
                $query = $query->whereHas('auditoria', function($q){
                    $q->where('departamento_encargado_id',auth()->user()->cp_ua2023);
                });
             }else{
                $query = $query->whereHas('auditoria', function($q){
                    $q->where('departamento_encargado_id',auth()->user()->cp_ua2022);
                 });
             }
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
