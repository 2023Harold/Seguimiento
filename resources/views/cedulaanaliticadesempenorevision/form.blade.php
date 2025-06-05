@extends('layouts.appPopup')
@section('content')
<div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    &nbsp; Cédula Analítica de Desempeño
                </h1>
            </div>
            <div class="card-body">               
            @include('flash::message')
            @include('layouts.contextos._auditoria', $auditoria) 
            @include('layouts.contextos._cedulas')                    
                {!! BootForm::open(['model' => $cedula,'update'=>'cedulaanaliticadesempenorevision.update','id'=>'form'] )!!}
                <div class="row" style="padding-left: 2rem;">
                    <div class="col-md-6">                                       
                        {!! BootForm::radios("estatus", ' ',
                        [
                            'Aprobado' => 'Aprobar',
                            'Rechazado' => 'Rechazar'
                        ], null,false,['class'=>'i-checks rechazado']); !!}
                    </div>
                </div>
                <div class="row" id="justificacion" style="display: none; padding-left: 2rem;">
                    <div class="col-md-12">
                        {!! BootForm::textarea('motivo_rechazo','Motivo del rechazo:*','',["rows" => "2", "style" => "rezise:none"])!!}
                    </div>
                </div>                
                <div class="row mt-3" style="padding-left: 2rem;">
                    <div class="col-md-6 justify-content-end">
                    @canany(['cedulaanaliticadesempenorevision.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                    @endcanany                
                    <a href="{{ route('cedulainicial.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                </div>
            {!! BootForm::close() !!}            
    </div>    
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\AprobarFlujoAutorizacionRequest') !!}
@endsection