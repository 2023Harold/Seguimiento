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
                {!!BootForm::open(['route'=>'buzonacuerdosconclusion.index','method'=>'GET']) !!}
                    <div class="row">
                        {!!BootForm::hidden('apartado',"acuerdo") !!}
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
                                <th rowspan=1 colspan=2 style="width:20px" class="text-center ">Acuerdo de conclusión</th>
                                <th rowspan=1 colspan=2 style="width:20px"></th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Acuerdo de Recomendaciones</th>
                                <th>Acuerdo de Pliegos</th>
                                <th rowspan=1 colspan=2 style="width:20px"></th>
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
                                        $ACR = "ACR-".$auditoria->id;
                                    @endphp 
                                    
                                    @if (!empty($auditoria->acuerdoconclusion) && ($auditoria->acuerdoconclusion->no_aplica!='X'))
                                        <td>
                                            <table class="table">
                                                <thead>                           
                                                    <tr>                                             
                                                        <th>Número de oficio</th>
                                                        <th>Nombre del titular a quien se dirige</th>
                                                        <th>Cargo del titular a quien se dirige</th>
                                                        <th>Domicilio</th>
                                                        <th>Acuerdo de conclusión</th>
                                                        <th>Fase / Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- {{ dd($auditoria->acuerdoconclusion,$auditoria); }} --}}
                                                    @if (!empty($auditoria->acuerdoconclusion))
                                                
                                                    <tr>
                                                        <td>
                                                            {{ $auditoria->acuerdoconclusion->numero_oficio }}
                                                        </td>
                                                        <td>
                                                            {{ $auditoria->acuerdoconclusion->nombre_titular }}
                                                        </td>
                                                        <td>
                                                            {{ $auditoria->acuerdoconclusion->cargo_titular }}
                                                        </td>
                                                        <td>
                                                            {{ $auditoria->acuerdoconclusion->domicilio }}
                                                        </td>

                                                        <td class="text-center">
                                                            <a>
                                                            @btnFile($auditoria->acuerdoconclusion->acuerdo_conclusion)
                                                            </a><br>
                                                            <small>{{ fecha($auditoria->acuerdoconclusion->fecha_acuerdo_conclusion) }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            @if( (getSession('cp')==2022) && ($auditoria->acuerdoconclusion->no_aplica=='X') || (getSession('cp')==2023) && ($auditoria->acuerdoconclusion->no_aplica=='X'))                        
                                                                <tr>
                                                                    <td class="text-center" colspan="5">
                                                                        No aplica.
                                                                    </td>                                
                                                                </tr>
                                                            @elseif ((getSession('cp')==2022)&& empty($auditoria->acuerdoconclusion->fase_autorizacion)||$auditoria->acuerdoconclusion->fase_autorizacion=='Rechazado' && ($auditoria->acuerdoconclusion->no_aplica==' '))
                                                                <span class="badge badge-light-danger">{{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                            
                                                            @elseif( (getSession('cp')==2023) && (empty($auditoria->acuerdoconclusion->fase_autorizacion)|| ($auditoria->acuerdoconclusion->fase_autorizacion=='Rechazado'|| ($auditoria->acuerdoconclusion->no_aplica==' '))) )
                                                                <span class="badge badge-light-danger">{{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                        
                                                            @endif       
                                                            @if(getSession('cp')!=2023)
                                                                @if ($auditoria->acuerdoconclusion->fase_autorizacion == 'En validación')
                                                                    <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                                                @elseif($auditoria->acuerdoconclusion->fase_autorizacion == 'En revisión')
                                                                    <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                                                @endif
                                                            @else
                                                                @if($auditoria->acuerdoconclusion->fase_autorizacion == 'En validación')
                                                                    <span class="badge badge-light-warning">{{$auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                                                @elseif($auditoria->acuerdoconclusion->fase_autorizacion == 'En revisión')
                                                                    <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                                                @endif
                                                            @endif
                                                            @if ($auditoria->acuerdoconclusion->fase_autorizacion == 'En autorización')
                                                                <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                                            @endif
                                                            @if ($auditoria->acuerdoconclusion->fase_autorizacion=='Autorizado')
                                                                <span class="badge badge-light-success"> {{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                                            @endif
                                                        </td>
                                                    </tr>        
                                                    @if (!empty($auditoria->acuerdoconclusion)&&!empty($auditoria->acuerdoconclusion->movimientos))
                                                        {!! movimientosDesglose($auditoria->acuerdoconclusion->id, 10, $auditoria->acuerdoconclusion->movimientos) !!}
                                                    @endif
                                                    @else                            
                                                    {{-- termino de fase de validación --}}
                                                    <tr>
                                                        <td class="text-center" colspan="5">
                                                            No se encuentran registros en este apartado.
                                                        </td>
                                                    </tr>

                                                @endif        
                                                </tbody>
                                            </table>      
                                        </td>

                                    @else
                                        <td class="text-center" colspan="1">
                                            <span class='text-center'>No hay registros en éste apartado</span>
                                        </td>
                                    @endif
                                    
                                    @if (!empty($auditoria->acuerdoconclusionpliegos) && ($auditoria->acuerdoconclusionpliegos->no_aplica!='X'))
                                        <td>
                                            <table class="table">
                                                <thead>                           
                                                    <tr>                                             
                                                        <th>Número de oficio</th>
                                                        <th>Nombre del titular a quien se dirige</th>
                                                        <th>Cargo del titular a quien se dirige</th>
                                                        <th>Domicilio</th>
                                                        <th>Acuerdo de conclusión</th>
                                                        <th>Fase / Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- {{ dd($auditoria->acuerdoconclusion,$auditoria); }} --}}
                                                    @if (!empty($auditoria->acuerdoconclusionpliegos))
                                                
                                                    <tr>
                                                        <td>
                                                            {{ $auditoria->acuerdoconclusionpliegos->numero_oficio }}
                                                        </td>
                                                        <td>
                                                            {{ $auditoria->acuerdoconclusionpliegos->nombre_titular }}
                                                        </td>
                                                        <td>
                                                            {{ $auditoria->acuerdoconclusionpliegos->cargo_titular }}
                                                        </td>
                                                        <td>
                                                            {{ $auditoria->acuerdoconclusionpliegos->domicilio }}
                                                        </td>

                                                        <td class="text-center">
                                                            <a>
                                                            @btnFile($auditoria->acuerdoconclusionpliegos->acuerdo_conclusion)
                                                            </a><br>
                                                            <small>{{ fecha($auditoria->acuerdoconclusionpliegos->fecha_acuerdo_conclusion) }}</small>
                                                        </td>
                                                        <td class="text-center">
    
                                                            @if(getSession('cp')!=2023)
                                                                @if ($auditoria->acuerdoconclusionpliegos->fase_autorizacion == 'En validación')
                                                                    <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusionpliegos->fase_autorizacion }} </span>
                                                                @elseif($auditoria->acuerdoconclusionpliegos->fase_autorizacion == 'En revisión')
                                                                    <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusionpliegos->fase_autorizacion }} </span>
                                                                @endif
                                                            @else
                                                                @if($auditoria->acuerdoconclusionpliegos->fase_autorizacion == 'En validación')
                                                                    <span class="badge badge-light-warning">{{$auditoria->acuerdoconclusionpliegos->fase_autorizacion }} </span>
                                                                @elseif($auditoria->acuerdoconclusionpliegos->fase_autorizacion == 'En revisión')
                                                                    <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusionpliegos->fase_autorizacion }} </span>
                                                                @endif
                                                            @endif
                                                            @if ($auditoria->acuerdoconclusionpliegos->fase_autorizacion == 'En autorización')
                                                                <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusionpliegos->fase_autorizacion }} </span>
                                                            @endif
                                                            @if ($auditoria->acuerdoconclusionpliegos->fase_autorizacion=='Autorizado')
                                                                <span class="badge badge-light-success"> {{ $auditoria->acuerdoconclusionpliegos->fase_autorizacion }} </span>
                                                            @endif
                                                        </td>
                                                    </tr>        
                                                    @if (!empty($auditoria->acuerdoconclusionpliegos)&&!empty($auditoria->acuerdoconclusionpliegos->movimientos))
                                                        {!! movimientosDesglose($auditoria->acuerdoconclusionpliegos->id, 10, $auditoria->acuerdoconclusionpliegos->movimientos) !!}
                                                    @endif
                                                    @else                            
                                                    {{-- termino de fase de validación --}}
                                                    <tr>
                                                        <td class="text-center" colspan="11">
                                                            No se encuentran registros en este apartado.
                                                        </td>
                                                    </tr>
                                                @endif        
                                                </tbody>
                                            </table>      
                                        </td>
                                        <td></td>
                                        
                                    @else
                                        <td class="text-center" colspan="2">
                                            <span class='text-center'>No hay registros en éste apartado</span>
                                        </td>
                                    @endif
                                       <td>
                                        <a href="{{ route('buzonseg.show', $ACR) }}" class="corner-button">
                                            <span class="cb-content">Ir a Acuerdo de Conclusión<i class="bi bi-arrow-up-right-circle-fill text-primary fs-1" aria-hidden="true"></i></span>
                                        </a>
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