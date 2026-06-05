@extends('layouts.app')

@section('breadcrums')
    {{Breadcrumbs::render('reportenotificaciones.show', $notificacion) }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title w-100">
                    <div class="row w-100">
                        <div class="col-md-11">
                            <a href="{{ route('reportenotificaciones.index') }}">
                                <i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i>
                            </a>&nbsp;
                            Detalle de la Notificación <i class="fas fa-bell text-primary fa-1x"></i>

                        </div>
                    </div>
                </h1>
            </div>
            <div class="card-body">
                {{-- Encabezado --}}
                {{-- <div class="col-md-12">
                    <div class="card h-30 shadow-sm">
                        <div class="card-body audit-card fancy-border">
                            <div class="text-primary small font-weight-bold text-uppercase mb-1">
                                <h4 style="color:#90144a;">

                                </h4>

                            </div>
                            <div class="h3 mb-0" style="color:#022626;"></div>
                        </div>
                    </div>
                </div> --}}

                <div class="row">
                    {{-- Panel principal --}}
                    <div class="col-md-8">
                        <div class="card fancy-border mb-4">
                            <div class="card-header" style="border-top: 4px solid #90144a;">
                                <h1 class="card-title w-100">
                                    <div class="row w-100">
                                        <div class="col-md-11">
                                            <i class="fas fa-info-circle text-primary fa-1x"></i> Información General <br>
                                            <small class="text-muted">Información de la notificación enviada</small>
                                        </div>
                                    </div>
                                </h1>
                            </div>
                            <div class="card-body" style="background-color:#faf9f8; border-radius: 50%;">

                                {{-- Título y estatus --}}
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-8">
                                        <h4 class="font-weight-bold" style="color:#022626;">TÍTULO</h4><label>{{ $notificacion->titulo }}</label>

                                    </div>
                                    {{-- <div class="col-md-4 text-right">
                                        @if($notificacion->estatus != 'Pendiente')
                                            <span class="badge badge-verdeclaro px-1">
                                                <i class="fas fa-check-circle text-white"></i> Leída
                                            </span>
                                        @else
                                            <span class="badge badge-rojo">
                                                <i class="fas fa-clock text-white px-1"></i> Pendiente
                                            </span>
                                        @endif
                                    </div> --}}
                                </div>
                                <hr>
                                {{-- Mensaje --}}
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <h4 class="font-weight-bold" style="color:#022626;">MENSAJE</h4>
                                        <div class="p-3 rounded" style="background-color:#ece6e0; border-left: 4px solid #90144a;">
                                            {{-- {!! nl2br(e($notificacion->mensaje)) !!} --}}
                                            @php $partes = explode('<br>', $notificacion->mensaje); @endphp
                                                    <strong>{{ $partes[1] ?? $partes[0] }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                {{-- Destinatario --}}
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <h4 class="font-weight-bold" style="color:#022626;">DESTINATARIO</h4>
                                        @if($notificacion->destinatario_id)
                                            {{ $notificacion->usuarioDestinatario?->name ?? 'Sin nombre' }}
                                        @elseif($notificacion->equipo_id)
                                            {{ $notificacion->destinatario ?? 'General' }}
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <h4 class="font-weight-bold" style="color:#022626;">Tipo</h4>
                                        @if($notificacion->destinatario_id)
                                            <span class="badge badge-primary mr-1">Individual</span>
                                        @elseif($notificacion->equipo_id)
                                            <span class="badge badge-amarillo text-dark mr-1">Equipo</span>
                                        @endif

                                    </div>
                                    <div class="col-md-4 text-center">
                                        <h4 class="font-weight-bold" style="color:#022626;">CUENTA PÚBLICA</h4>
                                        <p>{{ $notificacion->cp ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <hr>
                                {{-- Fechas --}}
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <h4 class="font-weight-bold" style="color:#022626;">FECHA ENVÍO</h4>
                                        <p>{{ $notificacion->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h4 class="font-weight-bold" style="color:#022626;">FECHA LECTURA</h4>
                                        <p>
                                            @if($notificacion->estatus !== 'Pendiente')
                                                {{ $notificacion->updated_at->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-muted">No leída aún</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                {{-- Auditoría relacionada --}}
                                {{-- @if($notificacion->auditoria || $notificacion->llave)
                                    <hr>
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <label class="font-weight-bold text-muted small">AUDITORÍA RELACIONADA</label>
                                            <p style="color:#022626;">
                                                <i class="fas fa-search"></i>
                                                {{ $notificacion->auditoria?->numero_radicacion ?? '—' }}
                                                @if($notificacion->llave)
                                                    <br><small class="text-muted">Llave: {{ $notificacion->llave }}</small>
                                                @endif
                                            </p>
                                        </div>
                                    </div> --}}

                                    {{-- Botón para ir a la auditoría --}}
                                    {{-- @if($notificacion->url && $notificacion->llave)
                                        <a href="{{ route('reportenotificaciones.goto', $notificacion->id) }}"
                                        class="btn btn-sm text-white"
                                        style="background-color:#022626;">
                                            <i class="fas fa-external-link-alt"></i>
                                            Ir a la Auditoría
                                        </a>
                                    @endif
                                @endif --}}

                            </div>
                        </div>
                    </div>
                    {{-- Panel lateral --}}
                    <div class="col-md-4">
                        {{-- Metadatos --}}
                        <div class="card fancy-border mb-4" style="border-top: 4px solid #022626;">
                            <div class="card-header" >
                                <h1 class="card-title w-100">
                                    <div class="row w-100">
                                        <div class="col-md-11">
                                            <i class="fas fa-user-edit text-primary fa-1x"></i> Registro
                                        </div>
                                    </div>
                                </h1>
                            </div>
                            <div class="card-body" style="background-color:#faf9f8; font-size:0.9rem; border-radius: 50%;">
                                <div class="mb-2">
                                    <h4 class="font-weight-bold" style="color:#022626;">CREADO POR</h4>
                                    <p class="mb-1 font-weight-bold">
                                        {{ $notificacion->usuarioCreacion?->name ?? 'N/A' }}
                                    </p>
                                </div>
                                @if($notificacion->usuario_actualizacion_id)
                                    <div class="mb-2">
                                        <h4 class="font-weight-bold" style="color:#022626;">ACTUALIZADO POR</h4>
                                        <p class="mb-1 font-weight-bold">
                                            {{ $notificacion->usuarioActualizacion?->name ?? 'N/A' }}
                                        </p>
                                        <span class="text-muted small">
                                            {{ $notificacion->updated_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                @endif
                                {{-- <hr>
                                <div>
                                    <span class="text-muted small">LLAVE INTERNA</span>
                                    <p class="mb-0" style="word-break:break-all; font-size:0.8rem;">
                                        {{ $notificacion->llave ?? 'N/A' }}
                                    </p>
                                </div> --}}
                            </div>
                        </div>

                        {{-- Estado visual --}}
                        <div class="card fancy-border mb-4"
                            style="border-top: 4px solid {{ $notificacion->estatus != 'Pendiente' ? '#b5c99a' : '#960048' }};">
                            <div class="card-body text-center py-4">
                                @if($notificacion->estatus != 'Pendiente')
                                    <i class="fas fa-check-circle fa-3x mb-2" style="color:#b5c99a;"></i>
                                    <p class="font-weight-bold mb-0" style="color:#022626;">Notificación Leída</p>
                                    <small class="text-muted">El destinatario ya visualizó esta notificación</small>
                                @else
                                    <i class="fas fa-hourglass-half fa-3x mb-2" style="color:#960048;"></i>
                                    <p class="font-weight-bold mb-0" style="color:#960048;">Pendiente de Lectura</p>
                                    <small class="text-muted">El destinatario aún no la visualiza</small>
                                @endif
                            </div>
                        </div>

                        {{-- Acciones --}}
                        {{-- <div class="card shadow">
                            <div class="card-body">
                                <a href="{{ route('reportenotificaciones.index') }}"
                                class="btn btn-block text-white mb-2"
                                style="background-color:#90144a;">
                                    <i class="fas fa-arrow-left"></i> Volver al Reporte
                                </a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
