@extends('layouts.appPopup')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">                   
                    Autorizar - Rechazar
                </h1>
            </div>
            <div class="card-body">         
                {!! BootForm::open(['model' => $auditoria,'update'=>'seguimientoauditoriaautorizacion.update','id'=>'form'] )!!}
                {{-- {!! BootForm::hidden('archivo_firmar',$b64archivoxml,['id'=>'archivo_firmar'])!!} --}}
                <div id="campos">
                    <div class="row">
                        <div class="col-md-6">
                            {!! BootForm::radios("estatus", ' ', ['Aprobado' => 'Autorizar', 'Rechazado' => 'Rechazar'],
                            null,false,['class'=>'i-checks rechazado']); !!}
                        </div>
                    </div>
                    <div class="row" id="justificacion" style="display: none;">
                        <div class="col-md-12">
                            {!! BootForm::textarea('motivo_rechazo','Motivo del rechazo:*','',["rows" => "2", "style" =>
                            "rezise:none"])!!}
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-6">
                            {!! archivoFirma('certificate_file', 'Certificado digital: *',
                            null,['data-allowedFileExtensions' => 'cer', 'accept'=>'.cer', 'class'=>'key']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! archivoFirma('privkey_file', 'Llave pública: *', null,['data-allowedFileExtensions' =>
                            'key', 'accept'=>'.key', 'class'=>'key']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! BootForm::password("password", "Contraseña:", ['autocomplete'=>'off',
                            'class'=>'enviar-firma']) !!}
                        </div>
                    </div> --}}
                </div>
                {{-- {!! camposFirma() !!} --}}
                <div class="row">
                    <div class="col-md-12">
                        {{-- <button type="button" id='btn-firma' class="btn btn-primary"
                            onclick="ConfirmFirma();">Firmar y guardar</button> --}}
                        <button type="submit" class="btn btn-primary">Guardar</button>                        
                        {{-- @btnCancelar('Cancelar', route('citardenunciantecita.index')) --}}
                    </div>
                </div>
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\AutorizarFlujoAutorizacionRequest') !!}
{{-- {!! JsValidator::formRequest('App\Http\Requests\FlujoAutorizacionRequest') !!} --}}
{{-- <script type="text/javascript" src="{{ asset('assets/js/signData.js')}}"></script>
@include('layouts.partials._firma') --}}
@endsection