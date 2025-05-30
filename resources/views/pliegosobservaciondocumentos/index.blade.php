@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            Listado de documentos
        </h1>
        <div class="card-toolbar">
            @can('pliegosobservaciondocumentos.create')
                @button('Agregar',route('pliegosobservaciondocumentos.create'))
            @endcan
        </div>
    </div>
    <div class="card-body">
        @include('flash::message')
		@include('layouts.contextos._accionpliego')
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
                                    {{ str_pad($documento->consecutivo, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>
                                    {{ $documento->nombre_documento }}
                                </td>
                                <td class="text-center">
                                    @can('pliegosobservaciondocumentos.destroy')
                                        @destroy(route('pliegosobservaciondocumentos.destroy', $documento))
                                    @endcan
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
