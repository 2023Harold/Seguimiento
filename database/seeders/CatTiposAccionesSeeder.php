<?php

namespace Database\Seeders;

use App\Models\CatalogoTipoAccion;
use App\Models\CatTipoAccion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatTiposAccionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CatalogoTipoAccion::insert([
            ['descripcion' => 'Solicitud de aclaración'],
            ['descripcion' => 'Recomendación'],
            ['descripcion' => 'Pliego de observación'],
            ['descripcion' => 'Promoción de responsabilidad administrativa sancionatoria'],
            
        ]);
    }
}
