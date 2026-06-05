<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Reporte')</title>

    <style>
            /* ── Reset ── */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11pt;
                color: #1a1a1a;
                background: #fff;
            }

            /* ── Cabecera del reporte ── */
            .report-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 3px solid #022626;
                padding-bottom: 8px;
                margin-bottom: 12px;
            }

            .report-header h1 {
                font-size: 16pt;
                color: #022626;
            }

            .report-header .meta {
                text-align: right;
                font-size: 9pt;
                color: #555;
            }

            .report-title {
                font-size: 18pt;
                font-weight: bold;
                color: #022626;
            }

            /* ── KPIs ── */
            .kpis {
                display: flex;
                gap: 10px;
                margin-bottom: 18px;
            }

            .kpi-box {
                display: inline-block;
                width: 24%;
                margin-right: 1%;
                margin-bottom: 10px;
                border: 1px solid #E5E7EB;
                border-radius: 6px;
                background: #fff;
                padding: 10px;
                text-align: center;
            }

            /* .kpi-box {
                flex: 1;
                border: 1px solid #ddd;
                border-radius: 6px;
                padding: 10px 12px;
                text-align: center;
            } */
            .kpi-label {
                font-size: 8pt;
                text-transform: uppercase;
                color: #777;
                margin-bottom: 4px;
            }

            .kpi-value {
                font-size: 20pt;
                font-weight: bold;
            }

            /* ── Resumen de filtros ── */
            .filtros-resumen {
                background: #f5f5f5;
                border-left: 4px solid #90144a;
                padding: 8px 12px;
                margin-bottom: 18px;
                font-size: 9pt;
                color: #444;
            }

            .filtros-resumen strong {
                color: #022626;
            }

            /* ── Tabla ── */
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 9pt;
                margin-bottom: 18px;
                background: #fff;
                border: 2px solid #BB945C;
            }

            thead tr {
                background-color: #022626;
                color: #fff;
            }

            thead th {
                background: #022626;
                color: #fff;
                padding: 7px 8px;
                text-align: left;
            }

            tbody td {
                padding: 5px;
                border: 1px solid #ddd;
            }

            tbody tr:nth-child(even) {
                background: #FAFAFB;
            }

            tbody tr:nth-child(even) {
                background-color: #f9f6f3;
            }

            tbody td {
                padding: 6px 8px;
                border-bottom: 1px solid #e8e0d8;
                vertical-align: middle;
            }

            /* Badges inline */
            .badge {
                display: inline-block;
                padding: 2px 7px;
                border-radius: 3px;
                font-size: 8pt;
                font-weight: bold;
            }

            .badge-individual {
                background: #90144a;
                color: #fff;
            }

            .badge-equipo {
                background: #f0c040;
                color: #333;
            }

            .badge-leida {
                background: #3c5c14;
                color: #fff;
            }

            .badge-pendiente {
                background: #960048;
                color: #fff;
            }
             .badge-verde {
                    color: #FFF;
                    background-color: #2D5346;
                }
            .badge-verdeclaro {
                    color: #FFF;
                    background-color: #3c5c14
                    /* background-color: #9D9655; */
                /*  background-color: #c6d4b0;
                    background-color: #B5C99A */
                }

            .badge-gris {
                color: #FFF;
                background-color: #7e7470;
            }

            .badge-cafe {
                color: #FFF;
                background-color: #48180b;
            }
            .badge-amarillo {
                color: #FFF;
                background-color: #f4d69a;
            }
            .badge-rojo {
                color: #FFF;
                background-color: #c92525;
            }
            .badge-morado {
                color: #FFF;
                background-color: #A07885;
            }

            /* ── Pie de página ── */
            .report-footer {
                border-top: 1px solid #ccc;
                padding-top: 8px;
                font-size: 8pt;
                color: #777;
                text-align: center;
            }

            /* ── Botón imprimir (solo en pantalla) ── */
            .print-btn {
                display: inline-block;
                margin-bottom: 16px;
                padding: 8px 20px;
                background: #022626;
                color: #fff;
                border: none;
                border-radius: 4px;
                font-size: 11pt;
                cursor: pointer;
            }

            .print-btn:hover {
                background: #033333;
            }

            @media print {
                .print-btn {
                    display: none;
                }

                body {
                    font-size: 10pt;
                }
            }

            /* Salto de página antes de la tabla si es larga */
            @media print {
                table {
                    page-break-inside: auto;
                }

                tr {
                    page-break-inside: avoid;
                    page-break-after: auto;
                }

                thead {
                    display: table-header-group;
                }
            }

            .page-wrap {
                padding: 5mm;
                background: #FAFAFB;
            }

            .card {
                border: 1px solid #E5E7EB;
                border-radius: 8px;
                padding: 10px;
                background: #ffffff;
                margin-bottom: 12px;
            }

            .card-ribbon {
                border-left: 5px solid #90144a;
                padding-left: 8px;
                margin-bottom: 6px;
                font-weight: bold;
            }

            .fancy-border {
                border: 2px solid #BB945C;
            }
            .pdf-navbar {
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 2px solid #022626;
                padding-bottom: 5px;
            }

            .pdf-navbar-left span {
                font-size: 14pt;
                font-weight: bold;
                color: #022626;
            }

            .pdf-navbar-right {
                display: flex;
                align-items: center;
                /* gap: 10px; */
            }

            .pdf-navbar-right img {
                height: 50px;   /* clave: controlas altura */
                width: auto;    /* evita deformación */
                object-fit: contain;
                margin-right: 10px;
            }
            @media print {
                .pdf-navbar {
                    margin-bottom: 10px;
                }

                .pdf-navbar-right img {
                    height: 45px;
                }
            }
            .badge.ok {
                background:#3c5c14;
                color:#fff;
            }

            .badge.warn {
                background:#960048;
                color:#fff;
            }


        </style>
</head>

    <body>

    <div class="pdf-navbar">
        <div class="pdf-navbar-left">
            <span>Seguimiento a las observaciones de fiscalización</span>
        </div>
        <div class="pdf-navbar-right">
            <img src="{{ asset('assets/img/legislatura.png') }}" alt="Logo 1">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo 2">
        </div>
    </div>
    @yield('content')

    </body>
</html>
