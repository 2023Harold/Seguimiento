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
                    <a href="{{ route('auditoriaseguimiento.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Informe Primera Etapa
                </h1>
				<div class="float-end">    
                   {{-- <a href="{{ route('informelegalidad.exportar') }}" class="btn btn-light-primary"><span class="fa fa-file-word">&nbsp;&nbsp;&nbsp;</span>INFORME </a>     --}}
                    @can('informeprimeraetapa.exportar')            
                        @if($auditoria->acto_fiscalizacion=='Legalidad')
                            @if((count($auditoria->accionesrecomendaciones)> 0)&& (count(value: $auditoria->accionespo) > 0))
                                <a href="{{ route('informelegalidad.export') }}?tipo=IS_EA_PAR" class="btn btn-light-primary"><span class="fa fa-file-word">&nbsp;&nbsp;&nbsp;</span>IS. EA Y PAR</a> 
                            @endif
                            @if(count($auditoria->accionesrecomendaciones)> 0)
                                <a href="{{ route('informelegalidad.export') }}?tipo=IS_PAR" class="btn btn-light-primary"><span class="fa fa-file-word">&nbsp;&nbsp;&nbsp;</span>IS. PAR</a>
                            @endif
                            @if(count($auditoria->accionespo) > 0)
                                <a href="{{ route('informelegalidad.export') }}?tipo=IS_EA" class="btn btn-light-primary"><span class="fa fa-file-word">&nbsp;&nbsp;&nbsp;</span>IS. EA</a>
                            @endif
                             <a href="{{route('informelegalidad.exportofis')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;OF. IS</a> 
                        @endif
                            
                        @if($auditoria->acto_fiscalizacion=='Desempeño')
                            <a href="{{route('informedesempeno.export')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;IS PAR</a>                                  
                            <a href="{{route('informedesempeno.exportofis')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;OF. IS PAR</a>          
                        @endif

                        @if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero')
                            <a href="{{route('informecumplimientoi.export')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;IS</a>                                  
                            <a href="{{route('informecumplimientoi.exportofis')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;OF. IS</a> 
                        @endif
                        @if($auditoria->acto_fiscalizacion=='Inversión Física')
                            <a href="{{route('informeinversionf.export')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;IS</a>                                  
                            <a href="{{route('informeinversionf.exportofis')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;OF. IS</a> 
                        @endif
                    @endcan
                </div>
                
            </div>           
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')                     
                  
                <div class="row">
                    <div class="col-md-12">                    
                            {{-- $accionesAutorizadasRec = $auditoria->accionesrecomendaciones->where('recomendaciones.fase_autorizacion', 'Autorizado')->count();                     --}}
                                        
                        @if ($auditoria->accionesrecomendaciones->count() > 0) 
                                   @if ($auditoria->accionesrecomendaciones->where('recomendaciones.fase_autorizacion', 'Autorizado')->count() === $auditoria->accionesrecomendaciones->count())
                                   <h1 class="card-title">
                                    <span class="text-primary"> 
                                        Informe Recomendaciones
                                        @if (empty($auditoria->informeprimeraetapa))
                                            @can('informeprimeraetapa.create')
                                                <a class="btn btn-primary float-end" href="{{ route('informeprimeraetapa.create') }}">
                                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar
                                                </a> 
                                            @endcan
                                        @endif
                                    </span>
                                </h1>  
                                
                                   <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Número de oficio</th>
                                                <th>Nombre del titular</th>
                                                <th>Informe</th>
                                                <th>Fase / Acción </th>
                                                @if (empty($auditoria->informeprimeraetapa->fase_autorizacion)||$auditoria->informeprimeraetapa->fase_autorizacion=='Rechazado')                                                                                                                              
                                                    <th>Envío</th>
                                                @endif
                                                @if(empty($auditoria->informeprimeraetapa))
													<th></th>
												@else
													@if ($auditoria->informeprimeraetapa->fase_autorizacion=='Autorizado')
														<th>Acuses</th>
													@endif
												@endif
                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($auditoria->informeprimeraetapa))
                                            <tr>                                
                                                <td class="text-center">
                                                    {{ fecha($auditoria->informeprimeraetapa->fecha_informe) }}
                                                </td>
                                                <td class="text-center">
                                                    {{$auditoria->informeprimeraetapa->numero_informe }}
                                                </td>
                                                <td class="text-center">
                                                    {{$auditoria->informeprimeraetapa->nombre_titular_informe }}<br>
                                                    <span class="badge-light-dark text-gray-500">{{ $auditoria->informeprimeraetapa->cargo_titular_informe}}</span> <br>
                                                    <span class="badge-light-dark text-gray-500">{{ $auditoria->informeprimeraetapa->periodo_gestion}}</span> <br>
                                                    <span class="badge-light-dark text-gray-700">{{ $auditoria->informeprimeraetapa->domicilio_informe}}</span> <br><br>
                                                </td>
                                                <td class="text-center">
                                                    @btnFile(asset(optional($auditoria->informeprimeraetapa)->informe))
                                                </td>
                                                <td class="text-center">                                                                                                                                                                                                                                                                                       
                                                    @if (empty($auditoria->informeprimeraetapa->fase_autorizacion)||$auditoria->informeprimeraetapa->fase_autorizacion=='Rechazado')
                                                        <span class="badge badge-light-danger">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>
                                                        @can('informeprimeraetapa.edit')                                                        
                                                        <a href="{{ route('informeprimeraetapa.edit',$auditoria->informeprimeraetapa) }}" class="btn btn-primary">
                                                            <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                                        </a>                                                                          
                                                        @endcan
                                                    @endif                                   
                                                    @if ($auditoria->informeprimeraetapa->fase_autorizacion == 'En validación')
                                                        @can('informeprimeraetapavalidacion.edit')
                                                            <a href="{{ route('informeprimeraetapavalidacion.edit',$auditoria->informeprimeraetapa) }}" class="btn btn-primary">
                                                                <li class="fa fa-gavel"></li>
                                                                Validar
                                                            </a>
                                                        @else
                                                            <span class="badge badge-light-warning">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>
                                                        @endcan
                                                    @endif      
                                                    @if ($auditoria->informeprimeraetapa->fase_autorizacion == 'En autorización')
                                                    @can('informeprimeraetapaautorizacion.edit')
                                                        <a href="{{ route('informeprimeraetapaautorizacion.edit',$auditoria->informeprimeraetapa) }}" class="btn btn-primary">
                                                            <li class="fa fa-gavel"></li>
                                                            Autorizar
                                                        </a>
                                                    @else
                                                        <span class="badge badge-light-warning">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>
                                                    @endcan
                                                @endif           
                                                    @if ($auditoria->informeprimeraetapa->fase_autorizacion=='Autorizado')
                                                        <span class="badge badge-light-success">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>                                                                                                                                               
                                                    @endif      
                                                    @if (empty($auditoria->informeprimeraetapa->fase_autorizacion)||$auditoria->informeprimeraetapa->fase_autorizacion=='Rechazado')                                                                                                                              
                                                        <td class="text-center">    
                                                            @can('informeprimeraetapa.edit')                                                        
                                                                <a href="{{ route('informeprimeraetapaenvio.edit',$auditoria->informeprimeraetapa) }}" class="btn btn-primary">
                                                                Enviar
                                                                </a>
                                                            @endcan
                                                        </td> 
                                                    @endif 
                                                    @if (optional($auditoria->informeprimeraetapa)->fase_autorizacion=='Autorizado')
                                                        <td class="text-center">
                                                            @if (empty(optional($auditoria->informeprimeraetapa)->acuse_notificacion))
                                                                <a href="{{ route('informeacuses.edit', $auditoria->informeprimeraetapa) }}" class="btn btn-primary">
                                                                    <span class="fa fa-file-circle-plus" aria-hidden="true"></span>&nbsp; Adjuntar
                                                                </a>
                                                            @endif
                                                            @if (!empty(optional($auditoria->informeprimeraetapa)->acuse_notificacion))
                                                                @can('informeacuses.show')
                                                                    <a href="{{ route('informeacuses.show', $auditoria->informeprimeraetapa) }}" class="btn btn-secondary" >
                                                                        <img alt="Logo" src="{{asset('assets/img/consultar.png')}}" class="h-30px logo" />
                                                                    </a>
                                                                @endcan
                                                            @endif
                                                        </td> 
                                                    @endif
                                                </td>                
                                            </tr>
                                            @if (!empty($auditoria->informeprimeraetapa))
                                                {!! movimientosDesglose($auditoria->informeprimeraetapa->id, 10, $auditoria->informeprimeraetapa->movimientos) !!}
                                            @endif   
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
            </div>                       
            <div class="card-body">                    
                <div class="row">
                    <div class="col-md-12">                 
                        @if ($auditoria->accionespo->count() > 0) 
                            @if ($auditoria->accionespo->where('pliegosobservacion.fase_autorizacion', 'Autorizado')->count() === $auditoria->accionespo->count())
                                <h1 class="card-title">
                                    <span class="text-primary"> 
                                        Informe Pliegos
                                        @if (empty($auditoria->informepliegos))                        
                                            @can('informepliegos.create')
                                                <a class="btn btn-primary float-end" href="{{ route('informepliegos.create') }}">
                                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar
                                                </a>
                                            @endcan                 
                                        @endif  
                                    </span>
                                </h1>     
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Número de oficio</th>
                                                <th>Nombre del titular</th>
                                                <th>Informe</th>
                                                <th>Fase / Acción </th>
                                                @if (empty(optional($auditoria->informepliegos)->fase_autorizacion)||optional($auditoria->informepliegos)->fase_autorizacion=='Rechazado')                                                                                                                              
                                                    <th>Envío</th>
                                                @endif
                                                @if (optional($auditoria->informepliegos)->fase_autorizacion=='Autorizado')
                                                    <th>Acuses</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($auditoria->informepliegos))
                                            <tr>
                                                <td class="text-center">
                                                    {{ fecha($auditoria->informepliegos->fecha_informe) }}
                                                </td>
                                                <td class="text-center">
                                                    {{$auditoria->informepliegos->numero_informe }}
                                                </td>
                                                <td class="text-center">
                                                    {{$auditoria->informepliegos->nombre_titular_informe }}<br>
                                                    <span class="badge-light-dark text-gray-500">{{ $auditoria->informepliegos->cargo_titular_informe}}</span> <br>
                                                    <span class="badge-light-dark text-gray-500">{{ $auditoria->informepliegos->periodo_gestion}}</span> <br>
                                                    <span class="badge-light-dark text-gray-700">{{ $auditoria->informepliegos->domicilio_informe}}</span> <br><br>
                                                </td>
                                                <td class="text-center">
                                                    @btnFile(asset(optional($auditoria->informepliegos)->informe))
                                                </td>
                                                <td class="text-center">                                                                                                                                                                                                                                                                                       
                                                    @if (empty($auditoria->informepliegos->fase_autorizacion)||$auditoria->informepliegos->fase_autorizacion=='Rechazado')
                                                        <span class="badge badge-light-danger">{{ $auditoria->informepliegos->fase_autorizacion }} </span>
                                                        @can('informeprimeraetapa.edit')                                                        
                                                        <a href="{{ route('informeprimeraetapa.edit',$auditoria->informepliegos) }}" class="btn btn-primary">
                                                            <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                                        </a>                                                                          
                                                        @endcan
                                                    @endif                                   
                                                    @if ($auditoria->informepliegos->fase_autorizacion == 'En validación')
                                                        @can('informeprimeraetapavalidacion.edit')
                                                            <a href="{{ route('informeprimeraetapavalidacion.edit',$auditoria->informepliegos) }}" class="btn btn-primary">
                                                                <li class="fa fa-gavel"></li>
                                                                Validar
                                                            </a>
                                                        @else
                                                            <span class="badge badge-light-warning">{{ $auditoria->informepliegos->fase_autorizacion }} </span>
                                                        @endcan
                                                    @endif      
                                                    @if ($auditoria->informepliegos->fase_autorizacion == 'En autorización')
                                                    @can('informeprimeraetapaautorizacion.edit')
                                                        <a href="{{ route('informeprimeraetapaautorizacion.edit',$auditoria->informepliegos) }}" class="btn btn-primary">
                                                            <li class="fa fa-gavel"></li>
                                                            Autorizar
                                                        </a>
                                                    @else
                                                        <span class="badge badge-light-warning">{{ $auditoria->informepliegos->fase_autorizacion }} </span>
                                                    @endcan
                                                @endif           
                                                    @if ($auditoria->informepliegos->fase_autorizacion=='Autorizado')
                                                        <span class="badge badge-light-success">{{ $auditoria->informepliegos->fase_autorizacion }} </span>                                                                                                                                               
                                                    @endif     
                                                    @if(empty($auditoria->informepliegos->fase_autorizacion)||$auditoria->informepliegos->fase_autorizacion=='Rechazado')                                                                                                                  
                                                        <td class="text-center">    
                                                            @can('informeprimeraetapa.edit')                                                        
                                                                <a href="{{ route('informeprimeraetapaenvio.edit',$auditoria->informepliegos) }}" class="btn btn-primary">
                                                                Enviar
                                                                </a>
                                                            @endcan
                                                        </td> 
                                                    @endif 
                                                </td> 
                                                @if (optional($auditoria->informepliegos)->fase_autorizacion=='Autorizado')
                                                    <td class="text-center">
                                                        @if (empty(optional($auditoria->informepliegos)->acuse_notificacion))
                                                            <a href="{{ route('informeacuses.edit', $auditoria->informepliegos) }}" class="btn btn-primary">
                                                                <span class="fa fa-file-circle-plus" aria-hidden="true"></span>&nbsp; Adjuntar
                                                            </a>
                                                        @endif
                                                        @if (!empty(optional($auditoria->informepliegos)->acuse_notificacion))
                                                            @can('informeacuses.show')
                                                                <a href="{{ route('informeacuses.show', $auditoria->informepliegos) }}" class="btn btn-secondary" >
                                                                    <img alt="Logo" src="{{asset('assets/img/consultar.png')}}" class="h-30px logo" />
                                                                </a>
                                                            @endcan
                                                        @endif
                                                    </td> 
                                                @endif             
                                            </tr>
                                            @if (!empty($auditoria->informepliegos))
                                                {!! movimientosDesglose($auditoria->informepliegos->id, 10, $auditoria->informepliegos->movimientos) !!}
                                            @endif   
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
            </div>        
        </div>                
    </div>                 

@endsection
