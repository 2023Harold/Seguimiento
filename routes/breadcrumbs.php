<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('cphome', function (BreadcrumbTrail $trail) {
    $trail->push('Cuenta Pública '.getSession('cp'), route('cphome'));
});

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->parent('cphome');
    $trail->push('Inicio', route('home'));
});

Breadcrumbs::for('administracion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('cphome');
    $trail->push('Administración', route('administracion.index'));
});

Breadcrumbs::for('asignacionunidadadministrativa.index', function (BreadcrumbTrail $trail) {
    $trail->parent('administracion.index');
    $trail->push('Asignación Unidad Administrativa', route('asignacionunidadadministrativa.index'));
});

Breadcrumbs::for('asignacionunidadadministrativa.edit', function (BreadcrumbTrail $trail,$user) {
    $trail->parent('administracion.index');
    $trail->push('Asignación Unidad Administrativa 2021', route('asignacionunidadadministrativa.edit',$user));
});
Breadcrumbs::for('asignacionunidadadministrativa2022.edit', function (BreadcrumbTrail $trail,$user) {
    $trail->parent('administracion.index');
    $trail->push('Asignación Unidad Administrativa 2022', route('asignacionunidadadministrativa2022.edit',$user));
});
Breadcrumbs::for('asignacionunidadadministrativa2023.edit', function (BreadcrumbTrail $trail,$user) {
    $trail->parent('administracion.index');
    $trail->push('Asignación Unidad Administrativa 2023', route('asignacionunidadadministrativa2023.edit',$user));
});

Breadcrumbs::for('notificaciones', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Notificaciones', route('notificaciones.index'));
});

Breadcrumbs::for('user.index', function (BreadcrumbTrail $trail) {
    $trail->parent('administracion.index');
    $trail->push('Usuarios', route('user.index'));
});

Breadcrumbs::for('rol.index', function (BreadcrumbTrail $trail) {
    $trail->parent('administracion.index');
    $trail->push('Catálogo de Roles', route('rol.index'));
});

Breadcrumbs::for('rol.create', function (BreadcrumbTrail $trail) {
    $trail->parent('rol.index');
    $trail->push('Agregar', route('rol.create'));
});

Breadcrumbs::for('rol.edit', function (BreadcrumbTrail $trail,$rol) {
    $trail->parent('rol.index');
    $trail->push('Editar', route('rol.edit',$rol));
});

Breadcrumbs::for('permiso.index', function (BreadcrumbTrail $trail) {
    $trail->parent('administracion.index');
    $trail->push('Catálogo de Permisos', route('permiso.index'));
});

Breadcrumbs::for('permiso.create', function (BreadcrumbTrail $trail) {
    $trail->parent('permiso.index');
    $trail->push('Agregar', route('permiso.create'));
});

Breadcrumbs::for('permiso.edit', function (BreadcrumbTrail $trail,$rol) {
    $trail->parent('permiso.index');
    $trail->push('Editar', route('permiso.edit',$rol));
});

//home

Breadcrumbs::for('acceso.index', function (BreadcrumbTrail $trail) {
    $trail->parent('administracion.index');
    $trail->push('Accesos', route('acceso.index'));
});

Breadcrumbs::for('seguimientoauditorias', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Auditorias', route('seguimientoauditoria.index'));
});

//tipologiaauditorias

Breadcrumbs::for('tipologiaauditorias', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Tipología Auditorias', route('tipologiaauditorias.index'));
});

Breadcrumbs::for('tipologiaauditorias.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('tipologiaauditorias.index');
    $trail->push('Auditoría', route('tipologiaauditorias.edit',$auditoria));
});

Breadcrumbs::for('tipologiaauditorias.create', function (BreadcrumbTrail $trail) {
    $trail->parent('tipologiaauditorias');
    $trail->push('Auditoría', route('tipologiaauditorias.create'));
});

Breadcrumbs::for('tipologiaaccion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('tipologiaauditorias');
    $trail->push('Acciones', route('tipologiaaccion.index'));
});

Breadcrumbs::for('tipologiaacciones.create', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('agregaracciones.index',$auditoria);
    $trail->push('Acciones', route('tipologiaacciones.create',$auditoria));
});



//Turno Archivo Transferencia
Breadcrumbs::for('inicioarchivotransferencia.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Archivo Transferencia', route('inicioarchivotransferencia.index'));
});

Breadcrumbs::for('turnoarchivotransferencia.create', function (BreadcrumbTrail $trail) {
    $trail->parent('inicioarchivotransferencia.index');
    $trail->push('Envío Archivo Transferencia', route('inicioarchivotransferencia.create'));
});


// Breadcrumbs::for('turnoarchivotransferencia.create', function (BreadcrumbTrail $trail) {
//     $trail->parent('auditoriaseguimiento.index');
//     $trail->push('Envío de archivo', route('turnoarchivo.index'));
// });

Breadcrumbs::for('turnoarchivotransferencia.index', function (BreadcrumbTrail $trail) {
    $trail->parent('inicioarchivotransferencia.index');
    $trail->push('Consulta Archivo Transferencia', route('turnoarchivotransferencia.index'));
});

Breadcrumbs::for('turnoarchivotransferenciarevision.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnoarchivotransferencia.index',$auditoria);
    $trail->push('Revisión archivo transferencia ', route('turnoarchivotransferencia.index'));
});

Breadcrumbs::for('turnoarchivotransferenciavalidacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnoarchivotransferencia.index',$auditoria);
    $trail->push('Validación del Archivo Transferencia ', route('turnoarchivotransferencia.index'));
});

Breadcrumbs::for('turnoarchivotransferenciaautorizacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnoarchivotransferencia.index',$auditoria);
    $trail->push('Autorización del Turno Archivo Transferencia ', route('turnoarchivotransferencia.index'));
});


//Reportes de Registro de Auditorias
Breadcrumbs::for('reportesregistrosauditorias.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Reportes de Registro de Auditorias', route('reportesregistrosauditorias.index'));
});

Breadcrumbs::for('seguimientoauditorias.create', function (BreadcrumbTrail $trail) {
    $trail->parent('seguimientoauditorias');
    $trail->push('Auditoría', route('seguimientoauditoria.create'));
});
Breadcrumbs::for('seguimientoauditorias.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('seguimientoauditorias');
    $trail->push('Auditoria', route('seguimientoauditoria.edit',$auditoria));
});

Breadcrumbs::for('seguimientoauditoriaacciones.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('seguimientoauditorias.edit',$auditoria);
    $trail->push('Acciones', route('seguimientoauditoriaacciones.index'));
});
Breadcrumbs::for('asignacion.accion', function (BreadcrumbTrail $trail,$auditoria,$accion) {
    $trail->parent('seguimientoauditoriaacciones.index',$auditoria);
    $trail->push('Acción', route('asignacion.accion',$accion));
});

Breadcrumbs::for('seguimientoauditoriaacciones.create', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('seguimientoauditoriaacciones.index',$auditoria);
    $trail->push('Acción', route('seguimientoauditoriaacciones.create'));
});

Breadcrumbs::for('seguimientoauditoriaacciones.consulta', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('seguimientoauditorias');
    $trail->push('Acciones', route('seguimientoauditoria.accionesconsulta',$auditoria));
});

Breadcrumbs::for('seguimientoauditoriaacciones.show', function (BreadcrumbTrail $trail,$auditoria,$accion) {
    $trail->parent('seguimientoauditoriaacciones.consulta',$auditoria);
    $trail->push('Acción', route('seguimientoauditoriaacciones.show',$accion));
});

//audioriaseguimientoacciones
Breadcrumbs::for('auditoriaseguimientoacciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('auditoriaseguimiento.index');
    $trail->push('Acciones', route('auditoriaseguimientoacciones.index'));
});

Breadcrumbs::for('auditoriaconsultaacciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('auditoriaseguimientoacciones.index');
    $trail->push('Acción', route('auditoriaconsultaacciones.index'));
});


Breadcrumbs::for('seguimientoauditoriarevision01.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('seguimientoauditorias');
    $trail->push('Revisar', route('seguimientoauditoriarevision01.edit',$auditoria));
});

Breadcrumbs::for('seguimientoauditoriaaccionrevision01.edit', function (BreadcrumbTrail $trail,$auditoria,$accion) {
    $trail->parent('seguimientoauditoriarevision01.edit',$auditoria);
    $trail->push('Revisar acción', route('seguimientoauditoriaaccionrevision01.edit',$accion));
});

Breadcrumbs::for('seguimientoauditoriaaccionrevision01.show', function (BreadcrumbTrail $trail,$auditoria,$accion) {
    $trail->parent('seguimientoauditoriarevision01.edit',$auditoria);
    $trail->push('Acción', route('seguimientoauditoriaaccionrevision01.show',$accion));
});

Breadcrumbs::for('seguimientoauditoriarevision.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('seguimientoauditorias');
    $trail->push('Revisar', route('seguimientoauditoriarevision.edit',$auditoria));
});

Breadcrumbs::for('seguimientoauditoriaaccionrevision.edit', function (BreadcrumbTrail $trail,$auditoria,$accion) {
    $trail->parent('seguimientoauditoriarevision.edit',$auditoria);
    $trail->push('Revisar acción', route('seguimientoauditoriaaccionrevision.edit',$accion));
});

Breadcrumbs::for('seguimientoauditoriaaccionrevision.show', function (BreadcrumbTrail $trail,$auditoria,$accion) {
    $trail->parent('seguimientoauditoriarevision.edit',$auditoria);
    $trail->push('Acción', route('seguimientoauditoriaaccionrevision.show',$accion));
});


Breadcrumbs::for('seguimientoauditoriavalidacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('seguimientoauditorias');
    $trail->push('Validar', route('seguimientoauditoriavalidacion.edit',$auditoria));
});

Breadcrumbs::for('seguimientoauditoriaautorizacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('seguimientoauditorias');
    $trail->push('Validar', route('seguimientoauditoriaautorizacion.edit',$auditoria));
});

//Asignacion Direccion
Breadcrumbs::for('asignaciondireccion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Asignación de Auditorias a Direcciones', route('asignaciondireccion.index'));
});

Breadcrumbs::for('asignaciondireccion.accionesconsulta', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignaciondireccion.index');
    $trail->push('Acciones', route('asignaciondireccion.accionesconsulta',$auditoria));
});

Breadcrumbs::for('asignaciondireccion.accion', function (BreadcrumbTrail $trail,$accion,$movimiento,$auditoria) {
    $trail->parent('asignaciondireccion.accionesconsulta',$auditoria);
    $trail->push('Accion', route('asignacion.accion',['accion'=>$accion,'movimiento'=>$movimiento]));
});

Breadcrumbs::for('seguimientoauditoriaaccion.accionesconsulta', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignaciondepartamento.accionesconsulta');
    $trail->push('Accion', route('seguimientoauditoriaaccion.accionesconsulta',$auditoria));
});


Breadcrumbs::for('asignaciondireccion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignaciondireccion.index');
    $trail->push('Asignación', route('asignaciondireccion.edit',$auditoria));
});
Breadcrumbs::for('asignaciondireccion.reasignar', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignaciondireccion.index');
    $trail->push('Reasignación', route('asignaciondireccion.reasignar',$auditoria));
});

//Asignacion Departamentos
Breadcrumbs::for('asignaciondepartamento.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Asignación de Auditorias a Departamentos', route('asignaciondepartamento.index'));
});
Breadcrumbs::for('asignaciondepartamento.accionesconsulta', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignaciondepartamento.index');
    $trail->push('Acciones', route('asignaciondepartamento.accionesconsulta',$auditoria));
});

Breadcrumbs::for('asignacion.acciondepa', function (BreadcrumbTrail $trail,$accion,$movimiento,$auditoria) {
    $trail->parent('asignaciondepartamento.accionesconsulta',$auditoria);
    $trail->push('Accion', route('asignacion.accion',['accion'=>$accion,'movimiento'=>$movimiento]));
});

Breadcrumbs::for('asignaciondepartamento.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignaciondepartamento.index');
    $trail->push('Asignación', route('asignaciondepartamento.edit',$auditoria));
});
Breadcrumbs::for('asignaciondepartamento.reasignar', function (BreadcrumbTrail $trail,$auditoria,$accion) {
    $trail->parent('asignaciondepartamento.edit',$auditoria);
    $trail->push('Reasignación', route('asignaciondepartamento.reasignar',$accion));
});

//Asignacion Staff Juridico
Breadcrumbs::for('asignacionstaff.accionesconsulta', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignaciondepartamento.index');
    $trail->push('Acciones', route('asignacionstaff.accionesconsulta',$auditoria));
});

Breadcrumbs::for('asignacionstaff.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignaciondepartamento.index');
    $trail->push('Asignación', route('asignacionstaff.edit',$auditoria));
});
Breadcrumbs::for('asignacionstaff.reasignar', function (BreadcrumbTrail $trail, $auditoria, $accionstaff) {
    $trail->parent('asignacionstaff.edit', $auditoria);
    $trail->push($accionstaff, route('asignacionstaff.reasignar', $auditoria));
});

//Asignacion Lider y Analista
Breadcrumbs::for('asignacionlideranalista.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Asignación de Auditorias a Lideres de proyecto y Analistas', route('asignacionlideranalista.index'));
});

Breadcrumbs::for('asignacionlideranalista.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignacionlideranalista.index');
    $trail->push('Asignación del Lider de proyecto y Analista', route('asignacionlideranalista.edit',$auditoria));
});

Breadcrumbs::for('asignacionlideranalista.accionesconsulta', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignacionlideranalista.index');
    $trail->push('Acciones', route('asignacionlideranalista.accionesconsulta',$auditoria));
});

Breadcrumbs::for('asignacion.accionlider', function (BreadcrumbTrail $trail,$accion,$movimiento,$auditoria) {
    $trail->parent('asignacionlideranalista.accionesconsulta',$auditoria);
    $trail->push('Acción', route('asignacion.accion',['accion'=>$accion,'movimiento'=>$movimiento]));
});

Breadcrumbs::for('asignacionlideranalista.consulta', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignacionlideranalista.index');
    $trail->push('Consulta de Asignaciones de Lideres y Analistas', route('asignacionlideranalista.consulta',$auditoria));
});

Breadcrumbs::for('asignacionlideranalista.reasignarlider', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignacionlideranalista.index');
    $trail->push('Reasignación del Lider de proyecto', route('asignacionlideranalista.reasignarlider',$auditoria));
});

Breadcrumbs::for('asignacionlideranalista.reasignaranalista', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignacionlideranalista.index');
    $trail->push('Reasignación del Analista', route('asignacionlideranalista.reasignaranalista',$auditoria));
});



/*Asignación del departamento encargado */
Breadcrumbs::for('asignaciondepartamentoencargado.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignaciondepartamento.index');
    $trail->push('Asignación del Departamento Encargado de la Auditoría', route('asignaciondepartamentoencargado.edit',$auditoria));
});

Breadcrumbs::for('auditoriaseguimiento.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Auditorias', route('auditoriaseguimiento.index'));
});


Breadcrumbs::for('auditoriaseguimiento.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.index');
    $trail->push('Auditoría', route('auditoriaseguimiento.edit',$auditoria));
});

// Agregar acciones

Breadcrumbs::for('agregaracciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('auditoriaseguimiento.index');
    $trail->push('Acciones', route('agregaracciones.index'));
});
Breadcrumbs::for('agregaracciones.create', function (BreadcrumbTrail $trail) {
    $trail->parent('auditoriaseguimiento.index');
    $trail->push('Accion', route('agregaracciones.index'));
});

Breadcrumbs::for('agregaracciones.accion', function (BreadcrumbTrail $trail,$accion) {
    $trail->parent('agregaracciones.index');
    $trail->push('Accion', route('agregaracciones.accion',$accion));
});

Breadcrumbs::for('agregaraccionesrevision01.edit', function (BreadcrumbTrail $trail,$accion) {
    $trail->parent('agregaracciones.index');
    $trail->push('Revisar', route('agregaraccionesrevision01.edit',$accion));
});

Breadcrumbs::for('agregaraccionesrevision.edit', function (BreadcrumbTrail $trail,$accion) {
    $trail->parent('agregaracciones.index');
    $trail->push('Revisar', route('agregaraccionesrevision.edit',$accion));
});

Breadcrumbs::for('agregaraccionesvalidacion.edit', function (BreadcrumbTrail $trail,$accion) {
    $trail->parent('agregaracciones.index');
    $trail->push('Validar', route('agregaraccionesvalidacion.edit',$accion));
});

Breadcrumbs::for('agregaraccionesautorizacion.edit', function (BreadcrumbTrail $trail,$accion) {
    $trail->parent('agregaracciones.index');
    $trail->push('Autorizar', route('agregaraccionesautorizacion.edit',$accion));
});

/*Radicacion*/
Breadcrumbs::for('radicacion.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Radicación', route('radicacion.index'));
});

Breadcrumbs::for('comparecenciaacuse.show', function (BreadcrumbTrail $trail,$comparecencia,$auditoria) {
    $trail->parent('radicacion.index',$auditoria);
    $trail->push('Acuses', route('comparecenciaacuse.show',$comparecencia));
});

Breadcrumbs::for('radicacion.create', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('radicacion.index',$auditoria);
    $trail->push('Agregar', route('radicacion.create'));
});

Breadcrumbs::for('radicacion.edit', function (BreadcrumbTrail $trail,$radicacion,$auditoria) {
    $trail->parent('radicacion.index',$auditoria);
    $trail->push('Editar', route('radicacion.edit',$radicacion));
});

Breadcrumbs::for('comparecenciaagenda.edit', function (BreadcrumbTrail $trail,$comparecencia,$radicacion,$auditoria) {
    $trail->parent('radicacion.edit',$radicacion,$auditoria);
    $trail->push('Agendar comparecencia', route('comparecenciaagenda.edit',$comparecencia));
});

Breadcrumbs::for('comparecencia.show', function (BreadcrumbTrail $trail,$comparecencia,$auditoria) {
    $trail->parent('radicacion.index',$auditoria);
    $trail->push('Datos de comparecencia', route('comparecencia.show',$comparecencia));
});

Breadcrumbs::for('radicacionrevision.edit', function (BreadcrumbTrail $trail,$radicacion,$auditoria) {
    $trail->parent('radicacion.index',$auditoria);
    $trail->push('Revisar', route('radicacionrevision.edit',$radicacion));
});

Breadcrumbs::for('radicacionvalidacion.edit', function (BreadcrumbTrail $trail,$radicacion,$auditoria) {
    $trail->parent('radicacion.index',$auditoria);
    $trail->push('Validar', route('radicacionvalidacion.edit',$radicacion));
});

Breadcrumbs::for('radicacionautorizacion.edit', function (BreadcrumbTrail $trail,$radicacion,$auditoria) {
    $trail->parent('radicacion.index',$auditoria);
    $trail->push('Autorizar', route('radicacionautorizacion.edit',$radicacion));
});

Breadcrumbs::for('comparecenciaacuse.edit', function (BreadcrumbTrail $trail,$comparecencia,$auditoria) {
    $trail->parent('radicacion.index',$auditoria);
    $trail->push('Acuses', route('comparecenciaacuse.edit',$comparecencia));
});
Breadcrumbs::for('comparecenciaacusecp.edit', function (BreadcrumbTrail $trail,$comparecencia,$auditoria) {
    $trail->parent('radicacion.index',$auditoria);
    $trail->push('Acuses', route('comparecenciaacusecp.edit',$comparecencia));
});

//acuerdo conclusion
Breadcrumbs::for('acuerdoconclusion.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.index',$auditoria);
    $trail->push('Acuerdo de conclusión ', route('acuerdoconclusion.index'));
});
// Breadcrumbs::for('acuerdoconclusioncp.index', function (BreadcrumbTrail $trail,$auditoria) {
//     $trail->parent('auditoriaseguimiento.index',$auditoria);
//     $trail->push('Acuerdo de conclusión ', route('acuerdoconclusioncp.index'));
// });

Breadcrumbs::for('acuerdoconclusion.create', function (BreadcrumbTrail $trail) {
    $trail->parent('auditoriaseguimiento.index');
    $trail->push('Acuerdo de conclusión', route('auditoriaseguimiento.create'));
});

Breadcrumbs::for('acuerdoconclusioncp.create', function (BreadcrumbTrail $trail) {
    $trail->parent('auditoriaseguimiento.index');
    $trail->push('Acuerdo de conclusión', route('acuerdoconclusioncp.create'));
});

Breadcrumbs::for('acuerdoconclusionrevision.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('acuerdoconclusion.index',$auditoria);
    $trail->push('Revisión del Acuerdo de Conclusion ', route('acuerdoconclusionrevision.edit',$auditoria));
});

Breadcrumbs::for('acuerdoconclusionvalidacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('acuerdoconclusion.index',$auditoria);
    $trail->push('Validacion del Acuerdo de Conclusion ', route('acuerdoconclusionvalidacion.edit',$auditoria));
});

Breadcrumbs::for('acuerdoconclusionautorizacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('acuerdoconclusion.index',$auditoria);
    $trail->push('Autorizacion del Acuerdo de Conclusión ', route('acuerdoconclusion.index'));
});


Breadcrumbs::for('acuerdoconclusionacuse.edit', function (BreadcrumbTrail $trail,$acuerdoconclusion,$auditoria) {
    $trail->parent('acuerdoconclusion.index',$auditoria);
    $trail->push('Acuses', route('acuerdoconclusion.edit',$acuerdoconclusion));
});
Breadcrumbs::for('acuerdoconclusionacusecp.edit', function (BreadcrumbTrail $trail,$acuerdoconclusion,$auditoria) {
    $trail->parent('acuerdoconclusion.index',$auditoria);
    $trail->push('Acuses', route('acuerdoconclusionacusecp.edit',$acuerdoconclusion));
});
Breadcrumbs::for('acuerdoconclusionacusecp.show', function (BreadcrumbTrail $trail,$acuerdoconclusion,$auditoria) {
    $trail->parent('acuerdoconclusion.index',$auditoria);
    $trail->push('Acuses', route('acuerdoconclusionacusecp.show',$acuerdoconclusion));
});

//recomendaciones contestaciones

Breadcrumbs::for('recomendacionescontestaciones.edit', function (BreadcrumbTrail $trail,$contestacion,$auditoria) {
    $trail->parent('recomendacionescontestaciones.index',$auditoria);
    $trail->push('Agregar', route('recomendacionescontestaciones.edit',$contestacion));
});

/*Comparecencia*/
Breadcrumbs::for('comparecenciaacta.index', function (BreadcrumbTrail $trail,$comparecencia,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Comparecencia', route('comparecenciaacta.index',$comparecencia));
});

Breadcrumbs::for('comparecenciaacta.edit', function (BreadcrumbTrail $trail,$comparecencia,$auditoria) {
    $trail->parent('comparecenciaacta.index',$comparecencia,$auditoria);
    $trail->push('Acta', route('comparecenciaacta.edit',$comparecencia));
});

Breadcrumbs::for('comparecenciarevision.edit', function (BreadcrumbTrail $trail,$comparecencia,$auditoria) {
    $trail->parent('comparecenciaacta.index',$comparecencia,$auditoria);
    $trail->push('Revisión', route('comparecenciarevision.edit',$comparecencia));
});


Breadcrumbs::for('comparecenciavalidacion.edit', function (BreadcrumbTrail $trail,$comparecencia,$auditoria) {
    $trail->parent('comparecenciaacta.index',$comparecencia,$auditoria);
    $trail->push('Validar', route('comparecenciavalidacion.edit',$comparecencia));
});

Breadcrumbs::for('comparecenciaacta2.show', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Comparecencia', route('comparecenciaacta.create'));
});

/*PRAS*/
Breadcrumbs::for('prasacciones.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('PRAS', route('prasacciones.index'));
});

Breadcrumbs::for('prasturno.create', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('prasacciones.index',$auditoria);
    $trail->push('Turnar PRAS', route('prasturno.create'));
});

Breadcrumbs::for('prasturno.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('prasacciones.index',$auditoria);
    $trail->push('Turnar PRAS', route('prasturno.index'));
});

Breadcrumbs::for('prasturnorevision.edit', function (BreadcrumbTrail $trail,$auditoria,$pras) {
    $trail->parent('prasturno.index',$auditoria);
    $trail->push('Revisar', route('prasturnorevision.edit',$pras));
});

Breadcrumbs::for('prasturnovalidacion.edit', function (BreadcrumbTrail $trail,$auditoria,$pras) {
    $trail->parent('prasturno.index',$auditoria);
    $trail->push('Validar', route('prasturnovalidacion.edit',$pras));
});
Breadcrumbs::for('prasturnoautorizacion.edit', function (BreadcrumbTrail $trail,$auditoria,$pras) {
    $trail->parent('prasturno.index',$auditoria);
    $trail->push('Autorizar-Rechazar', route('prasturnoautorizacion.edit',$pras));
});
Breadcrumbs::for('prasturnoacuses.edit', function (BreadcrumbTrail $trail,$auditoria,$pras) {
    $trail->parent('prasturno.index',$auditoria);
    $trail->push('Acuses', route('prasturnoacuses.edit',$pras));
});
Breadcrumbs::for('prasturnoacuses.show', function (BreadcrumbTrail $trail,$auditoria,$pras) {
    $trail->parent('prasturno.index',$auditoria);
    $trail->push('Acuses', route('prasturnoacuses.show',$auditoria,$pras));
});
Breadcrumbs::for('prasseguimiento.edit', function (BreadcrumbTrail $trail,$auditoria,$pras) {
    $trail->parent('prasturno.index',$auditoria);
    $trail->push('Seguimiento', route('prasseguimiento.edit',$auditoria,$pras));
});
Breadcrumbs::for('prasseguimiento.show', function (BreadcrumbTrail $trail,$auditoria,$pras) {
    $trail->parent('prasturno.index',$auditoria);
    $trail->push('Seguimiento', route('prasseguimiento.show',$auditoria,$pras));
});
Breadcrumbs::for('prasmedida.edit', function (BreadcrumbTrail $trail,$auditoria,$pras) {
    $trail->parent('prasturno.index',$auditoria);
    $trail->push('Medida de apremio', route('prasmedida.edit',$auditoria,$pras));
});
Breadcrumbs::for('prasmedida.show', function (BreadcrumbTrail $trail,$auditoria,$pras) {
    $trail->parent('prasturno.index',$auditoria);
    $trail->push('Medida de apremio', route('prasmedida.show',$auditoria,$pras));
});

/*Recomendaciones*/
Breadcrumbs::for('recomendacionesacciones.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Recomendaciones', route('recomendacionesacciones.index'));
});

Breadcrumbs::for('recomendacionesatencion.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('recomendacionesacciones.index',$auditoria);
    $trail->push('Atención de la recomendación', route('recomendacionesatencion.index'));
});

Breadcrumbs::for('recomendacionescontestaciones.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('recomendacionesatencion.index',$auditoria);
    $trail->push('Oficios de contestación', route('recomendacionescontestaciones.index'));
});

Breadcrumbs::for('recomendacionescontestaciones.create', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('recomendacionescontestaciones.index',$auditoria);
    $trail->push('Agregar', route('recomendacionescontestaciones.create'));
});

Breadcrumbs::for('recomendacionesdocumentos.edit', function (BreadcrumbTrail $trail,$recomedacion,$auditoria) {
    $trail->parent('recomendacionesatencion.index',$auditoria);
    $trail->push('Listado de documentos', route('recomendacionesdocumentos.edit',$recomedacion));
});

Breadcrumbs::for('recomendacionesanalisis.edit', function (BreadcrumbTrail $trail,$recomedacion,$auditoria) {
    $trail->parent('recomendacionesatencion.index',$auditoria);
    $trail->push('Análisis de la atención', route('recomendacionesanalisis.edit',$recomedacion));
});

Breadcrumbs::for('recomendacionesanexos.index', function (BreadcrumbTrail $trail,$recomedacion,$auditoria) {
    $trail->parent('recomendacionesanalisis.edit',$recomedacion,$auditoria);
    $trail->push('Anexos', route('recomendacionesanexos.index'));
});

Breadcrumbs::for('recomendacionesanexos.create', function (BreadcrumbTrail $trail,$recomedacion,$auditoria) {
    $trail->parent('recomendacionesanexos.index',$recomedacion,$auditoria);
    $trail->push('Agregar', route('recomendacionesanexos.create'));
});

Breadcrumbs::for('recomendacionesanalisis.show', function (BreadcrumbTrail $trail,$recomedacion,$auditoria) {
    $trail->parent('recomendacionesatencion.index',$auditoria);
    $trail->push('Análisis de la atención', route('recomendacionesanalisis.show',$recomedacion));
});

Breadcrumbs::for('recomendacionesanexos.show', function (BreadcrumbTrail $trail,$recomedacion,$auditoria) {
    $trail->parent('recomendacionesanalisis.show',$recomedacion,$auditoria);
    $trail->push('Anexos', route('recomendacionesanexos.show',$recomedacion));
});

Breadcrumbs::for('recomendacionesrevision01.edit', function (BreadcrumbTrail $trail,$recomedacion,$auditoria) {
    $trail->parent('recomendacionesatencion.index',$auditoria);
    $trail->push('Revisar', route('recomendacionesrevision01.edit',$recomedacion));
});

Breadcrumbs::for('recomendacionesrevision.edit', function (BreadcrumbTrail $trail,$recomedacion,$auditoria) {
    $trail->parent('recomendacionesatencion.index',$auditoria);
    $trail->push('Revisar', route('recomendacionesrevision.edit',$recomedacion));
});

Breadcrumbs::for('recomendacionesvalidacion.edit', function (BreadcrumbTrail $trail,$recomedacion,$auditoria) {
    $trail->parent('recomendacionesatencion.index',$auditoria);
    $trail->push('Validar', route('recomendacionesvalidacion.edit',$recomedacion));
});

Breadcrumbs::for('recomendacionesautorizacion.edit', function (BreadcrumbTrail $trail,$recomedacion,$auditoria) {
    $trail->parent('recomendacionesatencion.index',$auditoria);
    $trail->push('Autorizar-Rechazar', route('recomendacionesautorizacion.edit',$recomedacion));
});

/*solicitud de aclaración*/
Breadcrumbs::for('solicitudesaclaracionacciones.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Solicitudes de aclaración', route('solicitudesaclaracionacciones.index'));
});

Breadcrumbs::for('solicitudesaclaracionatencion.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('solicitudesaclaracionacciones.index',$auditoria);
    $trail->push('Atención de las solicitudes de aclaración', route('solicitudesaclaracionatencion.index'));
});

Breadcrumbs::for('solicitudesaclaracioncontestacion.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('solicitudesaclaracionatencion.index',$auditoria);
    $trail->push('Oficios de contestación', route('solicitudesaclaracioncontestacion.index'));
});

Breadcrumbs::for('solicitudesaclaracioncontestacion.create', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('solicitudesaclaracioncontestacion.index',$auditoria);
    $trail->push('Agregar', route('solicitudesaclaracioncontestacion.create'));
});

Breadcrumbs::for('solicitudesaclaracioncontestacion.edit', function (BreadcrumbTrail $trail,$contestacion,$auditoria) {
    $trail->parent('solicitudesaclaracioncontestacion.index',$auditoria);
    $trail->push('Agregar', route('solicitudesaclaracioncontestacion.edit',$contestacion));
});

Breadcrumbs::for('solicitudesaclaraciondocumentos.edit', function (BreadcrumbTrail $trail,$solicitud,$auditoria) {
    $trail->parent('solicitudesaclaracionatencion.index',$auditoria);
    $trail->push('Listado de documentos', route('solicitudesaclaraciondocumentos.edit',$solicitud));
});

Breadcrumbs::for('solicitudesaclaracionanalisis.edit', function (BreadcrumbTrail $trail,$solicitud,$auditoria) {
    $trail->parent('solicitudesaclaracionatencion.index',$auditoria);
    $trail->push('Análisis de la atención', route('solicitudesaclaracionanalisis.edit',$solicitud));
});

Breadcrumbs::for('solicitudesaclaracionanalisis.show', function (BreadcrumbTrail $trail,$solicitud,$auditoria) {
    $trail->parent('solicitudesaclaracionatencion.index',$auditoria);
    $trail->push('Análisis de la atención', route('solicitudesaclaracionanalisis.show',$solicitud));
});

Breadcrumbs::for('solicitudesaclaracionanexos.index', function (BreadcrumbTrail $trail,$solicitud,$auditoria) {
    $trail->parent('solicitudesaclaracionanalisis.edit',$solicitud,$auditoria);
    $trail->push('Anexos', route('solicitudesaclaracionanexos.index'));
});

Breadcrumbs::for('solicitudesaclaracionanexos.create', function (BreadcrumbTrail $trail,$solicitud,$auditoria) {
    $trail->parent('solicitudesaclaracionanexos.index',$solicitud,$auditoria);
    $trail->push('Agregar', route('solicitudesaclaracionanexos.create'));
});

Breadcrumbs::for('solicitudesaclaracionanexos.show', function (BreadcrumbTrail $trail,$solicitud,$auditoria) {
    $trail->parent('solicitudesaclaracionanalisis.show',$solicitud,$auditoria);
    $trail->push('Anexos', route('solicitudesaclaracionanexos.show',$solicitud));
});

Breadcrumbs::for('solicitudesaclaracionrevision01.edit', function (BreadcrumbTrail $trail,$solicitud,$auditoria) {
    $trail->parent('solicitudesaclaracionatencion.index',$auditoria);
    $trail->push('Revisar', route('solicitudesaclaracionrevision01.edit',$solicitud));
});

Breadcrumbs::for('solicitudesaclaracionrevision.edit', function (BreadcrumbTrail $trail,$solicitud,$auditoria) {
    $trail->parent('solicitudesaclaracionatencion.index',$auditoria);
    $trail->push('Revisar', route('solicitudesaclaracionrevision.edit',$solicitud));
});

Breadcrumbs::for('solicitudesaclaracionvalidacion.edit', function (BreadcrumbTrail $trail,$solicitud,$auditoria) {
    $trail->parent('solicitudesaclaracionatencion.index',$auditoria);
    $trail->push('Validar', route('solicitudesaclaracionvalidacion.edit',$solicitud));
});

Breadcrumbs::for('solicitudesaclaracionautorizacion.edit', function (BreadcrumbTrail $trail,$solicitud,$auditoria) {
    $trail->parent('solicitudesaclaracionatencion.index',$auditoria);
    $trail->push('Autorizar-Rechazar', route('solicitudesaclaracionautorizacion.edit',$solicitud));
});

/*Pliegos de observación*/
Breadcrumbs::for('pliegosobservacionacciones.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Pliegos de observación', route('pliegosobservacionacciones.index'));
});

Breadcrumbs::for('pliegosobservacionatencion.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('pliegosobservacionacciones.index',$auditoria);
    $trail->push('Atención del pliego de observación', route('pliegosobservacionatencion.index'));
});

Breadcrumbs::for('pliegosobservacionatencioncontestacion.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('pliegosobservacionatencion.index',$auditoria);
    $trail->push('Oficios de contestación', route('pliegosobservacionatencioncontestacion.index'));
});

Breadcrumbs::for('pliegosobservacionatencioncontestacion.create', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('pliegosobservacionatencioncontestacion.index',$auditoria);
    $trail->push('Agregar', route('pliegosobservacionatencioncontestacion.create'));
});

Breadcrumbs::for('pliegosobservacionatencioncontestacion.edit', function (BreadcrumbTrail $trail,$contestacion,$auditoria) {
    $trail->parent('pliegosobservacionatencioncontestacion.index',$auditoria);
    $trail->push('Editar', route('pliegosobservacionatencioncontestacion.edit',$contestacion));
});

Breadcrumbs::for('pliegosobservaciondocumentos.edit', function (BreadcrumbTrail $trail,$pliegos,$auditoria) {
    $trail->parent('pliegosobservacionatencion.index',$auditoria);
    $trail->push('Listado de documentos', route('pliegosobservaciondocumentos.edit',$pliegos));
});

Breadcrumbs::for('pliegosobservacionanalisis.edit', function (BreadcrumbTrail $trail,$pliegosobservacion,$auditoria) {
    $trail->parent('pliegosobservacionatencion.index',$auditoria);
    $trail->push('Análisis de la atención', route('pliegosobservacionanalisis.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionanexos.index', function (BreadcrumbTrail $trail,$pliegosobservacion,$auditoria) {
    $trail->parent('pliegosobservacionanalisis.edit',$pliegosobservacion,$auditoria);
    $trail->push('Anexos', route('pliegosobservacionanexos.index'));
});

Breadcrumbs::for('pliegosobservacionanexos.create', function (BreadcrumbTrail $trail,$pliegosobservacion,$auditoria) {
    $trail->parent('pliegosobservacionanexos.index',$pliegosobservacion,$auditoria);
    $trail->push('Agregar', route('pliegosobservacionanexos.create'));
});
Breadcrumbs::for('pliegosobservacionanalisis.show', function (BreadcrumbTrail $trail,$pliegosobservacion,$auditoria) {
    $trail->parent('pliegosobservacionatencion.index',$auditoria);
    $trail->push('Análisis de la atención', route('pliegosobservacionanalisis.show',$pliegosobservacion));
});
Breadcrumbs::for('pliegosobservacionanexos.show', function (BreadcrumbTrail $trail,$pliegosobservacion,$auditoria) {
    $trail->parent('pliegosobservacionanalisis.show',$pliegosobservacion,$auditoria);
    $trail->push('Anexos', route('pliegosobservacionanexos.show',$pliegosobservacion));
});
Breadcrumbs::for('pliegosobservacionrevision01.edit', function (BreadcrumbTrail $trail,$pliegosobservacion,$auditoria) {
    $trail->parent('pliegosobservacionatencion.index',$auditoria);
    $trail->push('Revisar', route('pliegosobservacionrevision01.edit',$pliegosobservacion));
});
Breadcrumbs::for('pliegosobservacionrevision.edit', function (BreadcrumbTrail $trail,$pliegosobservacion,$auditoria) {
    $trail->parent('pliegosobservacionatencion.index',$auditoria);
    $trail->push('Revisar', route('pliegosobservacionrevision.edit',$pliegosobservacion));
});
Breadcrumbs::for('pliegosobservacionvalidacion.edit', function (BreadcrumbTrail $trail,$pliegosobservacion,$auditoria) {
    $trail->parent('pliegosobservacionatencion.index',$auditoria);
    $trail->push('Validar', route('pliegosobservacionvalidacion.edit',$pliegosobservacion));
});
Breadcrumbs::for('pliegosobservacionautorizacion.edit', function (BreadcrumbTrail $trail,$pliegosobservacion,$auditoria) {
    $trail->parent('pliegosobservacionatencion.index',$auditoria);
    $trail->push('Autorizar-Rechazar', route('pliegosobservacionautorizacion.edit',$pliegosobservacion));
});
Breadcrumbs::for('cedulainicial.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Cédulas', route('cedulainicial.index'));
});
Breadcrumbs::for('cedulainicial.show', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('cedulainicial.index',$auditoria);
    $trail->push('Cédula General', route('cedulainicial.show',$auditoria));
});
Breadcrumbs::for('cedulageneralrecomendacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('cedulainicial.index',$auditoria);
    $trail->push('Cédula General Recomendaciones', route('cedulageneralrecomendacion.edit',$auditoria));
});
Breadcrumbs::for('cedulageneralpras.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('cedulainicial.index',$auditoria);
    $trail->push('Cédula General PRAS', route('cedulageneralpras.edit',$auditoria));
});
Breadcrumbs::for('cedulaanalitica.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('cedulainicial.index',$auditoria);
    $trail->push('Cédula Analítica', route('cedulaanalitica.edit',$auditoria));
});
Breadcrumbs::for('cedulaanaliticadesemp.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('cedulainicial.index',$auditoria);
    $trail->push('Cédula Analítica Desempeño', route('cedulaanaliticadesemp.edit',$auditoria));
});

Breadcrumbs::for('agregarcedulainicial.edit', function (BreadcrumbTrail $trail,$cedula,$auditoria) {
    $trail->parent('cedulainicial.index',$auditoria,$cedula);
    $trail->push('Acuses', route('agregarcedulainicial.edit',$auditoria,$cedula));
});


//PAC
Breadcrumbs::for('pac.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('PAC', route('pac.index'));
});

Breadcrumbs::for('reportesseg.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Reportes', route('reportesseg.index'));
});



// Informe Primer Etapa

Breadcrumbs::for('informeprimeraetapa.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.index',$auditoria);
    $trail->push('Informe', route('informeprimeraetapa.index'));
});

Breadcrumbs::for('informeprimeraetapa.create', function (BreadcrumbTrail $trail) {
    $trail->parent('auditoriaseguimiento.index');
    $trail->push('Informe Primera Etapa', route('informeprimeraetapa.create'));
});

Breadcrumbs::for('informeprimeraetapavalidacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('informeprimeraetapa.index',$auditoria);
    $trail->push('Validacion del Informe Primera Etapa', route('informeprimeraetapa.index'));
});
Breadcrumbs::for('informeprimeraetapaautorizacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnoui.index',$auditoria);
    $trail->push('Autorizacion del Informe Primera Etapa ', route('informeprimeraetapa.index'));
});




// Breadcrumbs::for('informeprimeraetapa.edit', function (BreadcrumbTrail $trail,) {
//     $trail->parent('auditoriaseguimiento');
//     $trail->push('Editar', route('informeprimeraetapa.edit',$informe));
// });

// turno UI
Breadcrumbs::for('turnoui.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.index',$auditoria);
    $trail->push('Turno a la Unidad de Investigación', route('turnoui.index'));
});

Breadcrumbs::for('turnoui.create', function (BreadcrumbTrail $trail) {
    $trail->parent('auditoriaseguimiento.index');
    $trail->push('Turno a la Unidad de Investigación', route('auditoriaseguimiento.create'));
});

Breadcrumbs::for('turnouirevision.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnoui.index',$auditoria);
    $trail->push('Revisar Turno a la Unidad de Investigación ', route('turnoui.index'));
});

Breadcrumbs::for('turnouivalidacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnoui.index',$auditoria);
    $trail->push('Validacion Turno a la Unidad de Investigación ', route('turnoui.index'));
});
Breadcrumbs::for('turnouiautorizacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnoui.index',$auditoria);
    $trail->push('Autorizacion Turno a la Unidad de Investigación ', route('turnoui.index'));
});


// turno OIC
Breadcrumbs::for('turnooic.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.index',$auditoria);
    $trail->push('Turno al Organo Intrerno de Control', route('turnooic.index'));
});

Breadcrumbs::for('turnooic.create', function (BreadcrumbTrail $trail) {
    $trail->parent('auditoriaseguimiento.index');
    $trail->push('Turno al Organo Interno de Control', route('auditoriaseguimiento.create'));
});

Breadcrumbs::for('turnooicrevision.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnooic.index',$auditoria);
    $trail->push('Revisión del Turno al Órgano  de Control', route('turnooic.index'));
});

Breadcrumbs::for('turnooicvalidacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnooic.index',$auditoria);
    $trail->push('Validacion del Turno al Órgano Interno de Control ', route('turnooic.index'));
});

Breadcrumbs::for('turnooicautorizacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnooic.index',$auditoria);
    $trail->push('Autorizacion del Turno al Órgano Interno de Control', route('turnooic.index'));
});

//turno acuse archivo
Breadcrumbs::for('turnoarchivo.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.index',$auditoria);
    $trail->push('Acuse envío archivo', route('turnoarchivo.index'));
});

Breadcrumbs::for('turnoarchivo.create', function (BreadcrumbTrail $trail) {
    $trail->parent('auditoriaseguimiento.index');
    $trail->push('Acuse envío de archivo', route('auditoriaseguimiento.create'));
});

Breadcrumbs::for('turnoarchivorevision01.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnoarchivo.index',$auditoria);
    $trail->push('Revisión acuse envío archivo ', route('turnoarchivo.index'));
});

Breadcrumbs::for('turnoarchivorevision.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnoarchivo.index',$auditoria);
    $trail->push('Revisión acuse envío archivo ', route('turnoarchivo.index'));
});

Breadcrumbs::for('turnoarchivovalidacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnoarchivo.index',$auditoria);
    $trail->push('Validación del Turno Archivo Envío ', route('turnoarchivo.index'));
});

Breadcrumbs::for('turnoarchivoautorizacion.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('turnoarchivo.index',$auditoria);
    $trail->push('Autorización del Turno Archivo Envío ', route('turnoarchivo.index'));
});

Breadcrumbs::for('seguimientoauditoriascp.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Auditorias', route('seguimientoauditoriacp.index'));
});

Breadcrumbs::for('seguimientoauditoriascp.create', function (BreadcrumbTrail $trail) {
    $trail->parent('seguimientoauditoriascp.index');
    $trail->push('Agregar', route('seguimientoauditoriacp.create'));
});

Breadcrumbs::for('seguimientoauditoriascp.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('seguimientoauditoriascp.index');
    $trail->push('Editar', route('seguimientoauditoriacp.edit',$auditoria));
});

/**Folios CRR*/
Breadcrumbs::for('folioscrr.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Folios', route('folioscrr.index',$auditoria));
});

Breadcrumbs::for('folioscrr.create', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('folioscrr.index',$auditoria);
    $trail->push('Folios', route('folioscrr.create',$auditoria));
});

Breadcrumbs::for('remitentes.show', function (BreadcrumbTrail $trail, $auditoria) {
    $trail->parent('folioscrr.index',$auditoria);
    $trail->push('Folios Remitentes', route('remitentes.show',$auditoria ));

});

Breadcrumbs::for('remitentes.create', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('remitentes.show',$auditoria);
    $trail->push('Folios Remitentes', route('remitentes.create', $auditoria));
});
Breadcrumbs::for('remitentes.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('remitentes.show',$auditoria);
    $trail->push('Folios Editar Remitentes', route('remitentes.edit', $auditoria));
});



