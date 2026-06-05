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
        'c',
        'fecha_ultimo_acceso',
        'usuario_creacion_id',
        'usuario_actualizacion_id',
        'cp_2024',
        'cp_2023',
        'cp_2022',
        'cp_2021',
        'cp_ua2024',
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

    public function getUnidadAttribute()
    {
        if(getSession('cp')==2021){
            return $this->cp_ua2021;
        }
        if(getSession('cp')==2022){
            return $this->cp_ua2022;
        }
        if(getSession('cp')==2023){
            return $this->cp_ua2023;
        }
        if(getSession('cp')==2024){
            return $this->cp_ua2024;
        }
    }

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
    public function unidadAdministrativa2024()
    {
        return $this->hasOne(CatalogoUnidadesAdministrativas::class, 'id', 'cp_ua2024');
    }
    public function getNotificacionesCountAttribute()
    {
        if (in_array($this->siglas_rol, ['LP', 'ANA']) && getSession('cp') >= 2024) {
            return $this->todasNotificacionesNuevas()->where('estatus', 'Pendiente')->count();
        }

        $hoy = now();
        return $this->hasMany(Notificacion::class, 'destinatario_id', 'id')->whereNull('fecha_muestra_fin') ->where('fecha_muestra_inicio', '<=', $hoy) ->where('estatus', 'Pendiente')->count();
    }
    public function getNotificacionesPendientesAttribute()
    {
        if (in_array($this->siglas_rol, ['LP', 'ANA']) && getSession('cp') >= 2024) {
            return $this->todasNotificacionesNuevas()->where('estatus', 'Pendiente')->take(10)->get();
        }
        $hoy = now();
        return $this->hasMany(Notificacion::class, 'destinatario_id', 'id')->whereNull('fecha_muestra_fin')->where('fecha_muestra_inicio', '<=', $hoy)->where('estatus', 'Pendiente')->orderBy('fecha_muestra_inicio', 'asc')->take(10)->get();
    }
    public function notificaciones()
    {
        $hoy = now();

        return $this->hasMany(Notificacion::class, 'destinatario_id', 'id')->whereNull('fecha_muestra_fin')->where('fecha_muestra_inicio', '<=', $hoy)->where('estatus', 'Pendiente')->orderBy('fecha_muestra_inicio', 'asc');
    }
    public function notificacionesLeidas()
    {
        $hoy = now();

        return $this->hasMany(Notificacion::class, 'destinatario_id', 'id')->whereNull('fecha_muestra_fin')->where('fecha_muestra_inicio', '<=', $hoy)->where('estatus', '!=', 'Pendiente')->where('estatus', '!=', null)->orderBy('fecha_muestra_inicio', 'asc');
    }
    public function todasNotificaciones()
    {
        $hoy = now();

        return $this->hasMany(Notificacion::class, 'destinatario_id', 'id')->whereNull('fecha_muestra_fin')->where('fecha_muestra_inicio', '<=', $hoy)->orderBy('fecha_muestra_inicio', 'asc');
    }
    public function todasNotificacionesNuevas()
    {
        $hoy = now();
        $query = Notificacion::query()
            ->whereNull('fecha_muestra_fin')
            ->where('fecha_muestra_inicio', '<=', $hoy);

        // Roles que NO son LP ni ANA → comportamiento original sin cambios
        if (!in_array($this->siglas_rol, ['LP', 'ANA'])) {
            return $query->where('destinatario_id', $this->id);
        }

        // Auditorías donde está activo en la tabla de asignaciones
        $auditoriasAsignadas = AuditoriaUsuarios::where('user_id', $this->id)
            ->where('estatus', 'Activo')
            ->pluck('auditoria_id');

        // Equipo del usuario (protegido contra null)
        $unidadId = $this->unidadAdministrativa->id ?? null;
        $equipoId = $unidadId
            ? EquiposDeTrabajo::where('departamento_encargado_id', $unidadId)->value('id')
            : null;

        $rolDestino = $this->siglas_rol === 'LP' ? 'Lider' : 'Analista';

        return $query->where(function ($q) use ($equipoId, $auditoriasAsignadas, $rolDestino) {

            // VIEJAS: directas al usuario (destinatario_id lleno, equipo_id null)
            $q->where(function ($sub) {
                $sub->where('destinatario_id', $this->id)
                    ->whereNull('equipo_id');
            });

            // NUEVAS: de equipo (destinatario_id null, equipo_id lleno)
            if ($equipoId && $auditoriasAsignadas->isNotEmpty()) {
                $q->orWhere(function ($sub) use ($equipoId, $auditoriasAsignadas, $rolDestino) {
                    $sub->whereNull('destinatario_id')
                        ->where('equipo_id', $equipoId)
                        ->whereIn('auditoriacp_id', $auditoriasAsignadas)
                        ->where(function ($rol) use ($rolDestino) {
                            // Sin filtro de rol = para todo el equipo
                            // Con filtro = solo para Lider o solo para Analista
                            $rol->whereNull('destinatario')
                                ->orWhere('destinatario', $rolDestino);
                        });
                });
            }
        });
    }

    public function getJefeAttribute()
    {
        $clave = substr(getSession('cp_ua'), 0, 5).'0';
        if(getSession('cp')==2021){
            return usuariocp($clave)->where('siglas_rol','JD')->first();
        }
        if(getSession('cp')==2022){
            return $this->where('cp_ua2022', substr(getSession('cp_ua'), 0, 5).'0')->where('siglas_rol','JD')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2023){
            return $this->where('cp_ua2023', substr(getSession('cp_ua'), 0, 5).'0')->where('siglas_rol','JD')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2024){
            return $this->where('cp_ua2024', substr(getSession('cp_ua'), 0, 5).'0')->where('siglas_rol','JD')->where('estatus', 'Activo')->first();
        }
    }

    public function getDirectorAttribute()
    {
        if(getSession('cp')==2021){
            return $this->where('cp_ua2021', substr(getSession('cp_ua'), 0, 4).'00')->where('siglas_rol','DS')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2022){
            return $this->where('cp_ua2022', substr(getSession('cp_ua'), 0, 4).'00')->where('siglas_rol','DS')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2023){
            return $this->where('cp_ua2023', substr(getSession('cp_ua'), 0, 4).'00')->where('siglas_rol','DS')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2024){
            return $this->where('cp_ua2024', substr(getSession('cp_ua'), 0, 4).'00')->where('siglas_rol','DS')->where('estatus', 'Activo')->first();
        }

        //return usuariocp( $clave)->first();

       // return $this->where('unidad_administrativa_id', substr(auth()->user()->unidad_administrativa_id, 0, 4).'00')->first();
    }

    public function getStaffAttribute()
    {
        if(getSession('cp')==2021){
            return $this->where('cp_ua2021', substr(getSession('cp_ua'), 0, 4).'00')->where('siglas_rol','STAFF')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2022){
            return $this->where('cp_ua2022', substr(getSession('cp_ua'), 0, 4).'00')->where('siglas_rol','STAFF')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2023){
            return $this->where('cp_ua2023', substr(getSession('cp_ua'), 0, 4).'00')->where('siglas_rol','STAFF')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2024){
            return $this->where('cp_ua2024', substr(getSession('cp_ua'), 0, 4).'00')->where('siglas_rol','STAFF')->where('estatus', 'Activo')->first();
        }

        //return usuariocp( $clave)->first();

       // return $this->where('unidad_administrativa_id', substr(auth()->user()->unidad_administrativa_id, 0, 4).'00')->first();
    }

    public function getTitularAttribute()
    {
        $clave = substr(getSession('cp_ua'), 0, 3).'000';
        if(getSession('cp')==2021){
            return $this->where('cp_ua2021', substr(getSession('cp_ua'), 0, 3).'000')->where('siglas_rol','TUS')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2022){
            return $this->where('cp_ua2022', substr(getSession('cp_ua'), 0, 3).'000')->where('siglas_rol','TUS')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2023){
            return $this->where('cp_ua2023', substr(getSession('cp_ua'), 0, 3).'000')->where('siglas_rol','TUS')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2024){
            return $this->where('cp_ua2024', substr(getSession('cp_ua'), 0, 3).'000')->where('siglas_rol','TUS')->where('estatus', 'Activo')->first();
        }
        // ret
        // return $this->where('unidad_administrativa_id', substr((empty(auth()->user()->unidad_administrativa_id) ? '119' : auth()->user()->unidad_administrativa_id), 0, 3).'000')->first();
    }
    public function getLiderAttribute()
    {
		$clave = substr(getSession('cp_ua'), 0, 5).'0';
        if(getSession('cp')==2021){
            return usuariocp($clave)->where('siglas_rol','LP')->first();
        }
        if(getSession('cp')==2022){
            return $this->where('cp_ua2022', substr(getSession('cp_ua'), 0, 5).'0')->where('siglas_rol','LP')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2023){
            return $this->where('cp_ua2023', substr(getSession('cp_ua'), 0, 5).'0')->where('siglas_rol','LP')->where('estatus', 'Activo')->first();
        }
        if(getSession('cp')==2024){
            return $this->where('cp_ua2024', substr(getSession('cp_ua'), 0, 5).'0')->where('siglas_rol','LP')->where('estatus', 'Activo')->first();
        }


        //return $this->where('unidad_administrativa_id', substr(auth()->user()->unidad_administrativa_id, 0, 5).'0')->where('siglas_rol','LP')->first();
    }
    public function NotMarcarLeido($notificacion){
        if(!empty($notificacion)&& ($notificacion->estatus == 'Pendiente')){
            $notificacion = Notificacion::find($notificacion->id);
			$notificacion->update(['estatus' => 'Leído']);
        }
    }
    // app/Models/User.php
    public function sessionLogs()
    {
        return $this->hasMany(UserSessionLog::class);
    }

    // Última sesión
    public function lastSession()
    {
        return $this->hasOne(UserSessionLog::class)->latest('login_at');
    }
}
