@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('radicacion.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('auditoriaseguimiento.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Radicación
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>                               
                                <th>Número de expediente</th>
                                <th>Número de oficio de notificación del informe de auditoria</th>
                                <th>Acuerdo de radicación</th>
                                <th>Acuse del oficio de designación</th>                                
                                <th>Fase / Acción / Constancia</th>
                                <th>Acuses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($auditoria->radicacion))
                                <tr>                                                
                                    <td class="text-center">                                       
                                        @if (!empty($auditoria->radicacion))
                                            {{ $auditoria->radicacion->numero_expediente }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($auditoria->radicacion))
                                            {{ $auditoria->radicacion->numero_acuerdo }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($auditoria->radicacion))
                                        <a href="{{ asset($auditoria->radicacion->oficio_acuerdo) }}" target="_blank">
                                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->radicacion->oficio_acuerdo)) ?>
                                        </a> <br>
                                        <small>{{ fecha($auditoria->radicacion->fecha_oficio_acuerdo) }}</small>
                                        @endif
                                    </td>
                                    {{-- <td class="text-center">
                                        @if (!empty($auditoria->radicacion))
                                            <a href="{{ asset($auditoria->radicacion->oficio_designacion) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->radicacion->oficio_designacion)) ?>
                                            </a> <br>
                                            <small> {{ fecha($auditoria->radicacion->fecha_oficio_acuerdo) }} </small>
                                        @endif
                                    </td>  --}}
                                    <td class="text-center">
                                        @if (!empty($auditoria->comparecencia))
                                        <a href="{{ route('comparecencia.show', $auditoria->comparecencia) }}" class="btn btn-secondary" data-title="Consultar">
                                            <img alt="Logo" src="{{asset('assets/img/consultar.png')}}" class="h-30px logo" />
                                        </a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (empty($auditoria->radicacion))
                                            @can('radicacion.auditoria')
                                                <a href="{{ route('radicacion.auditoria',$auditoria) }}"  class="btn btn-primary">
                                                    <i class="align-middle fa fa-file-circle-plus" aria-hidden="true"></i> Agregar radicacion
                                                </a>
                                            @endcan
                                        @else
                                            @if (empty($auditoria->radicacion->fase_autorizacion)||$auditoria->radicacion->fase_autorizacion=='Rechazado')
                                                <span class="badge badge-light-danger">{{ $auditoria->radicacion->fase_autorizacion }} </span><br>
                                                    @can('radicacion.edit')
                                                        <a href="{{ route('radicacion.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                            <span class="fas fa-edit text-primary" aria-hidden="true"></span>&nbsp; Editar
                                                        </a>
                                                    @endcan
                                            @endif
                                            @if ($auditoria->radicacion->fase_autorizacion == 'En validación')
                                                @can('radicacionvalidacion.edit')
                                                    <a href="{{ route('radicacionvalidacion.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Validar
                                                    </a>
                                                @else
                                                    <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion }} </span>
                                                @endcan
                                            @endif
                                            @if ($auditoria->radicacion->fase_autorizacion == 'En autorización')
                                                @can('radicacionautorizacion.edit')
                                                    <a href="{{ route('radicacionautorizacion.edit',$auditoria->radicacion) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Autorizar
                                                    </a>
                                                @else
                                                    <span class="badge badge-light-warning">{{ $auditoria->radicacion->fase_autorizacion }} </span>
                                                @endcan
                                            @endif
                                            @if ($auditoria->radicacion->fase_autorizacion=='Autorizado')
                                            <span class="badge badge-light-success">{{ $auditoria->radicacion->fase_autorizacion }} </span> <br>
                                                @btnFile($auditoria->radicacion->constancia)
                                                @btnXml($auditoria->radicacion, 'constancia')
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($auditoria->radicacion->fase_autorizacion)&&$auditoria->radicacion->fase_autorizacion=='Autorizado')
                                            @if (empty($auditoria->comparecencia->oficio_recepcion))
                                                @can('comparecenciaacuse.edit')
                                                    <a href="{{ route('comparecenciaacuse.edit', $auditoria->comparecencia) }}" class="btn btn-primary">
                                                        <span class="fa fa-file-circle-plus" aria-hidden="true"></span>&nbsp; Adjuntar
                                                    </a>
                                                @endcan
                                            @else
                                                @can('comparecenciaacuse.show')
                                                    <a href="{{ route('comparecenciaacuse.show', $auditoria->comparecencia) }}" class="btn btn-secondary" >
                                                        <img alt="Logo" src="{{asset('assets/img/consultar.png')}}" class="h-30px logo" />
                                                    </a>
                                                @endcan
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @if (!empty($auditoria->radicacion))
                                    {!! movimientosDesglose($auditoria->radicacion->id, 10, $auditoria->radicacion->movimientos) !!}
                                @endif                                                                                           
                            @else
                                <tr>
                                    <td class="text-center" colspan="10">
                                        <span class='text-center'>No hay registros en éste apartado</span>
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
