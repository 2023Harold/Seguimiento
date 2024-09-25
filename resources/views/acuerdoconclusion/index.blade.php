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
                                <th>Nombre del tutular a quien se dirige</th>
                                <th>Cargo del titular a quien se dirige</th>
                                <th>Domicilio</th>
                                <th>Número</th>
                                <th>Acuerdo de conclusión UI</th>
                                <th>Fecha del acuerdo de conclusión</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @if (!empty($auditoria->acuerdoconclusion))
                            <tr>
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
                                    {{$auditoria->acuerdoconclusion->numero_acuerdo_conclusion }}
                                </td>
                                <td class="text-center">
                                    @btnFile($auditoria->acuerdoconclusion->acuerdo)
                                </td>
                                <td class="text-center">
                                    {{ fecha($auditoria->acuerdoconclusion->fecha_acuerdo_conclusion) }}
                                </td>
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
