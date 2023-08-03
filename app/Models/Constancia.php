<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class Constancia extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segconstancias';

    protected $fillable = [
        'archivo_xml',
        'archivo_fir',
        'constancia_xml',
        'id_proceso_xml',
        'hash_xml',
        'constancia_pdf',
        'id_proceso_pdf',
        'hash_pdf',
        'accion',
        'accion_campo',
        'accion_id',
        'usuario_creacion_id',
        'estatus',
        'motivo_rechazo',
        'created_at',
        'updated_at',
        'ruta_cerrar'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];    
}
