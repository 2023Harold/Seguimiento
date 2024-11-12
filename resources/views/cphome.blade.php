@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('cphome') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    
                    <a class="btn btn-primary float-end" href="{{ route('administracion.index') }}">
                     Administración
                     </a>
                    </h1>
            </div>
         </div>      
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
    <div class="row justify-content-center">
        <div class="mt-4 mb-5 col-11">                                               
            <div class="card-body">
                <div class="row justify-content-center">
                @foreach ($cps as $cp)  
                @if(!empty(auth()->user()->cp_2021) && $cp->id==1)
                    <div class="col-12">
                        <div class="flex-row row flex-center">            
                            <div class="mb-3 col-md-12">
                                <a href="{{ route('cuenta',$cp) }}"> 
                                <div class="card bg-hover-secondary bg-gray-200">
                                    <div class="m-5">                                        
                                        <h1 class="text-primary"> 
                                            <span class="text-gray-800 fs-2x fa fa-folder-closed"></span>                                           
                                            Cuenta Pública {{($cp->cuenta_publica) }}                                            
                                        </h1>
                                        <h6 class="text-primary">{{($cp->leyenda) }} </h6>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>  
                @elseif(!empty(auth()->user()->cp_2022) && $cp->id==2)
                <div class="col-12">
                    <div class="flex-row row flex-center">            
                        <div class="mb-3 col-md-12">
                            <a href="{{ route('cuenta',$cp) }}"> 
                            <div class="card bg-hover-secondary bg-gray-200">
                                <div class="m-5">                                        
                                    <h1 class="text-primary"> 
                                        <span class="text-gray-800 fs-2x fa fa-folder-closed"></span>                                           
                                        Cuenta Pública {{($cp->cuenta_publica) }}                                            
                                    </h1>
                                    <h6 class="text-primary">{{($cp->leyenda) }} </h6>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>  
                @elseif(!empty(auth()->user()->cp_2023) && $cp->id==3)
                <div class="col-12">
                    <div class="flex-row row flex-center">            
                        <div class="mb-3 col-md-12">
                            <a href="{{ route('cuenta',$cp) }}"> 
                            <div class="card bg-hover-secondary bg-gray-200">
                                <div class="m-5">                                        
                                    <h1 class="text-primary"> 
                                        <span class="text-gray-800 fs-2x fa fa-folder-closed"></span>                                           
                                        Cuenta Pública {{($cp->cuenta_publica) }}                                            
                                    </h1>
                                    <h6 class="text-primary">{{($cp->leyenda) }} </h6>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>  
                @endif 
                @endforeach
            </div>
            </div>    
        </div>    
        </div>
    </div>
    </div>
</div> 
</div>
@endsection
