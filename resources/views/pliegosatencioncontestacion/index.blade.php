@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            Listado de contestaciones
        </h1>
        <div class="card-toolbar">
            @button('Agregar',route('pliegosobservacioncontestaciones.create'))
        </div>
    </div>
    <div class="card-body">
        @include('flash::message')
        <div class="pt-4">
            <table class="table">
                 <thead>
                    <tr>
                        <th>No.</th>
                        <th>Fecha</th>
                        <th>Documento</th>
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
                                    {{ fecha($contestacion->fecha_oficio_contestacion) }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ asset($contestacion->oficio_contestacion) }}" target="_blank">
                                        <?php echo htmlspecialchars_decode(iconoArchivo($contestacion->oficio_contestacion)) ?>
                                    </a>
                                </td>
                                <td class="text-center">
                                    {{-- @can('comparecenciaanexo.destroy') --}}
                                        @destroy(route('pliegosobservacioncontestaciones.destroy', $contestacion))
                                    {{-- @endcan --}}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class='text-center' colspan="4">No hay datos registrados en este apartado.</td>
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
@endsection
