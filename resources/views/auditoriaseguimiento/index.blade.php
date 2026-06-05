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
                        @if(auth()->user()->siglas_rol=='TUS' && getSession('cp')!=2022)
                            <div class="col-md-3">
                            {!!BootForm::radios("direccionaud", 'Dirección: ',['Todas' => ' Todas','122100'=>'Direccion A','122200'=>'Direccion B'],
                                old('direccionaud', empty($request->direccionaud) ? 'Todas' : $request->direccionaud),true,['class'=>'i-checks']) !!}
                            </div> 
                        @endif
                        
                    </div>
                    <div class="row">
                        
                        @if((auth()->user()->siglas_rol=='TUS' || auth()->user()->siglas_rol=='DS')&& getSession('cp')!=2022 )
                            <div class="col-md-3">
                                {!!BootForm::select('departamentoasig', 'Departamento: ', $departamentos , old('departamentoasig'), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                            </div>
                        @endif
                        @if((auth()->user()->siglas_rol=='TUS' || auth()->user()->siglas_rol=='DS' || auth()->user()->siglas_rol=='JD')&& getSession('cp')!=2022 )
                            <div class="col-md-3">
                                {!!BootForm::select('liderasig', 'Lideres: ', $lideres , old('liderasig'), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                            </div>
                            <div class="col-md-3">
                                {!!BootForm::select('analistaasig', 'Analistas: ', $analistas , old('analistaasig'), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                            </div>
                        @endif
                        <div class="col-md-1 mt-8">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                {!!BootForm::close() !!}
				<div class="row">
					<div class="col-md-12">
						<div class="pagination float-end">
							{{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion, 'direccionaud'=>$request->direccionaud])->links('vendor.pagination.bootstrap-5') }}
						</div>
					</div>
				</div>	
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                @if(auth()->user()->siglas_rol=='TUS' || auth()->user()->siglas_rol=='DS' || auth()->user()->siglas_rol=='JD' && getSession('cp')!=2022)
                                    <th>Encargados</th>
                                @endif
                                <th>No. de auditoría</th>
                                <th>Entidad fiscalizable</th>
                                <th>Acto de fiscalización</th>
                                <th>Informe de auditoría</th>
                                <th>Acciones promovidas</th>
                                <th>Monto por aclarar</th>
                                <th>Seguimiento</th>
                                @if(getSession('cp')!=2022)
                                	<th>Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                                <tr>
                                    @if((auth()->user()->siglas_rol=='TUS' || auth()->user()->siglas_rol=='DS' || auth()->user()->siglas_rol=='JD') && getSession('cp')!=2022)
                                        <td>
                                            <button class="btn btn-sm btn-light-primary"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#equipo-{{ $auditoria->id }}">
                                                <i class="fa fa-users"></i> Ver equipo
                                            </button>
                                            <div id="equipo-{{ $auditoria->id }}" class="collapse mt-3">
                                                @include('layouts.partials._equipostrabajoseguimiento', ['auditoria' => $auditoria])
                                            </div>
                                        </td>
                                    @endif
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
                                        <a href="{{ route('auditoriaseguimiento.edit', $auditoria) }}" class="btn btn-primary">Ingresar</a>
                                    </td>
                                    {{-- <td class="text-center">
                                        <a href="{{ route('seleccionarauditoria.auditoria', $auditoria) }}" class="btn btn-primary">Agregar</a>
                                    </td> --}}
{{-- revision --}}
                                    @if(getSession('cp')!=2022)                                   
                                      <td class="text-center">   
                                            @can('seleccionarauditoria.auditoria')                                         
                                                <a href="{{ route('seleccionarauditoria.auditoria',$auditoria) }}"class="btn btn-primary">
                                                  Ingresar
                                                </a>  
                                            @endcan                                                                                                                                               
                                    </td>         
                                    @endif
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
                    {{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion, 'direccionaud'=>$request->direccionaud])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

