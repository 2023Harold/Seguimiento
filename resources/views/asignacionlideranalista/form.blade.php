@extends('layouts.app')
@section('breadcrums')    
@if ($accion=="asignar")
{{ Breadcrumbs::render('asignacionlideranalista.edit',$auditoria) }}  
@elseif($accion=="reasignarlider")
{{ Breadcrumbs::render('asignacionlideranalista.reasignarlider',$auditoria) }}  
@elseif ($accion=="reasignaranalista")
{{ Breadcrumbs::render('asignacionlideranalista.reasignaranalista',$auditoria) }}  
@endif       
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('asignacionlideranalista.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
            &nbsp; 
            @if ($accion=="asignar")
                Asignación del Lider de proyecto y Analista
            @elseif($accion=="reasignarlider")            
                Reasignación del Lider de proyecto
            @elseif ($accion=="reasignaranalista")            
                Reasignación del Analista 
            @endif    
        </h1>
    </div>
    <div class="card-body">       
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        @include('layouts.contextos._acciones')
        {!! BootForm::open(['model' => $auditoria,'update' => 'asignacionlideranalista.update','id' => 'form']) !!}
        {!! BootForm::hidden('accion',$accion) !!}
        {!! BootForm::hidden('lider_asignado',null,['id'=>'lider_asignado_fid']) !!}
        {!! BootForm::hidden('analista_asignado',null,['id'=>'analista_asignado_fid']) !!}
        <div class="row">            
            <div class="col-md-8">
                @php
                    $verLider=(($accion=='asignar'||$accion=='reasignarlider')?"block":"none");
                @endphp
                <div id="div_lider" style="display: {{ $verLider }}">
                    <h5 class="text-primary text-decoration-underline">Lider de proyecto</h5>
                    <div class="row ">                    
                        <div class="col-md-1">
                            &nbsp;
                        </div>
                        <div class="col-md-5">
                            {!! BootForm::select('lider_asignado_id', 'Nombre: *', $lideres->toArray() , old('lider_asignado_id'), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                        </div>
                        <div class="col-md-5">
                            {!! BootForm::text('cargo_lider', 'Cargo: *', old('cargo_lider'),['readonly']) !!}
                        </div>            
                    </div> 
                </div>
                @php
                    $verAnalista=(($accion=='asignar'||$accion=='reasignaranalista')?"block":"none");
                @endphp
                <div id="div_analista" style="display: {{ $verAnalista }}">
                    <h5 class="text-primary text-decoration-underline">Analista</h5>
                    <div class="row">
                        <div class="col-md-1">
                            &nbsp;
                        </div>
                        <div class="col-md-5">
                            {!! BootForm::select('analista_asignado_id', 'Nombre: *', $analistas->toArray() , old('analista_asignado_id'), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                        </div>
                        <div class="col-md-5">
                            {!! BootForm::text('cargo_analista', 'Cargo: *', old('cargo_analista'),['readonly']) !!}
                        </div>            
                    </div> 
                </div>                 
            </div>          
        </div>                  
        <div class="row">
            <div class="col-md-6">        
                {{-- @can('asignacionlideranalista.update')               --}}
                    <button type="submit" class="btn btn-primary">Guardar</button>    
                {{-- @endcan            --}}
                <a href="{{ route('asignacionlideranalista.index') }}" class="btn btn-secondary me-2">Cancelar</a>
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $("#lider_asignado_id").select2().on('change', function(e) {
            var userSeleccionado = $(this).children("option:selected").text();
            var userSeleccionadoId = $(this).children("option:selected").val();           

            $.ajax({
                url: "{{ route('getLider') }}"
                , dataType: "JSON"
                , type: "POST"
                , method: 'POST'
                , data: {
                    "userid": userSeleccionadoId
                    , "user": userSeleccionado                    
                , }
                , beforeSend: function(objeto) {}
                , success: function(respuesta) {
                    console.log(respuesta);
                    var usuario = respuesta[1];                   
                    if (usuario.length > 0) {
                         $('#lider_asignado_fid').val(usuario[0].nombre); 
                         $('#cargo_lider').val(usuario[0].puesto);                        
                    } else {
                        $('#lider_asignado_fid').val('');   
                        $('#cargo_lider').val(usuario[0].puesto);              
                    }                    
                }
                , error: function() {
                    console.log('Error al cargar los datos');
                }
            });            
        });  
        
        $("#analista_asignado_id").select2().on('change', function(e) {
            var userAnalistaSeleccionado = $(this).children("option:selected").text();
            var userAnalistaSeleccionadoId = $(this).children("option:selected").val();           

            $.ajax({
                url: "{{ route('getAnalista') }}"
                , dataType: "JSON"
                , type: "POST"
                , method: 'POST'
                , data: {
                    "useranalistaid": userAnalistaSeleccionadoId
                    ,"useranalista": userAnalistaSeleccionado                    
                , }
                , beforeSend: function(objeto) {}
                , success: function(respuesta) {
                    console.log(respuesta);
                    var usuario = respuesta[1];                   
                    if (usuario.length > 0) {
                         $('#cargo_analista').val(usuario[0].puesto);  
                         $('#analista_asignado_fid').val(usuario[0].nombre);                      
                    } else {
                        $('#cargo_analista').val('');                
                        $('#analista_asignado_fid').val('');                
                    }                    
                }
                , error: function() {
                    console.log('Error al cargar los datos');
                }
            });            
        });
    });

</script>
{!! JsValidator::formRequest('App\Http\Requests\AsignacionLiderAnalistaRequest') !!}
@endsection
