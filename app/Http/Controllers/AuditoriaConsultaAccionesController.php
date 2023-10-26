<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use Illuminate\Http\Request;

class AuditoriaConsultaAccionesController extends Controller
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
    public function index()
    {
        //
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
        $auditoria = Auditoria::find(getSession('acciones_auditoria_id'));

        return view('auditoriaconsultaacciones.index', compact('accion', 'auditoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

    $query = $query->where('segauditoria_id',getSession('acciones_auditoria_id'));

   if ($request->filled('consecutivo')) {
       $query = $query->where('consecutivo',$request->consecutivo);
    }

   if ($request->filled('segtipo_accion_id') && $request->segtipo_accion_id!=0) {
       $query = $query->where('segtipo_accion_id',$request->segtipo_accion_id);
   }

   if ($request->filled('numero')) {
       $numeroAcccion=strtolower($request->numero);
       $query = $query->whereRaw('LOWER(numero) LIKE (?) ',["%{$numeroAcccion}%"]);
   }
   if ($request->filled('monto_aclarar')) {
       $query = $query->where('monto_aclarar',$request->monto_aclarar);;
   }


   return $query;
}
}
