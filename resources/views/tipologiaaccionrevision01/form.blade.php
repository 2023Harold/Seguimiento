@extends('layouts.appPopup')
@section('content')
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    Tipología revisión
                </h1>
            </div>
            <div class="card-body">
    <div class="card-body">
        @include('flash::message')
        <div>
        <h3 class="card-title text-primary">Acción</h3>
        <div class="card-body py-7">
            <div class="row">
                
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label>Tipo de acción: </label>
                    <span class="text-primary">
                        {{ $accion->tipo}}
                    </span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label>Acto de fiscalización: </label>
                    <span class="text-primary">
                        {{ $accion->acto_fiscalizacion}} 
                    </span>
                </div>                
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label>Tipología: </label>
                    <span class="text-primary">
                         {{ (empty($accion->tipologia_id)?'':$accion->tipologiadesc->tipologia) }}
                    </span>
                </div>                

                
            <hr/>
            {!! BootForm::open(['model' => $accion,'update'=>'tipologiaaccionrevision01.update','id'=>'form'] )!!}
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

