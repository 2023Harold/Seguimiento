@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">
            Copias de conocimiento
        </h4>
        <div class="card-toolbar">
            @button('Agregar copia de conocimiento',route('comparecenciacopia.create'))
        </div>
    </div>
    <div class="card-body">
        @include('flash::message')
        <div class="pt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. de copia</th>
                        <th>Nombre</th>
                        <th>Domicilio de la persona a la que se dirige la copia</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($copias)>0)
                    @foreach($copias as $copia)
                    <tr>
                        <td class="text-center">
                            {{-- @can('comparecenciacopia.edit') --}}
                            <a href="{{route('comparecenciacopia.edit',$copia)}}">
                                {{ str_pad($copia->numero, 3, '0', STR_PAD_LEFT) }}
                            </a>
                            {{-- @else
                                {{ str_pad($copia->numero, 3, '0', STR_PAD_LEFT) }}
                            @endcan --}}
                        </td>
                        <td class="text-center">
                            {{ $copia->nombre }}
                        </td>
                        <td class="text-center">
                            @if ($copia->domicilio_notificacion == 'Si')
                                Mismo domicilio que el de la notificación.
                            @else
                            {{ $copia->calle.' '.$copia->numero_domicilio.', '.$copia->colonia.', '.$copia->municipio.', '.$copia->entidad_federativa.'. C.P. '.$copia->codigo_postal }}
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- @can('comparecenciacopia.destroy') --}}
                                @destroy(route('comparecenciacopia.destroy', $copia))
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
            {{ $copias->appends(['numero'=>$request->numero,'nombre'=>$request->nombre,'domicilio'=>$request->domicilio])->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
