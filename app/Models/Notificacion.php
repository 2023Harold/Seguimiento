<?php

namespace App\Models;

use App\Models\CatalogoUnidadesAdministrativas;
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
		'cp',
		'llave',
		'url',
		'auditoriacp_id',
		'equipo_id',
		'destinatario',
    ];

    protected $dates = [
        'fecha_muestra_inicio',
        'fecha_muestra_fin',
        'created_at',
        'updated_at',
    ];

    public function unidadAdministrativa()
    {
        return $this->belongsTo(CatalogoUnidadesAdministrativas::class, 'unidad_administrativa_id', 'id');
    }
    // CORRECTO — es belongsTo porque la FK está en segnotificaciones

    public function usuarioDestinatario()
    {
        return $this->belongsTo(User::class, 'destinatario_id', 'id');
    }

    public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion_id', 'id');
    }

    public function usuarioActualizacion()
    {
        return $this->belongsTo(User::class, 'usuario_actualizacion_id', 'id');
    }

    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'auditoriacp_id','id');
    }


}
