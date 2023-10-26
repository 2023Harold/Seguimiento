<?php

namespace App\Models\Directorio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class UserDirectorio extends Model
{
    use HasApiTokens, HasFactory,RoutesWithFakeIds;

    protected $connection = 'directorio';

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'pkcveentusu',
        'name',
        'primer_apellido',
        'segundo_apellido',
        'curp',
        'rfc',
        'tipo_usuario',
        'cargo',
        'entidad_fiscalizable_id',
        'entidad_fiscalizable',
        'cargo_asociado_id',
        'cargo_asociado',
        'siglas_cargo_asociado',
        'inicio_administracion',
        'termino_administracion',
        'oficio_memo',
        'numero_oficio',
        'fecha_oficio_memo',
        'fecha_acuse',
        'nombramiento',
        'inicio_funciones',
        'termino_funciones',
        'componentes',
        'acepta_terminos',
        'confirmacion_terminos',
        'email',
        'email_verified_at',
        'password',
        'estatus',
        'fecha_envio_correo',
        'fecha_ultimo_acceso',
        'token',
        'is_verified',
        'remember_token',
        'session_id',
        'usuario_creacion_id',
        'usuario_actualizacion_id',
        'created_at',
        'updated_at',
    ];



    protected $dates = [
        'fecha_oficio_memo',
        'fecha_acuse',
        'inicio_funciones',
        'termino_funciones',
        'fecha_envio_correo',
        'fecha_ultimo_acceso',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'created_at'=>'datetime',
        'updated_at'=>'datetime',
        'fecha_termino_recomendacion'=>'datetime',
    ];

    public function get_declared_classes()
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
}
