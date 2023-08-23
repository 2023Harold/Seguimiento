@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('comparecenciaacta.edit',$comparecencia) }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('comparecencia.index') }}"><i
                    class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Acta
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        @include('layouts.contextos._comparecencia')
        {!! BootForm::open(['model' => $comparecencia,'update' => 'comparecenciaacta.update','id' => 'form',]) !!}
        <div class="row">
            <div class="col-md-6">
                {!! archivo('oficio_acta', 'Acta de comparecencia: *', old('oficio_acta', $comparecencia->oficio_acta))
                !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::text('numero_acta', 'Número de acta: *', old('numero_acta', $comparecencia->numero_acta));
                !!}
            </div>
            <div class="col-md-3">
                {!! BootForm::date('fecha_acta', 'Fecha del acta: *', old('fecha_comparecencia',
                fecha($comparecencia->fecha_comparecencia, 'Y-m-d')), ['readonly']); !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::time('hora_comparecencia_termino','Hora de término de la comparecencia:
                *',old('hora_comparecencia_termino', $comparecencia->hora_comparecencia_termino)) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! archivo('oficio_acreditacion', 'Oficio de acreditación: *', old('oficio_acreditacion',
                $comparecencia->oficio_acreditacion)) !!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                {!! BootForm::text('nombre_representante','Nombre del representante: *',old('nombre_representante',
                $comparecencia->nombre_representante),)!!}
            </div>
            <div class="col-md-4">
                {!! BootForm::text('cargo_representante','Cargo del representante 1: *',old('cargo_representante',
                $comparecencia->cargo_representante),) !!}
            </div>
            <div class="col-md-4">
                {!! BootForm::text('numero_identificacion_representante','Número de identificación del
                representante:
                *',old('numero de identificacion_testigo1', $comparecencia->numero_identificacion_representante),)
                !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::text('nombre_testigo1','Nombre del testigo 1: *',old('nombre_testigo1',
                $comparecencia->nombre_testigo1),)!!}
            </div>
            <div class="col-md-4">
                {!! BootForm::text('cargo_testigo1','Cargo del testigo 1: *',old('cargo_testigo1',
                $comparecencia->cargo_testigo1),) !!}
            </div>
            <div class="col-md-4">
                {!! BootForm::text('numero_identificacion_testigo1','Número de identificación del testigo 1:
                *',old('numero de identificacion_testigo1', $comparecencia->numero_identificacion_testigo1),) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::text('nombre_testigo2','Nombre del testigo 2: *',old('nombre_testigo2',
                $comparecencia->nombre_testigo1),) !!}
            </div>
            <div class="col-md-4">
                {!! BootForm::text('cargo_testigo2','Cargo del testigo 2: *',old('cargo_testigo2',
                $comparecencia->cargo_testigo2),) !!}
            </div>
            <div class="col-md-4">
                {!! BootForm::text('numero_identificacion_testigo2','Número de identificación del testigo 2:
                *',old('numero de identificacion_testigo1', $comparecencia->numero_identificacion_testigo2),) !!}
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @can('comparecenciaacta.update')
                @btnSubmit("Guardar")
                @endcan
                @btnCancelar('Cancelar', route('comparecencia.index'))
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\ComparecenciaActaRequest') !!}
@endsection