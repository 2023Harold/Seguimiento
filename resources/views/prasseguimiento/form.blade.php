@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('prasseguimiento.edit',$auditoria,$pras) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                <a href="{{ route('prasturno.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Seguimiento
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            @include('layouts.contextos._accion')
            @include('layouts.contextos._pras')
            {!!BootForm::open(['model' => $pras,'update' => 'prasseguimiento.update','id' => 'form',]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('oficio_contestacion', 'Contestación OIC: *', old('oficio_contestacion', $pras->oficio_contestacion)) !!}
                    </div>
                    <div class="col-md-3">
                        {!!BootForm::date('fecha_acuse_contestacion', 'Fecha del acuse de contestación: *', old('fecha_acuse_contestacion', fecha($pras->fecha_acuse_contestacion, 'Y-m-d'))) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!!BootForm::radios("estatus_cumplimiento", ' Estatus de cumplimiento: *', ['Atendido'=>'Atendido', 'No Atendido'=>'No Atendido'],old('estatus_cumplimiento',$pras->estatus_cumplimiento),false,['class'=>'i-checks']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!!BootForm::textarea('conlusion_pras', 'Conclusión: *',old('conlusion_pras', $pras->conlusion_pras),['rows'=>'10']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">                       
                        @btnSubmit("Guardar")
                        @btnCancelar('Cancelar', route('prasturno.index'))
                    </div>
                </div>
            {!!BootForm::close() !!}
        </div>
    </div>
@endsection
@section('script')
    {!!JsValidator::formRequest('App\Http\Requests\PRASSeguimientoRequest') !!}    
@endsection
