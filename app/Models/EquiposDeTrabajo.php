<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquiposDeTrabajo extends Model
{
    //use HasFactory;
    use  HasFactory;
    protected $table = 'segequipos_trabajo';
    protected $fillable = [
        'equipo_name',
        'consecutivo',
        'auditoria_id',
        'departamento_encargado',
        'departamento_encargado_id',
        'estatus',
        'usuario_creacion_id',
        'usuario_actualizacion_id',
    ];
    protected $cast = [
        'created_at'=>'datetime',
        'updated_at'=> 'datetime', 
    ];

    public $timestamps = true; 


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


}


