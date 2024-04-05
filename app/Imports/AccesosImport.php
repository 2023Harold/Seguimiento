<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccesosImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    
    public function model(array $row)
    {
        
        if($row['auditor_superior']=='X'){
            Role::where('name','Auditor Superior')->first()->givePermissionTo($row['permiso'].(!empty($row['metodo'])?'.'.$row['metodo']:''));
        }
        if($row['administrador_sistema']=='X'){
            Role::where('name','Administrador del Sistema')->first()->givePermissionTo($row['permiso'].(!empty($row['metodo'])?'.'.$row['metodo']:''));
        }
        if($row['titular_unidad_seguimiento']=='X'){
            Role::where('name','Titular Unidad de Seguimiento')->first()->givePermissionTo($row['permiso'].(!empty($row['metodo'])?'.'.$row['metodo']:''));
        }
        if($row['director_seguimiento']=='X'){
            Role::where('name','Director de Seguimiento')->first()->givePermissionTo($row['permiso'].(!empty($row['metodo'])?'.'.$row['metodo']:''));
        }
        if($row['jefe_departamento_seguimiento']=='X'){
            Role::where('name','Jefe de Departamento de Seguimiento')->first()->givePermissionTo($row['permiso'].(!empty($row['metodo'])?'.'.$row['metodo']:''));
        }
        if($row['lider_proyecto']=='X'){
            Role::where('name','Lider de Proyecto')->first()->givePermissionTo($row['permiso'].(!empty($row['metodo'])?'.'.$row['metodo']:''));
        }
        if($row['analista']=='X'){
            Role::where('name','Analista')->first()->givePermissionTo($row['permiso'].(!empty($row['metodo'])?'.'.$row['metodo']:''));
        }
    }
}
