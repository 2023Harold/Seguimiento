@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('reportesseg.index',$auditorias) }}
@endsection
@section('content')
<style>
tr:hover {background-color: #CAD5E2 !important;}
</style>
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title w-100">
                    <div class="row w-100">
                        <div class="col-md-11">
                            <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                            Reportes
                        </div>
                        <div class="col-md-1">
							@can('reporteseguimiento.exportar')
                            <a href="{!! route('reporteseguimiento.exportar',['aud' => $request->numero_auditoria,'ent' => $request->entidad_fiscalizable]) !!}" class=" btn btn-primary">Excel</a>
							@endcan
                        </div>
                    </div>                
                </h1>                
            </div> 
            <div class="card-body">     
				{!!BootForm::open(['route'=>'reportesseg.index','method'=>'GET']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            {!!BootForm::text('numero_auditoria', "No. auditoría:", old('numero_auditoria', $request->numero_auditoria)) !!}
                        </div>
                        <div class="col-md-2">
                            {!!BootForm::text('entidad_fiscalizable', "Entidad fiscalizable:", old('entidad_fiscalizable', $request->entidad_fiscalizable)) !!}
                        </div>                       
                        <div class="col-md-6 mt-8">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                {!!BootForm::close() !!}			
            <div class="table-responsive" id="tabscroll" >
                <table style="table-layout: fixed; width: 20000px; padding: .0rem .0rem !important;">
                    <thead>
                        <tr>
                            <td rowspan=3 style="width:4px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Tipo de Entidad </td>                          
                            <td rowspan=3 style="width:3px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Núm. Progresivo </td>                    
                            <td rowspan=3 style="width:5px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Núm. Entidad/ Siglas </td>
                            <td rowspan=3 style="width:5px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Tipo de Auditoria PAA </td>
                            <td rowspan=3 style="width:5px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Auditoría PAA </td>
                            <td rowspan=3 style="width:5px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Tipo Auditoría </td> 
                            <td rowspan=3 style="width:10px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Periodo Auditado</td>                           
                            <td rowspan=3 style="width:6px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Núm. de Auditoría </td>
							<td rowspan=2 colspan=2 style="width:30px; color: white; background-color: #A13B71; border:solid;" class="text-center">Memorándum de Recepción del Expediente Técnico de Auditoría </td>
                            <td rowspan=3 style="width:10px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Núm. de expediente <br> (interno US) </td>
                            <td rowspan=3 style="width:10px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Fecha del acuerdo de radicación </td>
                            <td rowspan=3 style="width:10px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Fecha de comparecencia</td>
							@if(getSession('cp')==2022)
                            <td rowspan=2 colspan=2 style="width:30px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Oficio de notificación del Informe de Auditoría </td> 
							@else
							<td rowspan=2 colspan=2 style="width:30px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Oficio de notificación de Acuerdos </td> 
							@endif
                            <td colspan=9 style="width:50px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Etapa de Aclaración </td> 
                            <td colspan=5 style="width:50px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Proceso de Atención</td>
                            <td rowspan=2 colspan=5 style="width:20px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Observaciones y Recomendaciones 1.° Etapa  </td>
                            <td rowspan=2 colspan=2 style="width:10px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Acciones promovidas </td>  
                            <td rowspan=2 colspan=2 style="width:10px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Acciones promovidas solventadas </td>   
                            <td rowspan=2 colspan=2 style="width:10px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Acciones promovidas no solventadas </td>
                            <td rowspan=3 style="width:8px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Área en que sencuentra </td>                             
                            <td rowspan=3 style="width:10px; word-wrap: break-word!important; color: white; background-color: #A13B71; border:solid;" class="text-center" > Parte del Proceso de Seguimiento en que se encuentra la Auditoría >Etapa aclaración< </td>
                            <td rowspan=3 style="width:10px; word-wrap: break-word!important; color: white; background-color: #A13B71; border:solid;" class="text-center"> Parte del Proceso de Seguimiento en que se encuentra la Auditoría >Atención de las Recom.< </td>
							
							<td rowspan=2 colspan=2 style="width:30px; color: white; background-color: #A13B71; border:solid;" class="text-center">Oficio de notificación de la conclusión de la Etapa de Aclaración </td>
							<td rowspan=2 colspan=2 style="width:30px; color: white; background-color: #A13B71; border:solid;" class="text-center">Oficio de notificación de la conclusión al Proceso de Atención de las Recomendaciones</td>
							<td rowspan=2 colspan=2 style="width:30px; color: white; background-color: #A13B71; border:solid;" class="text-center">Oficio de notificación del Informe de Seguimiento de la Etapa de Aclaración</td>
							<td rowspan=2 colspan=2 style="width:30px; color: white; background-color: #A13B71; border:solid;" class="text-center">Oficio de notificación del Informe de Seguimiento al Proceso de Atención de las Recomendaciones</td>
							<td rowspan=2 colspan=2 style="width:30px; color: white; background-color: #A13B71; border:solid;" class="text-center">Oficio de Envío del Pliego de Observaciones a la Unidad Investigadora</td>
							<td rowspan=2 colspan=2 style="width:30px; color: white; background-color: #A13B71; border:solid;" class="text-center">Oficio de Envío de las Recomendaciones al OIC</td>
							
                            
                            <td rowspan=2 colspan=4 style="width:20px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Asignación</td>
                            <td rowspan=2 colspan=4 style="width:20px; color: white; background-color: #A13B71; border:solid;" class="text-center"> Contestaciones</td>                            
                        </tr>  
                        <tr>  
												
                            <td colspan=3 style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Fechas </td>
                            <td colspan=2 style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Pliegos de Observaciones Promovidos </td>
                            <td colspan=2 style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Pliegos de observaciones solventados</td>
                            <td colspan=2 style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Pliegos de Observaciones No Solventados </td>
                            <td colspan=2 style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Plazo </td>    
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Recomendaciones Promovidas </td>    
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Recomendaciones Atendidas </td>    
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Recomendaciones No Atendidas </td>                                                        
                        </tr> 
                        <tr> 
							<td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Número </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Fecha (sello de acuse)</td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Número</td>
							<td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Fecha (sello de acuse)</td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Inicio</td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Vencimiento</td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> 120 días de la etapa de Seguimiento</td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Núm. Obs. </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Importe</td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Núm. Obs. </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Importe </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Núm. Obs. </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Importe </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Plazo Convenido </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Fecha termino </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Núm. Obs. </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Núm. Obs. </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Núm. Obs. </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> PO </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> SA </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> R </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> PRAS </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Total</td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Núm. Obs. </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Importe </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Núm. Obs. </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Importe </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Núm. Obs. </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Importe </td>
							
							<td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Número </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Fecha (sello de acuse)</td>
							<td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Número </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Fecha (sello de acuse)</td>
							<td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Número </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Fecha (sello de acuse)</td>
							<td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Número </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Fecha (sello de acuse)</td>
							<td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Número </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Fecha (sello de acuse)</td>
							<td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Número </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Fecha (sello de acuse)</td>
							
							
							
							
							
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Analista </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Líder de proyecto </td>                            
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Jefe de departamento </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Direccion </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Recibidas </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Atendidas </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Pendientes </td>
                            <td style="color: white; background-color: #A13B71; border:solid; text-align:center;"> Fecha de última contestación </td>
                        </tr>                             
                    </thead>
                    <tbody>
                        @forelse ($auditorias as $indice => $auditoria)
						
                            <tr style="background-color:{!! $indice % 2==0 ? '#EFF2F6':''; !!};">
                               <td class="text-center">{{$auditoria->tipo_entidad}}</td>
							   <td class="text-center">{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</td> 
							   <td class="text-center">{{$auditoria->entidad_sigla}}</td>
							   <td class="text-center">{{$auditoria->tipo_paa}}</td>
							    @if(getSession('cp')==2022)
									<td>{{$auditoria->entidades}}</td>
								@else
									<td>{{$auditoria->aud_paa_desc}}</td>
								@endif
							   <td class="text-center">{{$auditoria->acto_fiscalizacion}}</td>
							   <td class="text-center">{{$auditoria->periodo_revision}}</td>
							   <td class="text-center">{{$auditoria->numero_auditoria}}</td>
							   
							   <td class="text-center">{{$auditoria->num_recepcion_expediente}}</td>							   
							   <td class="text-center">{{fecha($auditoria->fecha_expediente_turnado)}}</td>
							   
							   <td class="text-center">{{$auditoria->numero_expediente}}</td>
							   <td class="text-center">{{fecha($auditoria->fecha_notificacion)}}</td>
							   <td class="text-center">{{fecha($auditoria->fecha_comparecencia)}}</td>
							   
							   @if(getSession('cp')==2022)
							   
							   <td class="text-center">{{$auditoria->numero_acuerdo}}</td>
							   <td class="text-center">{{fecha($auditoria->fecha_oficio_informe)}}</td>
							   
							   @else
								   
							   <td class="text-center">{{$auditoria->oficio_acuerdo}}</td>
							   <td class="text-center">{{fecha($auditoria->fecha_oficio_acuerdo)}}</td>
							   
							   @endif
							   
							   @if($auditoria->acto_fiscalizacion=="Desempeño")								   
								   <td class="text-center"></td>
								   <td class="text-center"></td>
								   <td class="text-center"></td>								
								@else							   
								   <td class="text-center">{{fecha($auditoria->fecha_inicio_aclaracion)}}</td>
								   <td class="text-center">{{fecha($auditoria->fecha_termino_aclaracion)}}</td>
								   <td class="text-center">{{empty($auditoria->calculo_fecha)?fecha($auditoria->fecha_termino_mas120):fecha($auditoria->calculo_fecha)}} </td>							   
							   @endif
							   
							   
							   <td class="text-center">{{$auditoria->pliegos_promovidos}}</td>
							   <td class="text-right" style="text-align: right;">{{$auditoria->importe_promovido}}</td>
							   <td class="text-center">{{$auditoria->pliegos_solventado}}</td>
							   <td class="text-right" style="text-align: right;">{{$auditoria->importe_solventado}}</td>
							   <td class="text-center">{{$auditoria->pliegos_no_solventado}}</td>
							   <td class="text-right" style="text-align: right;">{{$auditoria->importe_no_solventado}}</td>
							   <td class="text-center">{{$auditoria->plazo_convenido}}</td>
							   <td class="text-center">{{fecha($auditoria->fecha_termino_recomendacion)}}</td>
							   <td class="text-center">{{$auditoria->recomendaciones_promovidas}}</td>
							   <td class="text-center">{{$auditoria->recomendaciones_atendidas}}</td>
							   <td class="text-center">{{$auditoria->recomendaciones_no_atendidas}}</td>
							   <td class="text-center">{{$auditoria->po}}</td>
							   <td class="text-center">{{$auditoria->sa}}</td>
							   <td class="text-center">{{$auditoria->r}}</td>
							   <td class="text-center">{{$auditoria->pras}}</td>
							   <td class="text-center">{{$auditoria->total}}</td>	
							   
							   <td class="text-center">{{ $auditoria->acto_fiscalizacion=="Desempeño"?"0":$auditoria->accionespromnumobs}}</td>
							   <td style="text-align: right;">{{$auditoria->importeaccionespromnumobs}}</td>
							   <td class="text-center">{{$auditoria->acto_fiscalizacion=="Desempeño"?"0":$auditoria->accionespromsolv}}</td>
							   <td style="text-align: right;">{{$auditoria->importepromsolv}}</td>
							   <td class="text-center">{{$auditoria->acto_fiscalizacion=="Desempeño"?"0":$auditoria->accionespromnosolv}}</td>
							   <td style="text-align: right;"> {{$auditoria->importepromnosolv}}</td>
							   <td class="text-center">{{$auditoria->area_encuentra}}</td>
							   <td class="text-center">{{$auditoria->ppseaea}}</td>
							   <td class="text-center">{{$auditoria->ppseaar}}</td>
							   
							   <td class="text-center">{{$auditoria->numero_oficio_acea}}</td>
							   <td class="text-center">{{fecha($auditoria->fecha_acea)}}</td>
							   <td class="text-center">{{$auditoria->numero_oficio_acpa}}</td>
							   <td class="text-center">{{fecha($auditoria->fecha_acpa)}}</td>
							   <td class="text-center">{{$auditoria->numero_notisea}}</td>
							   <td class="text-center">{{fecha($auditoria->fecha_notisea)}}</td>
							   <td class="text-center">{{$auditoria->numero_notispa}}</td>
							   <td class="text-center">{{fecha($auditoria->fecha_notispa)}}</td>
							   <td class="text-center">{{$auditoria->numero_turno_ui}}</td>							   
							   <td class="text-center">{{fecha($auditoria->fecha_envioui)}}</td>
							   <td class="text-center">{{$auditoria->numero_turno_oic}}</td>
							   <td class="text-center">{{fecha($auditoria->fecha_enviooic)}}</td>
							   
							   <td>{{$auditoria->nombre_analista}}</td>
							   <td>{{$auditoria->nombre_lider}}</td>
							   <td>{{$auditoria->nombre_jefe}}</td>
							   <td>{{$auditoria->nombre_director}}</td>			   
							   <td class="text-center">{{$auditoria->contesrecibidas}}</td>
							   <td class="text-center">{{$auditoria->contestacionesatendidas}}</td>
							   <td class="text-center">{{$auditoria->contestacionespendientes}}</td>
							   <td class="text-center">{{fecha($auditoria->fechaultcont)}}</td>                       
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="8">
                                    <span class='text-center'>No hay registros en éste apartado</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination" style="justify-content:right !important;">
                {{ $auditorias->appends(['consecutivo'=>$request->consecutivo,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')   
    <script>
       const element = document.querySelector("#tabscroll");

		element.addEventListener('wheel', (event) => {
		  event.preventDefault();

		  element.scrollBy({
			left: event.deltaY < 0 ? -30 : 30,
			
		  });
		});
    </script>  
@endsection

