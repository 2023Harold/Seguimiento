@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('acuerdosanvav.show',$auditoria) }}
@endsection

@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('folioscrr.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Acuerdos de Valoración y No Valoración
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
                @include('layouts.contextos._folio')
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Número expediente</th>
                                <th>Tipo de documento</th>
                                @if(!empty($acuerdoanvav->numero_oficio_ent))
                                    <th>Numero de oficio que presenta la Entidad</th>
                                @endif
                                @if(!empty($acuerdoanvav->fecha_oficio_ent))
                                    <th>Fecha oficio que presenta la Entidad</th>
                                @endif
                                <th>Nombre de notificación del informe de auditoría</th>
                                <th>Cargo de notificación del informe de auditoría</th>
                                <th>Administración de notificación del informe de auditoría</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($acuerdoanvav))
                                <tr>
                                    <td class="text-center">
                                        {{$acuerdoanvav->numero_expediente}}
                                    </td>
                                    <td class="text-center">
                                    @if(!empty($acuerdoanvav->numero_oficio_ent))
                                        <td class="text-center">
                                            {{$acuerdoanvav->numero_oficio_ent}}
                                        </td>
                                    @endif
                                    @if(!empty($acuerdoanvav->fecha_oficio_ent))
                                        <td class="text-center">
                                            {{fecha($acuerdoanvav->fecha_oficio_ent)}}
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        {{$acuerdoanvav->tipo_doc}}
                                    </td>
                                    <td class="text-center">
                                        {{$acuerdoanvav->nombre_informe_au}}
                                    </td>
                                    <td class="text-center">
                                        {{$acuerdoanvav->cargo_informe_au}}
                                    </td>
                                    <td class="text-center">
                                        {{$acuerdoanvav->administracion_informe_au}}
                                    </td>
                                    <td class="text-center">

                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center" colspan=5>
                                        No se han registrado datos en este apartado
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="float-end">
                        <a href="{{ route('anexosanvav.index') }}" class="btn btn-primary">
                            <span class="fa fa-file-circle-plus" aria-hidden="true"></span>&nbsp; Anexos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
