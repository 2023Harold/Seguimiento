<!doctype html>
<html lang="{{str_replace('_', '-', app()->getLocale())}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/img/favicon.png')}}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token()}}">
    <title>{{config('app.name', 'OSFEM')}}</title>
    <style>
        @page {
            margin-left: 0;
            margin-right: 0;
            margin-top: 0;
            margin-bottom: 0;
        }

    </style>
</head>
<body>
    <table style="border:solid;" width="100%">
        <tr>
            <td colspan="3" rowspan="3" style="display:inline-block; width:30%;">
                <div style="width: max-content;">
                    <table width="100%">
                        <tr>
                            <td style="width:37%; vertical-align:middle; text-align: center;">
                                <img alt="Logo" src="{{asset('assets/img/logo1.png')}}" width="30%" />
                            </td>
                            <td style="width:26%; text-align: center;">
                                <span style="color: #A13B71;font-size: 0.5rem;"><strong>Unidad de Seguimiento</strong></span>
                            </td>
                            <td style="width:37%; vertical-align:middle;text-align: center;">
                                <img alt="Logo" src="{{asset('assets/img/logoh.png')}}" width="30%" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center; align-content: center; align-items: center;">
                                <span style="color: #A13B71; font-size: .6rem;"><strong>CÉDULA GENERAL DE SEGUIMIENTO</strong></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="display:inline-block; width:20%; background-color: #A13B71; color: white; vertical-align:middle;">
                <span style="font-size: 0.7rem"><strong>&nbsp;ENTIDAD</strong></span>
            </td>
            <td colspan="4"  style="border: .5 solid; display:inline-block; width:49%; color: grey; vertical-align:middle;">
                <span style="font-size: 0.7rem">Hola</span>
            </td>
        </tr>
        {{-- <tr>
                    <td style="border: solid; background: #A13B71; color: white; align-items: center;">
                        <span style="font-size: 0.6rem;"><strong>&nbsp;PERIODO DE REVISIÓN</strong></span>
                    </td>
                    <td ></td>
                    <td  style="border: solid; background: #A13B71; color: white; align-items: center;">
                        <span style="font-size: 0.6rem;"><strong>&nbsp;NÚMERO DE AUDITORÍA</strong></span>
                    </td>
                    <td ></td>
                </tr>
                <tr>
                    <td  style="border: solid; background: #A13B71; color: white; align-items: center;">
                        <span style="font-size: 0.6rem;"><strong>&nbsp;ACTO DE FISCALIZACIÓN</strong></span>
                    </td>
                    <td></td>
                    <td  style="border: solid; background: #A13B71; color: white; align-items: center;">
                        <span style="font-size: 0.6rem;"><strong>&nbsp;NÚMERO DE EXPEDIENTE</strong></span>
                    </td>
                    <td></td>
                </tr> --}}
    </table>
</body>
</html>
