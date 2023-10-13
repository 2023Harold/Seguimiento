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
use App\Http\Controllers\PliegosObservacionAccionesController;
use App\Http\Controllers\PliegosObservacionAcusesController;
use App\Http\Controllers\PliegosObservacionAnalisisEnvioController;
use App\Http\Controllers\PliegosObservacionAnalisisRevisionController;
use App\Http\Controllers\PliegosObservacionAtencionAnalisisController;
use App\Http\Controllers\PliegosObservacionAtencionCalificacionController;
use App\Http\Controllers\PliegosObservacionAtencionContestacionController;
use App\Http\Controllers\PliegosObservacionAtencionController;
use App\Http\Controllers\PliegosObservacionAtencionDocumentosController;
use App\Http\Controllers\PliegosObservacionAutorizacionController;
use App\Http\Controllers\PliegosObservacionController;
use App\Http\Controllers\PliegosObservacionRevision01Controller;
use App\Http\Controllers\PliegosObservacionRevisionController;
use App\Http\Controllers\PliegosObservacionValidacionController;
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
use App\Http\Controllers\RecomendacionesAnalisisController;
use App\Http\Controllers\RecomendacionesAnalisisEnvioController;
use App\Http\Controllers\RecomendacionesAnalisisRevisionController;
use App\Http\Controllers\RecomendacionesAnalisisRevisionJefeController;
use App\Http\Controllers\RecomendacionesAtencionCalificacionController;
use App\Http\Controllers\RecomendacionesAtencionContestacionController;
use App\Http\Controllers\RecomendacionesAtencionController;
use App\Http\Controllers\RecomendacionesAtencionDocumentosController;
use App\Http\Controllers\RecomendacionesAutorizacionController;
use App\Http\Controllers\RecomendacionesController;
use App\Http\Controllers\RecomendacionesRevision01Controller;
use App\Http\Controllers\RecomendacionesRevisionController;
use App\Http\Controllers\RecomendacionesValidacionController;
use App\Http\Controllers\RevisionesRecomendacionesAtencionController;
use App\Http\Controllers\RevisionesPliegosObservacionController;
use App\Http\Controllers\RevisionesRecomendacionesController;
use App\Http\Controllers\RevisionesSolicitudesAtencionController;
use App\Http\Controllers\RevisionesSolicitudesController;
use App\Http\Controllers\SeguimientoAuditoriaAccionRevision01Controller;
use App\Http\Controllers\SeguimientoAuditoriaAccionRevisionController;
use App\Http\Controllers\SeguimientoAuditoriaAutorizacionController;
use App\Http\Controllers\SeguimientoAuditoriaController;
use App\Http\Controllers\SeguimientoAuditoriaRevision01Controller;
use App\Http\Controllers\SeguimientoAuditoriaRevisionController;
use App\Http\Controllers\SeguimientoAuditoriaValidacionController;
use App\Http\Controllers\SolicitudesAclaracionAccionesController;
use App\Http\Controllers\SolicitudesAclaracionAnalisisController;
use App\Http\Controllers\SolicitudesAclaracionAnalisisEnvioController;
use App\Http\Controllers\SolicitudesAclaracionAnalisisRevisionController;
use App\Http\Controllers\SolicitudesAclaracionAnalisisRevisionJefeController;
use App\Http\Controllers\SolicitudesAclaracionAtencionController;
use App\Http\Controllers\SolicitudesAclaracionAutorizacionController;
use App\Http\Controllers\SolicitudesAclaracionCalificacionController;
use App\Http\Controllers\SolicitudesAclaracionContestacionController;
use App\Http\Controllers\SolicitudesAclaracionController;
use App\Http\Controllers\SolicitudesAclaracionDocumentosController;
use App\Http\Controllers\SolicitudesAclaracionRevision01Controller;
use App\Http\Controllers\SolicitudesAclaracionRevisionController;
use App\Http\Controllers\SolicitudesAclaracionValidacionController;
use App\Http\Controllers\Usercontroller;
use App\Http\Requests\PliegosObservacionContestacionRequest;
use App\Models\PliegosObservacion;
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
Route::resource('seguimientoauditoriaaccionrevision01', SeguimientoAuditoriaAccionRevision01Controller::class, ['parameters' => ['seguimientoauditoriaaccionrevision01' => 'accion']]);
Route::resource('seguimientoauditoriaaccionrevision', SeguimientoAuditoriaAccionRevisionController::class, ['parameters' => ['seguimientoauditoriaaccionrevision' => 'accion']]);
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
Route::resource('recomendacionescontestaciones',RecomendacionesAtencionContestacionController::class,['parameters' => ['recomendacionescontestaciones' => 'contestacion']]);
Route::get('recomendacionescontestacionesoficios/{recomendacion}', [RecomendacionesAtencionContestacionController::class,'oficiosrecomendacion'])->name('recomendacionescontestaciones.oficiosrecomendacion');
Route::resource('recomendacionesanalisis',RecomendacionesAnalisisController::class,['parameters' => ['recomendacionesanalisis' => 'recomendacion']]);
Route::resource('recomendacionesanalisisenvio',RecomendacionesAnalisisEnvioController::class,['parameters' => ['recomendacionesanalisisenvio' => 'recomendacion']]);
Route::resource('recomendacionesanalisisrevision',RecomendacionesAnalisisRevisionController::class,['parameters' => ['recomendacionesanalisisrevision' => 'recomendacion']]);
Route::resource('recomendacionesanalisisrevision02',RecomendacionesAnalisisRevisionJefeController::class,['parameters' => ['recomendacionesanalisisrevision02' => 'recomendacion']]);

// Route::resource('recomendacionesrevision01',RecomendacionesRevision01Controller::class,['parameters' => ['recomendacionesrevision01' => 'recomendacion']]);
// Route::resource('recomendacionesrevision',RecomendacionesRevisionController::class,['parameters' => ['recomendacionesrevision' => 'recomendacion']]);
Route::resource('recomendacionesvalidacion',RecomendacionesValidacionController::class,['parameters' => ['recomendacionesvalidacion' => 'recomendacion']]);
Route::resource('recomendacionesautorizacion',RecomendacionesAutorizacionController::class,['parameters' => ['recomendacionesautorizacion' => 'recomendacion']]);
Route::resource('recomendacionesacuses',RecomendacionesAcusesController::class,['parameters' => ['recomendacionesacuses' => 'recomendacion']]);
/*Revisiones*/
Route::resource('revisionesrecomendaciones',RevisionesRecomendacionesController::class,['parameters' => ['revisionesrecomendaciones' => 'comentario']]);
Route::resource('revisionesrecomendacionesatencion',RevisionesRecomendacionesAtencionController::class,['parameters' => ['revisionesrecomendacionesatencion' => 'comentario']]);
Route::resource('cedulainicial',CedulaInicialController::class,['parameters' => ['cedulainicial' => 'auditoria']]);

/*solicitudesaclaracion*/
Route::resource('solicitudesaclaracion',SolicitudesAclaracionController::class,['parameters' => ['solicitudesaclaracion' => 'auditoria']]);
Route::resource('solicitudesaclaracionacciones',SolicitudesAclaracionAccionesController::class,['parameters' => ['solicitudesaclaracionacciones' => 'accion']]);/// sirve para cambiar la variable que acepta esa ruta
Route::resource('solicitudesaclaracionatencion',SolicitudesAclaracionAtencionController::class,['parameters' => ['solicitudesaclaracionatencion' => 'solicitud']]);
Route::resource('solicitudesaclaracioncontestacion',SolicitudesAclaracionContestacionController::class,['parameters' => ['solicitudesaclaracioncontestacion' => 'contestacion']]);
Route::get('solicitudesaclaracioncontestacionoficios/{solicitud}', [SolicitudesAclaracionContestacionController::class,'oficiossolicitudes'])->name('solicitudescontestaciones.oficiossolicitud');
Route::resource('solicitudesaclaraciondocumentos',SolicitudesAclaracionDocumentosController::class,['parameters' => ['solicitudesaclaraciondocumentos' => 'documento']]);
Route::resource('solicitudesaclaracionanalisis',SolicitudesAclaracionAnalisisController::class,['parameters' => ['solicitudesaclaracionanalisis' => 'solicitud']]);
Route::resource('solicitudesaclanalisisenvio',SolicitudesAclaracionAnalisisEnvioController::class,['parameters' => ['solicitudesaclanalisisenvio' => 'solicitud']]);
Route::resource('solicitudesaclanalisisrevision',SolicitudesAclaracionAnalisisRevisionController::class,['parameters' => ['solicitudesaclanalisisrevision' => 'solicitud']]);
Route::resource('solicitudesaclanalisisrevision02',SolicitudesAclaracionAnalisisRevisionJefeController::class,['parameters' => ['solicitudesaclanalisisrevision02' => 'solicitud']]);
Route::resource('solicitudesaclaracioncalificacion',SolicitudesAclaracionCalificacionController::class,['parameters' => ['solicitudesaclaracioncalificacion' => 'solicitud']]);
Route::resource('solicitudesaclaracionvalidacion',SolicitudesAclaracionValidacionController::class,['parameters' => ['solicitudesaclaracionvalidacion' => 'solicitud']]);
Route::resource('solicitudesaclaracionautorizacion',SolicitudesAclaracionAutorizacionController::class,['parameters' => ['solicitudesaclaracionautorizacion' => 'solicitud']]);
Route::resource('revisionessolicitudes',RevisionesSolicitudesController::class,['parameters' => ['revisionessolicitudes' => 'comentario']]);
Route::resource('revisionessolicitudesatencion',RevisionesSolicitudesAtencionController::class,['parameters' => ['revisionessolicitudesatencion' => 'comentario']]);


/*pliegosobservacion*/
Route::resource('pliegosobservacion',PliegosObservacionController::class,['parameters' => ['pliegosobservacion' => 'auditoria']]);
Route::resource('pliegosobservacionacciones',PliegosObservacionAccionesController::class,['parameters' => ['pliegosobservacionacciones' => 'accion']]);/// sirve para cambiar la variable que acepta esa ruta
Route::resource('pliegosobservacionatencion',PliegosObservacionAtencionController::class,['parameters' => ['pliegosobservacionatencion' => 'pliegosobservacionatencion']]);
Route::resource('pliegosatencioncontestacion',PliegosObservacionAtencionContestacionController::class,['parameters' => ['pliegosatencioncontestacion' => 'contestacion']]);
Route::get('pliegosobservacioncontestacionoficios/{pliegosobservacion}', [PliegosObservacionAtencionContestacionController::class,'oficiospliegosobservacion'])->name('pliegosobservacioncontestacion.oficiospliegosobservacion');
Route::resource('pliegosobservaciondocumentos',PliegosObservacionAtencionDocumentosController::class,['parameters' => ['pliegosobservaciondocumentos' => 'documento']]);
Route::resource('pliegosobservacionanalisis',PliegosObservacionAtencionAnalisisController::class,['parameters' => ['pliegosobservacionanalisis' => 'pliegosobservacion']]);
Route::resource('pliegosobservacionanalisisenvio',PliegosObservacionAnalisisEnvioController::class,['parameters' => ['pliegosobservacionanalisisenvio' => 'pliegosobservacion']]);
Route::resource('pliegosobservacionanalisisrevision',PliegosObservacionAnalisisRevisionController::class,['parameters' => ['pliegosobservacionanalisisrevision' => 'pliegosobservacion']]);
Route::resource('pliegosobservacionrevision01',PliegosObservacionRevision01Controller::class,['parameters' => ['pliegosobservacionrevision01' => 'pliegosobservacion']]);

// Route::resource('pliegosobservacionrevision',PliegosObservacionRevisionController::class,['parameters' => ['pliegosobservacionrevision' => 'pliegosobservacion']]);
Route::resource('pliegosatencioncalificacion',PliegosObservacionAtencionCalificacionController::class,['parameters' => ['pliegosobservacioncalificacion' => 'pliegosobservacion']]);
// Route::resource('pliegosobservacioncalificacion',PliegosObservacionAtencionCalificacionController::class,['parameters' => ['pliegosobservacioncalificacion' => 'pliegosobservacion']]);
Route::resource('pliegosobservacionvalidacion',PliegosObservacionValidacionController::class,['parameters' => ['pliegosobservacionvalidacion' => 'pliegosobservacion']]);
Route::resource('pliegosobservacionautorizacion',PliegosObservacionAutorizacionController::class,['parameters' => ['pliegosobservacionautorizacion' => 'pliegosobservacion']]);
Route::resource('pliegosobservacionesacuses',PliegosObservacionAcusesController::class,['parameters' => ['pliegosobservacionacuses' => 'pliegosobservacion']]);
Route::resource('revisionespliegosobservacion',RevisionesPliegosObservacionController::class,['parameters' => ['revisionespliegosobservacion' => 'comentario']]);



