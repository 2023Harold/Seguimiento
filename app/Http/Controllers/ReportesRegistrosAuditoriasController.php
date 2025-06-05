<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\TurnoUI;
use App\Models\TurnoOIC;
use App\Models\TurnoArchivoTransferencia;
use App\Models\AuditoriaAccion;
use App\Models\Radicacion;
use App\Models\Comparecencia;
use App\Models\AcuerdoConclusion;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ReportesRegistrosAuditoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     protected $model;

     public function __construct(Auditoria $model)
     {
         $this->model = $model;
     } 

    public function index(Request $request)
    {	
        //$auditorias = $this->setQuery($request)->orderBy('id')->get();
		$auditorias = Auditoria::select('id')->where('cuenta_publica',getSession('cp'))->count();
		
		$turnosui = TurnoUI::whereHas('auditoria', function (Builder $query) {
											$query->where('cuenta_publica', getSession('cp'));
									})->count();
									
		$turnooic = TurnoOIC::whereHas('auditoria', function (Builder $query) {
											$query->where('cuenta_publica', getSession('cp'));
									})->count();
									
		$turnotat = TurnoArchivoTransferencia::whereHas('auditoria', function (Builder $query) {
											$query->where('cuenta_publica', getSession('cp'));
									})->count();
									
		$radicaciones = Radicacion::whereHas('auditoria', function (Builder $query) {
											$query->where('cuenta_publica', getSession('cp'));
									})->count();
									
		$comparecencias = Comparecencia::whereHas('auditoria', function (Builder $query) {
											$query->where('cuenta_publica', getSession('cp'));
									})->count();
		
		$acuerdoconclusion = AcuerdoConclusion::whereHas('auditoria', function (Builder $query) {
											$query->where('cuenta_publica', getSession('cp'));
									})->count();

		
		//dd($auditorias,$turnosui,$turnooic,$turnotat);
		
		
        return view('reportesregistrosauditorias.index', compact('request','auditorias','turnosui','turnooic','turnotat','radicaciones','comparecencias','acuerdoconclusion')); 
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
		 $query = $query->where('cuenta_publica',getSession('cp'));


        /*if(in_array("Analista", auth()->user()->getRoleNames()->toArray())){
            $query = $query->where('usuario_creacion_id',auth()->id());
        }

        if(in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())){
            $userLider=auth()->user();
            $query = $query->whereRaw('LOWER(lider_proyecto_id) LIKE (?) ',["%{$userLider->id}%"])->whereNotNull('fase_autorizacion');
        }

        if(in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray())){
            $unidadAdministrativa=auth()->user()->unidad_administrativa_id;
            $query = $query->whereNotNull('fase_autorizacion')->whereRaw('LOWER(unidad_administrativa_registro) LIKE (?) ',["%{$unidadAdministrativa}%"])->whereNotNull('nivel_autorizacion');
        }

        if(in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray())||
           in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray())||
           in_array("Administrador del Sistema", auth()->user()->getRoleNames()->toArray())||
           in_array("Auditor Superior", auth()->user()->getRoleNames()->toArray())){
            $unidadAdministrativa=rtrim(auth()->user()->unidad_administrativa_id, 0);
            $query = $query->whereNotNull('fase_autorizacion')->whereRaw('LOWER(unidad_administrativa_registro) LIKE (?) ',["%{$unidadAdministrativa}%"]);
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
        }*/

        return $query;
    }
}
