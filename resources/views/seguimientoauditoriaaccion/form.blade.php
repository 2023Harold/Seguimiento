@extends('layouts.app')
@section('breadcrums')    
    {{ Breadcrumbs::render('seguimientoauditoriaacciones.create',$auditoria) }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('seguimientoauditoriaacciones.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
            &nbsp; Acción
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        {!! BootForm::open(['model' => $accion,'store' => 'seguimientoauditoriaacciones.store','update' => 'seguimientoauditoriaacciones.update','id' => 'form']) !!}
          
        <div class="row">
            <div class="col-md-2">
                {!! BootForm::text('consecutivo', 'Número consecutivo: *', old('consecutivo', $accion->consecutivo?str_pad($accion->consecutivo, 3, '0', STR_PAD_LEFT) : str_pad($numeroconsecutivo, 3, '0', STR_PAD_LEFT))) !!}
            </div>
        </div>       
        <div class="row">
            <div class="col-md-4">
                {!! BootForm::select('segtipo_accion_id', 'Tipo de acción: *', $tiposaccion->toArray(), old('segtipo_accion_id',$accion->segtipo_accion_id),['data-control'=>'select2', 'class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>
        </div> 
        <div class="row">
            <div class="col-md-4">
                {!! BootForm::select('acto_fiscalizacion_id', 'Acto de fiscalización: *', $actosfiscalizacion->toArray(), old('acto_fiscalizacion_id',$accion->acto_fiscalizacion_id),['data-control'=>'select2', 'class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>
        </div> 
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::text('numero', 'Número de acción: *', old('numero_accion', $accion->numero_accion)) !!}
            </div>
        </div> 
        <div class="row">
            <div class="col-md-6">
                {!! archivo('cedula', 'Cédula de acción: ',  old('numero_accion', $accion->cedula)) !!}
            </div>
        </div>         
        <div class="row">
            <div class="col-md-12">
                {!! BootForm::textarea('accion', 'Acción: *', old('accion', $accion->accion),['class'=>'editor']) !!}
            </div>
        </div>   
        <div class="row">
            <div class="col-md-12">
                {!! BootForm::textarea('antecedentes_accion', 'Antecedentes de la acción: *', old('antecedentes_accion', $accion->antecedentes_accion),['class'=>'editor']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                {!! BootForm::textarea('normativa_infringida', 'Normativa infringida: *', old('normativa_infringida', $accion->normativa_infringida)) !!}
            </div>
        </div>   
        @php           
            $mostrarDivMonto = ((!empty(old('segtipo_accion_id', $accion->segtipo_accion_id))&&old('segtipo_accion_id', $accion->segtipo_accion_id)!='2')?'block':'none');
            $mostrarDivRecomendaciones = ((!empty(old('segtipo_accion_id', $accion->segtipo_accion_id))&&old('segtipo_accion_id', $accion->segtipo_accion_id)=='2')?'block':'none');
        @endphp
        <div class="row" id="div_monto" style="display:{!! $mostrarDivMonto !!}">
            <div class="col-md-3">
                {!! BootForm::text('monto_aclarar', 'Monto por aclarar: *', old('monto_aclarar', $accion->monto_aclarar),['class' => 'numeric']) !!}
            </div>
        </div> 
        <div id="div_recomendacion" style="display:{!! $mostrarDivRecomendaciones !!}">
            <div class="row" >
                <div class="col-md-12">
                    {!! BootForm::textarea('evidencia_resumen', 'Evidencia documental que acredite la atención de la recomendación: ', old('evidencia_resumen', $accion->evidencia_resumen)) !!}
                </div>
            </div>
            <div class="row" >
                <div class="col-md-6">
                    {!! archivo('evidencia_recomendacion', 'Soporte de la evidencia documental que acredite la atención de la recomendación: ',  old('evidencia_recomendacion', $accion->evidencia_recomendacion)) !!}
                </div>
            </div>              
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::text('tipo_recomendacion', 'Tipo de recomendación: ', old('tipo_recomendacion', $accion->tipo_recomendacion)) !!}
                </div>
            </div> 
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::text('tramo_control_recomendacion', 'Tramo de control: ', old('tramo_control_recomendacion', $accion->tramo_control_recomendacion)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    {!! BootForm::date('fecha_termino_recomendacion', 'Fecha de término: *', old('fecha_termino_recomendacion', fecha($accion->fecha_termino_recomendacion, 'Y-m-d'))) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::text('plazo_recomendacion', 'Plazo convenido: *', old('plazo_recomendacion', $accion->plazo_recomendacion)) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6"> 
                @canany(['seguimientoauditoriaacciones.store','seguimientoauditoriaacciones.update'])             
                    <button type="submit" class="btn btn-primary">Guardar</button>
                @endcanany
                <a href="{{ route('seguimientoauditoriaacciones.index') }}" class="btn btn-secondary me-2">Cancelar</a>
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
    {{-- <script>
        ClassicEditor
            .create(document.querySelector('.editor'))
            .catch(error => {
                console.error(error);
            });
    </script> --}}
    <script>
        $(document).ready(function() {
            $("#segtipo_accion_id").select2().on('change', function(e) {
                var tipoaccionseleccionado = $(this).children("option:selected").text();              
                if(tipoaccionseleccionado=='Recomendación'){
                    $('#div_monto').hide();
                    $('#div_recomendacion').show();
                }else{
                    $('#div_monto').show();
                    $('#div_recomendacion').hide();
                }        
            }); 

            $("#acto_fiscalizacion_id").select2().on('change', function(e) {
                var actofiscalizacion = $(this).children("option:selected").text();              
                if(actofiscalizacion=='Desempeño' || actofiscalizacion=='Legalidad'){
                    // alert('entra');  
                    $("label[for=evidencia_recomendacion] span").text('*');
                    $("label[for=tipo_recomendacion] span").text('*');
                    $("label[for=tramo_control_recomendacion] span").text('*');              
                }else{
                    // alert('no entra');
                    $("label[for=evidencia_recomendacion] span").text(' ');
                    $("label[for=tipo_recomendacion] span").text(' ');
                    $("label[for=tramo_control_recomendacion] span").text(' ');
                }        
            });        
        });
    </script>   
    {!! JsValidator::formRequest('App\Http\Requests\AuditoriaAccionRequest') !!}
@endsection
