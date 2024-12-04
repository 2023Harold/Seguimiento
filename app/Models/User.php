<?php

namespace App\Models;

use App\Http\Traits\NotificacionTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use NotificacionTrait;
    use HasApiTokens, HasFactory, Notifiable, HasRoles, RoutesWithFakeIds;


    protected $table = 'segusers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario_plataforma_id',
        'name',
        'curp',
        'email',
        'password',
        'puesto',
        'unidad_administrativa_id',
        'siglas_rol',
        'estatus',
        'fecha_ultimo_acceso',
        'usuario_creacion_id',
        'usuario_actualizacion_id',
        'cp_2023',
        'cp_2022',
        'cp_2021',
        'cp_ua2023',
        'cp_ua2022',
        'cp_ua2021'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_ultimo_acceso' => 'datetime',
        'created_at'=>'datetime',
        'updated_at'=>'datetime'
    ];

    public function unidadAdministrativa()
    {
        return $this->hasOne(CatalogoUnidadesAdministrativas::class, 'id', 'unidad_administrativa_id');
    }
    public function unidadAdministrativa2021()
    {
        return $this->hasOne(CatalogoUnidadesAdministrativas::class, 'id','cp_ua2021');
    }
    public function unidadAdministrativa2022()
    {
        return $this->hasOne(CatalogoUnidadesAdministrativas::class, 'id', 'cp_ua2022');
    }
    public function unidadAdministrativa2023()
    {
        return $this->hasOne(CatalogoUnidadesAdministrativas::class, 'id', 'cp_ua2023');
    }
    public function notificaciones()
    {
        $hoy = now();

        return $this->hasMany(Notificacion::class, 'destinatario_id', 'id')->whereNull('fecha_muestra_fin')->where('fecha_muestra_inicio', '<=', $hoy)->where('estatus', 'Pendiente')->orderBy('fecha_muestra_inicio', 'desc');
    }

    public function todasNotificaciones()
    {
        $hoy = now();

        return $this->hasMany(Notificacion::class, 'destinatario_id', 'id')->whereNull('fecha_muestra_fin')->where('fecha_muestra_inicio', '<=', $hoy)->orderBy('fecha_muestra_inicio', 'desc');
    } 
    
    public function getJefeAttribute()
    {
        $clave = substr(getSession('cp_ua'), 0, 5).'0';
        if(getSession('cp')==2021){
            return usuariocp($clave)->where('siglas_rol','JD')->first();
        }
        if(getSession('cp')==2022){
            return $this->where('cp_ua2022', substr(getSession('cp_ua'), 0, 5).'0')->where('siglas_rol','JD')->first();
        }
        if(getSession('cp')==2023){
            return $this->where('cp_ua2023', substr(getSession('cp_ua'), 0, 5).'0')->where('siglas_rol','JD')->first();
        }       
    }

    public function getDirectorAttribute()
    {
        if(getSession('cp')==2021){
            return $this->where('cp_ua2021', substr(getSession('cp_ua'), 0, 4).'00')->first();
        }
        if(getSession('cp')==2022){
            return $this->where('cp_ua2022', substr(getSession('cp_ua'), 0, 4).'00')->first();
        }
        if(getSession('cp')==2023){
            return $this->where('cp_ua2023', substr(getSession('cp_ua'), 0, 4).'00')->first();
        }
       
        //return usuariocp( $clave)->first();
       
       // return $this->where('unidad_administrativa_id', substr(auth()->user()->unidad_administrativa_id, 0, 4).'00')->first();
    }

    public function getTitularAttribute()
    {
        $clave = substr(getSession('cp_ua'), 0, 3).'000';
        if(getSession('cp')==2021){
            return $this->where('cp_ua2021', substr(getSession('cp_ua'), 0, 3).'000')->first();
        }
        if(getSession('cp')==2022){
            return $this->where('cp_ua2022', substr(getSession('cp_ua'), 0, 3).'000')->first();
        }
        if(getSession('cp')==2023){
            return $this->where('cp_ua2023', substr(getSession('cp_ua'), 0, 3).'000')->first();
        }
        // return $this->where('unidad_administrativa_id', substr((empty(auth()->user()->unidad_administrativa_id) ? '119' : auth()->user()->unidad_administrativa_id), 0, 3).'000')->first();
    }
    public function getLiderAttribute()
    {
		$clave = substr(getSession('cp_ua'), 0, 5).'0';
        if(getSession('cp')==2021){
            return usuariocp($clave)->where('siglas_rol','LP')->first();
        }
        if(getSession('cp')==2022){
            return $this->where('cp_ua2022', substr(getSession('cp_ua'), 0, 5).'0')->where('siglas_rol','LP')->first();
        }
        if(getSession('cp')==2023){
            return $this->where('cp_ua2023', substr(getSession('cp_ua'), 0, 5).'0')->where('siglas_rol','LP')->first();
        } 
       

        //return $this->where('unidad_administrativa_id', substr(auth()->user()->unidad_administrativa_id, 0, 5).'0')->where('siglas_rol','LP')->first();
    }
}
