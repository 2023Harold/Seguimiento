@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('informeprimeraetapa.index',$auditoria) }}
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
                    Informe
                </h1>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')                
                <div class="row">
                    <div class="col-md-12">
                        @if (empty($auditoria->informeprimeraetapa))
                            @can('informeprimeraetapa.create')
                                <a class="btn btn-primary float-end" href="{{ route('informeprimeraetapa.create') }}">
                                    Agregar informe
                                </a> 
                            @endcan
                        @endif
                    </div>                    
                </div>                                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Número de oficio</th>
                                <th>Nombre del titular</th>
                                <th>Acuse envío a notificar</th>                                
                                <th>Acuse de notificación</th>
                                <th>Fecha de notificación</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($auditoria->informeprimeraetapa))
                            <tr>
                                <td class="text-center">
                                    {{ fecha($auditoria->informeprimeraetapa->fecha_informe) }}
                                </td>
                                <td class="text-center">
                                    {{$auditoria->informeprimeraetapa->numero_informe }}
                                </td>
                                <td class="text-center">
                                    {{$auditoria->informeprimeraetapa->nombre_titular_informe }}
                                </td>
                                <td class="text-center">
                                    @btnFile($auditoria->informeprimeraetapa->informe)
                                </td>                                
                                <td class="text-center">
                                    @btnFile($auditoria->informeprimeraetapa->acuse_notificacion)
                                </td>
                                <td class="text-center">
                                    {{ fecha($auditoria->informeprimeraetapa->fecha_notificacion) }}
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
                <div class="pagination">
                    {{
                    $acciones->appends(['numero_auditoria'=>$request->numero_auditoria,'monto_aclarar'=>$request->monto_aclarar,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5')
                    }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
