@extends('layouts.app')
@section('breadcrums')
@if (empty($turnooic->numero_ordenauditoria))
    {{ Breadcrumbs::render('turnooic.create') }}
@else
    {{ Breadcrumbs::render('turnooic.edit',$turnooic) }}
@endif    
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('turnooic.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; Turno al Organo Interno de Control
                </h1>
                <div class="float-end">                    
                    <a href="{{route('turnooic.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;Of. R_OICs</a>                         
                </div>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['model' => $turnooic,'store' => 'turnooic.store','update' => 'turnooic.update','id' => 'form']) !!}
       
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('turnooic', 'Turno al Organo Interno de Conrtrol *', old('turnooic', $turnooic->turnooic)) !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::text('numero_turno_oic', 'Número del turno al Organo Interno de Control: *', old('numero_turno_oic', $turnooic->turnooic)) !!}
                    </div>
                </div>       
                <div class="row">
                    <div class="col-md-5">
                        {!! BootForm::date('fecha_turno_oic', 'Fecha del turno al Organo Interno de Control *', old('fecha_turno_oic', fecha($turnooic->fecha_turno_oic, 'Y-m-d'))); !!}
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-6"> 
                        @canany(['turnooic.store','turno_oic.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcanany
                        <a href="{{ route('turnooic.index') }}" class="btn btn-secondary me-2">Cancelar</a>
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
