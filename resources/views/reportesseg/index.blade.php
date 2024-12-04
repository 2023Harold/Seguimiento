@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('reportesseg.index',$auditorias) }}
@endsection
@section('content')
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
                            <a href="{!! route('reporteseguimiento.exportar') !!}" class=" btn btn-primary">Excel</a>
                        </div>
                    </div>                
                </h1>
                
            </div> 
            <div class="card-body">           
            <div class="table-responsive" >
                <table class="table" border="1" style="table-layout: fixed; width: 20000px;">
                    <thead>
                        <tr>
                            <th rowspan=3 style="width:20px" class="text-center"> Tipo de Entidad </th>                          
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. Progresivo </th>                    
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. Entidad/ Siglas </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Tipo de Entidad </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Auditoría PAA </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Tipo Auditoría </th> 
                            <th rowspan=3 style="width:20px" class="text-center"> Periodo Auditado</th> 
                            <th rowspan=3 style="width:20px" class="text-center"> Año Auditado </th>                            
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. de Auditoría </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Fecha de recepción del expediente </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. de expediente (interno US) </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Fecha del acuerdo de radicación </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Fecha de comparecencia</th>
                            <th rowspan=2 colspan=2 style="width:50px" class="text-center"> Oficio donde se notifican los acuerdos </th>                                                         
                            <th colspan=9 style="width:100px" class="text-center"> Etapa de Aclaración </th> 
                            <th colspan=5 style="width:100px" class="text-center"> Etapa de atención de Recomendaciones</th>
                            <th rowspan=2 colspan=5 style="width:100px" class="text-center"> Acciones Promovidas 1.° Etapa  </th>
                            <th rowspan=2 colspan=2 style="width:50px" class="text-center"> Acciones promovidas </th>  
                            <th rowspan=2 colspan=2 style="width:50px" class="text-center"> Acciones promovidas solventadas </th>   
                            <th rowspan=2 colspan=2 style="width:50px" class="text-center"> Acciones promovidas no solventadas </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Área en que sencuentra </th>                             
                            <th rowspan=3 style="width:50px; word-wrap: break-word!important;" class="text-center bg-danger" > Parte del Proceso de Seguimiento en que se encuentra la Auditoría >Etapa aclaración< </th>
                            <th rowspan=3 style="width:50px; word-wrap: break-word!important;" class="text-center bg-danger"> Parte del Proceso de Seguimiento en que se encuentra la Auditoría >Atención de las Recom.< </th>
                            <th rowspan=3 style="width:20px; word-wrap: break-word!important;" class="text-center bg-danger"> Fecha de acuse de la conclusión de la Etapa de Aclaración </th>
                            <th rowspan=3 style="width:20px; word-wrap: break-word!important;" class="text-center bg-danger"> Fecha de acuse de la conclusión al proceso de atención de las recom.</th>
                            <th rowspan=3 style="width:20px; word-wrap: break-word!important;" class="text-center bg-danger"> Fecha de acuse de oficio de notif. del informe de seguimiento de la etapa de aclaración </th>
                            <th rowspan=3 style="width:20px; word-wrap: break-word!important;" class="text-center bg-danger"> Fecha de acuse de oficio de notif. del informe de seguimiento al proceso de atención de las recom. </th>
                            <th rowspan=3 style="width:20px; word-wrap: break-word!important;" class="text-center"> Fecha de envío a la UI. </th>
                            <th rowspan=3 style="width:20px; word-wrap: break-word!important;" class="text-center"> Fecha de envío a la OIC </th>
                            <th rowspan=2 colspan=4 style="width:70px" class="text-center"> Asignación</th>
                            <th rowspan=2 colspan=4 style="width:80px" class="text-center bg-danger"> Contestaciones</th>                            
                        </tr>  
                        <tr>                              
                            <th colspan=3> Fechas </th>
                            <th colspan=2> Pliegos de Observaciones Promovidos </th>
                            <th colspan=2> Pliegos de observaciones solventados</th>
                            <th colspan=2> Pliegos de Observaciones No Solventados </th>
                            <th colspan=2> Proceso de Atención </th>    
                            <th> Recomendaciones Promovidas </th>    
                            <th> Recomendaciones Atendidas </th>    
                            <th> Recomendaciones No Atendidas </th>                                                        
                        </tr> 
                        <tr> 
                            <th> Fecha </th>
                            <th> Número</th>
                            <th> Notificación</th>
                            <th> Vencimiento</th>
                            <th> 120 días de la etapa de Seguimiento</th>
                            <th> Núm. Obs. </th>
                            <th> Importe</th>
                            <th> Núm. Obs. </th>
                            <th> Importe </th>
                            <th> Núm. Obs. </th>
                            <th> Importe </th>
                            <th> Plazo Convenido </th>
                            <th> Fecha termino </th>
                            <th> Núm. Obs. </th>
                            <th> Núm. Obs. </th>
                            <th> Núm. Obs. </th>
                            <th> PO </th>
                            <th> SA </th>
                            <th> R </th>
                            <th> PRAS </th>
                            <th> Total</th>
                            <th> Núm. Obs. </th>
                            <th> Importe </th>
                            <th> Núm. Obs. </th>
                            <th> Importe </th>
                            <th> Núm. Obs. </th>
                            <th> Importe </th>
                            <th> Analista </th>
                            <th> Líder de proyecto </th>                            
                            <th> Jefe de departamento </th>
                            <th> Direccion </th>
                            <th class="bg-danger"> Recibidas </th>
                            <th class="bg-danger"> Atendidas </th>
                            <th class="bg-danger"> Pendientes </th>
                            <th class="bg-danger"> Fecha de última contestación </th>
                        </tr>                             
                    </thead>
                    <tbody>
                        @forelse ($auditorias as $auditoria)
                            <tr>
                                <td class="text-center">
                                    {{ $auditoria->tipoEntidadAmbito=='PODER EJECUTIVO'?'ESTATAL':$auditoria->tipoEntidadAmbito}}
                                </td>
                                <td class="text-center bg-light-dark">{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</td>                                                             
                                <td class="text-center">                                    
                                    @if (!empty($auditoria->siglas_entidad))
                                    {{ $auditoria->siglas_entidad }}
                                    @else
                                    {{ $auditoria->entidad_fiscalizable_id }}
                                    @endif
                                </td>
                                <td class="text-center bg-light-dark">
                                    {{ $auditoria->tipo_entidad }}
                                </td>
                                <td  width='40%'>
                                    @php
                                        $entidadparciales = explode("-", $auditoria->entidad_fiscalizable);
                                    @endphp
                                    @foreach ($entidadparciales as $entidadparcial)
                                        {{ mb_convert_encoding(mb_convert_case(strtolower($entidadparcial), MB_CASE_TITLE), "UTF-8"); }}<br>
                                    @endforeach
                                </td>
                                <td class="bg-light-dark">
                                    {{ $auditoria->acto_fiscalizacion }}                                    
                                </td> 
                                <td>       
                                    {{ $auditoria->periodomes }}                                                                 
                                </td>                                              
                                <td class="text-center bg-light-dark">                                       
                                    {{ $auditoria->periodoAnio }}                            
                                </td>      
                                 {{-- Núm. de Auditoría	 --}}
                                <td class="text-center bg-light-dark">                                       
                                    {{ $auditoria->tipo_auditoria->sigla.' - '.$auditoria->numero_auditoria }}                                     
                                </td> 
                                {{-- fecha del acuerdo de radicación --}}                              
                                <td class="text-center"> 
                                    @if (!empty($auditoria->radicacion->fecha_expediente_turnado))                                                                                                                                                                   
                                    {{ fecha($auditoria->radicacion->fecha_expediente_turnado)}}
                                    @endif                                                                                                                                                  
                                </td>  
                                {{-- Núm. de expediente (interno US)	--}}
                                <td class="text-center bg-light-dark">                                       
                                 @if (!empty( $auditoria->radicacion->numero_expediente))                                                                                                                                                                                
                                 {{  $auditoria->radicacion->numero_expediente}}                                     
                                @endif                                       
                                </td> 
                                {{--Fecha del acuerdo de radicación	  --}}
                                <td class="text-center"> 
                                    @if(!empty($auditoria->radicacion->fecha_notificacion))                                  
                                    {{fecha($auditoria->radicacion->fecha_notificacion)}}                                                         
                                    @endif                                                                                                           
                                </td>  
                                {{--  Fecha de comparecencia	--}}
                                <td class="text-center bg-light-dark">                                       
                                @if(!empty($auditoria->comparecencia->fecha_comparecencia))                                      
                                {{ fecha($auditoria->comparecencia->fecha_comparecencia)}}
                                @endif                                      
                                </td> 
                                <td class="text-center">     
                                @if(!empty($auditoria->radicacion->fecha_notificacion))
                                {{ fecha($auditoria->radicacion->fecha_notificacion)}}                                     
                                @endif                                                                                                          
                                </td>                             
                                <td class="text-center">   
                                @if(!empty($auditoria->radicacion->num_memo_recepcion_expediente))
                                {{ $auditoria->radicacion->num_memo_recepcion_expediente}}                                    
                                @endif                                                                            
                                </td>  
                                <td class="text-center">
                                @if(!empty($auditoria->comparecencia->fecha_inicio_aclaracion))
                                {{ fecha($auditoria->comparecencia->fecha_inicio_aclaracion)}}    
                                @endif                                      
                                </td>
                                <td class="text-center bg-light-dark">
                                @if(!empty($auditoria->comparecencia->fecha_termino_aclaracion))   
                                {{ fecha($auditoria->comparecencia->fecha_termino_aclaracion)}}    
                                @endif 
                                </td>
                                {{-- 120 días de la etapa de Seguimiento	 --}}
                                <td class="text-center">
                                @if(!empty($auditoria->radicacion->calculo_fecha))   
                                {{ fecha($auditoria->radicacion->calculo_fecha)}}    
                                @endif                     
                                </td>
                                <td class="bg-light-dark text-center">
                                    {{  $auditoria->totalpliegos->count()}} 
                                </td>
                                <td style="text-align: right!important;">                                    
                                    {{ '$'.number_format( $auditoria->totalpliegos->sum('monto_aclarar'), 2) }}  
                                </td>
                                <td class="text-center bg-light-dark">
                                    {{  $auditoria->totalsolventadopliegos->count()}}
                                </td>
                                <td style="text-align: right!important;">
                                    {{ '$'.number_format( $auditoria->totalsolventadopliegos->sum('monto_aclarar'), 2) }}
                                </td>
                                <td class="text-center bg-light-dark">
                                    {{  $auditoria->totalNOsolventadopliegos->count()}}
                                </td>
                                <td style="text-align: right!important;">
                                    {{ '$'.number_format( $auditoria->totalNOsolventadopliegos->sum('monto_aclarar'), 2) }}
                                </td>
                                {{-- Plazo Convenido	 --}}
                                <td class="text-center">
                                 @if (!empty( $auditoria->radicacion->plazo_maximo))                                                                                                                                                                                
                                 {{ $auditoria->radicacion->plazo_maximo}} días hábiles                                     
                                @endif                                 
                                </td>
                                {{-- fecha termino --}}
                                <td class="text-center">
                                @if (!empty( $auditoria->radicacion->calculo_fecha))    
                                {{ fecha($auditoria->radicacion->calculo_fecha)}}
                                @endif
                                </td>
                                <td class="text-center bg-light-dark">
                                    {{  $auditoria->totalrecomendacion->count()}}
                                </td>
                                <td class="text-center">                                    
                                    {{  $auditoria->totalsolventadorecomendacion->count()}}
                                </td>
                                <td class="text-center bg-light-dark">
                                    {{  $auditoria->totalNOsolventadorecomendacion->count()}}
                                </td>
                                <td class="text-center">
                                    {{  $auditoria->totalpliegos->count()}} 
                                </td>
                                <td class="text-center bg-light-dark">
                                    {{  $auditoria->totalsolacl->count()}} 
                                </td>
                                <td class="text-center">
                                    {{  $auditoria->totalrecomendacion->count()}} 
                                </td>
                                <td class="text-center bg-light-dark">
                                    {{  $auditoria->totalpras->count()}} 
                                </td>
                                <td class="text-center">
                                    {{ $auditoria->acciones->count() }} 
                                </td>
                                {{-- Nota:  Se contemplan los PRAS   --}}
                                <td class="text-center">                                       
                                    {{ $auditoria->acciones->count() }}                                     
                                </td> 
                                <td style="text-align: right!important;">  
                                    {{ '$'.number_format(  $auditoria->total(), 2) }}  
                                </td>
                                <td class="text-center bg-light-dark">                                       
                                    {{ $auditoria->totalsolventadorecomendacion->count() + $auditoria->totalsolventadopras->count() +  $auditoria->totalsolventadosolacl->count() + $auditoria->totalsolventadopliegos->count()}}                                     
                                </td>                           
                                <td style="text-align: right!important;"> 
                                    {{ '$'.number_format( ($auditoria->totalsolventadorecomendacion->sum('monto_aclarar') + $auditoria->totalsolventadopras->sum('monto_aclarar') +  $auditoria->totalsolventadosolacl->sum('monto_aclarar') + $auditoria->totalsolventadopliegos->sum('monto_aclarar')), 2) }} </td>                           
                                <td class="text-center bg-light-dark">                                       
                                    {{ $auditoria->totalNOsolventadorecomendacion->count() + $auditoria->totalNOsolventadopras->count() +  $auditoria->totalNOsolventadosolacl->count() + $auditoria->totalNOsolventadopliegos->count()}}                                     
                                </td>                           
                                <td style="text-align: right!important;"> 
                                    {{ '$'.number_format(($auditoria->totalNOsolventadorecomendacion->sum('monto_aclarar') + $auditoria->totalNOsolventadopras->sum('monto_aclarar') +  $auditoria->totalNOsolventadosolacl->sum('monto_aclarar') + $auditoria->totalNOsolventadopliegos->sum('monto_aclarar')), 2) }} 
                                </td>   
                                <td class="text-center bg-light-dark">                                       
                                   US
                                </td>  
                                <td class="text-center">
                                    EN ANÁLISIS
                                </td>
                                <td class="text-center bg-light-dark">
                                    NO APLICA
                                </td>

                                {{-- Fecha de acuse de la conclusión de la Etapa de Aclaración	 --}}
                                <td class="text-center">
                                    @if (!empty($auditoria->acuerdoconclusion->fecha_acuerdo_conclusion))                                                                                                                                                                                
                                    {{ fecha($auditoria->acuerdoconclusion->fecha_acuerdo_conclusion)}}                                     
                                     @endif                                   
                                </td>
                                {{--  Fecha de acuse de la conclusión al proceso de atención de las recom.	--}}                              
                                <td class="text-center bg-light-dark">
                                    @if (!empty($auditoria->comparecencia->fecha_termino_proceso))                                                                                                                                                                                
                                    {{ fecha($auditoria->comparecencia->fecha_termino_proceso)}}                                     
                                     @endif                                              
                                </td>

                                {{--Fecha de acuse de oficio de notif. del informe de seguimiento de la etapa de aclaración	 --}}
                                <td class="text-center">
                                    @if (!empty($auditoria->informeprimeraetapa->fecha_notificacion))                                                                                                                                                                                
                                    {{fecha($auditoria->informeprimeraetapa->fecha_notificacion)}}                                     
                                     @endif      
                                </td>
                                {{-- Fecha de acuse de oficio de notif. del informe de seguimiento al proceso de atención de las recom.	 --}}
                                <td class="text-center bg-light-dark">
                                                          
                                </td>
                                {{-- Fecha de envío a la UI.--}}                            
                                <td class="text-center">        
                                    @if (!empty($auditoria->turnoui->fecha_notificacion_ui))                                                                                                                                                                                
                                    {{fecha($auditoria->turnoui->fecha_notificacion_ui)}}        
                                    @endif          
                                </td>
                                {{-- Fecha de envío a la OIC --}}
                                <td class="text-center bg-light-dark">      
                                    @if (!empty($auditoria->turnooic->fecha_envio))                                                                                                                                                                                
                                    {{ fecha($auditoria->turnooic->fecha_envio) }} 
                                    @endif                                                           
                                </td>
                                <td>
                                    @php
                                        $analistas='';
                                        
                                        foreach ($auditoria->acciones as $accion) {
                                            if(!str_contains($analistas, $accion->analista_asignado))
                                            $analistas=$analistas.$accion->analista_asignado.', ';                                            
                                        }
                                        $analistas = substr($analistas, 0, -2);

                                    @endphp                                   
                                     {{$analistas}}
                                </td>
                                <td class="bg-light-dark">
                                    @php
                                    $lideres='';                                    
                                    foreach ($auditoria->acciones as $accion) {
                                        if(!str_contains($lideres, $accion->lider_asignado))
                                        $lideres=$lideres.$accion->lider_asignado.', ';                                            
                                    }
                                    $lideres = substr($lideres, 0, -2);
                                    @endphp                                   
                                    {{$lideres}}
                                </td>
                                <td class="text-center">
                                    {{$auditoria->departamento_encargado}}<br>
                                    {{ $auditoria->jefedepartamentoencargado->name}} 
                                </td>
                                <td class="text-center bg-light-dark">
                                    {{ $auditoria->direccion_asignada}} <br>
                                    {{ $auditoria->directorasignado->name}} <br>
                                </td>
                                <td class="text-center">                                    
                                    @php
                                        $contestacionestotal=0;
                                        $rec=0;
                                        $sa=0;
                                        $po=0;
                                        foreach ($auditoria->acciones as $accion) {
                                            if (!empty($accion->recomendaciones)) {                                                
                                                // dd($accion->recomendaciones);
                                                // foreach ($accion->recomendaciones as $recomendacion) {
                                                    $contestaciones=$accion->recomendaciones->contestaciones;
                                                   
                                                    if (count($contestaciones)>0) {                                                               
                                                        $rec=count($contestaciones);                                               
                                                    }
                                                // } 
                                            }

                                            if (!empty($accion->solicitudesaclaracion)) {                                                
                                                // dd($accion->recomendaciones);
                                                // foreach ($accion->recomendaciones as $recomendacion) {
                                                    $contestaciones=$accion->solicitudesaclaracion->contestaciones;
                                                   
                                                    if (count($contestaciones)>0) {                                                        
                                                        $sa=count($contestaciones);                                                        
                                                    }
                                                // } 
                                            }

                                            if (!empty($accion->pliegosobservacion)) {                                                
                                                // dd($accion->recomendaciones);
                                                // foreach ($accion->recomendaciones as $recomendacion) {
                                                    $contestaciones=$accion->pliegosobservacion->contestaciones;
                                                   
                                                    if (count($contestaciones)>0) {                                                        
                                                        $po=count($contestaciones);                                                       
                                                    }
                                                // } 
                                            }
                                        }
                                        $contestacionestotal=$rec+$sa+$po;
                                    @endphp
                                    {{$contestacionestotal}}
                                </td>
                                <td class="bg-light-dark text-center">
                                   @php
                                   $contestacionesAtendidas=0;
                                     foreach ($auditoria->acciones as $accion) {

                                        if (!empty($accion->recomendaciones)) {                                                
                                                
                                                    $atencionr=empty($accion->recomendaciones->calificacion_sugerida)?'NoHay':$accion->recomendaciones->calificacion_sugerida;
                                                    $autorizacionr=empty($accion->recomendaciones->fase_autorizacion)?'NoHay':$accion->recomendaciones->fase_autorizacion;
                                                   
                                                   
                                                    if ($autorizacionr=='Autorizado') {  
                                                        $contestaciones=$accion->recomendaciones->contestaciones;                                                             
                                                        $contestacionesAtendidas=$contestacionesAtendidas + count($contestaciones);                                               
                                                    }
                                                
                                            }

                                            if (!empty($accion->solicitudesaclaracion)) {                                                
                                                // dd($accion->recomendaciones);
                                                // foreach ($accion->recomendaciones as $recomendacion) {
                                                    $atencionsa=empty($accion->solicitudesaclaracion->calificacion_sugerida)?'NoHay':$accion->solicitudesaclaracion->calificacion_sugerida;
                                                    $autorizacionsa=empty($accion->solicitudesaclaracion->fase_autorizacion)?'NoHay':$accion->solicitudesaclaracion->fase_autorizacion;
                                                   
                                                    if ($autorizacionsa=='Autorizado') {  
                                                        $contestaciones=$accion->solicitudesaclaracion->contestaciones;                                                      
                                                        $contestacionesAtendidas=$contestacionesAtendidas + count($contestaciones);                                                        
                                                    }
                                                // } 
                                            }

                                            if (!empty($accion->pliegosobservacion)) {                                                
                                                // dd($accion->recomendaciones);
                                                // foreach ($accion->recomendaciones as $recomendacion) {
                                                // $contestaciones=$accion->pliegosobservacion->constestaciones;

                                                    $atencionpo=empty($accion->pliegosobservacion->calificacion_sugerida)?'NoHay':$accion->pliegosobservacion->calificacion_sugerida;
                                                    $autorizacionpo=empty($accion->pliegosobservacion->fase_autorizacion)?'NoHay':$accion->pliegosobservacion->fase_autorizacion;
                                                   
                                                    if ($autorizacionpo=='Autorizado') {  
                                                        $contestaciones=$accion->pliegosobservacion->contestaciones; 
                                                        $contestacionesAtendidas=$contestacionesAtendidas + count($contestaciones);                                                      
                                                    }
                                                // } 
                                            }

                                     }
                                     @endphp
                                     {{$contestacionesAtendidas}}
                                </td>
                                <td class="text-center">
                                    @php
                                   $contestacionesNoAtendidas=0;
                                     foreach ($auditoria->acciones as $accion) {

                                        if (!empty($accion->recomendaciones)) {                                                
                                                
                                                    $atencionr=empty($accion->recomendaciones->calificacion_sugerida)?'NoHay':$accion->recomendaciones->calificacion_sugerida;
                                                    $autorizacionr=empty($accion->recomendaciones->fase_autorizacion)?'NoHay':$accion->recomendaciones->fase_autorizacion;
                                                   
                                                   
                                                    if ($autorizacionr!='Autorizado') {     
                                                        $contestaciones=$accion->recomendaciones->contestaciones;                                                           
                                                        $contestacionesNoAtendidas=$contestacionesNoAtendidas + count($contestaciones);                                               
                                                    }
                                                
                                            }

                                            if (!empty($accion->solicitudesaclaracion)) {                                                
                                                // dd($accion->recomendaciones);
                                                // foreach ($accion->recomendaciones as $recomendacion) {
                                                    $atencionsa=empty($accion->solicitudesaclaracion->calificacion_sugerida)?'NoHay':$accion->solicitudesaclaracion->calificacion_sugerida;
                                                    $autorizacionsa=empty($accion->solicitudesaclaracion->fase_autorizacion)?'NoHay':$accion->solicitudesaclaracion->fase_autorizacion;
                                                   
                                                    if ($autorizacionsa!='Autorizado') {     
                                                        $contestaciones=$accion->solicitudesaclaracion->contestaciones;                                                       
                                                        $contestacionesNoAtendidas=$contestacionesNoAtendidas + count($contestaciones);                                                        
                                                    }
                                                // } 
                                            }

                                            if (!empty($accion->pliegosobservacion)) {                                                
                                                // dd($accion->recomendaciones);
                                                // foreach ($accion->recomendaciones as $recomendacion) {
                                                // $contestaciones=$accion->pliegosobservacion->constestaciones;

                                                    $atencionpo=empty($accion->pliegosobservacion->calificacion_sugerida)?'NoHay':$accion->pliegosobservacion->calificacion_sugerida;
                                                    $autorizacionpo=empty($accion->pliegosobservacion->fase_autorizacion)?'NoHay':$accion->pliegosobservacion->fase_autorizacion;
                                                   
                                                    if ($autorizacionpo!='Autorizado') {   
                                                        $contestaciones=$accion->pliegosobservacion->contestaciones;                                                       
                                                        $contestacionesNoAtendidas=$contestacionesNoAtendidas + count($contestaciones);                                                      
                                                    }
                                                // } 
                                            }

                                     }
                                     @endphp
                                     {{$contestacionesNoAtendidas}}
                                </td>
                                <td class="bg-light-dark text-center">
                                    @php
                                    $fechapo=date('01/01/0001');
                                    $fechasa=date('01/01/0001');
                                    $fechar=date('01/01/0001');
                                    
                                     foreach ($auditoria->acciones as $accion) {

                                        if (!empty($accion->recomendaciones)) {                                                
                                            if (count($accion->recomendaciones->constestaciones)>0) {     
                                                $contestaciones=$accion->recomendaciones->contestaciones;                                                           
                                                $fechar=null;
                                                foreach ($contestaciones as $contestacion) {
                                                    if($fechar<$contestacion->fecha_oficio_contestacion){
                                                        $fechar = $contestacion->fecha_oficio_contestacion;
                                                    }                                                            
                                                }
                                            }                                                
                                        }

                                        if (!empty($accion->solicitudesaclaracion)) {
                                            if (count($accion->solicitudesaclaracion->contestaciones)>0) {     
                                                $contestaciones=$accion->solicitudesaclaracion->contestaciones;  
                                                $fechasa=null;                                                     
                                                foreach ($contestaciones as $contestacion) {
                                                    if($fechasa < $contestacion->fecha_oficio_contestacion){
                                                        $fechasa = $contestacion->fecha_oficio_contestacion;
                                                    }                                                            
                                                }                                                   
                                            }                                        
                                        }

                                            if (!empty($accion->pliegosobservacion)) {                                                
                                                if (count($accion->pliegosobservacion->contestaciones)>0) {   
                                                    $contestaciones=$accion->pliegosobservacion->contestaciones;  
                                                    $fechapo=null;                                                     
                                                    foreach ($contestaciones as $contestacion) {
                                                        if($fechapo < $contestacion->fecha_oficio_contestacion){
                                                            $fechapo = $contestacion->fecha_oficio_contestacion;
                                                        }                                                            
                                                    } 
                                                }
                                            }
                                        }
                                         $fecha=null;
                                        if ($fechapo > $fechasa) {
                                            if($fechapo > $fechar){
                                                $fecha=$fechapo;
                                            }else{
                                                $fecha=$fechasa;
                                            }
                                        }else{
                                            if($fechasa > $fechar){
                                                $fecha=$fechasa;
                                            }else{
                                                $fecha=$fechar; 
                                            }
                                        }
                                     @endphp
                                    {{fecha($fecha)}}
                                </td>                                                   
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
            <div class="pagination">
                {{ $auditorias->appends(['consecutivo'=>$request->consecutivo,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
