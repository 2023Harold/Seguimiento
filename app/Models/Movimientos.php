<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class Movimientos extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;


    protected $table = 'segmovimientos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tipo_movimiento',
        'accion',
        'accion_id',
        'firmante_id',
        'pdf',
        'estatus',
        'motivo_rechazo',
        'usuario_asignado_id',
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
    
    public function userCreacion()
    {
        return $this->belongsTo('App\Models\User', 'usuario_creacion_id');
    }

    public function userActualizacion()
    {
        return $this->belongsTo('App\Models\User', 'usuario_actualizacion_id');
    }

    public function userAsignado()
    {
        return $this->belongsTo('App\Models\User', 'usuario_asignado_id');
    }

    public function userFirmante()
    {
        return $this->belongsTo('App\Models\User', 'firmante_id');
    }
}
