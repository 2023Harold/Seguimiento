<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class ComparecenciaCopia extends Model
{
    use HasFactory, RoutesWithFakeIds;

    public $timestamps = false;

    protected $table = 'segcomparecencia_copias';


    protected $fillable = [
        'comparecencia_id',
        'numero',
        'nombre',
        'domicilio_notificacion',
        'calle',
        'numero_domicilio',
        'colonia',
        'municipio',
        'entidad_federativa',
        'codigo_postal',
    ];

    public function comparecencia()
    {
        return $this->belongsTo(Comparecencia::class, 'comparecencia_id', 'id');
    }
}
