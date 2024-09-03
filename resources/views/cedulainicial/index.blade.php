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
                                    <div class="d-flex flex-column">Cédula Analítica Desempeño
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>  
                    @endcan 
                    @endif 
                    @if($auditoria->acto_fiscalizacion!='Desempeño')
                        @can('cedulaanalitica.edit')              
                        <div class="col-md-4 mt-2">
                            <a href="{{ route('cedulaanaliticadesemp.edit',$auditoria) }}" rel="noopener noreferrer">
                                <div class="card">                           
                                    <div class="card-body overflow-auto h-50px btn btn-secondary">
                                        <div class="d-flex flex-column">Cédula Analítica Desempeño
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>  
                        @endcan
                    @endif
                </div>                
            </div>
        </div>
    </div>
</div>
@endsection