<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class SolicitudesdeAclaracion extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segsolicitudes_aclaracion';

    protected $fillable = [
        
        'id',
        'documento_solventacion',
        'fecha_vencimiento',
        'nombre_archivo',
        'cumple',
        'accion_id',        
        'usuario_creacion_id',
        'usuario_modificacion_id',
        
    ];

    protected $dates = [
        'fecha_oficio',
        'created_at',
        
    ];

    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'auditoria_id', 'id');
    }

    public function accion()
    {
        return $this->belongsTo(AuditoriaAccion::class, 'accion_id', 'id');
    }

    public function userCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Recomendación')->orderBy('id', 'ASC');
    }
}
