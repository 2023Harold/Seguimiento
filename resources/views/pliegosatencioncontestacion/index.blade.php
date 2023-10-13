@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('pliegosobservacion.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('pliegosobservacionatencion.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;Oficios de contestación
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._accion')
                <div class="row">
                    <div class="col-md-12">
                        <span>
                            <a class="btn btn-primary float-end" href="{{ route('pliegosatencioncontestacion.create') }}">
                                Agregar
                            </a>
                        </span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Oficio</th>
                                <th>Remitente</th>
                                <th>Recepción en oficialía</th>
                                <th>Fecha de recepción en la unidad de seguimiento</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($contestaciones)>0)
                                @foreach($contestaciones as $contestacion)
                                    <tr>
                                        <td class="text-center">

                                            {{-- @can('comparecenciaanexo.edit') --}}

                                                {{-- <a href="{{route('comparecenciaanexo.edit', $anexo)}}"> --}}
                                                    {{ str_pad($contestacion->consecutivo, 3, '0', STR_PAD_LEFT) }}
                                                {{-- </a> --}}
                                            {{-- @else
                                                {{ str_pad($anexo->numero, 3, '0', STR_PAD_LEFT) }}
                                            @endcan --}}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ asset($contestacion->oficio_contestacion) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($contestacion->oficio_contestacion)) ?>
                                            </a> <br>
                                            <small>Número de oficio: {{ $contestacion->numero_oficio }}</small> <br>
                                            <small>Fecha: {{ fecha($contestacion->fecha_oficio_contestacion) }}</small>
                                        </td>
                                        <td>
                                           {{ $contestacion->nombre_remitente }} <br>
                                           <span class="badge-light-dark text-gray-500">{{ $contestacion->cargo_remitente }}</span>
                                        </td>
                                        <td class="text-center">
                                            CRR: {{ $contestacion->folio_correspondencia }} <br>
                                            Fecha: {{ fecha($contestacion->fecha_recepcion_oficialia) }}
                                         </td>
                                        <td class="text-center">
                                            {{ fecha($contestacion->fecha_recepcion_seguimiento) }}
                                         </td>
                                        <td class="text-center">
                                            <a href="{{route('pliegosatencioncontestacion.edit', $contestacion)}}" class="icon-hover">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{-- @can('comparecenciaanexo.destroy') --}}
                                                @destroy(route('pliegosatencioncontestacion.destroy', $contestacion))
                                            {{-- @endcan --}}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class='text-center' colspan="7">No hay datos registrados en este apartado.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $contestaciones->appends(['consecutivo'=>$request->consecutivo,'nombre_documento'=>$request->nombre_documento])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
