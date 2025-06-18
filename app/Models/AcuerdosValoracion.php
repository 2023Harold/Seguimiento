<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class AcuerdosValoracion extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segacuerdosvaloracion';

    protected $fillable = [
        'id',
        'folio_id',
        'auditoria_id',
        'tipo_doc',
        'numero_oficio',
        'fecha_oficio',
        'nombre_firmante',
        'cargo_firmante',
        'anexos',
        'usuario_creacion_id',
        'usuario_modificacion_id',
    ];



    protected $cast = [
        'fecha_oficio',
    ];
}
