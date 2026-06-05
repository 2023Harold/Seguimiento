@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('asignarequipotrabajo.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Asignación de Equipo de Trabajo en la Auditoría
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')   
                <div class="row">
                    <div class="col-md-12 text-end">
                        @can('asignarequipotrabajo.update')
                            {!!BootForm::open(['route' => 'asignarequipotrabajo.sincronizarTodo','method' => 'POST','id' => 'form-sincronizar']) !!}
                                <button type="submit" class="btn btn-primary">
                                    Sincronizar asignaciones existentes
                                </button>
                            {!!BootForm::close() !!}
                        @endcan
                    </div>
                </div>               
                {!!BootForm::open(['route'=>'asignarequipotrabajo.index','method'=>'GET']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            {!!BootForm::text('numero_auditoria', "No. auditoría:", old('numero_auditoria', $request->numero_auditoria)) !!}
                        </div>
                        <div class="col-md-2">
                            {!!BootForm::text('entidad_fiscalizable', "Entidad fiscalizable:", old('entidad_fiscalizable', $request->entidad_fiscalizable)) !!}
                        </div>
                        <div class="col-md-2">
                            {!!BootForm::text('acto_fiscalizacion', "Acto de fiscalización:", old('acto_fiscalizacion', $request->acto_fiscalizacion)) !!}
                        </div>
                        <div class="col-md-3">
                            {!!BootForm::radios("asignaciones", 'Auditorias: ',['Todas' => ' Todas', 'Asignadas'=>' Asignadas','Pendientes'=>' Pendientes'],
                                old('asignaciones', empty($request->asignaciones) ? 'Todas' : $request->asignaciones),true,['class'=>'i-checks']) !!}
                        </div> 
						
                        <div class="col-md-1 mt-8">
                            <button type="submit" class="btn btn-primary"><i class="align-middle fas fa-search" aria-hidden="true"></i>Buscar</button>                           
                        </div>
                    </div>
                {!!BootForm::close() !!}     
                <div class="row">
                    <div class="col-md-12">
                        <div class="pagination float-end">
                        {{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion,'asignaciones'=>$request->asignaciones])->links('vendor.pagination.bootstrap-5') }}
                        </div>              
                    </div>              
                </div>          
                <div class="row">
                    <div class="col-md-12 text-end">
                        @can('asignarequipotrabajo.create')
                            <a href="{{ route('auditoriaseguimiento.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>Agregar</a>
                        @endcan
                    </div>
                </div>     
                      
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. de auditoría</th>
                                <th >Entidad fiscalizable</th>                                                              
                                <th colspan="2">Equipo de trabajo  <a href="{{ route('auditoriaseguimiento.create') }}" class="btn btn-color-primary btn-active-color-info"><i class="bi bi-search" style="font-size: 18px;" aria-hidden="true"></i></a></th>
                            </tr>
                            <tr>                                                          
                                <th colspan="2"></th>
                                <th>Lider</th>
                                <th >Analista</th>    
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                                <tr>
                                    <td class="col-1">
                                        {{ $auditoria->numero_auditoria }}
                                    </td>
                                    <td width='40%'>
                                        @php
                                            $entidadparciales = explode("-", $auditoria->entidad_fiscalizable);                                            
                                        @endphp
                                        @foreach ($entidadparciales as $entidadparcial)
                                            {{ mb_convert_encoding(mb_convert_case(strtolower($entidadparcial), MB_CASE_TITLE), "UTF-8") }}<br>
                                        @endforeach                                        
                                    </td>
                                    <td>
                                         {{-- FORM PARA AGREGAR --}}
                                        @can('asignarequipotrabajo.update')
                                            {!!BootForm::open(['model' => $auditoria,'store' => 'asignarequipotrabajo.store','update' => 'asignarequipotrabajo.update','id' => 'form']) !!}
                                            {!!BootForm::hidden('auditoria_id',$auditoria) !!}
                                            <div class="row">
                                                {!!BootForm::select('lider_asignado_id', 'Nombre: *', $lideres, old('lider_asignado_id'), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}

                                            </div>
                                             <div class="col-md-4 align-self-end">
                                                 <button type="submit" class="btn btn-primary">
                                                     <i class="fa fa-plus"></i> Agregar
                                                 </button>
                                             </div>
                                             {!! BootForm::close() !!}
                                        @endcan
                                    </td>     
                                    <td>
                                        <button class="btn btn-sm btn-light-primary"data-bs-toggle="collapse"data-bs-target="#equipo-{{ $auditoria->id }}">
                                            <i class="fa fa-users"></i> Ver equipo
                                        </button>
                                        <div id="equipo-{{ $auditoria->id }}"class="collapse mt-3">
                                            
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
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pagination float-end">
                    {{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion,'asignaciones'=>$request->asignaciones])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-sync-todo');
            if (!btn) return;

            Swal.fire({
                title: 'Sincronizar asignaciones',
                text: 'Se tomarán TODAS las asignaciones existentes.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Sí, sincronizar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (!result.isConfirmed) return;

                fetch('{{ route("asignarequipotrabajo.sincronizarTodo") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Listo',
                        text: data.message
                    });
                });
            });
        });
    </script>
@endsection

