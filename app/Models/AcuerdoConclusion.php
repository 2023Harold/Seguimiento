<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class AcuerdoConclusion extends Model
{
    //use HasFactory;
    use  HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;
    protected $table = 'segacuerdo_conclusion';
    protected $fillable = [
        'numero_acuerdo_conclusion',
        'fecha_acuerdo_conclusion',
        'acuerdo_conclusion',
        'auditoria_id',
        'nombre_titular',
        'cargo_titular',
        'domicilio',
		'numero_oficio',
		'fecha_oficio',
        'usuario_creacion_id',
        'usuario_asignado_id',
        'usuario_modificacion_id',
        'fase_autorizacion',
        'nivel_autorizacion',
        'tipo',

    ];
protected $cast = [
    'created_at'=>'datetime',
    'updated_at'=> 'datetime',
	'fecha_oficio'=> 'date',
	'fecha_acuerdo_conclusion'=> 'date',
];
public function getDepaasignadoAttribute()
    {
        return User::where('unidad_administrativa_id',$this->departamento_asignado_id)->first();
    }
    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'auditoria_id', 'id');
    }
    public function movimientos()
    {
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'AcuerdoConclusion')->orderBy('id', 'ASC');
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
