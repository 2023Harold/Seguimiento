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
                {!!BootForm::open(['route'=>'buzonradicacion.index','method'=>'GET']) !!}
                    <div class="row">
                        {!!BootForm::hidden('apartado',"radicacion") !!}
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
                                'todos' => 'Todos',
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
                                <th class="text-center ">Radicacion</th>
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
                                        $RAD = "RAD-".$auditoria->id;
                                    @endphp
                                    <td rowspan=1 colspan=2 style="width:20px" class="">
                                        <table class="table">
                                            <thead>
                                                <tr>  
                                                    <th>Número de expediente</th>
                                                    @if(getSession('cp')!=2022)
                                                        <th>Oficio de notificación de acuerdos</th>
                                                    @elseif(getSession('cp')==2022)
                                                        <th>Número de oficio de notificación del informe de auditoria</th>
                                                    @endif
                                                    <th>Acuerdo de radicación</th>                          
                                                    <th>Fase / Acción / Constancia</th>
                                                    <th>Acuses</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($auditoria->radicacion))
                                                    <tr>  
                                                        <td class="text-center">                                       
                                                            @if (!empty($auditoria->radicacion))
                                                                {{ $auditoria->radicacion->numero_expediente }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if(getSession('cp')!=2022)
                                                                {{ optional($auditoria->radicacion)->oficio_acuerdo }}
                                                            @elseif(getSession('cp')==2022)
                                                                @if (!empty($auditoria->radicacion))
                                                                    {{ $auditoria->radicacion->numero_acuerdo }}
                                                                @endif
                                                            @endif
                                                            
                                                        </td>
                                                        {{-- <td class="text-center">
                                                            @if (!empty($auditoria->radicacion))
                                                            <a href="{{ asset($auditoria->radicacion->oficio_acuerdo) }}" target="_blank">
                                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->radicacion->oficio_acuerdo)) ?>
                                                            </a> <br>
                                                            <small>{{ fecha($auditoria->radicacion->fecha_oficio_acuerdo) }}</small>
                                                            @endif
                                                        </td> --}}
                                                        <td class="text-center">
                                                            @can('radicacion.radicacionpdf')
                                                                <a href="{{route('radicacion.radicacionpdf',$auditoria->radicacion)}}" target="_blank">
                                                                    <?php echo htmlspecialchars_decode(iconoArchivo('.pdf')) ?>
                                                                </a>
                                                            @endcan
                                                            <!--AQUI SE COLOCA EL WORD-->
                                                            <!--<a href=" route('radicacion.word', $auditoria->radicacion) }}" class="btn btn-primary">Descargar Word</a>-->


                                                        </td> 
                                                        <td class="text-center">  
                                                            @if (!empty($auditoria->radicacion) &&($auditoria->radicacion->fase_autorizacion == 'En revisión'))
                                                                <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                                                
                                                            @elseif(!empty($auditoria->radicacion) &&($auditoria->radicacion->fase_autorizacion == 'En validación'))
                                                                <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion ?? 'Sin estatus' }}</span>

                                                            @elseif(!empty($auditoria->radicacion) &&($auditoria->radicacion->fase_autorizacion == 'En autorización'))
                                                                <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion ?? 'Sin estatus' }}</span>

                                                            @elseif(!empty($auditoria->radicacion) &&($auditoria->radicacion->fase_autorizacion == 'Autorizado'))
                                                                <span class="badge badge-light-success">{{ $auditoria->radicacion->fase_autorizacion ?? 'Sin estatus' }}</span>

                                                            @elseif(!empty($auditoria->radicacion) &&($auditoria->radicacion->fase_autorizacion == 'Rechazado'))
                                                                <span class="badge badge-light-danger">{{ $auditoria->radicacion->fase_autorizacion ?? 'Sin estatus' }}</span>
                                                                <!-- Compacto con texto 
                                                                <a href="#guardar" class="corner-button corner-button--sm">
                                                                <span class="cb-content">Guardar</span>
                                                                </a>
                                                                                        
                                                                <!-- Solo ícono 
                                                                <a href="#buscar" class="corner-button corner-button--icon" aria-label="Buscar">
                                                                <span class="cb-content"><i class="bi bi-search" aria-hidden="true"></i></span>
                                                                </a>
                                                                -->
                                                            @else
                                                                <span class="badge badge-light-secondary">Sin estatus</span>
                                                            @endif                        
                                                        </td>
                                                        <td class="">                                                                            

                                                                @if (!empty($auditoria->comparecencia->oficio_acuerdo))
                                                                Acuerdo de radicación:
                                                                <a href="{{ asset($auditoria->comparecencia->oficio_acuerdo) }}" target="_blank">
                                                                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acuerdo)); ?>
                                                                </a>
                                                                <small>{{ fecha($auditoria->comparecencia->fecha_oficio_acuerdo) }}</small><br><br>
                                                                @endif

                                                                @if (!empty($auditoria->comparecencia->oficio_recepcion))
                                                                Comprobante de recepción depto. de notificaciones:
                                                                <a href="{{ asset($auditoria->comparecencia->oficio_recepcion) }}"
                                                                    target="_blank">
                                                                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_recepcion)); ?>
                                                                </a>
                                                                <small>{{ fecha($auditoria->comparecencia->fecha_recepcion) }}</small><br><br>
                                                                @endif

                                                                @if (!empty($auditoria->comparecencia->oficio_acuse))
                                                                Acuse de notificación de acuerdos:
                                                                <a href="{{ asset($auditoria->comparecencia->oficio_acuse) }}" target="_blank">
                                                                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acuse)); ?>
                                                                </a>
                                                                <small>{{ fecha($auditoria->comparecencia->fecha_acuse) }}</small><br><br>
                                                                @endif

                                                        </td>
                                                        <td>
                                                            <a href="{{ route('buzonseg.show', $RAD) }}" class="corner-button">
                                                                <span class="cb-content">Ir a Radicación<i class="bi bi-arrow-up-right-circle-fill text-primary fs-1" aria-hidden="true"></i></span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @if (!empty($auditoria->radicacion))
                                                        {!! movimientosDesglose($auditoria->radicacion->id, 10, $auditoria->radicacion->movimientos) !!}
                                                    @endif                                                                                           
                                                @else
                                                    <tr>
                                                        <td class="text-center" colspan="10">
                                                            <span class='text-center'>No hay registros en éste apartado</span>
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