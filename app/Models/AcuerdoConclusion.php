<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcuerdoConclusion extends Model
{
    //use HasFactory;

    protected $table = 'segacuerdo_conclusion';
    protected $fillable = [
        'numero_acuerdo_conclusion',
        'fecha_acuerdo_conclusion',
        'acuerdo_conclusion',
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
