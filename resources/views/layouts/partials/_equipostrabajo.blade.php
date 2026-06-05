<style>
    .user-results .user-option {
    cursor: pointer;
        }

        .user-results .user-option:hover,
        .user-results .user-option:focus {
        background-color: #A13B71;
        color: white;
        }
</style>
@foreach($roles as $rolCode => $rolLabel)
    <div class="card mb-4">
        <div class="card-body">
            @php
                $asignadosPorRol = $auditoria->auditoriausuarios->where('rol_code', $rolCode)->where('estatus', 'Activo');
            @endphp
            {{-- LISTADO DE PERSONAS ASIGNADAS --}}
                <h2 class="text-primary">{{ $rolLabel }}</h2>
                <ul class="list-group mb-3" id="lista-{{ $auditoria->id }}-{{ $rolCode }}">
                @forelse($asignadosPorRol as $asig)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ optional($asig->usuarioequipo)->name }}
                        @if(auth()->user()->siglas_rol == 'TUS' ||(auth()->user()->siglas_rol == 'DS' && $rolCode != 'DIRECTOR') ||(auth()->user()->siglas_rol == 'JD' && in_array($rolCode, ['LIDER','ANALISTA','STAFF_JURIDICO'])))
                            <button type="button"
                                class="btn btn-sm btn-outline-danger btn-remove-user"
                                data-url="{{ route('asignarequipotrabajo.destroy', $asig) }}"
                                data-item="asig-{{ $asig->id }}">
                                <i class="fa fa-times"></i>
                            </button>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item text-muted">Sin asignar</li>
                @endforelse
                </ul>

            
            {{-- FORM PARA AGREGAR --}}
            @can('asignarequipotrabajo.update')
                    @if(
                        auth()->user()->siglas_rol == 'TUS' ||
                        (auth()->user()->siglas_rol == 'DS' && $rolCode != 'DIRECTOR') ||
                        (auth()->user()->siglas_rol == 'JD' && in_array($rolCode, ['LIDER','ANALISTA','STAFF_JURIDICO']))
                    )
                        {!! BootForm::open(['route'=>'asignarequipotrabajo.store','method'=>'POST','class'=>'row g-2 equipo-form']) !!}
                            {!! BootForm::hidden('auditoria_id', $auditoria->id) !!}
                            {!! BootForm::hidden('rol_code', $rolCode) !!}
                            {!! BootForm::hidden('user_id', null, ['class'=>'user-id-hidden']) !!}

                            <div class="col-md-8 position-relative">
                                <label class="form-label">Agregar {{ $rolLabel }}</label>
                                <input type="text" class="form-control user-search-input" placeholder="Buscar por nombre o apellido" autocomplete="off">

                                <div class="list-group position-absolute w-100 d-none user-results">
                                    @foreach($usuariosDisponiblesPorRol[$rolCode] ?? [] as $id => $label)
                                        <button type="button" class="list-group-item list-group-item-action user-option" data-id="{{ $id }}" data-name="{{ $label }}">
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-4 align-self-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Agregar
                                </button>
                            </div>
                        {!! BootForm::close() !!}
                    @endif
                
            @endcan

        </div>
    </div>
@endforeach
@section('script')
    <script>
        document.addEventListener('input', function (e) {
            if (!e.target.classList.contains('user-search-input')) return;

            const input = e.target;
            const form = input.closest('.equipo-form');
            const list = form.querySelector('.user-results');
            const options = list.querySelectorAll('.user-option');

            const term = input.value.toLowerCase();
            let hasResults = false;

            options.forEach(opt => {
                const match = opt.dataset.name.toLowerCase().includes(term);
                opt.classList.toggle('d-none', !match);
                if (match) hasResults = true;
            });

            list.classList.toggle('d-none', !hasResults);
        });

        // Seleccionar usuario
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.user-option');
            if (!btn) return;

            const form = btn.closest('.equipo-form');
            form.querySelector('.user-search-input').value = btn.dataset.name;
            form.querySelector('.user-id-hidden').value = btn.dataset.id;

            form.querySelector('.user-results').classList.add('d-none');
        });

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function (e) {
            document.querySelectorAll('.user-results').forEach(list => {
                if (!list.contains(e.target) && !list.previousElementSibling.contains(e.target)) {
                    list.classList.add('d-none');
                }
            });
        });

        // Submit AJAX
        document.addEventListener('submit', function (e) {
            if (!e.target.classList.contains('equipo-form')) return;
            e.preventDefault();

            const form = e.target;
            const userId = form.querySelector('.user-id-hidden').value;

            if (!userId) {
                Swal.fire('Atención', 'Debe seleccionar un usuario válido.', 'warning');
                return;
            }

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.ok) {
                        Swal.fire('Atención', data.message, 'warning');
                        return;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Listo',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    //Obtener datos necesarios
                    const auditoriaId = form.querySelector('[name="auditoria_id"]').value;
                    const rolCode = form.querySelector('[name="rol_code"]').value;

                    // Obtener la lista <ul> correcta
                    const ul = document.getElementById(`lista-${auditoriaId}-${rolCode}`);
                    if (!ul) return;

                    // Quitar "Sin asignar" si existe
                    const empty = ul.querySelector('.text-muted');
                    if (empty) empty.remove();

                    // Crear nuevo <li>
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.id = `asig-${data.user.id}`;

                    li.innerHTML = `
                        ${data.user.name}
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-remove-user"
                            data-url="${data.user.destroy_url}"
                            data-item="asig-${data.user.id}">
                            <i class="fa fa-times"></i>
                        </button>
                    `;

                    // Insertar al final
                    ul.appendChild(li);

                    // Limpiar formulario
                    form.reset();
                    form.querySelector('.user-results').classList.add('d-none');
                });
        });
        // Mostrar todos al hacer focus o click
        document.addEventListener('focusin', function (e) {
            if (!e.target.classList.contains('user-search-input')) return;

            const input = e.target;
            const form = input.closest('.equipo-form');
            const list = form.querySelector('.user-results');
            const options = list.querySelectorAll('.user-option');

            options.forEach(opt => opt.classList.remove('d-none'));
            list.classList.remove('d-none');
        });
        // Eliminar 
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-remove-user');
            if (!btn) return;

            e.preventDefault();

            Swal.fire({
                title: '¿Quitar integrante del equipo?',
                text: 'Esta acción no se puede revertir',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, quitar',
                cancelButtonText: 'Cancelar',
                customClass: {
						confirmButton: 'btn btn-sm btn-danger',
						cancelButton: 'btn btn-sm btn-primary'
					},
            }).then(result => {
                if (!result.isConfirmed) return;

                fetch(btn.dataset.url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Usuario removido del equipo correctamente',
                        timer: 1250,
                        showConfirmButton: false,
                        customClass: {
                            confirmButton: 'btn btn-sm btn-primary',
                            cancelButton: 'btn btn-sm btn-primary'
                        }
                    });
                    
                    // quitar del DOM
                    const li = document.getElementById(btn.dataset.item);
                    if (li) li.remove();
                });
            });
        });
    </script>
@endsection