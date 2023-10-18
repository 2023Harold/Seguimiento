<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoTipoIdentificacion extends Model
{
    protected $table = 'segcattipo_identificacion';

    use HasFactory;

    protected $fillable = [
        'id',
        'descripcion',
    ];

    public $timestamps = false;

}
