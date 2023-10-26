@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('prasturnoacuses.show',$auditoria,$pras) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                <a href="{{ route('prasturno.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Acuses
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            @include('layouts.contextos._accion')
            @include('layouts.contextos._pras')
            <h4 class="text-primary">Acuses</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Comprobante de recepción depto. de notificaciones</th>
                                <th>Acuse del turno del PRAS</th>
                                <th>Fecha próxima de seguimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="text-center">
                                        @if (!empty($pras->oficio_comprobante))
                                            <a href="{{ asset($pras->oficio_comprobante) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($pras->oficio_comprobante)) ?>
                                            </a><br>
                                            <small>{{  fecha($pras->fecha_recepcion) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($pras->oficio_acuse))
                                            <a href="{{ asset($pras->oficio_acuse) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($pras->oficio_acuse)) ?>
                                            </a><br>
                                            <small>{{  fecha($pras->fecha_acuse) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ fecha($pras->fecha_proxima_seguimiento) }}
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
@endsection
