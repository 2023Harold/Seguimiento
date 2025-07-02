<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;

class AcuerdosValoracion extends Model
{
    use HasFactory;
    use RoutesWithFakeIds;

    protected $table = 'seganv_av';

    protected $fillable = [
        'id',
        'folio_id',
        'auditoria_id',
        'numero_expediente',
        'tipo_doc',
        'numero_oficio_ent',
        'fecha_oficio_ent',
        'nombre_firmante',
        'cargo_firmante',
        'administracion_firmante',
        'nombre_informe_au',
        'cargo_informe_au',
        'administracion_informe_au',
        'usuario_creacion_id',
        'usuario_modificacion_id',
        'num_fojas'
    ];



    protected $cast = [
        'fecha_oficio_ent',
    ];

    public function anexoanvav()
    {
        return $this->hasMany(AnexosAnV_AV::class, 'anvav_id', 'id');
    }
}
