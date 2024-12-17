@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('radicacion.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('auditoriaseguimiento.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Radicación
                </h1>
				<div class="float-end">
                   <a href="{{route('radicacionar.exportar_ar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;AR</a>                                  
                </div>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @if(getSession('cp')==2022)
                    @if (empty($auditoria->radicacion) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                        @can('radicacion.auditoria')
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('radicacion.auditoria',$auditoria) }}"  class="btn btn-primary float-end">
                                        <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar radicación
                                    </a>
                                </div>                    
                            </div>					
                        @endcan 
                    @else
                        @include('layouts.contextos._radicacion')					
                    @endif 
                @elseif(getSession('cp')==2023)
                    @if (empty($auditoria->radicacion) && $auditoria->lidercp_id==auth()->user()->id)
                        @can('radicacion.auditoria')
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('radicacion.auditoria',$auditoria) }}"  class="btn btn-primary float-end">
                                        <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar radicación
                                    </a>
                                </div>                    
                            </div>					
                        @endcan 
                    @else
                        @include('layouts.contextos._radicacion')					
                    @endif 
                @endif		
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>  
                                <th>Número de expediente</th>
                                <th>Número de oficio de notificación del informe de auditoria</th>
                                <th>Acuerdo de radicación</th>                          
                                <th>Fase / Acción / Constancia</th>
                                <th>Acuses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($auditoria->radicacion))
                                <tr>  
                                    <td class="text-center">                                       
                                        @if (!empty($auditoria->radicacion))
                                            {{ $auditoria->radicacion->numero_expediente }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($auditoria->radicacion))
                                            {{ $auditoria->radicacion->numero_acuerdo }}
                                        @endif
                                    </td>
                                    {{-- <td class="text-center">
                                        @if (!empty($auditoria->radicacion))
                                        <a href="{{ asset($auditoria->radicacion->oficio_acuerdo) }}" target="_blank">
                                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->radicacion->oficio_acuerdo)) ?>
                                        </a> <br>
                                        <small>{{ fecha($auditoria->radicacion->fecha_oficio_acuerdo) }}</small>
                                        @endif
                                    </td> --}}
									<td class="text-center">
										@can('radicacion.radicacionpdf')
											<a href="{{route('radicacion.radicacionpdf',$auditoria->radicacion)}}" target="_blank">
												<?php echo htmlspecialchars_decode(iconoArchivo('.pdf')) ?>
											</a>
										@endcan
                                    </td> 
                                    <td class="text-center">  
                                    @if(getSession('cp')==2022)                                                                     
                                            @if (empty($auditoria->radicacion->fase_autorizacion)||$auditoria->radicacion->fase_autorizacion=='Rechazado')
                                                <span class="badge badge-light-danger">{{ $auditoria->radicacion->fase_autorizacion }} </span><br>
                                                    @can('radicacion.edit')
                                                        <a href="{{ route('radicacion.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                            <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                                        </a>
                                                    @endcan
                                            @endif
                                            @if ($auditoria->radicacion->fase_autorizacion == 'En validación')
                                                @can('radicacionvalidacion.edit')
                                                    <a href="{{ route('radicacionvalidacion.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Validar
                                                    </a>
                                                @else
                                                    <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion }} </span>
                                                @endcan
                                            @endif
                                            @if ($auditoria->radicacion->fase_autorizacion == 'En autorización')
                                                @can('radicacionautorizacion.edit')
                                                    <a href="{{ route('radicacionautorizacion.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Autorizar
                                                    </a>
                                                @else
                                                    <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion }} </span>
                                                @endcan
                                            @endif
                                            @if ($auditoria->radicacion->fase_autorizacion=='Autorizado')
                                            <span class="badge badge-light-success">{{ $auditoria->radicacion->fase_autorizacion }} </span> <br>
												@can('radicacionautorizacion.edit')
                                                    <a href="{{ route('radicacionautorizacion.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Autorizar
                                                    </a>                                                
                                                @endcan
										
                                                @if(!empty($auditoria->radicacion->radicacion_sistema))
                                                    <br>
                                                    @btnFile($auditoria->radicacion->constancia) 
                                                @endif
                                                                                                 
                                            @endif  
                                    @elseif(getSession('cp')==2023) 
                                            @if (empty($auditoria->radicacion->fase_autorizacion)||$auditoria->radicacion->fase_autorizacion=='Rechazado')
                                                <span class="badge badge-light-danger">{{ $auditoria->radicacion->fase_autorizacion }} </span><br>
                                                    @can('radicacion.edit')
                                                        <a href="{{ route('radicacion.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                            <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                                        </a>
                                                    @endcan
                                            @endif
                                            @if ($auditoria->radicacion->fase_autorizacion == 'En revisión')
                                                @can('radicacionrevision.edit')
                                                    <a href="{{ route('radicacionrevision.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Revisión
                                                    </a>
                                                @else
                                                    <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion }} </span>
                                                @endcan                                                
                                            @endif
                                            @if ($auditoria->radicacion->fase_autorizacion == 'En validación')
                                                @can('radicacionvalidacion.edit')
                                                    <a href="{{ route('radicacionvalidacion.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Validar
                                                    </a>
                                                @else
                                                    <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion }} </span>
                                                @endcan
                                            @endif
                                            @if ($auditoria->radicacion->fase_autorizacion == 'En autorización')
                                                @can('radicacionautorizacion.edit')
                                                    <a href="{{ route('radicacionautorizacion.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Autorizar
                                                    </a>
                                                @else
                                                    <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion }} </span>
                                                @endcan
                                            @endif
                                            @if ($auditoria->radicacion->fase_autorizacion=='Autorizado')
                                            <span class="badge badge-light-success">{{ $auditoria->radicacion->fase_autorizacion }} </span> <br>
												@can('radicacionautorizacion.edit')
                                                    <a href="{{ route('radicacionautorizacion.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Autorizar
                                                    </a>                                                
                                                @endcan
										
                                                @if(!empty($auditoria->radicacion->radicacion_sistema))
                                                    <br>
                                                    @btnFile($auditoria->radicacion->constancia) 
                                                @endif
                                                                                                 
                                            @endif  
                                    @endif                                         
                                    </td>
                                    <td class="text-center">                                                                            
                                        @if (!empty($auditoria->radicacion->fase_autorizacion)&&$auditoria->radicacion->fase_autorizacion=='Autorizado')
                                            @if (empty($auditoria->comparecencia->oficio_recepcion))  
                                                @if(getSession('cp')==2023)                                                                                       
                                                    @can('comparecenciaacusecp.edit')
                                                        <a href="{{ route('comparecenciaacusecp.edit', $auditoria->comparecencia) }}" class="btn btn-primary">
                                                            <span class="fa fa-file-circle-plus" aria-hidden="true"></span>&nbsp; Adjuntar
                                                        </a>
                                                    @endcan
                                                @else
                                                    @can('comparecenciaacuse.edit')
                                                        <a href="{{ route('comparecenciaacuse.edit', $auditoria->comparecencia) }}" class="btn btn-primary">
                                                            <span class="fa fa-file-circle-plus" aria-hidden="true"></span>&nbsp; Adjuntar
                                                        </a>
                                                    @endcan
                                                @endif
                                            @else                                           
                                                @can('comparecenciaacuse.show')
                                                    <a href="{{ route('comparecenciaacuse.show', $auditoria->comparecencia) }}" class="btn btn-secondary" >
                                                        <img alt="Logo" src="{{asset('assets/img/consultar.png')}}" class="h-30px logo" />
                                                    </a>
                                                @endcan
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @if (!empty($auditoria->radicacion))
                                    {!! movimientosDesglose($auditoria->radicacion->id, 10, $auditoria->radicacion->movimientos) !!}
                                @endif                                                                                           
                            @else
                                <tr>
                                    <td class="text-center" colspan="10">
                                        <span class='text-center'>No hay registros en éste apartado</span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>                
            </div>
        </div>
    </div>
</div>
@endsection
