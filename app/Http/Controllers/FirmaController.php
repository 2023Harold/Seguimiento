<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;


class FirmaController extends Controller
{
     /*
    URL Decodificar Certificado
    Pruebas:   https://feb.seguridata.com/ws-rest-seguritools/
    Producción:https://www.plataformaosfem.mx/signtools/
    */
    public $urlDecodeCert = 'https://firmatest.osfem.gob.mx/ws-rest-seguritools/';

    /*
    URL SignnRest Proceso de firma
    Pruebas:   https://feb.seguridata.com/sign_rest/
    Producción:https://www.plataformaosfem.mx/sign_rest/
    */
    public $urlSingRest = 'https://firmatest.osfem.gob.mx/sign_rest_osfem/';

    /*
     URL SignnRest Estampa de Tiempo
    Pruebas:   https://feb.seguridata.com/snotary-rest-ws/
    Producción:
    */
    public $urlSnotary = 'https://firmatest.osfem.gob.mx/snotary-rest-ws/';

    public function firmar(Request $request)
    {
        $resultado = [];
        $cer64 = $request->certificado;
        $archivo64 = $request->archivo;

        /* Obtener datos del .cert */
        $dataArrayCert = ['certificate2Decode' => $cer64];
        $request['dataCert'] = $dataArrayCert;

        $responseCert = Http::withHeaders([
            'Access-Control-Allow-Origin' => '*',
        ])
        ->withOptions(['verify' => false])
        ->post($this->urlDecodeCert.'decodeCertificate', $dataArrayCert);

        $nombre = $responseCert->json()['subjectNames']['CN'];
        $numeroSerie = $responseCert->json()['serialNumber'];
        $request['nombre'] = $nombre;
        $request['numeroSerie'] = $numeroSerie;

        /* Autenticación */
        $response = Http::withHeaders(['Access-Control-Allow-Origin' => '*'])
                        ->withOptions(['verify' => false])
                        ->asForm()
                        ->post($this->urlSingRest.'user', [
                            'user' => 'osfem',
                            'password' => '12121212qw.', ]);
        //'password' => '53ct3ch.21', ]);

        $token = $response->json()['token'];
        $request['token'] = $token;

        /* Inicio del proceso de firma para el xml con el metodo multilateral */
        $multiSignedMessageInitRequest = [
            'data' => 'archivo.pdf',
            'document2Sign' => [
                'data' => 'constanciaauditoriafuerapaamandato.xml',
                'base64' => true,
                'data' => $archivo64,
                'name' => 'constanciaauditoriafuerapaamandato.xml',
            ],
            'pdfPassword' => '',
            'hashAlg' => 'SHA256',
            'processType' => 'CMS_WITH_CONTENT',
            'totalSigners' => 1,
        ];

        $responseMultiLateral = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Authorization' => $token,
        ])
        ->withOptions(['verify' => false])
        ->post($this->urlSingRest.'multilateral/', $multiSignedMessageInitRequest);

        $mulLatId = $responseMultiLateral->json()['multilateralId'];
        $hash = $responseMultiLateral->json()['hash'];

        $resultado = Arr::add($resultado, 'nombreCertificado', $nombre);
        $resultado = Arr::add($resultado, 'numeroSerie', $numeroSerie);
        $resultado = Arr::add($resultado, 'token', $token);
        $resultado = Arr::add($resultado, 'multilateralId', $mulLatId);
        $resultado = Arr::add($resultado, 'hash', $hash);
        $resultado = Arr::add($resultado, 'resultado', $responseMultiLateral->json());

        return response()->json($resultado);
    }

    public function finalizarfirma(Request $request)
    {
        $resultado = [];
        $datosXML = [];

        $multilateralId = $request->multilaterald;
        $hash = $request->hash;
        $tokenfirma = $request->tokenfirma;
        $numeroserie = $request->numeroserie;
        $cer64 = $request->cer64;
        $nombre = $request->nombrecertificado;
        $data = $request->data;
        $auditoria_id = $request->data;
      
        
        

        /* Actualización de la firma en el xml con el método multilateral/update */
        $multiSignedMessageUpdtRequest = [
            'serial' => $numeroserie,
            'signedMessage' => [
                'base64' => true,
                'data' => $data,
                'name' => 'firma.p7',
            ],
        ];
        $responseMultiLateralUpdate = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Authorization' => $tokenfirma,
        ])->withOptions(['verify' => false])
          ->post($this->urlSingRest.'multilateral/update/'.$multilateralId.'/', $multiSignedMessageUpdtRequest);

        /* Finalización del proceso de la firma en el xml con el método multilateral/finalize */
        $responseMultiLateralFinalize = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Authorization' => $tokenfirma,
        ])->withOptions(['verify' => false])
          ->get($this->urlSingRest.'multilateral/finalize/'.$multilateralId.'/true');

        $resultado = Arr::add($resultado, 'resultado', $responseMultiLateralFinalize->json());
        $resultado = Arr::add($resultado, 'hash', $hash);

        /* Obtener estampa de tiempo del xml firmado */
        $estampaTiempoRequest = [
            'algorithm' => 'SHA256',
            'value' => $hash,
        ];
        $responseEstampaTiempo = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Authorization' => 'Basic b3NmZW06MTIxMjEyMTJRdy4=',
        ])->withOptions(['verify' => false])
          ->post($this->urlSnotary.'getTimeStamp?appendData=true&doHash=false&valueIsBase64=false', $estampaTiempoRequest);

        $datosXML = Arr::add($datosXML, 'acuse_xml', $responseMultiLateralFinalize->json()[0]['data']);
        $datosXML = Arr::add($datosXML, 'constancia_xml', $responseMultiLateralFinalize->json()[1]['data']);
        $datosXML = Arr::add($datosXML, 'id_proceso_xml', $multilateralId);
        $datosXML = Arr::add($datosXML, 'hash_xml', $hash);

        $resultado = Arr::add($resultado, 'datosXML', $datosXML);
        $resultado = Arr::add($resultado, 'estampaTiempo', $responseEstampaTiempo->json());
        $hashFirma = $responseEstampaTiempo->json()['data']['hash'];
        $xmlFirma = $responseEstampaTiempo->json()['data']['rsaSignature'];
        $fechaexpedicion = $responseEstampaTiempo->json()['data']['expeditionDate'];
       

        /* Se crea la versión imprimible(pdf) del xml */ 
        $qr = route('cotejamiento', [base64_encode($hash), 'Constancia']);      

        $pdf64=reportepdf($request->datosConstancia['nombrereporte'],0,$qr,
        $request->datosConstancia['auditoriaseleccionada'],
        $request->datosConstancia['accionseleccionada'],
        $request->datosConstancia['modelo_principal'],
        $request->datosConstancia['relacion1'],
        $request->datosConstancia['relacion2'],
        $request->datosConstancia['relacion3'],
        $request->datosConstancia['relacion4'],
        $data,
        $hashFirma,
        $fechaexpedicion,
        (str_contains($request->estatus, 'echazad') ? 'Rechazado' : 'Autorizado'),
        $request->motivo_rechazo,
        $request->datosConstancia['firmante'],
        $request->datosConstancia['firmante_puesto'],
    );

        $resultado = Arr::add($resultado, 'pdfBase64', $pdf64);


        $multiSignedMessageInitRequestPDF = [
            'data' => $request->datosConstancia['nombrereporte'].'.pdf',
            'document2Sign' => [
                'base64' => true,
                'data' => $pdf64,
                'name' => $request->datosConstancia['nombrereporte'].'.pdf',
            ],
            'pdfPassword' => '',
            'hashAlg' => 'SHA256',
            'processType' => 'PDF',
            'totalSigners' => 1,
        ];
        $responseMultiLateralPDF = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Authorization' => $tokenfirma,
        ])
        ->withOptions(['verify' => false])
        ->post($this->urlSingRest.'multilateral/', $multiSignedMessageInitRequestPDF);
        $mulLatIdPDF = $responseMultiLateralPDF->json()['multilateralId'];
        $hashPDF = $responseMultiLateralPDF->json()['hash'];
        $resultado = Arr::add($resultado, 'mulLatIdPDF', $mulLatIdPDF);

        /* Se obtiene el hash con el método multilateral/getHash para la versión imprimible(PDF) */
        $multiGetHashRequestPDF = [
            'idKey' => null,
            'location' => null,
            'signerName' => $nombre,
            'signatureReason' => ' ',
            'signatureImage' => null,
            'signerCertificate' => [
                'base64' => true,
                'sequence' => 0,
                'info' => '',
                'evidenceType' => 'EVIDENCE_TYPE',
                'data' => $cer64,
            ],
            'biometricData' => null,
        ];
        $responseMultiLateralGetHashPDF = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Authorization' => $tokenfirma,
        ])
        ->withOptions(['verify' => false])
        ->post($this->urlSingRest.'multilateral/getHash/'.$mulLatIdPDF.'/', $multiGetHashRequestPDF);

        $hashPDF = $responseMultiLateralGetHashPDF->json()['hash'];
        $resultado = Arr::add($resultado, 'multilateralIdPDF', $mulLatIdPDF);
        $resultado = Arr::add($resultado, 'hashPDF', $hashPDF);

        return response()->json($resultado);
    }

    public function finalizarfirmapdf(Request $request)
    {
        $resultado = [];
        $datosPDF = [];

        $multilateraldPDF = $request->multilateraldPDF;
        $tokenfirma = $request->token;
        $numeroserie = $request->numeroserie;
        $dataPDF = $request->dataPDF;
        $hashPDF = $request->hashPDF;

        /* Actualización de la firma en el versión imprimible(PDF) con el método multilateral/update */
        $multiSignedMessageUpdtRequest = [
            'serial' => $numeroserie,
            'signedMessage' => [
                'base64' => true,
                'data' => $dataPDF,
                'name' => 'firma.p7',
            ],
        ];
        $responseMultiLateralUpdatePDF = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Authorization' => $tokenfirma,
        ])->withOptions(['verify' => false])
          ->post($this->urlSingRest.'multilateral/update/'.$multilateraldPDF.'/', $multiSignedMessageUpdtRequest);

        /* Finalización del proceso de la firma en el xml con el método multilateral/finalize */
        $responseMultiLateralFinalizePDF = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Authorization' => $tokenfirma,
        ])->withOptions(['verify' => false])
          ->get($this->urlSingRest.'multilateral/finalize/'.$multilateraldPDF.'/true');

        $datosPDF = Arr::add($datosPDF, 'acuse_pdf', $responseMultiLateralFinalizePDF->json()[0]['data']);
        $datosPDF = Arr::add($datosPDF, 'id_proceso_pdf', $multilateraldPDF);
        $datosPDF = Arr::add($datosPDF, 'hash_pdf', $hashPDF);

        $resultado = Arr::add($resultado, 'datosPDF', $datosPDF);
        $resultado = Arr::add($resultado, 'resultado', $responseMultiLateralFinalizePDF->json());

        return response()->json($resultado);
    }
}
