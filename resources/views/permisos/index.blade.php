@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('permiso.index') }}
@endsection
@section('content')
    @include('flash::message')
    <div class="card ">
        <div class="card-header">
            <div class="row ">
                <h1 class="card-title">
                    <a href="{{ route('administracion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;&nbsp;&nbsp;
                    Catálogo de permisos
                </h1>        
            </div>
        </div>
        <div class="card-body">
            {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
            <div class="row align-items-end-base">
                <div class="col-md-5">
                    {!! BootForm::text("permiso", "Nombre del permiso:", old("permiso", optional(request())->permiso)); !!}
                </div>
                <div class="col-md-2">
                    {{-- @acceso('permiso.index') --}}
                    {!! BootForm::submit('Buscar', ['class' => 'btn btn-primary mt-8']); !!}
                    {{-- @endacceso --}}
                </div>
            </div>
            {!! BootForm::close() !!}
            <div class="row">
                <div class="col-md-12">
                    {{-- @acceso('permiso.create') --}}
                    <a class="btn btn-primary float-end" href="{{ route('permiso.create') }}" >
                        Agregar permiso
                    </a>
                    {{-- @endacceso --}}
                </div>
            </div>            
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-hover table-rounded table-row-gray-300 gy-7">
                        <thead class="table-head">
                        <tr>
                            <th scope="col">Permiso</th>
                            <th scope="col">Eliminar</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($permisos as $permiso)
                                <tr>
                                    <td>
                                        {{-- @acceso('permiso.edit') --}}
                                        <a href="{{ route('permiso.edit',$permiso) }}">
                                            {{ $permiso->name }}
                                        </a>
                                        {{-- @else --}}
                                            {{-- {{ $permiso->name }} --}}
                                        {{-- @endacceso --}}
                                    </td>
                                    <td class="text-center">
                                        {{-- @acceso('permiso.destroy') --}}
                                            @destroy(route('permiso.destroy', $permiso))
                                        {{-- @endacceso --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td @class('text-center') colspan="4">No hay permisos registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="pagination">
                {{ $permisos->appends(['permiso' => $request->permiso])->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
