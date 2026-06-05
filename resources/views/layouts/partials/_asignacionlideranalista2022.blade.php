<td class="text-center">
@if (!empty($auditoria->accionesDepartamento[0]->lider_asignado))                                            
<div class="row">
    <div class="col-md-6">
        <span class="badge-light-secondary text-gray-600">
            {{ $auditoria->accionesDepartamento[0]->lider_asignado  }} <br>
            {{ $auditoria->accionesDepartamento[0]->lider->puesto  }}
        </span><br>
        @if ($auditoria->accionesDepartamento[0]->reasignacion_lider=='Si')
            <span class="badge badge-square badge-light-secondary text-gray-500">Reasignado</span>
        @else
            @can('asignacionlideranalista.reasignarlider')
                <a href="{{ route('asignacionlideranalista.reasignarlider',$auditoria) }}" class="btn btn-primary">
                    <i class="fa fa-exchange"></i> Reasignar
                </a>
            @endcan
        @endif                                                
    </div>
    <div class="col-md-6">
        <span class="badge-light-secondary text-gray-600">
            {{ $auditoria->accionesDepartamento[0]->analista_asignado  }} <br>
            {{ $auditoria->accionesDepartamento[0]->analista->puesto  }}
        </span><br>
        @if ($auditoria->accionesDepartamento[0]->reasignacion_analista=='Si')
            <span class="badge badge-square badge-light-secondary text-gray-500">Reasignado</span>
        @else
            @can('asignacionlideranalista.reasignaranalista')
                <a href="{{ route('asignacionlideranalista.reasignaranalista',$auditoria) }}" class="btn btn-primary">
                    <i class="fa fa-exchange"></i> Reasignar
                </a>
            @endcan
        @endif
    </div>
</div>  
@else
    @can('asignacionlideranalista.edit')
        <a href="{{ route('asignacionlideranalista.edit',$auditoria) }}" class="btn btn-primary">
            <i class="fa fa-handshake"></i> Asignar
        </a>  
    @endcan                                      
@endif 
@can('asignacionlideranalista.consulta')
    <a href="{{ route('asignacionlideranalista.consulta',$auditoria) }}" class="btn btn-primary">
        <i class="fa fa-magnifying-glass-chart"></i> Consultar
    </a> 
@endcan    
</td>