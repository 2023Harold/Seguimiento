<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class AuditoriaAccion extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;


    protected $table = 'segauditoria_acciones';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'consecutivo',
        'tipo',
        'segtipo_accion_id',
        'acto_fiscalizacion',
        'acto_fiscalizacion_id',
        'numero',
        'cedula',
        'accion',
        'monto_aclarar',
        'revision_lider',
        'revision_jefe',
        'fase_revision',
        'segauditoria_id',
        'departamento_asignado_id',
        'departamento_asignado',
        'reasignacion_departamento',
        'lider_asignado',
        'lider_asignado_id',
        'reasignacion_lider',
        'lider_anterior_id',
        'analista_asignado',
        'analista_asignado_id',
        'reasignacion_analista',
        'analista_anterior_id',
        'usuario_creacion_id',
        'usuario_actualizacion_id',
        'created_at',
        'updated_at',
        'evidencia_recomendacion',
        'evidencia_resumen',
        'tipo_recomendacion',
        'tramo_control_recomendacion',
        'fecha_termino_recomendacion',
        'plazo_recomendacion',
        'normativa_infringida',
        'antecedentes_accion',
        'aprobar_cedini_analista',
        'aprobar_cedini_lider',
        'aprobar_cedini_jefe',
        'aprobar_cedpras_analista',
        'aprobar_cedpras_lider',
        'aprobar_cedpras_jefe',
        'aprobar_cedrec_analista',
        'aprobar_cedrec_lider',
        'aprobar_cedrec_jefe',
        'aprobar_cedana_analista',
        'aprobar_cedana_lider',
        'aprobar_cedana_jefe',
        'aprobar_cedanades_analista',
        'aprobar_cedanades_lider',
        'aprobar_cedanades_jefe',
        'eliminado',
        'tipologia_id'
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

    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'segauditoria_id','id');
    }

    public function tipo()
    {
        return $this->hasOne(CatalogoTipoAccion::class, 'id', 'segtipo_accion_id');
    }

    public function lider()
    {
        return $this->belongsTo(User::class, 'lider_asignado_id', 'id');
    }

    public function analista()
    {
        return $this->belongsTo(User::class, 'analista_asignado_id', 'id');
    }
    public function pras()
    {
        return $this->belongsTo(Segpras::class, 'id','accion_id');
    }
    public function recomendaciones()
    {
        return $this->belongsTo(Recomendaciones::class, 'id','accion_id');
    }
    public function solicitudesaclaracion()
    {
        return $this->belongsTo(SolicitudesAclaracion::class, 'id','accion_id');
    }
    public function pliegosobservacion()
    {
        return $this->belongsTo(PliegosObservacion::class, 'id','accion_id');
    }
    public function comentarios()
    {
        return $this->hasMany(Revisiones::class, 'accion_id', 'id')->where('accion', 'Recomendación')->whereNull('id_revision')->orderBy('id', 'ASC');
    }
    public function comentariossolicitudes()
    {
        return $this->hasMany(Revisiones::class, 'accion_id','id')->where('accion', 'Solicitud de Aclaración')->whereNull('id_revision')->orderBy('id', 'ASC');
    }
    public function comentariospliegos()
    {
        return $this->hasMany(Revisiones::class, 'accion_id','id')->where('accion', 'Pliego de Observación')->whereNull('id_revision')->orderBy('id', 'ASC');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Revisión Acción Registro Auditoría')->orderBy('id', 'ASC');
    }

    public function tipologiadesc()
    {
        return $this->belongsTo(CatalogoTipologia::class, 'tipologia_id', 'id');
    }   
}
