@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">           
            {{$accion}}
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        {!! BootForm::open(['model' => $comentario, 'store' => 'revisionessolicitudes.store', 'update' => 'revisionessolicitudes.update', 'id' => 'form']) !!}
            <div class="row">
                <div class="col-md-12">
                    {!! BootForm::textarea('comentario', 'Comentario: *', old("comentario", $comentario->comentario))!!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit("Guardar")
                </div>
            </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\RevisionRequest') !!}
@endsection
