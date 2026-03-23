<?php
namespace App\Http\Controllers\Cedulas;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\AuditoriaAccion;
use App\Models\Cedula;
use App\Models\Movimientos;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CedController extends Controller
{
    public function ver(AuditoriaAccion $accion)
    {
        // Asumiendo que guardas algo tipo 'archivos/cedula-OjfUKrzwbDJT.pdf' en disco public
        $relative = ltrim($accion->cedula, '/'); // por si viene con slash
        $path = Storage::disk('public')->path($relative);

        abort_unless(File::exists($path), 404, 'Archivo no encontrado');

        // BinaryFileResponse maneja Rangos y ETag/Last-Modified
        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            // NO pongas aquí Content-Length manualmente; déjalo a Symfony/servidor
            // Puedes agregar caching suave:
            // 'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}