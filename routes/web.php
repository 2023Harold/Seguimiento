<?php

use App\Http\Controllers\AccionesController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\AsignacionLiderAnalistaController;
use App\Http\Controllers\AsignacionDepartamentoController;
use App\Http\Controllers\AsignacionDepartamentoEncargadoController;
use App\Http\Controllers\AsignacionDireccionController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ComparecenciaActaController;
use App\Http\Controllers\ComparecenciaAcusesController;
use App\Http\Controllers\ComparecenciaAnexoController;
use App\Http\Controllers\ComparecenciaAutorizacionController;
use App\Http\Controllers\ComparecenciaCedulaController;
use App\Http\Controllers\ComparecenciaController;
use App\Http\Controllers\ComparecenciaCopiaController;
use App\Http\Controllers\ComparecenciaNotificacionController;
use App\Http\Controllers\ComparecenciaRespuestaController;
use App\Http\Controllers\ComparecenciaValidacionController;
use App\Http\Controllers\ConstanciaController;
use App\Http\Controllers\CotejamientoController;
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\RadicacionAutorizacionController;
use App\Http\Controllers\RadicacionController;
use App\Http\Controllers\RadicacionValidacionController;
use App\Http\Controllers\SeguimientoAuditoriaAutorizacionController;
use App\Http\Controllers\SeguimientoAuditoriaController;
use App\Http\Controllers\SeguimientoAuditoriaRevision01Controller;
use App\Http\Controllers\SeguimientoAuditoriaRevisionController;
use App\Http\Controllers\SeguimientoAuditoriaValidacionController;
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



Route::get('notificaciones',[NotificacionController::class, 'index'])->name('notificaciones.index');
Route::get('marcarleido', [NotificacionController::class, 'marcarleido'])->name('marcarleido');
Route::get('/cotejamiento/{archivo}/{model}', [CotejamientoController::class, 'cotejamiento'])->name('cotejamiento');
Route::post('getCargosAsociados', [SeguimientoAuditoriaController::class, 'getCargosAsociados'])->name('getCargosAsociados');
Route::post('archivo', [ArchivoController::class, 'upload']);

//Firma electrónica
//Route::resource('firmaarchivodemo', 'FirmaArchivoController');
Route::post('firmar', [FirmaController::class, 'firmar'])->name('firmar');
Route::post('finalizarfirma', [FirmaController::class, 'finalizarfirma'])->name('finalizarfirma');
Route::post('finalizarfirmapdf', [FirmaController::class, 'finalizarfirmapdf'])->name('finalizarfirmapdf');
Route::get('constancia/mostrarConstancia/{constancia}/{rutaCerrar}', [ConstanciaController::class, 'mostrarConstancia'])->name('constancia.mostrarConstancia');
// Route::resource('constancia', 'ConstanciaController', ['parameters' => ['constancia' => 'constancia']]);

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

/*Comparecencia*/
Route::resource('comparecencia', ComparecenciaController::class,['parameters' => ['comparecencia' => 'comparecencia']]);
Route::resource('comparecencianotificacion', ComparecenciaNotificacionController::class,['parameters' => ['comparecencianotificacion' => 'comparecencia']]);
Route::resource('comparecenciaanexo', ComparecenciaAnexoController::class,['parameters' => ['comparecenciaanexo' => 'anexo']]);
Route::resource('comparecenciacopia', ComparecenciaCopiaController::class,['parameters' => ['comparecenciacopia' => 'copia']]);
Route::get('auditoriacomparecencia/{auditoria}', [ComparecenciaController::class,'auditoria'])->name('comparecencia.auditoria');
Route::resource('comparecenciavalidacion', ComparecenciaValidacionController::class,['parameters' => ['comparecenciavalidacion' => 'comparecencia']]);
Route::resource('comparecenciaautorizacion', ComparecenciaAutorizacionController::class,['parameters' => ['comparecenciaautorizacion' => 'comparecencia']]);
Route::resource('comparecenciaacuse', ComparecenciaAcusesController::class,['parameters' => ['comparecenciaacuse' => 'comparecencia']]);
Route::resource('comparecenciacedula', ComparecenciaCedulaController::class,['parameters' => ['comparecenciacedula' => 'comparecencia']]);
Route::resource('comparecenciaacta', ComparecenciaActaController::class,['parameters' => ['comparecenciaacta' => 'comparecencia']]);
Route::resource('comparecenciarespuesta', ComparecenciaRespuestaController::class,['parameters' => ['comparecenciarespuesta' => 'comparecencia']]);