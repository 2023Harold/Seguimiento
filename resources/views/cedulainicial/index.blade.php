@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('cedulainicial.index',$auditoria) }}
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
                            <div class="row">  
                                <!-- ********************************************************************************************************** CG Seguimiento ******************************************************************************************************************************************* -->
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
                                                </div>
                                                <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0 text-center">
												
													
                                                    @if ($totaut==$totalacciones)
                                                        @if(count($auditoria->cedulageneralseguimiento)>0)

                                                            <!-- ---------------------------------------------------------------------------CG Seguimiento Rechazado ------------------------------------------------------------ -->
                                                            @if ($auditoria->cedulageneralseguimiento[0]->fase_autorizacion == 'Rechazado')
                                                                @can('cedulainicialprimera.update')  
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-danger">{{str_contains($auditoria->cedulageneralseguimiento[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralseguimiento[0]->fase_autorizacion }}</span>
                                                                </p>              
                                                                    {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulainicialprimera.update','id'=>'form']) !!}            
                                                                        <div class="row">
                                                                            <div class="col-md-12">                                                
                                                                                @if (auth()->user()->can('cedulainicialprimera.store') || auth()->user()->can('cedulainicialprimera.update'))
                                                                                <button type="submit" name="enviar" class="btn btn-primary float-end">Enviar a revisión</button>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    {!! BootForm::close() !!}
                                                                                                                                
                                                                @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-danger">{{str_contains($auditoria->cedulageneralseguimiento[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralseguimiento[0]->fase_autorizacion }}</span>
                                                                </p>
                                                                @endcan                                                           
                                                            @endif                                                 
                                                        
                                                            <!-- ---------------------------------------------------------------------------CG Seguimiento Enviar a Revision o Aprobar------------------------------------------------- -->
                                                            @if(count($cg_resultado['analistasF'])>0 && count($cg_resultado['analistasL'])== 0 && empty($auditoria->cedulageneralseguimiento[0]->fase_autorizacion))
                                                                {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulainicialprimera.update','id'=>'form']) !!}            
                                                                <div class="row">
                                                                    <div class="col-md-12">                         
                                                                        {!! BootForm::hidden('cedula2',$nombre)!!}                            
                                                                        @if (auth()->user()->can('cedulainicialprimera.update'))
                                                                        <button type="submit" name="enviar" class="btn btn-primary float-end">Enviar a revisión</button>
                                                                        @endif 
                                                                    </div>
                                                                </div>
                                                                {!! BootForm::close() !!}
                                                            @elseif(in_array(auth()->user()->id, $cg_resultado['analistasF']) && count($cg_resultado['analistasL'])>0)
                                                                <a href="{{ route('cedulainicialprimeraanalista.edit',$auditoria->cedulageneralseguimiento[0]) }}" class="btn btn-primary popuprevisar float-end">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Aprobar
                                                                </a>                                                    
                                                            @endif

                                                            <!-- ---------------------------------------------------------------------------CG Seguimiento Revision 01-------------------------------------------------------------------- -->
                                                            @if ($auditoria->cedulageneralseguimiento[0]->fase_autorizacion == 'En revisión 01')
                                                                @can('cedulainicialprimerarevision01.edit')
                                                                    @if(in_array(auth()->user()->id, $cg_resultado['lideresF']))
                                                                        <a href="{{ route('cedulainicialprimerarevision01.edit',$auditoria->cedulageneralseguimiento[0]) }}" class="btn btn-primary popuprevisar ">
                                                                            <li class="fa fa-gavel"></li>
                                                                            Revisar
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    <p class="text-gray-600 h4">
                                                                        Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralseguimiento[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralseguimiento[0]->fase_autorizacion }}</span>
                                                                    </p>
                                                                @endcan
                                                            @endif

                                                            <!-- ---------------------------------------------------------------------------CG Seguimiento Revision------------------------------------------------------------------------- -->
                                                            @if ($auditoria->cedulageneralseguimiento[0]->fase_autorizacion == 'En revisión')
                                                                @can('cedulainicialprimerarevision.edit')
                                                                    @if(in_array(auth()->user()->unidad_administrativa_id, $cg_resultado['jefesF']))
                                                                        <a href="{{ route('cedulainicialprimerarevision.edit',$auditoria->cedulageneralseguimiento[0]) }}" class="btn btn-primary popuprevisar">
                                                                            <li class="fa fa-gavel"></li>
                                                                            Revisar
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    <p class="text-gray-600 h4">
                                                                        Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralseguimiento[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralseguimiento[0]->fase_autorizacion }}</span>
                                                                    </p>
                                                                @endcan
                                                            @endif

                                                            <!-- ---------------------------------------------------------------------------CG Seguimiento Validar--------------------------------------------------------------------------- -->
                                                            @if ($auditoria->cedulageneralseguimiento[0]->fase_autorizacion == 'En validación')
                                                                @can('cedulainicialprimeravalidacion.edit')
                                                                    <a href="{{ route('cedulainicialprimeravalidacion.edit',$auditoria->cedulageneralseguimiento[0]) }}" class="btn btn-primary popuprevisar">
                                                                        <li class="fa fa-gavel"></li>
                                                                        Validar
                                                                    </a>
                                                                @else
                                                                    <p class="text-gray-600 h4">
                                                                        Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralseguimiento[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralseguimiento[0]->fase_autorizacion }}</span>
                                                                    </p>
                                                                @endcan                        
                                                            @endif    
                                                            
                                                            <!-- ---------------------------------------------------------------------------CG Seguimiento Autorizar-------------------------------------------------------------------------- -->
                                                            @if ($auditoria->cedulageneralseguimiento[0]->fase_autorizacion == 'En autorización')
                                                                @can('cedulainicialprimeraautorizacion.edit')
                                                                    <a href="{{ route('cedulainicialprimeraautorizacion.edit',$auditoria->cedulageneralseguimiento[0]) }}" class="btn btn-primary popuprevisar">
                                                                        <li class="fa fa-gavel"></li>
                                                                        Autorizar
                                                                    </a>                                                             
                                                                @else
                                                                    <p class="text-gray-600 h4">
                                                                        Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralseguimiento[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralseguimiento[0]->fase_autorizacion }}</span>
                                                                    </p>
                                                                @endcan
                                                            @endif
                                                            
                                                            <!-- ---------------------------------------------------------------------------CG Seguimiento Autorizado------------------------------------------------------------------------- -->
                                                            @if ($auditoria->cedulageneralseguimiento[0]->fase_autorizacion=='Autorizado')
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-success">{{str_contains($auditoria->cedulageneralseguimiento[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralseguimiento[0]->fase_autorizacion }}</span>
                                                                </p>
                                                                {{-- <a href="#" class="btn btn-outline-primary"><span class="fa fa-list"></span> Movimientos</a> --}}
                                                            @endif                                                         
                                                        @else  

                                                        <!-- ---------------------------------------------------------------------------CG Seguimiento Enviar a revision------------------------------------------------------------------------ -->
                                                        {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulainicialprimera.update','id'=>'form']) !!}            
                                                            <div class="row">
                                                                <div class="col-md-12">                                                                                    
                                                                    @if (auth()->user()->can('cedulainicialprimera.update'))
                                                                        <button type="submit" name="enviar" class="btn font-weight-bolder btn-primary py-4 px-6">Enviar a revisión</button>
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
                                                <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0 text-center">
                                                                                                      
                                                    @if (count($auditoria->totalrecomendacion)==$totalrecomendacionesautorizadas)
                                                        @if(count($auditoria->cedulageneralrecomendaciones)>0) 

                                                        <!-- --------------------------------------------------------------------------- CG Recomendación Rechazado----------------------------------------------------------------------------------------------------- -->
                                                            @if ($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion == 'Rechazado')
                                                                @can('cedulageneralrecomendacion.update')   
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-danger">{{str_contains($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion }}</span>
                                                                </p>
                                                                    {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulageneralrecomendacion.update','id'=>'form']) !!}            
                                                                    <div class="row">
                                                                        <div class="col-md-12">                                                                                 
                                                                            @if (auth()->user()->can('cedulageneralrecomendacion.update'))
                                                                                <button type="submit" name="enviar" class="btn btn-primary">Enviar a revisión</button>
                                                                            @endif 
                                                                        </div>
                                                                    </div>
                                                                    {!! BootForm::close() !!}
                                                                @else
                                                                    <p class="text-gray-600 h4">
                                                                        Fase: <span class="badge badge-danger">{{str_contains($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion }}</span>
                                                                    </p>
                                                                    {{-- <a href="#" class="btn btn-outline-primary"><span class="fa fa-list"></span> Movimientos</a> --}}
                                                                @endcan
                                                            @endif 

                                                            <!-- --------------------------------------------------------------------------- CG Recomendación Enviar a Revision o Aprobar----------------------------------------------------------------------------------------------------- -->
                                                            @if(count($cg_recresultado['analistasF'])>0 && count($cg_recresultado['analistasL'])== 0 && empty($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion))                           
                                                                {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulageneralrecomendacion.update','id'=>'form']) !!}            
                                                                <div class="row">
                                                                    <div class="col-md-12">     
                                                                        @if (auth()->user()->can('cedulageneralrecomendacion.update'))
                                                                            <button type="submit" name="enviar" class="btn btn-primary float-end">Iniciar revisión</button>
                                                                        @endif 
                                                                    </div>
                                                                </div>
                                                                {!! BootForm::close() !!}
                                                            @elseif(in_array(auth()->user()->id, $cg_recresultado['analistasF']) && count($cg_recresultado['analistasL'])>0)                    
                                                                <a href="{{ route('cedgralrecomendacionanalista.edit',$auditoria->cedulageneralrecomendaciones[0]) }}" class="btn btn-primary popuprevisar">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Aprobar
                                                                </a>
                                                            @endif                                                              
                                                            <!-- --------------------------------------------------------------------------- CG Recomendación Revisar01----------------------------------------------------------------------------------------------------- -->
                                                            @if ($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion == 'En revisión 01')          
                                                                @can('cedgralrecomendacionrevision01.edit') 
                                                                    @if(in_array(auth()->user()->id, $cg_recresultado['lideresF']))
                                                                        <a href="{{ route('cedgralrecomendacionrevision01.edit',$auditoria->cedulageneralrecomendaciones[0]) }}" class="btn btn-primary popuprevisar">
                                                                            <li class="fa fa-gavel"></li>
                                                                            Revisar
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    <p class="text-gray-600 h4">
                                                                        Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion }}</span>
                                                                    </p>
                                                                    {{-- <a href="#" class="btn btn-outline-primary"><span class="fa fa-list"></span> Movimientos</a> --}}
                                                                @endcan
                                                            @endif

                                                            <!-- --------------------------------------------------------------------------- CG Recomendación Revisar----------------------------------------------------------------------------------------------------- -->
                                                            @if ($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion == 'En revisión')     
                                                                @can('cedgralrecomendacionrevision.edit')
                                                                    @if(in_array(auth()->user()->unidad_administrativa_id, $cg_recresultado['jefesF']))
                                                                        <a href="{{ route('cedgralrecomendacionrevision.edit',$auditoria->cedulageneralrecomendaciones[0]) }}" class="btn btn-primary popuprevisar">
                                                                            <li class="fa fa-gavel"></li>
                                                                            Revisar
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    <p class="text-gray-600 h4">
                                                                        Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion }}</span>
                                                                    </p>
                                                                    {{-- <a href="#" class="btn btn-outline-primary"><span class="fa fa-list"></span> Movimientos</a>                                                             --}}
                                                                @endcan
                                                            @endif

                                                            <!-- --------------------------------------------------------------------------- CG Recomendación Validar----------------------------------------------------------------------------------------------------- -->
                                                            @if ($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion == 'En validación')
                                                                @can('cedgralrecomendacionvalidacion.edit')
                                                                    <a href="{{ route('cedgralrecomendacionvalidacion.edit',$auditoria->cedulageneralrecomendaciones[0]) }}" class="btn btn-primary popuprevisar">
                                                                        <li class="fa fa-gavel"></li>
                                                                        Validar
                                                                    </a>
                                                                @else
                                                                    <p class="text-gray-600 h4">
                                                                        Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion }}</span>
                                                                    </p>
                                                                @endcan
                                                            @endif

                                                            <!-- --------------------------------------------------------------------------- CG Recomendación Autorizar----------------------------------------------------------------------------------------------------- -->
                                                            @if ($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion == 'En autorización')
                                                                @can('cedgralrecomendacionautorizacion.edit')
                                                                    <a href="{{ route('cedgralrecomendacionautorizacion.edit',$auditoria->cedulageneralrecomendaciones[0]) }}" class="btn btn-primary popuprevisar">
                                                                        <li class="fa fa-gavel"></li>
                                                                        Autorizar
                                                                    </a>
                                                                @else
                                                                    <p class="text-gray-600 h4">
                                                                        Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion }}</span>
                                                                    </p>
                                                                @endcan
                                                            @endif
                                                            
                                                            <!-- --------------------------------------------------------------------------- CG Recomendación Autorizado----------------------------------------------------------------------------------------------------- -->
                                                            @if ($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion=='Autorizado')
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-success">{{str_contains($auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralrecomendaciones[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endif

                                                        @else
                                                        <!-- --------------------------------------------------------------------------- CG Recomendaciones Enviar a revision----------------------------------------------------------------------------------------------------------------------------------- -->   
                                                            {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulageneralrecomendacion.update','id'=>'form']) !!}                                                                      
                                                                <div class="row">
                                                                    <div class="col-md-12" >
                                                                        @if (auth()->user()->can('cedulageneralrecomendacion.update'))
                                                                            <button type="submit" name="enviar" class="btn btn-primary btn btn-primary float-end">Enviar a revisión</button>
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
                                                @if (count($auditoria->totalpras)==$totalprasautorizadas)                                                
                                                    @if(count($auditoria->cedulageneralpras)>0)    
                                                    
                                                        <!-- --------------------------------------------------------------------------- CG PRAS Rechazado----------------------------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulageneralpras[0]->fase_autorizacion == 'Rechazado')
                                                            @can('cedulageneralpras.update')                
                                                                {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulageneralpras.update','id'=>'form']) !!}            
                                                                <div class="row">
                                                                    <div class="col-md-12">     
                                                                        @if (auth()->user()->can('cedulageneralpras.update'))
                                                                            <button type="submit" name="enviar" class="btn btn-primary float-end">Enviar a revisión</button>
                                                                        @endif 
                                                                    </div>
                                                                </div>
                                                                {!! BootForm::close() !!}
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-danger">{{str_contains($auditoria->cedulageneralpras[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralpras[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif 
                                                        
                                                         <!-- --------------------------------------------------------------------------- CG PRAS Enviar a Revision o Aprobar----------------------------------------------------------------------------------------------------- -->
                                                        @if(count($cg_prasresultado['lideresF'])>0 && count($cg_prasresultado['lideresL'])== 0 && empty($auditoria->cedulageneralpras[0]->fase_autorizacion))                           
                                                            {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulageneralpras.update','id'=>'form']) !!}            
                                                            <div class="row">
                                                                <div class="col-md-12">   
                                                                    @if (auth()->user()->can('cedulageneralpras.update'))
                                                                        <button type="submit" name="enviar" class="btn btn-primary float-end">Iniciar revisión</button>
                                                                    @endif 
                                                                </div>
                                                            </div>
                                                            {!! BootForm::close() !!}
                                                        @elseif(in_array(auth()->user()->id, $cg_prasresultado['lideresF']) && count($cg_prasresultado['lideresL'])>0)                    
                                                            <a href="{{ route('cedulageneralpraslider.edit',$auditoria->cedulageneralpras[0]) }}" class="btn btn-primary popuprevisar">
                                                                <li class="fa fa-gavel"></li>
                                                                Aprobar
                                                            </a>
                                                        @endif

                                                        <!-- --------------------------------------------------------------------------- CG PRAS Revisar01----------------------------------------------------------------------------------------------------- -->
                                                        {{-- @if ($auditoria->cedulageneralpras[0]->fase_autorizacion == 'En revisión 01')                                                        
                                                            @can('cedulageneralprasrevision01.edit')                                                             
                                                                @if(in_array(auth()->user()->id,$cg_prasresultado['lideresF']))                                                              
                                                                    <a href="{{ route('cedulageneralprasrevision01.edit',$auditoria->cedulageneralpras[0]) }}" class="btn btn-primary popuprevisar">
                                                                        <li class="fa fa-gavel"></li>
                                                                        Revisar
                                                                    </a>
                                                                @endif
                                                            @else                                                            
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralpras[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralpras[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif --}}

                                                        <!-- --------------------------------------------------------------------------- CG PRAS Revisar----------------------------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulageneralpras[0]->fase_autorizacion == 'En revisión')                    
                                                            @can('cedulageneralprasrevision.edit')
                                                                @if(in_array(auth()->user()->unidad_administrativa_id, $cg_prasresultado['jefesF']))
                                                                    <a href="{{ route('cedulageneralprasrevision.edit',$auditoria->cedulageneralpras[0]) }}" class="btn btn-primary popuprevisar">
                                                                        <li class="fa fa-gavel"></li>
                                                                        Revisar
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralpras[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralpras[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif

                                                        <!-- --------------------------------------------------------------------------- CG PRAS Validar----------------------------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulageneralpras[0]->fase_autorizacion == 'En validación')
                                                            @can('cedulageneralprasvalidacion.edit')
                                                                <a href="{{ route('cedulageneralprasvalidacion.edit',$auditoria->cedulageneralpras[0]) }}" class="btn btn-primary popuprevisar">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Validar
                                                                </a>
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralpras[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralpras[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif

                                                        <!-- --------------------------------------------------------------------------- CG PRAS Autorizar----------------------------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulageneralpras[0]->fase_autorizacion == 'En autorización')
                                                            @can('cedulageneralprasautorizacion.edit')
                                                                <a href="{{ route('cedulageneralprasautorizacion.edit',$auditoria->cedulageneralpras[0]) }}" class="btn btn-primary popuprevisar">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Autorizar
                                                                </a>
                                                            @else
                                                            <p class="text-gray-600 h4">
                                                                Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulageneralpras[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralpras[0]->fase_autorizacion }}</span>
                                                            </p>
                                                            @endcan
                                                        @endif
                                                        
                                                        <!-- --------------------------------------------------------------------------- CG PRAS Autorizado----------------------------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulageneralpras[0]->fase_autorizacion=='Autorizado')
                                                            <p class="text-gray-600 h4">
                                                                Fase: <span class="badge badge-success">{{str_contains($auditoria->cedulageneralpras[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulageneralpras[0]->fase_autorizacion }}</span>
                                                            </p>
                                                        @endif                                                    
                                                    @else  

                                                         <!-- --------------------------------------------------------------------------- CG PRAS Enviar a revision----------------------------------------------------------------------------------------------------------------------------------- -->   
                                                        {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulageneralpras.update','id'=>'form']) !!} 
                                                        <div class="row">
                                                            <div class="col-md-12" >
                                                                @if (auth()->user()->can('cedulageneralpras.update'))
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
                                                       Seguimiento &nbsp;&nbsp;&nbsp;&nbsp; <span class="fa fa-eye"></span>
                                                    </a>
                                                </div>                                               
                                                <div class="ml-6 ml-lg-0 ml-xxl-6 flex-shrink-0 text-center">
                                                @if ($totaut==$totalacciones)
                                                    @if(count($auditoria->cedulaanalitica)>0) 

                                                        <!-- --------------------------------------------------------------------------- CA Seguimiento Rechazado----------------------------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulaanalitica[0]->fase_autorizacion == 'Rechazado')
                                                            @can('cedulaanalitica.update')  
                                                                {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulaanalitica.update','id'=>'form']) !!}            
                                                                <div class="row">
                                                                    <div class="col-md-12">         
                                                                        @if (auth()->user()->can('cedulaanalitica.update'))
                                                                            <button type="submit" name="enviar" class="btn btn-primary">Enviar a revisión</button>
                                                                        @endif 
                                                                    </div>
                                                                </div>
                                                                {!! BootForm::close() !!}
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-danger">{{str_contains($auditoria->cedulaanalitica[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanalitica[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif 

                                                         <!-- --------------------------------------------------------------------------- CA Seguimiento Enviar a Revision o Aprobar----------------------------------------------------------------------------------------------------- -->
                                                        @if(count($ca_seguimiento['analistasF'])>0 && count($ca_seguimiento['analistasL'])== 0 && empty($auditoria->cedulaanalitica[0]->fase_autorizacion))                           
                                                            {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulaanalitica.update','id'=>'form']) !!}            
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    @if (auth()->user()->can('cedulaanalitica.update'))
                                                                        <button type="submit" name="enviar" class="btn btn-primary float-end">Iniciar revisión</button>
                                                                    @endif 
                                                                </div>
                                                            </div>
                                                            {!! BootForm::close() !!}
                                                        @elseif(in_array(auth()->user()->id, $ca_seguimiento['analistasF']) && count($ca_seguimiento['analistasL'])>0)                    
                                                            <a href="{{ route('cedulaanaliticaanalista.edit',$auditoria->cedulaanalitica[0]) }}" class="btn btn-primary popuprevisar float-end">
                                                                <li class="fa fa-gavel"></li>
                                                                Aprobar
                                                            </a>
                                                        @endif                    


                                                        <!-- --------------------------------------------------------------------------- CA Seguimiento Revisar01----------------------------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulaanalitica[0]->fase_autorizacion == 'En revisión 01')          
                                                            @can('cedulaanaliticarevision01.edit') 
                                                                @if(in_array(auth()->user()->id, $ca_seguimiento['lideresF']))
                                                                    <a href="{{ route('cedulaanaliticarevision01.edit',$auditoria->cedulaanalitica[0]) }}" class="btn btn-primary popuprevisar">
                                                                        <li class="fa fa-gavel"></li>
                                                                        Revisar
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulaanalitica[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanalitica[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif

                                                        <!-- --------------------------------------------------------------------------- CA Seguimiento Revisar----------------------------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulaanalitica[0]->fase_autorizacion == 'En revisión')     
                                                            @can('cedulaanaliticarevision.edit')
                                                                @if(in_array(auth()->user()->unidad_administrativa_id, $ca_seguimiento['jefesF']))
                                                                    <a href="{{ route('cedulaanaliticarevision.edit',$auditoria->cedulaanalitica[0]) }}" class="btn btn-primary popuprevisar">
                                                                        <li class="fa fa-gavel"></li>
                                                                        Revisar
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulaanalitica[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanalitica[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif

                                                        <!-- --------------------------------------------------------------------------- CA Seguimiento Validar----------------------------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulaanalitica[0]->fase_autorizacion == 'En validación')
                                                            @can('cedulaanaliticavalidacion.edit')
                                                                <a href="{{ route('cedulaanaliticavalidacion.edit',$auditoria->cedulaanalitica[0]) }}" class="btn btn-primary popuprevisar">
                                                                    <li class="fa fa-gavel"></li>
                                                                    Validar
                                                                </a>
                                                            @else
                                                                <p class="text-gray-600 h4">
                                                                    Fase: <span class="badge badge-warning">{{str_contains($auditoria->cedulaanalitica[0]->fase_autorizacion, 'revisión')?'En revision':$auditoria->cedulaanalitica[0]->fase_autorizacion }}</span>
                                                                </p>
                                                            @endcan
                                                        @endif

                                                        <!-- --------------------------------------------------------------------------- CA Seguimiento Autorizar----------------------------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulaanalitica[0]->fase_autorizacion == 'En autorización')
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
                                                        @endif

                                                        <!-- --------------------------------------------------------------------------- CA Seguimiento Autorizado----------------------------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulaanalitica[0]->fase_autorizacion=='Autorizado')
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								@endif
                                @if (count($auditoria->totalrecomendacion)>0 && (str_contains($auditoria->acto_fiscalizacion, 'Desempeño')||str_contains($auditoria->acto_fiscalizacion, 'Legalidad')))
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
                                                        @endif      

                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Revisar 01---------------------------------------------------------------------------------- -->
                                                        @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En revisión 01')          
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
                                                        @endif

                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Revisar ------------------------------------------------------------------------------------ -->
                                                        @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En revisión')     
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
                                                        @endif

                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Validar ------------------------------------------------------------------------------------ -->
                                                        @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En validación')
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
                                                        @endif

                                                        <!-- --------------------------------------------------------------------------- CA Desempenio Autorizar ------------------------------------------------------------------------------------ -->
                                                        @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En autorización')
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
                </div> 

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
</script>
    
@endsection