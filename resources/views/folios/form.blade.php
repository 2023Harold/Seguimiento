@extends('layouts.app')
@section('breadcrums')
{{Breadcrumbs::render('folioscrr.create',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('radicacion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp; Folio
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!!BootForm::open(['model' => $folio,'store' => 'folioscrr.store','update' => 'folioscrr.update','id' =>'form',]) !!}
                {!!BootForm::hidden('fecha_aclaracion',fecha($auditoria->comparecencia->fecha_termino_aclaracion, 'Y-m-d'),['id'=>'fecha_aclaracion']) !!}
                {!!BootForm::hidden('fecha_atencion',fecha($auditoria->comparecencia->fecha_termino_proceso, 'Y-m-d'),['id'=>'fecha_atencion']) !!}

                    <div class="row">
                        <div class="col-md-6">
                        {!!BootForm::radios("solicitudes", 'Solicitudes en el oficio: *',
                        [
                            'Acciones' => 'Acciones',
                            'Recomendaciones' => 'Recomendaciones',
                            'Ambas' => 'Ambas'
                        ], old('solicitudes',$folio->solicitudes),true,['class'=>'i-checks rechazado'])
                        !!}
                        </div>
                    </div>
                    <div class="row" id="divsolambas" style="display:none;">
                        <div class="col-md-6">
                        {!!BootForm::radios("presentacionambs", "Presenta: *",
                        [
                            'En tiempo' => 'En tiempo',
                            'Extemporaneo' => 'Extemporaneo',
                        ], old('presentacionambs',$folio->presentacion),true,['class'=>'i-checks rechazado'])
                        !!}
                        </div>
                    </div>
                    <div class="row" id="divextempambas" style="display:none;">
                        <div class="col-md-6">
                            {!!BootForm::checkbox('sol_extemp_ad', ' Acciones a destiempo', 'XAD', ($folio->acciones_extemp == 'X'?true:false), ['class' => 'i-checks','id'=>'sol_extemp_ad']) !!}
                            {!!BootForm::checkbox('sol_extemp_rd',' Recomendaciones a destiempo', 'XRD', ($folio->recomendaciones_extemp?true:false), ['class' => 'i-checks','id'=>'sol_extemp_rd']) !!}
                            {!!BootForm::hidden('presentacion','',['id'=>'presentacion']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! archivo('oficio_contestacion_general', 'Oficio de contestación: *', old('oficio_contestacion_general', $folio->oficio_contestacion_general)) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            {!!BootForm::date('fecha_oficio_contestacion', 'Fecha del oficio de contestación: *', old('fecha_oficio_contestacion', fecha($folio->fecha_oficio_contestacion, 'Y-m-d'))) !!}
                        </div>
                        <div class="col-md-3">
                            {!!BootForm::text('numero_oficio', 'Número del oficio: ', old('numero_oficio', $folio->numero_oficio)) !!} {{--si el numero es NULL es memorandum si es S/N o XXX/XXX/XX/XX entonces es Oficio --}}
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-3">
                        {!!BootForm::text('folio', 'Folio de correspondencia: *', old('num_memo_recepcion_expediente',$folio->num_memo_recepcion_expediente)) !!}
                    </div>
                    <div class="col-md-3">
                        {!!BootForm::date('fecha_recepcion_oficialia', 'Fecha de recepción en Oficialia de Partes: *', old('fecha_recepcion_oficialia',fecha($folio->fecha_recepcion_oficialia, 'Y-m-d')),['onchange'=>'handler(event)']) !!}
                    </div>
                    <div class="col-md-4">
                        {!!BootForm::date('fecha_recepcion_us', 'Fecha de recepción en la Unidad de Seguimiento: *', old('fecha_recepcion_us',fecha($folio->fecha_recepcion_us,'Y-m-d'))) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @canany(['folioscrr.store','folioscrr.update'])
                            <button type="submit" class="btn btn-primary">Guardar y continuar</button>
                        @endcan
                        <a href="{{ route('folioscrr.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    </div>
                </div>
                {!!BootForm::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{!!JsValidator::formRequest('App\Http\Requests\FoliosRequest') !!}
<script>
    $(document).ready(function() {

        $('input[name="solicitudes"]').on('ifChanged', function(event) {
            if(event.target.checked){
                solicitud = event.target.value;
               // fecha = $('#fecha').val();
                //alert(solicitud);
                //setTimeout(mostrarCitas(), 1000);
                if(solicitud=='Acciones'){
                    $('#divsolambas').hide();
                    $('#divextempambas').hide();
                }

                if(solicitud=='Recomendaciones'){
                    $('#divsolambas').hide();
                    $('#divextempambas').hide();
                }

                if(solicitud=='Ambas'){
                $('#divsolambas').show();

                }
            }
        });

        $('input[name="presentacionambs"]').on('ifChanged', function(event) {
            if(event.target.checked){
                solicitud = event.target.value;
               // fecha = $('#fecha').val();
                //alert(solicitud);
                //setTimeout(mostrarCitas(), 1000);
                if(solicitud=='Extemporaneo'){
                    $('#divextempambas').show();
                }else{
                    $('#divextempambas').hide();
                }
            }
        });

        $("#sol_extemp_ad").change(function() {
            if (this.checked) {
                //alert(1);
                        $('#presentacion').val('Acciones Extemporaneas');
                        $('#presentacion-error').text(null);
                    }else{
                        $('#presentacion').val(null);
                    }
        });

        $("#sol_extemp_rd").change(function() {
            if (this.checked) {
               // alert(1);
                        $('#presentacion').val('Acciones Extemporaneas');
                        $('#presentacion-error').text(null);
                    }else{
                        $('#presentacion').val(null);
                    }
        });

    });



    function handler(e){
        //alert(e.target.value);
        let solicitudes = $('input[name="solicitudes"]:checked').val();
        //alert(solicitudes);
        let fechaatencion = $('#fecha_atencion').val();
        //alert(fechaatencion);
        let fechaaclaracion = $('#fecha_aclaracion').val();
        //alert(fechaaclaracion);


        if(solicitud=='Acciones'){
            var f1 = new Date(e.target.value);
            var f2 = new Date($('#fecha_aclaracion').val());
            if(f1 > f2){
                $('#presentacion').val('Extemporaneo');
            }else{
                $('#presentacion').val('En tiempo');
            }
        }

        if(solicitud=='Recomendaciones'){
            var f1 = new Date(e.target.value);
            var f2 = new Date($('#fecha_atencion').val());
            if(f1 > f2){
                $('#presentacion').val('Extemporaneo');
            }else{
                $('#presentacion').val('En tiempo');
            }
        }

        if(solicitud=='Ambas'){
            $('#presentacion').val(null);
        }
    }
</script>
@endsection
