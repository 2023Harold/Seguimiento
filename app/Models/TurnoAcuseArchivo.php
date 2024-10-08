<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoAcuseArchivo extends Model
{
    use HasFactory;
    protected $table = 'segturno_acuse_envio_archivo'; 
    protected $fillable = [
        'numero_turno_archivo',
        'fecha_turno_archivo',
        'turno_archivo',
        'auditoria_id',
        'legajos_tecnico_archivo',
        'fojas_tecnico_archivo',
        'legajos_seg_archivo',
        'fojas_seg_archivo',
        'fecha_notificacion_archivo',
        

    ];
    protected $cast=[
    'fecha_turno_archivo'=>'date',        
    'fecha_notificacion_archivo'=>'date',        
    'created_at' => 'datetime',
    'updated_at' => 'datetime',

    ];
    public function getDepaasignadoAttribute()
    {
        return User::where('unidad_administrativa_id',$this->departamento_asignado_id)->first();
    }



}
