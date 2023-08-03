@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('comparecencia.edit',$comparecencia) }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('comparecencia.edit', $comparecencia) }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Datos notificación
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            @include('layouts.contextos._comparecencia')
            {!! BootForm::open(['model' => $comparecencia, 'store' =>'comparecencianotificacion.store', 'update' =>'comparecencianotificacion.update', 'id' => 'form']) !!}
                <div class="row">
                    <div class="col-md-4">
                        {!!Form::label('lb_notificacion_estrados','Notificación por estrados o edictos? *'); !!}
                        {!!BootForm::checkbox("notificacion_estrados", false, "X",
                        (old("notificacion_estrados",$comparecencia->notificacion_estrados)=="X")?true:false, ["class" =>
                        "i-checks"]); !!}
                    </div>
                </div>
                @php
                $mostrar_domicilio = (old("notificacion_estrados",$comparecencia->notificacion_estrados) == 'X') ?
                "none":"show";
                @endphp
                <div id="div_domicilio" style="display:{{$mostrar_domicilio}}">
                    <h5 class="text-sistema">Domicilio de la notificación</h5>
                    <div class="row">
                        <div class="col-md-8">
                            {!! BootForm::text('calle','Calle:*',old("calle",$comparecencia->calle))!!}
                        </div>
                        <div class="col-md-2">
                            {!! BootForm::text('numero_domicilio','Número:
                            *',old("numero_domicilio",$comparecencia->numero_domicilio))!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            {!! BootForm::text('colonia','Colonia:*',old("colonia",$comparecencia->colonia))!!}
                        </div>
                        <div class="col-md-2">
                            {!! BootForm::number('codigo_postal','Código
                            postal:*',old("codigo_postal",$comparecencia->codigo_postal))!!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! BootForm::select('municipio', 'Municipio:*', $catmunicipios, old("municipio",$comparecencia->municipio),['data-control'=>'select2', 'class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción'])!!}
                        </div>
                        <div class="col-md-4">
                            {!! BootForm::text('entidad_federativa','Entidad
                            federativa:*',old("entidad_federativa", 'Estado de México'),["disabled" => "true"] )!!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!!BootForm::label('lb_anexos','¿El requerimiento cuenta con anexos?: *'); !!}
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {!! BootForm::radios("anexos",
                        null,['Si'=>' Si','No'=>' No'],old("anexos",$comparecencia->anexos),false,['class'=>'i-checks']); !!}
                    </div>
                    @php
                    $verAnexos = ($comparecencia->exists && !empty($comparecencia->anexos) && $comparecencia->anexos=='Si'||
                    old('anexos')=='Si') ? 'show' : 'none';
                    @endphp
                    <div id="div_anexos" class="col-md-6" style="display:{{$verAnexos}}">
                        @button('Anexos', route('comparecenciaanexo.index'), 'popupSinLocation')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!!BootForm::label('lb_copias','¿El requerimiento cuenta con copias de conocimiento?: *'); !!}
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {!! BootForm::radios("copias_conocimiento",
                        null,[
                            'Si'=>' Si',
                            'No'=>' No'], old("copias_conocimiento",$comparecencia->copias_conocimiento),false,['class'=>'i-checks']); !!}
                    </div>
                    @php
                    $verCC = ($comparecencia->exists && !empty($comparecencia->copias_conocimiento) &&
                    $comparecencia->copias_conocimiento=='Si' ||old('anexos')=='Si') ? 'show' : 'none';
                    @endphp
                    <div id="div_copias" class="col-md-6" style="display:{{$verCC}}">
                        @button('Copias de conocimiento', route('comparecenciacopia.index'), 'popupSinLocation')
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {!! BootForm::checkbox('revision_supervisor', ' Se envía al superior para su validación', '', true, ['class' => 'i-checks', 'disabled','checked']) !!}
                    </div>
                </div>
                @btnSubmit("Guardar ")
                @btnCancelar('Cancelar', route('comparecencia.index'))
            {!! BootForm::close() !!}
        </div>
    </div>
@endsection
@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\DatosNotificacionRequest') !!}  
    <script>
        $(document).ready(function() {
            $('input[name=notificacion_estrados]').on('ifChanged', function(event){
                if(event.target.checked==true){
                    $('#div_domicilio').hide();
                } else {
                    $('#div_domicilio').show();
                }
            });

            $('input[name=domicilio_copias]').on('ifChanged', function(event){
                if(event.target.checked==true){
                    $("#domicilio_copias").val($("#domicilio_notificacion").val());
                } else {
                    $("#domicilio_copias").val('');
                }
            });
            $('input[name="anexos"]').on('ifChanged', function(event) {
                if(event.target.value=='Si') {
                    $('#div_anexos').show();
                } else {
                    $('#div_anexos').hide();
                }
            });

            $('input[name="copias_conocimiento"]').on('ifChanged', function(event) {
                if(event.target.value=='Si') {
                    $('#div_copias').show();
                } else {
                    $('#div_copias').hide();
                }
            });
        });
    </script>
@endsection
