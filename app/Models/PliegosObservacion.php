<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class PliegosObservacion extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'Segpliegos_observacion';

    protected $fillable = [

        'id',
        'oficio_atencion',
        'fecha_oficio_atencion',
        'accion_id',
        'usuario_creacion_id',
        'usuario_modificacion_id',
        'constancia',
        'fase_autorizacion',
        'segauditorias',
        'auditoria_id',
        'entidad_fiscalizable_id',
        'usuario_firmante_id',
        'nivel_autorizacion',
        'accion',
        'calificacion_atencion',
        'analisis',
        'fase_revision',
        'oficio_contestacion',
        'fase_autorizacion',
        'constancia_autorizacion',
        'oficio_comprobante',
        'fecha_comprobante',
        'oficio_acuse',
        'fecha_acuse',
        'conclusion',
        'concluido'
    ];

    protected $dates = [
        'fecha_acuse_oficio',
        'fecha_elaboracion_oficio',
        'fecha_recepcion',
        'fecha_proxima_seguimiento',
        'fecha_comprobante',
        'fecha_acuse'
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
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Pliegos Observación')->orderBy('id', 'ASC');
    }

    public function documentos()
    {
        return $this->hasMany(PliegosDocumento::class, 'accion_id','id');
    }

}
