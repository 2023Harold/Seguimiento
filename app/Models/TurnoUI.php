<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class TurnoUI extends Model
{
    use HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;
    protected $table = 'segturno_ui';
    protected $fillable = [
        'numero_turno_ui',
        'fecha_turno_oi',
        'turno_ui',
        'auditoria_id',
        'legajos_tecnico',
        'fojas_tecnico',
        'legajos_seg',
        'fojas_seg',
        'fecha_notificacion_ui',
        'fase_autorizacion',
        'nivel_autorizacion',
        'usuario_creacion_id',
        'usuario_modificacion_id'
    ];
protected $cast = [
    'fecha_turno_oi'=>'date',
    'fecha_notificacion_ui'=>'date',
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
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'TurnoUI')->orderBy('id', 'ASC');
    }

}
