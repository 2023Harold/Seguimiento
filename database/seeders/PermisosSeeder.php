<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actions = ['index', 'create', 'store', 'edit', 'update', 'show', 'destroy'];
        $controllers = ['seguimientoauditoria','seguimientoauditoriaacciones',
                        'seguimientoauditoriarevisionlp','seguimientoauditoriarevision',
                        'seguimientoauditoriavalidacion','seguimientoauditoriaautorizacion',
                        'asignaciondireccion','asignaciondepartamento','asignacionlideranalista',
                        'asignaciondepartamentoencargado','radicacion','radicacionvalidacion',
                        'radicacionautorizacion','comparecencia','comparecenciaacuse','comparecenciaacta',
                        'pras','prasacciones','prasturno','prasturnorevision','prasturnovalidacion',
                        'prasturnoautorizacion','prasturnoacuses','recomendaciones','recomendacionesacciones'
                        ,'recomendacionesatencion','recomendacionescalificacion','recomendacionesdocumentos'
                        ,'recomendacionesrevision01','recomendacionesrevision','recomendacionesvalidacion'
                        ,'recomendacionesautorizacion','recomendacionesacuses','cedulainicial'
                        ,'solicitudesaclaracion','solicitudesaclaracionacciones','solicitudesaclaracioncontestacion'
                        ,'solicitudesaclaracioncalificacion','solicitudesaclaraciondocumentos','solicitudesaclaracionrevision01'
                        ,'solicitudesaclaracionrevision','solicitudesaclaracionvalidacion','solicitudesaclaracionautorizacion'];
        $permisosGenerales = [];
        $i = 0;

        foreach ($controllers as $controller) {
            foreach ($actions as $action) {
                // Using route sintax
                $permisosGenerales[$i] = ['name' => $controller.'.'.$action, 'guard_name' => 'web'];
                $i++;
            }
        }

        Permission::insert($permisosGenerales);
        Permission::create(['name' => 'home']);
        Permission::create(['name' => 'auth.logout']);
        Permission::create(['name' => 'firmar']);
        Permission::create(['name' => 'finalizarfirma']);
        Permission::create(['name' => 'finalizarfirmapdf']);
        Permission::create(['name' => 'constancia.mostrarConstancia']);
        Permission::create(['name' => 'cotejamiento']);
        Permission::create(['name' => 'seguimientoauditoria.acciones']);
        Permission::create(['name' => 'seguimientoauditoria.concluir']);
        Permission::create(['name' => 'seguimientoauditoria.accionesconsulta']);
        Permission::create(['name' => 'notificaciones.index']);
        Permission::create(['name' => 'marcarleido']);
        Permission::create(['name' => 'getCargosAsociados']);
        Permission::create(['name' => 'asignaciondireccion.accionesconsulta']);
        Permission::create(['name' => 'getDirector']);
        Permission::create(['name' => 'asignaciondireccion.reasignar']);
        Permission::create(['name' => 'asignaciondepartamento.accionesconsulta']);
        Permission::create(['name' => 'getJefeDepartamento']);
        Permission::create(['name' => 'asignaciondepartamento.reasignar']);
        Permission::create(['name' => 'getLider']);
        Permission::create(['name' => 'getAnalista']);
        Permission::create(['name' => 'asignacionlideranalista.reasignarlider']);
        Permission::create(['name' => 'asignacionlideranalista.reasignaranalista']);
        Permission::create(['name' => 'asignacionlideranalista.accionesconsulta']);
        Permission::create(['name' => 'asignacionlideranalista.consulta']);
        Permission::create(['name' => 'radicacion.auditoria']);
        Permission::create(['name' => 'getAgendaComparecencias']);
    }
}
