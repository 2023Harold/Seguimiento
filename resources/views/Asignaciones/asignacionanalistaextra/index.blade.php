@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('asignacionlideranalistaextra.index', $auditoria) }}
@endsection
@section('content')
<style>
    /* Aísla solo los toggles de estatus */
.form-switch .form-check-input {
  width: 3rem;
  height: 1.5rem;
  background-color: #BB945C;
  background-image: none;
  border: 2px solid rgba(0,0,0,.25);
  border-radius: 2rem;
  position: relative;
  transition: background-color .15s ease-in-out, border-color .15s ease-in-out;
}

.form-switch .form-check-input:checked {
  background-color: #960048; /* color primario Bootstrap */
  border-color: #BB945C;
}

.form-switch .form-check-input::before {
  content: "";
  position: absolute;
  top: .129rem;
  left: .1rem;
  width: 1rem;
  height: 1rem;
  background-color: #fff;
  border-radius: 50%;
  transition: transform .15s ease-in-out;
}

.form-switch .form-check-input:checked::before {
  transform: translateX(1.5rem);
}
</style>
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('asignaciondepartamento.index') }}"><i
                        class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; {{ $accions }}
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            {!!BootForm::hidden('accions', $accions) !!}
            <!-- !! BootForm::hidden('usuario_id',null,['id'=>'usuario_id']) !! -->
            @can('asignacionlideranalistaextra.edit')
                <div class="d-flex justify-content-end">
                    <a href="{{ route('asignacionlideranalistaextra.edit', $auditoria) }}" class="btn btn-primary">
                        Agregar <i class="bi bi-plus-square fs-1"></i>
                    </a>
                </div>
            @endcan
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Analista</th>
                            <th>Estatus</th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($AnalistaExtra as $ana)
                            <tr>
                                <td class="text-center">
                                    <span class="">
                                        {{ $ana->usuarioana->name ?? 'Usuario no encontrado' }}
                                    </span>
                                </td>
                                {{-- Estatus desde segauditoria_usuarios (columna estatus) --}}
                                <td class="text-center">
                                    <span
                                        class="badge estatus-badge {{ $ana->estatus === 'Activo' ? 'bg-success' : 'bg-danger' }}"
                                        id="badge-estatus-{{ $ana->id }}">
                                        {{ $ana->estatus === 'Activo' ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    <div class="form-check form-switch d-inline-block ms-2">
                                        {!!BootForm::checkbox( 'estatus_toggle[' . $ana->id . ']',
                                            ' ','Activo',
                                            $ana->estatus === 'Activo',
                                            [
                                                'id' => 'toggle-estatus-' . $ana->id,
                                                'class' => 'form-check-input toggle-estatus', // <-- sin i-checks, sin form-switch aquí
                                                'data-url' => route('asignacionlideranalistaextra.cambiar', $ana),
                                                'data-id' => $ana->id,
                                            ]
                                        ) !!}
                                        
                                    </div>
                                    <label class="form-check-label" for="toggle-estatus-{{ $ana->id }}"></label>
                                </td>
                                <td class="text-center">
                                    @can('asignacionlideranalistaextra.eliminar')
                                        <a href="{{ route('asignacionlideranalistaextra.eliminar', $ana) }}"
                                            class="corner-button-error btn-delete" data-nombre="{{ $ana->usuarioana->name ?? '' }}">
                                            <span class="cb-content">
                                                <i class="bi bi-trash-fill text-danger fs-2" aria-hidden="true"></i> Eliminar
                                            </span>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {!!JsValidator::formRequest('App\Http\Requests\AsignacionStaffJuridicoRequest') !!}
    <script>
        // ----- SweetAlert2: Confirmar eliminación sobre <a> -----
        (function initDeleteWithSwal() {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            document.querySelectorAll('.btn-delete').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    const nombre = this.getAttribute('data-nombre') || 'este registro';

                    swalWithBootstrapButtons.fire({
                        title: "¿Seguro que deseas eliminar?",
                        text: `Se eliminará ${nombre}. Esta acción no se puede deshacer.`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Sí, eliminar",
                        cancelButtonText: "No, cancelar",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {swalWithBootstrapButtons.fire({
                            title: "Eliminado!",
                            text: `Se elimino a ${nombre} correctamente de la auditoria`,
                            icon: "success"
                            });
                            // Redirección a tu ruta GET de eliminar
                            window.location.href = href;
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            swalWithBootstrapButtons.fire({
                                title: "Cancelado",
                                text: "No se realizó ninguna acción.",
                                icon: "info"
                            });
                        }
                    });
                });
            });
        })();

        // ----- Toggle estatus: llama a tu ruta GET /cambiar/{ana} -----

        (function initToggleEstatus() {
            function updateBadge(id, estatus) {
                const badge = document.getElementById('badge-estatus-' + id);
                if (!badge) return;
                badge.textContent = (estatus === 'Activo') ? 'Activo' : 'Inactivo';
                badge.classList.remove('bg-success', 'bg-danger');
                badge.classList.add(estatus === 'Activo' ? 'bg-success' : 'bg-danger');
            }

            document.querySelectorAll('.toggle-estatus').forEach(input => {
                input.addEventListener('change', async function (e) {
                    const checked = e.target.checked;
                    const baseUrl = e.target.getAttribute('data-url');
                    const id = e.target.getAttribute('data-id');

                    // Como mantendrás GET, pasamos estatus explícito:
                    const estatus = checked ? 'Activo' : 'Inactivo';
                    const url = `${baseUrl}?estatus=${encodeURIComponent(estatus)}`;

                    try {
                        const resp = await fetch(url, { method: 'GET', headers: { 'Accept': 'application/json' } });
                        let data = null; try { data = await resp.json(); } catch (_) { }
                        if (!resp.ok) throw new Error('Error HTTP');

                        updateBadge(id, data?.estatus ?? estatus);

                        Swal.fire({
                            icon: 'success',
                            title: 'Estatus actualizado',
                            text: `Ahora está: ${data?.estatus ?? estatus}`,
                            timer: 1200,
                            showConfirmButton: false
                        });
                    } catch (err) {
                        e.target.checked = !checked; // revertir
                        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo actualizar el estatus.' });
                    }
                });
            });
        })();
    </script>
@endsection