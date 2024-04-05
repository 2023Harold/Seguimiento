<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Administrador TI'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'Auditor Superior']);
        Role::create(['name' => 'Administrador del Sistema']);
        Role::create(['name' => 'Titular Unidad de Seguimiento']);
        Role::create(['name' => 'Director de Seguimiento']);
        Role::create(['name' => 'Jefe de Departamento de Seguimiento']);
        Role::create(['name' => 'Lider de Proyecto']);
        Role::create(['name' => 'Analista']);
    }
}
