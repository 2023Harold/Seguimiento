<?php

namespace App\Models;

use App\Models\Catalogo\CatUnidadAdministrativa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class Notificacion extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segnotificaciones';

    protected $fillable = [
        'titulo',
        'mensaje',
        'fecha_muestra_inicio',
        'fecha_muestra_fin',
        'estatus',
        'unidad_administrativa_id',
        'destinatario_id',
        'usuario_creacion_id',
        'usuario_actualizacion_id',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'fecha_muestra_inicio',
        'fecha_muestra_fin',
        'created_at',
        'updated_at',
    ];

    public function unidadAdministrativa()
    {
        return $this->hasOne(CatUnidadAdministrativa::class, 'unidad_administrativa_id', 'id');
    }

    public function destinatario()
    {
        return $this->hasOne(User::class, 'destinatario_id', 'id');
    }

    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'segauditoria_id','id');
    }
}