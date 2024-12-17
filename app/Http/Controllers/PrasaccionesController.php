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

        $auditoria = Auditoria::find(getSession('auditoria_id'));
        $acciones =  $this->setQuery($request)->orderBy('id')->paginate(30);


        return view('prasacciones.index', compact('request','acciones', 'auditoria'));
        }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accion=AuditoriaAccion::find(getSession('prasauditoriaaccion_id'));
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
            // dd($accion);
        }else{
            return redirect()->route('prasturno.index');
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

         $query = $query->where('segauditoria_id',getSession('auditoria_id'))->whereNull('eliminado')->where('segtipo_accion_id',4);

         if(getSession('cp')==2023){
            $query = $query->where('fase_revision','Atorizado');
         }

        

         if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){           
            $query = $query->where('lider_asignado_id',auth()->user()->id);
         } 
         if(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){           
            $query = $query->where('departamento_asignado_id',auth()->user()->unidad_administrativa_id);
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
