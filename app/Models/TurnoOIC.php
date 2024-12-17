<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoOIC extends Model
{
    use HasFactory;
    protected $table = 'segturno_oic';
    protected $fillable = [
        'numero_turno_oic',
        'fecha_turno_oic',
        'turno_oic',
        'auditoria_id',
        'nombre_titular_oic',
        'cargo_titular_oic',
        'domicilio_oic',
        'fecha_envio',
        'acuse_notificacion',
        'fecha_notificacion',        
        'fase_autorizacion',
        'nivel_autorizacion',
        'usuario_creacion_id',
        'usuario_modificacion_id',
    ];
protected $cast = [
    'fecha_turno_oic'=>'date',
    'fecha_envio'=>'date',
    'fecha_notificacion'=>'date',
    'created_at'=>'datetime',
    'updated_at'=> 'datetime',    
];

public function getDepaasignadoAttribute()
    {
        return User::where('unidad_administrativa_id',$this->departamento_asignado_id)->first();
    }
    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'auditoria_id', 'id');
    }   
    public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }
    public function usuarioActualizacion()
    {
        return $this->belongsTo(User::class, 'usuario_modificacion_id');
    }
    public function movimientos()
    {
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'TurnoOIC')->orderBy('id', 'ASC');
    }

}
