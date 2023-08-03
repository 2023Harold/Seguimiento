@extends('layouts.app')
@section('breadcrums')
    @if ($acciondep=='Asignación')
        {{ Breadcrumbs::render('asignaciondepartamento.edit',$auditoria) }}
    @else
        {{ Breadcrumbs::render('asignaciondepartamento.reasignar',$auditoria,$accion) }}
    @endif    
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('asignaciondepartamento.edit',$auditoria) }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
            &nbsp; {{ $acciondep }}
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        @include('layouts.contextos._accion')
        {!! BootForm::open(['model' => $auditoria,'update' => 'asignaciondepartamento.update','id' => 'form']) !!}
        {!! BootForm::hidden('accion',$acciondep) !!}
        {!! BootForm::hidden('usuario_id',null,['id'=>'usuario_id']) !!}
        {!! BootForm::hidden('accion_id',$accion->id,['id'=>'accion_id']) !!}
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::select('departamento_asignado_id', 'Departamento: *', $unidades->toArray() , old('departamento_asignado_id',$auditoria->departamento_asignado_id), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>
        </div> 
        {!! BootForm::hidden('departamento_asignado',$auditoria->departamento_asignado,['id'=>'departamento_id']) !!}
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::text('nombre', 'Nombre: *', old('nombre',optional($departamentoasignado)->name),['readonly']) !!}
            </div>
            <div class="col-md-3">
                {!! BootForm::text('cargo', 'Cargo: *', old('cargo',optional($departamentoasignado)->puesto),['readonly']) !!}
            </div>
        </div>               
        <div class="row">
            <div class="col-md-6"> 
                @can('asignaciondepartamento.update')                     
                    <button type="submit" class="btn btn-primary">Guardar</button>
                @endcan              
                <a href="{{ route('asignaciondepartamento.edit',$auditoria) }}" class="btn btn-secondary me-2">Cancelar</a>
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $("#departamento_asignado_id").select2().on('change', function(e) {
            var unidadSeleccionada = $(this).children("option:selected").text();
            var unidadSeleccionadaId = $(this).children("option:selected").val();           

            $.ajax({
                url: "{{ route('getJefeDepartamento') }}"
                , dataType: "JSON"
                , type: "POST"
                , method: 'POST'
                , data: {
                    "unidadid": unidadSeleccionadaId
                    , "unidad": unidadSeleccionada                    
                , }
                , beforeSend: function(objeto) {}
                , success: function(respuesta) {
                    console.log(respuesta);
                    var usuario = respuesta[1];                   
                    if (usuario.length > 0) {
                        $('#usuario_id').val(usuario[0].id);
                        $('#nombre').val(usuario[0].nombre);
                        $('#cargo').val(usuario[0].puesto);
                        $('#departamento_id').val(usuario[0].unidad);                        
                    } else {
                        $('#usuario_id').val('');
                        $('#nombre').val('');
                        $('#cargo').val('');
                        $('#departamento_id').val('');                    
                    }                    
                }
                , error: function() {
                    console.log('Error al cargar los datos');
                }
            });            
        });             
    });

</script>
{!! JsValidator::formRequest('App\Http\Requests\AsignacionDepartamentoRequest') !!}
@endsection
