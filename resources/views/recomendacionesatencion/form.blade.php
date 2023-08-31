@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('recomendacionesatencion.create') }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('recomendacionesacciones.index') }}"><i
                    class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Datos de atención
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        @include('layouts.contextos._accion')
        <h3 class="text-primary">Atención</h3> <br>
        <div class="row">
            {!! BootForm::open(['model' => $recomendacion,'store' => 'recomendacionesatencion.store','update' => 'recomendacionesatencion.update','id' =>
        'form',]) !!}
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::date('fecha_compromiso', 'Fecha compromiso de atención: *', old('fecha_compromiso', $recomendacion->fecha_compromiso)) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! BootForm::text('nombre_responsable', 'Nombre del responsable de atender las recomendaciones por parte de la entidad fiscalizable: *', old('nombre_responsable',$recomendacion->nombre_responsable)) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                {!! BootForm::text('cargo_responsable', 'Cargo del responsable de atender las recomendaciones por parte de la entidad fiscalizable: *', old('cargo_responsable',$recomendacion->cargo_responsable)) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! BootForm::text('analista_responsable', 'Responsable del seguimiento:*: *', old('analista_responsable',$accion->analista_asignado)) !!}
            </div>           
        </div>
        <div class="row">
            <div class="col-md-12">
                {!! BootForm::checkbox('check', ' Se envía a revisión con el superior', '', true, ['class' => 'i-checks', 'disabled', 'checked']) !!}
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12">
                {{-- @canany(['prasturno.store', 'prasturno.update']) --}}
                    {{-- @btnSubmit('Guardar') --}}
                {{-- @endcanany                 --}}
                @btnCancelar('Cancelar', route('prasacciones.index'))
            </div>
        </div>
        {!! BootForm::close() !!}
        </div>
        
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\PRASTurnosRequest') !!}
@endsection