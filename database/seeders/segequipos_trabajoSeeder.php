<?php

namespace Database\Seeders;

use App\Models\EquiposDeTrabajo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class segequipos_trabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
public function run()
    {
        EquiposDeTrabajo::insert([
            [
                'equipo_name' => 'equipo A1',
                'consecutivo' => 1,
                'departamento_encargado' => 'Departamento de Seguimiento "A1"',
                'departamento_encargado_id' => 122110,
                'estatus' => 'Activo',
            ],
            [
                'equipo_name' => 'equipo A2',
                'consecutivo' => 2,
                'departamento_encargado' => 'Departamento de Seguimiento "A2"',
                'departamento_encargado_id' => 122120,
                'estatus' => 'Activo',
            ],
            [
                'equipo_name' => 'equipo A3',
                'consecutivo' => 3,
                'departamento_encargado' => 'Departamento de Seguimiento "A3"',
                'departamento_encargado_id' => 122130,
                'estatus' => 'Activo',
            ],
            [
                'equipo_name' => 'equipo B1',
                'consecutivo' => 4,
                'departamento_encargado' => 'Departamento de Seguimiento "B1"',
                'departamento_encargado_id' => 122210,
                'estatus' => 'Activo',
            ],
            [
                'equipo_name' => 'equipo B2',
                'consecutivo' => 5,
                'departamento_encargado' => 'Departamento de Seguimiento "B2"',
                'departamento_encargado_id' => 122220,
                'estatus' => 'Activo',
            ],
            [
                'equipo_name' => 'equipo B3',
                'consecutivo' => 6,
                'departamento_encargado' => 'Departamento de Seguimiento "B3"',
                'departamento_encargado_id' => 122230,
                'estatus' => 'Activo',
            ],
        ]);
    }
}
