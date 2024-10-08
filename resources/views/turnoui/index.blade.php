@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('turnoui.index',$auditoria) }}
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
                    Turno UI
                </h1>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')                
                <div class="row">
                    <div class="col-md-12">
                        @if (empty($turnoui))
                            @can('turnoui.create')
                                <a class="btn btn-primary float-end" href="{{ route('turnoui.create') }}">
                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Turno UI
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
                                <th>Número de oficio </th>
                                <th>Acuse de notificación</th>
                                <th>Fecha de notificacion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($turnoui))
                            <tr>
                                <td class="text-center">
                                    {{ fecha($turnoui->fecha_turno_oi) }}
                                </td>
                                <td class="text-center">
                                    {{$turnoui->numero_turno_ui }}
                                </td>
                                <td class="text-center">
                                    @btnFile($turnoui->turno_ui)
                                </td>
                                <td class="text-center">
                                    {{ fecha($turnoui->fecha_notificacion_ui) }}
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
