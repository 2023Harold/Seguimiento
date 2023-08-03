<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoMunicipio extends Model
{
    protected $table = 'segcatmunicipio';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'descripcion',
    ];
}
