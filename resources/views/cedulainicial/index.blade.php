@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('cedulainicial.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('auditoriaseguimiento.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Cédulas
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ route('cedulainicialprimera.edit',$auditoria) }}" rel="noopener noreferrer">
                            <div class="card">                           
                                <div class="card-body overflow-auto h-50px btn btn-secondary">
                                    <div class="d-flex flex-column"> Cédula General de Seguimiento
                                    </div>
                                </div>
                            </div>
                        </a>                        
                    </div>                    
                    <div class="col-md-4">
                        <a href="{{ route('cedulageneralrecomendacion.edit',$auditoria) }}" rel="noopener noreferrer">
                            <div class="card">                           
                                <div class="card-body overflow-auto h-50px btn btn-secondary">
                                    <div class="d-flex flex-column">Cédula General Recomendaciones
                                    </div>
                                </div>
                            </div>
                        </a>                        
                    </div>                    
                    <div class="col-md-4">
                        <a href="{{ route('cedulageneralpras.edit',$auditoria) }}" rel="noopener noreferrer">
                            <div class="card">                           
                                <div class="card-body overflow-auto h-50px btn btn-secondary">
                                    <div class="d-flex flex-column">Cédula General PRAS
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>     
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ route('cedulaanalitica.edit',$auditoria) }}" rel="noopener noreferrer">
                            <div class="card">                           
                                <div class="card-body overflow-auto h-50px btn btn-secondary">
                                    <div class="d-flex flex-column">Cédula analitica
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>   
                    <div class="col-md-4">
                        <a href="{{ route('cedulaanaliticarecomendacion.edit',$auditoria) }}" rel="noopener noreferrer">
                            <div class="card">                           
                                <div class="card-body overflow-auto h-50px btn btn-secondary">
                                    <div class="d-flex flex-column">Cédula analitica desempeño
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>   
                </div>
                

                {{-- <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. de auditoría</th>
                                <th>Entidad fiscalizable</th>
                                <th>Acto de fiscalización</th>
                                <th>Monto por aclarar</th>
                                <th>Acciones</th>
                                <td></td>
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
                                    {{ '$'.number_format( $auditoria->total(), 2) }}
                                </td>
                                <td class="text-center">
                                    @can('cedulainicial.edit')
                                        <a href="{{ route('cedulainicial.edit',$auditoria) }}" class="btn btn-primary">
                                            <i class="align-middle fa fa-file-circle-plus" aria-hidden="true"></i> Ingresar
                                        </a>
                                    @endcan                                    
                                </td>
                                <td>
                                    <a href="{{ route('cedulainicialprimera.edit',$auditoria) }}" target="_blank" class="btn btn-primary" >
                                        <i class="align-middle fa fa-file-circle-plus" aria-hidden="true"></i> Ingresar
                                    </a>
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
                </div> --}}
                {{-- <div class="pagination">
                    {{
                    $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5')
                    }}
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection