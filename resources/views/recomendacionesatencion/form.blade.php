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
        <h3 class="text-primary">Atención de la recomendación</h3> <br>
        <div class="row">
            {!! BootForm::open(['model' => $recomendacion,'store' => 'recomendacionesatencion.store','update' =>
            'recomendacionesatencion.update','id' =>
            'form',]) !!}           
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::text('nombre_responsable', 'Nombre del responsable de atender las recomendaciones por
                    parte de la entidad fiscalizable: *', old('nombre_responsable',$recomendacion->nombre_responsable))
                    !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::text('cargo_responsable', 'Cargo del responsable de atender las recomendaciones por
                    parte de la entidad fiscalizable: *', old('cargo_responsable',$recomendacion->cargo_responsable))
                    !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::text('analista_responsable', 'Responsable del seguimiento:*',
                    old('analista_responsable',$accion->analista->name),['readonly']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! archivo('oficio_contestacion', 'Oficio de contestacion de la recomendación: *',
                    old('oficio_contestacion', $recomendacion->oficio_contestacion)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    {!! BootForm::text('analisis', 'Análisis *',
                    old('analisis', $recomendacion->analisis)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    {!! BootForm::text('conclusion', 'Conclusión: *',
                    old('conclusion', $recomendacion->conclusion)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit('Guardar',route('recomendacionesatencion.store'))
                    @btnCancelar('Cancelar', route('recomendacionesacciones.index'))
                </div>
            </div>
            {!! BootForm::close() !!}
        </div>
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\RecomendacionesRequest') !!}
@endsection