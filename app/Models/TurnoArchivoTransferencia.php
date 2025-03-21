<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoArchivoTransferencia extends Model
{
    use HasFactory;
    protected $table = 'segturno_archivo_trasferencia';
    protected $fillable = [
        'numero_transferencia',
        'inventario_transferencia',
        'fecha_transferencia',
        'tiempo_resguardo',
        'clave_topografica',
        'auditoria_id',
        'fase_autorizacion',
        'nivel_autorizacion',
        'usuario_creacion_id',
        'usuario_modificacion_id',
    ];
    protected $cast=[
    'fecha_trasferencia'=>'date',

    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    '' => '',

    ];
    public function getDepaasignadoAttribute()
    {
        return User::where('unidad_administrativa_id',$this->departamento_asignado_id)->first();
    }
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
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'TurnoTransferencia')->orderBy('id', 'ASC');
    }

}
