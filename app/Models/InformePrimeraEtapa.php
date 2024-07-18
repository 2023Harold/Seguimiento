<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformePrimeraEtapa extends Model
{
    // use HasFactory;
    // protected $fillable = ['numero_ordenauditoria','fecha_notificacion_oficio','numero_oficio_entro'];
    protected $table = 'seginforme_primeraetapa';
    protected $fillable = [
        'numero_ordenauditoria',
        'fecha_notificacion_oficio',
        'numero_oficio_entro',
        'acta_reunion_resultados',
        'fecha_notificación',
        'informe_seguimiento',
        'fojas_utiles',
        'clave_accion_pliego',
        'auditoria_id'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at'=>'datetime',
        'updated_at'=>'datetime',
        'fecha_termino_recomendacion'=>'datetime',
    ];

    public function getDepaasignadoAttribute()
    {
        return User::where('unidad_administrativa_id',$this->departamento_asignado_id)->first();
    }

    // public function informe()
    // {
    //     return $this->belongsTo(InformePrimeraEtapa::class, 'numero_ordenauditoria','id');
    // }
    // public function analista()
    // {
    //     return $this->belongsTo(User::class, 'analista_asignado_id', 'id');
    // }
    // public function movimientos()
    // {
    //     return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Revisión Acción Registro Auditoría')->orderBy('id', 'ASC');
    // }

}
