<?php

use App\Http\Controllers\AccionesController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\AsignacionLiderAnalistaController;
use App\Http\Controllers\AsignacionDepartamentoController;
use App\Http\Controllers\AsignacionDepartamentoEncargadoController;
use App\Http\Controllers\AsignacionDireccionController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\CedulaInicialController;
use App\Http\Controllers\ComparecenciaActaController;
use App\Http\Controllers\ComparecenciaAcusesController;
use App\Http\Controllers\ComparecenciaAgendaController;
use App\Http\Controllers\ComparecenciaController;
use App\Http\Controllers\ConstanciaController;
use App\Http\Controllers\CotejamientoController;
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PrasController;
use App\Http\Controllers\PrasaccionesController;
use App\Http\Controllers\PrasTurnoAcusesController;
use App\Http\Controllers\PrasTurnoAutorizacionController;
use App\Http\Controllers\PrasTurnoController;
use App\Http\Controllers\PrasTurnoRevisionController;
use App\Http\Controllers\PrasTurnoValidacionController;
use App\Http\Controllers\RadicacionAutorizacionController;
use App\Http\Controllers\RadicacionController;
use App\Http\Controllers\RadicacionValidacionController;
use App\Http\Controllers\RecomendacionesAccionesController;
use App\Http\Controllers\RecomendacionesAcusesController;
use App\Http\Controllers\RecomendacionesAtencionCalificacionController;
use App\Http\Controllers\RecomendacionesAtencionController;
use App\Http\Controllers\RecomendacionesAtencionDocumentosController;
use App\Http\Controllers\RecomendacionesAutorizacionController;
use App\Http\Controllers\RecomendacionesController;
use App\Http\Controllers\RecomendacionesRevision01Controller;
use App\Http\Controllers\RecomendacionesRevisionController;
use App\Http\Controllers\RecomendacionesValidacionController;
use App\Http\Controllers\SeguimientoAuditoriaAutorizacionController;
use App\Http\Controllers\SeguimientoAuditoriaController;
use App\Http\Controllers\SeguimientoAuditoriaRevision01Controller;
use App\Http\Controllers\SeguimientoAuditoriaRevisionController;
use App\Http\Controllers\SeguimientoAuditoriaValidacionController;
use App\Http\Controllers\SolicitudesAclaracionAccionesController;
use App\Http\Controllers\SolicitudesAclaracionAutorizacionController;
use App\Http\Controllers\SolicitudesAclaracionCalificacionController;
use App\Http\Controllers\SolicitudesAclaracionContestacionController;
use App\Http\Controllers\SolicitudesAclaracionController;
use App\Http\Controllers\SolicitudesAclaracionDocumentosController;
use App\Http\Controllers\SolicitudesAclaracionRevision01Controller;
use App\Http\Controllers\SolicitudesAclaracionRevisionController;
use App\Http\Controllers\SolicitudesAclaracionValidacionController;
use App\Http\Controllers\Usercontroller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    return view('welcome');
});

Route::post('/salir', [LogoutController::class, 'logout'])->name('auth.logout');


//usuarios
Route::resource('user', Usercontroller::class);

Route::get('notificaciones',[NotificacionController::class, 'index'])->name('notificaciones.index');
Route::get('marcarleido', [NotificacionController::class, 'marcarleido'])->name('marcarleido');
Route::get('/cotejamiento/{archivo}/{model}', [CotejamientoController::class, 'cotejamiento'])->name('cotejamiento');
Route::post('getCargosAsociados', [SeguimientoAuditoriaController::class, 'getCargosAsociados'])->name('getCargosAsociados');
Route::post('archivo', [ArchivoController::class, 'upload']);

//Firma electrónica
Route::post('firmar', [FirmaController::class, 'firmar'])->name('firmar');
Route::post('finalizarfirma', [FirmaController::class, 'finalizarfirma'])->name('finalizarfirma');
Route::post('finalizarfirmapdf', [FirmaController::class, 'finalizarfirmapdf'])->name('finalizarfirmapdf');
Route::get('constancia/mostrarConstancia/{constancia}/{rutaCerrar}', [ConstanciaController::class, 'mostrarConstancia'])->name('constancia.mostrarConstancia');

//Registro de auditorias
Route::resource('seguimientoauditoria', SeguimientoAuditoriaController::class, ['parameters' => ['seguimientoauditoria' => 'auditoria']]);
Route::resource('seguimientoauditoriaacciones', AccionesController::class, ['parameters' => ['seguimientoauditoriaacciones' => 'accion']]);
Route::get('/seguimientoauditoria/acciones/{auditoria}', [SeguimientoAuditoriaController::class, 'auditoriaAcciones'])->name('seguimientoauditoria.acciones');
Route::get('/seguimientoauditoria/{auditoria}', [SeguimientoAuditoriaController::class, 'concluir'])->name('seguimientoauditoria.concluir');
Route::get('/seguimientoauditoria/acciones/consulta/{auditoria}', [SeguimientoAuditoriaController::class, 'accionesConsulta'])->name('seguimientoauditoria.accionesconsulta');
Route::resource('seguimientoauditoriarevisionlp', SeguimientoAuditoriaRevision01Controller::class, ['parameters' => ['seguimientoauditoriarevisionlp' => 'auditoria']]);
Route::resource('seguimientoauditoriarevision', SeguimientoAuditoriaRevisionController::class, ['parameters' => ['seguimientoauditoriarevision' => 'auditoria']]);
Route::resource('seguimientoauditoriavalidacion', SeguimientoAuditoriaValidacionController::class, ['parameters' => ['seguimientoauditoriavalidacion' => 'auditoria']]);
Route::resource('seguimientoauditoriaautorizacion', SeguimientoAuditoriaAutorizacionController::class, ['parameters' => ['seguimientoauditoriaautorizacion' => 'auditoria']]);

//Asignaciones 
/*Direcciones*/ 
Route::resource('asignaciondireccion', AsignacionDireccionController::class, ['parameters' => ['asignaciondireccion' => 'auditoria']]);
Route::get('/asignaciondireccion/acciones/consulta/{auditoria}', [AsignacionDireccionController::class, 'accionesConsulta'])->name('asignaciondireccion.accionesconsulta');
Route::post('getDirector', [AsignacionDireccionController::class, 'getDirector'])->name('getDirector');
Route::get('/asignaciondireccion/reasignacion/{auditoria}', [AsignacionDireccionController::class, 'reasignar'])->name('asignaciondireccion.reasignar');
/*Departamentos*/
Route::resource('asignaciondepartamento', AsignacionDepartamentoController::class, ['parameters' => ['asignaciondepartamento' => 'auditoria']]);
Route::get('/asignaciondepartamento/acciones/consulta/{auditoria}', [AsignacionDepartamentoController::class, 'accionesConsulta'])->name('asignaciondepartamento.accionesconsulta');
Route::post('getJefeDepartamento', [AsignacionDepartamentoController::class, 'getJefeDepartamento'])->name('getJefeDepartamento');
Route::get('/asignaciondepartamento/reasignacion/{accion}', [AsignacionDepartamentoController::class, 'reasignar'])->name('asignaciondepartamento.reasignar');
/*Lider Analista*/
Route::resource('asignacionlideranalista', AsignacionLiderAnalistaController::class, ['parameters' => ['asignacionlideranalista' => 'auditoria']]);
Route::post('getLider', [AsignacionLiderAnalistaController::class, 'getLider'])->name('getLider');
Route::post('getAnalista', [AsignacionLiderAnalistaController::class, 'getAnalista'])->name('getAnalista');
Route::get('/asignacionlider/reasignacion/{auditoria}', [AsignacionLiderAnalistaController::class, 'reasignarlider'])->name('asignacionlideranalista.reasignarlider');
Route::get('/asignacionanalista/reasignacion/{auditoria}', [AsignacionLiderAnalistaController::class, 'reasignaranalista'])->name('asignacionlideranalista.reasignaranalista');
Route::get('/asignacionlideranalista/acciones/consulta/{auditoria}', [AsignacionLiderAnalistaController::class, 'accionesConsulta'])->name('asignacionlideranalista.accionesconsulta');
Route::get('/asignacionlideranalista/consulta/{auditoria}', [AsignacionLiderAnalistaController::class, 'consulta'])->name('asignacionlideranalista.consulta');
/*Departamento Encargado*/
Route::resource('asignaciondepartamentoencargado', AsignacionDepartamentoEncargadoController::class, ['parameters' => ['asignaciondepartamentoencargado' => 'auditoria']]);
/*Radicación*/
Route::resource('radicacion', RadicacionController::class);
Route::get('auditoriaradicacion/{auditoria}', [RadicacionController::class,'auditoria'])->name('radicacion.auditoria');
Route::resource('radicacionvalidacion', RadicacionValidacionController::class,['parameters' => ['radicacionvalidacion' => 'radicacion']]);
Route::resource('radicacionautorizacion', RadicacionAutorizacionController::class,['parameters' => ['radicacionautorizacion' => 'radicacion']]);
Route::resource('comparecenciaacuse', ComparecenciaAcusesController::class,['parameters' => ['comparecenciaacuse' => 'comparecencia']]);
Route::resource('comparecenciaagenda', ComparecenciaAgendaController::class,['parameters' => ['comparecenciaagenda' => 'comparecencia']]);

/*Comparecencia*/
Route::resource('comparecencia', ComparecenciaController::class,['parameters' => ['comparecencia' => 'comparecencia']]);
Route::post('getAgendaComparecencias', [AjaxController::class, 'getAgendaComparecencias'])->name('getAgendaComparecencias');
Route::get('auditoriacomparecencia/{auditoria}', [ComparecenciaController::class,'auditoria'])->name('comparecencia.auditoria');
Route::resource('comparecenciaacuse', ComparecenciaAcusesController::class,['parameters' => ['comparecenciaacuse' => 'comparecencia']]);
Route::resource('comparecenciaacta', ComparecenciaActaController::class,['parameters' => ['comparecenciaacta' => 'comparecencia']]);

/*pras*/
Route::resource('pras',PrasController::class,['parameters' => ['pras' => 'auditoria']]);

/*prasacciones*/
Route::resource('prasacciones',PrasaccionesController::class,['parameters' => ['prasacciones' => 'accion']]);/// sirve para cambiar la variable que acepta esa ruta
Route::resource('prasturno',PrasTurnoController::class,['parameters' => ['prasturno' => 'pras']]);
Route::resource('prasturnorevision',PrasTurnoRevisionController::class,['parameters' => ['prasturnorevision' => 'pras']]);
Route::resource('prasturnovalidacion',PrasTurnoValidacionController::class,['parameters' => ['prasturnovalidacion' => 'pras']]);
Route::resource('prasturnoautorizacion',PrasTurnoAutorizacionController::class,['parameters' => ['prasturnoautorizacion' => 'pras']]);
Route::resource('prasturnoacuses',PrasTurnoAcusesController::class,['parameters' => ['prasturnoacuses' => 'pras']]);

/*Recomendaciones*/
Route::resource('recomendaciones',RecomendacionesController::class,['parameters' => ['recomendaciones' => 'auditoria']]);
Route::resource('recomendacionesacciones',RecomendacionesAccionesController::class,['parameters' => ['recomendacionesacciones' => 'accion']]);/// sirve para cambiar la variable que acepta esa ruta
Route::resource('recomendacionesatencion',RecomendacionesAtencionController::class,['parameters' => ['recomendacionesatencion' => 'recomendacion']]);
Route::resource('recomendacionescalificacion',RecomendacionesAtencionCalificacionController::class,['parameters' => ['recomendacionescalificacion' => 'recomendacion']]);
Route::resource('recomendacionesdocumentos',RecomendacionesAtencionDocumentosController::class,['parameters' => ['recomendacionesdocumentos' => 'documento']]);

Route::resource('recomendacionesrevision01',RecomendacionesRevision01Controller::class,['parameters' => ['recomendacionesrevision01' => 'recomendacion']]);
Route::resource('recomendacionesrevision',RecomendacionesRevisionController::class,['parameters' => ['recomendacionesrevision' => 'recomendacion']]);
Route::resource('recomendacionesvalidacion',RecomendacionesValidacionController::class,['parameters' => ['recomendacionesvalidacion' => 'recomendacion']]);
Route::resource('recomendacionesautorizacion',RecomendacionesAutorizacionController::class,['parameters' => ['recomendacionesautorizacion' => 'recomendacion']]);
Route::resource('recomendacionesacuses',RecomendacionesAcusesController::class,['parameters' => ['recomendacionesacuses' => 'recomendacion']]);
Route::resource('cedulainicial',CedulaInicialController::class,['parameters' => ['cedulainicial' => 'auditoria']]);

/*solicitudesaclaracion*/
Route::resource('solicitudesaclaracion',SolicitudesAclaracionController::class,['parameters' => ['solicitudesaclaracion' => 'auditoria']]);
Route::resource('solicitudesaclaracionacciones',SolicitudesAclaracionAccionesController::class,['parameters' => ['solicitudesaclaracionacciones' => 'accion']]);/// sirve para cambiar la variable que acepta esa ruta
Route::resource('solicitudesaclaracioncontestacion',SolicitudesAclaracionContestacionController::class,['parameters' => ['solicitudesaclaracioncontestacion' => 'solicitud']]);
Route::resource('solicitudesaclaracioncalificacion',SolicitudesAclaracionCalificacionController::class,['parameters' => ['solicitudesaclaracioncalificacion' => 'solicitud']]);
Route::resource('solicitudesaclaraciondocumentos',SolicitudesAclaracionDocumentosController::class,['parameters' => ['solicitudesaclaraciondocumentos' => 'documento']]);

Route::resource('solicitudesaclaracionrevision01',SolicitudesAclaracionRevision01Controller::class,['parameters' => ['solicitudesaclaracionrevision01' => 'solicitud']]);
Route::resource('solicitudesaclaracionrevision',SolicitudesAclaracionRevisionController::class,['parameters' => ['solicitudesaclaracionrevision' => 'solicitud']]);
Route::resource('solicitudesaclaracionvalidacion',SolicitudesAclaracionValidacionController::class,['parameters' => ['solicitudesaclaracionvalidacion' => 'solicitud']]);
Route::resource('solicitudesaclaracionautorizacion',SolicitudesAclaracionAutorizacionController::class,['parameters' => ['solicitudesaclaracionautorizacion' => 'solicitud']]);
