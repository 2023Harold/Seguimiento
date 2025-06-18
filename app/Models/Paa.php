<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class Paa extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'segcatpaa';

    protected $fillable = [
        'id',
        'ejercicio_fiscal',
        'fecha_paa',
        'paa',
    ];



    protected $cast = [
        'fecha_paa',
    ];
}
