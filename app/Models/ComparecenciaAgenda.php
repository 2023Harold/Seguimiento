<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class ComparecenciaAgenda extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;


    protected $table = 'segagenda_comparecencias';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [       
        'id_comparecencia',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'sala',
        'usuario_creacion_id',
        'usuario_actualizacion_id',        
        'lugar_comparecencia',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'fecha'=>'datetime',       
    ];
    public $timestamps = false;
    
    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'id_comparecencia', 'id');
    }
    public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id');
    }

    public function usuarioActualizacion()
    {
        return $this->belongsTo(User::class, 'usuario_actualizacion_id');
    }
}
