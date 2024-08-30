<?php

namespace Database\Seeders;

use App\Models\CatalogoTipoAuditoria;
use Illuminate\Database\Seeder;

class CatTipoAuditoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Cumplimiento Financiero',
            'Sigla' => 'ACF',
            'cumplimiento_financiero' => 'X',
            'nombre_ae'=>'Jaime Enrique Perdigón Nieto',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Inversión Física',
            'Sigla' => 'AIF',
            'inversion_fisica' => 'X',
            'nombre_ae'=>'Jaime Enrique Perdigón Nieto',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Desempeño',
            'Sigla' => 'AD',
            'desempenio_legalidad' => 'X',
            'nombre_ae'=>'Javier López Pérez',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Legalidad',
            'Sigla' => 'AL',
            'desempenio_legalidad' => 'X',
            'nombre_ae'=>'Javier López Pérez',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Legalidad y Desempeño',
            'Sigla' => 'ALD',
            'desempenio_legalidad' => 'X',
            'nombre_ae'=>'Javier López Pérez',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Inversión Física y Legalidad',
            'Sigla' => 'AIFL',
            'inversion_fisica' => 'X',
            'desempenio_legalidad' => 'X',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Inversión Física y Desempeño',
            'Sigla' => 'AIFD',
            'inversion_fisica' => 'X',
            'desempenio_legalidad' => 'X',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Inversión Física, Desempeño y Legalidad',
            'Sigla' => 'AIFDL',
            'inversion_fisica' => 'X',
            'desempenio_legalidad' => 'X',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Cumplimiento Financiero y Legalidad',
            'Sigla' => 'ACFL',
            'cumplimiento_financiero' => 'X',
            'desempenio_legalidad' => 'X',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Cumplimiento Financiero y Desempeño',
            'Sigla' => 'ACFD',
            'cumplimiento_financiero' => 'X',
            'desempenio_legalidad' => 'X',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Cumplimiento Financiero e Inversión Física',
            'Sigla' => 'ACFIF',
            'cumplimiento_financiero' => 'X',
            'inversion_fisica' => 'X',
            'nombre_ae'=>'Jaime Enrique Perdigón Nieto',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Cumplimiento Financiero, Desempeño y Legalidad',
            'Sigla' => 'ACFDL',
            'cumplimiento_financiero' => 'X',
            'desempenio_legalidad' => 'X',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Cumplimiento Financiero, Inversión Física y Legalidad',
            'Sigla' => 'ACFIFL',
            'cumplimiento_financiero' => 'X',
            'inversion_fisica' => 'X',
            'desempenio_legalidad' => 'X',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Cumplimiento Financiero, Inversión Física y Desempeño',
            'Sigla' => 'ACFIFD',
            'cumplimiento_financiero' => 'X',
            'inversion_fisica' => 'X',
            'desempenio_legalidad' => 'X',
        ]);
        CatalogoTipoAuditoria::create([
            'Descripcion' => 'Cumplimiento Financiero, Inversión Física, Desempeño y Legalidad',
            'Sigla' => 'ACFIFDL',
            'cumplimiento_financiero' => 'X',
            'inversion_fisica' => 'X',
            'desempenio_legalidad' => 'X',
        ]);
    }
}
