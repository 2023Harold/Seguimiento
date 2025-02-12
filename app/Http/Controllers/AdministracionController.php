<?php

namespace App\Http\Controllers;

use App\Models\CuentaPublica;
use Illuminate\Http\Request;

class AdministracionController extends Controller
{

    protected $model;
    
    public function __construct(CuentaPublica $model)
    {
        $this->model = $model;
    }
    // protected $model;

    // public function __construct(CuentaPublica $model)
    // {
    //     $this->model = $model;
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        return view('administracion.index', compact('request'));
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

         if(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){
            //$query = $query->where('departamento_encargado_id',auth()->user()->unidad_administrativa_id);
            $query = $query->where(function ($queryJDE) {
                $queryJDE->where('departamento_encargado_id', auth()->user()->unidad_administrativa_id)
                ->orWhere(function ($queryJDA) {
                    $queryJDA->whereHas('acciones', function($q){
                            $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
                            $q = $q->whereNotNull('fase_autorizacion')->whereRaw('LOWER(departamento_asignado_id) LIKE (?) ',["%{$unidadAdministrativa}%"])->whereNotNull('nivel_autorizacion');

                    });
                });

            });

         }

         $query = $query->whereHas('acciones', function($q){
            if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){
                $q = $q->where('analista_asignado_id',auth()->user()->id);
            }
            if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){
                $userLider=auth()->user();
                $q = $q->whereRaw('LOWER(lider_asignado_id) LIKE (?) ',["%{$userLider->id}%"])->whereNotNull('fase_autorizacion');
            }
        });

        if(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())||
            in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())||
            in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
            in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())){
            $query = $query->whereNotNull('fase_autorizacion')->where('fase_autorizacion','Autorizado');
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
