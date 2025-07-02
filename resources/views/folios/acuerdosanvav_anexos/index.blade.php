@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('anexosanvav.index',$auditoria) }}
@endsection

@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('acuerdosanvav.show', $folio) }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Anexos Acuerdos de Valoración y No Valoración 
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
                        @can('anexosanvav.create')
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('anexosanvav.create') }}"  class="btn btn-primary float-end">
                                        <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar
                                    </a>
                                </div>
                            </div>
                        @endcan
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Consecutivo</th>
                                <th>Archivo</th>
                                <th>Nombre Archivo</th>
                                <th>Nombre Firmante</th>
                                <th>Cargo Firmante</th>
                                <th>Administración Firmante</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anexosacuerdoanvav as $anexoanvav)
                                <tr>
                                    <td class="text-center">
                                        {{$anexoanvav->consecutivo}}
                                    </td>
                                    <td class="text-center">
                                        
                                        @if (!empty($anexoanvav->archivo))
                                            <a href="{{ asset($anexoanvav->archivo) }}"
                                                target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($anexoanvav->archivo)); ?>
                                            </a><br>
                                            <small>{{ fecha($anexoanvav->fecha_archivo) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{$anexoanvav->nombre_archivo}}
                                    </td>
                                    <td class="text-center">
                                        {{$anexoanvav->nombre_firmante}}
                                    </td>

                                    <td class="text-center">
                                        {{$anexoanvav->cargo_firmante}}
                                    </td>

                                    <td class="text-center">
                                        {{$anexoanvav->administracion_firmante}}
                                    </td>
                                    <td class="text-center">
                                        editar
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan=5>
                                        No se han registrado datos en este apartado
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
