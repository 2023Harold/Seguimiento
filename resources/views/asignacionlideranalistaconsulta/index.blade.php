@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('asignacionlideranalista.consulta',$auditoria) }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('asignacionlideranalista.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Consulta de Asignaciones de Lideres y Analistas
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')   
                @include('layouts.contextos._auditoria')                            
                <h3 class="card-title text-primary">Acciones</h3> 
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Consecutivo</th>
                                <th>Tipo de acción</th>
                                <th>Número de acción</th>  
                                <th>Monto por aclarar</th>
                                <th>Departamento</th>                      
                                <th>Lider de proyecto</th>                      
                                <th>Analista</th>                      
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($acciones as $accion)
                                <tr>
                                    <td class="text-center">
                                        {{ str_pad($accion->consecutivo, 3, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td>
                                        {{ $accion->tipo }}
                                    </td>
                                    <td class="text-center">
                                        {{ $accion->numero }}                                        
                                    </td>                 
                                    <td style="text-align: right!important;">
                                        {{ '$'.number_format( $accion->monto_aclarar, 2) }} 
                                    </td>
                                    <td class="text-center"  width="20%">    
                                        <div class="row">                                    
                                            <div class="col-md-12">
                                                @if (!empty($auditoria->asignacion_departamentos) && $auditoria->asignacion_departamentos=='Si' )
                                                    <span class="badge-light-secondary text-gray-600">
                                                        {{ $accion->departamento_asignado }} <br>
                                                        {{ $accion->depaasignado->name }} <br>
                                                        {{ $accion->depaasignado->puesto }} <br>                                                
                                                    </span>
                                                    @if ($accion->reasignacion_departamento=="Si")
                                                        <span class="badge badge-square badge-light-secondary text-gray-500">Reasignado</span>                                                    
                                                    @endif  
                                                 @else            
                                                    <span class="badge-light-secondary text-gray-600">
                                                        Sin asignación <br>
                                                    </span>                          
                                                @endif
                                            </div>
                                        </div>                     
                                    </td> 
                                    <td class="text-center">
                                        @if (!empty($accion->lider_asignado))
                                            <span class="badge-light-secondary text-gray-600">
                                                {{ $accion->lider_asignado  }} <br>
                                                {{ $accion->lider->puesto  }}
                                            </span><br>
                                            @if ($accion->reasignacion_lider=='Si')
                                                <span class="badge badge-square badge-light-secondary text-gray-500">Reasignado</span>                                   
                                            @endif
                                        @else 
                                            <span class="badge-light-secondary text-gray-600">
                                                Sin asignación <br>
                                            </span>
                                        @endif  
                                    </td>   
                                    <td class="text-center">
                                        @if (!empty($accion->analista_asignado))
                                            <span class="badge-light-secondary text-gray-600">
                                                {{ $accion->analista_asignado  }} <br>
                                                {{ $accion->analista->puesto  }}
                                            </span><br>
                                            @if ($accion->reasignacion_analista=='Si')
                                                <span class="badge badge-square badge-light-secondary text-gray-500">Reasignado</span>                                   
                                            @endif
                                        @else 
                                            <span class="badge-light-secondary text-gray-600">
                                                Sin asignación <br>
                                            </span>
                                        @endif  
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
            </div>
        </div>
    </div>
</div>
@endsection
