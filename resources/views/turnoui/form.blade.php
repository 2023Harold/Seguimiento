@extends('layouts.app')
@section('breadcrums')
@if (empty($tuenoui->numero_ordenauditoria))
    {{ Breadcrumbs::render('turnoui.create') }}
@else
    {{ Breadcrumbs::render('turnoui.edit',$turnoui) }}
@endif    
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('turnoui.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; Turno a la Unidad de Investigación
                </h1>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['model' => $turnoui,'store' => 'turnoui.store','update' => 'turnoui.update','id' => 'form']) !!}
       
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('turnoui', 'Turno a la Unidad de Investigación: *', old('turnoui', $turnoui->turnoui)) !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::text('numero_turno_ui', 'Número del turno a la Unidad de Investigación: *', old('numero_turno_ui', $turnoui->turnoui)) !!}
                    </div>
                </div>       
                <div class="row">
                    <div class="col-md-5">
                        {!! BootForm::date('fecha_turno_ui', 'Fecha del turno a la Unidad de Investigación *', old('fecha_turno_ui', fecha($turnoui->fecha_turno_ui, 'Y-m-d'))); !!}
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-6"> 
                        @canany(['turnoui.store','turnoui.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcanany
                        <a href="{{ route('turnoui.index') }}" class="btn btn-secondary me-2">Cancelar</a>
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
