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
                {!!BootForm::open(['route'=>'buzoninformes.index','method'=>'GET']) !!}
                    <div class="row">
                        {!!BootForm::hidden('apartado',"informes") !!}
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
                                <th rowspan=1 colspan=2 style="width:20px" class="text-center ">Informes de Seguimiento</th>
                                <th rowspan=1 colspan=1 style="width:20px" ></th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Informe de Recomendaciones</th>
                                <th>Informe de Pliegos</th>
                                <th rowspan=1 colspan=1  class="text-center "></th>
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
                                        $IS = "IS-".$auditoria->id;
                                    @endphp 
                                    <!-- Informe de Recomendaciones -->
                                    <td class="text-center">
                                        @if (!empty($auditoria->informeprimeraetapa))
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Fecha</th>
                                                        <th>Número de oficio</th>
                                                        <th>Nombre del titular</th>
                                                        @if (!empty($auditoria->informeprimeraetapa)&&(optional($auditoria->informeprimeraetapa)->informe))
                                                            <th>Informe</th>
                                                        @endif
                                                        <th>Fase / Acción </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                        <tr>                                
                                                            <td class="text-center">
                                                                {{ fecha($auditoria->informeprimeraetapa->fecha_informe) }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{$auditoria->informeprimeraetapa->numero_informe }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{$auditoria->informeprimeraetapa->nombre_titular_informe }}<br>
                                                                <span class="badge-light-dark text-gray-500">{{ $auditoria->informeprimeraetapa->cargo_titular_informe}}</span> <br>
                                                                <span class="badge-light-dark text-gray-500">{{ $auditoria->informeprimeraetapa->periodo_gestion}}</span> <br>
                                                                <span class="badge-light-dark text-gray-700">{{ $auditoria->informeprimeraetapa->domicilio_informe}}</span> <br><br>
                                                            </td>
                                                            @if (!empty($auditoria->informeprimeraetapa)&&(optional($auditoria->informeprimeraetapa)->informe))
                                                                <td class="text-center">
                                                                    @btnFile(asset(optional($auditoria->informeprimeraetapa)->informe))
                                                                </td>
                                                            @endif
                                                            <td class="text-center">                                                                                                                                                                                                                                                                                       
                                                                @if (empty($auditoria->informeprimeraetapa->fase_autorizacion)||$auditoria->informeprimeraetapa->fase_autorizacion=='Rechazado')
                                                                    <span class="badge badge-light-danger">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>
                                                                @endif                                   
                                                                @if ($auditoria->informeprimeraetapa->fase_autorizacion == 'En validación')
                                                                        <span class="badge badge-light-warning">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>
                                                                @endif      
                                                                @if ($auditoria->informeprimeraetapa->fase_autorizacion == 'En autorización')
                                                                    <span class="badge badge-light-warning">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>
                                                            @endif           
                                                                @if ($auditoria->informeprimeraetapa->fase_autorizacion=='Autorizado')
                                                                    <span class="badge badge-light-success">{{ $auditoria->informeprimeraetapa->fase_autorizacion }} </span>                                                                                                                                               
                                                                @endif      
                                                            </td>                
                                                        </tr>
                                                        @if (!empty($auditoria->informeprimeraetapa))
                                                            {!! movimientosDesglose($auditoria->informeprimeraetapa->id, 10, $auditoria->informeprimeraetapa->movimientos) !!}
                                                        @endif   
                                                </tbody>
                                            </table>
                                        @else                                                             
                                            No se encuentran registros en este apartado.
                                        @endif
                                    </td>
                                    <!-- Informe de Pliegos de Observación -->
                                    <td class="text-center">
                                        @if (!empty($auditoria->informepliegos))
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Número de oficio</th>
                                                    <th>Nombre del titular</th>
                                                    @if(!empty($auditoria->informepliegos)&&(optional($auditoria->informepliegos)->informe))
                                                        <th>Informe</th>
                                                    @endif    
                                                    <th>Fase / Acción </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                                    <tr>
                                                        <td class="text-center">
                                                            {{ fecha($auditoria->informepliegos->fecha_informe) }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{$auditoria->informepliegos->numero_informe }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{$auditoria->informepliegos->nombre_titular_informe }}<br>
                                                            <span class="badge-light-dark text-gray-500">{{ $auditoria->informepliegos->cargo_titular_informe}}</span> <br>
                                                            <span class="badge-light-dark text-gray-500">{{ $auditoria->informepliegos->periodo_gestion}}</span> <br>
                                                            <span class="badge-light-dark text-gray-700">{{ $auditoria->informepliegos->domicilio_informe}}</span> <br><br>
                                                        </td>
                                                        @if(!empty($auditoria->informepliegos)&&(optional($auditoria->informepliegos)->informe))
                                                            <td class="text-center">
                                                                @btnFile(asset(optional($auditoria->informepliegos)->informe))
                                                            </td>
                                                        @endif
                                                        <td class="text-center">                                                                                                                                                                                                                                                                                       
                                                            @if (empty($auditoria->informepliegos->fase_autorizacion)||$auditoria->informepliegos->fase_autorizacion=='Rechazado')
                                                                <span class="badge badge-light-danger">{{ $auditoria->informepliegos->fase_autorizacion }} </span>                                                                         
                                                            @endif                                   
                                                            @if ($auditoria->informepliegos->fase_autorizacion == 'En validación')
                                                                <span class="badge badge-light-warning">{{ $auditoria->informepliegos->fase_autorizacion }} </span>
                                                            @endif      
                                                            @if ($auditoria->informepliegos->fase_autorizacion == 'En autorización')
                                                                <span class="badge badge-light-warning">{{ $auditoria->informepliegos->fase_autorizacion }} </span>
                                                            @endif           
                                                            @if ($auditoria->informepliegos->fase_autorizacion=='Autorizado')
                                                                <span class="badge badge-light-success">{{ $auditoria->informepliegos->fase_autorizacion }} </span>                                                                                                                                               
                                                            @endif
                                                        </td>        
                                                    </tr>
                                                    @if (!empty($auditoria->informepliegos))
                                                        {!! movimientosDesglose($auditoria->informepliegos->id, 10, $auditoria->informepliegos->movimientos) !!}
                                                    @endif   
                                                
                                            </tbody>
                                        </table>
                                        @else                                                             
                                            No se encuentran registros en este apartado.
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('buzonseg.show', $IS) }}" class="corner-button">
                                            <span class="cb-content">Informes<i class="bi bi-arrow-up-right-circle-fill text-primary fs-1" aria-hidden="true"></i></span>
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