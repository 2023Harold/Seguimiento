<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoTipologia extends Model
{
    use HasFactory;
    protected $table = 'segcattipologia';

    protected $fillable = [
        'id',
        'tipo_auditoria_id',
        'tipologia',
        'descripcion',
    ];

    public $timestamps = false;


}
