<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class InformePrimeraEtapa extends Model
{
     use HasFactory;
	 use RoutesWithFakeIds;
    // protected $fillable = ['numero_ordenauditoria','fecha_notificacion_oficio','numero_oficio_entro'];
    protected $table = 'seginforme_primeraetapa';
    protected $fillable = [
        'numero_informe',
        'fecha_informe',
        'informe',
        'auditoria_id',
        'nombre_titular_informe',
        'cargo_titular_informe',
        'domicilio_informe',
        'numero_fojas',
        'acuse_notificacion',
        'fecha_notificacion',
		'acuse_envio',
        'fecha_acuse_envio',
        'tipo',
        'fase_autorizacion',
        'nivel_autorizacion',
        'usuario_creacion_id',
        'usuario_modificacion_id',
        'periodo_gestion',
        
        'informe_acuse',
        'fecha_informe_acuse',
		'informe_comprobante',
        'fecha_informe_comprobante',
		'acuse_notificacion',
		'fecha_acuse_notificacion',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_informe'=>'date',
        'fecha_notificacion'=>'date',
        'created_at'=>'datetime',
        'updated_at'=>'datetime',
        'fecha_termino_recomendacion'=>'datetime',
		'fecha_acuse_envio'=>'datetime',
		'fecha_informe_acuse'=>'datetime',
		'fecha_informe_comprobante'=>'datetime',
		'fecha_acuse_notificacion'=>'datetime',
        
    ];

    public function getDepaasignadoAttribute()
    {
        return User::where('unidad_administrativa_id',$this->departamento_asignado_id)->first();
    }

    // public function analista()
    // {
    //     return $this->belongsTo(User::class, 'analista_asignado_id', 'id');
    // }
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
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'InformePrimeraEtapa')->orderBy('id', 'ASC');
    }
}
