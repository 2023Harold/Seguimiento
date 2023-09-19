<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class RecomendacionesContestacion extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segrecomendaciones_contestaciones';

    protected $fillable = [
        'consecutivo',
        'oficio_contestacion',
        'fecha_oficio_contestacion',
        'nombre_archivo',
        'recomendacion_id',
        'usuario_creacion_id',
        'usuario_modificacion_id',
        'created_at',
        'updated_at',       
    ];

    protected $dates = [
        'fecha_oficio_contestacion',
        'created_at',
        'updated_at',        
    ];

    public function accion()
    {
        return $this->belongsTo(AuditoriaAccion::class, 'accion_id', 'id');
    }
}
