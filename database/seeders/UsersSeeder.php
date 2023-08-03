<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'puesto' => 'Director del la Dirección de Seguimiento "A"',
            'unidad_administrativa_id' => 122100,
            'siglas_rol'=>'DS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Director de Seguimiento');
        User::create([
            'name' => 'Felipe Martinez Hernandez',
            'curp' => 'RORH920920DS8',
            'email' => 'felipe.martinez@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Lider de Proyecto de la Dirección de Seguimiento "A"',
            'unidad_administrativa_id' => 122100,
            'siglas_rol'=>'LP',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Lider de Proyecto');
        User::create([
            'name' => 'Yolanda Jordan Carranza',
            'curp' => 'RORH920920DS8',
            'email' => 'yolanda.jordan@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Lider de Proyecto de la Dirección de Seguimiento "A"',
            'unidad_administrativa_id' => 122100,
            'siglas_rol'=>'LP',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Lider de Proyecto');
        User::create([
            'name' => 'Baltazar Moreno Gutiérrez',
            'curp' => 'RORH920920DS8',
            'email' => 'baltazar.moreno@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Jefe de Departamento de Seguimiento "A1"',
            'unidad_administrativa_id' => 122110,
            'siglas_rol'=>'JD',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Jefe de Departamento de Seguimiento');       
        User::create([
            'name' => 'Beatriz Ivonne Morales Cuenca',
            'curp' => 'RORH920920DS8',
            'email' => 'beatriz.morales@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Analista del Departamento de Seguimiento "A1"',
            'unidad_administrativa_id' => 122110,
            'siglas_rol'=>'ANA',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Analista');        
        User::create([
            'name' => 'Deodoro Beltran Pedroza',
            'curp' => 'RORH920920DS8',
            'email' => 'deodoro.beltran@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Analista del Departamento de Seguimiento "A1"',
            'unidad_administrativa_id' => 122110,
            'siglas_rol'=>'ANA',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Analista');
        User::create([
            'name' => 'Juan Abasolo Alvarado',
            'curp' => 'RORH920920DS8',
            'email' => 'juan.abasolo@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Jefe de Departamento de Seguimiento "A2"',
            'unidad_administrativa_id' => 122120,
            'siglas_rol'=>'JD',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Jefe de Departamento de Seguimiento');        
        User::create([
            'name' => 'Blanca Esthela Sanchez Alfaro',
            'curp' => 'RORH920920DS8',
            'email' => 'blancaesthela.sanchez@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Analista del Departamento de Seguimiento "A2"',
            'unidad_administrativa_id' => 122120,
            'siglas_rol'=>'ANA',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Analista'); 
        
        
        //Dirección B
        User::create([
            'name' => 'Edgar Castellanos Álvarez ',
            'curp' => 'RORH920920DS8',
            'email' => 'edgar.castellanos@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Director del la Dirección de Seguimiento "B"',
            'unidad_administrativa_id' => 122200,
            'siglas_rol'=>'DS',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Director de Seguimiento'); 
        User::create([
            'name' => 'Rodrigo Diaz Lopez',
            'curp' => 'RORH920920DS8',
            'email' => 'rodrigo.diaz@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Lider de Proyecto de la Dirección de Seguimiento "B"',
            'unidad_administrativa_id' => 122200,
            'siglas_rol'=>'LP',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Lider de Proyecto');
        User::create([
            'name' => 'Miriam Eulogio Santos',
            'curp' => 'RORH920920DS8',
            'email' => 'miriam.eulogio@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Lider de Proyecto de la Dirección de Seguimiento "B"',
            'unidad_administrativa_id' => 122200,
            'siglas_rol'=>'LP',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Lider de Proyecto');
        User::create([
            'name' => 'Samantha Anallely Ubando Carbajal',
            'curp' => 'RORH920920DS8',
            'email' => 'samantha.ubando@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Jefe de Departamento de Seguimiento "B1"',
            'unidad_administrativa_id' => 122210,
            'siglas_rol'=>'JD',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Jefe de Departamento de Seguimiento');        
        User::create([
            'name' => 'German Verona Ledezma',
            'curp' => 'RORH920920DS8',
            'email' => 'german.verona@osfem.gob.mx',
            'password' => Hash::make('desa'),
            'puesto' => 'Analista del Departamento de Seguimiento "B1"',
            'unidad_administrativa_id' => 122210,
            'siglas_rol'=>'ANA',
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1,
        ])->assignRole('Analista'); 




        
    }
}
