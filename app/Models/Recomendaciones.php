<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;


class Segpras extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'Recomendaciones';

    protected $fillable = [
        'consecutivo',
        'fecha_vencimiento',
        'nombre_responsable',
        'cargo_responsable',
        'departamento_responsable_id',
        'segcatunidad_administrativas',
        'auditoria_id',
        'segauditorias',
        'usuario_creacion_id',
        'segusers',
        'usuario_modificacion_id',
        'accion_id',//accion_id
        'auditoria_id',
        'entidad_fiscalizable_id',
        'usuario_firmante_id',
        'usuario_creacion_id',
        'usuario_modificacion_id',
        'usuario_firmante_id',
        'nivel_autorizacion',
        'accion',
        'analisis',
        'oficio_contestacion',
        'conclusion'
        
            
    ];

    protected $dates = [
        'fecha_acuse_oficio',
        'fecha_elaboracion_oficio',
        'fecha_recepcion',
        'fecha_acuse',
        'fecha_proxima_seguimiento'
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
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'PRASAuditoria Turno')->orderBy('id', 'ASC');
    }
   
}

class Recomendaciones extends Model
{
    use HasFactory;
}
