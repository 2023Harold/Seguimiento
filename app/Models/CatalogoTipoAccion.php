<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoTipoAccion extends Model
{
    protected $table = 'segcattipo_accion';

    use HasFactory;

    protected $fillable = [
        'id',
        'descripcion',        
    ];

    public $timestamps = false;

}
