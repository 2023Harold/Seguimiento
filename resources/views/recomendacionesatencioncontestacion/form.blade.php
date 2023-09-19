@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('recomendacionescontestaciones.index') }}"><i
                class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            {{$accion}}
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        {!! BootForm::open(['model' => $contestacion, 'store' => 'recomendacionescontestaciones.store', 'update' => 'recomendacionescontestaciones.update', 'id' => 'form']) !!}
            <div class="row">
                <div class="col-md-12">
                    {!! archivo('oficio_contestacion', 'Oficio de contestación de la recomendación: *', old('oficio_contestacion', $contestacion->oficio_contestacion)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::date('fecha_oficio_contestacion', 'Fecha del oficio de contestación: *', old('fecha_oficio_contestacion', fecha($contestacion->fecha_oficio_contestacion, 'Y-m-d'))); !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit("Guardar")
                    @btnCancelar('Cancelar', route('recomendacionescontestaciones.index'))
                </div>
            </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\RecomendacionesContestacionRequest') !!}
@endsection
