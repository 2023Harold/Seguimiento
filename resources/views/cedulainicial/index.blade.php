@extends('layouts.app')
@section('breadcrums')
{{Breadcrumbs::render('cedulainicial.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('auditoriaseguimiento.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Cédulas 
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')

                <div class="row">                
                    <div class="card card-bordered border-primary">  
                        <div class="ribbon ribbon-top">
                            <div class="ribbon-label bg-primary">Cédulas Generales</div>  
                         </div>         
                                <div class="card-body">                                         
                                    @php
                                        $totalacciones=count($auditoria->acciones);
                                        $totalrecomendacionesautorizadas=count($auditoria->accionesrecomendacionesautorizadas);
                                        $totalprasautorizadas=count($auditoria->accionesprasautorizadas);
                                        $totalpoautorizadas=count($auditoria->accionespoautorizadas);
                                        $totalsaautorizadas=count($auditoria->accionessolaclautorizadas);
                                        $totaut=$totalrecomendacionesautorizadas+$totalprasautorizadas+$totalpoautorizadas+ $totalsaautorizadas;
                                        $cg_resultado=$resultado['cg_seguimiento'];
                                    @endphp                                              
                                    
                                    <!-- ********************************************************************************************************** CG Seguimiento ******************************************************************************************************************************************* -->
                                    <div class="row">      
                                        @if (count($auditoria->accionessolaclpo)>0)
                                        <div class="col-md-4">
                                            <div class="card card-custom gutter-b bg-light-primary">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                                        <div class="d-flex flex-column mr-3 text-center">
                                                            <a href="{{ route('cedulainicialprimera.edit',$auditoria) }}" target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 ">
                                                                Seguimiento &nbsp;&nbsp;&nbsp;&nbsp; <span class="fa fa-eye"></span>
                                                            </a> 
                                                        </div>                                  
                                                            @if((getSession('cp')==2022) || (getSession('cp')==2023))
                                                                    @if (empty($auditoria->cedulageneralseguimientoarchivo->cedula_cargada) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                                                                    @can('agregarcedulainicial.create')
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <a href="{{ route('agregarcedulainicial.create')}}?tipo=Cédula General Seguimiento" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                                Agregar Cédula <i class="bi bi-cloud-arrow-up-fill fs-1 "></i>
                                                                            </a>             
                                                                        </div>
                                                                    </div>
                                                                    @endcan
                                                                    @endif
                                                                @endif   
                                                                @if (!empty($auditoria->cedulageneralseguimientoarchivo->cedula_cargada))                                      
                                                                    <td class="text-center">
                                                                        @if (!empty($auditoria->cedulageneralseguimientoarchivo->cedula_cargada))
                                                                          <a href="{{ asset($auditoria->cedulageneralseguimientoarchivo->cedula_cargada) }}" target="_blank">
                                                                             <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->cedulageneralseguimientoarchivo->cedula_cargada)) ?>
                                                                          </a>
                                                                        @endif
                                                                    </td>
                                                                @endif                                                                              
                                                          <!-- ********************************************************************************************************** Fasde de Autorización Seguimiento ******************************************************************************************************************************************* -->
                                                             @if (!empty($auditoria->cedulageneralseguimientoarchivo))
                                                                            @if (empty($auditoria->cedulageneralseguimientoarchivo->fase_autorizacion)||$auditoria->cedulageneralseguimientoarchivo->fase_autorizacion=='Rechazado')
                                                                                <span class="badge badge-light-danger">{{ $auditoria->cedulageneralseguimientoarchivo->fase_autorizacion }} </span>
                                                                                @can('agregarcedulainicial.edit')
                                                                                <a href="{{ route('agregarcedulainicial.edit',$auditoria->cedulageneralseguimientoarchivo)}}?tipo=Cédula General Seguimiento" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                                    <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                                                </a>
                                                                                @endcan
                                                                            @endif
                                                                        @if(getSession('cp')!=2023 || (getSession('cp')!=2022))
                                                                            @if ($auditoria->cedulageneralseguimientoarchivo->fase_autorizacion == 'En revisión')
                                                                                @can('cedulaanaliticadesempenorevision.edit')
                                                                                    <a href="{{ route('cedulaanaliticadesempenorevision.edit',$auditoria->cedulageneralseguimientoarchivo) }}"class=" btn btn-primary h6 text-hover-primary mb-5 float popupcomentario">
                                                                                        <li class="fa fa-gavel"></li>
                                                                                        Revisar
                                                                                    </a>
                                                                            @else
                                                                                    <span class="badge badge-light-warning">{{ $auditoria->cedulageneralseguimientoarchivo->fase_autorizacion }} </span>
                                                                                @endcan
                                                                            @endif
                                                                        @endif                                              
                                                                        @if ($auditoria->cedulageneralseguimientoarchivo->fase_autorizacion=='Autorizado')
                                                                            <span class="badge badge-light-success">{{ $auditoria->cedulageneralseguimientoarchivo->fase_autorizacion }} </span>
                                                                        @endif
                                                                    @endif   
                                                                    @if (!empty($auditoria->cedulageneralseguimientoarchivo)&&(empty($auditoria->cedulageneralseguimientoarchivo->fase_autorizacion)||$auditoria->cedulageneralseguimientoarchivo->fase_autorizacion=='Rechazado'))
                                                                        @if (getSession('cp')==2022 || (getSession('cp')==2023) )
                                                                            @can('agregarcedulainicial.edit')
                                                                             <a href="{{ route('cedulasenvio.edit',$auditoria->cedulageneralseguimientoarchivo) }}" class="btn btn-primary">
                                                                              Enviar
                                                                             </a>
                                                                            @endcan                                                    
                                                                        @endif 
                                                                    @endif                                                                                                                          
                                                        </div>
                                                    </div>
                                                 </div>
                                             </div> 
                                        @endif 
                                        <!-- *************************************************************************************CG Recomendación ************************************************************************************************** -->
                                        @if (count($auditoria->totalrecomendacion)>0)    
                                             @php
                                                $cg_recresultado=$resultado['cg_recomendaciones'];
                                             @endphp                            
                                            <div class="col-md-4">
                                                <div class="card card-custom gutter-b bg-light-primary">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                                             <div class="d-flex flex-column mr-3 text-center">
                                                              <a href="{{ route('cedulageneralrecomendacion.edit',$auditoria) }}" target="_blank" class="h6 text-gray-600 text-hover-primary mb-5">
                                                                 Recomendaciones  &nbsp;&nbsp;&nbsp;&nbsp; <span class="fa fa-eye"></span>
                                                              </a>    
                                                             </div> 
                                                                @if((getSession('cp')==2022) || (getSession('cp')==2023)  )        
                                                                    @if (empty($auditoria->cedulageneralrecomendacionesarchivo->cedula_cargada) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                                                                    @can('agregarcedulainicial.create')
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <a href="{{ route('agregarcedulainicial.create')}}?tipo=Cédula General Recomendación" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                                Agregar Cédula <i class="bi bi-cloud-arrow-up-fill fs-1 "></i>
                                                                            </a>             
                                                                        </div>
                                                                    </div>
                                                                    @endcan
                                                                    @endif
                                                                @endif   
                                                                @if (!empty($auditoria->cedulageneralrecomendacionesarchivo))                                      
                                                                    <td class="text-center">
                                                                        @if (!empty($auditoria->cedulageneralrecomendacionesarchivo->cedula_cargada))
                                                                          <a href="{{ asset($auditoria->cedulageneralrecomendacionesarchivo->cedula_cargada) }}" target="_blank">
                                                                             <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->cedulageneralrecomendacionesarchivo->cedula_cargada)) ?>
                                                                          </a>
                                                                        @endif
                                                                    </td>
                                                                @endif                
                                                                <!-- ************************************************ Fasde de Autorización Recomendaciones *************************************************************** -->
                                                                    @if (!empty($auditoria->cedulageneralrecomendacionesarchivo))
                                                                            @if (empty($auditoria->cedulageneralrecomendacionesarchivo->fase_autorizacion)||$auditoria->cedulageneralrecomendacionesarchivo->fase_autorizacion=='Rechazado')
                                                                                <span class="badge badge-light-danger">{{ $auditoria->cedulageneralrecomendacionesarchivo->fase_autorizacion }} </span>
                                                                                @can('agregarcedulainicial.edit')
                                                                                <a href="{{ route('agregarcedulainicial.edit',$auditoria->cedulageneralrecomendacionesarchivo)}}?tipo=Cédula General Recomendación" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                                    <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                                                </a>
                                                                                @endcan
                                                                            @endif
                                                                        @if((getSession('cp')!=2023) || (getSession('cp')!=2022) )
                                                                            @if ($auditoria->cedulageneralrecomendacionesarchivo->fase_autorizacion == 'En revisión')
                                                                                @can('cedulaanaliticadesempenorevision.edit')
                                                                                    <a href="{{ route('cedulaanaliticadesempenorevision.edit',$auditoria->cedulageneralrecomendacionesarchivo) }}"class=" btn btn-primary h6 text-hover-primary mb-5 float popupcomentario">
                                                                                        <li class="fa fa-gavel"></li>
                                                                                        Revisar
                                                                                    </a>
                                                                            @else
                                                                                    <span class="badge badge-light-warning">{{ $auditoria->cedulageneralrecomendacionesarchivo->fase_autorizacion }} </span>
                                                                                @endcan
                                                                            @endif
                                                                        @endif                                              
                                                                        @if ($auditoria->cedulageneralrecomendacionesarchivo->fase_autorizacion=='Autorizado')
                                                                            <span class="badge badge-light-success">{{ $auditoria->cedulageneralrecomendacionesarchivo->fase_autorizacion }} </span>
                                                                        @endif
                                                                    @endif   
                                                                    @if (!empty($auditoria->cedulageneralrecomendacionesarchivo)&&(empty($auditoria->cedulageneralrecomendacionesarchivo->fase_autorizacion)||$auditoria->cedulageneralrecomendacionesarchivo->fase_autorizacion=='Rechazado'))
                                                                        @if ((getSession('cp')==2022) || (getSession('cp')==2023) )
                                                                            @can('agregarcedulainicial.edit')
                                                                             <a href="{{ route('cedulasenvio.edit',$auditoria->cedulageneralrecomendacionesarchivo) }}" class="btn btn-primary">
                                                                              Enviar
                                                                             </a>
                                                                            @endcan                                                    
                                                                        @endif 
                                                                    @endif                                                                                                                          
                                                        </div>
                                                    </div>
                                                 </div>
                                             </div> 
                                        @endif 
                                         <!-- *************************************************************************************CG PRAS ************************************************************************************************** -->
                                        @if (count($auditoria->totalpras)>0)  
                                             @php
                                                $cg_prasresultado=$resultado['cg_pras'];
                                             @endphp 
                                                <div class="col-md-4">
                                                    <div class="card card-custom gutter-b bg-light-primary">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                                                <div class="d-flex flex-column mr-3 text-center">
                                                                    <a href="{{ route('cedulageneralpras.edit',$auditoria) }}" target="_blank" class="h6 text-gray-600 text-hover-primary mb-5">
                                                                        PRAS &nbsp;&nbsp;&nbsp;&nbsp; <span class="fa fa-eye"></span> 
                                                                    </a>  
                                                                </div>  
                                                                @if((getSession('cp')==2022) || (getSession('cp')==2023) )
                                                                    @if (empty($auditoria->cedulageneralprasarchivo->cedula_cargada) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                                                                        @can('agregarcedulainicial.create')
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <a href="{{ route('agregarcedulainicial.create')}}?tipo=Cédula General PRAS" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                                      Agregar Cédula <i class="bi bi-cloud-arrow-up-fill fs-1 "></i>
                                                                                    </a>             
                                                                                </div>
                                                                            </div>
                                                                        @endcan
                                                                    @endif
                                                                @endif                                                           
                                                                @if (!empty($auditoria->cedulageneralprasarchivo))                                      
                                                                    <td class="text-center">
                                                                        @if (!empty($auditoria->cedulageneralprasarchivo->cedula_cargada))
                                                                            <a href="{{ asset($auditoria->cedulageneralprasarchivo->cedula_cargada) }}" target="_blank">
                                                                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->cedulageneralprasarchivo->cedula_cargada)) ?>
                                                                            </a>
                                                                        @endif
                                                                    </td>
                                                                @endif                
                                                                 <!-- ********************************************************************************************************** Fasde de Autorización PRAS ******************************************************************************************************************************************* -->
                                                                @if (!empty($auditoria->cedulageneralprasarchivo))
                                                                    @if (empty($auditoria->cedulageneralprasarchivo->fase_autorizacion)||$auditoria->cedulageneralprasarchivo->fase_autorizacion=='Rechazado')
                                                                        <span class="badge badge-light-danger">{{ $auditoria->cedulageneralprasarchivo->fase_autorizacion }} </span>
                                                                        @can('agregarcedulainicial.edit')
                                                                        <a href="{{ route('agregarcedulainicial.edit',$auditoria->cedulageneralprasarchivo)}}?tipo=Cédula General PRAS" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                              <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                                        </a>
                                                                        @endcan
                                                                    @endif
                                                                    @if((getSession('cp')!=2023) || (getSession('cp')!=2022))
                                                                        @if ($auditoria->cedulageneralprasarchivo->fase_autorizacion == 'En revisión')
                                                                            @can('cedulaanaliticadesempenorevision.edit')
                                                                            <a href="{{ route('cedulaanaliticadesempenorevision.edit',$auditoria->cedulageneralprasarchivo) }}"class=" btn btn-primary h6 text-hover-primary mb-5 float popupcomentario">
                                                                            <li class="fa fa-gavel"></li>
                                                                            Revisar
                                                                            </a>
                                                                        @else
                                                                            <span class="badge badge-light-warning">{{ $auditoria->cedulageneralprasarchivo->fase_autorizacion }} </span>
                                                                            @endcan
                                                                        @endif
                                                                    @endif                                              
                                                                    @if ($auditoria->cedulageneralprasarchivo->fase_autorizacion=='Autorizado')
                                                                        <span class="badge badge-light-success">{{ $auditoria->cedulageneralprasarchivo->fase_autorizacion }} </span>
                                                                    @endif
                                                                @endif   
                                                                    @if (!empty($auditoria->cedulageneralprasarchivo)&&(empty($auditoria->cedulageneralprasarchivo->fase_autorizacion)||$auditoria->cedulageneralprasarchivo->fase_autorizacion=='Rechazado'))
                                                                        @if ((getSession('cp')==2022 ) || (getSession('cp')==2023))
                                                                            @can('agregarcedulainicial.edit')
                                                                                <a href="{{ route('cedulasenvio.edit',$auditoria->cedulageneralprasarchivo) }}" class="btn btn-primary">
                                                                                  Enviar
                                                                                 </a>
                                                                            @endcan                                                    
                                                                        @endif 
                                                                @endif                   
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        @endif   
                                    </div>
                                </div>  
                    </div>
                </div> 

             <!-- ************************************************ Cédulas Analíticas*************************************************************** -->                                                      
             
                <div class="row">  
                    @php
                        $ca_seguimiento=$resultado['ca_seguimiento'];
                    @endphp               
                    <div class="card card-bordered border-primary">  
                        <div class="ribbon ribbon-top">
                            <div class="ribbon-label bg-primary">Cédulas Analíticas</div>      
                        </div>                  
                         <div class="card-body "> 							
                            <div class="row">  
                             @if (count($auditoria->accionessolaclpo)>0)							
                                <div class="col-md-4">
                                    <div class="card card-custom gutter-b bg-light-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                                <div class="d-flex flex-column mr-3 text-center">
                                                    <a href="{{ route('cedulaanalitica.edit',$auditoria) }}" target="_blank" class="h6 text-gray-600 text-hover-primary mb-5">
                                                        Seguimiento  &nbsp;&nbsp;&nbsp;&nbsp; <span class="fa fa-eye"></span>
                                                    </a>
                                                </div>     
                                                 @if((getSession('cp')==2022) || (getSession('cp')==2023) )        
                                                    @if (empty($auditoria->cedulaanaliticaarchivo->cedula_cargada) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                                                        @can('agregarcedulainicial.create')
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <a href="{{ route('agregarcedulainicial.create')}}?tipo=Cédula Analítica" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                         Agregar Cédula <i class="bi bi-cloud-arrow-up-fill fs-1 "></i>
                                                                    </a>             
                                                                </div>
                                                             </div>
                                                        @endcan
                                                     @endif
                                                 @endif   
                                                 {{-- {{ dd($auditoria->cedulaanaliticaarchivo) }} --}}
                                                 @if (!empty($auditoria->cedulaanaliticaarchivo))                                      
                                                     <td class="text-center">                                                        
                                                        @if (!empty($auditoria->cedulaanaliticaarchivo->cedula_cargada))
                                                            <a href="{{ asset($auditoria->cedulaanaliticaarchivo->cedula_cargada) }}" target="_blank">
                                                               <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->cedulaanaliticaarchivo->cedula_cargada)) ?>
                                                            </a>
                                                        @endif
                                                     </td>
                                                     
                                                 @endif                
                                                 <!-- ********************************************************************************************************** Fasde de Autorización Seguimiento ******************************************************************************************************************************************* -->
                                                    @if (!empty($auditoria->cedulaanaliticaarchivo))
                                                    {{-- {{ dd($auditoria->cedulaanaliticaarchivo) }} --}}
                                                        @if (empty($auditoria->cedulaanaliticaarchivo->fase_autorizacion)||$auditoria->cedulaanaliticaarchivo->fase_autorizacion=='Rechazado')
                                                            <span class="badge badge-light-danger">{{$auditoria->cedulaanaliticaarchivo->fase_autorizacion }} </span>
                                                                @can('agregarcedulainicial.edit')
                                                                    <a href="{{ route('agregarcedulainicial.edit',$auditoria->cedulaanaliticaarchivo)}}?tipo=Cédula Analítica" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                    <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                                    </a>
                                                                @endcan
                                                        @endif
                                                     @endif    
                                                 @if((getSession('cp')!=2023) || (getSession('cp')!=2022))
                                                         @if (!empty($auditoria->cedulaanaliticaarchivo) && ($auditoria->cedulaanaliticaarchivo->fase_autorizacion == 'En revisión'))
                                                            @can('cedulaanaliticadesempenorevision.edit')
                                                                <a href="{{ route('cedulaanaliticadesempenorevision.edit',$auditoria->cedulaanaliticaarchivo) }}"class="btn btn-primary h6 text-hover-primary mb-5 float popupcomentario">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Revisar
                                                                </a>
                                                         @else
                                                            <span class="badge badge-light-warning">{{ $auditoria->cedulaanaliticaarchivo->fase_autorizacion }} </span>
                                                            @endcan
                                                          @endif
                                                 @endif 
                                                @if (!empty($auditoria->cedulaanaliticaarchivo) && ( $auditoria->cedulaanaliticaarchivo->fase_autorizacion=='Autorizado'))
                                                    <span class="badge badge-light-success">{{ $auditoria->cedulaanaliticaarchivo->fase_autorizacion }} </span>
                                                @endif                                               
                                                @if (!empty($auditoria->cedulaanaliticaarchivo)&&(empty($auditoria->cedulaanaliticaarchivo->fase_autorizacion)||$auditoria->cedulaanaliticaarchivo->fase_autorizacion=='Rechazado'))
                                                    @if ((getSession('cp')==2022) || (getSession('cp')==2023))
                                                        @can('agregarcedulainicial.edit')
                                                            <a href="{{ route('cedulasenvio.edit',$auditoria->cedulaanaliticaarchivo) }}" class="btn btn-primary">
                                                                Enviar
                                                            </a>
                                                        @endcan                                                    
                                                     @endif  
                                                @endif                                                                                                                                                                                                                                       
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             @endif                                      
                                   <!--------------------------------------Cedula Análitica Desempeño MOD -------------------------------------------------------------------------------------------------------------------------------------> 
                                @if (count($auditoria->totalrecomendacion)>0 && (str_contains($auditoria->acto_fiscalizacion, 'Desempeño')||str_contains($auditoria->acto_fiscalizacion, 'Legalidad')||str_contains($auditoria->acto_fiscalizacion, 'Inversión Física')||str_contains($auditoria->acto_fiscalizacion, 'Cumplimiento Financiero')))                                        @php
                                            $ca_desempenio=$resultado['ca_desempeno'];
                                        @endphp
                                           <div class="col-md-4">
                                             <div class="card card-custom gutter-b bg-light-primary">
                                                <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-between p-4 flex-lg-wrap flex-xl-nowrap">
                                                            <div class="d-flex flex-column mr-3 text-center">
                                                                <a href="{{ route('cedulaanaliticadesemp.edit',$auditoria) }}" target="_blank" class="h6 text-gray-600 text-hover-primary mb-5">
                                                                    Desempeño &nbsp;&nbsp;&nbsp;&nbsp; <span class="fa fa-eye"></span>
                                                                </a>
                                                            </div>    
                                                             @if((getSession('cp')==2022)|| (getSession('cp')==2023))        
                                                                @if (empty($auditoria->cedulaanaliticadesemparchivo->cedula_cargada) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                                                                    @can('agregarcedulainicial.create')
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <a href="{{ route('agregarcedulainicial.create')}}?tipo=Cédula Analítica Desempeño" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                                    Agregar Cédula <i class="bi bi-cloud-arrow-up-fill fs-1 "></i>
                                                                                </a>             
                                                                            </div>
                                                                        </div>
                                                                    @endcan
                                                                @endif
                                                            @endif                                                               
                                                        @if (!empty($auditoria->cedulaanaliticadesemparchivo->cedula_cargada))                                      
                                                                    <td class="text-center">
                                                                        @if (!empty($auditoria->cedulaanaliticadesemparchivo->cedula_cargada))
                                                                            <a href="{{ asset($auditoria->cedulaanaliticadesemparchivo->cedula_cargada) }}" target="_blank">
                                                                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->cedulaanaliticadesemparchivo->cedula_cargada)) ?>
                                                                            </a>
                                                                        @endif
                                                                    </td>
                                                        @endif                
                                                        <!-- ********************************************************************************************************** Fasde de Autorización PRAS ******************************************************************************************************************************************* -->
                                                                 @if (!empty($auditoria->cedulaanaliticadesemparchivo))
                                                                    @if (empty($auditoria->cedulaanaliticadesemparchivo->fase_autorizacion)||$auditoria->cedulaanaliticadesemparchivo->fase_autorizacion=='Rechazado')
                                                                        <span class="badge badge-light-danger">{{ $auditoria->cedulaanaliticadesemparchivo->fase_autorizacion }} </span>
                                                                        @can('agregarcedulainicial.edit')
                                                                        <a href="{{ route('agregarcedulainicial.edit',$auditoria->cedulaanaliticadesemparchivo)}}?tipo=Cédula Analítica Desempeño" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                              <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                                        </a>
                                                                        @endcan
                                                                    @endif
                                                                    @if((getSession('cp')!=2023) || (getSession('cp')!=2022))
                                                                        @if ($auditoria->cedulaanaliticadesemparchivo->fase_autorizacion == 'En revisión')
                                                                            @can('cedulaanaliticadesempenorevision.edit')
                                                                            <a href="{{ route('cedulaanaliticadesempenorevision.edit',$auditoria->cedulaanaliticadesemparchivo ) }} "class=" btn btn-primary h6 text-hover-primary mb-5 float popupcomentario" >
                                                                            <li class="fa fa-gavel"></li>
                                                                            Revisar
                                                                            </a>
                                                                        @else
                                                                            <span class="badge badge-light-warning">{{ $auditoria->cedulaanaliticadesemparchivo->fase_autorizacion }} </span>
                                                                            @endcan
                                                                        @endif
                                                                    @endif                                              
                                                                    @if ($auditoria->cedulaanaliticadesemparchivo->fase_autorizacion=='Autorizado')
                                                                        <span class="badge badge-light-success">{{ $auditoria->cedulaanaliticadesemparchivo->fase_autorizacion }} </span>
                                                                    @endif
                                                                @endif   
                                                                    @if (!empty($auditoria->cedulaanaliticadesemparchivo)&&(empty($auditoria->cedulaanaliticadesemparchivo->fase_autorizacion)||$auditoria->cedulaanaliticadesemparchivo->fase_autorizacion=='Rechazado'))
                                                                        @if ((getSession('cp')==2022 )|| (getSession('cp')==2023))
                                                                            @can('agregarcedulainicial.edit')
                                                                                <a href="{{ route('cedulasenvio.edit',$auditoria->cedulaanaliticadesemparchivo) }}" class="btn btn-primary">
                                                                                  Enviar
                                                                                 </a>
                                                                            @endcan                                                    
                                                                        @endif 
                                                                @endif                                                                                                      
                                                    </div>
                                                </div>    
                                             </div> 
                                        </div>
                                @endif   
                            </div>
                        </div> 
                     </div>
                </div> 
            </div> 
        </div>
    </div>
</div>


@endsection
@section('script')
<script>
     $(document).ready(function() {            
            $('.popuprevisar').colorbox({     
                width:"65%",
                height:"650px",
                maxWidth:400,
                maxHeight:"650px",               
                iframe: true,                
                onClosed: function() {
                    location.reload(true);                    
                },
                onComplete: function () {
                 $(this).colorbox.resize({width:"65%",maxWidth:400, height:"650px", maxHeight:"650px"});
                 $(window).trigger("resize");                
                }
            });
        });
        $(document).ready(function() {
            $('.popupcomentario').colorbox({
                width:"65%",
                height:"650px",
                maxWidth:400,
                maxHeight:"650px",
                iframe: true,
                onClosed: function() {
                    location.reload(true);
                },
                onComplete: function () {
                 $(this).colorbox.resize({width:"65%",maxWidth:400, height:"650px", maxHeight:"650px"});
                 $(window).trigger("resize");
                }
            });
        });
</script>   
@endsection