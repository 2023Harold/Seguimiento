@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('recomendacionesautorizacion.edit', $recomendacion,$auditoria)}}
@endsection
@section('content')
    <div class="row">
      @include('layouts.partials._menu')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">
                        <a href="{{ route('recomendacionesatencion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                        Autorizar
                    </h1>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        @include('flash::message')
                        @include('layouts.contextos._auditoriaaut')
                        @include('layouts.contextos._accionaut')
                        @include('layouts.contextos._recomendacion')
                        <div class="row">
                            <div class="col-md-12">
                                <div id="body_loader" class="body_loader" style="display:none;">
                                    <span class="loader"></span>
                                    <span> Firmando la constancia, por favor espere.</span>
                                </div>
                                {{-- <embed src="{{asset($preconstancia)}}" type="application/pdf" width="100%" height="600px"/> --}}
                            </div>
                        </div>
                        {!! BootForm::open(['model' => $recomendacion,'update'=>'recomendacionesautorizacion.update','id'=>'form'] )!!}
                        {{-- {!! BootForm::hidden('archivo_firmar',$b64archivoxml,['id'=>'archivo_firmar'])!!} --}}
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
                                {{-- <div class="row">
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
                                </div> --}}
                            </div>
                            {{-- {!! camposFirma() !!} --}}
                            <div class="row mt-3">
                                <div class="col-md-6 justify-content-end">
                                    {{-- @can('prasturnoautorizacion.update') --}}
                                        {{-- <button type="button" id='btn-firma' class="btn btn-primary" onclick="ConfirmFirma();">Firmar y guardar</button> --}}
                                        <button type="submit" id='btn-firma' class="btn btn-primary">Guardar</button>
                                    {{-- @endcan --}}
                                <a href="{{ route('recomendacionesatencion.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                                </div>
                            </div>
                        {!! BootForm::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\AprobarFlujoAutorizacionRequest') !!}
{{-- <script type="text/javascript" src="{{ asset('assets/js/signData.js')}}"></script>
    @include('layouts.partials._firma') --}}
@endsection
