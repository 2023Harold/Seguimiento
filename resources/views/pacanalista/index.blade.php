@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('pac.index') }}
@endsection
@section('content')
<style>
.card-a:hover{
    background: #b64876!important;
    color: #FFF!important;
    border-color: #420a22 !important;
    animation: pulse 1s infinite ease-in-out alternate;    
}

@keyframes pulse {
  from { transform: scale(1.0); }
  to { transform: scale(1.05); }
}

@keyframes mymove {
  from {background-color: red;}
  to {background-color: blue;}
}


</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    PAC
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')  
                <div class="row">                
                    <div class="card card-bordered border-primary">  
                        <div class="ribbon ribbon-top">
                            <div class="ribbon-label bg-primary">Analista</div>      
                        </div>                  
                        <div class="card-body ">                                              
                            <div class="row">
                                <div class="col-md-4 ">
                                    <a href="{{ route('pac.mot',1) }}" class="card card-a  text-gray-700 align-items-center Grupo">
                                        <div class="card-body d-flex align-items-center pulses">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span>                        
                                            <span class="ms-3 fs-6 fw-bold">
                                                MOT
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-4">
                                    <a href="{{ route('pac.fc',2) }}" class="card card-a  text-gray-700 align-items-center mbs">
                                        <div class="card-body d-flex align-items-center pulses">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                    
                                            <span class="ms-3 fs-6 fw-bold">
                                                FC
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-4">
                                    <a href="{{ route('pac.fccd',3) }}" class="card card-a text-gray-700 align-items-center mbs">
                                        <div class="card-body d-flex align-items-center pulses">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                            <span class="ms-3 fs-6 fw-bold">
                                                FC CD
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                            </div>   
                        </div>
                    </div> 
                </div> 
                <div class="row">
                    <div class="card card-bordered border-primary"> 
                        <div class="ribbon ribbon-top">
                            <div class="ribbon-label bg-primary">Lider</div>      
                        </div>                     
                        <div class="card-body ">                                                   
                            <div class="row">
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ar',1) }}" class="card card-a  text-gray-700   align-items-center ">
                                        <div class="card-body d-flex align-items-center ">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span>                        
                                            <span class="ms-3 fs-6 fw-bold">
                                                AR
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ofiaar',2) }}" class="card card-a text-gray-700   align-items-center ">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                    
                                            <span class="ms-3 fs-6 fw-bold">
                                                Of. IA AR
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ofaroics',3) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                            <span class="ms-3 fs-6 fw-bold">
                                                Of. AR_OIC's
                                            </span>
                                        </div>
                                    </a>
                                </div>                           
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ac',1) }}" class="card card-a text-gray-700   align-items-center ">
                                        <div class="card-body d-flex align-items-center ">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span>                        
                                            <span class="ms-3 fs-6 fw-bold">
                                                4 AC
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ofai',2) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                    
                                            <span class="ms-3 fs-6 fw-bold">
                                                Of. AI
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ofroics',3) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                            <span class="ms-3 fs-6 fw-bold">
                                                Of. R_OIC's
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                            </div>   
                            <div class="row">
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ofprasoics',1) }}" class="card card-a text-gray-700 align-items-center ">
                                        <div class="card-body d-flex align-items-center ">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span>                        
                                            <span class="ms-3 fs-6 fw-bold">
                                                Of. PRAS_OIC's
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ofsc',2) }}" class="card  card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                    
                                            <span class="ms-3 fs-6 fw-bold">
                                                Of. SC
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ofuaj',3) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                            <span class="ms-3 fs-6 fw-bold">
                                                Of. UAJ
                                            </span>
                                        </div>
                                    </a>
                                </div>                           
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ac10',1) }}" class="card card-a text-gray-700   align-items-center ">
                                        <div class="card-body d-flex align-items-center ">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span>                        
                                            <span class="ms-3 fs-6 fw-bold">
                                                10 AC
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.acral',2) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                    
                                            <span class="ms-3 fs-6 fw-bold">
                                                AC_R AL
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ofac',3) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                            <span class="ms-3 fs-6 fw-bold">
                                                Of. AC
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                            </div>   
                            <div class="row">
                                <div class="col-md-2">
                                    <a href="{{ route('pac.anv',1) }}" class="card card-a text-gray-700   align-items-center ">
                                        <div class="card-body d-flex align-items-center ">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span>                        
                                            <span class="ms-3 fs-6 fw-bold">
                                                AnV
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ofanv',2) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                    
                                            <span class="ms-3 fs-6 fw-bold">
                                                OF. AnV
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.av',3) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                            <span class="ms-3 fs-6 fw-bold">
                                                A. V
                                            </span>
                                        </div>
                                    </a>
                                </div>                           
                                <div class="col-md-2">
                                    <a href="{{ route('pac.oi',1) }}" class="card card-a text-gray-700   align-items-center ">
                                        <div class="card-body d-flex align-items-center ">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span>                        
                                            <span class="ms-3 fs-6 fw-bold">
                                                OI
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ofriii',2) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                    
                                            <span class="ms-3 fs-6 fw-bold">
                                                OF. RIII
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-2">
                                    <a href="{{ route('pac.ai',3) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                            <span class="ms-3 fs-6 fw-bold">
                                                AI
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                            </div>   
                        </div>
                    </div> 
                </div>   
                <div class="row">                
                    <div class="card card-bordered border-primary">  
                        <div class="ribbon ribbon-top">
                            <div class="ribbon-label bg-primary">Jefe de Departamento</div>      
                        </div>                  
                        <div class="card-body ">                                              
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="{{ route('pac.ofis',1) }}" class="card card-a text-gray-700   align-items-center ">
                                        <div class="card-body d-flex align-items-center ">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span>                        
                                            <span class="ms-3 fs-6 fw-bold">
                                                Of. IS
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-4">
                                    <a href="{{ route('pac.is',2) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                    
                                            <span class="ms-3 fs-6 fw-bold">
                                                IS
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-4">
                                    <a href="{{ route('pac.is2',3) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                            <span class="ms-3 fs-6 fw-bold">
                                                IS2
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                            </div>   
                        </div>
                    </div> 
                </div> 
                <div class="row">                
                    <div class="card card-bordered border-primary">  
                        <div class="ribbon ribbon-top">
                            <div class="ribbon-label bg-primary">Director</div>      
                        </div>                  
                        <div class="card-body ">                                              
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-4">
                                    <a href="{{ route('pac.mda',1) }}" class="card card-a text-gray-700   align-items-center ">
                                        <div class="card-body d-flex align-items-center ">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span>                        
                                            <span class="ms-3 fs-6 fw-bold">
                                                MDA
                                            </span>
                                        </div>
                                    </a>
                                </div>                    
                                <div class="col-md-4">
                                    <a href="{{ route('pac.mdi',2) }}" class="card card-a text-gray-700   align-items-center">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span> 
                                    
                                            <span class="ms-3 fs-6 fw-bold">
                                                MDJ
                                            </span>
                                        </div>
                                    </a>
                                </div>  
                                <div class="col-md-2"></div>
                            </div>   
                        </div>
                    </div> 
                </div> 
                <div class="row">                
                    <div class="card card-bordered border-primary">  
                        <div class="ribbon ribbon-top">
                            <div class="ribbon-label bg-primary">Titular</div>      
                        </div>                  
                        <div class="card-body ">                                              
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <a href="{{ route('pac.aa',1) }}" class="card card-a text-gray-700   align-items-center ">
                                        <div class="card-body d-flex align-items-center ">
                                            <span class="bi bi-file-earmark-text fs-2x">                                    
                                            </span>                        
                                            <span class="ms-3 fs-6 fw-bold">
                                                AA
                                            </span>
                                        </div>
                                    </a>
                                </div>  
                                <div class="col-md-4"></div>
                            </div>   
                        </div>
                    </div> 
                </div> 
            </div>
        </div>
    </div>
</div>
@endsection
