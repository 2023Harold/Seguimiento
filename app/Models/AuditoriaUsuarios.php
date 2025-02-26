<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditoriaUsuarios extends Model
{
    //use HasFactory;
    use  HasFactory;
    protected $table = 'segauditoria_usuarios';
    protected $fillable = [
        'auditoria_id',
        'staff_id',
    ];
    protected $cast = [
        'created_at'=>'datetime',
        'updated_at'=> 'datetime', 
    ];

    public $timestamps = true; // Usa `created_at` y `updated_at`


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
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'AcuerdoConclusion')->orderBy('id', 'ASC');
    }

    //STAFF JURIDICO
    public function getStaffasignadoAttribute()
    {
        return User::where('unidad_administrativa_id',$this->staff_juridico_id)->where('siglas_rol','STAFF')->first();
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
            
}


