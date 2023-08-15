@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('comparecenciaacuse.edit',$comparecencia) }} 
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('radicacion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Acuses
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            @include('layouts.contextos._radicacion')
            {!! BootForm::open(['model' => $comparecencia,'update' => 'comparecenciaacuse.update','id' => 'form',]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('oficio_recepcion', 'Comprobante de recepción depto. de notificaciones: *', old('oficio_recepcion', $comparecencia->oficio_recepcion)) !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_recepcion', 'Fecha de recepción: *', old('fecha_recepcion', fecha($comparecencia->fecha_recepcion, 'Y-m-d'))); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('oficio_acuse', 'Acuse de la radicación y comparecencia: *', old('oficio_acuse', $comparecencia->oficio_acuse)) !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_acuse', 'Fecha del acuse: *', old('fecha_acuse', fecha($comparecencia->fecha_acuse, 'Y-m-d'))); !!}
                    </div>
                </div>                
                <div class="row">
                    <div class="col-md-12">
                        {{-- @can('comparecenciaacuse.update')  --}}
                            @btnSubmit("Guardar")
                        {{-- @endcan --}}
                        @btnCancelar('Cancelar', route('radicacion.index'))
                    </div>
                </div>
            {!! BootForm::close() !!}
        </div>
    </div>
@endsection
@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\ComparecenciaAcuseRequest') !!}  
@endsection
