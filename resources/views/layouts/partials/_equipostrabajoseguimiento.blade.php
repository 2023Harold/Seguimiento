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
@if(!usaEquipoTrabajo())
    {{-- MODELO ANTIGUO (CP ≤ 2023) --}}
    <p><b>-Dirección:</b> {{ $auditoria->direccion_asignada ?? 'Sin asignar' }}</p>
    <p><b>-Departamento:</b> {{ $auditoria->departamento_encargado ?? 'Sin asignar' }}</p>
    <p><b>-Líder de proyecto:</b> {{ $auditoria->lidercp->name ?? 'Sin asignar' }}</p>
    <p><b>-Analista:</b> {{ $auditoria->analistacp->name ?? 'Sin asignar' }}</p>
@else
    {{-- MODELO NUEVO (CP ≥ 2024: EQUIPO DE TRABAJO) --}}
    @php
        $equipo = $auditoria->auditoriausuarios
            ->where('estatus', 'Activo')
            ->groupBy('rol_code');
    @endphp

    <p><b>-Dirección:</b>
        {{ $auditoria->direccion_asignada ?? 'Sin asignar' }}
    </p>

    <p><b>-Departamento:</b>
        {{ $auditoria->departamento_encargado ?? 'Sin asignar' }}
    </p>

    <p><b>-Líder:</b>
        {{ optional($equipo['Lider'][0]->usuarioequipo ?? null)->name ?? 'Sin asignar' }}
    </p>

    <p><b>-Analista(s):</b>
        @if(isset($equipo['Analista']))
            {{ $equipo['Analista']->pluck('usuarioequipo.name')->implode(', ') }}
        @else
            Sin asignar
        @endif
    </p>
@endif
@section('script')
    <script>
        
    </script>
@endsection