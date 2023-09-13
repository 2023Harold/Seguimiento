@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('solicitudesaclaraciondocumentos.index') }}"><i
                class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            {{$accion}}
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        {!! BootForm::open(['model' => $documento, 'store' => 'solicitudesaclaraciondocumentos.store', 'update' => 'solicitudesaclaraciondocumentos.update', 'id' => 'form']) !!}
            <div class="row">
                <div class="col-md-12">
                    {!! BootForm::text('nombre_archivo', 'Nombre del documento: *', old("nombre_archivo", $documento->nombre_archivo))!!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit("Guardar")
                    @btnCancelar('Cancelar', route('solicitudesaclaraciondocumentos.index'))
                </div>
            </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\SolicitudesAclaracionDocumentoRequest') !!}
@endsection
