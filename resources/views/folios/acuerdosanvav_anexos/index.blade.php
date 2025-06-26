@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('acuerdosanvav.index',$auditoria) }}
@endsection

@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('folioscrr.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Acuerdos de Valoración y No Valoración Anexos 
                </h1>
                <div class="float-end">    
                   {{-- <a href="{{ route('informelegalidad.exportar') }}" class="btn btn-light-primary"><span class="fa fa-file-word">&nbsp;&nbsp;&nbsp;</span>INFORME </a>     --}}
                    {{--@can('informeprimeraetapa.exportar')    --}}        
                        @if($auditoria->acto_fiscalizacion=='Legalidad')
                             <a href="{{route('acuerdosanvav.export')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;AnV EA</a> 
                             <a href="{{route('acuerdosanvav.export')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;AnV PAR</a> 
                        @endif
                            
                        @if($auditoria->acto_fiscalizacion=='Desempeño')
                            <a href="{{route('acuerdosanvav.export')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;AV</a>          
                        @endif

                        @if($auditoria->acto_fiscalizacion=='Cumplimiento Financiero')
                            <a href="{{route('acuerdosanvavcfif.export')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;AnV</a> 
                        @endif
                        @if($auditoria->acto_fiscalizacion=='Inversión Física')
                            <a href="{{route('acuerdosanvavcfif.export')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;AnV</a> 
                        @endif
                    {{--@endcan--}}
                </div>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                        @can('folioscrr.create')
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('folioscrr.create') }}"  class="btn btn-primary float-end">
                                        <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar
                                    </a>
                                </div>
                            </div>
                        @endcan
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Oficio / Escrito</th>
                                <th>Numero de oficio que presenta la Entidad</th>
                                <th>Fecha Oficio</th>
                                <th>Nombre Firmante</th>
                                <th>Cargo Firmante</th>
                                <th>Acuses</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    1
                                </td>
                                <td class="text-center">
                                    2
                                </td>
                                <td class="text-center">
                                    3
                                </td>
                                <td class="text-center">
                                    4
                                </td>

                                <td class="text-center">
                                    5
                                </td>

                                <td class="text-center">
                                    6
                                </td>
                                <td class="text-center">
                                    7
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan=5>
                                    No se han registrado datos en este apartado
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
