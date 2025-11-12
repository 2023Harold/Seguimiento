@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('auditoriaseguimiento.index') }}
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
                {!!BootForm::open(['route'=>'auditoriaseguimiento2024.index','method'=>'GET']) !!}
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
                                <th>No. de auditoría</th>
                                <th>Entidad fiscalizable</th>
                                <th>Acto de fiscalización</th>
                                <th>Informe de auditoría</th>
                                <th>Acciones promovidas</th>
                                <th>Monto por aclarar</th>
                                <th>Auditoria</th>
                                @if(getSession('cp')==2024)
                                <th>Acciones</th>
                                @endif
                                <th>Seguimiento</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                                <tr>
                                    <td>
                                        {{ $auditoria->numero_auditoria }}
                                    </td>
                                    <td  width='40%'>
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
                                        @if(count($auditoria->acciones)>0)
                                            @if(!empty($auditoria->fase_autorizacion) && auth()->user()->siglas_rol!='ANA')
                                                <a href="{{ route('auditoriaseguimiento.accionesconsulta', $auditoria) }}" class="btn btn-secondary">Consultar</a>
                                            @endif
                                            @if(!empty($auditoria->fase_autorizacion) && $auditoria->fase_autorizacion!='Rechazado' && auth()->user()->siglas_rol=='ANA')
                                                <a href="{{ route('auditoriaseguimiento.accionesconsulta', $auditoria) }}" class="btn btn-secondary">Consultar</a>
                                            @endif
                                        @endif
                                    </td>                                    
                                    <td style="text-align: right!important;">
                                        {{ '$'.number_format( $auditoria->total(), 2) }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('seguimientoauditoriacp.edit', $auditoria) }}" class="btn btn-primary">Concluir</a>
                                    </td>                                                                      
{{-- revision --}}
                                    @if(getSession('cp')==2024)                                   
                                      <td class="text-center">   
                                            @can('seleccionarauditoria.auditoria')                                         
                                                <a href="{{ route('seleccionarauditoria.auditoria',$auditoria) }}"class="btn btn-primary">
                                                  Ingresar
                                                </a>  
                                            @endcan                                                                                                                                               
                                    </td>         
                                    @endif
{{-- fin del flujo --}}
                                    <td class="text-center">
                                        <a href="{{ route('auditoriaseguimiento.edit', $auditoria) }}" class="btn btn-primary">Ingresar</a>
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
