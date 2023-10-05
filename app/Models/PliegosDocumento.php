<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class PliegosDocumento extends Model
{

    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segpliegos_observacion_documentos';

    protected $fillable = [
        'pliegosobservacion_id',
        'consecutivo',
        'nombre_documento',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function accion()
    {
        return $this->belongsTo(AuditoriaAccion::class, 'accion_id', 'id');
    }
}
