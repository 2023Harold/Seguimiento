@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('prasacciones.index',$auditoria) }}
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
                    PRAS
                </h1>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')
                {!! BootForm::open(['route'=>'prasacciones.index','method'=>'GET']) !!}
                <div class="row">
                    <div class="col-md-2">
                        {!! BootForm::text('numero_accion', "No. acción:", old('numero_accion',
                        $request->numero_auditoria)) !!}
                    </div>
                    <div class="col-md-6 mt-8">
                        <button type="submit" class="btn btn-primary"><i class="align-middle fas fa-search"
                                aria-hidden="true"></i> Buscar</button>
                    </div>
                </div>
                {!! BootForm::close() !!}
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. de accion</th>
                                <th>Tipo de acción</th>
                                <th>Monto por aclarar</th>
                                <th>Turnar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($acciones as $accion)
                            <tr>
                                <td class="text-center">
                                    {{ $accion->numero }}
                                </td>
                                <td>
                                    {{ $accion->tipo}}
                                </td>
                                <td style="text-align: right!important;">
                                    {{ '$'.number_format( $accion->monto_aclarar, 2) }}
                                </td>
                                <td class="text-center">
                                    @if (empty($accion->pras)&&in_array("Lider de Proyecto", auth()->user()->getRoleNames()->toArray())&&!empty($accion->auditoria->comparecencia)&&!empty($accion->auditoria->comparecencia->oficio_acta))
                                       @can('prasacciones.edit')
                                            <a href="{{ route('prasacciones.edit',$accion) }}" class="btn btn-primary">
                                                <i class="align-middle fa fa-file-circle-plus" aria-hidden="true"></i> Turnar
                                            </a>
                                        @endcan
                                    @else
                                        @if (!empty($accion->pras))
                                            @can('prasacciones.edit')
                                                <a href="{{ route('prasacciones.edit',$accion) }}" class="btn btn-primary">
                                                    <i class="align-middle fa fa-magnifying-glass-chart" aria-hidden="true"></i> Consultar
                                                </a>
                                            @endcan
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="4">
                                    No se encuentran registros en este apartado.
                                </td>                                
                            </tr>

                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{
                    $acciones->appends(['numero_accion'=>$request->numero_accion])->links('vendor.pagination.bootstrap-5')
                    }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
