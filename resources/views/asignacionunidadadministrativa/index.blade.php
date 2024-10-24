@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('asignacionunidadadministrativa.index') }}
@endsection
@section('content')
<div class="row">
    @include('flash::message')   
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <h1 class="card-title">
                        <a href="{{ route('administracion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;&nbsp;&nbsp;
                        Asignación Unidad Administrativa
                    </h1>
                </div>
                <div class="card-body">
                    {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            {!! BootForm::text("name", "Nombre del usuario:", old("name", optional(request())->name)); !!}
                        </div>
                        <div class="col-md-4">
                            {!! BootForm::text("email", "Correo electrónico:", old("email", optional(request())->email)); !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::radios("estatus",
                            "Estatus:",['Todas'=>' Todas&nbsp;&nbsp;','Activo'=>' Activas&nbsp;&nbsp;','Inactivo'=>' Inactivas'] ,old("estatus",
                            (request()->estatus != "")?request()->estatus:'Todas'),true,['class'=>'i-checks']); !!}
                        </div>
                        <div class="col-md-1">
                            {!! Form::submit('Buscar', ['class' => 'btn btn-primary pull-right']); !!}
                        </div>
                    </div>
                    {!! BootForm::close() !!}
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover table-rounded table-row-gray-300 gy-7">
                                <thead class="table-head">
                                    <tr>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Puesto</th>
                                        <th scope="col">Correo electrónico</th>
                                        <th scope="col">Rol</th>
                                        <th scope="col">Unidad Administrativa 2021</th>
                                        <th scope="col">Unidad Administrativa 2022</th>
                                        <th scope="col">Unidad Administrativa 2023</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($users)>0)
                                    @foreach($users as $user)
                                    <tr>
                                        <td class="text-primary">
                                            @if ($user->estatus == 'Activo')
                                            <span class="fa fa-circle" style="color: green"></span>
                                            @else
                                            <span class="fa fa-circle" style="color: red"></span>
                                            @endif
                                            @can('user.edit')
                                            <a href="{{ route('user.edit',$user) }}">{{ $user->name }}</a>
                                            @else
                                            {{ $user->name }}
                                            @endcan
                                        </td>
                                        <td>{{ $user->puesto }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ implode($user->getRoleNames()->toArray()) }}</td>                                                                             
                                        
                                    <td>
                                        @if (empty($user->cp_ua2021))
                                        <a href="{{ route('asignacionunidadadministrativa.edit',$user) }}"  class="btn btn-primary float-end">
                                            <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Asignar
                                        </a>
                                        @else
                                       {{ $user->unidadAdministrativa2021->descripcion }}
                                       @endif
                                    </td>             
                                    <td>
                                        @if (empty($user->cp_ua2022))
                                        <a href="{{ route('asignacionunidadadministrativa2022.edit',$user) }}"  class="btn btn-primary float-end">
                                            <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Asignar
                                        </a>
                                        @else
                                        {{ $user->unidadAdministrativa2022->descripcion }}
                                       @endif

                                    </td>             
                                    <td>
                                        @if (empty($user->cp_ua2023))
                                        <a href="{{ route('asignacionunidadadministrativa2023.edit',$user) }}"  class="btn btn-primary float-end">
                                            <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Asignar
                                        </a>
                                        @else
                                        {{ $user->unidadAdministrativa2023->descripcion }}
                                       @endif
                                    </td>             
                                                                                                        
                                </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td @class('text-center') colspan="8">No hay usuarios registrados</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pagination">
                        {{ $users->appends(['usuario' => $request->usuario,
                                    'email' => $request->email,
                                    'estatus' => $request->estatus])->links('vendor.pagination.bootstrap-4') }}

                    </div>
                </div>
            </div>
        </div>
</div>
@endsection

