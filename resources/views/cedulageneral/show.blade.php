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
            margin: 1px 1px;
        }

		header {
			position: fixed;
			top: 0px;
			left: 0px;
			right: 0px;
			height: 0px;
			background-color: #000;
		}

		footer {
			position: fixed;
			bottom: -60px;
			left: 0px;
			right: 0px;
			height: 50px;

			/** Extra personal styles **/
			background-color: #03a9f4;
			color: white;
			text-align: center;
			line-height: 35px;
		}

    </style>
</head>
<body>
<header>
</header>

<footer>
</footer>

<main>
	<table width="100%">
        <tr style="border-collapse:separate;border-spacing:0px;">
            <td colspan="2" rowspan="3" style="width:20%; border: 1px solid;  color: grey;">
                {{-- <div style="width: max-content;">--}}
                    <table width="100%">
                        <tr>
                            <td style="width:27%; vertical-align:middle; text-align: center;">
                                <img alt="Logo" src="{{asset('assets/img/logo1.png')}}" width="80%" />
                            </td>
                            <td style="width:46%; vertical-align:middle;text-align: center;">
                                <span style="color: #960048;font-size: 0.6rem;"><strong>Unidad de Seguimiento</strong></span>
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
	</table>
	<table width="100%">
		<tr style="width:100%; border-collapse:separate;border-spacing:0 500px; vertical-align: middle;">
            <td rowspan="2" style="width:15%; background-color:#fff;"></td>
            <td colspan="6" style="width: 80%; background-color: #960048; text-align: center; color: white; vertical-align: middle;">
                <span style="font-size: .6rem;"><strong>1.ª Etapa de Aclaración</strong></span>
            </td>
        </tr>
		<tr style="border-collapse:separate;border-spacing:0 500px;">
            <td style="text-align: center; width: 14%; color: white; background-color: #960048; vertical-align: middle;">
                <span style="font-size: .6rem;"><strong>Fecha de Comparecencia</strong></span>
            </td>
            <td style="border: .5 solid; width:13%; color: #424242; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;{{ fecha(optional($auditoria->comparecencia)->fecha_comparecencia) }}</strong></span>
            </td>
            <td style="text-align: center; width: 13%; color: white; background-color: #960048; vertical-align: middle;">
                <span style="font-size: .6rem;"><strong>Fecha de Inicio</strong></span>
            </td>
            <td style="border: .5 solid; width:13%; color: #424242; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;{{ fecha(optional($auditoria->comparecencia)->fecha_inicio_aclaracion)  }}</strong></span>
            </td>
            <td style="text-align: center; width: 13%; color: white; background-color: #960048; vertical-align: middle;">
                <span style="font-size: .6rem;"><strong>Fecha de Vencimiento</strong></span>
            </td>
            <td style="border: .5 solid; width:14%; color: #424242; vertical-align:middle;">
                <span style="font-size: 0.6rem"><strong>&nbsp;{{ fecha(optional($auditoria->comparecencia)->fecha_termino_aclaracion)}}</strong></span>
            </td>
        </tr>
	</table>

	<div style="width:70%;  float: left;">
		<table width="100%">
			<tr>
				<td colspan="2">
					<table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
						<tr>
							<td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Acciones Promovidas</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Núm.</strong></span></td>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Importe</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalsolacl)+count($auditoria->totalpliegos) }}</strong></span></td>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $TAP, 2) }}</strong></span></td>
						</tr>
					</table>
				</td>
				<td colspan="2">
					<table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
						<tr>
							<td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Acciones Promovidas Solventadas</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Núm.</strong></span></td>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Importe</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalsolventadosolacl)+count($auditoria->totalsolventadopliegos) }}</strong></span></td>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $TAPS, 2) }}</strong></span></td>
						</tr>
					</table>
				</td>
				<td colspan="2">
					<table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
						<tr>
							<td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Acciones Promovidas No Solventadas</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Núm.</strong></span></td>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Importe</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalNOsolventadopras)+count($auditoria->totalNOsolventadosolacl)+count($auditoria->totalNOsolventadopliegos) }}</strong></span></td>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $TAPNS, 2) }}</strong></span></td>
						</tr>
					</table>
				</td>
				</tr>
				<tr></tr>
			<tr>
				<td colspan="2">
					<table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
						<tr>
							<td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Solicitudes de Aclaración Promovidas</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Núm.</strong></span></td>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Importe</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalsolacl) }}</strong></span></td>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $TSP, 2) }}</strong></span></td>
						</tr>
					</table>
				</td>
				<td colspan="2">
					<table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
						<tr>
							<td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Solicitudes de Aclaración Solventadas</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Núm.</strong></span></td>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Importe</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalsolventadosolacl) }}</strong></span></td>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $TSPS, 2) }}</strong></span></td>
						</tr>
					</table>
				</td>
				<td colspan="2">
					<table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
						<tr>
							<td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Solicitudes de Aclaración No Solventadas</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Núm.</strong></span></td>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Importe</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalNOsolventadosolacl) }}</strong></span></td>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $TSPNS, 2) }}</strong></span></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr></tr>
			<tr>
				<td colspan="2">
					<table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
						<tr>
							<td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Pliegos de Observaciones Promovidos</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Núm.</strong></span></td>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Importe</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalpliegos) }}</strong></span></td>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $TPP, 2) }}</strong></span></td>
						</tr>
					</table>
				</td>
				<td colspan="2">
					<table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
						<tr>
							<td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Pliegos de Observaciones Solventados</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Núm.</strong></span></td>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Importe</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalsolventadopliegos) }}</strong></span></td>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $TPPS, 2) }}</strong></span></td>
						</tr>
					</table>
				</td>
				<td colspan="2">
					<table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
						<tr>
							<td colspan="2" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Pliegos de Observaciones No Solventados</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Núm.</strong></span></td>
							<td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Importe</strong></span></td>
						</tr>
						<tr>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ count($auditoria->totalNOsolventadopliegos) }}</strong></span></td>
							<td style="text-align: center; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $TPPNS, 2) }}</strong></span></td>
						</tr>
					</table>
				</td>
			</tr>
			</table>
			</div>
	<div style="width:30%; float: right;">
        <table style="border-collapse:collapse; border: 1px solid; border-color: #424242; padding-top: 0%;" width="100%">
            <tr>
                <td colspan="2" style="text-align: center; width: 20%; height:1.5%; color: white; background-color: #960048;"> <span style="font-size: .6rem;"><strong>Acciones Promovidas</strong></span></td>
            </tr>
            <tr>
                <td style="text-align: center; width: 10%; height:3%; color: black; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Recomendaciones</strong></span></td>
                <td style="text-align: center; width: 10%; height:3%; color: black; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>{{ count($auditoria->totalrecomendacion) }}</strong></span></td>
            </tr>
            <tr>
                <td style="text-align: center; width: 10%; height:3%; color: black; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>PRAS</strong></span></td>
                <td style="text-align: center; width: 10%; height:3%; color: black;border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>{{ count($auditoria->totalpras) }}</strong></span></td>
            </tr>
            <tr>
                <td style="text-align: center; width: 10%; height:3%; color: black;border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Solicitudes de Aclaración</strong></span></td>
                <td style="text-align: center; width: 10%; height:3%; color: black;border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>{{ count($auditoria->totalsolacl) }}</strong></span></td>
            </tr>
            <tr>
            <td style="text-align: center; width: 10%; height:3%; color: black;border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Pliegos de Observación</strong></span></td>
                <td style="text-align: center; width: 10%; height:3%; color: black;border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>{{ count($auditoria->totalpliegos) }}</strong></span></td>
            </tr>
            <tr>
                <td style="text-align: center; width: 10%; height:7%; color: black; border: 1px solid; border-color: #424242; background-color: #D8D8D8;"> <span style="font-size: .6rem;"><strong>TOTAL</strong></span></td>
                <td style="text-align: center; width: 10%; height:7%; color: black; border: 1px solid; border-color: #424242; background-color: #D8D8D8;"> <span style="font-size: .6rem;"><strong>{{ count($auditoria->acciones) }}</strong></span></td>
            </tr>
        </table>
	</div>
	@if(count($auditoria->totalsolacl)>0)
    <table  width="100%" style="clear: both; border: 1px solid; border-collapse:collapse; border-color: #424242;">
        <tr>
            <td colspan="8" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Solicitudes de Aclaración</strong></span></td>
        </tr>
        @foreach ($auditoria->totalsolacl as $solacl)
		<tr>
            <td colspan="1" style="text-align: center; width: 5%;"> <span style="font-size: .6rem;"></span></td>
            <td style="text-align: center; width: 19%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Número</strong></span></td>
            <td style="text-align: center; width: 19%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Importe Promovido</strong></span></td>
            <td style="text-align: center; width: 19%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Importe Solventado</strong></span></td>
            <td style="text-align: center; width: 19%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Importe No Solventado</strong></span></td>
            <td style="text-align: center; width: 19%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Estatus</strong></span></td>
        </tr>
        <tr>
            <td style="text-align: center; width: 5%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong> {{ $loop->iteration }}</strong></span></td>
            <td style="text-align: center; width: 19%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ $solacl->numero }}</strong></span></td>
            <td style="text-align: center; width: 19%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $solacl->monto_aclarar, 2) }}</strong></span></td>
            <td style="text-align: center; width: 19%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( ((!empty($solacl->solicitudesaclaracion)&&!empty($solacl->solicitudesaclaracion->monto_solventado))?$solacl->solicitudesaclaracion->monto_solventado:0), 2) }}</strong></span></td>
            <td style="text-align: center; width: 19%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $solacl->monto_aclarar-(!empty($solacl->solicitudesaclaracion)&&empty($solacl->solicitudesaclaracion->monto_solventado)?$solacl->solicitudesaclaracion->monto_solventado:0), 2) }}</strong></span></td>
            <td style="text-align: center; width: 19%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ ((!empty($solacl->solicitudesaclaracion)&&!empty($solacl->solicitudesaclaracion->calificacion_sugerida))?$solacl->solicitudesaclaracion->calificacion_sugerida:"") }}</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center; width: 100%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Acción Promovida</strong></span></td>
        </tr>
        <tr>
            <td style="padding: 5px 20px 0 15px; text-align: justify; width: 97%; border: 1px solid; border-color: #424242;" colspan="8"><span style="font-size: .6rem;"><strong><p><?php echo nl2br(htmlspecialchars($solacl->accion)); ?></p></strong></span></td>
        </tr>
        @endforeach
    </table>
	@endif
	
	    <table  width="100%" style="clear: both; border: 1px solid; border-collapse:collapse; border-color: #424242; ">
        <tr>
            <td colspan="8" style="text-align: center; width: 20%; color: white; background-color: #960048; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Pliegos de Observación</strong></span></td>
        </tr>
		</table>
        @foreach ($auditoria->totalpliegos as $pliegos)
		<div style="page-break-inside: avoid"> 
		<table width="100%" style="clear: both; border: 1px solid; border-collapse:collapse; border-color: #424242; ">
        <tr>
            <td colspan="1" style="text-align: center; width: 5%;"> <span style="font-size: .6rem;"></span></td>
            <td style="text-align: center; width: 19%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Número</strong></span></td>
            <td style="text-align: center; width: 19%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Importe Promovido</strong></span></td>
            <td style="text-align: center; width: 19%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Importe Solventado</strong></span></td>
            <td style="text-align: center; width: 19%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Importe No Solventado</strong></span></td>
            <td style="text-align: center; width: 19%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Estatus</strong></span></td>
        </tr>
        <tr>
            <td style="text-align: center; width: 5%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong> {{ $loop->iteration }}</strong></span></td>
            <td style="text-align: center; width: 10%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ $pliegos->numero }}</strong></span></td>
            <td style="text-align: center; width: 10%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format( $pliegos->monto_aclarar, 2) }}</strong></span></td>
            <td style="text-align: center; width: 10%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format(((!empty($pliegos->pliegosobservacion)&&!empty($pliegos->pliegosobservacion->monto_solventado))?$pliegos->pliegosobservacion->monto_solventado:0), 2) }}</strong></span></td>
            <td style="text-align: center; width: 10%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ '$'.number_format($pliegos->monto_aclarar-(!empty ($pliegos->pliegosobservacion)&&!empty($pliegos->pliegosobservacion->monto_solventado)?$pliegos->pliegosobservacion->monto_solventado:0), 2) }}</strong></span></td>
            <td style="text-align: center; width: 10%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>{{ ((!empty($pliegos->pliegosobservacion)&&!empty($pliegos->pliegosobservacion->calificacion_sugerida))?$pliegos->pliegosobservacion->calificacion_sugerida:0) }}</td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center; width: 50%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"> <span style="font-size: .6rem;"><strong>Acción Promovida</strong></span></td>
        </tr>
		</table>           
		       
            <div style="padding: 5px 20px 0 15px; text-align: justify; width: 97%; border: 1px solid; border-color: #424242;" ><span style="font-size: .6rem; " ><strong><p><?php echo nl2br(htmlspecialchars($pliegos->accion)); ?></p></strong></span></div>
       	</div>
        @endforeach
  
	

	{{--@if (count($auditoria->cedulageneralseguimiento)>0 && $auditoria->cedulageneralseguimiento[0]->fase_autorizacion=='Autorizado')--}}
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
		{{--@endif--}}
    </table>
</main>
</body>
</html>
