@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('asignaciondepartamento.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Asignación de Auditorias a Departamentos
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')                
                {!! BootForm::open(['route'=>'asignaciondepartamento.index','method'=>'GET']) !!}
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
                                <th>Asignación del encargado de la auditoría</th>
                                <th>Asignación de departamentos</th>
                                
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
                                        @can('asignaciondepartamento.accionesconsulta')
                                            @if($auditoria->registro_concluido=='Si')
                                                <a href="{{ route('asignaciondepartamento.accionesconsulta', $auditoria) }}" class="btn btn-light-primary"><i class="fa fa-magnifying-glass-chart"></i>Consultar</a>
                                            @endif    
                                        @endcan                                    
                                    </td>
                                    <td style="text-align: right!important;">
                                        {{ '$'.number_format( $auditoria->total(), 2) }}                                         
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($auditoria->departamento_encargado_id))
                                            <span class="badge-light-secondary text-gray-600">
                                                {{ $auditoria->departamento_encargado}} <br>
                                                {{ $auditoria->jefedepartamentoencargado->name}} <br>
                                                {{ $auditoria->jefedepartamentoencargado->puesto}} <br>
                                            </span>                                                    
                                        @else
                                            @can('asignaciondepartamentoencargado.edit')
                                                <a href="{{ route('asignaciondepartamentoencargado.edit',$auditoria) }}" class="btn btn-primary">
                                                    <i class="fa fa-handshake"></i> Asignar
                                                </a>
                                            @endcan 
                                        @endif                                                                                                                                           
                                    </td>                                                                      
                                    <td class="text-center">
                                            @can('asignaciondepartamento.edit')                                          
                                                @if ($auditoria->asignacion_departamentos=='Si'|| in_array("Titular Unidad de Seguimiento", auth()->user()->getRoleNames()->toArray()))
                                                    <a href="{{ route('asignaciondepartamento.edit',$auditoria) }}" class="btn btn-primary">
                                                        <i class="fa fa-magnifying-glass"></i> Consultar
                                                    </a>
                                                @else
                                                    @if (empty($auditoria->asignacion_departamentos)&&($auditoria->departamento_encargado_id))
                                                        <a href="{{ route('asignaciondepartamento.edit',$auditoria) }}" class="btn btn-primary">
                                                            <i class="fa fa-handshake"></i> Asignar
                                                        </a>
                                                    @endif 
                                                @endif                                               
                                            @endcan                                                                                                          
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
                <div class="pagination">
                    {{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
