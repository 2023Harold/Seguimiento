@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('informeacuses.edit',$informe,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('informeprimeraetapa.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; Informe Acuses

                </h1>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
               
                {!!BootForm::open(['model' => $informe,'store' => 'informeacuses.store','update' => 'informeacuses.update','id' => 'form']) !!}
                
                {!!BootForm::hidden('tipo',$tipo) !!}	
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('informe', 'Informe de seguimiento: *', old('informe', $informe->informe)) !!}
                    </div>
                </div>	
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('acuse_envio', 'Acuse envío a notificar: *', old('acuse_envio', $informe->acuse_envio)) !!}
                    </div>
                    <div class="col-md-5">
                        {!!BootForm::date('fecha_acuse_envio', 'Fecha Acuse envío a notificar: *', old('fecha_acuse_envio', fecha($informe->fecha_acuse_envio, 'Y-m-d'))) !!}
                    </div>
                </div>  
				<div class="row">
                    <div class="col-md-6">
                        {!! archivo('acuse_notificacion', 'Acuse de notificación: *', old('acuse_notificacion', $informe->acuse_notificacion)) !!}
                    </div>
                    <div class="col-md-5">
                        {!!BootForm::date('fecha_notificacion', 'Fecha de notificación: *', old('fecha_notificacion', fecha($informe->fecha_notificacion, 'Y-m-d'))) !!}
                    </div>
                </div> 
				
                <div class="row">
                    <div class="col-md-6"> 
                        @canany(['informeacuses.store','informeacuses.update'])
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
{!!JsValidator::formRequest('App\Http\Requests\InformePrimeraEtapaRequest') !!}
@endsection
