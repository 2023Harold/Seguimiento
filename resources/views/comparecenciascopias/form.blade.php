@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">
            @can('comparecenciacopia.index')
                @btnBack(route('comparecenciacopia.index'))
            @endcan
            {{$accion.' copia de conocimiento'}}
        </h4>
    </div>
    <div class="card-body">
        @include('flash::message')
        {!! BootForm::open(['model' => $copia, 'store' => 'comparecenciacopia.store', 'update' =>'comparecenciacopia.update','id'=>'form']) !!}
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::text("nombre", "Nombre: *", old("nombre",$copia->nombre), ['maxlength' =>'75']); !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!!Form::label('lb_copias','¿El domicilio de la persona a la que se dirige la copia es el mismo que el domicilio de notificación? *'); !!}
                    {!!BootForm::checkbox("domicilio_notificacion", false, "Si", (old("domicilio_notificacion",$copia->domicilio_notificacion)=="Si")?true:false, ["class" => "i-checks"]); !!}
                </div>
            </div>
            @php
            $verDomicilio = (old("domicilio_notificacion",$copia->domicilio_notificacion)  == 'Si') ? "none":"show";
            @endphp
            <div id="div_domicilio" style="display:{{$verDomicilio}}">
                <h5 class="text-sistema">Domicilio de la persona a la que se dirige la copia</h5>
                <div class="row">
                    <div class="col-md-9">
                        {!! BootForm::text('calle','Calle: *',old("calle",$copia->calle))!!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::text('numero_domicilio','Número: *',old("numero_domicilio",$copia->numero_domicilio))!!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        {!! BootForm::text('colonia','Colonia: *',old("colonia",$copia->colonia))!!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::number('codigo_postal','Código postal: *',old("codigo_postal",$copia->codigo_postal))!!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::select('municipio','Municipio: *', $catmunicipios, old("municipio",$copia->municipio), ['data-control'=>'select2', 'class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción'])!!}
                    </div>
                    <div class="col-md-6">
                        {!! BootForm::text('entidad_federativa','Entidad federativa: *', old('entidad_federativa', ($copia->entidad_federativa? $copia->entidad_federativa:'Estado de México')), ['disabled'])!!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit("Guardar")
                    @btnCancelar('Cancelar', route('comparecenciacopia.index'))
                </div>
            </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\ComparecenciaCopiaRequest') !!}
<script>    
    $(document).ready(function() {
        $('input[name=domicilio_notificacion]').on('ifChanged', function(event){
            if(event.target.checked==true) {
                $('#div_domicilio').hide();
            }
            if(event.target.checked==false ){
                $('#div_domicilio').show();
            }
        });
    });
</script>
@endsection
