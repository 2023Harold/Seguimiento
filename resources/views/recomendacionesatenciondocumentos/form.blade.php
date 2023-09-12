@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('recomendacionesdocumentos.index') }}"><i
                class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            {{$accion}}
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        {!! BootForm::open(['model' => $documento, 'store' => 'recomendacionesdocumentos.store', 'update' => 'recomendacionesdocumentos.update', 'id' => 'form']) !!}
            <div class="row">
                <div class="col-md-12">
                    {!! BootForm::text('nombre_documento', 'Nombre del documento: *', old("nombre_documento", $documento->nombre_documento))!!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit("Guardar")
                    @btnCancelar('Cancelar', route('recomendacionesdocumentos.index'))
                </div>
            </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\RecomendacionesDocumentoRequest') !!}
@endsection
