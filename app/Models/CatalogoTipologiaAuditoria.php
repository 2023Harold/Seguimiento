<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoTipologiaAuditoria extends Model
{
    use HasFactory;
    protected $table = 'segcattipologia_auditorias';

    protected $fillable = [
        'id',
        'descripcion',
        'tipologia',
    ];

    public $timestamps = false;


}
