@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('buzonseg.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            {{--}<div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Auditorias
                </h1>
            </div>--}}
            <div class="card-body">
                @include('layouts.partials.Buzones._tabsbuzonseguimiento')
                @include('layouts.partials.Buzones._tabsbuzonseguimientoapartados')
                @include('flash::message')
                {!!BootForm::open(['route'=>'buzonrecomendaciones.index','method'=>'GET']) !!}
                    <div class="row">
                        {!!BootForm::hidden('apartado',"recomendaciones") !!}
                        <div class="col-md-2">
                            {!!BootForm::text('numero_auditoria', "No. auditoría:", old('numero_auditoria', $request->numero_auditoria)) !!}
                        </div>
                        <div class="col-md-2">
                            {!!BootForm::text('entidad_fiscalizable', "Entidad fiscalizable:", old('entidad_fiscalizable', $request->entidad_fiscalizable)) !!}
                        </div>
                        <div class="col-md-2">
                            {!!BootForm::text('acto_fiscalizacion', "Acto de fiscalización:", old('acto_fiscalizacion', $request->acto_fiscalizacion)) !!}
                        </div>
                        <div class="col-md-2">
                            {!!BootForm::select('estatus', 'Estatus:', [
                                '' => '',
                                'Rechazado' => 'Rechazado',
                                'En revisión' => 'En revisión',
                                'En validación' => 'En validación',
                                'En autorización' => 'En autorización',
                                'Autorizado' => 'Autorizado',
                            ], old('estatus' ),['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                        </div>
                        <div class="col-md-3 mt-8">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                {!!BootForm::close() !!}
				<div class="row">
					<div class="col-md-12">
						<div class="pagination float-end">
							{{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion, 'departamentoasig'=>$request->departamentoasig, 'liderasig'=>$request->liderasig, 'analistaasig'=>$request->analistaasig, 'direccionaud'=>$request->direccionaud,'estatus'=>$request->estatus,'apartado'=>$request->apartado])->links('vendor.pagination.bootstrap-5') }}
						</div>
					</div>
				</div>	
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Auditoria</th>
                                <th class="text-center ">Recomendaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                                <tr>
                                    <td class="col-md-2">
                                        @php
                                            $AUD = "AUD-".$auditoria->id;
                                        @endphp
                                        <a href="{{ route('buzonseg.show', $AUD) }}">
                                            -{{ $auditoria->numero_auditoria }}
                                        </a><br>
                                        <small>{{$auditoria->entidad_fiscalizable}}</small>
                                    </td>
                                    <td rowspan=1 colspan=2 style="width:20px" class="">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>No. consecutivo</th>
                                                    <th>No. de acción</th>
                                                    <th>Monto por aclarar</th>
                                                    <th>Fecha compromiso de atención</th>
                                                    <th>Nombre y cargo del responsable de la entidad fiscalizada</th>
                                                    <th>Calificación</th>
                                                    <th>Fase</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                @forelse ($auditoria->accionesrecomendaciones as $accion)
                                                @php
                                                    $REC = "AUD-".$auditoria->id."-ACC-".$accion->id."-REC-".$accion->recomendaciones->id;
                                                    //dd($accion->recomendaciones);
                                                @endphp
                                                <tr>
                                                    <td class="text-center">
                                                        {{$loop->iteration}}
                                                    </td>
                                                    <td>
                                                        {{ $accion->numero }}
                                                    </td>
                                                    <td style="text-align: right!important;">
                                                        {{'$'.number_format( $accion->monto_aclarar, 2)}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ fecha($accion->fecha_termino_recomendacion) }}
                                                    </td>
                                                    <td>
                                                        -{{ $accion->recomendaciones->auditoria->comparecencia->nombre_titular }}<br>
                                                        -{{ $accion->recomendaciones->auditoria->comparecencia->cargo_titular }}
                                                    </td>
                                                    <td class="text-center">
                                                        
                                                        @if (!empty($accion->recomendaciones->calificacion_atencion))
                                                            <a href="{{ route('recomendacionescalificacion.show',$recomendacion) }}" class="btn btn-link btn-color-muted btn-active-color-primary">
                                                                @if ($accion->recomendaciones->calificacion_atencion=='Atendida')
                                                                    <span class="badge badge-light-success">Atendida</span><br>
                                                                @endif
                                                                @if ($accion->recomendaciones->calificacion_atencion=='No Atendida')
                                                                    <span class="badge badge-light-danger">No Atendida</span><br>
                                                                @endif
                                                                @if ($accion->recomendaciones->calificacion_atencion=='Parcialmente Atendida')
                                                                    <span class="badge badge-light-warning">Parcialmente Atendida</span><br>
                                                                @endif
                                                                <span class="fa fa-align-justify" aria-hidden="true"></span>
                                                            </a>
                                                        @else
                                                            @if (!empty($accion->recomendaciones->calificacion_sugerida))
                                                                @if ($accion->recomendaciones->calificacion_sugerida=='Atendida')
                                                                    <span class="badge badge-light-success">Atendida</span><br>
                                                                @endif
                                                                @if ($accion->recomendaciones->calificacion_sugerida=='No Atendida')
                                                                    <span class="badge badge-light-danger">No Atendida</span><br>
                                                                @endif
                                                                @if ($accion->recomendaciones->calificacion_sugerida=='Parcialmente Atendida')
                                                                    <span class="badge badge-light-warning">Parcialmente Atendida</span><br>
                                                                @endif
                                                            @endif
                                                        @endif

                                                    </td>
                                                    <td class="text-center">
                                                        @if (empty($accion->recomendaciones->fase_autorizacion))
                                                            <span class="badge badge-light-warning">Pendiente</span>                                 
                                                        @elseif ($accion->recomendaciones->fase_autorizacion == 'Rechazado')
                                                            <span class="badge badge-light-danger">{{ $accion->recomendaciones->fase_autorizacion }}</span>                                      
                                                        @elseif ($accion->recomendaciones->fase_autorizacion == 'En revisión 01')                                        
                                                            <span class="badge badge-light-warning">En revisión</span>
                                                        @elseif ($accion->recomendaciones->fase_autorizacion == 'En revisión')
                                                            <span class="badge badge-light-warning">{{ $accion->recomendaciones->fase_autorizacion }} </span>
                                                        @elseif ($accion->recomendaciones->fase_autorizacion == 'En validación')
                                                            <span class="badge badge-light-warning">{{ $accion->recomendaciones->fase_autorizacion }} </span>
                                                        @elseif ($accion->recomendaciones->fase_autorizacion == 'En autorización')
                                                            <span class="badge badge-light-warning">{{ $accion->recomendaciones->fase_autorizacion }} </span>
                                                        @elseif ($accion->recomendaciones->fase_autorizacion=='Autorizado')
                                                            <span class="badge badge-light-success">{{ $accion->recomendaciones->fase_autorizacion }} </span>                                         
                                                        @endif 
                                                        
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('buzonseg.show', $REC) }}" class="corner-button">
                                                            <span class="cb-content">Recomendación<i class="bi bi-arrow-up-right-circle-fill text-primary fs-1" aria-hidden="true"></i></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td class="text-center" colspan="5">
                                                        No se encuentran registros en este apartado.
                                                    </td>                                
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        
                                           
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="11">
                                        <span class='text-center'>No hay registros en éste apartado</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination float-end">
					{{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion, 'departamentoasig'=>$request->departamentoasig, 'liderasig'=>$request->liderasig, 'analistaasig'=>$request->analistaasig, 'direccionaud'=>$request->direccionaud,'estatus'=>$request->estatus,'apartado'=>$request->apartado])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
</script>
@endsection