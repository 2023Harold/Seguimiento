<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoUI extends Model
{
    //use HasFactory;
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


}
