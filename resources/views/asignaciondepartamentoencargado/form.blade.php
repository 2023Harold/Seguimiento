@extends('layouts.app')
@section('breadcrums')    
    {{ Breadcrumbs::render('asignaciondepartamentoencargado.edit',$auditoria) }}   
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('asignaciondepartamento.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
            &nbsp; {{ $acciondep }}
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        {!! BootForm::open(['model' => $auditoria,'update' => 'asignaciondepartamentoencargado.update','id' => 'form']) !!}
        {{-- {!! BootForm::hidden('accion',$accion) !!} --}}
        {!! BootForm::hidden('usuario_id',null,['id'=>'usuario_id']) !!}
        <div class="row">
            <div class="col-md-6">
                {!! BootForm::checkbox('auditoria_completa', 'Asignación de la auditoria completa', 'X', false, ['class' => 'i-checks']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::select('departamento_encargado_id', 'Departamento: *', $unidades->toArray() , old('departamento_encargado_id',$auditoria->departamento_encargado_id), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>
        </div> 
        {!! BootForm::hidden('departamento_encargado',$auditoria->departamento_encargado,['id'=>'departamento_id']) !!}
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::text('nombre', 'Nombre: *', old('nombre'),['readonly']) !!}
            </div>
            <div class="col-md-3">
                {!! BootForm::text('cargo', 'Cargo: *', old('cargo'),['readonly']) !!}
            </div>
        </div>       
        <div class="row">
            <div class="col-md-6">         
                    @can('asignaciondepartamentoencargado.update')                     
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    @endcan  
                    <a href="{{ route('asignaciondepartamento.index') }}" class="btn btn-secondary me-2">Cancelar</a>                
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $("#departamento_encargado_id").select2().on('change', function(e) {
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
