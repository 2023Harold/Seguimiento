<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccesosImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */

    public function startRow(): int
    {
        return 2;
    }


    public function collection(Collection $rows)
    {
        $permisosGenerales = [];
        $i = 0;
        foreach ($rows as $row) {
            if ($row[2] != '' && $row[2] != '' && $row[2] != null) {
                foreach (explode(',', $row[2]) as $action) {
                    $permisosGenerales[$i] = ['name' => $row[1].'.'.$action, 'guard_name' => 'web'];
                    $i++;
                }
            } else {
                $permisosGenerales[$i] = ['name' => $row[1], 'guard_name' => 'web'];
                $i++;
            }
        }
        Permission::insert($permisosGenerales);


        $roles_accesos = [[], [], [], [], [], [], [], [], [], [], [], [] ];
        foreach ($rows as $row) {
            for ($posicionRol = 0; $posicionRol < 10; $posicionRol++) {
                if ($row[2]) {
                    $roles_accesos = $this->agregarAccesosARolesResource($posicionRol, $roles_accesos, $row[1], $row[$posicionRol + 3]);
                } else {
                    $roles_accesos = $this->agregarAccesosARolesRuta($posicionRol, $roles_accesos, $row[1], $row[$posicionRol + 3]);
                }
            }
        }
        Role::create(['name' => 'Administrador TI'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'Auditor Superior'])->givePermissionTo($roles_accesos[1]);
        Role::create(['name' => 'Administrador del Sistema'])->givePermissionTo($roles_accesos[2]);
        Role::create(['name' => 'Titular Unidad de Seguimiento'])->givePermissionTo($roles_accesos[3]);
        Role::create(['name' => 'Director de Seguimiento'])->givePermissionTo($roles_accesos[4]);
        Role::create(['name' => 'Jefe de Departamento de Seguimiento'])->givePermissionTo($roles_accesos[5]);
        Role::create(['name' => 'Lider de Proyecto'])->givePermissionTo($roles_accesos[6]);
        Role::create(['name' => 'Analista'])->givePermissionTo($roles_accesos[7]);
        Role::create(['name' => 'Staff Juridico'])->givePermissionTo($roles_accesos[8]);
        Role::create(['name' => 'Consultor Administrativo'])->givePermissionTo($roles_accesos[9]);
















       /* if($row['auditor_superior']=='X'){
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
        if($row['staff_juridico']=='X'){
            Role::where('name','Staff Juridico')->first()->givePermissionTo($row['permiso'].(!empty($row['metodo'])?'.'.$row['metodo']:''));
        }
        if($row['consulta_administrador']=='X'){
            Role::where('name','Consultor Administrativo')->first()->givePermissionTo($row['permiso'].(!empty($row['metodo'])?'.'.$row['metodo']:''));
        }*/
    }

    private function agregarAccesosARolesResource($posicionRol, $roles_accesos, $apartado, $metodos)
    {
        if ($metodos) {
            foreach (explode(',', $metodos) as $metodo) {
                array_push($roles_accesos[$posicionRol], $apartado.'.'.$metodo);
            }
        }

        return $roles_accesos;
    }

    private function agregarAccesosARolesRuta($posicionRol, $roles_accesos, $apartado, $hasPermiso)
    {
        if ($hasPermiso) {
            array_push($roles_accesos[$posicionRol], $apartado);
        }

        return $roles_accesos;
    }
}
