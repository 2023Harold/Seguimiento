<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class SolicitudesAclaracion extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segsolicitudes_aclaracion';

    protected $fillable = [        
        'id',
        'oficio_atencion',
        'fecha_oficio_atencion',
        'cumple',
        'accion_id',        
        'usuario_creacion_id',
        'usuario_modificacion_id',
        'constancia',
        'fase_autorizacion',
        'nivel_autorizacion',
        'concluido',
        'monto_solventado'        
    ];

    protected $dates = [
        'fecha_oficio_atencion',
        'created_at',
        'updated_at'
    ];

   
    public function accion()
    {
        return $this->belongsTo(AuditoriaAccion::class, 'accion_id', 'id');
    }

    public function userCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }

    public function userModificacion()
    {
        return $this->belongsTo(User::class, 'usuario_modificacion_id');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Solicitud de Aclaración')->orderBy('id', 'ASC');
    }
}
