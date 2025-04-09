<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class FolioCRR extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segfolios_correspondencia';

    protected $fillable = [
        'id',
        'folio',
        'fecha_recepcion_oficialia',
        'fecha_recepcion_us',
        'auditoria_id',
        'oficio_contestacion_general',
        'fecha_oficio_contestacion',
        'numero_oficio',
        'nombre_remitente',
        'cargo_remitente',
        'solicitudes',
        'presentacion',
        'usuario_creacion_id',
        'usuario_modificacion_id'
    ];



    protected $cast = [
        'fecha_recepcion_oficialia',
        'fecha_recepcion_us',

    ];
}
