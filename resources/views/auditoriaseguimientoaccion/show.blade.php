@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            Acción
        </h1>
    </div>
    <div class="card-body">
        <div class="pt-4">
            <div class="row">
                <div class="col-md-12">
                    {!! BootForm::textarea('accion', false,old('accion', $accion->accion),['rows'=>'10','readonly']) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
