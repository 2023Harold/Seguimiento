@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('inicioarchivotransferencia.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Auditorias
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                {!!BootForm::open(['route'=>'auditoriaseguimiento.index','method'=>'GET']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            {!!BootForm::text('numero_auditoria', "No. auditoría:", old('numero_auditoria', $request->numero_auditoria)) !!}
                        </div>
                        <div class="col-md-2">
                            {!!BootForm::text('entidad_fiscalizable', "Entidad fiscalizable:", old('entidad_fiscalizable', $request->entidad_fiscalizable)) !!}
                        </div>
                        <div class="col-md-2">
                            {!!BootForm::text('acto_fiscalizacion', "Acto de fiscalización:", old('acto_fiscalizacion', $request->acto_fiscalizacion)) !!}
                        </div>
                        <div class="col-md-6 mt-8">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                {!!BootForm::close() !!}
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
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th rowspan=1 colspan=2 style="width:20px" class="text-center"> Expediente Técnico de la Auditoría</th>
                                <th rowspan=1 colspan=2 style="width:20px" class="text-center"> Expediente de Seguimiento</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                
                        </tr>
                        <tr>                              
                                <th>No. de auditoría</th>
                                <th>Entidad fiscalizable</th>
                                <th>Acto de fiscalización</th>
                                <th>Informe de auditoría</th>
                                <th>Número de legajos</th>
                                <th>Número de fojas</th>
                                <th>Número de legajos</th>
                                <th>Número de fojas</th>
                                <th>Relación de expedientes al archivo</th>
                                <th>Fecha de entrega</th>                                
                                <th>Archivo Transferencia</th>
                                {{-- @if(getSession('cp')==2023)
                                <th>Acciones</th>
                                @endif --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                                <tr>
                                    <td>
                                        {{ $auditoria->numero_auditoria }}
                                    </td>
                                    <td  width='20%'>
                                        @php
                                            $entidadparciales = explode("-", $auditoria->entidad_fiscalizable);
                                        @endphp
                                        @foreach ($entidadparciales as $entidadparcial)
                                            {{ mb_convert_encoding(mb_convert_case(strtolower($entidadparcial), MB_CASE_TITLE), "UTF-8") }}<br>
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
                                    {{$auditoria->turnoarchivo->legajos_tecnico_archivo }}
                                </td>
                                <td class="text-center">
                                    {{$auditoria->turnoarchivo->fojas_tecnico_archivo }}
                                </td>
                                <td class="text-center">
                                    {{$auditoria->turnoarchivo->legajos_seg_archivo }}
                                </td>
                                <td class="text-center">
                                    {{$auditoria->turnoarchivo->fojas_seg_archivo }}
                                </td>
                                <td class="text-center">
                                    @btnFile($auditoria->turnoarchivo->turno_archivo)<br>
                                    <small>{{ fecha($auditoria->turnoarchivo->fecha_notificacion_archivo) }} <small>
                                </td>
                                <td class="text-center">
                                    {{ fecha($auditoria->turnoarchivo->fecha_turno_archivo) }}
                                </td>                                                                                                                                             
                                <td class="text-center">                                                        
                                    @if (empty($auditoria->turnoarchivotransferencia))      
                                    {{-- {{ dd($auditoria->turnoarchivotransferencia) }} --}}
                                    <a href="{{ route('inicioarchivotransferencia.edit', $auditoria) }}" class="btn btn-primary">Ingresar</a>                                      
                                    @else
                                    {{-- {{ dd($auditoria->turnoarchivotransferencia) }}                                                                               --}}
                                        <a href="{{ route('inicioarchivotransferencia.edit',$auditoria) }}" class="btn btn-secondary">Consultar</a>  
                                    @endif    
                                </td>
                                
                                    {{-- <td class="text-center">
                                        <a href="{{ route('seleccionarauditoria.auditoria', $auditoria) }}" class="btn btn-primary">Agregar</a>
                                    </td>
{{-- revision --}}
                                    {{-- @if(getSession('cp')==2023)
                                      <td class="text-center">
                                            @can('seleccionarauditoria.auditoria')
                                                <a href="{{ route('turnotransferencia.auditoria',$auditoria) }}"class="btn btn-primary">
                                                  Ingresar
                                                </a>
                                            @endcan
                                    </td>
                                    @endif --}}
{{-- fin del flujo --}}
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
