@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('prasturno.create') }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('prasacciones.index') }}"><i
                    class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Turnar PRAS a OIC o equivalente
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        {!! BootForm::open(['model' => $pras,'store' => 'prasturno.store','update' => 'prasturno.update','id' =>
        'form',]) !!}
        <div class="row">
            <div class="col-md-6">
                {!! BootForm::text('nombre_titular_oic','Nombre del titular del Órgano Interno de Control:
                *',old('nombre_titular_oic', (empty($pras->nombre_titular_oic)?$nombreuseroic:$pras->nombre_titular_oic)),) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! archivo('oficio_remision','Oficio de turno: *',old('oficio_remision',
                $pras->oficio_remision),['data-allowedFileExtensions' => 'pdf'],) !!}
            </div>
            <div class="col-md-3">
                {!! BootForm::date('fecha_acuse_oficio','Fecha del oficio: *',old('fecha_acuse_oficio',
                fecha($pras->fecha_acuse_oficio, 'Y-m-d'))) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::text('numero_oficio', 'Número del oficio: *', old('numero_oficio', $pras->numero_oficio))
                !!}
            </div>
        </div>       
        <div class="row">
            <div class="col-md-6">
                {!! BootForm::text('nombre_unidad','Área de adscripción del firmante:
                *',auth()->user()->titular->unidadAdministrativa->descripcion,['disabled']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! BootForm::text('nombre_firmante', 'Nombre del firmante: *',
                auth()->user()->titular->name,['disabled']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! BootForm::text('categoria_firmante', 'Categoría del firmante: *',
                auth()->user()->titular->puesto,['disabled']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @canany(['prasturno.store', 'prasturno.update'])
                    @btnSubmit('Guardar')
                @endcanany                
                @btnCancelar('Cancelar', route('prasacciones.index'))
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\PRASTurnosRequest') !!}
@endsection