@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('prasturno.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('prasacciones.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;Turnar PRAS a OIC o equivalente
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message') 
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._accion')
                <h3 class="card-title text-primary">Turno</h3> 
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre del titular del OIC</th>
                                <th>Oficio de turno</th>
                                <th>Número del oficio</th>
                                <th>Fecha proxima de seguimiento</th>
                                <th>Fase / Constancia</th>
                                <th>Acuses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prass as $pras)
                            <tr>
                                <td>
                                    {{ $pras->nombre_titular_oic }}
                                </td>
                                <td class="text-center">
                                    @if (!empty($pras->oficio_remision))
                                        <a href="{{ asset($pras->oficio_remision) }}" target="_blank">
                                            <?php echo htmlspecialchars_decode(iconoArchivo($pras->oficio_remision)) ?>
                                        </a> <br>
                                        <small>{{ fecha($pras->fecha_acuse_oficio) }}</small>                                   
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{$pras->numero_oficio }}
                                </td>
                                <td class="text-center">
                                    {{ fecha($pras->fecha_proxima_seguimiento) }}
                                </td>
                                <td class="text-center">                                                                           
                                    @if (empty($pras->fase_autorizacion)||$pras->fase_autorizacion=='Rechazado')   
                                        <span class="badge badge-light-danger">{{ $pras->fase_autorizacion }} </span><br>
                                            @can('prasturno.edit')
                                                <a href="{{ route('prasturno.edit',$pras) }}" class="btn btn-primary">
                                                    <span class="fas fa-edit text-primar" aria-hidden="true"></span>&nbsp; Editar
                                                </a>  
                                            @endcan
                                    @endif  
                                    @if ($pras->fase_autorizacion == 'En revisión')                                                
                                        @can('prasturnorevision.edit')
                                            <a href="{{ route('prasturnorevision.edit',$pras) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $pras->fase_autorizacion }} </span>
                                        @endcan                                               
                                    @endif
                                    @if ($pras->fase_autorizacion == 'En validación')                                                
                                        @can('prasturnovalidacion.edit')
                                            <a href="{{ route('prasturnovalidacion.edit',$pras) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Validar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $pras->fase_autorizacion }} </span>
                                        @endcan                                               
                                    @endif
                                    @if ($pras->fase_autorizacion == 'En autorización')                                                
                                        @can('prasturnoautorizacion.edit')
                                            <a href="{{ route('prasturnoautorizacion.edit',$pras) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Autorizar
                                            </a> 
                                        @else
                                            <span class="badge badge-light-warning">{{ $pras->fase_autorizacion }} </span>                                           
                                        @endcan
                                    @endif                                     
                                    @if ($pras->fase_autorizacion=='Autorizado')
                                    <span class="badge badge-light-success">{{ $pras->fase_autorizacion }} </span> <br>
                                        @btnFile($pras->constancia)
                                        @btnXml($pras, 'constancia')
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($pras->fase_autorizacion=='Autorizado')
                                        @if (empty($pras->oficio_comprobante))
                                            @can('prasturnoacuses.edit')
                                                <a href="{{ route('prasturnoacuses.edit', $pras) }}" class="btn btn-primary">
                                                    <span class="fa fa-file-circle-plus" aria-hidden="true"></span>&nbsp; Adjuntar
                                                </a>
                                            @endcan
                                        @else
                                            @can('prasturnoacuses.show')
                                                <a href="{{ route('prasturnoacuses.show', $pras) }}" class="btn btn-secondary" >
                                                    <img alt="Logo" src="{{asset('assets/img/consultar.png')}}" class="h-30px logo" />
                                                </a>
                                            @endcan
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            {!! movimientosDesglose($pras->id, 6, $pras->movimientos) !!}
                            @empty
                            <td colspan="6" class="text-center">
                                No se encontraron registros en este apartado.
                            </td>
                            @endforelse
                        </tbody>
                    </table>
                </div>                
            </div>
        </div>
    </div>
</div>
@endsection