<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\CatalogoTipoAccion;
use App\Models\CatalogoTipologia;
use Illuminate\Http\Request;

class TipologiaAccionController extends Controller
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
        $auditoria = Auditoria::find(getSession('auditoria_acion_tipologia'));
        $acciones =  $this->setQuery($request)->orderBy('consecutivo')->paginate(30);
        $tiposaccion= CatalogoTipoAccion::all()->pluck('descripcion', 'id')->prepend('Todas', 0);        
        $monto_aclarar=$this->setQuery($request)->orderBy('monto_aclarar');
        $movimiento=null;

        return view('tipologiaaccion.index', compact('acciones', 'request', 'auditoria','tiposaccion','monto_aclarar','movimiento'));
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
    public function edit(Auditoria $auditoria )
    {
        $tipologias = CatalogoTipologia::where('tipo_auditoria_id',$accion->acto_fiscalizacion_id)->pluck('tipologia', 'id')->prepend('Seleccionar una opción', '');
      
        return view('tipologiaaccion.form', compact('accion', 'request','tipologias','auditoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AuditoriaAccion $accion)
    {
         
         $accion->update($request->all());

         setMessage('La tipología se ha agregado correctamente.');
 
         return view ('layouts.close');
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

         $query = $query->where('segauditoria_id',getSession('auditoria_acion_tipologia'))->whereNull('eliminado');

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
