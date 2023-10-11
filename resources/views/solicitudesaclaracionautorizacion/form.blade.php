@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('solicitudesaclaracionautorizacion.edit', $solicitud)}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">
                        <a href="{{ route('solicitudesaclaracionatencion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                        Autorizar
                    </h1>
                </div>
                <div class="card-body">
                    @include('flash::message')
                    @include('layouts.contextos._auditoria')
                    @include('layouts.contextos._accion')
                    @include('layouts.contextos._solicitud')
                    <div class="row">
                        <div class="col-md-12">
                            <div id="body_loader" class="body_loader" style="display:none;">
                                <span class="loader"></span>
                                <span> Firmando la constancia, por favor espere.</span>
                            </div>
                            <embed src="{{asset($preconstancia)}}" type="application/pdf" width="100%" height="600px"/>
                        </div>
                    </div>
                    <div class="" style="padding-left: 2rem; ">
                        <div class="row">                        
                            {!! BootForm::open(['model' => $solicitud,'update'=>'solicitudesaclaracionautorizacion.update','id'=>'form'] )!!}
                            {!! BootForm::hidden('archivo_firmar',$b64archivoxml,['id'=>'archivo_firmar'])!!}
                                <div id="campos">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! BootForm::radios("estatus", ' ', ['Aprobado' => 'Autorizar', 'Rechazado' => 'Rechazar'], null,false,['class'=>'i-checks rechazado']); !!}
                                        </div>
                                    </div>
                                    <div class="row" id="justificacion" style="display: none;">
                                        <div class="col-md-12">
                                            {!! BootForm::textarea('motivo_rechazo','Motivo del rechazo:*','',["rows" => "2", "style" => "rezise:none"])!!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! archivoFirma('certificate_file', 'Certificado digital: *', null,['data-allowedFileExtensions' => 'cer', 'accept'=>'.cer', 'class'=>'key']) !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! archivoFirma('privkey_file', 'Llave pública: *', null,['data-allowedFileExtensions' => 'key', 'accept'=>'.key', 'class'=>'key']) !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                        {!! BootForm::password("password", "Contraseña:") !!}
                                        </div>
                                    </div>
                                </div>
                                {!! camposFirma() !!}
                                <div class="row mt-3">
                                    <div class="col-md-6 justify-content-end">
                                        {{-- @can('solicitudesaclaracionautorizacion.update') --}}
                                            <button type="button" id='btn-firma' class="btn btn-primary" onclick="ConfirmFirma();">Firmar y guardar</button>
                                        {{-- @endcan --}}
                                    <a href="{{ route('solicitudesaclaracionatencion.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                                    </div>
                                </div>
                            {!! BootForm::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\AutorizarFlujoAutorizacionRequest') !!}
<script type="text/javascript" src="{{ asset('assets/js/signData.js')}}"></script>
@include('layouts.partials._firma')
@endsection

