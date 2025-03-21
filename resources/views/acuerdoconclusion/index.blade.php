@extends('layouts.app')
@section('breadcrums')
{{Breadcrumbs::render('acuerdoconclusion.index',$auditoria) }}
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
                    Acuerdo de conclusión
                </h1>
                <div class="float-end">
                    @if($auditoria->acto_fiscalizacion=='Legalidad')
                        @if((count($auditoria->accionesrecomendaciones)> 0)&& (count($auditoria->accionespo) > 0))
                        <a href="{{ route('acuerdoconclusionac.exportar') }}?tipo=AC_PAR" class="btn btn-light-primary"><span class="fa fa-file-word">&nbsp;&nbsp;&nbsp;AC. PAR</span></a>
                        <a href="{{ route('acuerdoconclusionac.exportar') }}?tipo=AC_EA" class="btn btn-light-primary"><span class="fa fa-file-word">&nbsp;&nbsp;&nbsp;AC. EA</span></a>
                        @elseif(count($auditoria->accionesrecomendaciones)> 0)
                        <a href="{{ route('acuerdoconclusionac.exportar') }}?tipo=AC_PAR" class="btn btn-light-primary"><span class="fa fa-file-word">&nbsp;&nbsp;&nbsp;AC. PAR</span></a>
                        @elseif(count($auditoria->accionespo) > 0)
                        <a href="{{ route('acuerdoconclusionac.exportar') }}?tipo=AC_EA" class="btn btn-light-primary"><span class="fa fa-file-word">&nbsp;&nbsp;&nbsp;AC. EA</span></a>
                        @endif
                            <a href="{{route('acuerdoconclusionofac.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;OF. AC</a>
                    @else
                        <a href="{{route('acuerdoconclusionac.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;AC</a>
                        <a href="{{route('acuerdoconclusionofac.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;OF. AC</a>
                    @endif

                </div>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')
                <div class="row">
                    <div class="col-md-12">
                        @if(getSession('cp')==2022)
                            @if (empty($auditoria->acuerdoconclusion) && $auditoria->departamento_encargado_id==auth()->user()->unidad_administrativa_id)
                                @can('acuerdoconclusion.create')
                                    <a class="btn btn-primary float-end" href="{{ route('acuerdoconclusion.create') }}">
                                        <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Acuerdo
                                    </a>
                                @endcan
                            @endif
                        @elseif(getSession('cp')==2023)
                            @if (empty($auditoria->acuerdoconclusion) && $auditoria->lidercp_id==auth()->user()->id)
                            @can('acuerdoconclusioncp.create')
                                <a class="btn btn-primary float-end" href="{{ route('acuerdoconclusioncp.create') }}">
                                    <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Acuerdo
                                </a>
                            @endcan
                            @endif
                        @endif
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
								<th>Número de oficio</th>
                                <th>Nombre del titular a quien se dirige</th>
                                <th>Cargo del titular a quien se dirige</th>
                                <th>Domicilio</th>
                                <th>Acuerdo de conclusión</th>
                                <th>Fase / Acción</th>
                            @if (getSession('cp')==2022 && auth()->user()->siglas_rol=='JD')
                                <th>Enviar</th>
                            @elseif(getSession('cp')==2023 && auth()->user()->siglas_rol=='LP')
                                <th>Enviar</th>
                            @endif
                            </tr>
                        </thead>
                        <tbody>

                            @if (!empty($auditoria->acuerdoconclusion))
                            <tr>
								<td>
                                    {{ $auditoria->acuerdoconclusion->numero_oficio }}
                                </td>
                                <td>
                                    {{ $auditoria->acuerdoconclusion->nombre_titular }}
                                </td>
                                <td>
                                    {{ $auditoria->acuerdoconclusion->cargo_titular }}
                                </td>
                                <td>
                                    {{ $auditoria->acuerdoconclusion->domicilio }}
                                </td>

                                <td class="text-center">
                                    <a>
                                    @btnFile($auditoria->acuerdoconclusion->acuerdo_conclusion)
                                    </a><br>
                                    <small>{{ fecha($auditoria->acuerdoconclusion->fecha_acuerdo_conclusion) }}</small>


                                </td>

                            {{-- fase de validación --}}

                                <td class="text-center">
                                    @if (empty($auditoria->acuerdoconclusion->fase_autorizacion)||$auditoria->acuerdoconclusion->fase_autorizacion=='Rechazado')
                                        <span class="badge badge-light-danger">{{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                        @can('acuerdoconclusion.edit')
                                                <a href="{{ route('acuerdoconclusion.edit',$auditoria->acuerdoconclusion) }}" class="text-primary">
                                                <span class="fas fa-edit fa-lg" aria-hidden="true"></span>
                                                </a>
                                            @endcan
                                    @endif
                                    @if(getSession('cp')!=2023)
                                        @if ($auditoria->acuerdoconclusion->fase_autorizacion == 'En validación')
                                            @can('acuerdoconclusionvalidacion.edit')
                                                <a href="{{ route('acuerdoconclusionvalidacion.edit',$auditoria->acuerdoconclusion) }}" class="btn btn-primary">
                                                    <li class="fa fa-gavel"></li>
                                                    Validar
                                                </a>
                                            @else
                                                <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                            @endcan
                                        @elseif($auditoria->acuerdoconclusion->fase_autorizacion == 'En revisión')
                                            @can('acuerdoconclusionrevision.edit')
                                                <a href="{{ route('acuerdoconclusionrevision.edit',$auditoria->acuerdoconclusion) }}" class="btn btn-primary">
                                                    <li class="fa fa-gavel"></li>
                                                    Revisar
                                                </a>
                                            @else
                                                <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                            @endcan
                                        @endif
                                    @else {{-- AQUI EMPIEZA EL 2023--}}
                                         @if($auditoria->acuerdoconclusion->fase_autorizacion == 'En validación' && auth()->user()->siglas_rol=='DS')
                                            @can('acuerdoconclusionvalidacion.edit')
                                                <a href="{{ route('acuerdoconclusionvalidacion.edit',$auditoria->acuerdoconclusion) }}" class="btn btn-primary">
                                                    <li class="fa fa-gavel"></li>
                                                    Validar
                                                </a>
                                            @else
                                                <span class="badge badge-light-warning">{{$auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                            @endcan
                                        @elseif($auditoria->acuerdoconclusion->fase_autorizacion == 'En revisión')
                                            @can('acuerdoconclusionrevision.edit')
                                                <a href="{{ route('acuerdoconclusionrevision.edit',$auditoria->acuerdoconclusion) }}" class="btn btn-primary">
                                                    <li class="fa fa-gavel"></li>
                                                    Revisar
                                                </a>
                                            @else
                                                <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                            @endcan
                                        @endif
                                    @endif
                                    @if ($auditoria->acuerdoconclusion->fase_autorizacion == 'En autorización')
                                        @can('acuerdoconclusionautorizacion.edit')
                                            <a href="{{ route('acuerdoconclusionautorizacion.edit',$auditoria->acuerdoconclusion) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Autorizar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                        @endcan
                                    @endif
                                    @if ($auditoria->acuerdoconclusion->fase_autorizacion=='Autorizado')
                                        <span class="badge badge-light-success"> {{ $auditoria->acuerdoconclusion->fase_autorizacion }} </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                @if (empty($auditoria->acuerdoconclusion->fase_autorizacion)||$auditoria->acuerdoconclusion->fase_autorizacion=='Rechazado')
                                        @if (getSession('cp')==2022 && auth()->user()->siglas_rol=='JD')
                                            @can('acuerdoconclusionenvio.edit')
                                                <a href="{{ route('acuerdoconclusionenvio.edit',$auditoria->acuerdoconclusion) }}" class="btn btn-primary">
                                                Enviar
                                                </a>
                                            @endcan
                                        @else
                                            @can('acuerdoconclusionenviocp.edit')
                                                <a href="{{ route('acuerdoconclusionenviocp.edit',$auditoria->acuerdoconclusion) }}" class="btn btn-primary">
                                                Enviar
                                                </a>
                                            @endcan
                                        @endif
                                @endif
                                </td>
                            </tr>
                           {{-- {{ dd($auditoria->acuerdoconclusion->movimientos); }}} --}}
                            @if (!empty($auditoria->acuerdoconclusion)&&!empty($auditoria->acuerdoconclusion->movimientos))
                                {!! movimientosDesglose($auditoria->acuerdoconclusion->id, 10, $auditoria->acuerdoconclusion->movimientos) !!}
                            @endif
                            @else
                            {{-- termino de fase de validación --}}
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
