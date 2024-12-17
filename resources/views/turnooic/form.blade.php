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
                    &nbsp; Turno al Órgano Interno de Control
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
                    <div class="col-md-5">
                        {!! BootForm::date('fecha_turno_oic', 'Fecha del turno al Organo Interno de Control *', old('fecha_turno_oic', fecha($turnooic->fecha_turno_oic, 'Y-m-d'))); !!}
                    </div>
                </div> 
                <div class="row">                    
                    <div class="col-md-4">
                        {!! BootForm::text('numero_turno_oic', 'Número de oficio: *', old('numero_turno_oic', $turnooic->numero_turno_oic)) !!}
                    </div>
                </div>       
                <div class="row">                    
                    <div class="col-md-4">
                        {!! BootForm::text('nombre_titular_oic', 'Nombre del titular a quien se dirige: *', old('nombre_titualar', $turnooic->nombre_titular_oic)) !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::text('cargo_titular_oic', 'Cargo del titular a quien se dirige: *', old('nombre_titular_oic', $turnooic->cargo_titular_oic)) !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::text('domicilio_oic', 'Domicilio: *', old('domicilio_oic', $turnooic->domicilio_oic)) !!}
                    </div>
                </div>                       
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('turno_oic', 'Acuse envío a notificar *', old('turno_oic', $turnooic->turno_oic)) !!}
                    </div>
                    <div class="col-md-5">
                        {!! BootForm::date('fecha_envio', 'Fecha del envío a notificar *', old('fecha_envio', fecha($turnooic->fecha_envio, 'Y-m-d'))); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('acuse_notificacion', 'Acuse de notificación *', old('acuse_notificacion', $turnooic->acuse_notificacion)) !!}
                    </div>
                    <div class="col-md-5">
                        {!! BootForm::date('fecha_notificacion', 'Fecha de notificación *', old('fecha_notificacion', fecha($turnooic->fecha_notificacion, 'Y-m-d'))); !!}
                    </div>
                </div>
                    
                <div class="row">
                    <div class="col-md-6"> 
                        @canany(['turnooic.store','turnooic.update'])
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
