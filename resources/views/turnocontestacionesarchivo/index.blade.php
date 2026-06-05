@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('turnocontestacionesarc.index', $auditoria) }}
@endsection
@section('content')
    <div class="row">
        @include('layouts.partials._menu')
        <div class="col-md-9 mt-2">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">
                             <a href="{{ route('turnoarchivo.index') }}"><i
                                class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                        &nbsp;
                        Contestaciones
                    </h1>
                </div>
                <div class="card-body">
                    @include('layouts.contextos._auditoria')
                    <h3 class="card-title text-primary">Turno</h3>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <label>Fecha: </label>
                            <span class="text-primary">
                                {{ fecha($turno->fecha_turno_archivo) }}
                            </span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <label>Número de oficio: </label>
                            <span class="text-primary">
                                {{ $turno->numero_turno_archivo }}
                            </span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <label>Acuse de notificación: </label>
                            <span class="text-primary">
                               @btnFile($turno->turno_archivo)
                            </span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <label>Fecha de notificación: </label>
                            <span class="text-primary">
                                {{ fecha($turno->fecha_notificacion_archivo) }}
                            </span>
                        </div>
                    </div>
                    @include('flash::message')
                    <div class="row">
                        <div class="col-md-12">
                                 @can('turnocontestacionesarc.create')
                                    <a class="btn btn-primary float-end" href="{{ route('turnocontestacionesarc.create') }}">
                                        <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar contestacion
                                    </a>
                                @endcan
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Archivo de contestación </th>
                                    <th>Fecha de notificacion</th>
                                    <th>Fecha de recepción</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contestaciones as $contestacion)
                                    <tr>
                                        <td class="text-center">
                                            @btnFile($contestacion->archivo_contestacion)
                                        </td>
                                        <td class="text-center">
                                            {{fecha($contestacion->fecha_notificacion)}}
                                        </td>
                                        <td class="text-center">
                                            {{fecha($contestacion->fecha_recepcion)}}
                                        </td>
                                        <td class="text-center">
                                             <a href="{{ route('turnocontestacionesarc.edit',$contestacion) }}">
                                                <span class="fa fa-edit"></span>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                             @destroy(route('turnocontestacionesarc.destroy', $contestacion))
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">
                                            <h4>Observaciones:</h4><br>
                                            {{$contestacion->observaciones}}
                                        </td>
                                    </tr>
                                @empty
                                     <tr>
                                        <td colspan="5" class="text-center">
                                            No se han encontrado registros en este apartado
                                        </td>
                                     </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">
                        {{ $contestaciones->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
