@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('informeprimeraetapaautorizacion.edit',$informeprimeraetapa) }}  
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('informeprimeraetapa.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; Validar
                </h1>            
            </div>        
            <div class="card-body">
                @include('flash::message')                
                @include('layouts.contextos._informeprimeraetapa')                    
                
                {!! BootForm::open(['model' => $informeprimeraetapa,'update'=>'informeprimeraetapaautorizacion.update','id'=>'form'] )!!}
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::radios("estatus", ' ',
                        [ 
                            'Aprobado' => 'Aprobar',
                            'Rechazado' => 'Rechazar'
                        ], null,false,['class'=>'i-checks rechazado']); !!}
                    </div>
                </div>
                <div class="row" id="justificacion" style="display: none;">
                    <div class="col-md-12">
                        {!! BootForm::textarea('motivo_rechazo','Motivo del rechazo:*','',["rows" => "2", "style" => "rezise:none"])!!}
                    </div>
                </div>                               
                <div class="row mt-3">
                    <div class="col-md-6 justify-content-end">
                        @can('informeprimeraetapaautorizacion.update')
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcan 
                        <a href="{{ route('informeprimeraetapa.index') }}" class="btn btn-secondary me-2">Cancelar</a>
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
