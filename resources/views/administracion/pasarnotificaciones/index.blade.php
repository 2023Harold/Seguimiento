@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('user.index') }}
@endsection
@section('content')
<div class="row">
    @include('flash::message')   
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <h1 class="card-title">
                        <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;&nbsp;&nbsp;
                        Pasar notificaciones de usuario
                    </h1>
                </div>
                <div class="card-body">
                    {!!BootForm::open(['id'=>'form', 'method' => 'GET']) !!}
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            {!!BootForm::text("name", "Nombre del usuario:", old("name", optional(request())->name)) !!}
                        </div>
                        <div class="col-md-4">
                            {!!BootForm::text("email", "Correo electrónico:", old("email", optional(request())->email)) !!}
                        </div>
                        <div class="col-md-3">
                            {!!BootForm::radios("estatus",
                            "Estatus:",['Todas'=>' Todas&nbsp;&nbsp;','Activo'=>' Activas&nbsp;&nbsp;','Inactivo'=>' Inactivas'] ,old("estatus",
                            (request()->estatus != "")?request()->estatus:'Todas'),true,['class'=>'i-checks']) !!}
                        </div>
                        <div class="col-md-1">
                            {!!Form::submit('Buscar', ['class' => 'btn btn-primary pull-right']) !!}
                        </div>
                    </div>
                    {!!BootForm::close() !!}
                    <div class="pagination">
                        {{ $users->appends(['usuario' => $request->usuario,'email' => $request->email,
                                    'estatus' => $request->estatus])->links('vendor.pagination.bootstrap-4') }}
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover table-rounded table-row-gray-300 gy-7">
                                <thead class="table-head">
                                    <tr>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Notificaciones</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($users)>0)
                                        @foreach($users as $user)
                                        <tr>
                                            <td class="text-center text-primary">
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
                                            <td class="text-center">Pendientes:{{ count($user->notificaciones) }} <br>
                                                Leidas{{ count($user->notificacionesLeidas) }}
                                            </td>
                                            <td class=" text-center text-primary">
                                                <a href="{{ route('pasarnotificaciones.edit', $user) }}" class="corner-button">
                                                    <span class="cb-content">Pasar las notificaciones no leidas otro usuario<i class="bi bi-arrow-up-right-circle-fill text-primary fs-1" aria-hidden="true"></i></span>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class='text-center' colspan="8">No hay usuarios registrados</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pagination">
                        {{ $users->appends(['usuario' => $request->usuario, 'email' => $request->email,
                                    'estatus' => $request->estatus])->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
