<?php

namespace Database\Seeders;

use App\Models\CatalogoUnidadesAdministrativas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatUnidadesAdministrativasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CatalogoUnidadesAdministrativas::create(['id'=>100000,'descripcion' => 'OSFEM']);
        CatalogoUnidadesAdministrativas::create(['id'=>130000,'descripcion' => 'Unidad de Tecnologías de la Información y Comunicación']);
        CatalogoUnidadesAdministrativas::create(['id'=>110000,'descripcion' => 'Auditoría Superior']);
        CatalogoUnidadesAdministrativas::create(['id'=>112000,'descripcion' => 'Secretaría Técnica']);
        CatalogoUnidadesAdministrativas::create(['id'=>122000,'descripcion' => 'Unidad de Seguimiento']);
        CatalogoUnidadesAdministrativas::create(['id'=>122100,'descripcion' => 'Dirección de Seguimiento "A"']);
        CatalogoUnidadesAdministrativas::create(['id'=>122200,'descripcion' => 'Dirección de Seguimiento "B"']);
        CatalogoUnidadesAdministrativas::create(['id'=>122110,'descripcion' => 'Departamento de Seguimiento "A1"','direccion_id'=>122100]);
        CatalogoUnidadesAdministrativas::create(['id'=>122120,'descripcion' => 'Departamento de Seguimiento "A2"','direccion_id'=>122100]);
        CatalogoUnidadesAdministrativas::create(['id'=>122130,'descripcion' => 'Departamento de Seguimiento "A3"','direccion_id'=>122100]);        
        CatalogoUnidadesAdministrativas::create(['id'=>122210,'descripcion' => 'Departamento de Seguimiento "B1"','direccion_id'=>122200]);
        CatalogoUnidadesAdministrativas::create(['id'=>122220,'descripcion' => 'Departamento de Seguimiento "B2"','direccion_id'=>122200]);
        CatalogoUnidadesAdministrativas::create(['id'=>122230,'descripcion' => 'Departamento de Seguimiento "B3"','direccion_id'=>122200]);        
    }
}
