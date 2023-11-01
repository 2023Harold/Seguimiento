@extends('layouts.appquick')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="card-title">
                <h1 class="text-primary">Quick login</h1>
            </div>
        </div>
    </div>
    <div class="card-body">
        {!! BootForm::open(['id'=>'form', 'method' => 'GET']); !!}
        <div class="row align-items-center">
            <div class="col-md-3">
                {!! BootForm::text('nombre', 'Nombre:', old('nombre', optional(request())->nombre)); !!}
            </div>
            <div class="col-md-3">
                {!! BootForm::text('email', 'Correo electrónico:', old('email', optional(request())->email)); !!}
            </div>
            <div class="col-md-3">
                {!! BootForm::radios('inactivo', 'Estatus:',
                ['Todas'=>'Todas', 'Activas'=>'<i class="fa fa-circle" style="color: green;"></i>Activas', 'Inactivas'=>'<i class="fa fa-circle" style="color: red;"></i>Inactivas'],
                old('inactivo', (request()->inactivo!="")?request()->inactivo:'Todas'), true, ['class' => 'i-checks']); !!}
            </div>
            <div class="col-md-3">
                {!! Form::submit('Buscar', ['class' => 'btn btn-primary mt-8']); !!}
            </div>
        </div>
        {!! BootForm::close() !!}
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-rounded table-row-gray-300 gy-7">
                        <thead style="display:{{(count($usuarios)) ? "show" : "none"}}">
                            <tr>
                                <th>Nombre</th>
                                <th>Correo electrónico</th>
                                <th>Unidad Administrativa</th>
                                <th>Rol</th>
                                <th data-hide="phone">Ultimo acceso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuarios as $usuario)
                                <tr>
                                    <td>
                                        {!! ($usuario->inactivo == 'X') ? '<i class="fa fa-circle" style="color: red;"></i>' : '<i class=" fa fa-circle" style="color: green;"></i>' !!}
                                        <a href="{{ route('quicklogin.loginas', $usuario) }}" title="Ingresar como" class="btn-link">
                                                {{ $usuario->name }}
                                        </a>
                                    </td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        @if (implode($usuario->getRoleNames()->toArray()) == 'Entidad Fiscalizable')
                                            {{ $usuario->entidadFiscalizable->descripcion }}
                                        @else
                                            {{ $usuario->unidadAdministrativa->descripcion }}
                                        @endif
                                    </td>
                                    <td>
                                        @php $roles=$usuario->getRoleNames(); @endphp
                                        @foreach ($roles as $rol)
                                                {{ $rol }}
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ fecha($usuario->fecha_ultimo_acceso,'d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">No se ha registrado este usuario</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>
@endsection

