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

        //Recomendaciones
        $recomendacionesAnalista=['recomendaciones.index','recomendaciones.edit','recomendacionesacciones.index','recomendacionesacciones.edit','recomendacionesatencion.index','recomendacionesatencion.create','recomendacionesatencion.store','recomendacionesatencion.edit','recomendacionesatencion.update','recomendacionesdocumentos.index','recomendacionesdocumentos.create','recomendacionesacuses.store','recomendacionesdocumentos.destroy','recomendacionesanalisisenvio.edit'];
        $recomendacionesLider=['recomendaciones.index','recomendaciones.edit','recomendacionesacciones.index','recomendacionesacciones.edit','recomendacionesatencion.index', 'recomendacionescalificacion.show','recomendacionesrevision01.edit','recomendacionesrevision01.update'];
        $recomendacionesJefe=['recomendaciones.index','recomendaciones.edit','recomendacionesacciones.index','recomendacionesacciones.edit','recomendacionesatencion.index','recomendacionescalificacion.edit','recomendacionescalificacion.update','recomendacionescalificacion.show','recomendacionesrevision.edit','recomendacionesrevision.update'];
        $recomendacionesDirector=['recomendaciones.index','recomendaciones.edit','recomendacionesacciones.index','recomendacionesacciones.edit','recomendacionesatencion.index','recomendacionescalificacion.show','recomendacionesvalidacion.edit','recomendacionesvalidacion.update'];
        $recomendacionesTitular=['recomendaciones.index','recomendaciones.edit','recomendacionesacciones.index','recomendacionesacciones.edit','recomendacionesatencion.index','recomendacionescalificacion.show','recomendacionesautorizacion.edit','recomendacionesautorizacion.update'];
        $recomendacionesConsulta=['recomendaciones.index','recomendaciones.edit','recomendacionesacciones.index','recomendacionesacciones.edit','recomendacionesatencion.index','recomendacionescalificacion.show'];

        /*Cedula inicial*/
        $cedulaInicial = ['cedulainicial.index','cedulainicial.edit'];


        /*Solicitudes de aclaracion*/
        $solicitudesAnalista=['solicitudesaclaracion.index','solicitudesaclaracion.edit','solicitudesaclaracionacciones.index','solicitudesaclaracionacciones.edit','solicitudesaclaracionatencion.index'];
        $solicitudesContestacionesAnalista=['solicitudesaclaracioncontestacion.index','solicitudesaclaracioncontestacion.create','solicitudesaclaracioncontestacion.store','solicitudesaclaracioncontestacion.show','solicitudesaclaracioncontestacion.edit','solicitudesaclaracioncontestacion.update','solicitudesaclaracioncontestacion.destroy','solicitudescontestaciones.oficiossolicitud'];
        $solicitudesDocumentosAnalista=['solicitudesaclaraciondocumentos.index','solicitudesaclaraciondocumentos.create','solicitudesaclaraciondocumentos.store','solicitudesaclaraciondocumentos.show','solicitudesaclaraciondocumentos.destroy','solicitudesaclaraciondocumentos.edit','solicitudesaclaraciondocumentos.update'];
        $solicitudesAnalisisAnalista=['solicitudesaclaracionanalisis.edit','solicitudesaclaracionanalisis.update','solicitudesaclaracionanalisis.show'];
        $solicitudesAnalisisEnvioAnalista=['solicitudesaclanalisisenvio.edit'];
        $solicitudesCalificacionAnalista=['solicitudesaclaracioncalificacion.show'];

        $solicitudesLider=['solicitudesaclaracion.index','solicitudesaclaracion.edit','solicitudesaclaracionacciones.index','solicitudesaclaracionacciones.edit','solicitudesaclaracionatencion.index'];
        $solicitudesContestacionesLider=['solicitudesaclaracioncontestacion.index','solicitudesaclaracioncontestacion.show','solicitudescontestaciones.oficiossolicitud'];
        $solicitudesDocumentosLider=['solicitudesaclaraciondocumentos.index','solicitudesaclaraciondocumentos.show'];
        $solicitudesAnalisisLider=['solicitudesaclaracionanalisis.show'];
        $solicitudesAnalisisRevisionLider=['solicitudesaclaracionrevision01.edit','solicitudesaclaracionrevision01.update'];
        $solicitudesCalificacionLider=['solicitudesaclaracioncalificacion.show'];

        $solicitudesJefe=['solicitudesaclaracion.index','solicitudesaclaracion.edit','solicitudesaclaracionacciones.index','solicitudesaclaracionacciones.edit','solicitudesaclaracionatencion.index'];
        $solicitudesContestacionesJefe=['solicitudesaclaracioncontestacion.index','solicitudesaclaracioncontestacion.show','solicitudescontestaciones.oficiossolicitud'];
        $solicitudesDocumentosJefe=['solicitudesaclaraciondocumentos.index','solicitudesaclaraciondocumentos.show'];
        $solicitudesAnalisisJefe=['solicitudesaclaracionanalisis.show'];
        $solicitudesAnalisisRevisionJefe=['solicitudesaclaracionrevision.edit','solicitudesaclaracionrevision.update'];
        $solicitudesCalificacionJefe=['solicitudesaclaracioncalificacion.show','solicitudesaclaracioncalificacion.edit','solicitudesaclaracioncalificacion.update'];

        $solicitudesDirector=['solicitudesaclaracion.index','solicitudesaclaracion.edit','solicitudesaclaracionacciones.index','solicitudesaclaracionacciones.edit','solicitudesaclaracionatencion.index'];
        $solicitudesContestacionesDirector=['solicitudesaclaracioncontestacion.index','solicitudesaclaracioncontestacion.show','solicitudescontestaciones.oficiossolicitud'];
        $solicitudesDocumentosDirector=['solicitudesaclaraciondocumentos.index','solicitudesaclaraciondocumentos.show'];
        $solicitudesAnalisisDirector=['solicitudesaclaracionanalisis.show'];
        $solicitudesCalificacionDirector=['solicitudesaclaracioncalificacion.show'];
        $solicitudesCalificacionValidacionDirector=['solicitudesaclaracionvalidacion.edit','solicitudesaclaracionvalidacion.update'];

        $solicitudesTitular=['solicitudesaclaracion.index','solicitudesaclaracion.edit','solicitudesaclaracionacciones.index','solicitudesaclaracionacciones.edit','solicitudesaclaracionatencion.index'];
        $solicitudesContestacionesTitular=['solicitudesaclaracioncontestacion.index','solicitudesaclaracioncontestacion.show','solicitudescontestaciones.oficiossolicitud'];
        $solicitudesDocumentosTitular=['solicitudesaclaraciondocumentos.index','solicitudesaclaraciondocumentos.show'];
        $solicitudesAnalisisTitular=['solicitudesaclaracionanalisis.show'];
        $solicitudesCalificacionTitular=['solicitudesaclaracioncalificacion.show'];
        $solicitudesCalificacionAutorizacionTitular=['solicitudesaclaracionautorizacion.edit','solicitudesaclaracionautorizacion.update'];

        $solicitudesConsulta=['solicitudesaclaracion.index','solicitudesaclaracion.edit','solicitudesaclaracionacciones.index','solicitudesaclaracionacciones.edit','solicitudesaclaracionatencion.index'];
        $solicitudesContestacionesConsulta=['solicitudesaclaracioncontestacion.index','solicitudesaclaracioncontestacion.show','solicitudescontestaciones.oficiossolicitud'];
        $solicitudesDocumentosConsulta=['solicitudesaclaraciondocumentos.index','solicitudesaclaraciondocumentos.show'];
        $solicitudesAnalisisConsulta=['solicitudesaclaracionanalisis.show'];
        $solicitudesCalificacionConsulta=['solicitudesaclaracioncalificacion.show'];



        /*Pliegos de observacion*/
        $pliegosAnalista=['pliegosobservacion.index','pliegosobservacion.edit','pliegosobservacionacciones.index','pliegosobservacionacciones.edit','pliegosobservacionatencion.index'];
        $pliegosContestacionesAnalista=['pliegosobservacionatencioncontestacion.index','pliegosobservacionatencioncontestacion.create','pliegosobservacionatencioncontestacion.store','pliegosobservacionatencioncontestacion.show','pliegosobservacionatencioncontestacion.edit','pliegosobservacionatencioncontestacion.update','pliegosobservacionatencioncontestacion.destroy','pliegosobservacioncontestacion.oficiospliegosobservacion'];
        $pliegosDocumentosAnalista=['pliegosobservaciondocumentos.index','pliegosobservaciondocumentos.create','pliegosobservaciondocumentos.store','pliegosobservaciondocumentos.show','pliegosobservaciondocumentos.destroy'];
        $pliegosAnalisisAnalista=['pliegosobservacionanalisis.edit','pliegosobservacionanalisis.update','pliegosobservacionanalisis.show'];
        $pliegosAnalisisEnvioAnalista=['pliegosobservacionanalisisenvio.edit'];
        $pliegosCalificacionAnalista=['pliegosatencioncalificacion.show'];

        $pliegosLider=['pliegosobservacion.index','pliegosobservacion.edit','pliegosobservacionacciones.index','pliegosobservacionacciones.edit','pliegosobservacionatencion.index'];
        $pliegosContestacionesLider=['pliegosobservacionatencioncontestacion.index','pliegosobservacionatencioncontestacion.show','pliegosobservacioncontestacion.oficiospliegosobservacion'];
        $pliegosDocumentosLider=['pliegosobservaciondocumentos.index','pliegosobservaciondocumentos.show'];
        $pliegosAnalisisLider=['pliegosobservacionanalisis.show'];
        $pliegosAnalisisRevisionLider=['pliegosobservacionrevision01.edit','pliegosobservacionrevision01.update'];
        $pliegosCalificacionLider=['pliegosatencioncalificacion.show'];

        $pliegosJefe=['pliegosobservacion.index','pliegosobservacion.edit','pliegosobservacionacciones.index','pliegosobservacionacciones.edit','pliegosobservacionatencion.index'];
        $pliegosContestacionesJefe=['pliegosobservacionatencioncontestacion.index','pliegosobservacionatencioncontestacion.show','pliegosobservacioncontestacion.oficiospliegosobservacion'];
        $pliegosDocumentosJefe=['pliegosobservaciondocumentos.index','pliegosobservaciondocumentos.show'];
        $pliegosAnalisisJefe=['pliegosobservacionanalisis.show'];
        $pliegosAnalisisRevisionJefe=['pliegosobservacionrevision.edit','pliegosobservacionrevision.update'];
        $pliegosCalificacionJefe=['pliegosatencioncalificacion.show','pliegosatencioncalificacion.edit','pliegosatencioncalificacion.update'];

        $pliegosDirector=['pliegosobservacion.index','pliegosobservacion.edit','pliegosobservacionacciones.index','pliegosobservacionacciones.edit','pliegosobservacionatencion.index'];
        $pliegosContestacionesDirector=['pliegosobservacionatencioncontestacion.index','pliegosobservacionatencioncontestacion.show','pliegosobservacioncontestacion.oficiospliegosobservacion'];
        $pliegosDocumentosDirector=['pliegosobservaciondocumentos.index','pliegosobservaciondocumentos.show'];
        $pliegosAnalisisDirector=['pliegosobservacionanalisis.show'];
        $pliegosCalificacionDirector=['pliegosatencioncalificacion.show'];
        $pliegosCalificacionValidacionDirector=['pliegosobservacionvalidacion.edit','pliegosobservacionvalidacion.update'];

        $pliegosTitular=['pliegosobservacion.index','pliegosobservacion.edit','pliegosobservacionacciones.index','pliegosobservacionacciones.edit','pliegosobservacionatencion.index'];
        $pliegosContestacionesTitular=['pliegosobservacionatencioncontestacion.index','pliegosobservacionatencioncontestacion.show','pliegosobservacioncontestacion.oficiospliegosobservacion'];
        $pliegosDocumentosTitular=['pliegosobservaciondocumentos.index','pliegosobservaciondocumentos.show'];
        $pliegosAnalisisTitular=['pliegosobservacionanalisis.show'];
        $pliegosCalificacionTitular=['pliegosatencioncalificacion.show'];
        $pliegosCalificacionAutorizacionTitular=['pliegosobservacionautorizacion.edit','pliegosobservacionautorizacion.update'];

        $pliegosConsulta=['pliegosobservacion.index','pliegosobservacion.edit','pliegosobservacionacciones.index','pliegosobservacionacciones.edit','pliegosobservacionatencion.index'];
        $pliegosContestacionesConsulta=['pliegosobservacionatencioncontestacion.index','pliegosobservacionatencioncontestacion.show','pliegosobservacioncontestacion.oficiospliegosobservacion'];
        $pliegosDocumentosConsulta=['pliegosobservaciondocumentos.index','pliegosobservaciondocumentos.show'];
        $pliegosAnalisisConsulta=['pliegosobservacionanalisis.show'];
        $pliegosCalificacionConsulta=['pliegosatencioncalificacion.show'];



        //*********************************************************************************************************************************************************** */

        //Permisos Analista
        $permisosAnalista = array_merge($home,$seguimientoauditoriaAnalista, $seguimientoauditoriaaccionesAnalista,$recomendacionesAnalista,$cedulaInicial,$radicacionConsulta,$comparecenciaConsulta,$solicitudesAnalista,$solicitudesContestacionesAnalista,$solicitudesDocumentosAnalista,$solicitudesAnalisisAnalista,$solicitudesAnalisisEnvioAnalista,$solicitudesCalificacionAnalista,$pliegosAnalista,$pliegosContestacionesAnalista,$pliegosDocumentosAnalista,$pliegosAnalisisAnalista,$pliegosAnalisisEnvioAnalista,$pliegosCalificacionAnalista);

        //Permisos Lider de Proyecto
        $permisosLiderProyecto = array_merge($home, $seguimientoauditoriaLiderProyecto, $seguimientoauditoriaaccionesLiderProyecto,$seguimientoauditoriarevisionLiderProyecto,$prasLider,$recomendacionesLider,$cedulaInicial,$radicacionConsulta,$comparecenciaConsulta,$solicitudesLider,$solicitudesContestacionesLider,$solicitudesDocumentosLider,$solicitudesAnalisisLider,$solicitudesAnalisisRevisionLider,$solicitudesCalificacionLider,$pliegosLider,$pliegosContestacionesLider,$pliegosDocumentosLider,$pliegosAnalisisLider,$pliegosAnalisisRevisionLider,$pliegosCalificacionLider);

        //Permisos Jefe de Departamento
        $permisosJefeDepartamento = array_merge($home, $seguimientoauditoriaJefeDepartamento, $seguimientoauditoriaaccionesJefeDepartamento, $seguimientoauditoriarevisionJefeDepartamento,$asignacionesLiderAnalistaJefeDepartamento,$radicacionJefeDepartamento,$comparecenciaJefeDepartamento,$prasJefe,$recomendacionesJefe,$cedulaInicial,$solicitudesJefe,$solicitudesContestacionesJefe,$solicitudesDocumentosJefe,$solicitudesAnalisisJefe,$solicitudesAnalisisRevisionJefe,$solicitudesCalificacionJefe,   $pliegosJefe,$pliegosContestacionesJefe,$pliegosDocumentosJefe,$pliegosAnalisisJefe,$pliegosAnalisisRevisionJefe,$pliegosCalificacionJefe);

        //Permisos Director
        $permisosDirector = array_merge($home, $seguimientoauditoriaDirector, $seguimientoauditoriaaccionesDirector, $seguimientoauditoriavalidacionDirector,$asignaciondepartamentoDirector,$asignacionesLiderAnalistaConsulta,$radicacionvalidacionDirector,$asignacionesDepartamentoEncargadoDirector,$comparecenciaDirector,$prasDirector,$recomendacionesDirector,$cedulaInicial,$solicitudesDirector,$solicitudesContestacionesDirector,$solicitudesDocumentosDirector,$solicitudesAnalisisDirector,$solicitudesCalificacionDirector,$solicitudesCalificacionValidacionDirector,$pliegosDirector,$pliegosContestacionesDirector,$pliegosDocumentosDirector,$pliegosAnalisisDirector,$pliegosCalificacionDirector,$pliegosCalificacionValidacionDirector);

        //Permisos Titular
        $permisosTitular = array_merge($home, $seguimientoauditoriaTitular, $seguimientoauditoriaaccionesTitular, $seguimientoauditoriaautorizacionTitular, $asignaciondireccionTitular, $asignaciondepartamentoTitular,$asignacionesLiderAnalistaConsulta,$radicacionautorizacionTitular,$comparecenciaTitular,$prasTitular,$recomendacionesTitular,$cedulaInicial,$solicitudesTitular,$solicitudesContestacionesTitular,$solicitudesDocumentosTitular,$solicitudesAnalisisTitular,$solicitudesCalificacionTitular,$solicitudesCalificacionAutorizacionTitular,$pliegosTitular,$pliegosContestacionesTitular,$pliegosDocumentosTitular,$pliegosAnalisisTitular,$pliegosCalificacionTitular,$pliegosCalificacionAutorizacionTitular);

        //Permisos Usuario Consulta
        $permisosConsulta = array_merge($home, $seguimientoauditoriaConsulta, $seguimientoauditoriaaccionesConsulta,$asignacionesConsulta,$asignacionesLiderAnalistaConsulta,$radicacionConsulta,$comparecenciaConsulta,$prasConsulta,$recomendacionesConsulta,$cedulaInicial,$solicitudesConsulta,$solicitudesContestacionesConsulta,$solicitudesDocumentosConsulta,$solicitudesAnalisisConsulta,$solicitudesCalificacionConsulta,$pliegosConsulta,$pliegosContestacionesConsulta,$pliegosDocumentosConsulta,$pliegosAnalisisConsulta,$pliegosCalificacionConsulta);


        // Role::create(['name' => 'Administrador TI'])->givePermissionTo(Permission::all());
        // Role::create(['name' => 'Auditor Superior'])->givePermissionTo($permisosConsulta);
        // Role::create(['name' => 'Administrador del Sistema'])->givePermissionTo($permisosConsulta);
        // Role::create(['name' => 'Titular Unidad de Seguimiento'])->givePermissionTo($permisosTitular);
        // Role::create(['name' => 'Director de Seguimiento'])->givePermissionTo($permisosDirector);
        // Role::create(['name' => 'Jefe de Departamento de Seguimiento'])->givePermissionTo($permisosJefeDepartamento);
        // Role::create(['name' => 'Lider de Proyecto'])->givePermissionTo($permisosLiderProyecto);
        // Role::create(['name' => 'Analista'])->givePermissionTo($permisosAnalista);


        Role::where('name','Administrador TI')->first()->givePermissionTo(Permission::all());
        Role::where('name','Auditor Superior')->first()->givePermissionTo($permisosConsulta);
        Role::where('name','Administrador del Sistema')->first()->givePermissionTo($permisosConsulta);
        Role::where('name','Titular Unidad de Seguimiento')->first()->givePermissionTo($permisosTitular);
        Role::where('name','Director de Seguimiento')->first()->givePermissionTo($permisosDirector);
        Role::where('name','Jefe de Departamento de Seguimiento')->first()->givePermissionTo($permisosJefeDepartamento);
        Role::where('name','Lider de Proyecto')->first()->givePermissionTo($permisosLiderProyecto);
        Role::where('name','Analista')->first()->givePermissionTo($permisosAnalista);
    }
}
