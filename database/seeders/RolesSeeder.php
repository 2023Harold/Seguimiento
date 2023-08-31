<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //home
        $home = ['home','auth.logout','notificaciones.index','marcarleido','getCargosAsociados','cotejamiento','firmar','finalizarfirma','finalizarfirmapdf','constancia.mostrarConstancia'];

        //Seguimiento Auditoria
        $seguimientoauditoriaAnalista=['seguimientoauditoria.index','seguimientoauditoria.create','seguimientoauditoria.store','seguimientoauditoria.edit','seguimientoauditoria.update','seguimientoauditoria.show','seguimientoauditoria.destroy','seguimientoauditoria.acciones','seguimientoauditoria.concluir','seguimientoauditoria.accionesconsulta'];
        $seguimientoauditoriaLiderProyecto=['seguimientoauditoria.index','seguimientoauditoria.show','seguimientoauditoria.acciones','seguimientoauditoria.accionesconsulta'];
        $seguimientoauditoriaJefeDepartamento=['seguimientoauditoria.index','seguimientoauditoria.show','seguimientoauditoria.acciones','seguimientoauditoria.accionesconsulta'];
        $seguimientoauditoriaDirector=['seguimientoauditoria.index','seguimientoauditoria.show','seguimientoauditoria.acciones','seguimientoauditoria.accionesconsulta'];
        $seguimientoauditoriaTitular=['seguimientoauditoria.index','seguimientoauditoria.show','seguimientoauditoria.acciones','seguimientoauditoria.accionesconsulta'];
        $seguimientoauditoriaConsulta=['seguimientoauditoria.index','seguimientoauditoria.show','seguimientoauditoria.acciones','seguimientoauditoria.accionesconsulta'];
        
        //Seguimiento Auditoria Acciones
        $seguimientoauditoriaaccionesAnalista=['seguimientoauditoriaacciones.index','seguimientoauditoriaacciones.create','seguimientoauditoriaacciones.store','seguimientoauditoriaacciones.edit','seguimientoauditoriaacciones.update','seguimientoauditoriaacciones.show','seguimientoauditoriaacciones.destroy'];
        $seguimientoauditoriaaccionesLiderProyecto=['seguimientoauditoriaacciones.index','seguimientoauditoriaacciones.show'];
        $seguimientoauditoriaaccionesJefeDepartamento=['seguimientoauditoriaacciones.index','seguimientoauditoriaacciones.show'];
        $seguimientoauditoriaaccionesDirector=['seguimientoauditoriaacciones.index','seguimientoauditoriaacciones.show'];
        $seguimientoauditoriaaccionesTitular=['seguimientoauditoriaacciones.index','seguimientoauditoriaacciones.show'];
        $seguimientoauditoriaaccionesConsulta=['seguimientoauditoriaacciones.index','seguimientoauditoriaacciones.show'];

        //Seguimiento Auditoria Revision Lider Proyecto
        $seguimientoauditoriarevisionLiderProyecto=['seguimientoauditoriarevisionlp.edit','seguimientoauditoriarevisionlp.update'];

        //Seguimiento Auditoria Revisión Jefe de Departamento
        $seguimientoauditoriarevisionJefeDepartamento=['seguimientoauditoriarevision.edit','seguimientoauditoriarevision.update'];

        //Seguimiento Auditoria Validación
        $seguimientoauditoriavalidacionDirector=['seguimientoauditoriavalidacion.edit','seguimientoauditoriavalidacion.update'];

        //Seguimiento Auditoria Autorización
        $seguimientoauditoriaautorizacionTitular=['seguimientoauditoriaautorizacion.edit','seguimientoauditoriaautorizacion.update'];

        //Asignación Auditoria a Dirección
        $asignaciondireccionTitular=['asignaciondireccion.index','asignaciondireccion.edit','asignaciondireccion.update','asignaciondireccion.accionesconsulta','getDirector','asignaciondireccion.reasignar'];

        //Asignación Auditoria a Departamento
        $asignaciondepartamentoDirector=['asignaciondepartamento.index','asignaciondepartamento.edit','asignaciondepartamento.update','asignaciondepartamento.accionesconsulta','getJefeDepartamento','asignaciondepartamento.reasignar'];

        //Asignación Auditoria a Departamento por titular
        $asignaciondepartamentoTitular=['asignaciondepartamento.index','asignaciondepartamento.accionesconsulta','asignaciondepartamento.edit'];

        //Asignaciónes Auditoria Solo Consulta
        $asignacionesConsulta=['asignaciondireccion.index','asignaciondepartamento.index','asignaciondireccion.accionesconsulta','asignaciondepartamento.accionesconsulta'];

        //Asignaciones de Auditorias a Lideres de Proyecto y Analistas
        $asignacionesLiderAnalistaJefeDepartamento=['asignacionlideranalista.index','asignacionlideranalista.edit','asignacionlideranalista.update','asignacionlideranalista.accionesconsulta','asignacionlideranalista.reasignarlider','asignacionlideranalista.reasignaranalista','getAnalista','getLider'];
        
        //Asignaciones de Auditorias a Lideres de Proyecto y Analistas Consulta
        $asignacionesLiderAnalistaConsulta=['asignacionlideranalista.index','asignacionlideranalista.consulta','asignacionlideranalista.accionesconsulta'];

        //Asignacion Departamento Encargado 
        $asignacionesDepartamentoEncargadoDirector=['asignaciondepartamentoencargado.edit','asignaciondepartamentoencargado.update'];
                
        //Radicacion
        $radicacionJefeDepartamento=['radicacion.index','radicacion.auditoria','radicacion.create','radicacion.store','radicacion.edit','radicacion.update','comparecencia.show','comparecenciaacuse.show','comparecenciaacuse.edit','comparecenciaacuse.update'];
       
        //Radicacion Validación Director
        $radicacionvalidacionDirector=['radicacion.index','radicacionvalidacion.edit','radicacionvalidacion.update','comparecencia.show','comparecenciaacuse.show'];
        
        //Radicacion Autorización Titular
        $radicacionautorizacionTitular=['radicacion.index','radicacionautorizacion.edit','radicacionautorizacion.update','comparecencia.show','comparecenciaacuse.show'];
        
        //Rdaicación Consulta
        $radicacionConsulta=['radicacion.index','comparecencia.show','comparecenciaacuse.show'];

        //Comparecencia Titular
        $comparecenciaTitular=['comparecencia.index','comparecenciaacta.show'];

        //Comparecencia Director
        $comparecenciaDirector=['comparecencia.index','comparecenciaacta.show'];
        
        //Comparecencia Jefe de Departamento
        $comparecenciaJefeDepartamento=['comparecencia.index','comparecenciaacta.show','comparecenciaacta.edit','comparecenciaacta.update','getAgendaComparecencias'];
       
       
        //Comparecencia Consulta
        $comparecenciaConsulta=['comparecencia.index','comparecenciaacta.show'];

        //PRASLider
        $prasLider=['pras.index','pras.edit','prasacciones.index','prasacciones.edit','prasturno.index','prasturno.create','prasturno.edit','prasturno.update','prasturnoacuses.edit','prasturnoacuses.update','prasturnoacuses.show'];

        //PRASJefe
        $prasJefe=['pras.index','pras.edit','prasacciones.index','prasacciones.edit','prasturno.index','prasturnorevision.edit','prasturnorevision.update','prasturnoacuses.show'];
        
        //PRASDirector
        $prasDirector=['pras.index','pras.edit','prasacciones.index','prasacciones.edit','prasturno.index','prasturnovalidacion.edit','prasturnovalidacion.update','prasturnoacuses.show'];

        //PRASTitular
        $prasTitular=['pras.index','pras.edit','prasacciones.index','prasacciones.edit','prasturno.index','prasturnoautorizacion.edit','prasturnoautorizacion.update','prasturnoacuses.show'];
        
        //PRASConsulta
        $prasConsulta=['pras.index','pras.edit','prasacciones.index','prasacciones.edit','prasturno.index','prasturnoacuses.show'];
        //*********************************************************************************************************************************************************** */
       
        //Permisos Analista
        $permisosAnalista = array_merge($home, $seguimientoauditoriaAnalista, $seguimientoauditoriaaccionesAnalista);
        
        //Permisos Lider de Proyecto
        $permisosLiderProyecto = array_merge($home, $seguimientoauditoriaLiderProyecto, $seguimientoauditoriaaccionesLiderProyecto,$seguimientoauditoriarevisionLiderProyecto,$prasLider);
        
        //Permisos Jefe de Departamento
        $permisosJefeDepartamento = array_merge($home, $seguimientoauditoriaJefeDepartamento, $seguimientoauditoriaaccionesJefeDepartamento, $seguimientoauditoriarevisionJefeDepartamento,$asignacionesLiderAnalistaJefeDepartamento,$radicacionJefeDepartamento,$comparecenciaJefeDepartamento,$prasJefe);
        
        //Permisos Director
        $permisosDirector = array_merge($home, $seguimientoauditoriaDirector, $seguimientoauditoriaaccionesDirector, $seguimientoauditoriavalidacionDirector,$asignaciondepartamentoDirector,$asignacionesLiderAnalistaConsulta,$radicacionvalidacionDirector,$asignacionesDepartamentoEncargadoDirector,$comparecenciaDirector,$prasDirector);
        
        //Permisos Titular
        $permisosTitular = array_merge($home, $seguimientoauditoriaTitular, $seguimientoauditoriaaccionesTitular, $seguimientoauditoriaautorizacionTitular, $asignaciondireccionTitular, $asignaciondepartamentoTitular,$asignacionesLiderAnalistaConsulta,$radicacionautorizacionTitular,$comparecenciaTitular,$prasTitular);

        //Permisos Usuario Consulta
        $permisosConsulta = array_merge($home, $seguimientoauditoriaConsulta, $seguimientoauditoriaaccionesConsulta,$asignacionesConsulta,$asignacionesLiderAnalistaConsulta,$radicacionConsulta,$comparecenciaConsulta,$prasConsulta);        
        
        
        Role::create(['name' => 'Administrador TI'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'Auditor Superior'])->givePermissionTo($permisosConsulta);
        Role::create(['name' => 'Administrador del Sistema'])->givePermissionTo($permisosConsulta);
        Role::create(['name' => 'Titular Unidad de Seguimiento'])->givePermissionTo($permisosTitular);
        Role::create(['name' => 'Director de Seguimiento'])->givePermissionTo($permisosDirector);
        Role::create(['name' => 'Jefe de Departamento de Seguimiento'])->givePermissionTo($permisosJefeDepartamento);
        Role::create(['name' => 'Lider de Proyecto'])->givePermissionTo($permisosLiderProyecto);        
        Role::create(['name' => 'Analista'])->givePermissionTo($permisosAnalista);
    }
}
