<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoUnidadesAdministrativas extends Model
{
    protected $table = 'segcatunidad_administrativas';

    use HasFactory;

    protected $fillable = [
        'id',
        'descripcion',
        'direccion_id',
        'created_at',	
        'updated_at'
    ];

    protected $casts = [
        'created_at'=>'datetime',
        'updated_at'=>'datetime'
    ];  


    public function getDireccionesAttribute()
    {
        return $this->whereIn('id', [122100,122200])->get()->pluck('descripcion', 'id');
    }   

    public function getDepartamentosAttribute()
    {
        return CatalogoUnidadesAdministrativas::where('direccion_id', $this->id)->get()->pluck('descripcion', 'id');
    } 
	
	public function getUnidadesAdministrativas()
    {
        return CatalogoUnidadesAdministrativas::whereNotNull('id')->get()->pluck('descripcion', 'id');
    } 

}
