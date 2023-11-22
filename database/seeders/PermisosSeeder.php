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
        $controllers = [
                        'user','permiso','rol','acceso',
                        'seguimientoauditoria','seguimientoauditoriaacciones',
                        'seguimientoauditoriarevision01','seguimientoauditoriarevision',
                        'seguimientoauditoriavalidacion','seguimientoauditoriaautorizacion',
                        'seguimientoauditoriaaccionrevision01','seguimientoauditoriaaccionrevision',
                        'asignaciondireccion','asignaciondepartamento','asignacionlideranalista',
                        'asignaciondepartamentoencargado','auditoriaseguimiento','auditoriaseguimientoacciones',
                        'auditoriaconsultaacciones','radicacion','radicacionvalidacion',
                        'radicacionautorizacion','comparecencia','comparecenciaacuse','comparecenciaacta','comparecenciaagenda',
                        'pras','prasacciones','prasturno','prasturnorevision','prasturnovalidacion',
                        'prasturnoautorizacion','prasturnoacuses','recomendaciones','recomendacionesacciones',
                        'recomendacionesatencion','recomendacionescontestaciones','recomendacionescalificacion','recomendacionesdocumentos',
                        'recomendacionesanalisis','recomendacionesanexos','recomendacionesanalisisenvio','recomendacionesrevision01','recomendacionesrevision',
                        'recomendacionesvalidacion','recomendacionesautorizacion','recomendacionesacuses',
                        'revisionesrecomendaciones','revisionesrecomendacionesatencion','cedulainicial',
                        'solicitudesaclaracion','solicitudesaclaracionacciones','solicitudesaclaracionatencion',
                        'solicitudesaclaracioncontestacion','solicitudesaclaraciondocumentos','solicitudesaclaracionanalisis','solicitudesaclaracionanexos',
                        'solicitudesaclanalisisenvio','solicitudesaclaracionrevision01','solicitudesaclaracionrevision',
                        'solicitudesaclaracionvalidacion','solicitudesaclaracionautorizacion','revisionessolicitudes',
                        'revisionessolicitudesatencion','pliegosobservacion','pliegosobservacionacciones','pliegosobservacionatencion',
                        'pliegosobservacionatencioncontestacion','pliegosobservaciondocumentos','pliegosobservacionanalisis','pliegosobservacionanexos',
                        'pliegosobservacionanalisisenvio','pliegosobservacionrevision01','pliegosobservacionrevision',
                        'pliegosobservacionvalidacion','pliegosobservacionautorizacion','revisionespliegos',
                        'revisionespliegosatencion','informeprimeraetapa'
                    ];
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
        Permission::create(['name' => 'setPermission']);
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
        Permission::create(['name' => 'solicitudescontestaciones.oficiossolicitud']);
        Permission::create(['name' => 'pliegosobservacioncontestacion.oficiospliegosobservacion']);
        Permission::create(['name' => 'auditoriaseguimiento.accionesconsulta']);
        Permission::create(['name' => 'recomendacionescontestaciones.oficiosrecomendacion']);
        Permission::create(['name' => 'solicitudes.anexos']);
        Permission::create(['name' => 'pliegos.anexos']);
        Permission::create(['name' => 'recomendacion.anexos']);
    }
}
