<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class PliegosObservacion extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segpliegos_observacion';

    protected $fillable = [
        'id',
        'monto_solventado', 
        'analisis', 
        'fase_revision', 
        'conclusion', 
        'concluido', 
        'constancia', 
        'fase_autorizacion', 
        'nivel_autorizacion', 
        'constancia_autorizacion', 
        'auditoria_id', 
        'accion_id', 
        'usuario_creacion_id', 
        'usuario_modificacion_id', 
        'created_at', 
        'updated_at', 
        'calificacion_sugerida', 
        'calificacion_atencion'
    ];

    protected $dates = [
        'created_at', 
        'updated_at', 
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
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Pliegos de observación')->orderBy('id', 'ASC');
    }

    public function documentos()
    {
        return $this->hasMany(PliegosDocumento::class, 'accion_id','id');
    }

}
