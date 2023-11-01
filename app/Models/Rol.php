<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class Rol extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;
    //Nombre de la tabla de la base de datos que definimos (Database table name).

    protected $table='segroles';


    //Por defecto Eloquent  asume que existe una clave primaria llamada id,
    //si este no es nuesto caso lo tenemos que indicar en la variable $primaryKey

    protected $primaryKey = 'id';
    protected $guard_name = 'web';
    protected $attributes = array(
    'guard_name' => 'web'
    );
    //Denimos los campos de la tabla directamente en la variable de tipo array $fillable

    protected $fillable =  array('name','guard_name','user','clave');


    //En la variable $hidden podemos indicar los campos que no queremos que nos devuelvan
    //en las consultas, por ejemplo, los campos created_at y updated_at, que el ORM Eloquent
    //añade por defecto

    protected $hidden = ['created_at','updated_at'];

    public function getRoles()
    {
        return $this->all()->pluck('name','name');
    }

    public function notificaciones(){
        $hoy = now();
        $notificacion = $this->hasMany(Notificacion::class,'rol_id','id');

        if(auth()->user()->roles[0]->name=='Supervisor')
            $notificacion = $notificacion->where('supervisor_id',auth()->user()->id);

        if(auth()->user()->roles[0]->name=='Entidad Fiscalizable')
            $notificacion = $notificacion->where('entidad_fiscalizable_id',auth()->user()->entidad_fiscalizable_id);

        if(auth()->user()->roles[0]->name=='Personal Operativo')
            $notificacion = $notificacion->where('supervisor_id',auth()->user()->id);

        if(auth()->user()->roles[0]->name!='Entidad Fiscalizable')
            $notificacion = $notificacion->where('unidad_administrativa_id',auth()->user()->unidad_administrativa_id);

        if(auth()->user()->roles[0]->name=='Director de Seguimiento')
            $notificacion = $notificacion->where('unidad_administrativa_id',auth()->user()->unidad_administrativa_id);


        $notificacion=$notificacion->whereNull('fecha_muestra_fin')->where('fecha_muestra_inicio','<=', $hoy)->where('estatus','Pendiente');
        return $notificacion;
    }
}
