<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CotejamientoController extends Controller
{
    public function cotejamiento($hash, $model)
    {
        $hash = base64_decode($hash);
        $file_path = null;
        //$constancia = ConstanciaFirmada::where('hash_xml', $hash)->first();
        //$file_path = $constancia->constancia_pdf;

        return view('cotejamiento.index', compact('file_path'));
    }
}
