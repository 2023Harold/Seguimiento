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
                {!!BootForm::open(['route'=>'buzonseg.index','method'=>'GET']) !!}
                    <div class="row">    
                        <div class="col-md-3">{!!BootForm::select('apartado', 'Apartado:', [
                                '' => 'Auditorías',
                                'pras' => 'PRAS',
                                'recomendaciones' => 'Recomendaciones',
                                'pliegos' => 'Pliego de Observaciones',
                                'solicitudes' => 'Solicitudes de aclaración',
                            ], old('apartado', $request->apartado), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                        </div>
                    </div>
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
                                <th>Radicacion</th>
                                <th>Comparecencia</th>
                                <th rowspan=1 colspan=2 style="width:20px" class="text-center ">Acuerdo de Conclusión</th>
                                <th rowspan=1 colspan=2 style="width:20px" class="text-center ">Informes</th>
                                <th>Turno UI</th>
                                <th>Turno OIC</th>
                                <th>Turno envío archivo</th>
                            </tr>
                            <tr >
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Acuerdo de Recomendaciones</th>
                                <th>Acuerdo de Pliegos</th>
                                <th>Informe de Recomendaciones</th>
                                <th>Informe de Pliegos</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                                <tr>
                                    <td class="">
                                        @php
                                            $AUD = "AUD-".$auditoria->id;
                                        @endphp
                                        <a href="{{ route('buzonseg.show', $AUD) }}">
                                            {{ $auditoria->numero_auditoria }}
                                        </a><br>
                                        <small>{{$auditoria->entidad_fiscalizable}}</small>
                                    </td>
                                    
                                    <td class="text-center">
                                        @php
                                            $RAD = "RAD-".$auditoria->id;
                                        @endphp
                                        @if (!empty($auditoria->radicacion) &&($auditoria->radicacion->fase_autorizacion == 'En revisión'))
                                            <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $RAD) }}" class="corner-button corner-button--icon">
                                                 <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->radicacion) &&($auditoria->radicacion->fase_autorizacion == 'En validación'))
                                            <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $RAD) }}" class="corner-button corner-button--icon">
                                                 <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->radicacion) &&($auditoria->radicacion->fase_autorizacion == 'En autorización'))
                                            <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $RAD) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->radicacion) &&($auditoria->radicacion->fase_autorizacion == 'Autorizado'))
                                            <span class="badge badge-light-success">{{ $auditoria->radicacion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $RAD) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->radicacion) &&($auditoria->radicacion->fase_autorizacion == 'Rechazado'))
                                            <span class="badge badge-light-danger">{{ $auditoria->radicacion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $RAD) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                    
                                            <!-- Compacto con texto 
                                            <a href="#guardar" class="corner-button corner-button--sm">
                                            <span class="cb-content">Guardar</span>
                                            </a>
                                                                    
                                            <!-- Solo ícono 
                                            <a href="#buscar" class="corner-button corner-button--icon" aria-label="Buscar">
                                            <span class="cb-content"><i class="bi bi-search" aria-hidden="true"></i></span>
                                            </a>

                                            <!-- Normal con ícono + texto 
                                            <a href="#descargar" class="corner-button">
                                            <span class="cb-content"><i class="bi bi-download" aria-hidden="true"></i> Descargar</span>
                                            </a>-->
                                        @else
                                            <span class="badge badge-light-secondary">Sin estatus</span>
                                        @endif
                                        @if (!empty($auditoria->radicacion))
                                            {!! movimientosEnTd($auditoria->radicacion->id."1", $auditoria->radicacion->movimientos) !!}
                                        @endif
                                           
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $COM = "COM-".$auditoria->id;
                                        @endphp
                                        @if (!empty($auditoria->comparecencia) &&($auditoria->comparecencia->fase_autorizacion == 'En revisión'))
                                            <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $COM) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->comparecencia) &&($auditoria->comparecencia->fase_autorizacion == 'En validación'))
                                            <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $COM) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->comparecencia) &&($auditoria->comparecencia->fase_autorizacion == 'En autorización'))
                                            <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $COM) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->comparecencia) &&($auditoria->comparecencia->fase_autorizacion == 'Autorizado'))
                                            <span class="badge badge-light-success">{{ $auditoria->comparecencia->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $COM) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->comparecencia) &&($auditoria->comparecencia->fase_autorizacion == 'Rechazado'))
                                            <span class="badge badge-light-danger">{{ $auditoria->comparecencia->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $COM) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @else
                                            <span class="badge badge-light-secondary">Sin estatus</span>
                                        @endif
                                        @if (!empty($auditoria->comparecencia))
                                            {!! movimientosEnTd($auditoria->comparecencia->id."2", $auditoria->comparecencia->movimientos) !!}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $ACR = "ACR-".$auditoria->id;
                                        @endphp
                                        @if (!empty($auditoria->acuerdoconclusion) &&($auditoria->acuerdoconclusion->fase_autorizacion == 'En revisión'))
                                            <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ACR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->acuerdoconclusion) &&($auditoria->acuerdoconclusion->fase_autorizacion == 'En validación'))
                                            <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ACR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->acuerdoconclusion) &&($auditoria->acuerdoconclusion->fase_autorizacion == 'En autorización'))
                                            <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ACR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->acuerdoconclusion) &&($auditoria->acuerdoconclusion->fase_autorizacion == 'Autorizado'))
                                            <span class="badge badge-light-success">{{ $auditoria->acuerdoconclusion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ACR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->acuerdoconclusion) &&($auditoria->acuerdoconclusion->fase_autorizacion == 'Rechazado'))
                                            <span class="badge badge-light-danger">{{ $auditoria->acuerdoconclusion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ACR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @else
                                            <span class="badge badge-light-secondary">Sin estatus</span>
                                        @endif
                                        @if (!empty($auditoria->acuerdoconclusion))
                                            {!! movimientosEnTd($auditoria->acuerdoconclusion->id."3", $auditoria->acuerdoconclusion->movimientos) !!}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $ACP = "ACP-".$auditoria->id;
                                        @endphp
                                        @if (!empty($auditoria->acuerdoconclusionpliegos) &&($auditoria->acuerdoconclusionpliegos->fase_autorizacion == 'En revisión'))
                                            <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusionpliegos->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ACP) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->acuerdoconclusionpliegos) &&($auditoria->acuerdoconclusionpliegos->fase_autorizacion == 'En validación'))
                                            <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusionpliegos->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ACP) }}"class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->acuerdoconclusionpliegos) &&($auditoria->acuerdoconclusionpliegos->fase_autorizacion == 'En autorización'))
                                            <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusionpliegos->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ACP) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->acuerdoconclusionpliegos) &&($auditoria->acuerdoconclusionpliegos->fase_autorizacion == 'Autorizado'))
                                            <span class="badge badge-light-success">{{ $auditoria->acuerdoconclusionpliegos->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ACP) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->acuerdoconclusionpliegos) &&($auditoria->acuerdoconclusionpliegos->fase_autorizacion == 'Rechazado'))
                                            <span class="badge badge-light-danger">{{ $auditoria->acuerdoconclusionpliegos->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ACP) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @else
                                            <span class="badge badge-light-secondary">Sin estatus</span>
                                        @endif
                                        @if (!empty($auditoria->acuerdoconclusionpliegos))
                                            {!! movimientosEnTd($auditoria->acuerdoconclusionpliegos->id."4", $auditoria->acuerdoconclusionpliegos->movimientos) !!}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $ISR = "ISR-".$auditoria->id;
                                        @endphp
                                        @if (!empty($auditoria->informeprimeraetapa) &&($auditoria->informeprimeraetapa->fase_autorizacion == 'En revisión'))
                                            <span class="badge badge-light-warning">{{ $auditoria->informeprimeraetapa->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ISR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->informeprimeraetapa) &&($auditoria->informeprimeraetapa->fase_autorizacion == 'En validación'))
                                            <span class="badge badge-light-warning">{{ $auditoria->informeprimeraetapa->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ISR) }}"class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->informeprimeraetapa) &&($auditoria->informeprimeraetapa->fase_autorizacion == 'En autorización'))
                                            <span class="badge badge-light-warning">{{ $auditoria->informeprimeraetapa->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ISR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->informeprimeraetapa) &&($auditoria->informeprimeraetapa->fase_autorizacion == 'Autorizado'))
                                            <span class="badge badge-light-success">{{ $auditoria->informeprimeraetapa->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ISR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->informeprimeraetapa) &&($auditoria->informeprimeraetapa->fase_autorizacion == 'Rechazado'))
                                            <span class="badge badge-light-danger">{{ $auditoria->informeprimeraetapa->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ISR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @else
                                            <span class="badge badge-light-secondary">Sin estatus</span>
                                        @endif
                                        @if (!empty($auditoria->informeprimeraetapa))
                                            {!! movimientosEnTd($auditoria->informeprimeraetapa->id."5", $auditoria->informeprimeraetapa->movimientos) !!}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $ISR = "ISP-".$auditoria->id;
                                        @endphp
                                        @if (!empty($auditoria->informepliegos) &&($auditoria->informepliegos->fase_autorizacion == 'En revisión'))
                                            <span class="badge badge-light-warning">{{ $auditoria->informepliegos->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ISR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->informepliegos) &&($auditoria->informepliegos->fase_autorizacion == 'En validación'))
                                            <span class="badge badge-light-warning">{{ $auditoria->informepliegos->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ISR) }}"class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->informepliegos) &&($auditoria->informepliegos->fase_autorizacion == 'En autorización'))
                                            <span class="badge badge-light-warning">{{ $auditoria->informepliegos->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ISR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->informepliegos) &&($auditoria->informepliegos->fase_autorizacion == 'Autorizado'))
                                            <span class="badge badge-light-success">{{ $auditoria->informepliegos->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ISR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->informepliegos) &&($auditoria->informepliegos->fase_autorizacion == 'Rechazado'))
                                            <span class="badge badge-light-danger">{{ $auditoria->informepliegos->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $ISR) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @else
                                            <span class="badge badge-light-secondary">Sin estatus</span>
                                        @endif
                                        @if (!empty($auditoria->informepliegos))
                                            {!! movimientosEnTd($auditoria->informepliegos->id."6", $auditoria->informepliegos->movimientos) !!}
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        @php
                                            $TUI = "TUI-".$auditoria->id;
                                        @endphp
                                        @if (!empty($auditoria->turnoui) &&($auditoria->turnoui->fase_autorizacion == 'En revisión'))
                                            <span class="badge badge-light-warning">{{ $auditoria->turnoui->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TUI) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnoui) &&($auditoria->turnoui->fase_autorizacion == 'En validación'))
                                            <span class="badge badge-light-warning">{{ $auditoria->turnoui->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TUI) }}"class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnoui) &&($auditoria->turnoui->fase_autorizacion == 'En autorización'))
                                            <span class="badge badge-light-warning">{{ $auditoria->turnoui->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TUI) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnoui) &&($auditoria->turnoui->fase_autorizacion == 'Autorizado'))
                                            <span class="badge badge-light-success">{{ $auditoria->turnoui->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TUI) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnoui) &&($auditoria->turnoui->fase_autorizacion == 'Rechazado'))
                                            <span class="badge badge-light-danger">{{ $auditoria->turnoui->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TUI) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @else
                                            <span class="badge badge-light-secondary">Sin estatus</span>
                                        @endif
                                        @if (!empty($auditoria->turnoui))
                                            {!! movimientosEnTd($auditoria->turnoui->id."7", $auditoria->turnoui->movimientos) !!}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $TOIC = "TOIC-".$auditoria->id;
                                        @endphp
                                        @if (!empty($auditoria->turnooic) &&($auditoria->turnooic->fase_autorizacion == 'En revisión'))
                                            <span class="badge badge-light-warning">{{ $auditoria->turnooic->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TOIC) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnooic) &&($auditoria->turnooic->fase_autorizacion == 'En validación'))
                                            <span class="badge badge-light-warning">{{ $auditoria->turnooic->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TOIC) }}"class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnooic) &&($auditoria->turnooic->fase_autorizacion == 'En autorización'))
                                            <span class="badge badge-light-warning">{{ $auditoria->turnooic->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TOIC) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnooic) &&($auditoria->turnooic->fase_autorizacion == 'Autorizado'))
                                            <span class="badge badge-light-success">{{ $auditoria->turnooic->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TOIC) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnooic) &&($auditoria->turnooic->fase_autorizacion == 'Rechazado'))
                                            <span class="badge badge-light-danger">{{ $auditoria->turnooic->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TOIC) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @else
                                            <span class="badge badge-light-secondary">Sin estatus</span>
                                        @endif
                                        @if (!empty($auditoria->turnooic))
                                            {!! movimientosEnTd($auditoria->turnooic->id."8", $auditoria->turnooic->movimientos) !!}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $TARCH = "TARCH-".$auditoria->id;
                                        @endphp
                                        @if (!empty($auditoria->turnoarchivo) &&($auditoria->turnoarchivo->fase_autorizacion == 'En revisión'))
                                            <span class="badge badge-light-warning">{{ $auditoria->turnoarchivo->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TARCH) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnoarchivo) &&($auditoria->turnoarchivo->fase_autorizacion == 'En validación'))
                                            <span class="badge badge-light-warning">{{ $auditoria->turnoarchivo->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TARCH) }}"class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnoarchivo) &&($auditoria->turnoarchivo->fase_autorizacion == 'En autorización'))
                                            <span class="badge badge-light-warning">{{ $auditoria->turnoarchivo->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TARCH) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnoarchivo) &&($auditoria->turnoarchivo->fase_autorizacion == 'Autorizado'))
                                            <span class="badge badge-light-success">{{ $auditoria->turnoarchivo->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TARCH) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @elseif(!empty($auditoria->turnoarchivo) &&($auditoria->turnoarchivo->fase_autorizacion == 'Rechazado'))
                                            <span class="badge badge-light-danger">{{ $auditoria->turnoarchivo->fase_autorizacion ?? 'Sin estatus' }}</span>
                                            <a href="{{ route('buzonseg.show', $TARCH) }}" class="corner-button corner-button--icon">
                                                <span class="cb-content"> <i class="bi bi-arrow-up-right-circle-fill text-primary" aria-hidden="true"></i></span>
                                            </a>
                                        @else
                                            <span class="badge badge-light-secondary">Sin estatus</span>
                                        @endif
                                        @if (!empty($auditoria->turnoarchivo))
                                            {!! movimientosEnTd($auditoria->turnoarchivo->id."9", $auditoria->turnoarchivo->movimientos) !!}
                                        @endif
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