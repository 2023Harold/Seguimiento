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

Breadcrumbs::for('seguimientoauditoriaacciones.consulta', function (BreadcrumbTrail $trail) {
    $trail->parent('seguimientoauditorias');
    $trail->push('Acciones', route('seguimientoauditoriaacciones.index'));
});

Breadcrumbs::for('seguimientoauditoriarevision.edit', function (BreadcrumbTrail $trail,$auditoria) {
    $trail->parent('seguimientoauditorias');
    $trail->push('Revisar', route('seguimientoauditoriarevision.edit',$auditoria));
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

/*Radicacion*/
Breadcrumbs::for('radicacion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Radicación', route('radicacion.index'));
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
Breadcrumbs::for('comparecenciaacuse.show', function (BreadcrumbTrail $trail,$comparecencia) {
    $trail->parent('radicacion.index');
    $trail->push('Acuses', route('comparecenciaacuse.show',$comparecencia));
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
Breadcrumbs::for('comparecenciaacta.show', function (BreadcrumbTrail $trail,$comparecencia) {
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
Breadcrumbs::for('prasacciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('pras.index');
    $trail->push('Acciones', route('prasacciones.index'));

});

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
Breadcrumbs::for('recomendacionesacciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('recomendaciones.index');
    $trail->push('Acciones', route('recomendacionesacciones.index'));
});

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
Breadcrumbs::for('solicitudesaclaracionacciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('solicitudesaclaracion.index');
    $trail->push('Acciones', route('solicitudesaclaracionacciones.index'));
});

Breadcrumbs::for('solicitudesaclaracionatencion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('solicitudesaclaracionacciones.index');
    $trail->push('Atención de las solciitudes de aclaración', route('solicitudesaclaracionatencion.index'));
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

Breadcrumbs::for('solicitudesaclaracionanalisis.edit', function (BreadcrumbTrail $trail,$solicitud) {
    $trail->parent('solicitudesaclaracionatencion.index');
    $trail->push('Análisis de la atención', route('solicitudesaclaracionanalisis.edit',$solicitud));
});

// Breadcrumbs::for('solicitudesaclaracioncalificacion.index', function (BreadcrumbTrail $trail) {
//     $trail->parent('solicitudesaclaracionacciones.index');
//     $trail->push('Calificación de la atención', route('solicitudesaclaracioncalificacion.index'));
// });

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
Breadcrumbs::for('pliegosobservacionacciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('pliegosobservacion.index');
    $trail->push('Acciones', route('pliegosobservacionacciones.index'));
});

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
//solicitud de aclaracion contestaciones
Breadcrumbs::for('pliegosobservacionatencioncontestacion.index', function (BreadcrumbTrail $trail) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Oficios de contestación', route('pliegosobservacioncontestacion.index'));
});

Breadcrumbs::for('pliegosobservacionatencioncontestacion.create', function (BreadcrumbTrail $trail) {
    $trail->parent('pliegosobservacioncontestacion.index');
    $trail->push('Agregar', route('pliegosobservacioncontestacion.create'));
});

Breadcrumbs::for('pliegosobservacioncalificacion.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Calificación de la atención', route('recomendacionescalificacion.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionanalisis.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Análisis de la atención', route('pliegosobservacionanalisis.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionrevision01.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Revisar', route('pliegosobservacionrevision01.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionrevision.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Revisar', route('pliegosobservacionrevision.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionalidacion.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Validar', route('pliegosobservacionvalidacion.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionautorizacion.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Autorizar-Rechazar', route('pliegosobservacionautorizacion.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionacuses.edit', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Acuses', route('pliegosobservacionacuses.edit',$pliegosobservacion));
});

Breadcrumbs::for('pliegosobservacionacuses.show', function (BreadcrumbTrail $trail,$pliegosobservacion) {
    $trail->parent('pliegosobservacionatencion.index');
    $trail->push('Acuses', route('pliegosobservacionacuses.show',$pliegosobservacion));
});



