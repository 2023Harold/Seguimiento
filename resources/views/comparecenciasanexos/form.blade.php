@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">
            @can('preconfrontaanexo.index') 
                @btnBack(route('comparecenciaanexo.index'))
            @endcan
            {{$accion.' anexo'}}
        </h4>
    </div>
    <div class="card-body">
        @include('flash::message')
        {!! BootForm::open(['model' => $anexo, 'store' => 'comparecenciaanexo.store', 'update' => 'comparecenciaanexo.update', 'id' => 'form']) !!}
            <div class="row">
                <div class="col-md-12">
                    {!! archivo('archivo', 'Anexo: *', old('archivo', $anexo->archivo),['data-allowedFileExtensions' => 'pdf', 'required']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! BootForm::textarea('descripcion', 'Descripción:*', old("descripcion", $anexo->descripcion),["rows" => "2", "style" => "rezise:none"])!!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit("Guardar")
                    @btnCancelar('Cancelar', route('comparecenciaanexo.index'))
                </div>
            </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\ComparecenciaAnexoRequest') !!}
@endsection
