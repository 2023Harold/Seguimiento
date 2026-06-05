<?php

namespace Database\Seeders;

use App\Models\EquiposDeTrabajo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Permission;

class PermisosFaltantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    //Se colocan los permisos que faltan en la base de datos, para que no se tengan que crear manualmente (Solo los agrega tu tienes que asiganrlos a los roles)
 public function run()
    {
        $permisos = [
            //'turnoarchivoenvio.update',
            //'turnoarchivorevision01.edit',
            //'turnoarchivorevision01.update',
            //'turnooicenvio.update',
        ];
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate([
                'name' => trim($permiso),
                'guard_name' => 'web'
            ]);
        }
    }
}
