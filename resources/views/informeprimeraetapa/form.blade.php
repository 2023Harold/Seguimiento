@extends('layouts.app')
@section('breadcrums')
@if (empty($informeprimeraetapa->numero_ordenauditoria))
    {{Breadcrumbs::render('informeprimeraetapa.create') }}
@else
    {{Breadcrumbs::render('informeprimeraetapa.edit',$informeprimeraetapa,$informepliegos) }}
@endif    
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('informeprimeraetapa.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; informe Primera Etapa            

                </h1>
                <div class="float-end">
                    <a href="{{route('informeprimeraetapa.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;IS</a>                                  
                </div>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
               
                {!!BootForm::open(['model' => $informeprimeraetapa,'store' => 'informeprimeraetapa.store','update' => 'informeprimeraetapa.update','id' => 'form']) !!}
                
                {!!BootForm::hidden('tipo',$tipo) !!}
            
                                
                <div class="row">
                    <div class="col-md-5">
                        {!!BootForm::date('fecha_informe', 'Fecha del oficio de notificación del informe de seguimiento: *', old('fecha_informe', fecha($informeprimeraetapa->fecha_informe, 'Y-m-d'))) !!}
                    </div>
                </div> 
				<div class="row">                    
                    <div class="col-md-4">
                        {!!BootForm::text('numero_informe', 'Número de oficio de notificación: *', old('numero_informe', $informeprimeraetapa->numero_informe)) !!}
                    </div>
                </div> 
                     
                <div class="row">                    
                    <div class="col-md-4">
                        {!!BootForm::text('nombre_titular_informe', 'Nombre del titular a quien se dirige: *', old('nombre_titular_informe', $informeprimeraetapa->nombre_titular_informe)) !!}
                    </div>
                    <div class="col-md-4">
                        {!!BootForm::text('cargo_titular_informe', 'Cargo del titular a quien se dirige: *', old('cargo_titular_informe', $informeprimeraetapa->cargo_titular_informe)) !!}
                    </div>
                    <div class="col-md-4">
                        {!!BootForm::text('periodo_gestion', 'Periodo de Gestión: *', old('periodo_gestion', $informeprimeraetapa->periodo_gestion)) !!}
                    </div>
                    <div class="col-md-4">
                        {!!BootForm::text('domicilio_informe', 'Domicilio: *', old('domicilio_informe', $informeprimeraetapa->domicilio_informe)) !!}
                    </div>
                </div>       
                <div class="row">                    
                    <div class="col-md-4">
                        {!!BootForm::text('numero_fojas', 'Número de fojas: *', old('numero_fojas', $informeprimeraetapa->numero_fojas)) !!}
                    </div>
                </div>
				
				<div class="row">
                    <div class="col-md-6">
                        {!! archivo('informe', 'Informe de seguimiento: *', old('informe', $informeprimeraetapa->informe)) !!}
                    </div>
                </div>		
                <div class="row">
                    <div class="col-md-6"> 
                        @canany(['informeprimeraetapa.store','informeprimeraetapa.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcanany
                        <a href="{{ route('informeprimeraetapa.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    </div>
                </div>
                {!!BootForm::close() !!}    
            </div>    
        </div>  
    </div>
</div> 
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\InformePrimeraEtapaRequest') !!}
@endsection
