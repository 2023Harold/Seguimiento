<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoTipoAuditoria extends Model
{
    protected $table = 'segcattipos_auditorias';

    use HasFactory;

    protected $fillable = [
        'id',
        'Descripcion',
        'Sigla',
        'cumplimiento_financiero',
        'inversion_fisica',
        'desempenio_legalidad',
    ];

    public function tipo_auditorias()
    {
        return$this->hasMany(Auditoria::class);
    }

    public function tipo_auditorias_()
    {
        return$this->hasMany(FisAuditoria_PAA::class);
    }

    public function filtro_tipo_auditoria()
    {
        $arr_cumplimiento = ! empty($this->cumplimiento_financiero) ? ['Cumplimiento Financiero' => 'Cumplimiento Financiero'] : [];
        $arr_inversion = ! empty($this->inversion_fisica) ? ['Inversión Física' => 'Inversión Física'] : [];
        $arr_desempenio_legalidad = ! empty($this->desempenio_legalidad) ? ['Desempeño y Legalidad' => 'Desempeño y Legalidad'] : [];

        return array_merge($arr_cumplimiento, $arr_desempenio_legalidad, $arr_inversion);
    }

    public function scopeCumplimiento()
    {
        return $this->whereLike('descripcion', 'Cumplimiento Financiero')->pluck('id')->toArray();
    }

    public function scopeInversion()
    {
        return $this->whereLike('descripcion', 'Inversión Física')->pluck('id')->toArray();
    }

    public function scopeDesempenio()
    {
        return $this->whereLike('descripcion', 'Desempeño')->pluck('id')->toArray();
    }

    public function scopeLegalidad()
    {
        return $this->whereLike('Descripcion', 'Legalidad')->pluck('id')->toArray();
    }
}
