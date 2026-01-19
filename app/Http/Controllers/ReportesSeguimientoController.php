<?php

namespace App\Http\Controllers;

use App\Exports\ReporteSeguimiento;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportesSeguimientoController extends Controller
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
        $auditoriasper = $this->setQuery($request); 
		$collection = Collection::make($auditoriasper);	
		
		$perPage = 20;
		$currentPage = request()->get('page', 1); // Get current page from request
		$offset = ($currentPage * $perPage) - $perPage;
		
		$auditorias = new LengthAwarePaginator(
        $collection->slice($offset, $perPage)->values(), // Slice for current page
        $collection->count(), // Total count
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()] // For generating links
    );

		
		//$auditorias = new Paginator($auditorias,30);

		//dd($page1);








		
        return view('reportesseg.index', compact('request','auditorias'));
        
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
        $auditorias = $this->model->orderBy('id')->paginate(30);

        return view('reportesseg.show', [
            'auditorias' => $auditorias
        ]);
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
        //$query = $this->model;
		//$query = $query->where('cuenta_publica',getSession('cp'));
		
		
		$auditorias = DB::select("
		with pop as(
    select segauditorias.id,NVL(count(*), 0) as pliegos_promovidos,NVL(sum(segauditoria_acciones.monto_aclarar), 0) as importe_promovido from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
        inner join segpliegos_observacion on segauditoria_acciones.id = segpliegos_observacion.accion_id
	where segauditorias.cuenta_publica = ".getSession('cp')."
        GROUP BY segauditorias.id
        Order by segauditorias.id
),pos as(
        select segauditorias.id,NVL(count(*), 0) as pliegos_solventado,NVL(sum(segpliegos_observacion.monto_solventado),0) as importe_solventado from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
        inner join segpliegos_observacion on segauditoria_acciones.id = segpliegos_observacion.accion_id
        where segauditorias.cuenta_publica = ".getSession('cp')." AND segpliegos_observacion.calificacion_sugerida = 'Solventado'
        GROUP BY segauditorias.id
        Order by segauditorias.id
),pons as(
        select segauditorias.id,NVL(count(*), 0) as pliegos_no_solventado,(NVL(sum(segauditoria_acciones.monto_aclarar), 0)  - NVL(sum(segpliegos_observacion.monto_solventado), 0)) as importe_no_solventado from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
        inner join segpliegos_observacion on segauditoria_acciones.id = segpliegos_observacion.accion_id
        where segauditorias.cuenta_publica = ".getSession('cp')." AND segpliegos_observacion.calificacion_sugerida in ('Solventado Parcialmente','No Solventado')
        GROUP BY segauditorias.id
        Order by segauditorias.id
),rpa as (
        select 
        segauditorias.id,
        MAX(segauditoria_acciones.fecha_termino_recomendacion) as fecha_termino_recomendacion,
        MAX(TO_NUMBER(REGEXP_REPLACE(segauditoria_acciones.plazo_recomendacion, '[^0-9]', ''))) as plazo_convenido
        from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
        inner join segrecomendaciones on segauditoria_acciones.id = segrecomendaciones.accion_id
        where segauditorias.cuenta_publica = ".getSession('cp')."
        GROUP BY segauditorias.id
        Order by segauditorias.id
),rp as (
        select 
        segauditorias.id,
        count(*) as recomendaciones_promovidas
        from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
        inner join segrecomendaciones on segauditoria_acciones.id = segrecomendaciones.accion_id
        where segauditorias.cuenta_publica = ".getSession('cp')."
        GROUP BY segauditorias.id
        Order by segauditorias.id
),
ra as (
        select 
        segauditorias.id,
        count(*) as recomendaciones_atendidas
        from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
        inner join segrecomendaciones on segauditoria_acciones.id = segrecomendaciones.accion_id
        where segauditorias.cuenta_publica = ".getSession('cp')." and segrecomendaciones.calificacion_sugerida='Atendida'
        GROUP BY segauditorias.id
        Order by segauditorias.id
),rna as (
        select 
        segauditorias.id,
        count(*) as recomendaciones_no_atendidas
        from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
        inner join segrecomendaciones on segauditoria_acciones.id = segrecomendaciones.accion_id
        where segauditorias.cuenta_publica = ".getSession('cp')." and segrecomendaciones.calificacion_sugerida in ('No Atendida','Parcialmente Atendida')
        GROUP BY segauditorias.id
        Order by segauditorias.id
),solacl as (
        select 
        segauditorias.id,
        count(*) as sa
        from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
        inner join segsolicitudes_aclaracion on segauditoria_acciones.id = segsolicitudes_aclaracion.accion_id
        where segauditorias.cuenta_publica = ".getSession('cp')."
        GROUP BY segauditorias.id
        Order by segauditorias.id
),pras as (
        select 
        segauditorias.id,
        count(*) as pras
        from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
        inner join segpras on segauditoria_acciones.id = segpras.accion_id
        where segauditorias.cuenta_publica = ".getSession('cp')."
        GROUP BY segauditorias.id
        Order by segauditorias.id
),pliobs as (
        select 
        segauditorias.id,
        count(*) as po
        from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
        inner join segpliegos_observacion on segauditoria_acciones.id = segpliegos_observacion.accion_id
        where segauditorias.cuenta_publica = ".getSession('cp')."
        GROUP BY segauditorias.id
        Order by segauditorias.id
),recom as (
        select 
        segauditorias.id,
        count(*) as r
        from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
        inner join segrecomendaciones on segauditoria_acciones.id = segrecomendaciones.accion_id
        where segauditorias.cuenta_publica = ".getSession('cp')."
        GROUP BY segauditorias.id
        Order by segauditorias.id
),
accionesprom as (
        select 
        segauditorias.id,
        count(*) as ap_num,
        NVL(sum(segauditoria_acciones.monto_aclarar),0) as importeaccionesprom        
        from 
        segauditorias
        inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id and segauditoria_acciones.segtipo_accion_id = 3      
        where segauditorias.cuenta_publica = ".getSession('cp')." 
        GROUP BY segauditorias.id
        Order by segauditorias.id
),apspo as(        
            select 
            segauditorias.id,
            count(*) as aps_no,
            NVL(sum(segpliegos_observacion.monto_solventado),0) as importepliobssol        
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segpliegos_observacion on segauditoria_acciones.id = segpliegos_observacion.accion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and segpliegos_observacion.calificacion_sugerida='Solventado'
            GROUP BY segauditorias.id
            Order by segauditorias.id        
), apssa as (        
            select 
            segauditorias.id,
            count(*) as apssolacl,
            NVL(sum(segsolicitudes_aclaracion.monto_solventado),0) as importesolaclsol       
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segsolicitudes_aclaracion on segauditoria_acciones.id = segsolicitudes_aclaracion.accion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and segsolicitudes_aclaracion.calificacion_sugerida='Solventada'
            GROUP BY segauditorias.id
            Order by segauditorias.id        
), apsrec as (          
            select 
            segauditorias.id,
            count(*) as apsrec,
            0 as importesolrec       
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segrecomendaciones on segauditoria_acciones.id = segrecomendaciones.accion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and segrecomendaciones.calificacion_sugerida='Atendida'
            GROUP BY segauditorias.id
            Order by segauditorias.id              
),apspons as(        
            select 
            segauditorias.id,
            count(*) as apns_numobs,
            NVL(sum(NVL(segauditoria_acciones.monto_aclarar,0))-sum(NVL(segpliegos_observacion.monto_solventado,0)),0) as importepliobsnosolv        
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segpliegos_observacion on segauditoria_acciones.id = segpliegos_observacion.accion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and segpliegos_observacion.calificacion_sugerida != 'Solventado'
            GROUP BY segauditorias.id
            Order by segauditorias.id        
), apssans as (        
            select 
            segauditorias.id,
            count(*) as apnssolacl,
            NVL(sum(NVL(segauditoria_acciones.monto_aclarar,0))-sum(NVL(segsolicitudes_aclaracion.monto_solventado,0)),0) as importesolaclnosol       
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segsolicitudes_aclaracion on segauditoria_acciones.id = segsolicitudes_aclaracion.accion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and segsolicitudes_aclaracion.calificacion_sugerida != 'Solventada'
            GROUP BY segauditorias.id
            Order by segauditorias.id        
), apsrecns as (          
            select 
            segauditorias.id,
            count(*) as apnsrecns,
            0 as importesolrecnonosol       
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segrecomendaciones on segauditoria_acciones.id = segrecomendaciones.accion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and segrecomendaciones.calificacion_sugerida!='Atendida'
            GROUP BY segauditorias.id
            Order by segauditorias.id              
), facnisea as (
            select 
            segauditorias.id,
            seginforme_primeraetapa.fecha_notificacion as fecha_notisea,
			seginforme_primeraetapa.numero_informe as numero_notisea,
            seginforme_primeraetapa.fase_autorizacion as faseautipepli
            from 
            segauditorias
            inner join seginforme_primeraetapa on segauditorias.id = seginforme_primeraetapa.auditoria_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and seginforme_primeraetapa.tipo='pliegos'
            GROUP BY segauditorias.id,seginforme_primeraetapa.fecha_notificacion,seginforme_primeraetapa.numero_informe,seginforme_primeraetapa.fase_autorizacion
            Order by segauditorias.id 
),facnispa as (
            select 
            segauditorias.id,
            seginforme_primeraetapa.fecha_notificacion as fecha_notispa,
			seginforme_primeraetapa.numero_informe as numero_notispa,
            seginforme_primeraetapa.fase_autorizacion as faseautiperec
            from 
            segauditorias
            inner join seginforme_primeraetapa on segauditorias.id = seginforme_primeraetapa.auditoria_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and seginforme_primeraetapa.tipo='recomendaciones'
            GROUP BY segauditorias.id,seginforme_primeraetapa.fecha_notificacion,seginforme_primeraetapa.numero_informe,seginforme_primeraetapa.fase_autorizacion
            Order by segauditorias.id 
),faccea as (
            select 
            segauditorias.id,
            segacuerdo_conclusion.fecha_acuerdo_conclusion as fecha_acea,
			segacuerdo_conclusion.numero_oficio as numero_oficio_acea
            from 
            segauditorias
            inner join segacuerdo_conclusion on segauditorias.id = segacuerdo_conclusion.auditoria_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and segacuerdo_conclusion.tipo='pliegos'
            GROUP BY segauditorias.id,segacuerdo_conclusion.fecha_acuerdo_conclusion,segacuerdo_conclusion.numero_oficio
            Order by segauditorias.id 
),faccpa as (
            select 
            segauditorias.id,
            segacuerdo_conclusion.fecha_acuerdo_conclusion as fecha_acpa,
			segacuerdo_conclusion.numero_oficio as numero_oficio_acpa
            from 
            segauditorias
            inner join segacuerdo_conclusion on segauditorias.id = segacuerdo_conclusion.auditoria_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and segacuerdo_conclusion.tipo='recomendaciones'
            GROUP BY segauditorias.id,segacuerdo_conclusion.fecha_acuerdo_conclusion,segacuerdo_conclusion.numero_oficio
            Order by segauditorias.id 
),asiganalista as (
            select 
            segauditorias.id,
            segusers.name as nombre_analista
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segusers on segusers.id = segauditoria_acciones.analista_asignado_id and segusers.siglas_rol='ANA'
            where segauditorias.cuenta_publica = ".getSession('cp')." 
            GROUP BY segauditorias.id,segusers.name 
            Order by segauditorias.id 
),asiglider as (
            select 
            segauditorias.id,
            segusers.name as nombre_lider
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segusers on segusers.id = segauditoria_acciones.lider_asignado_id and segusers.siglas_rol='LP' and segusers.estatus='Activo'
            where segauditorias.cuenta_publica = ".getSession('cp')." 
            GROUP BY segauditorias.id,segusers.name 
            Order by segauditorias.id 
),asigdep as (
            select 
            segauditorias.id,
            segusers.name as nombre_jefe
            from 
            segauditorias
            inner join segusers on segusers.cp_ua".getSession('cp')." = segauditorias.departamento_encargado_id and segusers.siglas_rol='JD' and segusers.estatus='Activo'
            where segauditorias.cuenta_publica = ".getSession('cp')." 
            GROUP BY segauditorias.id,segusers.name 
            Order by segauditorias.id 
),asigdir as (
            select 
            segauditorias.id,
            segusers.name as nombre_director
            from 
            segauditorias
            inner join segusers on segusers.cp_ua".getSession('cp')." = segauditorias.direccion_asignada_id and segusers.siglas_rol='DS' and segusers.estatus='Activo'
            where segauditorias.cuenta_publica = ".getSession('cp')." 
            GROUP BY segauditorias.id,segusers.name 
            Order by segauditorias.id 
), contestacionespliegos as (
            select 
            segauditorias.id,
            count(*) as totcontestacionespo
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segpliegos_observacion on segauditoria_acciones.id = segpliegos_observacion.accion_id
            inner join segpliegos_observacion_contestacion on segpliegos_observacion.id = segpliegos_observacion_contestacion.pliegosobservacion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." 
            GROUP BY segauditorias.id
            Order by segauditorias.id 
),contestacionesolacl as (
            select 
            segauditorias.id,
            count(*) as totcontestacionessa
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segsolicitudes_aclaracion on segauditoria_acciones.id = segsolicitudes_aclaracion.accion_id
            inner join segsolicitudes_acl_contestaciones on segsolicitudes_aclaracion.id = segsolicitudes_acl_contestaciones.solicitudaclaracion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." 
            GROUP BY segauditorias.id
            Order by segauditorias.id 
),contestacionesreco as (
            select 
            segauditorias.id,
            count(*) as totcontestacionesreco
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segrecomendaciones on segauditoria_acciones.id = segrecomendaciones.accion_id
            inner join segrecomendaciones_contestaciones on segrecomendaciones.id = segrecomendaciones_contestaciones.recomendacion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." 
            GROUP BY segauditorias.id
            Order by segauditorias.id 
),contestacionespliegosatend as (
            select 
            segauditorias.id,
            count(*) as totcontestacionespoat
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segpliegos_observacion on segauditoria_acciones.id = segpliegos_observacion.accion_id
            inner join segpliegos_observacion_contestacion on segpliegos_observacion.id = segpliegos_observacion_contestacion.pliegosobservacion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and segpliegos_observacion.fase_autorizacion = 'Autorizado'
            GROUP BY segauditorias.id
            Order by segauditorias.id 
),contestacionesolaclatend as (
            select 
            segauditorias.id,
            count(*) as totcontestacionessaat
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segsolicitudes_aclaracion on segauditoria_acciones.id = segsolicitudes_aclaracion.accion_id
            inner join segsolicitudes_acl_contestaciones on segsolicitudes_aclaracion.id = segsolicitudes_acl_contestaciones.solicitudaclaracion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and segsolicitudes_aclaracion.fase_autorizacion = 'Autorizado'
            GROUP BY segauditorias.id
            Order by segauditorias.id 
),contestacionesrecoatend as (
            select 
            segauditorias.id,
            count(*) as totcontestacionesrecoat
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segrecomendaciones on segauditoria_acciones.id = segrecomendaciones.accion_id
            inner join segrecomendaciones_contestaciones on segrecomendaciones.id = segrecomendaciones_contestaciones.recomendacion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." and segrecomendaciones.fase_autorizacion = 'Autorizado'
            GROUP BY segauditorias.id
            Order by segauditorias.id 
),contestacionespliegosfecha as (
            select 
            segauditorias.id,
            max(segpliegos_observacion_contestacion.fecha_recepcion_oficialia) as fechaultconpo
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segpliegos_observacion on segauditoria_acciones.id = segpliegos_observacion.accion_id
            inner join segpliegos_observacion_contestacion on segpliegos_observacion.id = segpliegos_observacion_contestacion.pliegosobservacion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." 
            GROUP BY segauditorias.id
            Order by segauditorias.id 
),contestacionesolaclfecha as (
            select 
            segauditorias.id,
            max(segsolicitudes_acl_contestaciones.fecha_recepcion_oficialia) as fechaultconsa            
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segsolicitudes_aclaracion on segauditoria_acciones.id = segsolicitudes_aclaracion.accion_id
            inner join segsolicitudes_acl_contestaciones on segsolicitudes_aclaracion.id = segsolicitudes_acl_contestaciones.solicitudaclaracion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." 
            GROUP BY segauditorias.id
            Order by segauditorias.id 
),contestacionesrecofecha as (
            select 
            segauditorias.id,
            max(segrecomendaciones_contestaciones.fecha_recepcion_oficialia) as fechaultconreco  
            from 
            segauditorias
            inner join segauditoria_acciones on segauditorias.id = segauditoria_acciones.segauditoria_id
            inner join segrecomendaciones on segauditoria_acciones.id = segrecomendaciones.accion_id
            inner join segrecomendaciones_contestaciones on segrecomendaciones.id = segrecomendaciones_contestaciones.recomendacion_id            
            where segauditorias.cuenta_publica = ".getSession('cp')." 
            GROUP BY segauditorias.id
            Order by segauditorias.id 
)
select  
segauditorias.tipo_entidad,
(
CASE 
 WHEN segauditorias.siglas_entidad IS NOT NULL THEN
    segauditorias.siglas_entidad
 ELSE
 ' '
 END
)as entidad_sigla,
seglistadoentidades.tipo_paa,
seglistadoentidades.entidades,
seglistadoentidades.aud_paa_desc,
segauditorias.acto_fiscalizacion,
segauditorias.periodo_revision,
seglistadoentidades.no_auditoria,
segauditorias.numero_auditoria,
segradicacion.fecha_expediente_turnado,
segradicacion.calculo_fecha,
segradicacion.numero_expediente,
segradicacion.fecha_notificacion,
segcomparecencia.fecha_comparecencia,
segradicacion.fecha_oficio_acuerdo,
segradicacion.oficio_acuerdo,
segcomparecencia.fecha_inicio_aclaracion,
segcomparecencia.fecha_termino_aclaracion,
segcomparecencia.fecha_termino_aclaracion + 120 + (
        CASE
            WHEN TO_CHAR(segcomparecencia.fecha_termino_aclaracion + 120, 'D') IN ('1', '7') THEN 2
            WHEN TO_CHAR(segcomparecencia.fecha_termino_aclaracion + 120, 'D') = '2' THEN 1
            ELSE 0
        END
    ) AS fecha_termino_mas120,
NVL(pop.pliegos_promovidos, 0) as pliegos_promovidos,
NVL(pop.importe_promovido, 0) as importe_promovido,
NVL(pos.pliegos_solventado, 0) as pliegos_solventado,
NVL(pos.importe_solventado, 0) as importe_solventado,
NVL(pons.pliegos_no_solventado, 0) as pliegos_no_solventado,
NVL(pons.importe_no_solventado, 0) as importe_no_solventado,
rpa.plazo_convenido,
rpa.fecha_termino_recomendacion,
NVL(rp.recomendaciones_promovidas, 0) as recomendaciones_promovidas,
NVL(ra.recomendaciones_atendidas, 0) as recomendaciones_atendidas,
NVL(rna.recomendaciones_no_atendidas, 0) as recomendaciones_no_atendidas,
NVL(pliobs.po,0) as po,
NVL(solacl.sa,0) as sa,
NVL(recom.r,0) as r,
NVL(pras.pras,0) as pras,
(NVL(pliobs.po,0)+NVL(solacl.sa,0)+NVL(recom.r,0)+NVL(pras.pras,0))as total,
NVL(accionesprom.ap_num,0) as accionespromnumobs,
NVL(accionesprom.importeaccionesprom,0) as importeaccionespromnumobs,
(NVL(apspo.aps_no,0) + NVL(apssa.apssolacl,0)) as accionespromsolv,
(NVL(apspo.importepliobssol,0) + NVL(apssa.importesolaclsol,0)) as importepromsolv,
(NVL(apspons.apns_numobs,0) + NVL(apssans.apnssolacl,0)) as accionespromnosolv,
(NVL(apspons.importepliobsnosolv,0) + NVL(apssans.importesolaclnosol,0)) as importepromnosolv,
(
        CASE
            WHEN segturno_ui.fase_autorizacion IN ('Autorizado') THEN 'UI'
            ELSE 'US'
        END
) as area_encuentra,
(
        CASE 
            WHEN (NVL(pliobs.po,0) + NVL(solacl.sa,0)) > 0 and segturno_ui.fase_autorizacion = 'Autorizado' THEN
                'TURNADOS INICIO PROCEDIMIENTO (UI)'            
            WHEN (NVL(pliobs.po,0) + NVL(solacl.sa,0)) > 0 and (NVL(apspo.aps_no,0) + NVL(apssa.apssolacl,0) + NVL(apsrec.apsrec,0)) = NVL(accionesprom.ap_num,0) AND facnisea.faseautipepli = 'Autorizado' THEN
                'SOLVENTADO'
            WHEN (NVL(pliobs.po,0) + NVL(solacl.sa,0)) > 0 and facnisea.faseautipepli is not null  THEN 
                'INFORME DE SEGUIMIENTO'
            WHEN (NVL(pliobs.po,0) + NVL(solacl.sa,0)) > 0 and SYSDATE  > segcomparecencia.fecha_termino_aclaracion THEN
                'ANALISIS'
            WHEN (NVL(pliobs.po,0) + NVL(solacl.sa,0)) > 0 and SYSDATE BETWEEN  segcomparecencia.fecha_inicio_aclaracion AND segcomparecencia.fecha_termino_aclaracion THEN
                'ETAPA DE ACLARACIÓN'               
            ELSE
            ''
        END
) as  ppseaea,
(
        CASE 
            WHEN  NVL(recom.r,0) > 0 and segturno_oic.fase_autorizacion = 'Autorizado' THEN
                'TURNADA AL OIC'            
            WHEN NVL(recom.r,0) > 0 and NVL(apsrec.apsrec,0) = NVL(rp.recomendaciones_promovidas, 0) AND facnispa.faseautiperec = 'Autorizado' THEN
                'ATENDIDA'
            WHEN  NVL(recom.r,0) > 0 and facnispa.faseautiperec is not null  THEN 
                'INFORME DE SEGUIMIENTO'
            WHEN NVL(recom.r,0) > 0 and SYSDATE  > segcomparecencia.fecha_termino_proceso THEN
                'ANALISIS'
            WHEN NVL(recom.r,0) > 0 and SYSDATE BETWEEN  segcomparecencia.fecha_inicio_proceso AND segcomparecencia.fecha_termino_proceso THEN
                'ETAPA DE ACLARACIÓN'               
            ELSE
            ''
        END
) as  ppseaar,
faccea.numero_oficio_acea,
faccea.fecha_acea,
faccpa.numero_oficio_acpa,
faccpa.fecha_acpa,
facnisea.numero_notisea,
facnisea.fecha_notisea,
facnispa.numero_notispa,
facnispa.fecha_notispa,
segturno_ui.numero_turno_ui,
segturno_ui.fecha_notificacion_ui as fecha_envioui,
segturno_oic.numero_turno_oic,
segturno_oic.fecha_notificacion as fecha_enviooic,
asiganalista.nombre_analista,
asiglider.nombre_lider,
asigdep.nombre_jefe,
asigdir.nombre_director,
(NVL(contestacionespliegos.totcontestacionespo,0)+NVL(contestacionesolacl.totcontestacionessa,0)+NVL(contestacionesreco.totcontestacionesreco,0)) as contesrecibidas,
(NVL(contestacionespliegosatend.totcontestacionespoat,0)+NVL(contestacionesolaclatend.totcontestacionessaat,0)+NVL(contestacionesrecoatend.totcontestacionesrecoat,0)) as contestacionesatendidas,
((NVL(contestacionespliegos.totcontestacionespo,0)+NVL(contestacionesolacl.totcontestacionessa,0)+NVL(contestacionesreco.totcontestacionesreco,0))-(NVL(contestacionespliegosatend.totcontestacionespoat,0)+NVL(contestacionesolaclatend.totcontestacionessaat,0)+NVL(contestacionesrecoatend.totcontestacionesrecoat,0))) as contestacionespendientes, 
GREATEST( NVL(contestacionespliegosfecha.fechaultconpo,TO_DATE('01/01/1000','MM/DD/YYYY')),NVL(contestacionesolaclfecha.fechaultconsa, TO_DATE('01/01/1000','MM/DD/YYYY')),NVL(contestacionesrecofecha.fechaultconreco, TO_DATE('01/01/1000','MM/DD/YYYY'))) as fechaultcont,
TO_CHAR(segradicacion.num_memo_recepcion_expediente) as num_recepcion_expediente,
segradicacion.numero_acuerdo,
segradicacion.fecha_oficio_informe
from seglistadoentidades
inner join segauditorias on segauditorias.numero_auditoria = seglistadoentidades.no_auditoria
inner join segradicacion on segradicacion.auditoria_id = segauditorias.id
inner join segcomparecencia on segcomparecencia.auditoria_id = segauditorias.id
left join pop on pop.id = segauditorias.id
left join pos on pos.id = segauditorias.id
left join pons on pons.id = segauditorias.id
left join rpa on rpa.id = segauditorias.id
left join rp on rp.id = segauditorias.id
left join ra on ra.id = segauditorias.id
left join rna on rna.id = segauditorias.id
left join solacl on solacl.id = segauditorias.id
left join pras on pras.id = segauditorias.id
left join pliobs on pliobs.id = segauditorias.id
left join recom on recom.id = segauditorias.id
left join accionesprom on accionesprom.id = segauditorias.id
left join apspo on apspo.id = segauditorias.id
left join apssa on apssa.id = segauditorias.id
left join apsrec on apsrec.id = segauditorias.id
left join apspons on apspons.id = segauditorias.id
left join apssans on apssans.id = segauditorias.id
left join apsrecns on apsrecns.id = segauditorias.id
left join segturno_ui on segturno_ui.auditoria_id = segauditorias.id
left join segturno_oic on segturno_oic.auditoria_id = segauditorias.id
left join faccea on faccea.id = segauditorias.id
left join faccpa on faccpa.id = segauditorias.id
left join facnisea on facnisea.id = segauditorias.id
left join facnispa on facnispa.id = segauditorias.id
inner join asiganalista on asiganalista.id = segauditorias.id
left join asiglider on asiglider.id = segauditorias.id
left join asigdep on asigdep.id = segauditorias.id
left join asigdir on asigdir.id = segauditorias.id
left join contestacionespliegos on contestacionespliegos.id = segauditorias.id
left join contestacionesolacl on contestacionesolacl.id = segauditorias.id
left join contestacionesreco on contestacionesreco.id = segauditorias.id
left join contestacionespliegosatend on contestacionespliegosatend.id = segauditorias.id
left join contestacionesolaclatend on contestacionesolaclatend.id = segauditorias.id
left join contestacionesrecoatend on contestacionesrecoatend.id = segauditorias.id
left join contestacionespliegosfecha on contestacionespliegosfecha.id = segauditorias.id
left join contestacionesolaclfecha on contestacionesolaclfecha.id = segauditorias.id
left join contestacionesrecofecha on contestacionesrecofecha.id = segauditorias.id
left join seginforme_primeraetapa on seginforme_primeraetapa.auditoria_id = segauditorias.id
where seglistadoentidades.cuenta_publica = ".getSession('cp')."
".(!empty($request->numero_auditoria)?"and seglistadoentidades.no_auditoria like '%".$request->numero_auditoria."%'":"").
"".(!empty($request->entidad_fiscalizable)?"and segauditorias.entidad_fiscalizable like '%".strtoupper($request->entidad_fiscalizable)."%'":"").
"group by 
    segauditorias.tipo_entidad,
    segauditorias.siglas_entidad,
    segauditorias.entidad_fiscalizable_id,
    seglistadoentidades.tipo_paa,      
    seglistadoentidades.entidades,
	seglistadoentidades.aud_paa_desc,
    segauditorias.acto_fiscalizacion,
    segauditorias.periodo_revision,
    seglistadoentidades.no_auditoria, 
    segauditorias.numero_auditoria,
    segradicacion.fecha_expediente_turnado,
    segradicacion.numero_expediente,
    segradicacion.fecha_notificacion,
	segradicacion.calculo_fecha,
    segcomparecencia.fecha_comparecencia,
    segradicacion.fecha_oficio_acuerdo,
    segradicacion.oficio_acuerdo,
    segcomparecencia.fecha_inicio_aclaracion,
    segcomparecencia.fecha_termino_aclaracion,
    pop.pliegos_promovidos,
    pop.importe_promovido,
    pos.pliegos_solventado,
    pos.importe_solventado,
    pons.pliegos_no_solventado,
    pons.importe_no_solventado,
    rpa.plazo_convenido,
    rpa.fecha_termino_recomendacion,
    rp.recomendaciones_promovidas,
    ra.recomendaciones_atendidas,
    rna.recomendaciones_no_atendidas,
    pliobs.po,
    solacl.sa,
    recom.r,
    pras.pras,
    apspo.aps_no,
    apssa.apssolacl,
    apsrec.apsrec,
    accionesprom.ap_num,
    accionesprom.importeaccionesprom,
    apspo.importepliobssol,
    apssa.importesolaclsol,
    apsrec.importesolrec,
    apspons.apns_numobs,
    apssans.apnssolacl,
    apsrecns.apnsrecns,
    apspons.importepliobsnosolv,
    apssans.importesolaclnosol,
    apsrecns.importesolrecnonosol,
    segturno_ui.fase_autorizacion,
    segcomparecencia.fecha_inicio_proceso, 
    segcomparecencia.fecha_termino_proceso,
    segturno_oic.fase_autorizacion,
    faccea.numero_oficio_acea,
	faccea.fecha_acea,
	faccpa.numero_oficio_acpa,
	faccpa.fecha_acpa,
    facnisea.numero_notisea,
	facnisea.fecha_notisea,
    facnisea.faseautipepli,
	facnispa.numero_notispa,
	facnispa.fecha_notispa,
    facnispa.faseautiperec,
	segturno_ui.numero_turno_ui,
    segturno_ui.fecha_notificacion_ui,
    segturno_oic.numero_turno_oic, 
	segturno_oic.fecha_notificacion, 
    asiganalista.nombre_analista,
    asiglider.nombre_lider,
    asigdep.nombre_jefe,
    asigdir.nombre_director,
    contestacionespliegos.totcontestacionespo,
    contestacionesolacl.totcontestacionessa,
    contestacionesreco.totcontestacionesreco,
    contestacionespliegosatend.totcontestacionespoat,
    contestacionesolaclatend.totcontestacionessaat,
    contestacionesrecoatend.totcontestacionesrecoat,
    contestacionespliegosfecha.fechaultconpo,
    contestacionesolaclfecha.fechaultconsa,
    contestacionesrecofecha.fechaultconreco,
    seglistadoentidades.orden,
	TO_CHAR(segradicacion.num_memo_recepcion_expediente),
	segradicacion.numero_acuerdo,
	segradicacion.fecha_oficio_informe
	order by seglistadoentidades.orden		
	");
	
	
   

        return $auditorias;
    }

    public function export($aud=null,$ent=null) 	
    {
		
        return Excel::download(new ReporteSeguimiento($aud,$ent), 'reportecp'.getSession('cp').'.xlsx');
    }

}
