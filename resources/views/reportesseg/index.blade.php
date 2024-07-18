@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('reportesseg.index',$auditorias) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Reportes
                </h1>
            </div> 
            <div class="card-body">           
            <div class="table-responsive" >
                <table class="table" border="1" style="table-layout: fixed; width: 20000px;">
                    <thead>
                        <tr>
                            <th rowspan=3 style="width:20px" class="text-center"> Tipo Auditoria </th>                          
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. Progresivo </th>                    
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. Entidad/ Siglas </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Tipo de Entidad </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Entidad fiscalizada </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Tipo Auditoría </th> 
                            <th rowspan=3 style="width:20px" class="text-center"> Periodo Auditado </th> 
                            <th rowspan=3 style="width:20px" class="text-center"> Año Auditado </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Admon. </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. de Auditoría </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Fecha de recepción del expediente </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Núm. de expediente (interno US) </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Fecha del acuerdo de radicación </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Fecha de comparecencia</th>
                            <th rowspan=2 colspan=2 style="width:50px" class="text-center"> Oficio donde se notifican los acuerdos </th>                            
                            <th colspan=9 style="width:100px" class="text-center"> Etapa de Aclaración </th> 
                            <th colspan=5 style="width:100px" class="text-center"> Etapa de atención de Recomendaciones</th>
                            <th rowspan=2 colspan=5 style="width:100px" class="text-center"> Acciones Promovidas 1.° Etapa  </th>
                            <th rowspan=2 colspan=2 style="width:50px" class="text-center"> Acciones Promovidas </th>
                            <th rowspan=2 colspan=2 style="width:50px" class="text-center"> Acciones Promovidas Solventadas</th>
                            <th rowspan=2 colspan=2 style="width:50px" class="text-center"> Acciones Promovidas No Solventadas</th>
                            <th rowspan=3 style="width:100px" class="text-center"> Area en que se encuentra </th>
                            <th rowspan=3 style="width:100px" class="text-center"> Parte del Proceso de Seguimiento en que se encuentra la Auditoría >Etapa aclaración< </th>
                            <th rowspan=3 style="width:100px" class="text-center"> Parte del Proceso de Seguimiento en que se encuentra la Auditoría >Atención de las Recom.< </th>
                            <th rowspan=3 style="width:100px" class="text-center"> Fecha de acuse de la conclusión de la Etapa de Aclaración </th>
                            <th rowspan=3 style="width:100px" class="text-center"> Fecha de acuse de la conclusión al proceso de atención de las recom.</th>
                            <th rowspan=3 style="width:100px" class="text-center"> Fecha de acuse de oficio de notif. del informe de seguimiento de la etapa de aclaración </th>
                            <th rowspan=3 style="width:100px" class="text-center"> Fecha de acuse de oficio de notif. del informe de seguimiento al proceso de atención de las recom. </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Fecha de envío a la UI. </th>
                            <th rowspan=3 style="width:20px" class="text-center"> Fecha de envío a la OIC </th>
                            <th rowspan=2 colspan=4 style="width:70px" class="text-center"> Asignación</th>
                            <th rowspan=2 colspan=5 style="width:80px" class="text-center"> Contestaciones</th>                            
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
                            <th> vencimiento</th>
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
                            <th> Recibidas </th>
                            <th> Atendidas </th>
                            <th> Pendientes </th>
                            <th> Fecha de última contestación </th>
                        </tr>                             
                    </thead>
                    <tbody>
                        @forelse ($auditorias as $auditoria)
                            <tr>
                                <td class="text-center">
                                    {{ str_pad($auditoria->consecutivo, 3, '0', STR_PAD_LEFT) }}
                                </td>                                
                                <td>
                                    {{ $auditoria->numero_auditoria }}
                                </td>
                                <td  width='40%'>
                                    @php
                                        $entidadparciales = explode("-", $auditoria->entidad_fiscalizable);
                                    @endphp
                                    @foreach ($entidadparciales as $entidadparcial)
                                        {{ mb_convert_encoding(mb_convert_case(strtolower($entidadparcial), MB_CASE_TITLE), "UTF-8"); }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    {{ $auditoria->acto_fiscalizacion }}                                    
                                </td> 
                                <td>
                                    {{ $auditoria->tipo_auditoria_id }}                                    
                                </td> 
                                                                                       
                                <td class="text-center">                                       
                                    <a href="{{ route('tipologiaauditorias.edit', $auditoria) }}" class="btn btn-primary">Ingresar</a>                                       
                                </td>
                                <td style="text-align: right!important;">
                                    {{ '$'.number_format( $auditoria->total(), 2) }}
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
