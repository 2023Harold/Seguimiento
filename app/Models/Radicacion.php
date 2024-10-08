<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Traits\HasRoles;

class Radicacion extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;


    protected $table = 'segradicacion';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'auditoria_id',
        'numero_expediente',
        'numero_acuerdo',
        'oficio_acuerdo',
        'fecha_oficio_acuerdo',
        'oficio_designacion',
        'fecha_oficio_designacion',
        'constancia',
        'fase_autorizacion',
        'nivel_autorizacion',
        'num_memo_recepcion_expediente',
        'fecha_expediente_turnado',
        'fecha_oficio_informe',
        'fecha_notificacion',
        'plazo_maximo',
        'calculo_fecha',
        'usuario_creacion_id',
        'usuario_modificacion_id',
        'usuario_firmante_id',
        'num_memo_recepcion_expediente',
        'radicacion_sistema',
        'created_at'=>'datetime',
        'updated_at'=>'datetime',        
    ];

           
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_oficio_acuerdo'=>'datetime',
        'fecha_oficio_designacion'=>'datetime',
        'created_at'=>'datetime',
        'updated_at'=>'datetime'
    ];
    
    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'auditoria_id', 'id');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimientos::class, 'accion_id', 'id')->where('accion', 'Radicación')->orderBy('id', 'ASC');
    }
    
    public function usuarioCreacion()
    {
        return $this->belongsTo('App\Models\User', 'usuario_creacion_id');
    }

    public function usuarioActualizacion()
    {
        return $this->belongsTo('App\Models\User', 'usuario_modificacion_id');
    }

    public function userFirmante()
    {
        return $this->belongsTo('App\Models\User', 'usuario_firmante_id');
    }
}
