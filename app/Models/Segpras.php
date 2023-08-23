<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;


class Segpras extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'Segpras';

    protected $fillable = [
        'nombre_titular_oic',
        'oficio_remision',
        'fecha_proxima_seguimiento',
        'fecha_acuse_oficio',
        'numero_oficio',
        'fecha_elaboracion_oficio',
        'anexos',
        'copias_conocimiento',
        'notificacion_estrados',
        'calle',
        'numero_domicilio',
        'colonia',
        'municipio',
        'entidad_federativa',
        'codigo_postal',
        'fase_autorizacion',
        'motivo_rechazo',
        'constancia_turno',
        'oficio_comprobante',
        'fecha_recepcion',
        'oficio_acuse',
        'fecha_acuse',
        'estatus_llenado',
        'conclusion_seguimientos',
        'accion_id',//accion_id
        'auditoria_id',
        'entidad_fiscalizable_id',
        'usuario_firmante_id',
        'usuario_creacion_id',
        'usuario_modificacion_id',
        'usuario_firmante_id'
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
