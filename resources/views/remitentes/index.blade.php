@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('remitentes.show', $folioscrr) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('folioscrr.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Folios Remitentes index
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')

                 @can('remitentes.create') 
                    <div class="d-flex justify-content-end">
                        {{--<a class="btn btn-icon bi bi-person-plus-fill text-sistema fs-1" href="#"></a>--}}
                        <a href="{{ route('remitentes.create', $folioscrr) }}" class="btn btn-primary"> 
                            <i class="bi bi-person-plus-fill fs-1"></i> Agregar Remitente 
                        </a>
                    </div>
                    <br>
                @endcan
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Remitente</th>
                                <th>Cargo</th>
                                <th>Domicilio</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($remitentes as $remitente)
                            
                            <tr>
                                <td class="text-center">
                                    <small>{{$remitente->nombre_remitente}}</small> <br>
                                </td>
                                <td class="text-center">
                                    {{$remitente->cargo_remitente}} <br>
                                </td>
                                <td class="text-center">
                                    {{$remitente->domicilio_remitente}} <br>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('remitentes.edit', $remitente) }}" class="btn btn-primary"> 
                                        <i class="align-middle fas fa-edit" aria-hidden="true"></i> 
                                    </a>
								</td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan=5>
                                    No se han registrado datos en este apartado
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                    <a href="{{ route('folioscrr.index') }}" class="btn btn-primary me-2">Continuar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
