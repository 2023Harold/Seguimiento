<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermisosTurnosContestacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisoslider = [
            'turnoui.contestaciones',
            'turnooic.contestaciones',
            'turnoarchivo.contestaciones',
            'turnocontestacionesoic.index',
            'turnocontestacionesoic.create',
            'turnocontestacionesoic.store',
            'turnocontestacionesoic.show',
            'turnocontestacionesoic.edit',
            'turnocontestacionesoic.update',
            'turnocontestacionesoic.destroy',
            'turnocontestacionesarc.index',
            'turnocontestacionesarc.create',
            'turnocontestacionesarc.store',
            'turnocontestacionesarc.show',
            'turnocontestacionesarc.edit',
            'turnocontestacionesarc.update',
            'turnocontestacionesarc.destroy',
            'turnocontestacionesui.index',
            'turnocontestacionesui.create',
            'turnocontestacionesui.store',
            'turnocontestacionesui.show',
            'turnocontestacionesui.edit',
            'turnocontestacionesui.update',
            'turnocontestacionesui.destroy',
        ];

        foreach ($permisoslider as $permiso) {
            \Spatie\Permission\Models\Permission::create(['name' => $permiso]);
        }

        $roladmin = \Spatie\Permission\Models\Role::findByName('Administrador TI');
        $rollider= \Spatie\Permission\Models\Role::findByName('Lider de Proyecto');
        $rolanalista= \Spatie\Permission\Models\Role::findByName('Analista');

        $roladmin->givePermissionTo($permisoslider);
        $rollider->givePermissionTo([
            'turnoui.contestaciones',
            'turnooic.contestaciones',
            'turnocontestacionesui.index',
            'turnocontestacionesui.create',
            'turnocontestacionesui.store',
            'turnocontestacionesui.show',
            'turnocontestacionesui.edit',
            'turnocontestacionesui.update',
            'turnocontestacionesui.destroy',
            'turnocontestacionesoic.index',
            'turnocontestacionesoic.create',
            'turnocontestacionesoic.store',
            'turnocontestacionesoic.show',
            'turnocontestacionesoic.edit',
            'turnocontestacionesoic.update',
            'turnocontestacionesoic.destroy',
        ]);
        $rolanalista->givePermissionTo([
            'turnoarchivo.contestaciones',
            'turnocontestacionesarc.index',
            'turnocontestacionesarc.create',
            'turnocontestacionesarc.store',
            'turnocontestacionesarc.show',
            'turnocontestacionesarc.edit',
            'turnocontestacionesarc.update',
            'turnocontestacionesarc.destroy',
        ]);
    }
}
