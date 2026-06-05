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
            'name' => 'Hector Hans Marin Kado',
            'curp' => 'XXXXXXXXXXXXXXXXXX',
            'email' => 'hans.marin@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Unidad de Tecnologías de la Información y Comunicación',
            'unidad_administrativa_id' => 130000,
            'siglas_rol'=>'ATI',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
            'cp_2021'=>null,
            'cp_2022'=>'X',
            'cp_2023'=>'X',
            'cp_2024'=>'X',
            'cp_ua2021'=>null,
            'cp_ua2022'=>130000,
            'cp_ua2023'=>130000,
            'cp_ua2024'=>130000,
        ])->assignRole('Administrador TI');
       User::create([
            'usuario_plataforma_id'=>null,
            'name' => 'Jorge Angel Becerril Gonzalez',
            'curp' => 'XXXXXXXXXXXXXXXXXX',
            'email' => 'jorge.becerril@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Unidad de Tecnologías de la Información y Comunicación',
            'unidad_administrativa_id' => 130000,
            'siglas_rol'=>'ATI',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
            'cp_2021'=>null,
            'cp_2022'=>'X',
            'cp_2023'=>'X',
            'cp_2024'=>'X',
            'cp_ua2021'=>null,
            'cp_ua2022'=>130000,
            'cp_ua2023'=>130000,
            'cp_ua2024'=>130000,
        ])->assignRole('Administrador TI');
        User::create([
            'usuario_plataforma_id'=>541,
            'name' => 'Liliana Davalos Ham ',
            'curp' => 'CAMM730505MMCRRR07',
            'email' => 'miroslava.carrillo@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Auditora Superior',
            'unidad_administrativa_id' => 110000,
            'siglas_rol'=>'AS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
            'cp_2021'=>null,
            'cp_2022'=>'X',
            'cp_2023'=>'X',
            'cp_ua2021'=>null,
            'cp_ua2022'=>110000,
            'cp_ua2023'=>110000,
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
            'cp_2021'=>null,
            'cp_2022'=>'X',
            'cp_2023'=>'X',
            'cp_ua2021'=>null,
            'cp_ua2022'=>112000,
            'cp_ua2023'=>112000,
        ])->assignRole('Administrador del Sistema');
        User::create([
            'usuario_plataforma_id'=>544,
            'name' => 'Roberto Osorio García',
            'curp' => 'XXXXXXXXXXXXXXXXXX',
            'email' => 'roberto.osorio@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Titular de la Unidad de Seguimiento',
            'unidad_administrativa_id' => 122000,
            'siglas_rol'=>'TUS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
            'cp_2021'=>null,
            'cp_2022'=>'X',
            'cp_2023'=>'X',
            'cp_2024'=>'X',
            'cp_ua2021'=>null,
            'cp_ua2022'=>122000,
            'cp_ua2023'=>122000,
            'cp_ua2024'=>122000,
        ])->assignRole('Titular Unidad de Seguimiento');
        //Dirección A
        User::create([
			'usuario_plataforma_id'=>1649,
            'name' => 'Giovanna Delgado Casas',
            'curp' => 'XXXXXXXXXXXXXXXXXX',
            'email' => 'giovanna.delgado@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Directora de la Dirección de Seguimiento "A"',
            'unidad_administrativa_id' => 122100,
            'siglas_rol'=>'DS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
            'cp_2021'=>null,
            'cp_2022'=>'X',
            'cp_2023'=>'X',
            'cp_2024'=>'X',
            'cp_ua2021'=>null,
            'cp_ua2022'=>122100,
            'cp_ua2023'=>122100,
            'cp_ua2024'=>122100,
        ])->assignRole('Director de Seguimiento');
        //Dirección B
        User::create([
			'usuario_plataforma_id'=>1650,
            'name' => 'Guadalupe Ruíz Velázquez',
            'curp' => 'CAAE830723HDFSLD04',
            'email' => 'guadalupe.ruiz@osfem.gob.mx',
            'password' => Hash::make('d3s42023'),
            'puesto' => 'Directora de la Dirección de Seguimiento "B"',
            'unidad_administrativa_id' => 122200,
            'siglas_rol'=>'DS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
            'cp_2021'=>null,
            'cp_2022'=>'X',
            'cp_2023'=>'X',
            'cp_2024'=>'X',
            'cp_ua2021'=>null,
            'cp_ua2022'=>122200,
            'cp_ua2023'=>122200,
            'cp_ua2024'=>122200,
        ])->assignRole('Director de Seguimiento');

        Excel::import(new UserImport, base_path().'/database/seeders/Usuarios.xlsx');
    }
}
