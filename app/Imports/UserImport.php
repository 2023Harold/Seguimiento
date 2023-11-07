<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
   
    public function model(array $row)
    {
        return User::create([
            'usuario_plataforma_id'=>$row['plataforma_id'],
            'name' => $row['nombre'],
            'curp' =>'xxxxxxxxxxxxxxxxxx',
            'email' => $row['email'],            
            'password' => Hash::make('d3s42023'),
            'puesto' => $row['puesto'],
            'unidad_administrativa_id' => $row['unidad_administrativa'],
            'siglas_rol'=>$row['siglas_rol'],
            'estatus' => 'Activo',
            'usuario_creacion_id' => 1
        ])->assignRole($row['rol']);
    }
}
