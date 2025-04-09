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
                {!! BootForm::hidden('fecha_aclaracion',fecha($auditoria->comparecencia->fecha_termino_aclaracion, 'Y-m-d'),['id'=>'fecha_aclaracion']); !!}
                {!! BootForm::hidden('fecha_atencion',fecha($auditoria->comparecencia->fecha_termino_proceso, 'Y-m-d'),['id'=>'fecha_atencion']); !!}
                {!! BootForm::hidden('presentacion','',['id'=>'presentacion']); !!}
                    <div class="row">
                        <div class="col-md-6">
                        {!! BootForm::radios("solicitudes", 'Solicitudes en el oficio: *',
                        [
                            'Acciones' => 'Acciones',
                            'Recomendaciones' => 'Recomendaciones',
                            'Ambas' => 'Ambas'
                        ], old('solicitudes',$folio->solicitudes),true,['class'=>'i-checks rechazado']);
                        !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!!BootForm::checkbox('entiempoacciones', ' Acciones a destiempo', 'X', ($folio->entiempoacciones == 'X'?true:false), ['class' => 'i-checks']) !!}
                            {!!BootForm::checkbox('entiemporecomendaciones',' Recomendaciones a destiempo', 'X', ($folio->entiemporecomendaciones?true:false), ['class' => 'i-checks']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! archivo('oficio_contestacion_general', 'Oficio de contestación: *', old('oficio_contestacion_general', $folio->oficio_contestacion_general)) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            {!! BootForm::date('fecha_oficio_contestacion', 'Fecha del oficio de contestación: ', old('fecha_oficio_contestacion', fecha($folio->fecha_oficio_contestacion, 'Y-m-d'))); !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::text('numero_oficio', 'Número del oficio: *', old('numero_oficio', $folio->numero_oficio)); !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! BootForm::text('nombre_remitente', 'Nombre del remitente: *', old('nombre_remitente', $folio->nombre_remitente)); !!}
                        </div>
                        <div class="col-md-6">
                            {!! BootForm::text('cargo_remitente', 'Cargo del remitente: *', old('cargo_remitente', $folio->cargo_remitente)); !!}
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-3">
                        {!!BootForm::text('folio', 'Folio de correspondencia: *', old('num_memo_recepcion_expediente',$folio->num_memo_recepcion_expediente)) !!}
                    </div>
                    <div class="col-md-3">
                        {!!BootForm::date('fecha_recepcion_oficialia', 'Fecha de recepción en Oficialia de Partes: *', old('fecha_recepcion_oficilia',fecha($folio->fecha_recepcion_oficilia, 'Y-m-d')),['onchange'=>'handler(event)']) !!}
                    </div>
                    <div class="col-md-4">
                        {!!BootForm::date('fecha_recepcion_us', 'Fecha de recepción en la Unidad de Seguimiento: *', old('fecha_recepcion_us',fecha($folio->fecha_recepcion_us,'Y-m-d'))) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @canany(['folioscrr.store','folioscrr.update'])
                        <button type="submit" class="btn btn-primary">Guardar</button>
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
                alert(solicitud);
                //setTimeout(mostrarCitas(), 1000);
                if(solicitud=='Acciones'){

                }

                if(solicitud=='Recomendaciones'){

                }

                if(solicitud=='Ambas'){

                }
            }
        });
    });

    function handler(e){
        alert(e.target.value);
        let solicitudes = $('input[name="solicitudes"]:checked').val();
        alert(solicitudes);
        let fechaatencion = $('#fecha_atencion').val();
        alert(fechaatencion);
        let fechaaclaracion = $('#fecha_aclaracion').val();
        alert(fechaaclaracion);


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

        }


    }
</script>
@endsection
