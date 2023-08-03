@extends('layouts.app')
@section('breadcrums')
    @if ($accion=='Asignación')
        {{ Breadcrumbs::render('asignaciondireccion.edit',$auditoria) }}
    @else
        {{ Breadcrumbs::render('asignaciondireccion.reasignar',$auditoria) }}
    @endif    
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('asignaciondireccion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
            &nbsp; {{ $accion }}
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        {!! BootForm::open(['model' => $auditoria,'update' => 'asignaciondireccion.update','id' => 'form']) !!}
        {!! BootForm::hidden('accion',$accion) !!}
        {!! BootForm::hidden('usuario_id',null,['id'=>'usuario_id']) !!}
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::select('direccion_asignada_id', 'Direccion: *', $unidades->toArray() , old('direccion_asignada_id',$auditoria->direccion_asignada_id), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>
        </div> 
        {!! BootForm::hidden('direccion_asignada',$auditoria->direccion_asignada,['id'=>'direccion_id']) !!}
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::text('nombre', 'Nombre: *', old('nombre',optional($directorasignado)->name),['readonly']) !!}
            </div>
            <div class="col-md-3">
                {!! BootForm::text('cargo', 'Cargo: *', old('cargo',optional($directorasignado)->puesto),['readonly']) !!}
            </div>
        </div>               
        <div class="row">
            <div class="col-md-6">        
                @can('asignaciondireccion.update')              
                    <button type="submit" class="btn btn-primary">Guardar</button>    
                @endcan           
                <a href="{{ route('asignaciondireccion.index') }}" class="btn btn-secondary me-2">Cancelar</a>
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $("#direccion_asignada_id").select2().on('change', function(e) {
            var unidadSeleccionada = $(this).children("option:selected").text();
            var unidadSeleccionadaId = $(this).children("option:selected").val();           

            $.ajax({
                url: "{{ route('getDirector') }}"
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
                        $('#direccion_id').val(usuario[0].unidad);                        
                    } else {
                        $('#usuario_id').val('');
                        $('#nombre').val('');
                        $('#cargo').val('');
                        $('#direccion_id').val('');                    
                    }                    
                }
                , error: function() {
                    console.log('Error al cargar los datos');
                }
            });            
        });             
    });

</script>
{!! JsValidator::formRequest('App\Http\Requests\AsignacionDireccionRequest') !!}
@endsection
