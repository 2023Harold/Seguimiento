@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('solicitudesaclaracion.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Solicitudes de Aclaracion
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
                {!! BootForm::close() !!}

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. de auditoría</th>
                                <th>Entidad fiscalizable</th>
                                <th>Acto de fiscalización</th>
                                <th>Monto por aclarar</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                            <tr>
                                <td>
                                    {{ $auditoria->numero_auditoria }}
                                </td>
                                <td width='40%'>
                                    @php
                                    $entidadparciales = explode("-", $auditoria->entidad_fiscalizable);
                                    @endphp
                                    @foreach ($entidadparciales as $entidadparcial)
                                    {{ mb_convert_encoding(mb_convert_case(strtolower($entidadparcial), MB_CASE_TITLE),
                                    "UTF-8"); }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    {{ $auditoria->acto_fiscalizacion }}
                                </td>
                                <td style="text-align: right!important;">
                                    {{ '$'.number_format( $auditoria->total(), 1) }}
                                </td>
                                <td class="text-center">
                                    {{-- @can('solicitudesaclaracion.edit') --}}
                                    <a href="{{ route('solicitudesaclaracion.edit',$auditoria) }}"
                                        class="btn btn-primary">
                                        <i class="align-middle fa fa-file-circle-plus" aria-hidden="true"></i> Ingresar
                                    </a>
                                    {{-- @endcan --}}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="8">
                                    <span class='text-center'>No hay registros en éste apartado</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{
                    $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5')
                    }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection