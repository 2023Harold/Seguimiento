@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('asignacionlideranalista.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Asignación de Auditorias a Lideres y Analistas
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')                
                {!! BootForm::open(['route'=>'asignacionlideranalista.index','method'=>'GET']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            {!! BootForm::text('numero_auditoria', "No. auditoría:", old('numero_auditoria', $request->numero_auditoria)) !!}
                        </div>
                        <div class="col-md-2">
                            {!! BootForm::text('entidad_fiscalizable', "Entidad fiscalizable:", old('entidad_fiscalizable', $request->entidad_fiscalizable)) !!}
                        </div>
                        <div class="col-md-2">
                            {!! BootForm::text('acto_fiscalizacion', "Acto de fiscalización:", old('acto_fiscalizacion', $request->acto_fiscalizacion)) !!}
                        </div>
                        <div class="col-md-6 mt-8">
                            <button type="submit" class="btn btn-primary"><i class="align-middle fas fa-search" aria-hidden="true"></i>Buscar</button>                           
                        </div>
                    </div>
                {!! BootForm::close() !!}
				<div class="row">
					<div class="col-md-12">
						<div class="pagination float-end">
							{{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5') }}
						</div>
					</div>
				</div>				
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. de auditoría</th>
                                <th>Entidad fiscalizable</th>
                                <th>Acto de fiscalización</th>
                                <th>Informe de auditoría</th>
                                <th>Acciones promovidas</th>
                                <th>Monto por aclarar</th>                                                                 
                                <th>Asignación de lider y analista</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                                <tr>
                                    <td>
                                        {{ $auditoria->numero_auditoria }}
                                    </td>
                                    <td width='40%'>
                                        @php
                                            $entidadparciales = explode("-", $auditoria->entidad_fiscalizable);                                            
                                        @endphp
                                        @foreach ($entidadparciales as $entidadparcial)
                                            {{ mb_convert_encoding(mb_convert_case(strtolower($entidadparcial), MB_CASE_TITLE), "UTF-8"); }}<br>
                                        @endforeach                                        
                                    </td>
                                    <td>
                                        {{ $auditoria->acto_fiscalizacion }}
                                    </td>                                    
                                    <td class="text-center">
                                        @if (!empty($auditoria->informe_auditoria))
                                            <a href="{{ asset($auditoria->informe_auditoria) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->informe_auditoria)) ?>
                                            </a>                                    
                                        @endif
                                    </td>
                                    <td class="text-center">
                                            @if($auditoria->registro_concluido=='Si'&& count($auditoria->acciones) > 0)
                                                @can('asignacionlideranalista.accionesconsulta')
                                                    <a href="{{ route('asignacionlideranalista.accionesconsulta', $auditoria) }}" class="btn btn-light-primary"><i class="fa fa-magnifying-glass-chart"></i>Consultar</a>
                                                @endcan
                                            @endif                                  
                                    </td>
                                    <td style="text-align: right!important;">
                                        {{ '$'.number_format( $auditoria->total(), 2) }}                                         
                                    </td>                                    
                                    <td class="text-center">
                                    @if(getSession('cp')==2023)
                                      @if (!empty($auditoria->lidercp_id))                                            
                                      <div class="row">
                                          <div class="col-md-6">
                                              <span class="badge-light-secondary text-gray-600">
                                                  {{ $auditoria->lidercp->name  }} <br>
                                                  {{ $auditoria->lidercp->puesto  }}
                                              </span><br>                                              
                                                  @can('asignacionlideranalista.reasignarlider')
                                                      <a href="{{ route('asignacionlideranalista.reasignarlider',$auditoria) }}" class="btn btn-primary">
                                                          <i class="fa fa-exchange"></i> Reasignar
                                                      </a>
                                                  @endcan                                                                                              
                                          </div>
                                          <div class="col-md-6">
                                              <span class="badge-light-secondary text-gray-600">
                                                  {{ $auditoria->analistacp->name }} <br>
                                                  {{ $auditoria->analistacp->puesto  }}
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
                                    @else                                        
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
                <div class="pagination float-end">
                    {{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
