@extends('layouts.app')
@section('breadcrums')
@if (empty($turnotransferencia->turnotransferencia))
    {{ Breadcrumbs::render('turnotransferencia.create') }}
@else
    {{ Breadcrumbs::render('turnotransferencia.edit',$turnotransferencia) }}
@endif    
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('turnotransferencia.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; Turno acuse envío al archivo
                </h1>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['model' => $turnotransferencia,'store' => 'turnotransferencia.store','update' => 'turnotransferencia.update','id' => 'form']) !!}
                <div class="row">                   
                    <div class="col-md-4">
                        {!! BootForm::text('numero_transferencia', 'Número de oficio: *', old('numero_transferencia', $turnotransferencia->numero_transferencia)) !!}
                    </div>
                </div> 
                <div class="row"> 
                    <div class="row">
                        <div class="col-md-5">
                            {!! archivo('inventario_transferencia', 'Inventario de documentos *', old('turnoarchivo', $turnotransferencia->inventario_transferencia)) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::date('fecha_transferencia', 'Fecha de transferencia', old('fecha_transferencia', fecha($turnotransferencia->fecha_transferencia, 'Y-m-d'))); !!}
                        </div>
                    </div>
                </div>        
                <div class="row">                   
                    <div class="col-md-4">
                        {!! BootForm::text('tiempo_resguardo', 'Tiempo de resguardo: *', old('tiempo_resguardo', $turnotransferencia->tiempo_resguardo)) !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::text('clave_topografica', 'Clave topográfica: *', old('clave_topografica', $turnotransferencia->clave_topografica)) !!}
                    </div>
                </div>                     
                <div class="row">
                    <div class="col-md-6"> 
                        @canany(['turnotransferencia.store','turnoarchivo.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcanany
                        <a href="{{ route('turnotransferencia.index') }}" class="btn btn-secondary me-2">Cancelar</a>
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
