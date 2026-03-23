@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('user.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                   <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;&nbsp;&nbsp;
                    &nbsp; Pasar notificaciones de usuario
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                
                {!! BootForm::open([
                    'model' => $user,
                    'update' => 'pasarnotificaciones.update',
                    'id' => 'form',
                    // Si usas PUT/PATCH, BootForm suele manejarlo con method spoofing al usar 'update'
                ]) !!}

                <div class="mb-3">
                    <p class="mb-1">
                        <strong>Usuario actual:</strong> {{ $user->full_name ?? $user->name ?? $user->id }}
                    </p>
                    <p class="mb-1">
                        <strong>Notificaciones pendientes:</strong> {{ $NotUsuario->count() }}
                    </p>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        {!!BootForm::select(
                            'nuevo_usuario_id',
                            'Usuario destino:',
                            $users->toArray(),
                            old('nuevo_usuario_id', ''),
                            [
                                'data-control' => 'select2',
                                'class' => 'form-select form-group',
                                'data-placeholder' => 'Seleccionar un usuario',
                                'required' => true
                            ]
                        ) !!}
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        @canany(['pasarnotificaciones.update'])
                            <button type="submit" class="btn btn-primary" id="btn-guardar">Guardar</button>
                        @endcanany
                        <a href="{{ route('pasarnotificaciones.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    </div>
                </div>

                {!! BootForm::close() !!}

            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    
    $(function(){
        // Inicialización Select2 si no se inicializa globalmente
        const $select = $('select[name="nuevo_usuario_id"]');
        if ($select.data('control') === 'select2' && $.fn.select2) {
            $select.select2({
                placeholder: $select.data('placeholder') || 'Seleccionar',
                width: '100%',
                allowClear: true
            });
        }

        // Tu lógica previa (si aplica)
        $('#no_aplica').show();
        $('.rxs').on('ifChanged', function() {
            const estado = $(this).is(':checked') ? 1 : 0;
            if (estado === 0) {
                $('#no_aplica').show();
                $('#btn-guardar').show();
            } else {
                $('#no_aplica').hide();
                $('#btn-guardar').show();
            }
        });
    });

</script> 
@endsection
