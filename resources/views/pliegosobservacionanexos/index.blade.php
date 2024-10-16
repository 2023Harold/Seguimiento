@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('pliegosobservacionanexos.index',$pliegosobservacion,$auditoria) }}
@endsection
@section('content')
<div class="row">
  @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('pliegosobservacionanalisis.edit',$pliegosobservacion) }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;Anexos
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                <div class="row">
                    <div class="col-md-12">
                        <span>
                            <a class="btn btn-primary float-end" href="{{ route('pliegosobservacionanexos.create') }}">
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
                                <th>Archivo</th>
                                <th>Nombre archivo</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($anexos)>0)
                                @foreach($anexos as $anexo)
                                    <tr>
                                        <td class="text-center">

                                            {{-- @can('comparecenciaanexo.edit') --}}

                                                {{-- <a href="{{route('comparecenciaanexo.edit', $anexo)}}"> --}}
                                                    {{ str_pad($anexo->consecutivo, 3, '0', STR_PAD_LEFT) }}
                                                {{-- </a> --}}
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
                                           {{ $anexo->nombre_archivo }}                                         
                                        </td>                                        
                                        <td class="text-center">
                                            {{-- @can('comparecenciaanexo.destroy') --}}
                                                @destroy(route('pliegosobservacionanexos.destroy', $anexo))
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
                    <a class="btn btn-primary" href="{{ route('pliegosobservacionatencion.index') }}">
                        Continuar
                    </a>
                </div>
                <div class="pagination">
                    {{ $anexos->appends(['consecutivo'=>$request->consecutivo,'nombre_archivo'=>$request->nombre_archivo])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
