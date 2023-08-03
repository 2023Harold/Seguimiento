<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArchivoController extends Controller
{
    public function upload(Request $request)
    {
        reset($_FILES);
        $field = key($_FILES);
        if ($request->hasFile($field)) {
            $file = $request->file($field);
            $pathandfilename = $file->store('public/temporales', 'local');
            $pathandfilename = Str::replaceFirst('public', 'storage', $pathandfilename);
            $output = ['fileName' => $pathandfilename, 'uploadLink' => asset($pathandfilename)];
            $preview[] = $output['uploadLink'];
            $config[] = [
                'key' => $output['fileName'],
                'caption' => $output['fileName'],
                'size' => 10,
                'filename' => $output['fileName'],
                'downloadUrl' => $output['uploadLink'],
                'url' => 'http://localhost/delete.php',
            ];
            $out = ['initialPreview' => $preview, 'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
            if (!empty($errors)) {
                $img = count($errors) === 1 ? 'file "' . $errors[0] . '" ' : 'files: "' . implode('", "', $errors) . '" ';
                $out['error'] = 'Oh snap! We could not upload the ' . $img . 'now. Please try again later.';
            }

            return $out;
        }
    }

    public function show($archivo)
    {
        
        $archivo=str_replace('|','/',$archivo);
        //dd($archivo);
        $file = Storage::cloud()->get($archivo);        

        return  Response($file, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
