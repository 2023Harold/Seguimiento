@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('rol.index') }}
@endsection
@section('content')
@include('flash::message')
<div class="card ">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('administracion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;&nbsp;&nbsp;
            Catálogo de roles
        </h1>        
    </div>
    <div class="card-body">
        {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
        <div class="row">
            <div class="col-md-5">
                {!! BootForm::text("rol", "Nombre del rol:", old("rol", optional(request())->rol)); !!}
            </div>
            <div class="col-md-2">
                {{-- @acceso('rol.index') --}}
                <button type="submit" class="btn btn-primary  mt-8">Buscar</button>  
                {{-- @endacceso --}}
            </div>
        </div>
        {!! BootForm::close() !!}
        <div class="row ">           
            <div class="col-md-12">
                {{-- @acceso('rol.create') --}}
                <a class="btn btn-primary float-end" href="{{ route('rol.create') }}">
                    Agregar rol
                </a>
                {{-- @endacceso --}}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <table class="table table-striped table-hover table-rounded table-row-gray-300 gy-7">
                    <thead class="table-head">
                        <tr>
                            <th scope="col">Rol</th>
                            <th scope="col">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($roles)>0)
                        @foreach($roles as $rol)
                        <tr>
                            <td>
                                <a href="{{ route('rol.edit',$rol) }}">
                                    {{ $rol->name }}
                                </a>
                            </td>
                            <td class="text-center">
                                {{-- @acceso('rol.destroy') --}}
                                @destroy(route('rol.destroy', $rol))
                                {{-- @endacceso --}}
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td @class('text-center') colspan="4">No hay permisos registrados</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pagination">
            {{ $roles->appends(['rol' => $request->rol])->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
</div>
@endsection