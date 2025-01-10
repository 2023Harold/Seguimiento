@extends('layouts.app')
@section('breadcrums')
@if (empty($turnoarchivotransferencia->turnoarchivotransferencia))
    {{ Breadcrumbs::render('turnoarchivotransferencia.create') }}
@else
    {{ Breadcrumbs::render('turnoarchivotransferencia.edit',$turnoarchivotransferencia) }}
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
                    &nbsp; Turno envío al archivo de Trasferencia
                </h1>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._turnoarchivo')
                {!! BootForm::open(['model' => $turnoarchivotransferencia,'store' => 'turnoarchivotransferencia.store','update' => 'turnoarchivotransferencia.update','id' => 'form']) !!}               
                <div class="row"> 
                    <div class="row">
                        <div class="col-md-5">
                            {!! archivo('inventario_transferencia', 'Inventario de documentos *', old('inventario_transferencia', $turnoarchivotransferencia->inventario_transferencia)) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::date('fecha_transferencia', 'Fecha de transferencia', old('fecha_transferencia', fecha($turnoarchivotransferencia->fecha_transferencia, 'Y-m-d'))); !!}
                        </div>
                    </div>
                </div>        
                <div class="row">                   
                    <div class="col-md-4">
                        {!! BootForm::text('tiempo_resguardo', 'Tiempo de resguardo: *', old('tiempo_resguardo', $turnoarchivotransferencia->tiempo_resguardo)) !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::text('clave_topografica', 'Clave topográfica: *', old('clave_topografica', $turnoarchivotransferencia->clave_topografica)) !!}
                    </div>
                </div>                     
                <div class="row">
                    <div class="col-md-6">                        
                        @canany(['turnoarchivotransferencia.store','turnoarchivotrasferencia.update'])
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
{{-- {!! JsValidator::formRequest('App\Http\Requests\AcuseArchivoTransferenciaRequest') !!} --}}
@endsection
