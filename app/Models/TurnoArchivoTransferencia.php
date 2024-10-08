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


}
