<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class TipologiasAccion extends Model
{
     use HasApiTokens, HasFactory, RoutesWithFakeIds;
     
    protected $primaryKey = 'id';

    protected $table = 'segtipologia_accion';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'auditoria_id',
        'accion_id',
        'tipologia',
        'descripcion',
        'usuario_creacion_id',
        'usuario_modificacion_id',
    ];
    protected $cast=[
    'created_at' => 'datetime',
    'updated_at' => 'datetime',

    ];

    public function accion(){
        return $this->belongsTo(AuditoriaAccion::class, 'accion_id', 'id');
    }

}
