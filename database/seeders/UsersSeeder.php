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
            'usuario_plataforma_id'=>1,
            'name' => 'Ulises Ivan Lovera Villegas',
            'curp' => 'LOVU810319HMCVLL10',
            'email' => 'ulises.lovera@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Unidad de Tecnologías de la Información y Comunicación',
            'unidad_administrativa_id' => 130000,
            'siglas_rol'=>'ATI',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Administrador TI');
        User::create([
            'usuario_plataforma_id'=>541,
            'name' => 'Miroslava Carrillo Martínez',
            'curp' => 'CAMM730505MMCRRR07',
            'email' => 'miroslava.carrillo@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Auditora Superior',
            'unidad_administrativa_id' => 110000,
            'siglas_rol'=>'AS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Administrador del Sistema');
        User::create([
            'usuario_plataforma_id'=>528,
            'name' => 'María Wendoline Morales Carrera',
            'curp' => 'MOCW900128MVZRRN06',
            'email' => 'wendoline.morales@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Secretaria Técnica',
            'unidad_administrativa_id' => 112000,
            'siglas_rol'=>'AS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Administrador del Sistema');
        User::create([
            'usuario_plataforma_id'=>544,
            'name' => 'Luis Ignacio Sierra Villa',
            'curp' => 'SIVL631102HMCRLS08',
            'email' => 'luis.sierra@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Titular de la Unidad de Seguimiento',
            'unidad_administrativa_id' => 122000,
            'siglas_rol'=>'TUS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Titular Unidad de Seguimiento');
        //Dirección A
        User::create([
			'usuario_plataforma_id'=>1649,
            'name' => 'Karem Ríos Lara',
            'curp' => 'RILK840402MMCSRR05',
            'email' => 'karem.rios@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Director de la Dirección de Seguimiento "A"',
            'unidad_administrativa_id' => 122100,
            'siglas_rol'=>'DS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Director de Seguimiento');
        //Dirección B
        User::create([
			'usuario_plataforma_id'=>1650,
            'name' => 'Edgar Castellanos Álvarez ',
            'curp' => 'CAAE830723HDFSLD04',
            'email' => 'edgar.castellanos@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Director de la Dirección de Seguimiento "B"',
            'unidad_administrativa_id' => 122200,
            'siglas_rol'=>'DS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Director de Seguimiento');

        Excel::import(new UserImport, base_path().'/database/seeders/Usuarios.xlsx');
    }
}
