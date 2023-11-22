<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class RecomendacionesAnexos extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segrecomendaciones_anexos';

    protected $fillable = [
        'id'
        ,'consecutivo' 
        ,'archivo'       
        ,'nombre_archivo'
        ,'recomendacion_id'
        ,'usuario_creacion_id'
        ,'usuario_modificacion_id'
        ,'created_at'
        ,'updated_at'       
    ];

    protected $dates = [        
        'created_at',
        'updated_at',        
    ];

    public function solicitud()
    {
        return $this->belongsTo(Recomendaciones::class, 'recomendacion_id', 'id');
    }
}
