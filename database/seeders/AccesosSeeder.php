<?php

namespace Database\Seeders;

use App\Imports\AccesosImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class AccesosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Excel::import(new AccesosImport, base_path().'/database/seeders/ACCESOS.xlsx');

        
        // Role::where('name','Administrador TI')->first()->givePermissionTo(Permission::all());
        // Role::where('name','Auditor Superior')->first()->givePermissionTo($permisosConsulta);
        // Role::where('name','Administrador del Sistema')->first()->givePermissionTo($permisosConsulta);
        // Role::where('name','Titular Unidad de Seguimiento')->first()->givePermissionTo($permisosTitular);
        // Role::where('name','Director de Seguimiento')->first()->givePermissionTo($permisosDirector);
        // Role::where('name','Jefe de Departamento de Seguimiento')->first()->givePermissionTo($permisosJefeDepartamento);
        // Role::where('name','Lider de Proyecto')->first()->givePermissionTo($permisosLiderProyecto);
        // Role::where('name','Analista')->first()->givePermissionTo($permisosAnalista);
    }
}
