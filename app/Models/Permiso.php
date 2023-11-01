<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class Permiso extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;
  //Nombre de la tabla de la base de datos que definimos (Database table name).

 protected $table='segpermissions';


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
}
