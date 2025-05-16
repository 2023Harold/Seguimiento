<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class RemitentesFolio extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segfolios_correspondencia_remitentes';

    protected $fillable = [
        'id',
        'folio_id',
        'nombre_remitente',
        'cargo_remitente',
        'domicilio_remitente',
        'usuario_creacion_id',
        'usuario_modificacion_id',
    ];



    protected $cast = [
        
    ];
}
