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
                            <span style="font-size: 0.6rem"><strong>&nbsp;{{ $auditoria->entidad_fiscalizable }}</strong></span>
                        </td>
                    </tr>
                    <tr>
                        <td><span style="font-size: 0.6rem"><strong>&nbsp;PERIODO DE REVISIÓN: &nbsp; {{ $auditoria->periodo_revision }} </strong></span></td>
                        <td><span style="font-size: 0.6rem"><strong>&nbsp;NÚMERO DE AUDITORÍA: &nbsp; {{ $auditoria->numero_auditoria }} </strong></span></td>
                    </tr>
                    <tr>
                        <td><span style="font-size: 0.6rem"><strong>&nbsp;ACTO DE FISCALIZACIÓN:&nbsp;{{ $auditoria->acto_fiscalizacion }}</strong></span></td>
                        <td><span style="font-size: 0.6rem"><strong>&nbsp;NÚMERO DE EXPEDIENTE: &nbsp;{{ $auditoria->radicacion->numero_expediente}}</strong></span></td>
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
                        <td style="width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: 0.6rem"><strong>RECOMENDACIÓN PROMOVIDA:</strong></span></td>
                    </tr>
                    <tr>
                        <td style="text-align: justify; border: .5 solid black; width:60%; color: #960048; vertical-align:middle;">
                            <span style="font-size: 0.6rem"><strong>{{ $accion->accion }}</strong></span> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>       
        <tr>
            <td colspan="8">
                <table style="border: 1px none; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td style="width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: 0.6rem"><strong>DEBER SER:</strong></span></td>
                    </tr>
                    <tr>
                        <td style="text-align: justify; border: .5 solid black; width:60%; color: #960048; vertical-align:middle;">
                            <span style="font-size: 0.6rem"><strong>{{ $accion->normativa_infringida }}</strong></span> 
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="8">
                <table style="border: 1px none; border-collapse:collapse; border-color: #424242;" width="100%">
                    <tr>
                        <td style="width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: 0.6rem"><strong>EVIDENCIA DOCUMENTAL QUE ACREDITE LA ATENCIÓN DE LA RECOMENDACIÓN:</strong></span></td>
                    </tr>
                    <tr>
                        <td style="text-align: justify; border: .5 solid black; width:60%; color: #960048; vertical-align:middle;">
                            <span style="font-size: 0.6rem">
                                <strong>
                                    {{ $accion->evidencia_resumen }}  
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
                        <td style="width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: 0.6rem"><strong>DOCUMENTACIÓN ANALIZADA:</strong></span></td>
                    </tr>
                    <tr>
                        <td style="text-align: justify; border: .5 solid black; width:60%; color: #960048; vertical-align:middle;">
                            <span style="font-size: 0.6rem">
                                <strong>                                   
                                        {{ $accion->recomendaciones->listado_documentos }}                                    
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
                        <td style="width: 20%; color: black; background-color: #D8D8D8; border: 1px solid; border-color: #424242;"><span style="font-size: 0.6rem"><strong>ANALISIS:</strong></span></td>
                    </tr>
                    <tr>
                        <td style="text-align: justify; border: .5 solid black; width:60%; color: #960048; vertical-align:middle;">
                            <span style="font-size: 0.6rem">
                                <strong>
                                     {{ $accion->recomendaciones->conclusion }}                                  
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
                <table style="border: 0px solid; border-collapse:collapse;" width="100%">
                    <tr>
                        <td colspan="2" style="text-align: center; color: black; width: 40%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>ELABORÓ: <br><br><br> ANALISTA</strong></span></td>
                        <td colspan="2" style="text-align: center; color: black; width: 40%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>SUPERVISÓ<br><br><br>LÍDER DE PROYECTO</strong></span></td>
                        <td colspan="2" style="text-align: center; color: black; width: 20%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>REVISÓ<br><br><br>JEFE DE DEPARTAMENTO</strong></span></td>
                    </tr>
                    <tr>
                        <td colspan="3"  style="text-align: center; color: black; width: 50%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>VALIDÓ<br><br><br>DIRECTOR</strong></span></td>
                        <td colspan="3" style="text-align: center; color: black; width: 50%; border: 1px solid; border-color: #424242;"><span style="font-size: .6rem;"><strong>AUTORIZÓ<br><br><br>TITULAR DE LA UNIDAD DE SEGUIMIENTO</strong></span></td>
                    </tr>
                    <tr>
                        <td style="width: 20%;"></td>
                        <td style="width: 20%;"></td>
                        <td style="width: 20%;"></td>
                        <td style="width: 20%;"></td>
                        <td style="width: 20%;"></td>
                        <td style="width: 20%;"></td>
                    </tr>
                </table>
            </td>  
            <td colspan="1"></td>          
        </tr>
    </table> 
</body>
</html>
