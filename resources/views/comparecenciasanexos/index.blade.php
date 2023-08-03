@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            Anexos
        </h1>
        <div class="card-toolbar">
            @button('Agregar anexo',route('comparecenciaanexo.create'))
        </div>
    </div>
    <div class="card-body">
        @include('flash::message')
        <div class="pt-4">
            <table class="table">
                 <thead>
                    <tr>
                        <th>No. de anexo</th>
                        <th>Anexo</th>
                        <th>Descripción</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($anexos)>0)
                        @foreach($anexos as $anexo)
                            <tr>
                                <td class="text-center">
                                    {{-- @can('comparecenciaanexo.edit') --}}

                                        <a href="{{route('comparecenciaanexo.edit', $anexo)}}">
                                            {{ str_pad($anexo->numero, 3, '0', STR_PAD_LEFT) }}
                                        </a>
                                    {{-- @else
                                        {{ str_pad($anexo->numero, 3, '0', STR_PAD_LEFT) }}
                                    @endcan --}}
                                </td>
                                <td class="text-center">
                                    <a href="{{ asset($anexo->archivo) }}" target="_blank">
                                        <?php echo htmlspecialchars_decode(iconoArchivo($anexo->archivo)) ?>
                                    </a> 
                                </td>
                                <td>
                                    {{ $anexo->descripcion }}
                                </td>
                                <td class="text-center">
                                    {{-- @can('comparecenciaanexo.destroy') --}}
                                        @destroy(route('comparecenciaanexo.destroy', $anexo))
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
            {{ $anexos->appends(['numero'=>$request->numero,'descripcion'=>$request->descripcion])->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
