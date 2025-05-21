@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">           
           Agregar Cedula {{$tipo}}
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        {!!BootForm::open(['model' => $cedulaA, 'store' => 'agregarcedula.store', 'update' => 'agregarcedula.update', 'id' => 'form']) !!}
            <div class="row">
                {!!BootForm::hidden('cedula_tipo', $tipo) !!}
                {!! archivo('cedula_cargada', 'Cédula de '.$tipo,  old('cedula_cargada', $auditoria->cedula)) !!}
            </div>
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit("Guardar")
                </div>
            </div>
        {!!BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')

@endsection
