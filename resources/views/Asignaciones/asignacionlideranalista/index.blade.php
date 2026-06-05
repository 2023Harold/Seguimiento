@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('asignacionlideranalista.index') }}
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
                {!!BootForm::open(['route'=>'asignacionlideranalista.index','method'=>'GET']) !!}
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
                        <div class="col-md-3">
                            {!!BootForm::radios("asignaciones", 'Auditorias: ',['Todas' => ' Todas', 'Asignadas'=>' Asignadas','Pendientes'=>' Pendientes'],
                                old('asignaciones', empty($request->asignaciones) ? 'Todas' : $request->asignaciones),true,['class'=>'i-checks']) !!}
                        </div> 
                        <div class="col-md-1 mt-8">
                            <button type="submit" class="btn btn-primary"><i class="align-middle fas fa-search" aria-hidden="true"></i>Buscar</button>                           
                        </div>
                    </div>
                {!!BootForm::close() !!}
				<div class="row">
					<div class="col-md-12">
						<div class="pagination float-end">
							{{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion,'asignaciones'=>$request->asignaciones])->links('vendor.pagination.bootstrap-5') }}
						</div>
					</div>
				</div>	
                <div class="row">
                    <div class="col-md-12">
                        <div class="float-end">
                            <a href="javascript:void(0)" id="btn-sincronizar" class="corner-button float-end">
                                <span class="cb-content text-primary">
                                    <i class="bi bi-arrow-repeat text-primary" style="font-size: 20px"></i>
                                        Sincronizar
                                </span>
                            </a>

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
                                @if(!usaEquipoTrabajo())
                                    @if(getSession('cp')==2023)
                                        <th>Asignación de lider y analista</th>                                                   
                                    @else       
                                        <th>Asignación de lider y analista</th>                                
                                    @endif     
                                @else
                                    <th colspan="4">Equipo de trabajo </th>
                                @endif
                            </tr>
                            @if(usaEquipoTrabajo())
                                <tr>
                                    <th colspan="6"></th>
                                    <th colspan="2">Lider</th>
                                    <th colspan="2">Analista</th>
                                </tr>
                            @endif
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                                <tr>
                                    <td>
                                        {{ $auditoria->numero_auditoria }}
                                    </td>
                                    <td>
                                        {{$auditoria->nombreentidad->entidades ?? "sin nombre"}}                                     
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
                                    @if(!usaEquipoTrabajo())
                                        @if(getSession('cp')==2023)
                                            @include('layouts.partials._asignacionlideranalista2023',$auditoria)                                                             
                                        @else       
                                            @include('layouts.partials._asignacionlideranalista2022')                                        
                                        @endif     
                                    @else
                                        <td>
                                            @if (!empty($auditoria->liderEquipo))
                                                @foreach ($auditoria->liderEquipo as $lider )
                                                    {{$lider->usuarioequipo->name ?? 'Sin asignar'}}<br>
                                                    {{$lider->usuarioequipo->puesto ?? 'Sin asignar'}}
                                                    @can('asignarequipotrabajo.eliminar')
                                                        <a href="{{ route('asignarequipotrabajo.eliminar', [$lider, $auditoria]) }}" class="btn btn-link btn-active-color-danger js-confirm-delete"
                                                            data-confirm-title="¿Desea eliminar al lider de la auditoría?"
                                                            data-confirm-text="Esta acción no se puede deshacer."
                                                            data-success-text="El lider se eliminó correctamente, recuerda que el historial se puede ver en el apartado de consulta.">
                                                                <span class="bi bi-trash-fill" style="font-size: 1rem;"></span>
                                                        </a>
                                                    @endcan
                                                    <br><br>
                                                    @if (!empty($lider))
                                                        {!! movimientosEnTd($lider->id."10", $lider->movimientos) !!}
                                                    @endif
                                                @endforeach
                                            @else
                                                Sin asignar
                                            @endif
                                        </td>
                                        <td >
                                            <a href="{{ route('asignarequipotrabajo.lider',$auditoria) }}" class="corner-button-success2 ">
                                                <span class="cb-content"><i class="fa fa-users text-primary" style="font-size: 20px" aria-hidden="true"></i><i class="bi bi-plus-square-fill text-primary"  style="font-size: 16px"aria-hidden="true"></i></span>
                                            </a>  
                                        </td>
                                        <td>
                                            @if (!empty($auditoria->analistaEquipo))
                                                @foreach ( $auditoria->analistaEquipo as $analista )
                                                    -{{optional($analista->usuarioequipo)->name ?? "Sin asignar" }}<br>
                                                    {{optional($analista->usuarioequipo)->puesto ?? "Sin asignar"}}
                                                    @can('asignarequipotrabajo.eliminar')
                                                        <a href="{{ route('asignarequipotrabajo.eliminar', [$analista, $auditoria]) }}" class="btn btn-link btn-active-color-danger js-confirm-delete"
                                                            data-confirm-title="¿Desea eliminar al analista de la auditoría?"
                                                            data-confirm-text="Esta acción no se puede deshacer."
                                                            data-success-text="El analista se eliminó correctamente, recuerda que el historial se puede ver en el apartado de consulta.">
                                                                <span class="bi bi-trash-fill" style="font-size: 1rem;"></span>
                                                        </a>
                                                    @endcan
                                                    <br><br>
                                                    
                                                @endforeach 
                                            @else
                                                Sin asignar
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('asignarequipotrabajo.analista',$auditoria) }}" class="corner-button-success2">
                                                <span class="cb-content"><i class="fa fa-users text-primary" style="font-size: 20px" aria-hidden="true"></i><i class="bi bi-plus-square-fill text-primary"  style="font-size: 16px"aria-hidden="true"></i></span>
                                            </a>  
                                        </td>
                                    @endif                                                                           
                                </tr> 
                                @if (!empty($auditoria->movimientosAsignacionLider))
                                    {!! movimientosDesgloseAsignacionesLA($auditoria->id,"12", $auditoria->movimientosAsignacionLider, $auditoria->movimientosAsignacionAnalista) !!}
                                @endif  
                                                                                        
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
                    {{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion,'asignaciones'=>$request->asignaciones])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        document.getElementById('btn-sincronizar').addEventListener('click', () => {
            Swal.fire({
                title: '¿Sincronizar equipo?',
                text: 'Se actualizarán todos los integrantes',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, sincronizar',
                customClass: {
					confirmButton: 'btn btn-sm btn-primary',
					cancelButton:  'btn btn-sm btn-danger'
				}
            }).then(result => {
                if (result.isConfirmed) {
                    fetch("{{ route('asignarequipotrabajo.sincronizarTodo') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => location.reload());
                }
            });
        });

    </script>
@endsection
