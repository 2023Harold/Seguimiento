<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class PliegosContestacion extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segpliegos_observacion_contestacion';

    protected $fillable = [
        'consecutivo'
        ,'oficio_contestacion'
        ,'fecha_oficio_contestacion'
        ,'numero_oficio'
        ,'nombre_remitente'
        ,'cargo_remitente'
        ,'fecha_recepcion_oficialia'
        ,'folio_correspondencia'
        ,'fecha_recepcion_seguimiento'
        ,'nombre_archivo'
        ,'pliegosobservacion_id'
        ,'foliocrr_id'
        ,'usuario_creacion_id'
        ,'usuario_modificacion_id'
        ,'created_at'
        ,'updated_at'
    ];

    protected $dates = [
        'fecha_oficio_contestacion',
        'fecha_recepcion_oficialia',
        'fecha_recepcion_seguimiento',
        'created_at',
        'updated_at',
    ];
    public function accion()
    {
        return $this->belongsTo(AuditoriaAccion::class, 'accion_id', 'id');
    }
    public function remitentes()
    {
        return $this->hasMany(RemitentesFolio::class, 'folio_id', 'foliocrr_id');
    }
}
