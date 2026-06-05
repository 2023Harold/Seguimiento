<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class TurnoContestacion extends Model
{
    use HasFactory, RoutesWithFakeIds;
    protected $table = 'segturno_contestaciones';
    protected $fillable = [
        'id',
        'turno_id',
        'tipo_turno',
        'archivo_contestacion',
        'fecha_notificacion',
        'fecha_recepcion',
        'observaciones',
        'usuario_creacion_id',
        'usuario_modificacion_id',
    ];

protected $cast = [
    'fecha_notificacion'=>'date',
    'fecha_recepcion'=>'date',
    'created_at'=>'datetime',
    'updated_at'=> 'datetime',
];


    public function turnoOIC()
    {
        return $this->belongsTo(TurnoOIC::class, 'turno_id', 'id')->where('tipo_turno', 'TurnoOIC');
    }
    public function turnoUI()
    {
        return $this->belongsTo(TurnoUI::class, 'turno_id', 'id')->where('tipo_turno', 'TurnoUI');
    }
    public function turnoAcuseEnvioArchivo()
    {
        return $this->belongsTo(TurnoAcuseArchivo::class, 'turno_id', 'id')->where('tipo_turno', 'TurnoAcuseArchivo');
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
