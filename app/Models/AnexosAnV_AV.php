<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class AnexosAnV_AV extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'seganv_av_anexos';

    protected $fillable = [
        'id',
        'consecutivo',
        'archivo',
        'nombre_archivo',
        'anvav_id',
        'usuario_creacion_id',
        'usuario_modificacion_id',
    ];



    protected $cast = [
        
    ];
}
