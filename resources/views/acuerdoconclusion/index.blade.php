@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('acuerdoconclusion.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('auditoriaseguimiento.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Acuerdo de conclusión
                </h1>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')                
                <div class="row">
                    <div class="col-md-12">
                        @if (empty($auditoria->acuerdoconclusion))
                            @can('acuerdoconclusion.create')
                                <a class="btn btn-primary float-end" href="{{ route('acuerdoconclusion.create') }}">
                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Acuerdo
                                </a> 
                            @endcan
                        @endif
                    </div>                    
                </div>                                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
								<th>Número de oficio</th>
                                <th>Nombre del titular a quien se dirige</th>
                                <th>Cargo del titular a quien se dirige</th>
                                <th>Domicilio</th>                                
                                <th>Acuerdo de conclusión</th> 
								<th></th>
                            </tr>
                        </thead>
                        <tbody>
                            
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
								@if($auditoria->numero_auditoria=='AD-097'||
									$auditoria->numero_auditoria=='AD-108'||
									$auditoria->numero_auditoria=='AD-120'||
									$auditoria->numero_auditoria=='AD-107'||
									$auditoria->numero_auditoria=='AL-078'||
									$auditoria->numero_auditoria=='AL-077'||
									$auditoria->numero_auditoria=='AL-130'||
									$auditoria->numero_auditoria=='AL-091'||
									$auditoria->numero_auditoria=='ACF-10'||
									$auditoria->numero_auditoria=='AL-089'||
									$auditoria->numero_auditoria=='ACF-119'||
									$auditoria->numero_auditoria=='AD-063'||
									$auditoria->numero_auditoria=='AD-046'||
									$auditoria->numero_auditoria=='ACF-025.'||
									$auditoria->numero_auditoria=='ACF-016'||
									$auditoria->numero_auditoria=='ACF-015'||
									$auditoria->numero_auditoria=='ACF-01'
									)
                                    <a href="{{ route('acuerdoconclusion.edit',$auditoria->acuerdoconclusion) }}" class="btn btn-primary">
                                        <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                    </a>
								@endif
                                </td>
                                {{-- <td class="text-center">
                                    {{ fecha($auditoria->acuerdoconclusion->fecha_acuerdo_conclusion) }}
                                </td> --}}
                            </tr>
                            @else
                            <tr>
                                <td class="text-center" colspan="5">
                                    No se encuentran registros en este apartado.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
