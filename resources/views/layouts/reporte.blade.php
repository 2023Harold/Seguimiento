<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        @page {
            margin: 0cm 0cm;
            font-family: Arial;
        }

        body {
            margin: 4cm 2cm 3cm;
        }

        header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            line-height: 30px;
            text-align: right;
            align-items: center;
            align-content: center;
        }

        footer {
            position: fixed;
            bottom: 20px;
            left: 0px;
            right: 0px;
            text-align: center;
            z-index: 3;            
        }

        main {
            position: relative;
            top: 50px;
            left: 0cm;
            right: 0cm;
            margin-bottom: 0.5cm;           
        }

        .table,
        .th,
        .td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <header>
        <img src="{{ public_path('/assets/img/banner.jpg') }}" width="100%" style="position: absolute; height: 160px;">
        <table border="0" width="100%" style="font-size:1em; z-index: 2; margin-top: 20px;">
            <tr>
                <td width="10%" rowspan="2" align ="right" style="vertical-align: top;"><img
                        src="{{ public_path('/assets/img/LogoLegislaturaTransparente.png') }}"
                        style="width: 45px; height: 44px" alt=""></td>
                <td width="10%" rowspan="2" style="vertical-align: top;"><img
                        src="{{ public_path('/assets/img/LogoOsfemTransparente.png') }}"
                        style="width: 120px; height: 38px" alt=""></td>
                <td width="60%" align ="right">
                    <span
                        style="font-family: Arial; color: #000000; font-size: 22px;  ">Constancia de &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </td>
                <td width="10%"></td>
                <td width="10%"></td>
            </tr>
            <tr>
                <td style="margin-top: -2px;" align ="right">
                    <span style="font-family: Arial; color: #A0B0B9; font-size: 24px;   font-weight: bold;">Movimiento</span>
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="5">
                    <center>
                        <span style="font-family: Times New Roman; color: #000000; font-size: 10px;   font-style: italic; margin-top: 10px ">
                            {{config("constants.MENSAJE_CONSTANCIA");}}
                        </span>
                    </center>
                </td>
            </tr>
            <tr>
                <td colspan="4" align ="right">                   
                    <span style="font-family: Arial; color: #000000; font-size: 10px;  ">Toluca de Lerdo, Estado de México; {{fechaactualreporte()}}</span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4" align ="right">                   
                    <span style="font-family: Arial; color: #000000; font-size: 10px;  ">@yield('Titulo')</span>
                </td>
                <td></td>
            </tr>
        </table>
    </header>
    <footer>
        <img src="{{ public_path('/assets/img/pleca.png') }}" width="100%"
            style="position: absolute; height: 100px; z-index: 10;">
        <table border="0" width="100%"
            style="font-size:1em; margin-top: 35px; border-collapse: collapse;  z-index: 20;">
            <tr>
                <td class="text-center">
                    <center>
                        <span style="font-family: Arial; color: #000000; font-size: 8px;">
                            Este documento y anexos, en su caso, serán tratados conforme a lo previsto en la Ley de
                            Protección de Datos Personales en Posesión de Sujetos Obligados del Estado de México y
                            Municipios.<br>Para mayor información, visite el aviso de privacidad en el sitio:
                            www.osfem.gob.mx</p>
                        </span>
                    </center>

                </td>
            </tr>
        </table>
    </footer>
    <main>
        
        <table>
            <tr>
                <td><span style="font-family: Arial; color: #000000; font-size: 10px;  ">ÓRGANO
                        SUPERIOR DE FISCALIZACIÓN DEL ESTADO DE MÉXICO</span></td>
            </tr>
            <tr>
                <td><span style="font-family: Arial; color: #000000; font-size: 10px;  ">P R E S E N T E:</span></td>
            </tr>
            <tr>
                <td>
                    <p style="font-family: Arial; color: #000000; font-size: 10px; text-align: justify;">En atribuciones
                        conferidas al suscrito en el articulo 'XX' del Reglamento Interior del Órgano Superior de
                        Fiscalización del Estado de México y en atención a mis funciones y obligaciones realicé el
                        análisis del soporte documental y metadatos del presente expediente, constando que cumple con la
                        normatividad establecida en las leyes de la materia.</p>
                </td>
            </tr>
        </table>
        @yield('content') 
        @if ($temporal == 0)
        <div style="page-break-inside: avoid;">
            <table>
                <tr>
                    <td colspan="2">
                        <p style="font-family: Arial; color: #000000; font-size: 10px; text-align: justify;"> Por lo
                            antes expuesto, firmo el presente documento entendible y legible, para su incorporación al
                            expediente de control mediante Plataforma Digital de este Órgano Superior.</p>
                    </td>
                </tr>
            </table>
            <div style="border: 1 solid black; border-radius: 3%; width:100%; align-items: center; ">
                <table width="95%" style="margin: 10px; table-layout:fixed;" border="0">
                    <tr>
                        <td colspan="2" width="100%">
                            <center>
                                <span
                                    style="font-family: Arial; color: #787878; font-size: 16px;   font-weight: bold;">F I R M A &nbsp; E L E C T R Ó N I C A</span>
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td width="15%">
                            <center>
                                <img src="data:image/png;base64, {{ base64_encode($qr) }}" width="80%" />
                            </center>
                        </td>
                        <td width="85%" style="word-wrap:break-word;">
                            <p style="font-family: Arial; color: #000000; font-size: 5px;">
                                {{ $firma }}<br><span
                                    style="font-family: Arial; color: #000000; font-size: 6px;  ">Hash:{{ $hash }}
                                    <br>Fecha y Hora de Certificación:{{ $fechahora }}</span></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" width="100%">
                            <center>
                                <span
                                    style="font-family: Arial; color: #616161; font-size: 12px;   font-weight: bold;">{{ $firmante }}</span><br>
                                <span
                                    style="font-family: Arial; color: #616161; font-size: 11px;  ">{{ $firmante_puesto }}</span>
                            </center>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </main>
    <div style="z-index: 10;">
        <script type="text/php">
            if (isset($pdf)) {
                $x = 280;
                $y = 825;
                $text = "Página  {PAGE_NUM} de {PAGE_COUNT}";
                $font = null;
                $size = 6;
                $color = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>
    </div>
</html>
