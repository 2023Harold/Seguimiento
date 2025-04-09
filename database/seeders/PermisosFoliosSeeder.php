<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisosFoliosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisosGenerales = [
            ['name' => 'folioscrr.index', 'guard_name' => 'web'],
            ['name' => 'folioscrr.create', 'guard_name' => 'web'],
            ['name' => 'folioscrr.store', 'guard_name' => 'web'],
            ['name' => 'folioscrr.show', 'guard_name' => 'web'],
            ['name' => 'folioscrr.edit', 'guard_name' => 'web'],
            ['name' => 'folioscrr.update', 'guard_name' => 'web'],
            ['name' => 'folioscrr.destroy', 'guard_name' => 'web'],
        ];

        Permission::insert($permisosGenerales);

        Role::findByName('Administrador TI')->givePermissionTo(['folioscrr.index','folioscrr.create','folioscrr.store','folioscrr.show','folioscrr.edit','folioscrr.update','folioscrr.destroy']);
        Role::findByName('Auditor Superior')->givePermissionTo(['folioscrr.index','folioscrr.show']);
        Role::findByName('Administrador del Sistema')->givePermissionTo(['folioscrr.index','folioscrr.show']);
        Role::findByName('Titular Unidad de Seguimiento')->givePermissionTo(['folioscrr.index','folioscrr.show']);
        Role::findByName('Director de Seguimiento')->givePermissionTo(['folioscrr.index','folioscrr.show']);
        Role::findByName('Jefe de Departamento de Seguimiento')->givePermissionTo(['folioscrr.index','folioscrr.show']);
        Role::findByName('Lider de Proyecto')->givePermissionTo(['folioscrr.index','folioscrr.show']);
        Role::findByName('Analista')->givePermissionTo(['folioscrr.index','folioscrr.create','folioscrr.store','folioscrr.show','folioscrr.edit','folioscrr.update','folioscrr.destroy']);
        Role::findByName('Staff Juridico')->givePermissionTo(['folioscrr.index','folioscrr.show']);
        Role::findByName('Consultor Administrativo')->givePermissionTo(['folioscrr.index','folioscrr.show']);
    }
}
