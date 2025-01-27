@extends('layouts.app')
@section('breadcrums')    
    @if ($accionstaff=='Asignación')
    {{Breadcrumbs::render('asignacionstaff.edit',$auditoria) }}   
    @else
        {{Breadcrumbs::render('asignacionstaff.reasignar', $auditoria, 'Reasignación') }}
    @endif     
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('asignaciondepartamento.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
            &nbsp; {{ $accionstaff }}       
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        {!!BootForm::open(['model' => $auditoria,'update' => 'asignacionstaff.update','id' => 'form']) !!}
        {!!BootForm::hidden('accionstaff',$accionstaff) !!}
        <!-- !! BootForm::hidden('usuario_id',null,['id'=>'usuario_id']) !! -->
        <div class="row">
            <div class="col-md-3">
                {!!BootForm::select('staff_juridico_id', 'Staff juridico: *',$staff , old('staff_juridico_id',$auditoria->staff_juridico_id), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
            </div>
        </div> 
            
            {!!BootForm::hidden('staff_asignada', null, ['id' => 'staff_asignada']) !!}

        <div class="row">
            <div class="col-md-3">
                {!!BootForm::text('cargo', 'Cargo: *', old('cargo'), ['readonly', 'id' => 'cargo']) !!}
            </div>
        </div>       
        <div class="row">
            <div class="col-md-6">         
                    @can('asignacionstaff.update')                     
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    @endcan  
                    <a href="{{ route('asignaciondepartamento.index') }}" class="btn btn-secondary me-2">Cancelar</a>                
            </div>
        </div>
        {!!BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        const selectStaff = $("#staff_juridico_id");
        const inputCargo = $('#cargo');
        const inputStaffAsignada = $('#staff_asignada'); // Input oculto

        // Función para cargar el cargo del usuario
        function cargarCargo(userId) {
            if (userId) {
                $.ajax({
                    url: "{{ route('asignacionstaff.getStaff') }}",
                    dataType: "JSON",
                    type: "POST",
                    data: {
                        "user_id": userId,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response); // Depuración
                        if (response.length > 0) {
                            const usuario = response[0];
                            if (usuario.puesto) {
                                inputCargo.val(usuario.puesto); // Asigna el puesto al input
                            } else {
                                inputCargo.val('No se encontró el puesto');
                            }
                            // Actualizar el input oculto con el nombre del usuario
                            inputStaffAsignada.val(usuario.name);
                        } else {
                            inputCargo.val('No se encontró el puesto');
                            inputStaffAsignada.val('');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar el puesto:', error);
                        inputCargo.val('Error al obtener el puesto');
                        inputStaffAsignada.val('');
                    }
                });
            } else {
                inputCargo.val('');
                inputStaffAsignada.val('');
            }
        }

        // Ejecutar al cargar la página si hay un usuario seleccionado
        const userIdInicial = selectStaff.val();
        if (userIdInicial) {
            cargarCargo(userIdInicial);
        }

        // Ejecutar al cambiar el select
        selectStaff.select2().on('change', function() {
            const userId = $(this).val();
            cargarCargo(userId);
        });
    });
</script>
{!!JsValidator::formRequest('App\Http\Requests\AsignacionStaffJuridicoRequest') !!}
@endsection
