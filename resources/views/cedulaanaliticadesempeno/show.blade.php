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
@php
    $i=0;
@endphp
<body>         
    @foreach ($auditoria->acciones as $accion)
    @if($accion->tipo=='Recomendación')   
    <table width="100%" >  
        <tr style="border-collapse:collapse;border-spacing:0px;">
            <td colspan="2" style="width:250px; color: #424242;">                
                <table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td style="vertical-align:middle; text-align: center; width:50px;">
                            <img alt="Logo" src="{{asset('assets/img/logo1.png')}}" width="65%" />
                        </td>
                        <td style="text-align: center;">
                            <span style="color: #960048;font-size: 0.6rem;"><strong>Unidad de Seguimiento</strong></span>
                        </td>
                        <td style="vertical-align:middle; text-align: center; width:50px;">
                            <img alt="Logo" src="{{asset('assets/img/logoh.png')}}" width="65%" />
                        </td>
                    </tr> 
                    <tr>
                        <td colspan="3" style="text-align: center; align-content: center; align-items: center;">
                            <span style="color: #960048; font-size: .6rem;"><strong>CÉDULA GENERAL DE SEGUIMIENTO</strong></span>
                        </td>
                    </tr>                   
                </table>
            </td>
            <td colspan="6" style="width:900px; color: #424242;">
                <table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td style="background-color: #960048; color: white; height: 30px;">
                            <span style="font-size: 0.6rem"><strong>&nbsp;ENTIDAD:</strong></span>
                        </td>
                        <td style="border: 1px solid; border-collapse:collapse; border-color: #424242; width:500px;">
                            <span style="font-size: 0.6rem"><strong>&nbsp;{{ ($auditoria->nombreentidadcedula)?$auditoria->nombreentidadcedula->entidades:'' }}</strong></span>
                        </td>
                    </tr>
                    <tr>
                        <td><span style="font-size: 0.6rem"><strong>&nbsp;PERIODO DE REVISIÓN: &nbsp; {{ $auditoria->periodo_revision }} </strong></span></td>
                        <td><span style="font-size: 0.6rem"><strong>&nbsp;NÚMERO DE AUDITORÍA: &nbsp; {{ $auditoria->numero_auditoria }} </strong></span></td>
                    </tr>
                    <tr>
                        <td><span style="font-size: 0.6rem"><strong>&nbsp;ACTO DE FISCALIZACIÓN:&nbsp;{{ $auditoria->acto_fiscalizacion }}</strong></span></td>
                        <td><span style="font-size: 0.6rem"><strong>&nbsp;NÚMERO DE EXPEDIENTE: &nbsp;{{ optional($auditoria->radicacion)->numero_expediente}}</strong></span></td>
                    </tr>
                </table>
            </td>             
        </tr>              
        <tr>
            <td colspan="4">
                <table style="border: 1px solid; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td style="text-align: center; width: 50%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Tipo de accion promovida:</strong></span></td>
                        <td style="text-align: center; width: 50%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Número de acción:</strong></span></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; border: .5 solid black; width:60%; color: #960048; vertical-align:middle;"><span style="font-size: 0.6rem"><strong>&nbsp;{{ $accion->tipo }}</strong></span></td>
                        <td style="text-align: center; border: .5 solid black; width:60%; color: #960048; vertical-align:middle;"><span style="font-size: 0.6rem"><strong>&nbsp;{{ $accion->numero }}</strong></span></td>
                    </tr>
                </table>
            </td>            
            <td></td>            
            <td colspan="3">
                <table style="border: 1px none; border-collapse:collapse; border-color: #424242;" width="100%">                    
                    <tr>
                        <td style="text-align: center; width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>Estatus:</strong></span></td>
                        <td></td>
                        <td></td>
                    </tr>                   
                    <tr>
                        <td style=" text-align: center; border: .5 solid black; width:60%; color: #960048; vertical-align:middle;"><span style="font-size: 0.6rem"><strong>&nbsp;{{((!empty($accion->recomendaciones->fase_autorizacion)&&$accion->recomendaciones->fase_autorizacion=='Autorizado')? $accion->recomendaciones->calificacion_sugerida:'Pendiente')}}</strong></span></td>
                        <td></td>
                        <td></td>
                    </tr>                   
                </table>
            </td>                  
        </tr>
        <tr>
            <td colspan="8">
                <table style="border: 1px none; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td style="width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: 0.6rem"><strong>OBSERVACIÓN PROMOVIDA:</strong></span></td>
                    </tr>
                    <tr>
                        <td style="text-align: justify; border: .5 solid black; width:60%; color: #960048; vertical-align:middle; font-size: 0.6rem;">
                            <span>
								<strong>
									@php
										 echo nl2br(htmlentities($accion->accion));									
									@endphp
								</strong>
							</span> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>       
        <tr>
            <td colspan="8">
                <table style="border: 1px none; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td style="width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: 0.6rem"><strong>ORDENAMIENTOS LEGALES Y DISPOSICIONES JURÍDICAS APLICABLES:</strong></span></td>
                    </tr>
                    <tr>
                        <td style="text-align: justify; border: .5 solid black; width:60%; color: #960048; vertical-align:middle; font-size: 0.6rem;">
                            <span>
								<strong>									
									@php
										 echo nl2br(htmlentities($accion->normativa_infringida));									
									@endphp
								</strong>
							</span> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <table style="border: 1px none; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td style="width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: 0.6rem"><strong>EVIDENCIA DOCUMENTAL PARA LA ATENCIÓN DE LA RECOMENDACIÓN:</strong></span></td>
                    </tr>
                    <tr>
                        <td style="text-align: justify; border: .5 solid black; width:60%; color: #960048; vertical-align:middle; font-size: 0.6rem;">
                            <span>
                                <strong>
									@php
										 echo nl2br(htmlentities($accion->evidencia_resumen));									
									@endphp                                    
                                </strong>
                            </span> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <table style="border: 1px none; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td style="width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: 0.6rem"><strong>DESCRIPCIÓN DE LA DOCUMENTACIÓN PRESENTADA:</strong></span></td>
                    </tr>
                    <tr>
                        <td style="text-align: justify; border: .5 solid black; width:60%; color: #960048; vertical-align:middle; margin:45px; font-size: 0.6rem;">
							<strong>
								<span>								   
										@php
										if(!empty($accion->recomendaciones)&&!empty($accion->recomendaciones->listado_documentos)){
										 echo nl2br(htmlentities($accion->recomendaciones->listado_documentos));
										}									
										@endphp
								</span>
							<strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <table style="border: 1px none; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td style="width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: 0.6rem"><strong>ANALISIS DE LA DOCUMENTACIÓN PRESENTADA:</strong></span></td>
                    </tr>
                    <tr>
                        <td style="text-align: justify; border: .5 solid black; width:60%; color: #960048; vertical-align:middle; font-size: 0.6rem;">
                            <span>
                                <strong>
									@php
										if(!empty($accion->recomendaciones)&&!empty($accion->recomendaciones->conclusion)){
										 echo nl2br(htmlentities($accion->recomendaciones->conclusion));	
										}
									@endphp                                                           
                                </strong>
                            </span> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>         
    </table>
    @php
        $i=$i+1;
    @endphp
    @if(count($auditoria->totalrecomendacion) != $i)       
        <div style="page-break-after:always;"></div>
    @endif
    @endif
    @endforeach  


    <table width="100%">
    <tr>
        <td colspan="1"></td>
        <td colspan="6">
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
        </td>  
        <td colspan="1"></td>          
    </tr> 
    </table> 
</body>
</html>
