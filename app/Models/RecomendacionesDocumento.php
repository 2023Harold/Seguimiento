<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class RecomendacionesDocumento extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segrecomendaciones_documentos';

    protected $fillable = [
        'recomendacion_id',
        'consecutivo',        
        'nombre_documento', 
        'created_at',              
        'updated_at',              
    ];

    protected $dates = [
        'created_at',
        'updated_at',        
    ];

    public function recomendacion()
    {
        return $this->belongsTo(Recomendaciones::class, 'recomendacion_id', 'id');
    }
}
