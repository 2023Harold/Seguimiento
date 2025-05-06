<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class Revisiones extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segrevisiones';

    protected $fillable = [
        'de_usuario_id',
        'para_usuario_id', 
        'comentario',
        'accion',
        'accion_id',
        'estatus',
        'notificacion_titular',
        'notificacion_director',
        'notificacion_jefe',
        'notificacion_lider',
        'notificacion_analista',            
        'id_revision', 
        'usuario_creacion_id',
        'usuario_modificacion_id',
        'created_at',
        'updated_at', 
        'muestra_rev',           
        'tipo',           
    ];

    protected $dates = [
        'created_at',
        'updated_at',        
    ];

    public function accion()
    {
        return $this->belongsTo(AuditoriaAccion::class, 'accion_id', 'id');
    }

    public function deusuario()
    {
        return $this->belongsTo(User::class, 'de_usuario_id');
    }

    public function respuestas()
    {
        return $this->hasMany(Revisiones::class, 'id_revision','id');
    }
}
