@extends('layouts.app')
@section('breadcrums')
    
@endsection
@section('content')
@include('flash::message')
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-header">  
            <h1 class="card-title py-2">                        
                <a class="text-primary" href="{{ route('user.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>                        
                    &nbsp;&nbsp;&nbsp; Usuario
            </h1>             
        </div>
        <div class="card-body">
            @include('flash::message')
            {!! BootForm::open(['model' => $user, 'store' => 'user.store', 'update' => 'user.update','id'=>'form']) !!}
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
                        {!! BootForm::select('rol','Rol: *' ,$roles->toArray(),old("rol",$values[0])); !!}
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
                            $unidadesadministrativas->toArray(),old("unidad_administrativa_id",$user->unidad_administrativa_id));!!}
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
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcanany
                        <a href="{{route('user.index')}}" class="btn btn-secondary me-2">Cancelar</a>
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
