<?php

namespace Database\Seeders;

use App\Imports\UserImport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       User::create([
            'name' => 'Ulises Ivan Lovera Villegas',
            'curp' => 'RORH920920DS8',
            'email' => 'ulises.lovera@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Unidad de Tecnologías de la Información y Comunicación',
            'unidad_administrativa_id' => 130000,
            'siglas_rol'=>'ATI',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Administrador TI');
        User::create([
            'name' => 'Miroslava Carrillo Martínez',
            'curp' => 'RORH920920DS8',
            'email' => 'miroslava.carrillo@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Auditora Superior',
            'unidad_administrativa_id' => 110000,
            'siglas_rol'=>'AS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Administrador del Sistema');
        User::create([
            'name' => 'María Wendoline Morales Carrera',
            'curp' => 'RORH920920DS8',
            'email' => 'wendoline.morales@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Secretaria Técnica',
            'unidad_administrativa_id' => 112000,
            'siglas_rol'=>'AS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Administrador del Sistema');
        User::create([
            'name' => 'Luis Ignacio Sierra Villa',
            'curp' => 'RORH920920DS8',
            'email' => 'luis.sierra@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Titular de la Unidad de Seguimiento',
            'unidad_administrativa_id' => 122000,
            'siglas_rol'=>'TUS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Titular Unidad de Seguimiento');
        //Dirección A
        User::create([
            'name' => 'Karem Ríos Lara',
            'curp' => 'RORH920920DS8',
            'email' => 'karem.rios@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Director de la Dirección de Seguimiento "A"',
            'unidad_administrativa_id' => 122100,
            'siglas_rol'=>'DS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Director de Seguimiento');
        //Dirección B
        User::create([
            'name' => 'Edgar Castellanos Álvarez ',
            'curp' => 'RORH920920DS8',
            'email' => 'edgar.castellanos@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Director de la Dirección de Seguimiento "B"',
            'unidad_administrativa_id' => 122200,
            'siglas_rol'=>'DS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Director de Seguimiento');

        Excel::import(new UserImport, base_path().'/database/seeders/Usuarios.xlsx');
    }
}
