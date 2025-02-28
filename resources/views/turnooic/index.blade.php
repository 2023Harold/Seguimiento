@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('turnooic.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('turnooic.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Turno al Organo Interno de Control
                </h1>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')                
                <div class="row">
                    <div class="col-md-12">
                        @if (empty($turnooic))
                            @can('turnooic.create')
                                <a class="btn btn-primary float-end" href="{{ route('turnooic.create') }}">
                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Turno al OIC
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
                                <th>Nombre de titular</th>
                                <th>Acuse de envio a notificar</th>
                                <th>Fecha de envío a notificar</th>
                                <th>Acuse de notificación</th>
                                <th>Fecha de notificación</th>
								<th>Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($turnooic))
                            <tr>
                                <td class="text-center">
                                    {{ fecha($turnooic->fecha_turno_oic) }}
                                </td>
                                <td class="text-center">
                                    {{$turnooic->numero_turno_oic }}
                                </td>
                                <td class="text-center">
                                    {{$turnooic->nombre_titular_oic }}
                                </td>
                                <td class="text-center">
                                    @btnFile($turnooic->turno_oic)
                                </td>
                                <td class="text-center">
                                    {{ fecha($turnooic->fecha_envio) }}
                                </td>
                                <td class="text-center">
                                    @btnFile($turnooic->acuse_notificacion)
                                </td>
                                <td class="text-center">
                                    {{ fecha($turnooic->fecha_notificacion) }}
                                </td>
								<td class="text-center">								
                                    <a href="{{ route('turnooic.edit',$turnooic) }}" class="btn btn-primary">
                                        <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                    </a>
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
