@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('turnoarchivo.index', $auditoria) }}
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
                        Turno acuse envío archivo
                    </h1>
                </div>
                <div class="card-body">
                    @include('layouts.contextos._auditoria')
                    @include('flash::message')
                    <div class="row">
                        <div class="col-md-12">
                            {{-- @if ($auditoria->accionesrecomendacionesautorizadas == $auditoria->totalsolventadorecomendacion && $auditoria->accionespoautorizadas == $auditoria->totalsolventadopliegos && $auditoria->accionessolaclautorizadas == $auditoria->totalsolventadosolacl) --}}
                            @if (empty($turnoarchivo))
                                @can('turnoarchivo.create')
                                    <a class="btn btn-primary float-end" href="{{ route('turnoarchivo.create') }}">
                                        <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Acuse envío
                                        archivo
                                    </a>
                                @endcan
                            @endif

                            {{-- @endif --}}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            @if (!empty($auditoria->turnoarchivo->no_aplica) && $auditoria->turnoarchivo->no_aplica == 'X')

                                <h4 class="text-primary">No aplica </h4><br>
                            @else
                                <thead>
                                    <tr>
                                        <th rowspan=1 colspan=2 style="width:20px" class="text-center"> Expediente Técnico
                                            de la Auditoría</th>
                                        <th rowspan=1 colspan=2 style="width:20px" class="text-center"> Expediente de
                                            Seguimiento</th>
                                        <th rowspan=1 colspan=4 style="width:20px"></th>
                                    </tr>
                                    <tr>
                                        {{-- <th>Número del oficio</th> --}}
                                        <th>Número de legajos</th>
                                        <th>Número de fojas</th>
                                        <th>Número de legajos</th>
                                        <th>Número de fojas</th>
                                        <th>Relación de expedientes al archivo</th>
                                        <th>Fecha de entrega</th>
                                        <th>Fase/Acción</th>
                                        @can('turnoarchivoenvio.edit')
                                            <th> Envío </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($turnoarchivo))
                                        <tr>
                                            <td class="text-center">
                                                {{ $turnoarchivo->legajos_tecnico_archivo }}
                                            </td>
                                            <td class="text-center">
                                                {{ $turnoarchivo->fojas_tecnico_archivo }}
                                            </td>
                                            <td class="text-center">
                                                {{ $turnoarchivo->legajos_seg_archivo }}
                                            </td>
                                            <td class="text-center">
                                                {{ $turnoarchivo->fojas_seg_archivo }}
                                            </td>
                                            <td class="text-center">
                                                @btnFile($turnoarchivo->turno_archivo)<br>
                                                <small>{{ fecha($turnoarchivo->fecha_notificacion_archivo) }} <small>
                                            </td>
                                            <td class="text-center">
                                                {{ fecha($turnoarchivo->fecha_turno_archivo) }}
                                            </td>
                                            <td class="text-center">
                                                @if (empty($auditoria->turnoarchivo->fase_autorizacion) || $auditoria->turnoarchivo->fase_autorizacion == 'Rechazado')
                                                    <span
                                                        class="badge badge-light-danger">{{ $auditoria->turnoarchivo->fase_autorizacion }}
                                                    </span>
                                                    @can('turnoarchivo.edit')
                                                        {{-- <a href="{{ route('turnoarchivo.edit',$auditoria->turnoarchivo) }}" class="btn btn-primary">
                                                <span class="fas fa-edit" aria-hidden="true"></span>&nbsp; Editar
                                            </a> --}}
                                                        <a href="{{ route('turnoui.edit', $auditoria->turnoui) }}"
                                                            class="btn btn-color-primary btn-active-color-warning ">
                                                            <i class="fa-solid fa-pen-to-square" style="font-size: 16px;"
                                                                aria-hidden="true"></i>&nbsp; Editar
                                                        </a>
                                                    @endcan
                                                @endif
                                                @if ($auditoria->turnoarchivo->fase_autorizacion == 'En revisión01')
                                                    @can('turnoarchivorevision01.edit')
                                                        <a href="{{ route('turnoarchivorevision01.edit', $auditoria->turnoarchivo) }}"
                                                            class="btn btn-primary">
                                                            <li class="fa fa-gavel"></li>
                                                            Revisar
                                                        </a>
                                                    @else
                                                        <span class="badge badge-light-warning">En revisión</span>
                                                    @endcan
                                                @endif
                                                @if ($auditoria->turnoarchivo->fase_autorizacion == 'En revisión')
                                                    @can('turnoarchivorevision.edit')
                                                        <a href="{{ route('turnoarchivorevision.edit', $auditoria->turnoarchivo) }}"
                                                            class="btn btn-primary">
                                                            <li class="fa fa-gavel"></li>
                                                            Revisar
                                                        </a>
                                                    @else
                                                        <span
                                                            class="badge badge-light-warning">{{ $auditoria->turnoarchivo->fase_autorizacion }}
                                                        </span>
                                                    @endcan
                                                @endif
                                                @if ($auditoria->turnoarchivo->fase_autorizacion == 'En validación')
                                                    @can('turnoarchivovalidacion.edit')
                                                        <a href="{{ route('turnoarchivovalidacion.edit', $auditoria->turnoarchivo) }}"
                                                            class="btn btn-primary">
                                                            <li class="fa fa-gavel"></li>
                                                            Validar
                                                        </a>
                                                    @else
                                                        <span
                                                            class="badge badge-light-warning">{{ $auditoria->turnoarchivo->fase_autorizacion }}
                                                        </span>
                                                    @endcan
                                                @endif
                                                @if ($auditoria->turnoarchivo->fase_autorizacion == 'En autorización')
                                                    @can('turnoarchivoautorizacion.edit')
                                                        <a href="{{ route('turnoarchivoautorizacion.edit', $auditoria->turnoarchivo) }}"
                                                            class="btn btn-primary">
                                                            <li class="fa fa-gavel"></li>
                                                            Autorizar
                                                        </a>
                                                    @else
                                                        <span
                                                            class="badge badge-light-warning">{{ $auditoria->turnoarchivo->fase_autorizacion }}
                                                        </span>
                                                    @endcan
                                                @endif
                                                @if ($auditoria->turnoarchivo->fase_autorizacion == 'Autorizado')
                                                    <span
                                                        class="badge badge-light-success">{{ $auditoria->turnoarchivo->fase_autorizacion }}
                                                    </span>
                                                @endif

                                            </td>
                                            @can('turnoarchivoenvio.edit')
                                                <td class="text-center">
                                                    @if (empty($auditoria->turnoarchivo->fase_autorizacion) || $auditoria->turnoarchivo->fase_autorizacion == 'Rechazado')
                                                        <a href="{{ route('turnoarchivoenvio.edit', $auditoria->turnoarchivo) }}"
                                                            class="btn btn-color-primary btn-active-color-info">
                                                            <i class="bi bi-send-check-fill" style="font-size: 16px;"
                                                                aria-hidden="true"></i>&nbsp; Enviar
                                                        </a>
                                                    @endif
                                                </td>
                                            @endcan
                                        </tr>
                                        @if (!empty($auditoria->turnoarchivo))
                                            {!! movimientosDesglose($auditoria->turnoarchivo->id, 10, $auditoria->turnoarchivo->movimientos) !!}
                                        @endif
                                    @else
                                        <td class="text-center" colspan="5">
                                            No se encuentran registros en este apartado.
                                        </td>
                                        </tr>
                                    @endif
                                </tbody>
                            @endif
                        </table>
                    </div>
                    @can('turnoarchivo.contestaciones')
                        @if (!empty($turnoarchivo->fase_autorizacion) && $turnoarchivo->fase_autorizacion == 'Autorizado')
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('turnoarchivo.contestaciones', $turnoarchivo) }}"
                                        class="btn btn-primary float-end">
                                        Contestaciones
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection
