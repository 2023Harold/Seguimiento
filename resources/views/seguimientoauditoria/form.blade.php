@extends('layouts.app')
@section('breadcrums')
@if (empty($auditoria->numero_auditoria))
    {{ Breadcrumbs::render('seguimientoauditorias.create') }}
@else
    {{ Breadcrumbs::render('seguimientoauditorias.edit',$auditoria) }}
@endif    
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('seguimientoauditoria.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
            &nbsp; Auditoría
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        {!! BootForm::open(['model' => $auditoria,'store' => 'seguimientoauditoria.store','update' => 'seguimientoauditoria.update','id' => 'form']) !!}
        {!! BootForm::hidden('entidad_fiscalizable_id',$auditoria->entidad_fiscalizable_id,['id'=>'entidad_fiscalizable_id']) !!}       
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::text('numero_auditoria', 'Número de auditoría: *', old('numero_auditoria', $auditoria->numero_auditoria)) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::text('numero_orden', 'Número de orden de auditoría: *', old('numero_orden', $auditoria->numero_orden)) !!}
            </div>
        </div>                      
            {!! BootForm::label('lb_ebtidad','Entidad Fiscalizable: *') !!}
        <div class="row">           
            <div class="col-md-3 mb-4">
                {!! BootForm::select('entidad_n1', false, $entidades->prepend('Seleccionar una opción','')->toArray() , old('entidad_n1',$entidad1), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>
            @php           
                $mostrarDivEntidad2 = ((!empty($entidad2))?'block':'none');
            @endphp
            <div class="col-md-4 mb-4" id="div_ent_2" style="display:{!! $mostrarDivEntidad2 !!}">
                {!! BootForm::select('entidad_n2', false, (!empty($entidades2)?$entidades2->toArray():[]) , old('entidad_n2',$entidad2), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>
            @php
            $mostrarDivEntidad3 = ((!empty($entidad3))?'block':'none');
            @endphp
            <div class="col-md-5 mb-4" id="div_ent_3" style="display:{!! $mostrarDivEntidad3 !!}">
                {!! BootForm::select('entidad_n3', false, (!empty($entidades3)?$entidades3->toArray():[]) , old('entidad_n3',$entidad3), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>           
        </div>    
        <div class="row">
            <div class="col-md-8">
                {!! BootForm::text('entidad_descripcion', false, old('entidad_descripcion',$auditoria->entidad_descripcion)) !!}
            </div>
        </div>    
        <div class="row">
            <div class="col-md-4">
                {!! BootForm::select('periodo_revision', 'Periodo de la revisión: *', $periodorevision , old('periodo_revision',$auditoria->periodo_revision), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>
        </div> 
        <div class="row">
            <div class="col-md-6">
                {!! BootForm::select('tipo_auditoria_id', 'Acto de fiscalización: *', $tipos->toArray(), old('tipo_auditoria_id',$auditoria->tipo_auditoria_id),['data-control'=>'select2', 'class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>
        </div>       
        <div class="row">
            <div class="col-md-4">
                {!! archivo('informe_auditoria', 'Informe de auditoria: ', optional($auditoria)->informe_auditoria) !!}
            </div>
            <div class="col-md-2">
                {!! BootForm::text('fojas_utiles', 'Número de fojas útiles: *', optional($auditoria)->fojas_utiles) !!}
            </div>
        </div>        
        <div class="row">
            <div class="col-md-4">
                {!! BootForm::select('lider_proyecto_id', 'Lider de proyecto: *', $lideresProyecto->toArray(), old('lider_proyecto_id',$auditoria->lider_proyecto_id),['data-control'=>'select2', 'class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>
        </div>        
        <div class="row">
            <div class="col-md-6"> 
                @canany(['seguimientoauditoria.store','seguimientoauditoria.update'])        
                    <button type="submit" class="btn btn-primary">Guardar y continuar</button>
                @endcanany
                <a href="{{ route('seguimientoauditoria.index') }}" class="btn btn-secondary me-2">Cancelar</a>
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $("#entidad_n1").select2().on('change', function(e) {
            var entidadSeleccionado = $(this).children("option:selected").text();
            var entidadSeleccionadoId = $(this).children("option:selected").val();

            $.ajax({
                url: "{{ route('getCargosAsociados') }}"
                , dataType: "JSON"
                , type: "POST"
                , method: 'POST'
                , data: {
                    "entidadid": entidadSeleccionadoId
                    , "entidadn1": entidadSeleccionado
                    , "nivel": 2
                , }
                , beforeSend: function(objeto) {}
                , success: function(respuesta) {
                    console.log(respuesta);
                    var entidades = respuesta[1];                   
                    if (entidades.length > 0) {
                        $('#entidad_n2').empty();
                        $('#entidad_n2').append('<option value="" disable="">Seleccionar una opción</option>');
                        for (var i = 0; i < entidades.length; i++) {
                            $('#entidad_n2').append('<option value="' + entidades[i].id + '">' + entidades[i].text + '</option>');
                        }
                        $('#entidad_n2').select2();
                        $('#div_ent_2').show();
                        $('#div_ent_3').hide();
                        $('#entidad_fiscalizable_id').val(null);
                    } else {
                        $('#div_ent_2').hide();
                        $('#div_ent_3').hide();
                        $('#entidad_fiscalizable_id').val(entidadSeleccionadoId);
                    }                    
                }
                , error: function() {
                    console.log('Error al cargar las entidades fiscalizables');
                }
            });            
        });

        $("#entidad_n2").select2().on('change', function(e) {
            var entidadN2Seleccionado = $(this).children("option:selected").text();
            var entidadN2SeleccionadoId = $(this).children("option:selected").val();

            $.ajax({
                url: "{{ route('getCargosAsociados') }}"
                , dataType: "JSON"
                , type: "POST"
                , method: 'POST'
                , data: {
                    "entidadid": entidadN2SeleccionadoId
                    , "entidadn1": entidadN2Seleccionado
                    , "nivel": 3
                , }
                , beforeSend: function(objeto) {}
                , success: function(respuesta) {
                    console.log(respuesta);
                    var entidadesN3 = respuesta[1];                   

                    if (entidadesN3.length > 0) {
                        $('#entidad_n3').empty();
                        $('#entidad_n3').append('<option value="" disable="">Seleccionar una opción</option>');
                        for (var i = 0; i < entidadesN3.length; i++) {
                            $('#entidad_n3').append('<option value="' + entidadesN3[i].id + '">' + entidadesN3[i].text + '</option>');
                        }
                        $('#entidad_n3').select2();
                        $('#div_ent_3').show();
                        $('#entidad_fiscalizable_id').val(null);
                    } else {
                        $('#div_ent_3').hide();
                        $('#entidad_fiscalizable_id').val(entidadN2SeleccionadoId);
                    }                    
                }
                , error: function() {
                    console.log('Error al cargar las entidades fiscalizables');
                }
            });
        });
        $("#entidad_n3").select2().on('change', function(e) {
            var entidadN3SeleccionadoId = $(this).children("option:selected").val();

            $('#entidad_fiscalizable_id').val(entidadN3SeleccionadoId);
        });        
    });

</script>
{!! JsValidator::formRequest('App\Http\Requests\AuditoriaRequest') !!}
@endsection
