<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class Cedula extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segcedulas';

    protected $fillable = [
        'auditoria_id',
        'cedula_tipo',
        'cedula',
        'constancia',
        'fase_autorizacion',
        'nivel_autorizacion',
        'usuario_creacion_id',
        'usuario_modificacion_id',        
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function userCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }


}
