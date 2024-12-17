@extends('layouts.app')
@section('breadcrums')
@if ($accion=='Agregar')
{{ Breadcrumbs::render('permiso.create') }}
@else
{{ Breadcrumbs::render('permiso.edit',$permiso) }}
@endif
@endsection
@section('content')
<div class="container-fluid">
    @include('flash::message')
    <div class="card">
        <div class="card-header">           
            <div class="row ">
                <h1 class="card-title">
                    <a href="{{ route('permiso.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;&nbsp;&nbsp;
                    {{ $accion.' permiso'}}
                </h1>
            </div>
        </div>
        <div class="card-body">
            {!! BootForm::open(['model' => $permiso, 'store' => 'permiso.store', 'update' =>
            'permiso.update','id'=>'form']) !!}
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::text("name", "Nombre del permiso: *", old("name",$permiso->name), ['maxlength' => '50']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {{-- @if (auth()->user()->can('permiso.store') || auth()->user()->can('permiso.update')) --}}
                    <button type="submit" name="enviar" class="btn btn-primary">Guardar</button>
                    {{-- @endif --}}
                    {{-- @acceso('permiso.index') --}}
                    <a href="{!! route('permiso.index') !!}" class="text-danger text-right col-md-2">Cancelar</a>
                    {{-- @endacceso --}}
                </div>
            </div>
            {!! BootForm::close() !!}
        </div>
    </div>
</div>
@endsection
@section('script')
<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! $validator !!}

@endsection