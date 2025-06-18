@extends('layouts.app')
@section('breadcrums')
{{Breadcrumbs::render('informeprimeraetapa.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('informeprimeraetapa.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Informe Primera Etapa Acuses
                </h1>
                
            </div>           
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')                     
                @if(!empty(optional($auditoria->informeprimeraetapa)->acuse_envio))
                    <div class="row">
                        <div class="col-md-12">                    
                                {{-- $accionesAutorizadasRec = $auditoria->accionesrecomendaciones->where('recomendaciones.fase_autorizacion', 'Autorizado')->count();                     --}}
                            @if ($auditoria->accionesrecomendaciones->count() > 0) 
                                @if ($auditoria->accionesrecomendaciones->where('recomendaciones.fase_autorizacion', 'Autorizado')->count() === $auditoria->accionesrecomendaciones->count())
                                    <h1 class="card-title">
                                        <span class="text-primary"> 
                                            Informe Recomendaciones
                                        </span>
                                    </h1>  
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Acuse envío a notificar</th>                                
                                                    <th>Acuse de notificación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($auditoria->informeprimeraetapa))
                                                <tr>                                
                                                    <td class="text-center">
                                                        @btnFile(asset(optional($auditoria->informeprimeraetapa)->acuse_envio))<br>
                                                        {{ fecha(optional($auditoria->informeprimeraetapa)->fecha_acuse_envio) }}
                                                    </td>                                
                                                    <td class="text-center">
                                                        @btnFile(asset(optional($auditoria->informeprimeraetapa)->acuse_notificacion))<br>
                                                        {{ fecha(optional($auditoria->informeprimeraetapa)->fecha_notificacion) }}
                                                    </td>
                                                    {{--<td class="text-center">
                                                        @if(empty(optional($auditoria->informeprimeraetapa)->usuario_modificacion_id))
                                                            <a href="{{ route('informeacuses.edit', $auditoria->informeprimeraetapa) }}"  class="btn btn-primary">
                                                                <i class="align-middle fas fa-edit" aria-hidden="true"></i> 
                                                            </a>
                                                        @endif
                                                    </td>--}}
                                                                
                                                </tr>  
                                                @else                                                             
                                                <tr>            
                                                    <td class="text-center" colspan="5">
                                                        No se encuentran registros en este apartado.
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>     
                                @endif
                            @endif
                        </div>                    
                    </div>                        
                @endif                                      
            </div>                       
            <div class="card-body"> 
                @if(!empty(optional($auditoria->informepliegos)->acuse_envio))                 
                    <div class="row">
                        <div class="col-md-12">                   
                            @if ($auditoria->accionespo->count() > 0) 
                                @if ($auditoria->accionespo->where('pliegosobservacion.fase_autorizacion', 'Autorizado')->count() === $auditoria->accionespo->count())
                                    <h1 class="card-title">
                                        <span class="text-primary"> 
                                            Informe Pliegos
                                        </span>
                                    </h1>   
                                    @if (!empty($auditoria->informepliegos))  
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Acuse envío a notificar</th>                                
                                                        <th>Acuse de notificación</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">
                                                            @btnFile(asset(optional($auditoria->informepliegos)->acuse_envio))<br>
                                                            {{ fecha(optional($auditoria->informepliegos)->fecha_acuse_envio) }}
                                                        </td>                                
                                                        <td class="text-center">
                                                            @btnFile(asset(optional($auditoria->informepliegos)->acuse_notificacion)) <br>
                                                            {{ fecha(optional($auditoria->informepliegos)->fecha_notificacion) }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div> 
                                    @endif                        
                                @endif 
                            @endif  
                        </div>                                              
                    </div>
                @endif                                                                                              
            </div>        
        </div>                
    </div>                 
@endsection
