@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('comparecenciacedula.edit',$comparecencia) }} 
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('comparecencia.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Cédula
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            @include('layouts.contextos._comparecencia')
            {!! BootForm::open(['model' => $comparecencia,'update' => 'comparecenciacedula.update','id' => 'form',]) !!}
            <div class="row">
                <div class="col-md-6">
                    {!! archivo('cedula_general','Cédula general: *',old('cedula_general', $comparecencia->cedula_general),) !!}
                </div>
                <div class="col-md-3">
                    {!! BootForm::date('fecha_cedula','Fecha de la cédula: *',old('fecha_cedula', $comparecencia->fecha_cedula),) !!}
                </div>
            </div>               
                <div class="row">
                    <div class="col-md-12">
                        @btnSubmit("Guardar")
                        @btnCancelar('Cancelar', route('comparecencia.index'))
                    </div>
                </div>
            {!! BootForm::close() !!}
        </div>
    </div>
@endsection
@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\ComparecenciaCedulaRequest') !!}  
@endsection
