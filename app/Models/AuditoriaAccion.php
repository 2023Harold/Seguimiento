<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class AuditoriaAccion extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;


    protected $table = 'segauditoria_acciones';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'consecutivo',	
        'tipo',
        'segtipo_accion_id',
        'numero',	
        'cedula',
        'accion',	
        'monto_aclarar',	
        'segauditoria_id',	
        'departamento_asignado_id',   
        'departamento_asignado',
        'reasignacion_departamento',
        'lider_asignado',
        'lider_asignado_id',
        'reasignacion_lider',
        'lider_anterior_id',
        'analista_asignado',
        'analista_asignado_id',
        'reasignacion_analista',
        'analista_anterior_id',
        'usuario_creacion_id',	
        'usuario_actualizacion_id',	
        'created_at',	
        'updated_at'
    ];

           
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at'=>'datetime',
        'updated_at'=>'datetime'
    ];       
    
    public function getDepaasignadoAttribute()
    {
        return User::where('unidad_administrativa_id',$this->departamento_asignado_id)->first();
    }

    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'id', 'segauditoria_id');
    }

    public function tipo()
    {                
        return $this->hasOne(CatalogoTipoAccion::class, 'id', 'segtipo_accion_id');
    }

    public function lider()
    {
        return $this->belongsTo(User::class, 'lider_asignado_id', 'id');
    }

    public function analista()
    {
        return $this->belongsTo(User::class, 'analista_asignado_id', 'id');
    }
    
}
