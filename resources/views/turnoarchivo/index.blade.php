@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('turnoarchivo.index',$auditoria) }}
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
                    Acuse envío archivo
                </h1>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')                
                <div class="row">
                    <div class="col-md-12">
                        @if (empty($turnoarchivo))
                            @can('turnoui.create')
                                <a class="btn btn-primary float-end" href="{{ route('turnoarchivo.create') }}">
                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Acuse envío archivo
                                </a> 
                            @endcan
                        @endif
                    </div>                    
                </div>                                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha </th>
                                <th>Número del oficio</th>
                                <th>Acuse de notificacion</th>
                                <th>Fecha de notificacion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($turnoarchivo))
                            <tr>
                                <td class="text-center">
                                    {{ fecha($turnoarchivo->fecha_turno_archivo) }}
                                </td>
                                <td class="text-center">
                                    {{$turnoarchivo->numero_turno_archivo }}
                                </td>
                                <td class="text-center">
                                    @btnFile($turnoarchivo->turno_archivo)
                                </td>
                                <td class="text-center">
                                    {{ fecha($turnoarchivo->fecha_notificacion_archivo) }}
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
