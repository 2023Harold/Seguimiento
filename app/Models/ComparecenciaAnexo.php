<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class ComparecenciaAnexo extends Model
{
    use HasFactory, RoutesWithFakeIds;

    public $timestamps = false;

    protected $table = 'segcomparecencia_anexos';

    protected $fillable = [
        'comparecencia_id',
        'numero',
        'archivo',
        'descripcion',
    ];

    public function comparecencia()
    {
        return $this->belongsTo(Comparecencia::class, 'comparecencia_id', 'id');
    }
}
