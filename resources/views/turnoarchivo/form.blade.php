@extends('layouts.app')
@section('breadcrums')
@if (empty($turnoarchivo->numero_ordenauditoria))
    {{ Breadcrumbs::render('turnoarchivo.create') }}
@else
    {{ Breadcrumbs::render('turnoarchivo.edit',$turnoarchivo) }}
@endif    
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('turnoarchivo.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; Turno acuse envío al archivo
                </h1>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['model' => $turnoarchivo,'store' => 'turnoarchivo.store','update' => 'turnoarchivo.update','id' => 'form']) !!}
       
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('turnoarchivo', 'Acuse envío al archivo: *', old('turnoarchivo', $turnoarchivo->turnoarchivo)) !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::text('numero_turno_archivo', 'Número del Acuse del envío al archivo: *', old('numero_turno_archivo', $turnoarchivo->turnoarchivo)) !!}
                    </div>
                </div>       
                <div class="row">
                    <div class="col-md-5">
                        {!! BootForm::date('fecha_turno_archivo', 'Fecha del Acuse del envío al archivo *', old('fecha_turno_archivo', fecha($turnoarchivo->fecha_turno_archivo, 'Y-m-d'))); !!}
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-6"> 
                        @canany(['turnoarchivo.store','turnoarchivo.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcanany
                        <a href="{{ route('turnoarchivo.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    </div>
                </div>
                {!! BootForm::close() !!}    
            </div>    
        </div>  
    </div>
</div> 
@endsection
@section('script')
{{-- {!! JsValidator::formRequest('App\Http\Requests\InformePrimeraEtapaRequest') !!} --}}
@endsection
