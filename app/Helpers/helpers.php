<?php

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Constancia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jaspersoft\Client\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\NumberFormatter;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

function fecha($fecha = null, string $formato = 'd/m/Y')
{
	
    if(empty($fecha)){
        return "";
    }

    $fecha = new Carbon($fecha);
    return optional($fecha)->format($formato);
}
function fechadias($fecha = null, $dias )
{
    if(empty($fecha)){
        return "";
    }

    $fecha = new Carbon($fecha); 
	$nuevafecha=$fecha->addDay($dias); 
    return optional($nuevafecha); 
}
function hora($fecha = null, string $formato = 'h:i')
{
    return optional($fecha)->format($formato);
}

function setMessage($mensaje, $alerta = 'success')
{
    return  flash($mensaje)->{$alerta}()->important();
}

//Registra las variables de sesion para el usuario
function setSession($attribute = '', $value = '')
{
    session([config('app.name').'_'.$attribute => $value]);
}

//Retorna las variables de sesion para el usuario
function getSession($attribute = '', $value = '')
{
    return session(config('app.name').'_'.$attribute);
}

function iconoArchivo($archivo)
{
    $extension = pathinfo(storage_path($archivo), PATHINFO_EXTENSION);
    switch (Str::lower($extension)) {
        case 'pdf':
            $icon = '<i class="align-middle fas fa-file-pdf text-primary fa-2x" aria-hidden="true"></i>';
            break;
        case 'xls':
        case 'xlsx':
            $icon = '<i class="align-middle fas fa-file-excel text-primary fa-2x" aria-hidden="true"></i>';
            break;
        case 'doc':
        case 'docx':
            $icon = '<i class="align-middle fas fa-file-word  text-primary fa-2x" aria-hidden="true"></i>';
            break;
        case 'jpg':
        case 'jpeg':
        case 'png':
            $icon = '<i class="align-middle fas fa-file-image text-primary fa-2x" aria-hidden="true"></i>';
            break;
        default:
            $icon = '<i class="align-middle fas fa-file-alt text-primary fa-2x" aria-hidden="true"></i>';
    }

    return $icon;
}

function archivo($field, $caption, $value, array $options = [])
{
    $url_archivo = (! empty($value)) ? asset($value) : 'javascript:void(0);';
    //$url_archivo = (! empty($value)) ? (asset(env('MINIO_URL')).'/'.$value) : 'javascript:void(0);';
    $verArchivoAdjunto = (! empty($value)) ? 'show' : 'none';
    $allowedFileExtensions = (array_key_exists('data-allowedFileExtensions', $options)) ? ['data-allowedFileExtensions' => $options['data-allowedFileExtensions']] : ['data-allowedFileExtensions' => 'pdf,doc,docx,xls,xlsx,png'];
    $options = array_merge($options, $allowedFileExtensions);
    $maxFileSize = (array_key_exists('data-maxFileSize', $options)) ? ['data-maxFileSize' => $options['data-maxFileSize']] : ['data-maxFileSize' => '20'];
    $options = array_merge($options, $maxFileSize);
    $html_tag = $caption;

    return ''.
            '<label for="'.$field.'" class="form-label">'.$caption.'<small class="mx-2">('.$allowedFileExtensions['data-allowedFileExtensions'].','.$maxFileSize['data-maxFileSize'].'MB)</small>'.'</label>'.
            '<a id="'.$field.'-upload-upload-link" href="'.$url_archivo.'" style="display: '.($value ? 'show' : 'none').';" class="btn btn-secondary btn-sm p-2 mx-2 pb-0 pt-0 mb-0" target="_blank">Archivo</a>'.
            '<a class="btn-link text-danger" id="'.$field.'-upload-delete-link" href="javascript:void(0);" onclick="javascript:if(confirm(\'¿Desea eliminar el archivo?\')){document.getElementById(\''.$field.'-upload-upload-link\').style.display=\'none\';document.getElementById(\''.$field.'-upload-delete-link\').style.display=\'none\';document.getElementById(\''.$field.'\').value=\'\'; $(\'#'.$field.'-upload'.'\').fileinput(\'reset\'); }" style="display:'.$verArchivoAdjunto.'">'.'<i class="mdi mdi-close"></i>Eliminar'.'</a>'.
            BootForm::file($field.'-upload', false, $options).
            '<div id="'.$field.'-upload-errorBlock" class="'.$field.'-upload-errors help-block mx-0"></div>'.
            BootForm::text($field, false, $value, ['class' => 'file-input-hidden', 'data-forig' => $field.'-upload', 'id' => $field, 'style' => 'display:none']);
}

function mover_archivos_minio(Request $request, $lista_archivos, $old_data = null, $ruta = null)
{
    $rutaDestino = 'app/public/archivos/';

    foreach ($lista_archivos as $archivo) {
        $fileRequest = $request[$archivo];
        $fileOld = ($old_data) ? $old_data->getAttributeValue($archivo) : '';
        if ($fileRequest != $fileOld) {
            if (filled($fileRequest)) {
                // curp 1dew234.pdf
                $fileDestino = $archivo.'-'.substr(basename($fileRequest), -16);
                $fileRequest = Str::replaceFirst('storage', 'public', $fileRequest);
                $fileDestino = Str::replaceFirst('.bin', '.xlsx', $fileDestino);
                $fileDestino = Str::replaceFirst('.txt', '.doc', $fileDestino);

                File::move(storage_path('app/'.$fileRequest), storage_path($rutaDestino.$fileDestino));
                // Actualizamos el Request
                $request[$archivo] = 'storage/archivos/'.$fileDestino;

                $contenido = file_get_contents($request[$archivo]);
                //$numeroExpediente = Str::slug($ruta);
                $nombre = $ruta.'/'.$fileDestino;
                Storage::cloud()->put($nombre,$contenido);
                $request[$archivo] = $nombre;
            }

            // Borramos el archivo anterior
            if ($fileOld) {
                // File::delete($fileOld);
                Storage::cloud()->delete($fileOld);
            }
        }
    }
}

function mover_archivos(Request $request, $lista_archivos, $old_data = null)
{
    $rutaDestino = 'app/public/archivos/';

    foreach ($lista_archivos as $archivo) {
        $fileRequest = $request[$archivo];
        $fileOld = ($old_data) ? $old_data->getAttributeValue($archivo) : '';
        if ($fileRequest != $fileOld) {
            if (filled($fileRequest)) {
                // curp 1dew234.pdf
                $fileDestino = $archivo.'-'.substr(basename($fileRequest), -16);
                $fileRequest = Str::replaceFirst('storage', 'public', $fileRequest);
                $fileDestino = Str::replaceFirst('.bin', '.xml', $fileDestino);
                $fileDestino = Str::replaceFirst('.txt', '.doc', $fileDestino);

                File::move(storage_path('app/'.$fileRequest), storage_path($rutaDestino.$fileDestino));
                // Actualizamos el Request
                $request[$archivo] = 'storage/archivos/'.$fileDestino;
            }
            // Borramos el archivo anterior
            if ($fileOld) {
                File::delete($fileOld);
            }
        }
    }
}

function btnFileMinio($archivo){
    if (!empty($archivo)) {
        return '<a href="'.asset(env('MINIO_URL')).$archivo.'" target="_blank">'.iconoArchivo($archivo).'</a>';
    }else{
        return '';
    }
}

function movimientosDesglose($id, $colspan, $movimientos)
{
    if (count($movimientos) > 0) {
        $results = '<tr><td colspan="'.$colspan. '"><div class="row mb-1">
                <div class="col-md-12 list-desglose">
                    <div class="text-primary pl-4 pt-2 collapsed" data-bs-toggle="collapse" href="#a-list-' . $id . '" aria-expanded="true">
                        <i class="fa fa-chevron-down fa-chev"></i> Lista de movimientos
                    </div>
                </div>
            </div>
            <div id="a-list-'.$id.'" class="collapse">
               
                    <table class="table gray-200">
                        <thead class="table-secondary">
                            <tr>
                                <th class="col-3">Movimiento</th>
                                <th class="col-2">Fecha y Hora</th>
                                <th class="w-15">Usuario</th>
                                <th class="w-1">Estatus</th>
                                <th>Comentario</th>
                            </tr>
                        </thead>
                        <tbody>';
        foreach ($movimientos as $movimiento) {
            $results .= '<tr>
                            <td>'.str_replace('Enviar', 'Se envío ', $movimiento->tipo_movimiento).'</td>
                            <td>'.fecha($movimiento->created_at, 'd/m/Y H:i:s').'</td>
                            <td>'.$movimiento->userCreacion->name.'<br><small class="text-muted">'.$movimiento->userCreacion->puesto.'</small></td>
                            <td class="text-center">'.($movimiento->estatus == 'Rechazado' ? '<i class="far fa-times-circle fa-2x text-danger"></i>' : '<i class="far fa-check-circle fa-2x text-success"></i>').'</td>
                            <td>'.$movimiento->motivo_rechazo.'</td>
                    </tr>';
        }
        $results .= '</tbody>
                    </table>
                
            </div></td></tr>';
    } else {
        $results = '';
    }

    return $results;
}

function reporte($idRegistro, $name, $params, $type = 'pdf')
{
    $client = new Client(env('JASPER_SERVER_URL'), env('JASPER_SERVER_USERNAME'), env('JASPER_SERVER_PASSWORD'));
    $params['mensaje_encabezado'] = config("constants.MENSAJE_CONSTANCIA");
    $report = $client->reportService()->runReport('/reports/' . $name, $type, null, null, $params);
    $nombre_temporal = str_replace('/', '_', $name) . '_' . $idRegistro . '.' . $type;
    Storage::disk('public')->put('temporales/' . $nombre_temporal, $report);

    return 'storage/temporales/' . $nombre_temporal;
}

function archivoFirma($field, $caption, $value, array $options = [])
{
    $allowedFileExtensions = (array_key_exists('data-allowedFileExtensions', $options)) ? ['data-allowedFileExtensions' => $options['data-allowedFileExtensions']] : ['data-allowedFileExtensions' => 'pdf,doc,png'];
    $options = array_merge($options, $allowedFileExtensions);
    $maxFileSize = (array_key_exists('data-maxFileSize', $options)) ? ['data-maxFileSize' => $options['data-maxFileSize']] : ['data-maxFileSize' => '6'];
    $options = array_merge($options, $maxFileSize);

    return ''.
            '<label for="'.$field.'" class="form-label">'.$caption.'<small class="mx-2">('.$allowedFileExtensions['data-allowedFileExtensions'].','.$maxFileSize['data-maxFileSize'].'MB)</small>'.'</label>'.
            BootForm::file($field.'-upload', false, $options).
            '<div id="'.$field.'-upload-errorBlock" class="'.$field.'-upload-errors help-block mx-0"></div>'.
            BootForm::text($field, false, $value, ['class' => 'file-input-hidden', 'data-forig' => $field.'-upload', 'id' => $field, 'style' => 'display:none']);
}

function camposFirma()
{
    echo '<div class="row">'.
    BootForm::hidden('acuse_xml', '', ['id' => 'acuse_xml']).
    BootForm::hidden('constancia_xml', '', ['id' => 'constancia_xml']).
    BootForm::hidden('id_proceso_xml', '', ['id' => 'id_proceso_xml']).
    BootForm::hidden('hash_xml', '', ['id' => 'hash_xml']).
    BootForm::hidden('acuse_pdf', '', ['id' => 'acuse_pdf']).
    BootForm::hidden('constancia_pdf', '', ['id' => 'constancia_pdf']).
    BootForm::hidden('id_proceso_pdf', '', ['id' => 'id_proceso_pdf']).
    BootForm::hidden('hash_pdf', '', ['id' => 'hash_pdf']).'</div>';
}

function guardarConstanciasFirmadas($model, $nombre_constancia, Request $request, $campo_constancia = null, $ruta_minio = '')
    {
        $campo_constancia = $campo_constancia ?? $nombre_constancia;
        // $rutaDestino = 'app/public/archivos/';
        $rutaDestino = 'storage/archivos';
        $caracteresAleatorios = Str::random(10).$model->id;

        $archivoxml = $nombre_constancia.'-'.$caracteresAleatorios.'.xml';
        $archivofir = $nombre_constancia.'-'.$caracteresAleatorios.'.fir';
        $constancia = $nombre_constancia.'-'.$caracteresAleatorios.'.der';
        $archivopdffirmado = $nombre_constancia.'-'.$caracteresAleatorios.'.pdf';

        $requestCons['acuse_xml'] = $request->acuse_xml;
        $requestCons['id_proceso_xml'] = $request->id_proceso_xml;
        $requestCons['hash_xml'] = $request->hash_xml;
        $requestCons['acuse_pdf'] = $request->acuse_pdf;
        $requestCons['id_proceso_pdf'] = $request->id_proceso_pdf;
        $requestCons['hash_pdf'] = $request->hash_pdf;

        

        
        //MINIO ALMACENAR
        /*$requestCons['archivo_xml'] = $nombrexml = $ruta_minio.'/'.$archivoxml;
        Storage::cloud()->put($nombrexml, base64_decode($request->archivo_firmar));
    
        $requestCons['archivo_fir'] = $nombrefir = $ruta_minio.'/'.$archivofir;
        Storage::cloud()->put($nombrefir, base64_decode($request->acuse_xml));
    
        $requestCons['constancia_xml'] = $nombreder = $ruta_minio.'/'.$constancia;
        Storage::cloud()->put($nombreder, base64_decode($request->constancia_xml));
    
        $request[$campo_constancia] = $requestCons['constancia_pdf'] = $nombre = $ruta_minio.'/'.$archivopdffirmado;
        Storage::cloud()->put($nombre, base64_decode($request->acuse_pdf));*/

        //ALMACENAR DE FORMA LOCAL       
        $requestCons['archivo_xml'] = $rutaDestino.'/'.$archivoxml;      
        Storage::disk('local')->put('public/archivos/'.$archivoxml, base64_decode($request->archivo_firmar));
       
        $requestCons['archivo_fir'] = $rutaDestino.'/'.$archivofir;
        Storage::disk('local')->put('public/archivos/'.$archivofir, base64_decode($request->acuse_xml));

        $requestCons['constancia_xml'] = $rutaDestino.'/'.$constancia;
        Storage::disk('local')->put('public/archivos/'.$archivofir, base64_decode($request->constancia_xml));
       
        $requestCons['constancia_pdf'] = $rutaDestino.'/'.$archivopdffirmado;
        Storage::disk('local')->put('public/archivos/'.$archivopdffirmado, base64_decode($request->acuse_pdf));

        


        
        $requestCons['accion'] = $model->getTable();
        $requestCons['accion_campo'] = $campo_constancia;
        $requestCons['accion_id'] = $model->id;
        $requestCons['usuario_creacion_id'] = auth()->user()->id;
               
        $constancia = Constancia::create($requestCons);

        return $constancia;
    }

    function documentosFirmados($model, $accion_campo)
    {
        return Constancia::where('accion_id', $model->id)->where('accion', $model->getTable())->where('accion_campo', $accion_campo)->first();
    }

    function btnDownloadXml($model, $campo)
    {
        if (documentosFirmados($model, $campo)) {
            $archivo_xml = documentosFirmados($model, $campo)->archivo_xml;
            if (! empty($archivo_xml)) {
                $nombres_xml = explode("/", $archivo_xml);

                //$url = asset(env('MINIO_URL')).'/'.$archivo_xml;
                $url = asset($archivo_xml);
                $headers = @get_headers($url);
                if($headers && strpos( $headers[0], '200')) {
                    return '<a href=data:application/pdf;base64,'.base64_encode(file_get_contents($url)).' download='.end($nombres_xml).'>
                        <i class="align-middle fas fa-file-code fa-2x" aria-hidden="true"></i>
                    </a>';
                }else {
                    return '';
                }
            } else {
                return '';
            }
        }else{
            return '';
        }
    }

    function addBusinessDays($date, $days)
    {
        $start = strtotime($date);
        while ($days > 0) {
            $start = strtotime("+1 day", $start);
            $weekday = date('N', $start);
            if ($weekday < 6) {
                $days--;
            }
        }
        return date('Y-m-d', $start);
    }

    function esVacioStr($valores=[],$auditoria){        
        $nuevovalor=null;
        $band="";
    
        for ($i=0;   $i < count($valores); $i++) { 
            if($i==0){
                if (empty(${$valores[$i]})){
                    return "";
                }else{
                    $band=$valores[0];
                }              
            }elseif($i==count($valores)-1){
                $band=$band.'->'.$valores[$i];
                if (empty(${$band})){
                    return "";
                }else{
                    return ${$band};
                }   
            }else{
                $band=$band.'->'.$valores[$i];
                if (empty(${$band})){
                    return "";
                }else{
                    $band=$valores[0];
                }   
            }            
        }        
    }

    function esVacioInt($valores=[],$auditoria){        
        $nuevovalor=null;
        $band="";
    
        for ($i=0;   $i < count($valores); $i++) { 
            if($i==0){
                if (empty(${$valores[$i]})){
                    return 0;
                }else{
                    $band=$valores[0];
                }              
            }elseif($i==count($valores)-1){
                $band=$band.'->'.$valores[$i];
                if (empty(${$band})){
                    return 0;
                }else{
                    return ${$band};
                }   
            }else{
                $band=$band.'->'.$valores[$i];
                if (empty(${$band})){
                    return 0;
                }else{
                    $band=$valores[0];
                }   
            }            
        }        
    }

    function fechaactualreporte()
    {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                    $fecha = Carbon::parse(now());

                    $formatterD = new NumeroALetras();
                    $anioD = $formatterD->toString($fecha->format('Y'));

                    $anioMax = ucwords($anioD);             
                    $anioMin = strtolower(ucwords(strtolower($anioMax)));

                    $mes = strtolower($meses[($fecha->format('n')) - 1]);
                    $fechaactual = $fecha->format('d') . ' dias del mes de ' . $mes . ' del año ' . $anioMin;


        return $fechaactual;
    }

    function reportepdf($nombrereporte,$temporal=1,$qr='',$auditoria,$accion='',$modeloprincipal=[],$relacion1=[],$relacion2=[],$relacion3=[],$relacion4=null,$firma='', $hash='', $fechahora='', $estatus='',$motivo_rechazo='', $firmante='',$firmante_puesto='')
    {
        $archivob64='';
        if(!empty($auditoria))
        $auditoria=Auditoria::find(substr(base64_decode($auditoria), 5, -5));
        if(!empty($accion))
        $accion=AuditoriaAccion::find(substr(base64_decode($accion), 5, -5));
        
        $relacionconstancia1 ='';
        $relacionconstancia2 ='';
        $relacionconstancia3 ='';
       
        $modelo = DB::table($modeloprincipal['tbl'])->where('id',substr(base64_decode($modeloprincipal['vinculo']), 5, -5))->first();
        if(!empty($relacion1))
        $relacionconstancia1 = DB::table( $relacion1['tbl_rel'])->where($relacion1['col_rel'],$modelo->id)->get();
        if(!empty($relacion2))
        $relacionconstancia2 = DB::table( $relacion2['tbl_rel'])->where($relacion2['col_rel'],$modelo->id)->get();
        if(!empty($relacion3))
        $relacionconstancia3 = DB::table( $relacion3['tbl_rel'])->where($relacion3['col_rel'],$modelo->id)->get();
       
        $codigoQR = QrCode::format('png')->size(100)->generate($qr);       
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $data = [
                 'temporal'=>$temporal,
                 'qr'=>$codigoQR,
                 'auditoria'=>$auditoria,                 
                 'accion'=>$accion, 
                 'modelo'=>$modelo,
                 'relacion1'=>$relacionconstancia1,
                 'relacion2'=>$relacionconstancia2,
                 'relacion3'=>$relacionconstancia3,
                 'relacion4'=>$relacion4,
                 'firma'=>$firma,
                 'hash'=>$hash,
                 'fechahora'=>$fechahora,
                 'estatus'=>$estatus,
                 'motivo_rechazo'=>$motivo_rechazo,
                 'firmante'=>$firmante,
                 'firmante_puesto'=>$firmante_puesto,
                ];
        $pdf->loadView('reportes.'.$nombrereporte, $data);


        Storage::disk('local')->put('/public/temporales/' . $nombrereporte . '.xml', $pdf->getDomPDF()->getDom()->saveXML());
        if($temporal==1){
            $b64archivoxml = chunk_split(base64_encode(file_get_contents(base_path() . '/public/storage/temporales/'.$nombrereporte.'.xml')));
            $archivob64= $b64archivoxml;
            $pdf->save(storage_path('app/public/temporales/') . $nombrereporte .'.pdf');
            $pdf->save(storage_path('app/public/temporales/') . $nombrereporte .'.docx');
        }else{
            $pdf->save(storage_path('app/public/temporales/') . $nombrereporte .'.pdf');
            $pdf64 = chunk_split(base64_encode(file_get_contents(storage_path('app/public/temporales/') . $nombrereporte.'.pdf')));
            $archivob64= $pdf64;
        }          

        return $archivob64;
    }
	
	function reportepdfprevio($nombrereporte,$temporal=1,$qr='',$auditoria,$accion='',$modeloprincipal=[],$relacion1=[],$relacion2=[],$relacion3=[],$relacion4=null,$firma='', $hash='', $fechahora='', $estatus='',$motivo_rechazo='', $firmante='',$firmante_puesto='')
    {
        $archivob64='';
        if(!empty($auditoria))
        $auditoria=Auditoria::find(substr(base64_decode($auditoria), 5, -5));
        if(!empty($accion))
        $accion=AuditoriaAccion::find(substr(base64_decode($accion), 5, -5));
        
        $relacionconstancia1 ='';
        $relacionconstancia2 ='';
        $relacionconstancia3 ='';
       
        $modelo = DB::table($modeloprincipal['tbl'])->where('id',substr(base64_decode($modeloprincipal['vinculo']), 5, -5))->first();
        if(!empty($relacion1))
        $relacionconstancia1 = DB::table( $relacion1['tbl_rel'])->where($relacion1['col_rel'],$modelo->id)->get();
        if(!empty($relacion2))
        $relacionconstancia2 = DB::table( $relacion2['tbl_rel'])->where($relacion2['col_rel'],$modelo->id)->get();
        if(!empty($relacion3))
        $relacionconstancia3 = DB::table( $relacion3['tbl_rel'])->where($relacion3['col_rel'],$modelo->id)->get();
       
        $codigoQR = QrCode::format('png')->size(100)->generate($qr);       
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $data = [
                 'temporal'=>$temporal,
                 'qr'=>$codigoQR,
                 'auditoria'=>$auditoria,                 
                 'accion'=>$accion, 
                 'modelo'=>$modelo,
                 'relacion1'=>$relacionconstancia1,
                 'relacion2'=>$relacionconstancia2,
                 'relacion3'=>$relacionconstancia3,
                 'relacion4'=>$relacion4,
                 'firma'=>$firma,
                 'hash'=>$hash,
                 'fechahora'=>$fechahora,
                 'estatus'=>$estatus,
                 'motivo_rechazo'=>$motivo_rechazo,
                 'firmante'=>$firmante,
                 'firmante_puesto'=>$firmante_puesto,
                ];
        $pdf->loadView('reportes.'.$nombrereporte, $data);


       
        return $pdf;
    }
	

    function fechaaletra($fecha){
       
        $diacomparecencia=explode('/',fecha($fecha));

        $dia=$diacomparecencia[0];
        $anio=$diacomparecencia[2];

        $formatterD = new NumeroALetras();
        $formatterD->apocope = true;
        $diaD = $formatterD->toString($dia);    
        $anioD = $formatterD->toString($anio);
      
        $diaMax = ucwords($diaD);            
        $diaMin = mb_convert_encoding(mb_convert_case(ucwords(strtolower($diaMax)), MB_CASE_TITLE), "UTF-8") ;       

        $anioMax = ucwords($anioD);            
        $anioMin = ucwords(strtolower($anioMax));

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                    $fecha = Carbon::parse($fecha);
                    $mes = $meses[($fecha->format('n')) - 1];
                    $fechaactual = $diaMin. 'de ' . $mes . ' del ' .  $anioMin;


        return $fechaactual;
    }
    function usuariocp($ua){
        $users=new User();
        if(getSession('cp')==2021)
        {
            $users=$users->where('cp_ua2021','LIKE','%'.$ua .'%' );
        }
        if(getSession('cp')==2022)
        {
            $users=$users->where('cp_ua2022','LIKE','%'.$ua .'%' );
        }
        if(getSession('cp')==2023)
        {
            $users=$users
            ->where('cp_ua2023','LIKE','%'.$ua .'%' );
        }
        return $users;
    }    



