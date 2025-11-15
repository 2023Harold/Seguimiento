@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('notificaciones') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h1 class="card-title">
                        <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                        Notificaciones
                    </h1>
                </div>
                <div class="card-body">
                    {!!BootForm::open(['id' => 'form', 'method' => 'GET']) !!}
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            {!!BootForm::radios("estatus", 'Estatus: ',['Todos' => ' Todos', 'Pendiente'=>' No leído','Leído'=>' Leído'],
                                old('estatus', empty($request->estatus) ? 'Todos' : $request->estatus),true,['class'=>'i-checks']) !!}
                        </div>
                        <div class="col-md-3">
                            {!!BootForm::date('created_at', 'Fecha de recepción', old('created_at', $request->created_at)) !!}
                        </div>
                        <div class="col-md-3">
                            {!!BootForm::date('updated_at', 'Fecha de lectura', old('updated_at', $request->updated_at)) !!}
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            {!!BootForm::text('numero_auditoria', 'Número de Auditoría', old('numero_auditoria', $request->numero_auditoria)) !!}
                        </div>
						<div class="col-md-3">
                            {!!BootForm::radios("cuenta", 'Cuenta Pública: ',['Todas' => ' Todas', '|2022'=>' 2022','|2023'=>' 2023','|2024'=>' 2024'],
                                old('cuenta', empty($request->cuenta) ? 'Todas' : $request->cuenta),true,['class'=>'i-checks']) !!}
                        </div> 
						
						<div class="col-md-3 mt-8">
                            <button type="submit" class="btn btn-primary">Buscar</button>  
                        </div>
                    </div>
                    {!!BootForm::close() !!}
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cuenta Pública</th>
                                        <th>Asunto</th>
										<th>Mensaje</th>
                                        <th>Fecha y hora de recibido</th>
                                        <th>Estatus</th>
                                        <th>Fecha y hora de lectura</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($notificaciones as $notificacion)
                                        
                                        <tr id="rownotificacion{{ $notificacion->id }}">
											<td>{{ $notificacion->cp??'Sin registro'}}</td>
                                            <td>{{ $notificacion->titulo}}</td>
                                            <td>
												@if (!empty($notificacion->url))
													<label class="fs-6 text-primary fw-bold float-end">
														<a href="{{ route('notificacionurl.edit', $notificacion) }}"><i class="bi bi-box-arrow-in-up-right fa-2x text-primary float-end"></i></a>
													</label>
													<br>
												@endif
                                                @php
                                                    $partes = explode('<br>', $notificacion->mensaje);
                                                    $texto = $partes[1] ?? $notificacion->mensaje; // usa mensaje completo si no hay segunda parte
                                                @endphp
                                                @if(!empty($notificacion->auditoria_id)||!empty($notificacion->accion_id))
                                                    @button($texto, route('notificacionaccion.edit', $notificacion), '')
                                                @else
                                                    {{explode("<br>", $notificacion->mensaje)[1]}}
                                                @endif
												
                                            </td>
                                            <td>{{ fecha($notificacion->created_at, 'd/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                @if( $notificacion->estatus == 'Pendiente' ) 
														<!-- Checkbox para marcar como leído -->
														{!!BootForm::checkbox('notificacion' . $notificacion->id, false, $notificacion->id, old('notificacion' . $notificacion->id, $notificacion->estatus) == 'Leído' ? true : false, ['class' => 'i-checks mr-3 casilla', 'id' => 'notificacion' . $notificacion->id]) !!}
														<span class="badge badge-light-warning">No leído</span>                                                    
                                                @else
                                                    <span class="badge badge-light-success">{{$notificacion->estatus}}</span>
                                                @endif
                                            </td>
                                            <td class="fecha-leido">
                                                {{ $notificacion->estatus == 'Leído'? fecha($notificacion->updated_at, 'd/m/Y H:i') : 'Mensaje no leído' }}
                                            </td>
                                        </tr>
                                        
                                    @empty
                                        <tr>
                                            <td class='text-center' colspan="9"> No se han encontrado notificaciones.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="pagination justify-content-start">
                                {{ $notificaciones->appends(['estatus'=>$request->estatus,
															 'created_at'=>$request->created_at,
															 'updated_at'=>$request->updated_at,
															 'numero_auditoria'=>$request->numero_auditoria,
															 'cuenta'=>$request->cuenta])->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div> 
                    </div>
    
                </div>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function() {
    var total_notificaciones = '{{ !empty(auth()->user()->notificaciones) && count(auth()->user()->notificaciones) != 0 ? count(auth()->user()->notificaciones) : 0 }}';

    $('.casilla').on('ifChanged', function(event) {
        var idcheck = $(this).attr('id');
        var valor = $(this).val();

        $.ajax({
            url: "{{ route('marcarleido') }}",
            dataType: "json",
            method: 'GET',
            data: { id: valor },
            success: function(respuesta) {
                // Actualizamos el contador de notificaciones
                total_notificaciones = total_notificaciones - 1;
                
                $('#numero_notificaciones').text(total_notificaciones);
                $('#numero_notificaciones_badge').text(total_notificaciones);
                
                if (total_notificaciones == 1) {
                    $('#span-ntf').text('notificación');
                } else {
                    $('#span-ntf').text('notificaciones');
                }
				$(this).hide();
                // Recargar la página completa
                //window.location.reload();
            },
            error: function() {
                alert('Error al generar la petición');
            }
        });
    });
});

</script>
@endsection