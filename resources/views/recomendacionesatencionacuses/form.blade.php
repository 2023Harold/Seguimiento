@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('recomendacionesacuses.edit',$recomendacion) }} 
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('recomendacionesatencion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Acuses
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            @include('layouts.contextos._accion')
            @include('layouts.contextos._recomendacion')
            {!! BootForm::open(['model' => $recomendacion,'update' => 'recomendacionesacuses.update','id' => 'form',]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('oficio_comprobante', 'Comprobante de recepción depto. de notificaciones: *', old('oficio_comprobante', $recomendacion->oficio_comprobante)) !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_comprobante', 'Fecha del comprobante: *', old('fecha_recepcion', fecha($recomendacion->fecha_comprobante, 'Y-m-d'))); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('oficio_acuse', 'Acuse del oficio: *', old('oficio_acuse', $recomendacion->oficio_acuse)) !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_acuse', 'Fecha del acuse: *', old('fecha_acuse', fecha($recomendacion->fecha_acuse, 'Y-m-d'))); !!}
                    </div>
                </div>       
                <div class="row">
                    <div class="col-md-12">
                        {{-- @can('comparecenciaacuse.update')  --}}
                            @btnSubmit("Guardar")
                        {{-- @endcan --}}
                        @btnCancelar('Cancelar', route('recomendacionesatencion.index'))
                    </div>
                </div>
            {!! BootForm::close() !!}
        </div>
    </div>
@endsection
@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\RecomendacionesAtencionAcusesRequest') !!}    
@endsection
