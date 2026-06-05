<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
            body {
                font-family: Calibri, Arial, sans-serif;
                font-size: 11pt;
            }

            /* Título del reporte */
            .title-row td {
                font-size: 14pt;
                font-weight: bold;
                color: #022626;
                background-color: #e8f0e8;
                padding: 6px 10px;
            }

            /* Fila de metadatos */
            .meta-row td {
                font-size: 9pt;
                color: #555;
                padding: 2px 10px;
            }

            /* Sección de KPIs */
            .section-header td {
                font-size: 11pt;
                font-weight: bold;
                color: #fff;
                background-color: #022626;
                padding: 5px 10px;
            }

            .kpi-label {
                font-weight: bold;
                color: #444;
                background-color: #f0ebe5;
            }

            .kpi-value {
                font-weight: bold;
                font-size: 13pt;
            }

            .kv-total {
                color: #022626;
            }

            .kv-leidas {
                color: #3c5c14;
            }

            .kv-pendientes {
                color: #960048;
            }

            .kv-tasa {
                color: #90144a;
            }

            .kv-individual {
                color: #90144a;
            }

            .kv-equipo {
                color: #022626;
            }

            /* Tabla de detalle */
            .detail-header td {
                font-weight: bold;
                color: #fff;
                background-color: #022626;
                padding: 5px 8px;
                border: 1px solid #011515;
            }

            .detail-row td {
                padding: 4px 8px;
                border: 1px solid #ddd;
                vertical-align: top;
            }

            .detail-row-alt td {
                background-color: #f9f6f3;
            }

            /* Espaciador */
            .spacer td {
                height: 12px;
            }
        </style>
    </head>
    <body>

        <table>

            {{-- ── Título ── --}}
            <tr class="title-row">
                <td colspan="7">Reporte de Notificaciones Enviadas</td>
            </tr>
            <tr class="meta-row">
                <td colspan="7">
                    Generado por: {{ Auth::user()->name }}
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    Fecha: {{ now()->format('d/m/Y H:i') }}
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    Período: {{ $fechaInicio->format('d/m/Y') }} — {{ $fechaFin->format('d/m/Y') }}
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    Tipo:
                    {{ request('tipo_destinatario') === 'individual' ? 'Individual' : (request('tipo_destinatario') === 'equipo' ? 'Equipo' : 'Todos') }}
                </td>
            </tr>
            <tr class="spacer">
                <td colspan="7"></td>
            </tr>

            {{-- ── KPIs ── --}}
            <tr class="section-header">
                <td colspan="7">Resumen</td>
            </tr>
            <tr>
                <td class="kpi-label">Total de Notificaciones</td>
                <td class="kpi-value kv-total">{{ $totalNotificaciones }}</td>
                <td class="kpi-label">Leídas</td>
                <td class="kpi-value kv-leidas">{{ $notificacionesLeidas }}</td>
                <td class="kpi-label">Pendientes</td>
                <td class="kpi-value kv-pendientes">{{ $notificacionesNoLeidas }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="kpi-label">Tasa de Lectura</td>
                <td class="kpi-value kv-tasa">{{ $porcentajeLeidas }}%</td>
                <td class="kpi-label">Individuales</td>
                <td class="kpi-value kv-individual">{{ $notificacionesIndividuales }}</td>
                <td class="kpi-label">Equipo</td>
                <td class="kpi-value kv-equipo">{{ $notificacionesEquipo }}</td>
                <td></td>
            </tr>
            <tr class="spacer">
                <td colspan="7"></td>
            </tr>

            {{-- ── Detalle de notificaciones ── --}}
            <tr class="section-header">
                <td colspan="7">Detalle de Notificaciones</td>
            </tr>
            <tr class="detail-header">
                <td>#</td>
                <td>Título</td>
                <td>Destinatario</td>
                <td>Tipo</td>
                <td>Estatus</td>
                <td>Fecha Envío</td>
                <td>Fecha Lectura</td>
            </tr>

            @php $i = 1; @endphp
            @foreach($notificaciones->sortByDesc('created_at') as $notificacion)
                <tr class="detail-row {{ $i % 2 === 0 ? 'detail-row-alt' : '' }}">
                    <td>{{ $i++ }}</td>
                    <td>
                        {{-- Limpiamos las etiquetas HTML del mensaje --}}
                        {{ strip_tags(Str::limit($notificacion->titulo, 100)) }}
                    </td>
                    <td>
                        @if($notificacion->destinatario_id)
                            {{ $notificacion->usuarioDestinatario?->name ?? 'Sin nombre' }}
                        @elseif($notificacion->equipo_id)
                            {{ $notificacion->destinatario ?? 'General' }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($notificacion->destinatario_id && !$notificacion->equipo_id)
                            Individual
                        @elseif($notificacion->equipo_id)
                            Equipo
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        {{ $notificacion->estatus !== 'Pendiente' ? 'Leída' : 'Pendiente' }}
                    </td>
                    <td>{{ $notificacion->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($notificacion->estatus !== 'Pendiente')
                            {{ $notificacion->updated_at->format('d/m/Y H:i') }}
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @endforeach

            <tr class="spacer">
                <td colspan="7"></td>
            </tr>
            <tr>
                <td colspan="7" style="font-size:8pt; color:#aaa;">
                    Sistema de Auditoría — Generado el {{ now()->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </body>
</html>
