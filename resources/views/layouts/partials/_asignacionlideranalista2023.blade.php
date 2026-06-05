 <td class="text-center">
    @if (!empty($auditoria->lidercp_id))                                            
        <div class="row">
            <div class="col-md-6">
                <span class="badge-light-secondary text-gray-600">
                    {{ optional($auditoria->lidercp)->name  }} <br>
                    {{ optional($auditoria->lidercp)->puesto  }}
                </span><br>                                              
                    @can('asignacionlideranalista.reasignarlider')
                        <a href="{{ route('asignacionlideranalista.reasignarlider',$auditoria) }}" class="btn btn-primary">
                            <i class="fa fa-exchange"></i> Reasignar
                        </a>
                    @endcan                                                                                              
            </div>
            <div class="col-md-6">
                <span class="badge-light-secondary text-gray-600">
                    {{ optional($auditoria->analistacp)->name }} <br>
                    {{ optional($auditoria->analistacp)->puesto  }}
                </span><br>                                            
                    @can('asignacionlideranalista.reasignaranalista')
                        <a href="{{ route('asignacionlideranalista.reasignaranalista',$auditoria) }}" class="btn btn-primary">
                            <i class="fa fa-exchange"></i> Reasignar
                        </a>
                    @endcan                                              
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