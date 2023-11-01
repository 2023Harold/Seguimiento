@extends('layouts.app')
@section('breadcrums')
@if ($accion=='Agregar')
{{ Breadcrumbs::render('rol.create') }}
@else
{{ Breadcrumbs::render('rol.edit',$rol) }}
@endif
@endsection
@section('content')
@include('flash::message')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('rol.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;&nbsp;&nbsp;
            {{ $accion }}
        </h1>
    </div>
    <div class="card-body">
        {!! BootForm::open(['model' => $rol, 'store' => 'rol.store', 'update' => 'rol.update','id'=>'form']) !!}
        <div class="row">
            <div class="col-md-6">
                {!! BootForm::text("name", "Nombre del rol: *", old("name",$rol->name), ['maxlength' => '30']); !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                {{-- @if (auth()->user()->can('rol.store') || auth()->user()->can('rol.update')) --}}
                <button type="submit" name="enviar" class="btn btn-primary">Guardar</button>
                {{-- @endif --}}
                {{-- @acceso('rol.index') --}}
                <a href="{!! route('rol.index') !!}" class="text-danger text-right col-md-2">Cancelar</a>
                {{-- @endacceso --}}
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! $validator !!}
@endsection