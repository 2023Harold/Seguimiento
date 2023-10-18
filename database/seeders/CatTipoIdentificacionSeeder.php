<?php

namespace Database\Seeders;

use App\Models\CatalogoTipoAccion;
use App\Models\CatalogoTipoIdentificacion;
use App\Models\CatTipoAccion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatTipoIdentificacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CatalogoTipoIdentificacion::insert([
            ['descripcion' => 'INE'],
            ['descripcion' => 'Pasaporte'],
            ['descripcion' => 'Cédula profesional'],
            ['descripcion' => 'Cartilla militar'],
            ['descripcion' => 'Gafete institucional'],
        ]);
    }
}
