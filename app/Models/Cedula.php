<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Cedula extends Model
{
    // use HasFactory;
    // use RoutesWithFakeIds;
     use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;

    protected $table = 'segcedulas';

    protected $fillable = [
        'auditoria_id',
        'cedula_tipo',
        'cedula',
        'constancia',
        'fase_autorizacion',
        'nivel_autorizacion',
        'usuario_creacion_id',
        'usuario_modificacion_id',        
        'created_at',
        'updated_at',
        'cedula_cargada',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
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
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Cedula')->orderBy('id', 'ASC');
    }


}
