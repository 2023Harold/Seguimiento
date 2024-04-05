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
                        'user',
                        'permiso',
                        'rol',
                        'acceso',
                        'seguimientoauditoria',
                        'seguimientoauditoriaacciones',
                        'seguimientoauditoriaaccionrevision01',
                        'seguimientoauditoriaaccionrevision',
                        'seguimientoauditoriarevision01',
                        'seguimientoauditoriarevision',
                        'seguimientoauditoriavalidacion',
                        'seguimientoauditoriaautorizacion',                      
                        'asignaciondireccion',
                        'asignaciondepartamento',
                        'asignacionlideranalista',
                        'asignaciondepartamentoencargado',
                        'auditoriaseguimiento',
                        'auditoriaseguimientoacciones',
                        'auditoriaconsultaacciones',
                        'radicacion',
                        'radicacionvalidacion',
                        'radicacionautorizacion',
                        'comparecenciaacuse',
                        'comparecenciaagenda',
                        'comparecencia',                    
                        'comparecenciaacta',                      
                        'pras',
                        'prasacciones',
                        'prasturno',
                        'prasturnorevision',
                        'prasturnovalidacion',
                        'prasturnoautorizacion',
                        'prasturnoacuses',
                        'prasseguimiento',
                        'prasmedida',
                        'recomendaciones',
                        'recomendacionesacciones',
                        'recomendacionesatencion',
                        'recomendacionescalificacion',
                        'recomendacionesdocumentos',
                        'recomendacionescontestaciones',                       
                        'recomendacionesanalisis',
                        'recomendacionesanexos',
                        'recomendacionesanalisisenvio',
                        'recomendacionesrevision01',
                        'recomendacionesrevision',
                        'recomendacionesvalidacion',
                        'recomendacionesautorizacion',
                        'recomendacionesacuses',
                        'revisionesrecomendaciones',
                        'revisionesrecomendacionesatencion',
                        'cedulainicial',
                        'solicitudesaclaracion',
                        'solicitudesaclaracionacciones',
                        'solicitudesaclaracionatencion',
                        'solicitudesaclaracioncontestacion',
                        'solicitudesaclaraciondocumentos',
                        'solicitudesaclaracionanalisis',
                        'solicitudesaclanalisisenvio',
                        'solicitudesaclaracionrevision01',
                        'solicitudesaclaracionrevision',
                        'solicitudesaclaracionvalidacion',
                        'solicitudesaclaracionautorizacion',
                        'solicitudesaclaracionanexos',
                        'revisionessolicitudes',
                        'revisionessolicitudesatencion',
                        'pliegosobservacion',
                        'pliegosobservacionacciones',
                        'pliegosobservacionatencion',
                        'pliegosobservacionatencioncontestacion',
                        'pliegosobservaciondocumentos',
                        'pliegosobservacionanalisis',
                        'pliegosobservacionanexos',
                        'pliegosobservacionanalisisenvio',
                        'pliegosobservacionrevision01',
                        'pliegosobservacionrevision',
                        'pliegosobservacionvalidacion',
                        'pliegosobservacionautorizacion',
                        'revisionespliegos',
                        'revisionespliegosatencion',
                        'informeprimeraetapa',
                        'cedulainicialprimera',
                        'cedulainicialprimeraanalista',
                        'cedulainicialprimerarevision01',
                        'cedulainicialprimerarevision',
                        'cedulainicialprimeravalidacion',
                        'cedulainicialprimeraautorizacion',
                        'cedulageneralrecomendacion',
                        'cedgralrecomendacionanalista',
                        'cedgralrecomendacionrevision01',
                        'cedgralrecomendacionrevision',
                        'cedgralrecomendacionvalidacion',
                        'cedgralrecomendacionautorizacion',
                        'cedulageneralpras',
                        'cedulageneralprasanalista',
                        'cedulageneralpraslider',
                        'cedulageneralprasrevision01',
                        'cedulageneralprasrevision',
                        'cedulageneralprasvalidacion',
                        'cedulageneralprasautorizacion',
                        'cedulaanalitica',
                        'cedulaanaliticaanalista',
                        'cedulaanaliticarevision01',
                        'cedulaanaliticarevision',
                        'cedulaanaliticavalidacion',
                        'cedulaanaliticaautorizacion',
                        'cedulaanaliticadesemp',
                        'cedanadesempanalista',
                        'cedanadesemprevision01',
                        'cedanadesemprevision',
                        'cedanadesempvalidacion',
                        'cedanadesempautorizacion',
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
        Permission::create(['name' => 'auth.logout']);
        Permission::create(['name' => 'cotejamiento']);
        Permission::create(['name' => 'getCargosAsociados']);
        Permission::create(['name' => 'archivo']);
        Permission::create(['name' => 'setPermission']);
        Permission::create(['name' => 'quicklogin.loginas']);
        Permission::create(['name' => 'quicklogin.loginasuser']);
        Permission::create(['name' => 'home']);
        Permission::create(['name' => 'notificaciones.index']);
        Permission::create(['name' => 'marcarleido']);
        Permission::create(['name' => 'firmar']);
        Permission::create(['name' => 'finalizarfirma']);
        Permission::create(['name' => 'finalizarfirmapdf']);
        Permission::create(['name' => 'constancia.mostrarConstancia']);        
        Permission::create(['name' => 'seguimientoauditoria.acciones']);
        Permission::create(['name' => 'seguimientoauditoria.accion']);
        Permission::create(['name' => 'seguimientoauditoria.concluir']);
        Permission::create(['name' => 'seguimientoauditoria.accionesconsulta']);       
        Permission::create(['name' => 'asignaciondireccion.accionesconsulta']);
        Permission::create(['name' => 'asignacion.accion']);
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
        Permission::create(['name' => 'auditoriaseguimiento.accionesconsulta']);
        Permission::create(['name' => 'getAgendaComparecencias']);
        Permission::create(['name' => 'comparecencia.auditoria']);
        Permission::create(['name' => 'recomendacionescontestaciones.oficiosrecomendacion']);
        Permission::create(['name' => 'recomendacion.anexos']);  
        Permission::create(['name' => 'solicitudescontestaciones.oficiossolicitud']);
        Permission::create(['name' => 'solicitudes.anexos']);
        Permission::create(['name' => 'pliegos.anexos']);
        Permission::create(['name' => 'pliegosobservacioncontestacion.oficiospliegosobservacion']);     
    }
}
