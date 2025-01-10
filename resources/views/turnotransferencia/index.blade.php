@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('turnotransferencia.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('auditoriaseguimiento.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Envío al Archivo de Transferencia
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')              
                @include('flash::message')                
                <div class="row">
                    <div class="col-md-12">
                        @if (empty($turnotransferencia))
                            {{-- @can('turnotransferencia.create') --}}
                                <a class="btn btn-primary float-end" href="{{ route('turnotransferencia.create') }}">
                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Archivo de Transferencia
                                </a> 
                            {{-- @endcan --}}
                        @endif
                    </div>                    
                </div>                                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>                               
                                <th>Número del oficio</th>
                                <th>Inventario de documentos</th>
                                <th>Fecha </th>
                                <th>Tiempo de resguardo</th>
                                <th>Clave topográfica</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($turnotransferencia))
                            <tr>
                                <td class="text-center">
                                    {{$turnotransferencia->numero_transferencia }}
                                </td>
                                <td class="text-center">
                                    @btnFile($turnotransferencia->inventario_transferencia)
                                </td>
                                <td class="text-center">
                                    {{ fecha($turnotransferencia->fecha_transferencia) }}
                                </td>                                
                                <td class="text-center">
                                    {{$turnotransferencia->tiempo_resguardo }}
                                </td>
                                <td class="text-center">
                                    {{$turnotransferencia->clave_topografica }}
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td class="text-center" colspan="5">
                                    No se encuentran registros en este apartado.
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>                
            </div>
        </div>
    </div>
</div>
@endsection
