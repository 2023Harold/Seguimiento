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
            margin-bottom: 10px;
        }

    </style>
</head>
<body>


<table width="100%" style="page-break-inside: avoid;">
        <tr style="border-collapse:separate;border-spacing:0px;">
            <td colspan="2" rowspan="3" style="width:20%; border: 1px solid;  color: grey;">
                {{-- <div style="width: max-content;">--}}
                    <table width="100%">
                        <tr>
                            <td style="width:27%; vertical-align:middle; text-align: center;">
                                <img alt="Logo" src="{{asset('assets/img/logo1.png')}}" width="80%" />
                            </td>
                            <td style="width:46%; vertical-align:middle;text-align: center;">
                                <span style="color: #960048;font-size: 0.6rem;">Unidad de Seguimiento</span>
                            </td>
                            <td style="width:27%; vertical-align:middle;text-align: center;">
                                <img alt="Logo" src="{{asset('assets/img/logoh.png')}}" width="80%" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center; align-content: center; align-items: center;">
                                <span style="color: #960048; font-size: .6rem;"><strong>CÉDULA GENERAL DE SEGUIMIENTO</strong></span>
                            </td>
                        </tr>
                    </table>
                {{--</div> --}}
            </td>
            <td style="width:20%; background-color: #960048; color: white; vertical-align:middle; justify-items: center;">
                <span style="font-size: 0.6rem"><strong>&nbsp;ENTIDAD</strong></span>
            </td>
            <td colspan="5" style="border: .5 solid; width:60%; color: #424242; vertical-align:middle;">
                <span style="font-size: 0.7rem"><strong>&nbsp;{{ ($auditoria->nombreentidadcedula)?$auditoria->nombreentidadcedula->entidades:'' }}</strong></span>
            </td>
        </tr>
        <tr style="border-collapse:separate;border-spacing:0 500px;">
            <td style="width:15%; background-color: #960048; color: white; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;PERIODO DE REVISIÓN</strong></span>
            </td>
            <td colspan="3" style="border: .5 solid; width:25%; color: #424242; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;{{ $auditoria->periodo_revision }}</strong></span>
            </td>
            <td style="width:15%; background-color: #960048; color: white; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;NÚMERO DE AUDITORÍA</strong></span>
            </td>
            <td style="border: .5 solid; width:20%; color: #424242; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;{{ $auditoria->numero_auditoria }}</strong></span>
            </td>
        </tr>
        <tr style="border-collapse:separate;border-spacing:0 500px;">
            <td style="width:20%; background-color: #960048; color: white; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;ACTO DE FISCALIZACIÓN</strong></span>
            </td>
            <td colspan="3" style="border: .5 solid; width:20%; color: #424242; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;{{ $auditoria->acto_fiscalizacion }}</strong></span>
            </td>
            <td style="width:20%; background-color: #960048; color: white; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;NÚMERO DE EXPEDIENTE</strong></span>
            </td>
            <td style="border: .5 solid; width:20%; color: #424242; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;{{ optional($auditoria->radicacion)->numero_expediente}}</strong></span>
            </td>
        </tr>
        <tr style="border-collapse:separate;border-spacing:0 500px; vertical-align: middle;">
            <td colspan="2" rowspan="2"></td>
            <td colspan="6" style="text-align: center; width: 80%; color: white; background-color: #960048; vertical-align: middle;">
                <span style="font-size: .6rem;"><strong>Proceso de Atención a Recomendaciones</strong></span>
            </td>
        </tr>
        <tr style="border-collapse:separate;border-spacing:0 500px;">
            <td style="text-align: center; width: 10%; color: white; background-color: #960048; vertical-align: middle;">
                <span style="font-size: .6rem;"><strong>Fecha de Comparecencia</strong></span>
            </td>
            <td colspan="2" style="border: .5 solid; width:10%; color: #424242; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;{{ fecha(optional($auditoria->comparecencia)->fecha_comparecencia) }}</strong></span>
            </td>
            <td style="text-align: center; width: 10%; color: white; background-color: #960048; vertical-align: middle;">
                <span style="font-size: .6rem;"><strong>Fecha de Vencimiento</strong></span>
            </td>
            <td colspan="2" style="border: .5 solid; width:10%; color: #424242; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>{{ fecha($rfm) }}</strong></span>
            </td>
        </tr>
        <tr></tr>
        <tr>
            <td colspan="2">
                <table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Recomendaciones determinadas</strong></span></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalrecomendacion) }}</strong></span></td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
                <table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Recomendaciones atendidas</strong></span></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalsolventadorecomendacion) }}</strong></span></td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
                <table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Recomendaciones no atendidas</strong></span></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalNOsolventadorecomendacion) }}</strong></span></td>
                    </tr>
                </table>
            </td>
            <td colspan="2" rowspan="12" style="vertical-align: top;">
            </td>
        </tr>
        <tr></tr>

        <tr style="border-collapse:separate;border-spacing:0 500px;">
            <td style="text-align: center; width: 20%;"></td>
            <td style="text-align: center; width: 20%;"></td>
            <td style="text-align: center; width: 20%;"></td>
            <td style="text-align: center; width: 20%;"></td>
            <td style="text-align: center; width: 20%;"></td>
            <td style="text-align: center; width: 20%;"></td>
        </tr>
        <tr style="border-collapse:collapse;border-spacing:0 500px;">
            <td colspan="8">
                <table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td colspan="8" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Recomendaciones</strong></span></td>
                    </tr>
                    @foreach ($auditoria->totalrecomendacion as $recomendacion)
                    <tr>
                        <td style="text-align: center; width: 25%; border-color: #424242;"> <span style="font-size: .6rem;"></span></td>
                        <td style="text-align: center; width: 25%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Número</strong></span></td>
                        <td style="text-align: center; width: 25%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Plazo convenido</strong></span></td>
                        <td style="text-align: center; width: 25%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Estatus</strong></span></td>
                    </tr>
                        <tr>
                            <td style="text-align: center; width: 25%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong> {{ $loop->iteration }}</strong></span></td>
                            <td style="text-align: center; width: 25%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ $recomendacion->numero }}</strong></span></td>
                            <td style="text-align: center; width: 25%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ $recomendacion->plazo_recomendacion }}</strong></span></td>
                            <td style="text-align: center; width: 25%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{((!empty($recomendacion->recomendaciones)&&!empty($recomendacion->recomendaciones->calificacion_sugerida))?$recomendacion->recomendaciones->calificacion_sugerida:0) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: center; width: 90%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Recomendación Determinada</strong></span></td>
                        </tr>
                        <tr>
                            <td style="text-align: justify; width: 90%; border: 1px solid; border-color: #424242;" colspan="4"><span style="font-size: .6rem;"><strong><?php echo nl2br(htmlspecialchars($recomendacion->accion)); ?></strong></span></td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr></tr>
		</table>
   
                <table style="border-collapse:collapse;" width="100%">
                    <tr>
                        <td colspan="6" style="text-align: center; color: black; width: 100%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>ELABORÓ:</strong></span></td>
                    </tr>
                    <tr>
                        @foreach ($nombresanalistasL as $analista)
                        <td colspan="{{(count($nombresanalistasL)==3?'2': (count($nombresanalistasL)==2?'3': '6')) }}" style="text-align: center; color: black; width: 40%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong><br><br><br><br><br>{{ $analista }} <br> ANALISTA</strong></span></td>
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan="6" style="text-align: center; color: black; width: 100%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>SUPERVISÓ:</strong></span></td>
                    </tr>
                    <tr>
                        @foreach ($nombreslideresL as $lider)
                        <td colspan="{{(count($nombreslideresL)==3?'2': (count($nombreslideresL)==2?'3': '6')) }}" style="text-align: center; color: black; width: 40%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong><br><br><br><br><br>  {{ $lider }} <br> LÍDER DE PROYECTO</strong></span></td>
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan="6" style="text-align: center; color: black; width: 100%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>REVISÓ:</strong></span></td>
                    </tr>
                    <tr>
                         <td colspan="6" style="text-align: center; color: black; width: 40%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong><br><br><br><br><br>  {{ $jefe->name }} <br> JEFE DE DEPARTAMENTO</strong></span></td>

                    </tr>
                    <tr>
                        <td colspan="3"  style="text-align: center; color: black; width: 50%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>VALIDÓ: <br><br><br><br><br>  {{ $director->name }} <br>DIRECTOR</strong></span></td>
                        <td colspan="3" style="text-align: center; color: black; width: 50%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>AUTORIZÓ:<br><br><br><br><br>  {{ auth()->user()->titular->name }} <br>TITULAR DE LA UNIDAD DE SEGUIMIENTO</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;"></td>
                        <td style="width: 15%;"></td>
                        <td style="width: 15%;"></td>
                        <td style="width: 15%;"></td>
                        <td style="width: 15%;"></td>
                        <td style="width: 20%;"></td>
                    </tr>
                </table>
            
   

</body>
</html>
