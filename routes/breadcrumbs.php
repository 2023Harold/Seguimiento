<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Inicio', route('home'));
});

Breadcrumbs::for('notificaciones', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Notificaciones', route('notificaciones.index'));
});

Breadcrumbs::for('seguimientoauditorias', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Auditorias', route('seguimientoauditoria.index'));
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

Breadcrumbs::for('seguimientoauditoriarevisionlp.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('seguimientoauditorias');
    $trail->push('Revisar', route('seguimientoauditoriarevisionlp.edit',$auditoria));
});

Breadcrumbs::for('seguimientoauditoriaaccionrevision01.edit', function (BreadcrumbTrail $trail,$auditoria,$accion) {
    $trail->parent('seguimientoauditoriarevisionlp.edit',$auditoria);
    $trail->push('Revisar acción', route('seguimientoauditoriaaccionrevision01.edit',$accion));
});

Breadcrumbs::for('seguimientoauditoriaaccionrevision01.show', function (BreadcrumbTrail $trail,$auditoria,$accion) {
    $trail->parent('seguimientoauditoriarevisionlp.edit',$auditoria);
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
Breadcrumbs::for('asignaciondepartamento.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('asignaciondepartamento.index');
    $trail->push('Asignación', route('asignaciondepartamento.edit',$auditoria));
});
Breadcrumbs::for('asignaciondepartamento.reasignar', function (BreadcrumbTrail $trail,$auditoria,$accion) {
    $trail->parent('asignaciondepartamento.edit',$auditoria);
    $trail->push('Reasignación', route('asignaciondepartamento.reasignar',$accion));
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
    $trail->parent('asignacionlideranalista.index');
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

Breadcrumbs::for('auditoriaseguimientoacciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('auditoriaseguimiento.index');
    $trail->push('Acciones', route('auditoriaseguimientoacciones.index'));
});


/*Radicacion*/
Breadcrumbs::for('radicacion.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Radicación', route('radicacion.index'));
});

Breadcrumbs::for('comparecenciaacuse.show', function (BreadcrumbTrail $trail,$comparecencia) {
    $trail->parent('radicacion.index');
    $trail->push('Acuses', route('comparecenciaacuse.show',$comparecencia));
});


/*Comparecencia*/
Breadcrumbs::for('comparecenciaacta.show', function (BreadcrumbTrail $trail,$comparecencia,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Comparecencia', route('comparecenciaacta.show',$comparecencia));
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

/*Recomendaciones*/
Breadcrumbs::for('recomendacionesacciones.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Recomendaciones', route('recomendacionesacciones.index'));
});

/*solicitud de aclaración*/
Breadcrumbs::for('solicitudesaclaracionacciones.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Solicitudes de aclaración', route('solicitudesaclaracionacciones.index'));
});

/*Pliegos de observación*/
Breadcrumbs::for('pliegosobservacionacciones.index', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('auditoriaseguimiento.edit',$auditoria);
    $trail->push('Pliegos de observación', route('pliegosobservacionacciones.index'));
});











Breadcrumbs::for('radicacion.create', function (BreadcrumbTrail $trail) {
    $trail->parent('radicacion.index');
    $trail->push('Agregar', route('radicacion.create'));
});
Breadcrumbs::for('radicacion.edit', function (BreadcrumbTrail $trail,$radicacion) {
    $trail->parent('radicacion.index');
    $trail->push('Editar', route('radicacion.edit',$radicacion));
});
Breadcrumbs::for('comparecenciaagenda.edit', function (BreadcrumbTrail $trail,$comparecencia,$radicacion) {
    $trail->parent('radicacion.edit',$radicacion);
    $trail->push('Agendar comparecencia', route('comparecenciaagenda.edit',$comparecencia));
});
Breadcrumbs::for('radicacionvalidacion.edit', function (BreadcrumbTrail $trail,$radicacion) {
    $trail->parent('radicacion.index');
    $trail->push('Validar', route('radicacionvalidacion.edit',$radicacion));
});
Breadcrumbs::for('radicacionautorizacion.edit', function (BreadcrumbTrail $trail,$radicacion) {
    $trail->parent('radicacion.index');
    $trail->push('Autorizar', route('radicacionautorizacion.edit',$radicacion));
});
Breadcrumbs::for('comparecenciaacuse.edit', function (BreadcrumbTrail $trail,$comparecencia) {
    $trail->parent('radicacion.index');
    $trail->push('Acuses', route('comparecenciaacuse.edit',$comparecencia));
});
Breadcrumbs::for('comparecencia.show', function (BreadcrumbTrail $trail,$comparecencia) {
    $trail->parent('radicacion.index');
    $trail->push('Datos de comparecencia', route('comparecencia.show',$comparecencia));
});



/*Comparecencia*/
Breadcrumbs::for('comparecencia.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Comparecencia', route('comparecencia.index'));
});
Breadcrumbs::for('comparecenciaacta.edit', function (BreadcrumbTrail $trail,$comparecencia) {
    $trail->parent('comparecencia.index');
    $trail->push('Acta', route('comparecenciaacta.edit',$comparecencia));
});


/*pras */
Breadcrumbs::for('pras.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('PRAS', route('pras.index'));
});

Breadcrumbs::for('pras.create', function (BreadcrumbTrail $trail) {
    $trail->parent('pras.index');
    $trail->push('Turnar PRAS a OIC o equivalente', route('pras.index'));
});

/*prasaccion */


/*prasturno */
Breadcrumbs::for('prasturno.create', function (BreadcrumbTrail $trail) {
    $trail->parent('prasacciones.index');
    $trail->push('Turnar PRAS', route('prasturno.create'));
});

Breadcrumbs::for('prasturno.index', function (BreadcrumbTrail $trail) {
    $trail->parent('prasacciones.index');
    $trail->push('Turnar PRAS', route('prasturno.index'));
});
Breadcrumbs::for('prasturnorevision.edit', function (BreadcrumbTrail $trail,$pras) {
    $trail->parent('prasturno.index');
    $trail->push('Revisar', route('prasturnorevision.edit',$pras));
});
Breadcrumbs::for('prasturnovalidacion.edit', function (BreadcrumbTrail $trail,$pras) {
    $trail->parent('prasturno.index');
    $trail->push('Validar', route('prasturnovalidacion.edit',$pras));
});
Breadcrumbs::for('prasturnoautorizacion.edit', function (BreadcrumbTrail $trail,$pras) {
    $trail->parent('prasturno.index');
    $trail->push('Autorizar-Rechazar', route('prasturnoautorizacion.edit',$pras));
});
Breadcrumbs::for('prasturnoacuses.edit', function (BreadcrumbTrail $trail,$pras) {
    $trail->parent('prasturno.index');
    $trail->push('Acuses', route('prasturnoacuses.edit',$pras));
});
Breadcrumbs::for('prasturnoacuses.show', function (BreadcrumbTrail $trail,$pras) {
    $trail->parent('prasturno.index');
    $trail->push('Acuses', route('prasturnoacuses.show',$pras));
});

/*recomendaciones */
Breadcrumbs::for('recomendaciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Recomendaciones', route('recomendaciones.index'));

});
/*recomendacionesaccion */


Breadcrumbs::for('recomendacionesatencion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('recomendacionesacciones.index');
    $trail->push('Atención de la recomendación', route('recomendacionesatencion.index'));
});

Breadcrumbs::for('recomendacionesatencion.create', function (BreadcrumbTrail $trail) {
    $trail->parent('recomendacionesatencion.index');
    $trail->push('Datos de atención', route('recomendacionesatencion.create'));
});

Breadcrumbs::for('recomendacionesatencion.edit', function (BreadcrumbTrail $trail,$recomendacion) {
    $trail->parent('recomendacionesacciones.index');
    $trail->push('Datos de atención', route('recomendacionesatencion.edit',$recomendacion));
});

Breadcrumbs::for('recomendacionescontestaciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('recomendacionesatencion.index');
    $trail->push('Oficios de contestación', route('recomendacionescontestaciones.index'));
});

Breadcrumbs::for('recomendacionescontestaciones.create', function (BreadcrumbTrail $trail) {
    $trail->parent('recomendacionescontestaciones.index');
    $trail->push('Agregar', route('recomendacionescontestaciones.create'));
});

Breadcrumbs::for('recomendacionescontestaciones.edit', function (BreadcrumbTrail $trail,$contestacion) {
    $trail->parent('recomendacionescontestaciones.index');
    $trail->push('Agregar', route('recomendacionescontestaciones.edit',$contestacion));
});

Breadcrumbs::for('recomendacionescalificacion.edit', function (BreadcrumbTrail $trail,$recomendacion) {
    $trail->parent('recomendacionesatencion.index');
    $trail->push('Calificación de la atención', route('recomendacionescalificacion.edit',$recomendacion));
});

Breadcrumbs::for('recomendacionesanalisis.edit', function (BreadcrumbTrail $trail,$recomedacion) {
    $trail->parent('recomendacionesatencion.index');
    $trail->push('Análisis de la atención', route('recomendacionesanalisis.edit',$recomedacion));
});

Breadcrumbs::for('recomendacionesrevision01.edit', function (BreadcrumbTrail $trail,$recomedacion) {
    $trail->parent('recomendacionesatencion.index');
    $trail->push('Revisar', route('recomendacionesrevision01.edit',$recomedacion));
});

Breadcrumbs::for('recomendacionesrevision.edit', function (BreadcrumbTrail $trail,$recomedacion) {
    $trail->parent('recomendacionesatencion.index');
    $trail->push('Revisar', route('recomendacionesrevision.edit',$recomedacion));
});

Breadcrumbs::for('recomendacionesvalidacion.edit', function (BreadcrumbTrail $trail,$recomedacion) {
    $trail->parent('recomendacionesatencion.index');
    $trail->push('Validar', route('recomendacionesvalidacion.edit',$recomedacion));
});

Breadcrumbs::for('recomendacionesautorizacion.edit', function (BreadcrumbTrail $trail,$recomedacion) {
    $trail->parent('recomendacionesatencion.index');
    $trail->push('Autorizar-Rechazar', route('recomendacionesautorizacion.edit',$recomedacion));
});

Breadcrumbs::for('recomendacionesacuses.edit', function (BreadcrumbTrail $trail,$recomedacion) {
    $trail->parent('recomendacionesatencion.index');
    $trail->push('Acuses', route('recomendacionesacuses.edit',$recomedacion));
});

Breadcrumbs::for('recomendacionesacuses.show', function (BreadcrumbTrail $trail,$recomedacion) {
    $trail->parent('recomendacionesatencion.index');
    $trail->push('Acuses', route('recomendacionesacuses.show',$recomedacion));
});

Breadcrumbs::for('cedulainicial.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Cédulas iniciales', route('cedulainicial.index'));
});

Breadcrumbs::for('cedulainicial.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('cedulainicial.index');
    $trail->push('Cédula', route('cedulainicial.edit',$auditoria));
});

/*solicitudes de aclaracion */
Breadcrumbs::for('solicitudesaclaracion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Solicitudes de Aclaración', route('solicitudesaclaracion.index'));
});

/*solicitudesaclaracionacciones */


Breadcrumbs::for('solicitudesaclaracionatencion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('solicitudesaclaracionacciones.index');
    $trail->push('Atención de las solicitudes de aclaración', route('solicitudesaclaracionatencion.index'));
});

//solicitud de aclaracion contestaciones
Breadcrumbs::for('solicitudesaclaracioncontestacion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('solicitudesaclaracionatencion.index');
    $trail->push('Oficios de contestación', route('solicitudesaclaracioncontestacion.index'));
});

Breadcrumbs::for('solicitudesaclaracioncontestacion.create', function (BreadcrumbTrail $trail) {
    $trail->parent('solicitudesaclaracioncontestacion.index');
    $trail->push('Agregar', route('solicitudesaclaracioncontestacion.create'));
});

Breadcrumbs::for('solicitudesaclaracioncontestacion.edit', function (BreadcrumbTrail $trail,$contestacion) {
    $trail->parent('solicitudesaclaracioncontestacion.index');
    $trail->push('Agregar', route('solicitudesaclaracioncontestacion.edit',$contestacion));
});

Breadcrumbs::for('solicitudesaclaracionanalisis.edit', function (BreadcrumbTrail $trail,$solicitud) {
    $trail->parent('solicitudesaclaracionatencion.index');
    $trail->push('Análisis de la atención', route('solicitudesaclaracionanalisis.edit',$solicitud));
});

Breadcrumbs::for('solicitudesaclanalisisrevision.edit', function (BreadcrumbTrail $trail,$solicitud) {
    $trail->parent('solicitudesaclaracionatencion.index');
    $trail->push('Revisar', route('solicitudesaclanalisisrevision.edit',$solicitud));
});

Breadcrumbs::for('solicitudesaclanalisisrevision02.edit', function (BreadcrumbTrail $trail,$solicitud) {
    $trail->parent('solicitudesaclaracionatencion.index');
    $trail->push('Revisar', route('solicitudesaclanalisisrevision02.edit',$solicitud));
});

Breadcrumbs::for('solicitudesaclaracioncalificacion.edit', function (BreadcrumbTrail $trail,$solicitud) {
    $trail->parent('solicitudesaclaracionatencion.index');
    $trail->push('Calificación de la atención', route('solicitudesaclaracioncalificacion.edit',$solicitud));
});

Breadcrumbs::for('solicitudesaclaracionvalidacion.edit', function (BreadcrumbTrail $trail,$solicitud) {
    $trail->parent('solicitudesaclaracionatencion.index');
    $trail->push('Validar', route('solicitudesaclaracionvalidacion.edit',$solicitud));
});

Breadcrumbs::for('solicitudesaclaracionautorizacion.edit', function (BreadcrumbTrail $trail,$solicitud) {
    $trail->parent('solicitudesaclaracionatencion.index');
    $trail->push('Autorizar-Rechazar', route('solicitudesaclaracionautorizacion.edit',$solicitud));
});


// Breadcrumbs::for('solicitudesaclaracionrevision01.edit', function (BreadcrumbTrail $trail,$solicitud) {
//     $trail->parent('solicitudesaclaracioncalificacion.index');
//     $trail->push('Revisar', route('solicitudesaclaracionrevision01.edit',$solicitud));
// });

// Breadcrumbs::for('solicitudesaclaracionrevision.edit', function (BreadcrumbTrail $trail,$solicitud) {
//     $trail->parent('solicitudesaclaracioncalificacion.index');
//     $trail->push('Revisar', route('solicitudesaclaracionrevision.edit',$solicitud));
// });

// Breadcrumbs::for('solicitudesaclaracionvalidacion.edit', function (BreadcrumbTrail $trail,$solicitud) {
//     $trail->parent('solicitudesaclaracioncalificacion.index');
//     $trail->push('Validar', route('solicitudesaclaracionvalidacion.edit',$solicitud));
// });

// Breadcrumbs::for('solicitudesaclaracionautorizacion.edit', function (BreadcrumbTrail $trail,$solicitud) {
//     $trail->parent('solicitudesaclaracioncalificacion.index');
//     $trail->push('Autorizar-Rechazar', route('solicitudesaclaracionautorizacion.edit',$solicitud));
// });

/*pliegos de observación */
Breadcrumbs::for('pliegosobservacion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Pliegos de Observación', route('pliegosobservacion.index'));
});

/*pliegosobservacionacciones */


/*pliegosobservacionatencion */

Breadcrumbs::for('pliegosobservacionatencion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('pliegosobservacionacciones.index');
    $trail->push('Atención de los pliegos de observación', route('pliegosobservacionatencion.index'));
});

Breadcrumbs::for('pliegosobservacionatencion.create', function (BreadcrumbTrail $trail) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Datos de atención', route('pliegosobservacionatencion.create'));
});

Breadcrumbs::for('pliegosobservacionatencion.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionacciones.index');
    $trail->push('Datos de atención', route('pliegosobservacionatencion.edit',$pliegosobservacion));
});
//pliegos observacion contestaciones
Breadcrumbs::for('pliegosobservacionatencioncontestacion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Oficios de contestación', route('pliegosobservacionatencioncontestacion.index'));
});

Breadcrumbs::for('pliegosobservacionatencioncontestacion.create', function (BreadcrumbTrail $trail) {
    $trail->parent('pliegosobservacionatencioncontestacion.index');
    $trail->push('Agregar', route('pliegosobservacionatencioncontestacion.create'));
});

Breadcrumbs::for('pliegosobservacionatencioncontestacion.edit', function (BreadcrumbTrail $trail,$contestacion) {
    $trail->parent('pliegosobservacionatencioncontestacion.index');
    $trail->push('Editar', route('pliegosobservacionatencioncontestacion.edit',$contestacion));
});

Breadcrumbs::for('pliegosobservacionanalisis.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Análisis de la atención', route('pliegosobservacionanalisis.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionanalisisrevision02.edit', function (BreadcrumbTrail $trail,$solicitud) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Revisar', route('pliegosobservacionanalisisrevision02.edit',$solicitud));
});

Breadcrumbs::for('pliegosobservacionrevision01.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Revisar', route('pliegosobservacionrevision01.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionrevision.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Revisar', route('pliegosobservacionrevision.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosatencioncalificacion.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Atención', route('pliegosatencioncalificacion.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosatencioncalificacionvalidacion.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Validar', route('pliegosatencioncalificacionvalidacion.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosatencioncalificacionautorizacion.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Autorizar-Rechazar', route('pliegosatencioncalificacionautorizacion.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionacuses.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Acuses', route('pliegosobservacionacuses.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionacuses.show', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Acuses', route('pliegosobservacionacuses.show',$pliegosobservacion));
});



