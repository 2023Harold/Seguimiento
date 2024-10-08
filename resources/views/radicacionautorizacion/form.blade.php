@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('radicacionautorizacion.edit', $radicacion,$auditoria)}}
@endsection
@section('content')
    <div class="row">
        @include('layouts.partials._menu')
        <div class="col-md-9 mt-2">  
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">
                        <a href="{{ route('radicacion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                        Autorizar                        
                    </h1>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        @include('flash::message')
                        @include('layouts.contextos._radicacion')                    
                        <div class="row">
                            <div class="col-md-12">
                                <div id="body_loader" class="body_loader" style="display:none;">
                                    <span class="loader"></span>
                                    <span> Firmando la constancia, por favor espere.</span>
                                </div>
                                <embed src="{{asset($preconstancia)}}" type="application/pdf" width="100%" height="600px"/>
                            </div>
                        </div>
                        {!! BootForm::open(['model' => $radicacion,'update'=>'radicacionautorizacion.update','id'=>'form'] )!!}                       
                        {!! BootForm::hidden('archivo_firmar',$b64archivoxml,['id'=>'archivo_firmar'])!!}
                        
                            <div id="campos"><br>
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! BootForm::checkbox('radicacion_sistema', 'Acuerdo de radicacion por sistema', 'X', false, ['class' => 'i-checks rxs']) !!}
                                    </div>
                                </div>
                                <div class="div-estatus" style="display:block;">
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
                                </div>                                
                                <div id="camposfirma" style="display:none;" >
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
                                            {!! BootForm::password("password", "Contraseña:", ['autocomplete'=>'off', 'class'=>'enviar-firma']); !!}
                                        </div>
                                    </div>
                                    {!! camposFirma() !!}
                                </div>                                
                            </div>                           
                            <div class="row mt-3">
                                <div class="col-md-6 justify-content-end">
                                    @can('radicacionautorizacion.update')
                                        <button type="button" id='btn-guardar' class="btn btn-primary" onclick="ConfirmFirma();" >Guardar</button>                                                                              
                                        {{-- <button type="submit" id='btn-guardarsinfirma' class="btn btn-primary">Guardar</button> --}}
                                    @endcan                                    
                                     <a href="{{ route('radicacion.index') }}" class="btn btn-secondary me-2">Cancelar</a>
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
<script type="text/javascript" src="{{ asset('assets/js/signData.js')}}"></script>
    @include('layouts.partials._firma')
    <script>
        $(document).ready(function() {
            $('.rxs').on('ifChanged', function(event) {              
                var estado = $(this).is(':checked')? 1 : 0;

                if(estado==0){
                    $('#div_acuerdopdf').hide();
                    $('#camposfirma').hide();
                    $('#btn-firma').hide();
                    $('#btn-guardar').hide();
                    $('#btn-guardarsinfirma').show();
                    

                }else{
                    $('#div_acuerdopdf').show();
                    $('#camposfirma').show();
                    $('#btn-firma').show();
                    $('#btn-guardar').show();
                    $('#btn-guardarsinfirma').hide();

                }
                //alert(estado);            
            });
        });
    </script>    
    {!! JsValidator::formRequest('App\Http\Requests\AprobarFlujoAutorizacionRequest') !!}
@endsection
