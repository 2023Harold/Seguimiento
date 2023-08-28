@extends('layouts.app')
@section('breadcrums')

{{ Breadcrumbs::render('prasacciones.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('pras.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Acciones
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                {!! BootForm::open(['route'=>'pras.index','method'=>'GET']) !!}
                <div class="row">
                    <div class="col-md-2">
                        {!! BootForm::text('numero_auditoria', "No. auditoría:", old('numero_auditoria',
                        $request->numero_auditoria)) !!}
                    </div>
                    <div class="col-md-2">
                        {!! BootForm::text('entidad_fiscalizable', "Entidad fiscalizable:", old('entidad_fiscalizable',
                        $request->entidad_fiscalizable)) !!}
                    </div>
                    <div class="col-md-2">
                        {!! BootForm::text('acto_fiscalizacion', "Acto de fiscalización:", old('acto_fiscalizacion',
                        $request->acto_fiscalizacion)) !!}
                    </div>
                    <div class="col-md-6 mt-8">
                        <button type="submit" class="btn btn-primary"><i class="align-middle fas fa-search"
                                aria-hidden="true"></i> Buscar</button>
                    </div>
                </div>
                <th>
                </th>
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
                                <td>
                                    {{ $accion->numero }}
                                </td>
                                <td>
                                    {{ $accion->tipo}}
                                </td>
                                <td style="text-align: right!important;">
                                    {{ '$'.number_format( $accion->monto_aclarar, 2) }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('prasacciones.edit',$accion) }}" class="btn btn-primary">
                                        <i class="align-middle fas fa-file-plus" aria-hidden="true"></i> Turnar
                                    </a>
                                </td>
                            </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{
                    $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'monto_aclarar'=>$request->monto_aclarar,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5')
                    }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection