@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('prasmedida.edit',$auditoria,$pras) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                <a href="{{ route('prasturno.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Medida de apremio
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            @include('layouts.contextos._accion')
            @include('layouts.contextos._pras')
            {!! BootForm::open(['model' => $pras,'update' => 'prasmedida.update','id' => 'form',]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('oficio_medida_apremio', 'Medida de apremio: *', old('oficio_medida_apremio', $pras->oficio_medida_apremio)) !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_acuse_medida_apremio', 'Fecha del acuse de la medida de apremio: *', old('fecha_acuse_medida_apremio', fecha($pras->fecha_acuse_medida_apremio, 'Y-m-d'))); !!}
                    </div>
                </div>                
                <div class="row">
                    <div class="col-md-12">                       
                        @btnSubmit("Guardar")
                        @btnCancelar('Cancelar', route('prasturno.index'))
                    </div>
                </div>
            {!! BootForm::close() !!}
        </div>
    </div>
@endsection
@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\PRASMedidasRequest') !!}    
@endsection
