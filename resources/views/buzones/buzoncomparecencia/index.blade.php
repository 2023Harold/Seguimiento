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
                {!!BootForm::open(['route'=>'buzoncomparecencia.index','method'=>'GET']) !!}
                    <div class="row">
                        {!!BootForm::hidden('apartado',"comparecencia") !!}
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
                                '' => 'Todos',
                                'Rechazado' => 'Rechazado',
                                'En revisión' => 'En revisión',
                                'En validación' => 'En validación',
                                'En autorización' => 'En autorización',
                                'Autorizado' => 'Autorizado',
                            ], old('estatus', $request->estatus),['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
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
                                <th class="text-center ">Comparecencia</th>
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
                                    @php
                                        $COM = "COM-".$auditoria->id;
                                    @endphp   
                                    <td>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Acta de comparecencia</th>
                                                    @if(!empty($auditoria->comparecencia->oficio_designacion))
                                                    <th>Oficio de designación</th>
                                                    @endif
                                                    <th>Fase / Acción</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($auditoria->comparecencia->oficio_acta))
                                                    <tr>
                                                        <td class="text-center">
                                                            @if (!empty($auditoria->comparecencia->oficio_acta))
                                                            <a href="{{ asset($auditoria->comparecencia->oficio_acta) }}" target="_blank">
                                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acta)) ?>
                                                            </a><br>
                                                            <small>{{ 'No. '.$auditoria->comparecencia->numero_acta }}</small><br>
                                                            <small>{{ fecha($auditoria->comparecencia->fecha_cedula) }}</small>
                                                            @endif
                                                        </td>
                                                        @if(!empty($auditoria->comparecencia->oficio_designacion))
                                                        <td class="text-center">
                                                            @if (!empty($auditoria->comparecencia->oficio_designacion))
                                                            <a href="{{ asset($auditoria->comparecencia->oficio_designacion) }}"
                                                                target="_blank">
                                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_designacion)) ?>
                                                            </a><br>
                                                            <small>{{ fecha($auditoria->comparecencia->fecha_oficio_designacion) }}</small>
                                                            @endif
                                                        </td>
                                                        @endif
                                                        <td class="text-center">
                                                            @if (empty($auditoria->comparecencia->fase_autorizacion)||$auditoria->comparecencia->fase_autorizacion=='Rechazado')
                                                                <span class="badge badge-light-danger">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                            @endif
                                                            @if(getSession('cp')!=2023)
                                                                @if ($auditoria->comparecencia->fase_autorizacion == 'En validación')
                                                                        <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                                @elseif($auditoria->comparecencia->fase_autorizacion == 'En revisión')
                                                                        <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                                @endif
                                                            @endif
                                                            @if ($auditoria->comparecencia->fase_autorizacion == 'En autorización')
                                                                <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                            @endif
                                                            @if ($auditoria->comparecencia->fase_autorizacion=='Autorizado')
                                                                <span class="badge badge-light-success">{{ $auditoria->radicacion->fase_autorizacion }} </span> </br>
                                                            @endif
                                                                @if(getSession('cp')==2023)
                                                                    @if ($auditoria->comparecencia->fase_autorizacion == 'En validación')
                                                                            <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                                    @elseif($auditoria->comparecencia->fase_autorizacion == 'En revisión')
                                                                            <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                                    @endif
                                                                @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('buzonseg.show', $COM) }}" class="corner-button">
                                                            <span class="cb-content">Ir a Comparecencia<i class="bi bi-arrow-up-right-circle-fill text-primary fs-1" aria-hidden="true"></i></span>
                                                        </a>
                                                        <!--@if (!empty($auditoria->comparecencia))
                                                                {!! movimientosEnTd($auditoria->comparecencia->id."2", $auditoria->comparecencia->movimientos) !!}
                                                            @endif -->
                                                    </td>
                                                </tr>
                                                @if (!empty($auditoria->comparecencia))
                                                    {!! movimientosDesglose($auditoria->comparecencia->id, 10, $auditoria->comparecencia->movimientos) !!}
                                                @endif
                                                @else
                                                <tr>
                                                    <td class="text-center" colspan="2">
                                                        No hay registros en éste apartado.
                                                    </td>
                                                </tr>
                                                @endif
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