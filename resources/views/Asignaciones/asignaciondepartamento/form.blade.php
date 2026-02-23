@extends('layouts.app')
@section('breadcrums')
    @if ($acciondep=='Asignación')
        {{ Breadcrumbs::render('asignaciondepartamento.edit',$auditoria) }}
    @else
        {{ Breadcrumbs::render('asignaciondepartamento.reasignar',$auditoria) }}
    @endif    
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
        {!! BootForm::open(['model' => $auditoria,'update' => 'asignaciondepartamento.update','id' => 'form']) !!}
        {{-- {!! BootForm::hidden('accion',$accion) !!}
        {!! BootForm::hidden('usuario_id',null,['id'=>'usuario_id']) !!}
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
        </div>                --}}
        <h3 class="card-title text-primary">Acciones</h3> 
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Consecutivo</th>
                        <th>Tipo de acción</th>
                        <th>Número de acción</th>  
                        <th>Monto por aclarar</th>
                        <th>Departamento</th>                      
                    </tr>
                </thead>
                <tbody>
                    @forelse ($acciones as $accion)
                        <tr>
                            <td class="text-center">
                                {{ str_pad($accion->consecutivo, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                {{ $accion->tipo }}
                            </td>
                            <td class="text-center">
                                {{ $accion->numero }}                                        
                            </td>                 
                            <td style="text-align: right!important;">
                                {{ '$'.number_format( $accion->monto_aclarar, 2) }} 
                            </td>
                            <td class="text-center"  width="20%">    
                                <div class="row">                                    
                                    <div class="col-md-12">
                                        @if (!empty($auditoria->asignacion_departamentos) && $auditoria->asignacion_departamentos=='Si' )
                                            <span class="badge-light-secondary text-gray-600">
                                                {{ $accion->departamento_asignado }} <br>
                                                {{ $accion->depaasignado->name }} <br>
                                                {{ $accion->depaasignado->puesto }} <br>                                                
                                            </span>
                                            @if ($accion->reasignacion_departamento=="Si")
                                                <span class="badge badge-square badge-light-secondary text-gray-500">Reasignado</span>
                                            @else
                                                @can('asignaciondepartamento.reasignar')
                                                    <a href="{{ route('asignaciondepartamento.reasignar',$accion) }}" class="btn btn-primary">
                                                        <i class="fa fa-exchange"></i> Reasignar                                                    
                                                    </a>
                                                @endcan
                                            @endif                                            
                                        @else
                                            @if (in_array("Director de Seguimiento", auth()->user()->getRoleNames()->toArray()))
                                                {!! BootForm::hidden('accion_id[]',$accion->id) !!}
                                                {!! BootForm::select('departamento_asignado_id_'.$accion->id, false, $unidades->toArray() , old('departamento_asignado_id_'.$accion->id,$auditoria->departamento_asignado_id), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                                            @else
                                                    Sin asignación
                                            @endif 
                                        @endif
                                    </div>
                                </div>                     
                            </td>                                
                        </tr>                                            
                    @empty
                        <tr>
                            <td class="text-center" colspan="8">
                                <span class='text-center'>No hay registros en éste apartado</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-md-6">                
                @if (empty($auditoria->asignacion_departamentos) || $auditoria->asignacion_departamentos!='Si' )
                    @can('asignaciondepartamento.update')                     
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    @endcan  
                    <a href="{{ route('asignaciondepartamento.index') }}" class="btn btn-secondary me-2">Cancelar</a>            
                @endif
                    
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
