@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            Listado de documentos
        </h1>
        <div class="card-toolbar">
            @button('Agregar',route('pliegosobservaciondocumentos.create'))
        </div>
    </div>
    <div class="card-body">
        @include('flash::message')
        <div class="pt-4">
            <table class="table">
                 <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nombre del documento</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($documentos)>0)
                        @foreach($documentos as $documento)
                            <tr>
                                <td class="text-center">
                                    {{-- @can('comparecenciaanexo.edit') --}}

                                        {{-- <a href="{{route('comparecenciaanexo.edit', $anexo)}}"> --}}
                                            {{ str_pad($documento->consecutivo, 3, '0', STR_PAD_LEFT) }}
                                        {{-- </a> --}}
                                    {{-- @else
                                        {{ str_pad($anexo->numero, 3, '0', STR_PAD_LEFT) }}
                                    @endcan --}}
                                </td>
                                <td>
                                    {{ $documento->nombre_documento }}
                                </td>
                                <td class="text-center">
                                    {{-- @can('comparecenciaanexo.destroy') --}}
                                        @destroy(route('pliegosobservaciondocumentos.destroy', $documento))
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
            {{ $documentos->appends(['consecutivo'=>$request->consecutivo,'nombre_documento'=>$request->nombre_documento])->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
