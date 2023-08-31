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
    $trail->push('Agendar', route('comparecenciaagenda.edit',$comparecencia));
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

/*recomendaciones */
Breadcrumbs::for('recomendaciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Recomendaciones', route('recomendaciones.index'));
    
});


/*recomendacionesaccion */
Breadcrumbs::for('recomendacionesacciones.index', function (BreadcrumbTrail $trail) {
    $trail->parent('recomendaciones');
    $trail->push('Acciones', route('recomendaciones.index'));
    
});
