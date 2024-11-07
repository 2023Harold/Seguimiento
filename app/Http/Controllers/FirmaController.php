<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class FirmaController extends Controller
{
     /*
    URL Decodificar Certificado
    Pruebas:   https://feb.seguridata.com/ws-rest-seguritools/
    Producción:https://firma.osfem.gob.mx/signtools/
    */
    public $urlDecodeCert = 'https://firma.osfem.gob.mx/signtools/';

    /*
    URL SignnRest Proceso de firma
    Pruebas:   https://feb.seguridata.com/sign_rest/
    Producción:https://firma.osfem.gob.mx/sign_rest/
    */
    public $urlSingRest = 'https://firma.osfem.gob.mx/sign_rest/';

    /*
     URL SignnRest Estampa de Tiempo
    Pruebas:   https://feb.seguridata.com/snotary-rest-ws/
    Producción:
    */
    public $urlSnotary = 'https://firma.osfem.gob.mx/snotary-rest-ws/';

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
                            'user' => 'sectech',
                            'password' => '53ct3ch.21', ]);
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
            'Authorization' => 'Basic c2VjdGVjaDo1M2N0M2NoLjIx',
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
        $registro_id = substr(base64_decode($request->datosConstancia['where']), 5, -5);
        $qr = route('cotejamiento', [base64_encode($hash), 'Auditoria_Fuera']);
        $parametros = $request->datosConstancia['parametros'];
        $parametros = Arr::add($parametros, 'estado', (str_contains($request->estatus, 'echazad') ? 'Rechazado' : 'Autorizado'));
        $parametros = Arr::add($parametros, 'motivo_rechazo', $request->motivo_rechazo);
        $parametros = Arr::add($parametros, 'firma', $xmlFirma);
        $parametros = Arr::add($parametros, 'fechahora', $fechaexpedicion);
        $parametros = Arr::add($parametros, 'hash', $hashFirma);
        $parametros = Arr::add($parametros, 'qr', $qr);
        $parametros = Arr::add($parametros, 'where', $registro_id);
        $nombreConstancia = $request->datosConstancia['nombreConstancia'];
        $archivorutapdf = reporte($registro_id, $nombreConstancia, $parametros, 'pdf');
        $resultado = Arr::add($resultado, 'storage', $archivorutapdf);
        
        $pdf64 = chunk_split(base64_encode(file_get_contents(base_path().'/public/'.$archivorutapdf)));
        $resultado = Arr::add($resultado, 'pdfBase64', $pdf64);

         $multiSignedMessageInitRequestPDF = [
            'data' => $nombreConstancia.'.pdf',
            'document2Sign' => [
                'base64' => true,
                'data' => $pdf64,
                'name' => $nombreConstancia.'.pdf',
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
