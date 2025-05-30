<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class CatalogoUMAS extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;


    protected $table = 'segcatumas';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ejercicio',
        'cant_por_dia',
        'cant_multiplicada',
        'fecha_publicacion'=>'datetime',
        'fecha_vigencia'=>'datetime',
        'texto',
        'created_at'=>'datetime',
        'updated_at'=>'datetime',
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
    
    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'auditoria_id', 'id');
    }

    public function usuarioCreacion()
    {
        return $this->belongsTo('App\Models\User', 'usuario_creacion_id');
    }

    public function usuarioActualizacion()
    {
        return $this->belongsTo('App\Models\User', 'usuario_modificacion_id');
    }

    public function userFirmante()
    {
        return $this->belongsTo('App\Models\User', 'usuario_firmante_id');
    }
}
