@extends('layouts.app')
@section('breadcrums')
    @if ($accion=='Agregar')
        {{ Breadcrumbs::render('usercreate') }}
    @else
        {{ Breadcrumbs::render('useredit') }}
    @endif
@endsection
@section('content')
<div class="container-fluid">
    @section('content')
    @include('flash::message')
    <div class="card">
        <div class="card-header">
            <div class="row ">
                <div class="col-md-8">
                    <h3 class="color-sistema py-2">
                        @can('user.index')
                            <a class="color-sistema" href="{{ route('user.index') }}"><i class="fa fa-arrow-circle-o-left"></i></a>
                        @endcan
                        Usuario
                    </h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('flash::message')
            {{ BootForm::open(['model' => $user, 'store' => 'user.store', 'update' => 'user.update','id'=>'form']) }}
                <div class="row">
                    <div class="col-md-3 pt-2">
                        {!! BootForm::text('name','Nombre del usuario: *',old("name",$user->name),['maxlength'=>'75']); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!! BootForm::text('puesto','Puesto: *',old("puesto",$user->puesto))!!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {!! BootForm::text('email','Correo electrónico: *',old("email",$user->email),['maxlength'=>'60']); !!}
                    </div>
                </div>
                @php
                    $values = ($user->exists) ? $user->roles->pluck('name')->toArray():[0=>''];
                @endphp
                <div class="row">
                    <div class="col-md-2">
                        {!! BootForm::select('rol','Rol: *' ,$roles,old("rol",$values[0])); !!}
                    </div>
                </div>
                @php
                    $rol = old('rol',$values[0]);
                    $mostrar_unidad  = "none";
                    $mostrar_entidad = "none";

                    if ($rol  == 'Entidad Fiscalizable') {
                        $mostrar_unidad  = "none";
                        $mostrar_entidad = "show";
                    }else{
                        if ($rol<>''){
                            $mostrar_unidad  = "show";
                            $mostrar_entidad = "none";
                        }
                    }
                @endphp
                <div id="div_mostrar_unidad" style="display:{{$mostrar_unidad}}">
                    <div class="row">
                        <div class="col-md-4">
                            {!! BootForm::select('unidad_administrativa_id', 'Unidad Administrativa: *',
                            $unidadesadministrativas,old("unidad_administrativa_id",$user->unidad_administrativa_id));!!}
                        </div>
                    </div>
                </div>
                <div id="div_mostrar_entidad" style="display:{{$mostrar_entidad}}">
                    <div class="row">
                        <div class="col-sm-6">
                        {!! BootForm::select("entidad_fiscalizable_id", "Entidad fiscalizable: *",$entidades_fiscalizables,
                        old("entidad_fiscalizable_id",$user->entidad_fiscalizable_id),['placeholder'=>'Seleccione la entidad fiscalizable' ,'class'=>'selection', 'width'=>'col-md-12']); !!}
                        </div>
                    </div>
                </div>

                @if ($user->exists)
                <div class="row">
                    <div class="col-md-5">
                        {!! BootForm::radios("estatus", "Estatus:",['Activo'=>' Activo','Inactivo'=>' Inactivo']
                        ,old("estatus", $user->estatus),false,['class'=>'i-checks']); !!}
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        @canany('user.store','user.update')
                            <button type="submit" class="btn btn-sistema">Guardar</button>
                        @endcanany
                        <a href="{{route('user.index')}}" class="text-danger text-right col-md-2">Cancelar</a>
                    </div>
                </div>
            {!! BootForm::close() !!}
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    <script>
        $(document).ready(function() {
            $.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" })
            $("#rol").change(function(){
                Ocultar(['#div_mostrar_unidad']);
                Ocultar(['#div_mostrar_entidad']);
                var rolSeleccionado = $(this).children("option:selected").val();
                if (rolSeleccionado=='Entidad Fiscalizable'){
                    Ocultar(['#div_mostrar_unidad']);
                    Mostrar(['#div_mostrar_entidad']);
                } else {
                   Ocultar(['#div_mostrar_entidad']);
                   Mostrar(['#div_mostrar_unidad']);
                }
            });
        });
    </script>
    {!! $validator !!}
@endsection
