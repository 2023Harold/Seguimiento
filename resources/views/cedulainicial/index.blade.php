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
                                                        <div class="d-flex flex-column mr-5 text-center">
                                                            <a href="{{ route('cedulainicialprimera.edit',$auditoria) }}" target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 align-items-sm-center">
                                                                Seguimiento &nbsp;&nbsp;&nbsp;&nbsp;
                                                                <span class="fa fa-eye"></span>
                                                            </a>                               
                                                            @if((getSession('cp')==2022) )        
                                                                @if (empty($auditoria->cedulaanaliticadesemparchivo->cedula_cargada) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                                                                    @can('agregarcedulainicial.edit')
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <a href="{{ route('agregarcedulainicial.edit',$auditoria)}}?tipo=Cedula Analítica Desempeño" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
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
                    <!-- ********************************************************************************************************** Fasde de Autorización Seguimiento ******************************************************************************************************************************************* -->
                                                            @if (!empty($auditoria->cedulaanaliticadesemparchivo))
                                                                @if (empty($auditoria->cedulaanaliticadesemparchivo->fase_autorizacion)||$auditoria->cedulaanaliticadesemparchivo->fase_autorizacion=='Rechazado')
                                                                    <span class="badge badge-light-danger">{{ $auditoria->cedulaanaliticadesemparchivo->fase_autorizacion }} </span>
                                                                    @can('agregarcedulainicial.edit')
                                                                        <a href="{{ route('agregarcedulainicial.edit',$auditoria)}}?tipo=Cedula Analítica Desempeño" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                            <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                                        </a>
                                                                    @endcan
                                                                @endif
                                                            
                                                                @if(getSession('cp')!=2023)
                                                                    @if ($auditoria->cedulaanaliticadesemparchivo->fase_autorizacion == 'En revisión')
                                                                        @can('cedulaanaliticadesempenorevision.edit')
                                                                            <a href="{{ route('cedulaanaliticadesempenorevision.edit',$auditoria->cedulaanaliticadesemparchivo) }}"class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario">
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
                                                            @if (!empty($auditoria->cedulaanaliticadesemparchivo)||(optional($auditoria->cedulaanaliticadesemparchivo)->fase_autorizacion=='Rechazado'))
                                                                @if (getSession('cp')==2022 )
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
                                            
                                                            @if((getSession('cp')==2022) )        
                                                                @if (empty($auditoria->cedulageneralrecomendacionesarchivo->cedula_cargada) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                                                                    @can('agregarcedulainicial.edit')
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                            <a href="{{ route('agregarcedulainicial.edit',$auditoria)}}?tipo=Cedula General Recomendación" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                                Agregar Cédula <i class="bi bi-cloud-arrow-up-fill fs-1 "></i>
                                                                            </a>             
                                                                            </div>
                                                                        </div>
                                                                    @endcan
                                                                @endif
                                                            @endif   
                                                            @if (!empty($auditoria->cedulageneralrecomendacionesarchivo->cedula_cargada))                                      
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
                                                                    <a href="{{ route('agregarcedulainicial.edit',$auditoria)}}?tipo=Cedula General Recomendación" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                    <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                                    </a>
                                                                    @endcan
                                                                @endif
                                                                @if(getSession('cp')!=2023)
                                                                    @if ($auditoria->cedulageneralrecomendacionesarchivo->fase_autorizacion == 'En revisión')
                                                                        @can('cedulaanaliticadesempenorevision.edit')
                                                                            <a href="{{ route('cedulaanaliticadesempenorevision.edit',$auditoria->cedulageneralrecomendacionesarchivo) }}"class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario">
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
                                                                @if (getSession('cp')==2022 )
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
                                                            @if((getSession('cp')==2022) )
                                                                @if (empty($auditoria->cedulageneralprasarchivo->cedula_cargada) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                                                                    @can('agregarcedulainicial.edit')
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <a href="{{ route('agregarcedulainicial.edit',$auditoria)}}?tipo=Cédula General PRAS" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                                    Agregar Cédula <i class="bi bi-cloud-arrow-up-fill fs-1 "></i>
                                                                                </a>             
                                                                            </div>
                                                                        </div>
                                                                    @endcan
                                                                @endif
                                                            @endif   
                                 
                                                            @if (!empty($auditoria->cedulageneralprasarchivo->cedula_cargada))                                      
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
                                                                        <a href="{{ route('agregarcedulainicial.edit',$auditoria)}}?tipo=Cédula General PRAS" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                            <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                                        </a>
                                                                    @endcan
                                                                @endif
                                                                @if(getSession('cp')!=2023)
                                                                    @if ($auditoria->cedulageneralprasarchivo->fase_autorizacion == 'En revisión')
                                                                        @can('cedulaanaliticadesempenorevision.edit')
                                                                            <a href="{{ route('cedulaanaliticadesempenorevision.edit',$auditoria->cedulageneralprasarchivo) }}"class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario">
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
                                                                @if (getSession('cp')==2022 )
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
                                        </div>
                                    @endif                                   
                                </div>
                            </div>
                        </div> 
                    </div> 
                </div> 
                @php
                    $ca_seguimiento=$resultado['ca_seguimiento'];
                @endphp 
                <div class="row">                
                    <div class="card card-bordered border-primary">  
                        <div class="ribbon ribbon-top">
                            <div class="ribbon-label bg-primary">Cédulas Analiticas</div>      
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
                                                @if((getSession('cp')==2022) )        
                                                    @if (empty($auditoria->cedulaanaliticaarchivo->cedula_cargada) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                                                        @can('agregarcedulainicial.edit')
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                <a href="{{ route('agregarcedulainicial.edit',$auditoria)}}?tipo=Cedula Analítica" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                    Agregar Cédula <i class="bi bi-cloud-arrow-up-fill fs-1 "></i>
                                                                </a>             
                                                                </div>
                                                            </div>
                                                        @endcan
                                                    @endif
                                                @endif   
                                                @if (!empty($auditoria->cedulaanaliticaarchivo->cedula_cargada))                                      
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
                                                    @if (empty($auditoria->cedulaanaliticaarchivo->fase_autorizacion)||$auditoria->cedulaanaliticaarchivo->fase_autorizacion=='Rechazado')
                                                        <span class="badge badge-light-danger">{{ $auditoria->cedulaanaliticaarchivo->fase_autorizacion }} </span>
                                                        @can('agregarcedulainicial.edit')
                                                            <a href="{{ route('agregarcedulainicial.edit',$auditoria)}}?tipo=Cedula Analítica" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                            </a>
                                                        @endcan
                                                    @endif
                                                    @if(getSession('cp')!=2023)
                                                        @if ($auditoria->cedulaanaliticaarchivo->fase_autorizacion == 'En revisión')
                                                            @can('cedulaanaliticadesempenorevision.edit')
                                                                <a href="{{ route('cedulaanaliticadesempenorevision.edit',$auditoria->cedulaanaliticaarchivo) }}"class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Revisar
                                                                </a>
                                                            @else
                                                                <span class="badge badge-light-warning">{{ $auditoria->cedulaanaliticaarchivo->fase_autorizacion }} </span>
                                                            @endcan
                                                        @endif
                                                    @endif                                              
                                                    @if ($auditoria->cedulaanaliticaarchivo->fase_autorizacion=='Autorizado')
                                                        <span class="badge badge-light-success">{{ $auditoria->cedulaanaliticaarchivo->fase_autorizacion }} </span>
                                                    @endif
                                                @endif   
                                                @if (!empty($auditoria->cedulaanaliticaarchivo)&&(empty($auditoria->cedulaanaliticaarchivo->fase_autorizacion)||$auditoria->cedulaanaliticaarchivo->fase_autorizacion=='Rechazado'))
                                                    @if (getSession('cp')==2022 )
                                                        @can('agregarcedulainicial.edit')
                                                            <a href="{{ route('cedulasenvio.edit',$auditoria->cedulaanaliticaarchivo) }}" class="btn btn-primary">
                                                                Enviar
                                                            </a>
                                                        @endcan                                                    
                                                    @endif 
                                                @endif
{{-- 

                                                    @if((getSession('cp')==2022) )
                                                     @if($auditoria->cedulaanaliticaarchivo)
                                                            @can ('agregarcedulainicial.show')
                                                                <a href="{{ route('agregarcedulainicial.show', $auditoria->cedulaanaliticaarchivo) }} " >
                                                                @btnFile($auditoria->cedulaanaliticaarchivo->cedula_cargada)                                             
                                                                </a>                                                                                                            
                                                            @endcan
                                                    @elseif (empty($auditoria->cedulaanaliticaarchivo->fase_autorizacion)||$auditoria->cedulaanaliticaarchivo->fase_autorizacion=='Rechazado')                                                    
                                                        @can('agregarcedulainicial.edit')
                                                            <a href="{{ route('agregarcedulainicial.edit',$auditoria)}}?tipo=Cedula Analítica" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario"> 
                                                                Agregar Cédula <i class="bi bi-cloud-arrow-up-fill fs-1 "></i>
                                                            </a>                                                      
                                                        @endcan 
                                                    @else
                                                        <span class="badge badge-light-danger">{{ $auditoria->cedulaanaliticaarchivo->fase_autorizacion }} </span>                                                       
                                                    @endif
                                                @endif
                                                <td class="text-center">
 --}}

                                                  {{-- @if($auditoria->cedulaanaliticadesemparchivo->fase_autorizacion == 'En revisión')
                                                        @can('cedulasrevision.edit')
                                                            <a href="{{ route('cedulasrevision.edit',$auditoria->cedulaanaliticadesemparchivo) }}" class="btn btn-primary">
                                                                <li class="fa fa-gavel"></li>
                                                                Revisar
                                                            </a>
                                                        @else
                                                            <span class="badge badge-light-warning">{{ $auditoria->cedulaanaliticadesemparchivo->fase_autorizacion }} </span>
                                                        @endcan
                                                    @endif
                                                    @if (empty($auditoria->cedulaanaliticadesemparchivo->fase_autorizacion) ||$auditoria->cedulaanaliticadesemparchivo->fase_autorizacion=='Rechazado')
                                                            @if (getSession('cp')==2022 )
                                                                @can('agregarcedulainicial.edit')
                                                                    <a href="{{ route('cedulasenvio.edit',$auditoria->cedulaanaliticadesemparchivo) }}" class="btn btn-primary">
                                                                    Enviar
                                                                    </a>
                                                                @endcan
                                                             @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-danger">{{str_contains($auditoria->cedulaanaliticadesemparchivo->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanaliticadesemparchivo->fase_autorizacion }}</span>
                                                                </p>                                                              
                                                            @endif
                                                    @endif --}}
                                                </td>

                                                        <!-- --------------------------------------------------------------------------- CA Seguimiento Rechazado----------------------------------------------------------------------------------------------------- -->
                                                        
                                                        <!-- --------------------------------------------------------------------------- CA Seguimiento Autorizar----------------------------------------------------------------------------------------------------- -->
                                                        {{-- @if ($auditoria->cedulaanalitica[0]->fase_autorizacion == 'En autorización')
                                                            @can('cedulaanaliticaautorizacion.edit')
                                                                <a href="{{ route('cedulaanaliticaautorizacion.edit',$auditoria->cedulaanalitica[0]) }}" class="btn btn-primary popuprevisar float-end">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Autorizar
                                                                </a><br><br><br>
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulaanalitica[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanalitica[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif --}}

                                                        <!-- --------------------------------------------------------------------------- CA Seguimiento Autorizado----------------------------------------------------------------------------------------------------- -->
                                                        {{-- @if ($auditoria->cedulaanalitica[0]->fase_autorizacion=='Autorizado')
                                                            <p class="text-gray-600 h4">
                                                                Fase: <span class="badge badge-success">{{str_contains($auditoria->cedulaanalitica[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanalitica[0]->fase_autorizacion }}</span>
                                                            </p>
                                                        @endif
                                                    @else 

                                                        <!-- --------------------------------------------------------------------------- CG Seguimiento Enviar a revision---------------------------------------------------------------------------------- -->
                                                        {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulaanalitica.update','id'=>'form']) !!}            
                                                        <div class="row">
                                                            <div class="col-md-12" >
                                                                @if (auth()->user()->can('cedulaanalitica.update'))
                                                                    <button type="submit" name="enviar" class="btn btn-primary btn btn-primary">Enviar a revisión</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        {!! BootForm::close() !!}
                                                    @endif                                                
                                                @endif
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
								@endif
                                {{-- @if (count($auditoria->totalrecomendacion)>0 && (str_contains($auditoria->acto_fiscalizacion, 'Desempeño')||str_contains($auditoria->acto_fiscalizacion, 'Legalidad')))
                                @php
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
                                                    @if(getSession('cp')==2022)
                                                         <a href="{{ route('agregarcedulainicial.edit',$auditoria)}}?tipo=Cedula Analítica" " target="_blank" class="h6 text-gray-600 text-hover-primary mb-5 float popupcomentario">    
                                                            Agregar Cedula <i class="bi bi-cloud-arrow-up-fill fs-1"></i>
                                                        </a>
                                                    @endif                                               
                                                </div>
                                                @if (count($auditoria->totalrecomendacion)==$totalrecomendacionesautorizadas)
                                                    @if(count($auditoria->cedulaanaliticadesemp)>0) 
                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Rechazado ------------------------------------------------------------------------------------ -->
                                                        @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'Rechazado')
                                                            @can('cedulaanaliticadesemp.update')            
                                                                {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulaanaliticadesemp.update','id'=>'form']) !!}            
                                                                <div class="row">
                                                                    <div class="col-md-12">                            
                                                                        @if (auth()->user()->can('cedulaanaliticadesemp.update'))
                                                                            <button type="submit" name="enviar" class="btn btn-primary float-end">Enviar a revisión</button>
                                                                        @endif 
                                                                    </div>
                                                                </div>
                                                                {!! BootForm::close() !!}
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-danger">{{str_contains($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanaliticadesemp[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif 

                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Enviar a Revision o Aprobar ------------------------------------------------------------------------------------ -->
                                                        @if(count($ca_desempenio['analistasF'])>0 && count($ca_desempenio['analistasL'])== 0 && empty($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion))                           
                                                            {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulaanaliticadesemp.update','id'=>'form']) !!}            
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    @if (auth()->user()->can('cedulaanaliticadesemp.update'))
                                                                        <button type="submit" name="enviar" class="btn btn-primary float-end">Enviar a revisión</button>
                                                                    @endif 
                                                                </div>
                                                            </div>
                                                            {!! BootForm::close() !!}
                                                        @elseif(in_array(auth()->user()->id, $ca_desempenio['analistasF']) && count($ca_desempenio['analistasL'])>0)                    
                                                            <a href="{{ route('cedanadesempanalista.edit',$auditoria->cedulaanaliticadesemp[0]) }}" class="btn btn-primary popuprevisar float-end">
                                                                <li class="fa fa-gavel"></li>
                                                                Aprobar
                                                            </a>
                                                        @endif       --}}

                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Revisar 01---------------------------------------------------------------------------------- -->
                                                        {{-- @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En revisión 01')          
                                                            @can('cedanadesemprevision01.edit') 
                                                                @if(in_array(auth()->user()->id, $ca_desempenio['lideresF']))
                                                                    <a href="{{ route('cedanadesemprevision01.edit',$auditoria->cedulaanaliticadesemp[0]) }}" class="btn btn-primary popuprevisar">
                                                                        <li class="fa fa-gavel"></li>
                                                                        Revisar
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanaliticadesemp[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif --}}

                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Revisar ------------------------------------------------------------------------------------ -->
                                                        {{-- @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En revisión')     
                                                            @can('cedanadesemprevision.edit')
                                                                @if(in_array(auth()->user()->unidad_administrativa_id, $ca_desempenio['jefesF']))
                                                                    <a href="{{ route('cedanadesemprevision.edit',$auditoria->cedulaanaliticadesemp[0]) }}" class="btn btn-primary popuprevisar">
                                                                        <li class="fa fa-gavel"></li>
                                                                        Revisar
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanaliticadesemp[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif --}}

                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Validar ------------------------------------------------------------------------------------ -->
                                                        {{-- @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En validación')
                                                            @can('cedanadesempvalidacion.edit')
                                                                <a href="{{ route('cedanadesempvalidacion.edit',$auditoria->cedulaanaliticadesemp[0]) }}" class="btn btn-primary popuprevisar">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Validar
                                                                </a>
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanaliticadesemp[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif --}}

                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Autorizar ------------------------------------------------------------------------------------ -->
                                                        {{-- @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En autorización')
                                                            @can('cedanadesempautorizacion.edit')
                                                                <a href="{{ route('cedanadesempautorizacion.edit',$auditoria->cedulaanaliticadesemp[0]) }}" class="btn btn-primary popuprevisar">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Autorizar
                                                                </a>
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanaliticadesemp[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif

                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Autorizado ------------------------------------------------------------------------------------ -->
                                                        @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion=='Autorizado')
                                                            <p class="text-gray-600 h4">
                                                                Fase: <span class="badge badge-success">{{str_contains($auditoria->cedulaanalitica[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanalitica[0]->fase_autorizacion }}</span>
                                                            </p>
                                                        @endif

                                                    @else

                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Enviar a revision---------------------------------------------------------------------------------- -->   
                                                        {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulaanaliticadesemp.update','id'=>'form']) !!}        
                                                        <div class="row">
                                                            <div class="col-md-12" >
                                                                @if (auth()->user()->can('cedulaanaliticadesemp.update'))
                                                                    <button type="submit" name="enviar" class="btn btn-primary btn btn-primary">Enviar a revisión</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        {!! BootForm::close() !!}                                                    
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
                </div>  --}}

                {{-- <div class="row">
                    @can('cedulainicialprimera.edit')
                    <div class="col-md-4  mt-2">
                        <a href="{{ route('cedulainicialprimera.edit',$auditoria) }}" rel="noopener noreferrer">
                            <div class="card">                           
                                <div class="card-body overflow-auto h-50px btn btn-secondary">
                                    <div class="d-flex flex-column"> Cédula General de Seguimiento
                                    </div>
                                </div>
                            </div>
                        </a>                        
                    </div> 
                    @endcan    
                    @if (count($auditoria->totalrecomendacion)>0)
                    @can('cedulageneralrecomendacion.edit')
                    <div class="col-md-4  mt-2">
                        <a href="{{ route('cedulageneralrecomendacion.edit',$auditoria) }}" rel="noopener noreferrer">
                            <div class="card">                           
                                <div class="card-body overflow-auto h-50px btn btn-secondary">
                                    <div class="d-flex flex-column">Cédula General Recomendaciones
                                    </div>
                                </div>
                            </div>
                        </a>                        
                    </div>
                    @endcan 
                    @endif    
                    @if (count($auditoria->totalpras)>0)   
                    @can('cedulageneralpras.edit')                 
                    <div class="col-md-4  mt-2">
                        <a href="{{ route('cedulageneralpras.edit',$auditoria) }}" rel="noopener noreferrer">
                            <div class="card">                           
                                <div class="card-body overflow-auto h-50px btn btn-secondary">
                                    <div class="d-flex flex-column">Cédula General PRAS
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>  
                    @endcan 
                    @endif
                    @if($auditoria->acto_fiscalizacion!='Desempeño')
                        @can('cedulaanalitica.edit')              
                        <div class="col-md-4  mt-2">
                            <a href="{{ route('cedulaanalitica.edit',$auditoria) }}" rel="noopener noreferrer">
                                <div class="card">                           
                                    <div class="card-body overflow-auto h-50px btn btn-secondary">
                                        <div class="d-flex flex-column">Cédula Analítica
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div> 
                        @endcan
                    @endif                      
                    @if (count($auditoria->totalrecomendacion)>0 &&(str_contains($auditoria->acto_fiscalizacion, 'Desempeño')||str_contains($auditoria->acto_fiscalizacion, 'Legalidad')))
                    @can('cedulaanaliticadesemp.edit')
                    <div class="col-md-4 mt-2">
                        <a href="{{ route('cedulaanaliticadesemp.edit',$auditoria) }}" rel="noopener noreferrer">
                            <div class="card">                           
                                <div class="card-body overflow-auto h-50px btn btn-secondary">
                                    <div class="d-flex flex-column">Cédula Analitica Desempeño
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>  
                    @endcan 
                    @endif 
                </div>                 --}}
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