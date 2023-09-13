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
        {!! BootForm::open(['model' => $solicitud, 'store' => 'solicitudesaclaracioncontestacion.store', 'update' => 'solicitudesaclaracioncontestacion.update', 'id' => 'form']) !!}
            <div class="row">
                <div class="col-md-12">
                    {!! archivo('oficio_atencion', 'Oficio de contestación de la solicitud de aclaración: *', old('oficio_atencion', $solicitud->oficio_atencion)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    {!! BootForm::date('fecha_oficio_atencion', 'Fecha del oficio de atención: *', old('fecha_oficio_atencion', fecha($solicitud->fecha_oficio_atencion, 'Y-m-d'))); !!}
                </div>
            </div>       
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit("Guardar")
                    @btnCancelar('Cancelar', route('solicitudesaclaracioncontestacion.show',0))
                </div>
            </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\SolicitudesAclaracionContestacionRequest') !!}
@endsection
