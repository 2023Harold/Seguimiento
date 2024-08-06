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
                            <th rowspan=3 style="width:20px" class="text-center  bg-danger"> Tipo Auditoria </th>                          
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. Progresivo </th>                    
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. Entidad/ Siglas </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Tipo de Entidad </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Entidad fiscalizada </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Tipo Auditoría </th> 
                            <th rowspan=3 style="width:20px" class="text-center"> Periodo Auditado </th> 
                            <th rowspan=3 style="width:20px" class="text-center"> Año Auditado </th>
                            <th rowspan=3 style="width:20px" class="text-center bg-danger"> Admon. </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. de Auditoría </th>
                            <th rowspan=3 style="width:20px" class="text-center  bg-warning"> Fecha de recepción del expediente </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. de expediente (interno US) </th>
                            <th rowspan=3 style="width:20px" class="text-center bg-warning"> Fecha del acuerdo de radicación </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Fecha de comparecencia</th>
                            <th rowspan=2 colspan=2 style="width:50px" class="text-center bg-warning"> Oficio donde se notifican los acuerdos </th>                                                         
                            <th colspan=9 style="width:100px" class="text-center"> Etapa de Aclaración </th> 
                            <th colspan=5 style="width:100px" class="text-center"> Etapa de atención de Recomendaciones</th>
                            <th rowspan=2 colspan=5 style="width:100px" class="text-center"> Acciones Promovidas 1.° Etapa  </th>
                            <th rowspan=2 colspan=2 style="width:50px" class="text-center bg-warning"> Acciones promovidas </th>  
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
                            <th colspan=2 class="bg-danger"> Proceso de Atención </th>    
                            <th> Recomendaciones Promovidas </th>    
                            <th> Recomendaciones Atendidas </th>    
                            <th> Recomendaciones No Atendidas </th>                                                        
                        </tr> 
                        <tr> 
                            <th class="bg-warning"> Fecha </th>
                            <th class="bg-warning"> Número</th>
                            <th> Notificación</th>
                            <th> Vencimiento</th>
                            <th> 120 días de la etapa de Seguimiento</th>
                            <th> Núm. Obs. </th>
                            <th> Importe</th>
                            <th> Núm. Obs. </th>
                            <th> Importe </th>
                            <th> Núm. Obs. </th>
                            <th> Importe </th>
                            <th class="bg-danger"> Plazo Convenido </th>
                            <th class="bg-danger"> Fecha termino </th>
                            <th> Núm. Obs. </th>
                            <th> Núm. Obs. </th>
                            <th> Núm. Obs. </th>
                            <th> PO </th>
                            <th> SA </th>
                            <th> R </th>
                            <th> PRAS </th>
                            <th> Total</th>
                            <th class="bg-warning"> Núm. Obs. </th>
                            <th class="bg-warning"> Importe </th>
                            <th> Núm. Obs. </th>
                            <th> Importe </th>
                            <th> Núm. Obs. </th>
                            <th> Importe </th>
                            <th class="bg-warning"> Analista </th>
                            <th class="bg-warning"> Líder de proyecto </th>                            
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
                                <td class="text-center bg-danger">MUNICIPAL</td>
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
                                    {{ $auditoria->periodo_revision }}                                    
                                </td>                                              
                                <td class="text-center bg-light-dark">                                       
                                    {{ $auditoria->ejercicio-1 }}                                       
                                </td>
                                <td style="text-align: center;" class="bg-danger">
                                   2022-2024
                                </td>   
                                <td class="text-center bg-light-dark">                                       
                                    {{ $auditoria->tipo_auditoria->sigla.' - '.$auditoria->numero_auditoria }}                                     
                                </td>
                                <td class="text-center bg-warning">                                       
                                    {{ fecha($auditoria->radicacion->fecha_oficio_acuerdo)}}                                     
                                </td>                                 
                                <td class="text-center bg-light-dark">                                       
                                    {{ $auditoria->radicacion->numero_expediente}}                                     
                                </td> 
                                <td class="text-center bg-warning">                                       
                                    {{ fecha($auditoria->radicacion->fecha_oficio_acuerdo)}}                                     
                                </td>  
                                <td class="text-center bg-light-dark">                                       
                                    {{ fecha($auditoria->comparecencia->fecha_comparecencia)}}                                     
                                </td> 
                                <td class="text-center bg-warning">                                       
                                    {{ fecha($auditoria->comparecencia->fecha_acta)}}                                     
                                </td>                             
                                <td class="text-center bg-warning">                                       
                                    {{ $auditoria->comparecencia->numero_acta}}                                     
                                </td>  
                                <td></td>
                                <td class="text-center bg-light-dark">
                                    {{ fecha($auditoria->comparecencia->fecha_inicio_aclaracion)}}    
                                </td>
                                <td class="text-center">
                                    {{ fecha($auditoria->comparecencia->fecha_termino_aclaracion)}}
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
                                <td class="bg-danger text-center">
                                    120 días hábiles
                                </td>
                                <td class="bg-danger text-center">
                                    15-may-24
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
                                <td class="text-center bg-warning">                                       
                                    {{ $auditoria->acciones->count() }}                                     
                                </td> 
                                <td class="bg-warning" style="text-align: right!important;">  
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
                                <td class="text-center bg-danger">
                                    EN ANÁLISIS
                                </td>
                                <td class="text-center bg-danger">
                                    NO APLICA
                                </td>
                                <td class="text-center bg-danger">
                                    01-feb-24
                                </td>
                                <td class="text-center bg-danger"></td>
                                <td class="text-center bg-danger">
                                    26-abr-24
                                </td>
                                <td class="text-center bg-danger"></td>
                                <td class="text-center">                                    
                                </td>
                                <td class="bg-light-dark">
                                </td>
                                <td class="bg-warning">
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
                                <td class="bg-warning">
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
                                <td class="bg-danger"></td>
                                <td class="bg-danger"></td>
                                <td class="bg-danger"></td>
                                <td class="bg-danger"></td>
                                                   
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
