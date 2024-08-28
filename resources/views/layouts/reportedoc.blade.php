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
            margin: 4cm 2cm 2cm;
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
            top: 0px;
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
        <table border="0" width="100%" style="font-size:1em; z-index: 2; margin-top: 20px;">
            <tr>
                
                <td width="13%" style="vertical-align: top;" align ="right">
                    <img src="{{ public_path('/assets/img/LogoLegislaturaTransparente.png') }}" style="width: 55px; height: 54px" alt="">
                </td>
                <td width="50%" style="vertical-align: baseline;">
                    <center>
                        <span style="font-family: Times New Roman; color: #000000; font-size: 14px;   font-style: bold; margin-top: 15px ">
                            Unidad de Seguimiento
                        </span>
                    </center>
                </td>
                <td width="20%" style="vertical-align: top;" align ="left">
                    <img src="{{ public_path('/assets/img/log1.png') }}" style="width: 120px; height: 48px" alt="">
                </td>
                
            </tr>           
            <tr>                
                <td colspan="3">
                    <center>
                        <span style="font-family: Times New Roman; color: #000000; font-size: 10px;   font-style: italic; margin-top: 15px ">
                            {{config("constants.MENSAJE_CONSTANCIA");}}
                        </span>
                    </center>
                </td>
                
            </tr>
            <tr>                
                <td colspan="3">
                    <center>
                        <span style="font-family: Times New Roman; color: #000000; font-size: 14px;   font-style: bold; margin-top: 15px ">
                            ACUERDO RADICACIÓN
                        </span>
                    </center>
                </td>
            </tr>
            {{-- <tr>
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
            </tr> --}}
        </table>
    </header>
    <footer>
       
        <table border="0" width="100%"
            style="font-size:1em; margin-top: 35px; border-collapse: collapse;  z-index: 20;">
            <tr>
                <td class="text-center">
                    <center>
                        <span style="font-family: Arial; color: #000000; font-size: 8px;">
                            Av. José María Pino Suárez Sur, núms. 104, 106 y 108, Colonia Cinco de Mayo, Toluca, Estado de México, C.P. 50090    Tel. 722 167 84 50  (Opción 3)<br>
                            Este documento y anexos, en su caso, serán tratados conforme a lo previsto en la Ley de Protección de Datos Personales en Posesión de Sujetos Obligados del Estado de México y Municipios<br>
                            Para mayor información, visite el aviso de privacidad en los sitios: IntraNet o www.osfem.gob.mx </p>
                        </span>
                    </center>

                </td>
            </tr>
        </table>
    </footer>
    <main>        
        <br>
        @yield('content') 
        @if ($temporal == 0)
        <div style="page-break-inside: avoid;">            
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
