<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class Comparecencia extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;


    protected $table = 'segcomparecencia';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [        
        'auditoria_id',
        'oficio_acreditacion',
        'nombre_titular',
        'cargo_titular',
        'oficio_comparecencia',
        'fecha_comparecencia',
        'hora_comparecencia_inicio',
        'hora_comparecencia_termino',
        'fecha_inicio_aclaracion',
        'fecha_termino_aclaracion',
        'notificacion_estrados',
        'calle',
        'numero_domicilio',
        'colonia',
        'codigo_postal',
        'municipio',
        'entidad_federativa',
        'anexos',
        'copias_conocimiento',
        'domicilio_notificacion',
        'constancia',
        'fase_autorizacion',
        'nivel_autorizacion',
        'oficio_recepcion',
        'fecha_recepcion',
        'oficio_acuse',
        'fecha_acuse',            
        'oficio_acta',
        'numero_acta',
        'fecha_acta',
        'oficio_respuesta',
        'fecha_respuesta',
        'cedula_general',
        'fecha_cedula',
        'usuario_creacion_id',
        'usuario_modificacion_id',
        'created_at',
        'updated_at',
        'nombre_representante',
        'cargo_representante1',
        'numero_identificacion_representante',
        'nombre_testigo1',
        'cargo_testigo1',
        'numero_identificacion_testigo1',
        'nombre_testigo2',
        'cargo_testigo2',
        'numero_identificacion_testigo2',
        'aplicacion_periodo'
    ];

           
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_comparecencia'=>'date',
        'fecha_inicio_aclaracion'=>'date',
        'fecha_termino_aclaracion'=>'date',
        'fecha_recepcion'=>'date',
        'fecha_acuse'=>'date',  
        'fecha_acta'=>'date',
        'fecha_respuesta'=>'date',
        'fecha_cedula'=>'date',
        'created_at'=>'datetime',
        'updated_at'=>'datetime'
    ];

    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'auditoria_id', 'id');
    }

    public function agenda()
    {
        return $this->belongsTo(ComparecenciaAgenda::class, 'id','id_comparecencia');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Comparecencia')->orderBy('id', 'ASC');
    }

    public function comparecenciaAnexos()
    {
        return $this->hasMany(ComparecenciaAnexo::class, 'comparecencia_id', 'id');
    }

    public function comparecenciaCopias()
    {
        return $this->hasMany(ComparecenciaCopia::class, 'comparecencia_id', 'id');
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
