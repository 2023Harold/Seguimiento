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
        'fecha_turno_ui',
        'turno_ui',
        'auditoria_id',
    ];
protected $cast = [
    'created_at'=>'datetime',
    'updated_at'=> 'datetime',    

];

public function getDepaasignadoAttribute()
    {
        return User::where('unidad_administrativa_id',$this->departamento_asignado_id)->first();
    }


}
