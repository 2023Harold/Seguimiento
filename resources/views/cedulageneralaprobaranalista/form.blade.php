@extends('layouts.appPopup')
@section('content')
<div class="row">   
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    &nbsp; Cédula General
                </h1>
            </div>
            <div class="card-body">                            
                {!! BootForm::open(['model' => $cedula,'update'=>'cedulainicialprimeraanalista.update','id'=>'form'] )!!}
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
                        <button type="submit" class="btn btn-primary">Guardar</button>                       
                    </div>
                </div>
            {!! BootForm::close() !!}
            </div>
        </div>
    </div>    
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\AprobarFlujoAutorizacionRequest') !!}
@endsection