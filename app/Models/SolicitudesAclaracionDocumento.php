<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class SolicitudesAclaracionDocumento extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segsolicitudes_aclaracion_doc';

    protected $fillable = [
        'solicitud_aclaracion_id',
        'consecutivo',        
        'nombre_archivo', 
        'created_at',              
        'updated_at',              
    ];

    protected $dates = [
        'created_at',
        'updated_at',        
    ];

    public function solicitud()
    {
        return $this->belongsTo(SolicitudesAclaracion::class, 'solicitud_aclaracion_id', 'id');
    }
}
