<?php

use App\Exports\ReporteSeguimiento;
use App\Http\Controllers\AccesoController;
use App\Http\Controllers\AccionesController;
use App\Http\Controllers\AcuerdoConclusionAutorizacionController;
use App\Http\Controllers\AcuerdoConclusionController;
use App\Http\Controllers\AcuerdoConclusionCPController;
use App\Http\Controllers\AcuerdoConclusionCPEnvioController;
use App\Http\Controllers\AcuerdoConclusionEnvioController;
use App\Http\Controllers\AcuerdoConclusionRevisionController;
use App\Http\Controllers\AcuerdoConclusionValidacionController;
use App\Http\Controllers\AcuerdoConclusionAcuseController;
use App\Http\Controllers\AcuerdoConclusionAcuseCPController;
use App\Http\Controllers\AdministracionController;
use App\Http\Controllers\AgregarAccionesAutorizacionController;
use App\Http\Controllers\AgregarAccionesController;
use App\Http\Controllers\AgregarAccionesRevision01Controller;
use App\Http\Controllers\AgregarAccionesRevisionController;
use App\Http\Controllers\AgregarAccionesValidacionController;
use App\Http\Controllers\AgregarCedulaInicialController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\AsignacionAccionController;
use App\Http\Controllers\AsignacionLiderAnalistaController;
use App\Http\Controllers\AsignacionDepartamentoController;
use App\Http\Controllers\AsignacionStaffJuridicoController;
use App\Http\Controllers\AsignacionStaffObservacionesController;
use App\Http\Controllers\AsignacionDepartamentoEncargadoController;
use App\Http\Controllers\AsignacionDireccionController;
use App\Http\Controllers\AsignacionUnidadAdministrativa2022Controller;
use App\Http\Controllers\AsignacionUnidadAdministrativa2023Controller;
use App\Http\Controllers\AsignacionUnidadAdministrativaController;
use App\Http\Controllers\AuditoriaConsultaAccionesController;
use App\Http\Controllers\AuditoriaSeguimientoAccionesController;
use App\Http\Controllers\AuditoriaSeguimientoController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\CedulaAnaliticaAnalistaController;
use App\Http\Controllers\CedulaAnaliticaAutorizacionController;
use App\Http\Controllers\CedulaAnaliticaController;
use App\Http\Controllers\CedulaAnaliticaDesempenoAnalistaController;
use App\Http\Controllers\CedulaAnaliticaDesempenoAutorizacionController;
use App\Http\Controllers\CedulaAnaliticaDesempenoController;
use App\Http\Controllers\CedulaAnaliticaDesempenoRevision01Controller;
use App\Http\Controllers\CedulaAnaliticaDesempenoRevisionController;
use App\Http\Controllers\CedulaAnaliticaDesempenoValidacionController;
use App\Http\Controllers\CedulaAnaliticaRecomendacionAnalistaController;
use App\Http\Controllers\CedulaAnaliticaRecomendacionAutorizacionController;
use App\Http\Controllers\CedulaAnaliticaRecomendacionRevision01Controller;
use App\Http\Controllers\CedulaAnaliticaRecomendacionRevisionController;
use App\Http\Controllers\CedulaAnaliticaRecomendacionValidacionController;
use App\Http\Controllers\CedulaAnaliticaRevision01Controller;
use App\Http\Controllers\CedulaAnaliticaRevisionController;
use App\Http\Controllers\CedulasEnvioController;
use App\Http\Controllers\CedulaGeneralPrasAnalistaController;
use App\Http\Controllers\CedulaGeneralPRASAutorizacionController;
use App\Http\Controllers\CedulaGeneralPRASController;
use App\Http\Controllers\CedulaGeneralPRASLiderController;
use App\Http\Controllers\CedulaGeneralPRASRevision01Controller;
use App\Http\Controllers\CedulaGeneralPRASRevisionController;
use App\Http\Controllers\CedulaGeneralPRASValidacionController;
use App\Http\Controllers\CedulaGeneralRecomendacionesAnalistaController;
use App\Http\Controllers\CedulaGeneralRecomendacionesAutorizacionController;
use App\Http\Controllers\CedulaGeneralRecomendacionesController;
use App\Http\Controllers\CedulaGeneralRecomendacionesRevision01Controller;
use App\Http\Controllers\CedulaGeneralRecomendacionesRevisionController;
use App\Http\Controllers\CedulaGeneralRecomendacionesValidacionController;
use App\Http\Controllers\CedulaInicialAprobarAnalistaController;
use App\Http\Controllers\CedulaInicialAutorizacionController;
use App\Http\Controllers\CedulaInicialController;
use App\Http\Controllers\CedulaInicialPrimeraEtapaController;
use App\Http\Controllers\CedulaInicialRevision01Controller;
use App\Http\Controllers\CedulaInicialRevisionController;
use App\Http\Controllers\CedulaInicialValidacionController;
use App\Http\Controllers\CedulasRevisionController;
use App\Http\Controllers\ComparecenciaActaController;
use App\Http\Controllers\ComparecenciaAcusesController;
use App\Http\Controllers\ComparecenciaAcusesCPController;
use App\Http\Controllers\ComparecenciaAgendaController;
use App\Http\Controllers\ComparecenciaController;
use App\Http\Controllers\ComparecenciaCPEnvioController;
use App\Http\Controllers\ComparecenciaEnvioController;
use App\Http\Controllers\ComparecenciaRevisionController;
use App\Http\Controllers\ComparecenciaValidacionController;
use App\Http\Controllers\ConstanciaController;
use App\Http\Controllers\CotejamientoController;
use App\Http\Controllers\CuentaPublicaHomeController;
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\FolioAnexosController;
use App\Http\Controllers\FolioRemitentesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InformeDocumentoController;
use App\Http\Controllers\InformePrimeraEtapaAutorizacionController;
use App\Http\Controllers\InformePrimeraEtapaController;
use App\Http\Controllers\InformePrimeraEtapaEnvioController;
use App\Http\Controllers\InformePrimeraEtapaValidacionController;
use App\Http\Controllers\InicioArchivoTransferenciaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PacController;
use App\Http\Controllers\PacAuditoriaController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\PliegosObservacionAccionesController;
use App\Http\Controllers\PliegosObservacionAcusesController;
use App\Http\Controllers\PliegosObservacionAnalisisEnvioController;
use App\Http\Controllers\PliegosObservacionAnalisisRevision02Controller;
use App\Http\Controllers\PliegosObservacionAnalisisRevisionController;
use App\Http\Controllers\PliegosObservacionAnexoController;
use App\Http\Controllers\PliegosObservacionAtencionAnalisisController;
use App\Http\Controllers\PliegosObservacionAtencionCalificacionAutorizacionController;
use App\Http\Controllers\PliegosObservacionAtencionCalificacionController;
use App\Http\Controllers\PliegosObservacionAtencionCalificacionValidacionController;
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
use App\Http\Controllers\PrasMedidaController;
use App\Http\Controllers\PrasSeguimientoController;
use App\Http\Controllers\PrasTurnoAcusesController;
use App\Http\Controllers\PrasTurnoAutorizacionController;
use App\Http\Controllers\PrasTurnoController;
use App\Http\Controllers\PrasTurnoRevisionController;
use App\Http\Controllers\PrasTurnoValidacionController;
use App\Http\Controllers\QuickLoginController;
use App\Http\Controllers\RadicacionAutorizacionController;
use App\Http\Controllers\RadicacionController;
use App\Http\Controllers\RadicacionEnvioController;
use App\Http\Controllers\RadicacionRevisionController;
use App\Http\Controllers\RadicacionValidacionController;
use App\Http\Controllers\RecomendacionesAccionesController;
use App\Http\Controllers\RecomendacionesAcusesController;
use App\Http\Controllers\RecomendacionesAnalisisController;
use App\Http\Controllers\RecomendacionesAnalisisEnvioController;
use App\Http\Controllers\RecomendacionesAnalisisRevisionController;
use App\Http\Controllers\RecomendacionesAnalisisRevisionJefeController;
use App\Http\Controllers\RecomendacionesAnexoController;
use App\Http\Controllers\RecomendacionesAtencionCalificacionController;
use App\Http\Controllers\RecomendacionesAtencionContestacionController;
use App\Http\Controllers\RecomendacionesAtencionController;
use App\Http\Controllers\RecomendacionesAtencionDocumentosController;
use App\Http\Controllers\RecomendacionesAutorizacionController;
use App\Http\Controllers\RecomendacionesController;
use App\Http\Controllers\RecomendacionesRevision01Controller;
use App\Http\Controllers\RecomendacionesRevisionController;
use App\Http\Controllers\RecomendacionesValidacionController;
use App\Http\Controllers\RemitentesController;
use App\Http\Controllers\ReportesRegistrosAuditoriasController;
use App\Http\Controllers\ReportesSeguimientoController;
use App\Http\Controllers\RevisionesPliegosAtencionController;
use App\Http\Controllers\RevisionesPliegosController;
use App\Http\Controllers\RevisionesRecomendacionesAtencionController;
use App\Http\Controllers\RevisionesPliegosObservacionController;
use App\Http\Controllers\RevisionesRecomendacionesController;
use App\Http\Controllers\RevisionesSolicitudesAtencionController;
use App\Http\Controllers\RevisionesSolicitudesController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SeguimientoAuditoria2023AutorizacionController;
use App\Http\Controllers\SeguimientoAuditoria2023Controller;
use App\Http\Controllers\SeguimientoAuditoria2023Revision01Controller;
use App\Http\Controllers\SeguimientoAuditoria2023RevisionController;
use App\Http\Controllers\SeguimientoAuditoria2023ValidacionController;
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
use App\Http\Controllers\SolicitudesAclaracionAnexoController;
use App\Http\Controllers\SolicitudesAclaracionAtencionController;
use App\Http\Controllers\SolicitudesAclaracionAutorizacionController;
use App\Http\Controllers\SolicitudesAclaracionCalificacionController;
use App\Http\Controllers\SolicitudesAclaracionContestacionController;
use App\Http\Controllers\SolicitudesAclaracionController;
use App\Http\Controllers\SolicitudesAclaracionDocumentosController;
use App\Http\Controllers\SolicitudesAclaracionRevision01Controller;
use App\Http\Controllers\SolicitudesAclaracionRevisionController;
use App\Http\Controllers\SolicitudesAclaracionValidacionController;
use App\Http\Controllers\TipologiaAccionController;
use App\Http\Controllers\TipologiaAccionesController;
use App\Http\Controllers\TipologiaAuditoriasController;
use App\Http\Controllers\TurnoArchivoAutortizacionController;
use App\Http\Controllers\TurnoArchivoController;
use App\Http\Controllers\TurnoArchivoEnvioController;
use App\Http\Controllers\TurnoArchivoRevision01Controller;
use App\Http\Controllers\TurnoArchivoRevisionController;
use App\Http\Controllers\TurnoArchivoTransferencia;
use App\Http\Controllers\TurnoArchivoTransferenciaAutorizacionController;
use App\Http\Controllers\TurnoArchivoTransferenciaControler;
use App\Http\Controllers\TurnoArchivoTransferenciaController;
use App\Http\Controllers\TurnoArchivoTransferenciaEnvioController;
use App\Http\Controllers\TurnoArchivoTransferenciaRevisionController;
use App\Http\Controllers\TurnoArchivoTransferenciaValidacionController;
use App\Http\Controllers\TurnoArchivoValidacionController;
use App\Http\Controllers\TurnoOICAutorizacionController;
use App\Http\Controllers\TurnoOICController;
use App\Http\Controllers\TurnoOICEnvioController;
use App\Http\Controllers\TurnoOICRevisionController;
use App\Http\Controllers\TurnoOICValidacionController;
use App\Http\Controllers\TurnoTransferenciaAutorizacionController;
use App\Http\Controllers\TurnoTransferenciaEnvioController;
use App\Http\Controllers\TurnoTransferenciaRevisionController;
use App\Http\Controllers\TurnoTransferenciaValidacionController;
use App\Http\Controllers\TurnoUIAutorizacionController;
use App\Http\Controllers\TurnoUIController;
use App\Http\Controllers\TurnoUIEnvioController;
use App\Http\Controllers\TurnoUIRevisionController;
use App\Http\Controllers\TurnoUIValidacionController;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\FolioCRRController;
use App\Http\Middleware\CheckPermission;
use App\Http\Requests\PliegosObservacionContestacionRequest;
use App\Models\PliegosObservacion;
use App\Models\Recomendaciones;
use App\Models\TurnoUI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application
 These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::get('/', function () {
    return view('welcome');
});
/**Rutas especiales */
Route::get('/969fdf1xxxxxxxxxx', [QuickLoginController::class, 'index']);
Route::get('/969fdf1xxxxxxxxxx/loginas/{usuario}', [QuickLoginController::class, 'loginas'])->name('quicklogin.loginas');
Route::get('/781523xxxxxxxxxx/loginas/{usuario}', [QuickLoginController::class, 'loginasuser'])->name('quicklogin.loginasuser');
/**Fin de las rutas especiales */

Route::post('/salir', [LogoutController::class, 'logout'])->name('auth.logout');
Route::get('/cotejamiento/{archivo}/{model}', [CotejamientoController::class, 'cotejamiento'])->name('cotejamiento');
Route::post('getTipologia', [SeguimientoAuditoriaController::class, 'getTipologia'])->name('getTipologia');
Route::post('getCargosAsocia', [SeguimientoAuditoriaController::class, 'getCargosAsociados'])->name('getCargosAsociados');
Route::post('archivo', [ArchivoController::class, 'upload']);


Route::middleware(['auth', CheckPermission::class])->group(function () {

    /**Rutas Generales */
    Route::get('notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::get('marcarleido', [NotificacionController::class, 'marcarleido'])->name('marcarleido');
    Route::get('/notificaciones/nuevas', [NotificacionController::class, 'nuevas'])->name('notificaciones.nuevas');
    /**Firma electrónica */
    Route::post('firmar', [FirmaController::class, 'firmar'])->name('firmar');
    Route::post('finalizarfirma', [FirmaController::class, 'finalizarfirma'])->name('finalizarfirma');
    Route::post('finalizarfirmapdf', [FirmaController::class, 'finalizarfirmapdf'])->name('finalizarfirmapdf');
    Route::get('constancia/mostrarConstancia/{constancia}/{rutaCerrar}', [ConstanciaController::class, 'mostrarConstancia'])->name('constancia.mostrarConstancia');
    /**Fin apartado rutas generales */

    /**Inicio */
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home/{cp}', [HomeController::class, 'cuenta'])->name('cuenta');
    Route::get('/cphome', [CuentaPublicaHomeController::class, 'index'])->name('cphome');
    /**Fin apartado inicio */

    /**Administracion */
    Route::get('/administracion', [AdministracionController::class, 'index'])->name('administracion.index');
    Route::resource('user', UsersController::class);
    Route::resource('permiso', PermisoController::class);
    Route::resource('rol', RolController::class);
    Route::resource('acceso', AccesoController::class);
    Route::post('setPermission', [AccesoController::class, 'setPermission'])->name('setPermission');
    Route::resource('asignacionunidadadministrativa', AsignacionUnidadAdministrativaController::class, ['parameters' => ['asignacionunidadadministrativa' => 'user']]);
    Route::resource('asignacionunidadadministrativa2022', AsignacionUnidadAdministrativa2022Controller::class, ['parameters' => ['asignacionunidadadministrativa2022' => 'user']]);
    Route::resource('asignacionunidadadministrativa2023', AsignacionUnidadAdministrativa2023Controller::class, ['parameters' => ['asignacionunidadadministrativa2023' => 'user']]);
    /**Fin apartado administración */

    /**Auditorias - Registro */
    /**     2022        */
     Route::resource('seguimientoauditoria', SeguimientoAuditoriaController::class, ['parameters' => ['seguimientoauditoria' => 'auditoria']]);
     Route::get('/seguimientoauditoria/acciones/consulta/{auditoria}', [SeguimientoAuditoriaController::class, 'accionesConsulta'])->name('seguimientoauditoria.accionesconsulta');
     Route::get('/seguimientoauditoria/acciones/{auditoria}', [SeguimientoAuditoriaController::class, 'auditoriaAcciones'])->name('seguimientoauditoria.acciones');
     Route::get('/seguimientoauditoria/{auditoria}', [SeguimientoAuditoriaController::class, 'concluir'])->name('seguimientoauditoria.concluir');
     Route::resource('seguimientoauditoriaacciones', AccionesController::class, ['parameters' => ['seguimientoauditoriaacciones' => 'accion']]);
     Route::get('/seguimientoauditoria/acciones/accion/{accion}', [AccionesController::class, 'accion'])->name('seguimientoauditoriaacciones.accion');
     Route::resource('seguimientoauditoriaaccionrevision01', SeguimientoAuditoriaAccionRevision01Controller::class, ['parameters' => ['seguimientoauditoriaaccionrevision01' => 'accion']]);
     Route::resource('seguimientoauditoriaaccionrevision', SeguimientoAuditoriaAccionRevisionController::class, ['parameters' => ['seguimientoauditoriaaccionrevision' => 'accion']]);
     Route::resource('seguimientoauditoriarevision01', SeguimientoAuditoriaRevision01Controller::class, ['parameters' => ['seguimientoauditoriarevision01' => 'auditoria']]);
     Route::resource('seguimientoauditoriarevision', SeguimientoAuditoriaRevisionController::class, ['parameters' => ['seguimientoauditoriarevision' => 'auditoria']]);
     Route::resource('seguimientoauditoriavalidacion', SeguimientoAuditoriaValidacionController::class, ['parameters' => ['seguimientoauditoriavalidacion' => 'auditoria']]);
     Route::resource('seguimientoauditoriaautorizacion', SeguimientoAuditoriaAutorizacionController::class, ['parameters' => ['seguimientoauditoriaautorizacion' => 'auditoria']]);
    /**     2023        */
    Route::resource('seguimientoauditoriacp', SeguimientoAuditoria2023Controller::class, ['parameters' => ['seguimientoauditoriacp' => 'auditoria']]);
    Route::resource('seguimientoauditoriacprevision01', SeguimientoAuditoria2023Revision01Controller::class, ['parameters' => ['seguimientoauditoriacprevision01' => 'auditoria']]);
    Route::resource('seguimientoauditoriacprevision', SeguimientoAuditoria2023RevisionController::class, ['parameters' => ['seguimientoauditoriacprevision' => 'auditoria']]);
    Route::resource('seguimientoauditoriacpvalidacion', SeguimientoAuditoria2023ValidacionController::class, ['parameters' => ['seguimientoauditoriacpvalidacion' => 'auditoria']]);
    Route::resource('seguimientoauditoriacpautorizacion', SeguimientoAuditoria2023AutorizacionController::class, ['parameters' => ['seguimientoauditoriacpautorizacion' => 'auditoria']]);
    /**Fin apartado Auditorias - Registro*/

    /**Asiganciones - Direccion*/
    Route::resource('asignaciondireccion', AsignacionDireccionController::class, ['parameters' => ['asignaciondireccion' => 'auditoria']]);
    Route::get('/asignaciondireccion/acciones/consulta/{auditoria}', [AsignacionDireccionController::class, 'accionesConsulta'])->name('asignaciondireccion.accionesconsulta');
    Route::get('/asignaciondireccion/reasignacion/{auditoria}', [AsignacionDireccionController::class, 'reasignar'])->name('asignaciondireccion.reasignar');
    Route::post('getDirector', [AsignacionDireccionController::class, 'getDirector'])->name('getDirector');
    /**Fin del apartado de la direccion */

    /**Asignacion - Departamento y Staff*/
    /**     Departameto         */
    Route::resource('asignaciondepartamento', AsignacionDepartamentoController::class, ['parameters' => ['asignaciondepartamento' => 'auditoria']]);
    Route::get('/asignaciondepartamento/acciones/consulta/{auditoria}', [AsignacionDepartamentoController::class, 'accionesConsulta'])->name('asignaciondepartamento.accionesconsulta');
    Route::post('getJefeDepartamento', [AsignacionDepartamentoController::class, 'getJefeDepartamento'])->name('getJefeDepartamento');
    Route::get('/asignaciondepartamento/reasignacion/{accion}', [AsignacionDepartamentoController::class, 'reasignar'])->name('asignaciondepartamento.reasignar');
    Route::get('/asignacionaccion/{accion}/{movimiento?}', [AsignacionAccionController::class, 'accionesConsulta'])->name('asignacion.accion');
    Route::post('getUnidadAdministrativa', [AsignacionUnidadAdministrativaController::class, 'getUnidadAdministrativa'])->name('getUnidadAdministrativa');
    Route::resource('asignaciondepartamentoencargado', AsignacionDepartamentoEncargadoController::class, ['parameters' => ['asignaciondepartamentoencargado' => 'auditoria']]);
    /**     Staff               */
    Route::resource('asignacionstaff', AsignacionStaffJuridicoController::class, ['parameters' => ['asignacionstaff' => 'auditoria']]);
    Route::get('asignacionstaff/reasignacion/{auditoria}', [AsignacionStaffJuridicoController::class, 'reasignar'])->name('asignacionstaff.reasignar');
    Route::post('asignacionstaff/getStaff', [AsignacionStaffJuridicoController::class, 'getStaff'])->name('asignacionstaff.getStaff');
    Route::get('/asignacionstaff/consultar/{auditoria}', [AsignacionStaffJuridicoController::class, 'consultar'])->name('asignacionstaff.consultar');
    /**Fin apartado de Asignacion - Departamento */

    /**Asignacion - Lider y Analista */
    Route::resource('asignacionlideranalista', AsignacionLiderAnalistaController::class, ['parameters' => ['asignacionlideranalista' => 'auditoria']]);
    Route::post('getLider', [AsignacionLiderAnalistaController::class, 'getLider'])->name('getLider');
    Route::post('getAnalista', [AsignacionLiderAnalistaController::class, 'getAnalista'])->name('getAnalista');
    Route::get('/asignacionlider/reasignacion/{auditoria}', [AsignacionLiderAnalistaController::class, 'reasignarlider'])->name('asignacionlideranalista.reasignarlider');
    Route::get('/asignacionanalista/reasignacion/{auditoria}', [AsignacionLiderAnalistaController::class, 'reasignaranalista'])->name('asignacionlideranalista.reasignaranalista');
    Route::get('/asignacionlideranalista/acciones/consulta/{auditoria}', [AsignacionLiderAnalistaController::class, 'accionesConsulta'])->name('asignacionlideranalista.accionesconsulta');
    Route::get('/asignacionlideranalista/consulta/{auditoria}', [AsignacionLiderAnalistaController::class, 'consulta'])->name('asignacionlideranalista.consulta');
    /**Fin del apartado de Asignacion - Lider y Analista */

    /**Seguimiento - Auditorias */
    Route::resource('auditoriaseguimiento', AuditoriaSeguimientoController::class, ['parameters' => ['auditoriaseguimiento' => 'auditoria']]);
    Route::get('/auditoriaseguimiento/acciones/consulta/{auditoria}', [AuditoriaSeguimientoController::class, 'accionesConsulta'])->name('auditoriaseguimiento.accionesconsulta');
    Route::get('/auditoriaseguimiento/seleccionar/{auditoria}', [AuditoriaSeguimientoController::class, 'seleccionarauditoria'])->name('seleccionarauditoria.auditoria');
    Route::resource('auditoriaseguimientoacciones', AuditoriaSeguimientoAccionesController::class, ['parameters' => ['auditoriaseguimientoacciones' => 'auditoria']]);
    Route::resource('auditoriaconsultaacciones', AuditoriaConsultaAccionesController::class, ['parameters' => ['auditoriaconsultaacciones' => 'accion']]);
     /**    2023        */
     Route::resource('agregaracciones', AgregarAccionesController::class, ['parameters' => ['agregaracciones' => 'accion']]);
     Route::get('/agregaracciones/acciones/{accion}', [AgregarAccionesController::class, 'accion'])->name('agregaracciones.accion');
     Route::get('/agregaracciones/concluircp/{auditoria}', [AgregarAccionesController::class, 'concluir'])->name('agregaracciones.concluir');
     Route::get('/AgregarAccionesController/acciones/consulta/{accion}', [AgregarAccionesController::class, 'accionesConsulta'])->name('AgregarAccionesController.accionesconsulta');
     Route::resource('agregaraccionesrevision01', AgregarAccionesRevision01Controller::class, ['parameters' => ['agregaraccionesrevision01' => 'accion']]);
     Route::resource('agregaraccionesrevision', AgregarAccionesRevisionController::class, ['parameters' => ['agregaraccionesrevision' => 'accion']]);
     Route::resource('agregaraccionesvalidacion', AgregarAccionesValidacionController::class, ['parameters' => ['agregaraccionesvalidacion' => 'accion']]);
     Route::resource('agregaraccionesautorizacion', AgregarAccionesAutorizacionController::class, ['parameters' => ['agregaraccionesautorizacion' => 'accion']]);
     Route::resource('tipologiaacciones', TipologiaAccionesController::class);
     Route::get('/tipologiaacciones/create/{auditoria}', [TipologiaAccionesController::class,'create'])->name('tipologiaacciones.create') ;
    //  Route::resource('revisionesrecomendaciones', RevisionesRecomendacionesController::class, ['parameters' => ['revisionesrecomendaciones' => 'comentario']]);
    /**Fin del apartado de Auditorias */

    //Inicio Archivo Transferencia
    Route::resource('inicioarchivotransferencia', InicioArchivoTransferenciaController::class, ['parameters' => ['inicioarchivotransferencia' => 'auditoria']]);
    Route::resource('turnoarchivotransferencia', TurnoArchivoTransferenciaController::class, ['parameters' => ['turnoarchivotransferencia' => 'auditoria']]);
    Route::resource('turnoarchivotransferenciaenvio', TurnoArchivoTransferenciaEnvioController::class, ['parameters' => ['turnoarchivotransferenciaenvio' => 'auditoria']]);
    Route::resource('turnoarchivotransferenciarevision', TurnoArchivoTransferenciaRevisionController::class, ['parameters' => ['turnoarchivotransferenciarevision' => 'auditoria']]);
    Route::resource('turnoarchivotransferenciavalidacion', TurnoArchivoTransferenciaValidacionController::class, ['parameters' => ['turnoarchivotransferenciavalidacion' => 'auditoria']]);
    Route::resource('turnoarchivotransferenciaautorizacion', TurnoArchivoTransferenciaAutorizacionController::class, ['parameters' => ['turnoarchivotransferenciaautorizacion' => 'auditoria']]);
    

    /**Seguimiento - Auditorias - Radicacion */
    Route::resource('radicacion', RadicacionController::class);
    Route::resource('comparecenciaagenda', ComparecenciaAgendaController::class, ['parameters' => ['comparecenciaagenda' => 'comparecencia']]);
    Route::post('getAgendaComparecencias', [AjaxController::class, 'getAgendaComparecencias'])->name('getAgendaComparecencias');
    Route::get('auditoriaradicacion/{auditoria}', [RadicacionController::class, 'auditoria'])->name('radicacion.auditoria');
    Route::get('radicacionpdf/{radicacionpdf}', [RadicacionController::class, 'radicacionpdf'])->name('radicacion.radicacionpdf'); //RUTA PARA GENERAR PDF
    Route::get('/radicacion/word/{radicacion}', [RadicacionController::class, 'radicacionWord'])->name('radicacion.word'); //RUTA ARCHIVO WORD AR
    Route::get('/radicacion/wordOF/{radicacion}', [RadicacionController::class, 'radicacionWordOF'])->name('radicacion.wordOF'); //RUTA PARA ARCHIVO WORD OF.AR
    Route::get('radicacionconcluir/{radicacion}', [RadicacionController::class, 'concluir'])->name('radicacion.concluir');
    Route::resource('radicacionenvio', RadicacionEnvioController::class, ['parameters' => ['radicacionenvio' => 'radicacion']]);
    Route::resource('radicacionrevision', RadicacionRevisionController::class, ['parameters' => ['radicacionrevision' => 'radicacion']]);
    Route::resource('radicacionvalidacion', RadicacionValidacionController::class, ['parameters' => ['radicacionvalidacion' => 'radicacion']]);
    Route::resource('radicacionautorizacion', RadicacionAutorizacionController::class, ['parameters' => ['radicacionautorizacion' => 'radicacion']]);
    Route::resource('comparecenciaacuse', ComparecenciaAcusesController::class, ['parameters' => ['comparecenciaacuse' => 'comparecencia']]);
    Route::resource('comparecenciaacusecp', ComparecenciaAcusesCPController::class, ['parameters' => ['comparecenciaacusecp' => 'comparecencia']]);
    Route::get('/radicacionnotificacion/iaar', [RadicacionController::class, 'export'])->name('radicacioniaar.exportar');
    Route::get('/radicacion/ar', [RadicacionController::class, 'exportar_ar'])->name('radicacionar.exportar_ar');
    Route::get('/radicacionnotificacion/aroic', [RadicacionController::class, 'exportOIC'])->name('radicacioniaar.exportaroic');
    /**Fin del apartado de Seguimiento - Auditorias - Radicacion */

    /**Seguimiento - Auditorias - Comparecencia */
    Route::get('/comparecencia/ac', [ComparecenciaController::class, 'export'])->name('comparecencia.exportar');
    Route::resource('comparecencia', ComparecenciaController::class, ['parameters' => ['comparecencia' => 'comparecencia']]);
    Route::get('auditoriacomparecencia/{auditoria}', [ComparecenciaController::class, 'auditoria'])->name('comparecencia.auditoria');
    Route::resource('comparecenciaacta', ComparecenciaActaController::class, ['parameters' => ['comparecenciaacta' => 'comparecencia']]);
    Route::resource('comparecenciaenvio', ComparecenciaEnvioController::class, ['parameters' => ['comparecenciaenvio' => 'comparecencia']]);
    Route::resource('comparecenciacpenvio', ComparecenciaCPEnvioController::class, ['parameters' => ['comparecenciacpenvio' => 'comparecencia']]);

    Route::resource('comparecenciarevision', ComparecenciaRevisionController::class, ['parameters' => ['comparecenciarevision' => 'comparecencia']]);
    Route::resource('comparecenciavalidacion', ComparecenciaValidacionController::class, ['parameters' => ['comparecenciavalidacion' => 'comparecencia']]);
    
    //Route::get('comparecenciaconcluir/{comparecencia}', [ComparecenciaController::class, 'concluir'])->name('comparecencia.concluir');
    /**Fin del apartado de Seguimiento - Auditorias - Comparecencia*/

    /**Seguimiento - Auditorias - Acuerdo de conclusion */
    Route::get('/acuerdoconclusion/ac', [AcuerdoConclusionController::class, 'export'])->name('acuerdoconclusionac.exportar');
    Route::get('/acuerdoconclusion/ofac', [AcuerdoConclusionController::class, 'exportOFAC'])->name('acuerdoconclusionofac.exportar');
    Route::resource('acuerdoconclusion', AcuerdoConclusionController::class, ['parameters' => ['acuerdoconclusion' => 'auditoria']]);
    Route::resource('acuerdoconclusioncp', AcuerdoConclusionCPController::class, ['parameters' => ['acuerdoconclusioncp' => 'auditoria']]);
    Route::get('acuerdoconclusionpliegos', [AcuerdoConclusionController::class, 'acuerdoconclusionpliegos'])->name('acuerdoconclusionpliegos.create');
    Route::get('acuerdoconclusioncppliegos', [AcuerdoConclusionCPController::class, 'acuerdoconclusionpliegos'])->name('acuerdoconclusioncppliegos.create');
    Route::resource('acuerdoconclusionenvio', AcuerdoConclusionEnvioController::class, ['parameters' => ['acuerdoconclusionenvio' => 'auditoria']]);
    Route::resource('acuerdoconclusionenviopliegos', AcuerdoConclusionEnvioController::class, ['parameters' => ['acuerdoconclusionenviopliegos' => 'auditoria']]);    
    Route::resource('acuerdoconclusionrevision', AcuerdoConclusionRevisionController::class, ['parameters' => ['acuerdoconclusionrevision' => 'auditoria']]);
    Route::resource('acuerdoconclusionenviocp', AcuerdoConclusionCPEnvioController::class, ['parameters' => ['acuerdoconclusionenviocp' => 'auditoria']]);
    Route::resource('acuerdoconclusionenviocppliegos', AcuerdoConclusionCPEnvioController::class, ['parameters' => ['acuerdoconclusionenviocppliegos' => 'auditoria']]);
    Route::resource('acuerdoconclusionvalidacion', AcuerdoConclusionValidacionController::class, ['parameters' => ['acuerdoconclusionvalidacion' => 'auditoria']]);
    Route::resource('acuerdoconclusionautorizacion', AcuerdoConclusionAutorizacionController::class, ['parameters' => ['acuerdoconclusionautorizacion' => 'auditoria']]);
    Route::resource('acuerdoconclusionacuse', AcuerdoConclusionAcuseController::class, ['parameters' => ['acuerdoconclusionacuse' => 'acuerdoconclusion']]);
    // Route::resource('acuerdoconclusionacuse', AcuerdoConclusionAcuseController::class, ['parameters' => ['acuerdoconclusionacuse' => 'acuerdoconclusion']]);
    Route::resource('acuerdoconclusionacusecp', AcuerdoConclusionAcuseCPController::class, ['parameters' => ['acuerdoconclusionacusecp' => 'acuerdoconclusion']]);
    /**Fin del apartado de Seguimiento - Auditorias - Acuerdo de conclusion*/

    /**Seguimiento - Auditorias - PRAS  */
    Route::resource('pras', PrasController::class, ['parameters' => ['pras' => 'auditoria']]);
    Route::resource('prasacciones', PrasaccionesController::class, ['parameters' => ['prasacciones' => 'accion']]);
    Route::resource('prasturno', PrasTurnoController::class, ['parameters' => ['prasturno' => 'pras']]);
    Route::resource('prasturnorevision', PrasTurnoRevisionController::class, ['parameters' => ['prasturnorevision' => 'pras']]);
    Route::resource('prasturnovalidacion', PrasTurnoValidacionController::class, ['parameters' => ['prasturnovalidacion' => 'pras']]);
    Route::resource('prasturnoautorizacion', PrasTurnoAutorizacionController::class, ['parameters' => ['prasturnoautorizacion' => 'pras']]);
    Route::resource('prasturnoacuses', PrasTurnoAcusesController::class, ['parameters' => ['prasturnoacuses' => 'pras']]);
    Route::resource('prasseguimiento', PrasSeguimientoController::class, ['parameters' => ['prasseguimiento' => 'pras']]);
    Route::resource('prasmedida', PrasMedidaController::class, ['parameters' => ['prasmedida' => 'pras']]);
    /**Fin del apartado de Seguimiento - Auditorias - PRAS*/

     /**Seguimiento - Auditorias - Recomendaciones  */
     Route::resource('recomendaciones', RecomendacionesController::class, ['parameters' => ['recomendaciones' => 'auditoria']]);
     Route::resource('recomendacionesacciones', RecomendacionesAccionesController::class, ['parameters' => ['recomendacionesacciones' => 'accion']]);
     Route::resource('recomendacionesatencion', RecomendacionesAtencionController::class, ['parameters' => ['recomendacionesatencion' => 'recomendacion']]);
     Route::resource('recomendacionescalificacion', RecomendacionesAtencionCalificacionController::class, ['parameters' => ['recomendacionescalificacion' => 'recomendacion']]);
     Route::resource('recomendacionesdocumentos', RecomendacionesAtencionDocumentosController::class, ['parameters' => ['recomendacionesdocumentos' => 'documento']]);
     Route::resource('recomendacionescontestaciones', RecomendacionesAtencionContestacionController::class, ['parameters' => ['recomendacionescontestaciones' => 'contestacion']]);
     Route::get('recomendacionescontestacionesoficios/{recomendacion}', [RecomendacionesAtencionContestacionController::class, 'oficiosrecomendacion'])->name('recomendacionescontestaciones.oficiosrecomendacion');
     Route::resource('recomendacionesanalisis', RecomendacionesAnalisisController::class, ['parameters' => ['recomendacionesanalisis' => 'recomendacion']]);
     Route::get('recomendacionanexos/{recomendacion}', [RecomendacionesAnexoController::class, 'anexos'])->name('recomendacion.anexos');
     Route::resource('recomendacionesanexos', RecomendacionesAnexoController::class, ['parameters' => ['recomendacionesanexos' => 'anexo']]);
     Route::resource('recomendacionesanalisisenvio', RecomendacionesAnalisisEnvioController::class, ['parameters' => ['recomendacionesanalisisenvio' => 'recomendacion']]);
     Route::resource('recomendacionesrevision01', RecomendacionesRevision01Controller::class, ['parameters' => ['recomendacionesrevision01' => 'recomendacion']]);
     Route::resource('recomendacionesrevision', RecomendacionesRevisionController::class, ['parameters' => ['recomendacionesrevision' => 'recomendacion']]);
     Route::resource('recomendacionesvalidacion', RecomendacionesValidacionController::class, ['parameters' => ['recomendacionesvalidacion' => 'recomendacion']]);
     Route::resource('recomendacionesautorizacion', RecomendacionesAutorizacionController::class, ['parameters' => ['recomendacionesautorizacion' => 'recomendacion']]);
     Route::resource('recomendacionesacuses', RecomendacionesAcusesController::class, ['parameters' => ['recomendacionesacuses' => 'recomendacion']]);
     Route::resource('revisionesrecomendaciones', RevisionesRecomendacionesController::class, ['parameters' => ['revisionesrecomendaciones' => 'comentario']]);
     Route::resource('revisionesrecomendacionesatencion', RevisionesRecomendacionesAtencionController::class, ['parameters' => ['revisionesrecomendacionesatencion' => 'comentario']]);
    /**Fin del apartado de Seguimiento - Auditorias - Recomendaciones*/

     /**Seguimiento - Auditorias - Solicitudes  */
     Route::resource('solicitudesaclaracion', SolicitudesAclaracionController::class, ['parameters' => ['solicitudesaclaracion' => 'auditoria']]);
     Route::resource('solicitudesaclaracionacciones', SolicitudesAclaracionAccionesController::class, ['parameters' => ['solicitudesaclaracionacciones' => 'accion']]);
     Route::resource('solicitudesaclaracionatencion', SolicitudesAclaracionAtencionController::class, ['parameters' => ['solicitudesaclaracionatencion' => 'solicitud']]);
     Route::resource('solicitudesaclaracioncontestacion', SolicitudesAclaracionContestacionController::class, ['parameters' => ['solicitudesaclaracioncontestacion' => 'contestacion']]);
     Route::get('solicitudesaclaracioncontestacionoficios/{solicitud}', [SolicitudesAclaracionContestacionController::class, 'oficiossolicitudes'])->name('solicitudescontestaciones.oficiossolicitud');
     Route::resource('solicitudesaclaraciondocumentos', SolicitudesAclaracionDocumentosController::class, ['parameters' => ['solicitudesaclaraciondocumentos' => 'documento']]);
     Route::resource('solicitudesaclaracionanalisis', SolicitudesAclaracionAnalisisController::class, ['parameters' => ['solicitudesaclaracionanalisis' => 'solicitud']]);
     Route::resource('solicitudesaclanalisisenvio', SolicitudesAclaracionAnalisisEnvioController::class, ['parameters' => ['solicitudesaclanalisisenvio' => 'solicitud']]);
     Route::resource('solicitudesaclaracionrevision01', SolicitudesAclaracionRevision01Controller::class, ['parameters' => ['solicitudesaclaracionrevision01' => 'solicitud']]);
     Route::resource('solicitudesaclaracionrevision', SolicitudesAclaracionRevisionController::class, ['parameters' => ['solicitudesaclaracionrevision' => 'solicitud']]);
     Route::resource('solicitudesaclaracionvalidacion', SolicitudesAclaracionValidacionController::class, ['parameters' => ['solicitudesaclaracionvalidacion' => 'solicitud']]);
     Route::resource('solicitudesaclaracionautorizacion', SolicitudesAclaracionAutorizacionController::class, ['parameters' => ['solicitudesaclaracionautorizacion' => 'solicitud']]);
     Route::get('solicitudesaclanexos/{solicitud}', [SolicitudesAclaracionAnexoController::class, 'anexos'])->name('solicitudes.anexos');
     Route::resource('solicitudesaclaracionanexos', SolicitudesAclaracionAnexoController::class, ['parameters' => ['solicitudesaclaracionanexos' => 'anexo']]);
     Route::resource('revisionessolicitudes', RevisionesSolicitudesController::class, ['parameters' => ['revisionessolicitudes' => 'comentario']]);
     Route::resource('revisionessolicitudesatencion', RevisionesSolicitudesAtencionController::class, ['parameters' => ['revisionessolicitudesatencion' => 'comentario']]);
    /**Fin del apartado de Seguimiento - Auditorias - Solicitudes*/

    /**Seguimiento - Auditorias - Pliegos  */
    Route::resource('pliegosobservacion', PliegosObservacionController::class, ['parameters' => ['pliegosobservacion' => 'auditoria']]);
    Route::resource('pliegosobservacionacciones', PliegosObservacionAccionesController::class, ['parameters' => ['pliegosobservacionacciones' => 'accion']]);
    Route::resource('pliegosobservacionatencion', PliegosObservacionAtencionController::class, ['parameters' => ['pliegosobservacionatencion' => 'pliegosobservacionatencion']]);
    Route::resource('pliegosobservacionatencioncontestacion', PliegosObservacionAtencionContestacionController::class, ['parameters' => ['pliegosobservacionatencioncontestacion' => 'contestacion']]);
    Route::resource('pliegosobservaciondocumentos', PliegosObservacionAtencionDocumentosController::class, ['parameters' => ['pliegosobservaciondocumentos' => 'documento']]);
    Route::resource('pliegosobservacionanalisis', PliegosObservacionAtencionAnalisisController::class, ['parameters' => ['pliegosobservacionanalisis' => 'pliegosobservacion']]);
    Route::get('pliegosobsanexos/{pliegosobservacion}', [PliegosObservacionAnexoController::class, 'anexos'])->name('pliegos.anexos');
    Route::resource('pliegosobservacionanexos', PliegosObservacionAnexoController::class, ['parameters' => ['pliegosobservacionanexos' => 'anexo']]);
    Route::resource('pliegosobservacionanalisisenvio', PliegosObservacionAnalisisEnvioController::class, ['parameters' => ['pliegosobservacionanalisisenvio' => 'pliegosobservacion']]);
    Route::resource('pliegosobservacionrevision01', PliegosObservacionRevision01Controller::class, ['parameters' => ['pliegosobservacionrevision01' => 'pliegosobservacion']]);
    Route::resource('pliegosobservacionrevision', PliegosObservacionRevisionController::class, ['parameters' => ['pliegosobservacionrevision' => 'pliegosobservacion']]);
    Route::resource('pliegosobservacionvalidacion', PliegosObservacionValidacionController::class, ['parameters' => ['pliegosobservacionvalidacion' => 'pliegosobservacion']]);
    Route::resource('pliegosobservacionautorizacion', PliegosObservacionAutorizacionController::class, ['parameters' => ['pliegosobservacionautorizacion' => 'pliegosobservacion']]);
    Route::get('pliegosobservacioncontestacionoficios/{pliegosobservacion}', [PliegosObservacionAtencionContestacionController::class, 'oficiospliegosobservacion'])->name('pliegosobservacioncontestacion.oficiospliegosobservacion');
    Route::resource('revisionespliegos', RevisionesPliegosController::class, ['parameters' => ['revisionespliegos' => 'comentario']]);
    Route::resource('revisionespliegosatencion', RevisionesPliegosAtencionController::class, ['parameters' => ['revisionespliegosatencion' => 'comentario']]);
    /**Fin del apartado de Seguimiento - Auditorias - Pliegos*/

    /**Seguimiento - Auditorias - Cedulas*/
    /**     Cedula Inicial          */
    Route::resource('cedulainicial', CedulaInicialController::class, ['parameters' => ['cedulainicial' => 'auditoria']]);
    /**Cedula General Seguimiento       */
    Route::resource('cedulainicialprimera', CedulaInicialPrimeraEtapaController::class, ['parameters' => ['cedulainicialprimera' => 'auditoria']]);
    Route::resource('cedulainicialprimeraanalista', CedulaInicialAprobarAnalistaController::class, ['parameters' => ['cedulainicialprimeraanalista' => 'cedula']]);
    Route::resource('cedulainicialprimerarevision01', CedulaInicialRevision01Controller::class, ['parameters' => ['cedulainicialprimerarevision01' => 'cedula']]);
    Route::resource('cedulainicialprimerarevision', CedulaInicialRevisionController::class, ['parameters' => ['cedulainicialprimerarevision' => 'cedula']]);
    Route::resource('cedulainicialprimeravalidacion', CedulaInicialValidacionController::class, ['parameters' => ['cedulainicialprimeravalidacion' => 'cedula']]);
    Route::resource('cedulainicialprimeraautorizacion', CedulaInicialAutorizacionController::class, ['parameters' => ['cedulainicialprimeraautorizacion' => 'cedula']]);

    Route::resource('agregarcedulainicial', AgregarCedulaInicialController::class, ['parameters' => ['agregracedulainicial' => 'cedula']]);
    Route::resource('cedulasenvio', CedulasEnvioController::class, ['parameters' => ['cedulasenvio' => 'auditoria']]);
    Route::resource('cedulaanaliticadesempenorevision', CedulaAnaliticaDesempenoRevisionController::class, ['parameters' => ['cedulaanaliticadesempenorevision' => 'auditoria']]);
    

    /**     Cedula General Recomendaciones      */
    Route::resource('cedulageneralrecomendacion', CedulaGeneralRecomendacionesController::class, ['parameters' => ['cedulageneralrecomendacion' => 'auditoria']]);
    Route::resource('cedgralrecomendacionanalista', CedulaGeneralRecomendacionesAnalistaController::class, ['parameters' => ['cedgralrecomendacionanalista' => 'cedula']]);
    Route::resource('cedgralrecomendacionrevision01', CedulaGeneralRecomendacionesRevision01Controller::class, ['parameters' => ['cedgralrecomendacionrevision01' => 'cedula']]);
    Route::resource('cedgralrecomendacionrevision', CedulaGeneralRecomendacionesRevisionController::class, ['parameters' => ['cedgralrecomendacionrevision' => 'cedula']]);
    Route::resource('cedgralrecomendacionvalidacion', CedulaGeneralRecomendacionesValidacionController::class, ['parameters' => ['cedgralrecomendacionvalidacion' => 'cedula']]);
    Route::resource('cedgralrecomendacionautorizacion', CedulaGeneralRecomendacionesAutorizacionController::class, ['parameters' => ['cedgralrecomendacionautorizacion' => 'cedula']]);
    /**     Cedula General PRAS         */
    Route::resource('cedulageneralpras', CedulaGeneralPRASController::class, ['parameters' => ['cedulageneralpras' => 'auditoria']]);
    Route::resource('cedulageneralprasanalista', CedulaGeneralPrasAnalistaController::class, ['parameters' => ['cedulageneralprasanalista' => 'cedula']]);
    Route::resource('cedulageneralpraslider', CedulaGeneralPRASLiderController::class, ['parameters' => ['cedulageneralpraslider' => 'cedula']]);
    Route::resource('cedulageneralprasrevision01', CedulaGeneralPRASRevision01Controller::class, ['parameters' => ['cedulageneralprasrevision01' => 'cedula']]);
    Route::resource('cedulageneralprasrevision', CedulaGeneralPRASRevisionController::class, ['parameters' => ['cedulageneralprasrevision' => 'cedula']]);
    Route::resource('cedulageneralprasvalidacion', CedulaGeneralPRASValidacionController::class, ['parameters' => ['cedulageneralprasvalidacion' => 'cedula']]);
    Route::resource('cedulageneralprasautorizacion', CedulaGeneralPRASAutorizacionController::class, ['parameters' => ['cedulageneralprasautorizacion' => 'cedula']]);
    /**     Cedula Analitica        */
    Route::resource('cedulaanalitica', CedulaAnaliticaController::class, ['parameters' => ['cedulaanalitica' => 'auditoria']]);
    Route::resource('cedulaanaliticaanalista', CedulaAnaliticaAnalistaController::class, ['parameters' => ['cedulaanaliticaanalista' => 'cedula']]);
    Route::resource('cedulaanaliticarevision01', CedulaAnaliticaRevision01Controller::class, ['parameters' => ['cedulaanaliticarevision01' => 'cedula']]);
    Route::resource('cedulaanaliticarevision', CedulaAnaliticaRevisionController::class, ['parameters' => ['cedulaanaliticarevision' => 'cedula']]);
    Route::resource('cedulaanaliticavalidacion', CedulaAnaliticaValidacionController::class, ['parameters' => ['cedulaanaliticavalidacion' => 'cedula']]);
    Route::resource('cedulaanaliticaautorizacion', CedulaAnaliticaAutorizacionController::class, ['parameters' => ['cedulaanaliticaautorizacion' => 'cedula']]);
    /**     Cedula Analitica de Desempeño       */
    Route::resource('cedulaanaliticadesemp', CedulaAnaliticaDesempenoController::class, ['parameters' => ['cedulaanaliticadesemp' => 'auditoria']]);
    Route::resource('cedanadesempanalista', CedulaAnaliticaDesempenoAnalistaController::class, ['parameters' => ['cedanadesempanalista' => 'cedula']]);
    Route::resource('cedanadesemprevision01', CedulaAnaliticaDesempenoRevision01Controller::class, ['parameters' => ['cedanadesemprevision01' => 'cedula']]);
    // Route::resource('cedanadesemprevision', CedulaAnaliticaDesempenoRevisionController::class, ['parameters' => ['cedanadesemprevision' => 'cedula']]);
    Route::resource('cedanadesempvalidacion', CedulaAnaliticaDesempenoValidacionController::class, ['parameters' => ['cedanadesempvalidacion' => 'cedula']]);
    Route::resource('cedanadesempautorizacion', CedulaAnaliticaDesempenoAutorizacionController::class, ['parameters' => ['cedanadesempautorizacion' => 'cedula']]);
    
    
    

    /**Fin del apartado de Seguimiento - Auditorias - Cedulas*/

    /** Seguimiento - Auditorias - Informe*/
    Route::resource('informeprimeraetapa', InformePrimeraEtapaController::class, ['parameters' => ['informeprimeraetapa' => 'auditoria']]);
    Route::get('informepliegos', [InformePrimeraEtapaController::class, 'informepliegos'])->name('informepliegos.create');
    //Route::get('/informeprimeraetapa/exportar', [InformePrimeraEtapaController::class, 'export'])->name('informeprimeraetapa.exportar');
    Route::get('/informeprimeraetapais', [InformePrimeraEtapaController::class, 'export'])->name('informeprimeraetapa.exportar');
    Route::get('/informeprimeraetapaofis', [InformePrimeraEtapaController::class, 'exportOFIS'])->name('informeprimeraetapaofis.exportar');

    Route::resource('informeprimeraetapaenvio', InformePrimeraEtapaEnvioController::class, ['parameters' => ['informeprimeraetapaenvio' => 'auditoria']]);
    Route::resource('informeprimeraetapavalidacion', InformePrimeraEtapaValidacionController::class, ['parameters' => ['informeprimeraetapavalidacion' => 'auditoria']]);
    Route::resource('informeprimeraetapaautorizacion', InformePrimeraEtapaAutorizacionController::class, ['parameters' => ['informeprimeraetapaautorizacion' => 'auditoria']]);
    /**Fin del apartado de Seguimiento - Auditorias - Informe*/

    /** Seguimiento - Auditorias - TurnoUI */
    Route::get('/turnoui/oui', [TurnoUIController::class, 'export'])->name('turnoui.exportar');
    Route::resource('turnoui', TurnoUIController::class, ['parameters' => ['turnoui' => 'auditoria']]);
    Route::resource('turnouienvio', TurnoUIEnvioController::class, ['parameters' => ['turnouienvio' => 'auditoria']]);
    Route::resource('turnouirevision', TurnoUIRevisionController::class, ['parameters' => ['turnouirevision' => 'auditoria']]);
    Route::resource('turnouivalidacion', TurnoUIValidacionController::class, ['parameters' => ['turnouivalidacion' => 'auditoria']]);
    Route::resource('turnouiautorizacion', TurnoUIAutorizacionController::class, ['parameters' => ['turnouiautorizacion' => 'auditoria']]);
    /**Fin del apartado de Seguimiento - Auditorias - TurnoUI*/

    /** Seguimiento - Auditorias - TurnoOIC */
    Route::get('/turnooic/oroic', [TurnoOICController::class, 'export'])->name('turnooic.exportar');
    Route::resource('turnooic', TurnoOICController::class, ['parameters' => ['turnooic' => 'auditoria']]);
    Route::resource('turnooicenvio', TurnoOICEnvioController::class, ['parameters' => ['turnooicenvio' => 'auditoria']]);
    Route::resource('turnooicrevision', TurnoOICRevisionController::class, ['parameters' => ['turnooicrevision' => 'auditoria']]);
    Route::resource('turnooicvalidacion', TurnoOICValidacionController::class, ['parameters' => ['turnooicvalidacion' => 'auditoria']]);
    Route::resource('turnooicautorizacion', TurnoOICAutorizacionController::class, ['parameters' => ['turnooicautorizacion' => 'auditoria']]);
    /**Fin del apartado de Seguimiento - Auditorias - TurnoOIC*/

    /** Seguimiento - Auditorias - TurnoArchivo */
    Route::resource('turnoarchivo', TurnoArchivoController::class, ['parameters' => ['turnoarchivo' => 'auditoria']]);
    Route::resource('turnoarchivoenvio', TurnoArchivoEnvioController::class, ['parameters' => ['turnoarchivoenvio' => 'auditoria']]);
    Route::resource('turnoarchivorevision01', TurnoArchivoRevision01Controller::class, ['parameters' => ['turnoarchivorevision01' => 'auditoria']]);
    Route::resource('turnoarchivorevision', TurnoArchivoRevisionController::class, ['parameters' => ['turnoarchivorevision' => 'auditoria']]);
    Route::resource('turnoarchivovalidacion', TurnoArchivoValidacionController::class, ['parameters' => ['turnoarchivovalidacion' => 'auditoria']]);
    Route::resource('turnoarchivoautorizacion', TurnoArchivoAutortizacionController::class, ['parameters' => ['turnoarchivoautorizacion' => 'auditoria']]);
    /**Fin del apartado de Seguimiento - Auditorias - TurnoArchivo*/

    /** Seguimiento - Auditorias - PAC */
     Route::resource('pac', PacController::class);
     Route::resource('pacauditoria', PacAuditoriaController::class);
     /**    Analista        */
     Route::get('/pac/mot/{id}', [PacController::class, 'mot'])->name('pac.mot');
     Route::get('/pac/fc/{id}', [PacController::class, 'fc'])->name('pac.fc');
     Route::get('/pac/fccd/{id}', [PacController::class, 'fccd'])->name('pac.fccd');
     Route::get('/pacauditoria/mot/{id}', [PacAuditoriaController::class, 'mot'])->name('pacauditoria.mot');
     Route::get('/pacauditoria/fc/{id}', [PacAuditoriaController::class, 'fc'])->name('pacauditoria.fc');
     Route::get('/pacauditoria/fccd/{id}', [PacAuditoriaController::class, 'fccd'])->name('pacauditoria.fccd');
     /**    Lider       */
     Route::get('/pac/ar/{id}', [PacController::class, 'ar'])->name('pac.ar');
     Route::get('/pac/ofiaar/{id}', [PacController::class, 'ofiaar'])->name('pac.ofiaar');
     Route::get('/pac/ofaroics/{id}', [PacController::class, 'ofaroics'])->name('pac.ofaroics');
     Route::get('/pac/ac/{id}', [PacController::class, 'ac'])->name('pac.ac');
     Route::get('/pac/ofai/{id}', [PacController::class, 'ofai'])->name('pac.ofai');
     Route::get('/pac/ofroics/{id}', [PacController::class, 'ofroics'])->name('pac.ofroics');
     Route::get('/pac/ofprasoics/{id}', [PacController::class, 'ofprasoics'])->name('pac.ofprasoics');
     Route::get('/pac/ofsc/{id}', [PacController::class, 'ofsc'])->name('pac.ofsc');
     Route::get('/pac/ofuaj/{id}', [PacController::class, 'ofuaj'])->name('pac.ofuaj');
     Route::get('/pac/ac10/{id}', [PacController::class, 'ac10'])->name('pac.ac10');
     Route::get('/pac/acral/{id}', [PacController::class, 'acral'])->name('pac.acral');
     Route::get('/pac/ofac/{id}', [PacController::class, 'ofac'])->name('pac.ofac');
     Route::get('/pac/anv/{id}', [PacController::class, 'anv'])->name('pac.anv');
     Route::get('/pac/ofanv/{id}', [PacController::class, 'ofanv'])->name('pac.ofanv');
     Route::get('/pac/av/{id}', [PacController::class, 'av'])->name('pac.av');
     Route::get('/pac/oi/{id}', [PacController::class, 'oi'])->name('pac.oi');
     Route::get('/pac/ofriii/{id}', [PacController::class, 'ofriii'])->name('pac.ofriii');
     Route::get('/pac/ai/{id}', [PacController::class, 'ai'])->name('pac.ai');
     Route::get('/pacauditoria/ofiaar/{id}', [PacAuditoriaController::class, 'ofiaar'])->name('pacauditoria.ofiaar');
     Route::get('/pacauditoria/ofaroics/{id}', [PacAuditoriaController::class, 'ofaroics'])->name('pacauditoria.ofaroics');
     Route::get('/pacauditoria/ac/{id}', [PacAuditoriaController::class, 'ac'])->name('pacauditoria.ac');
     /**    Jefe        */
     Route::get('/pac/ofis/{id}', [PacController::class, 'ofis'])->name('pac.ofis');
     Route::get('/pac/is/{id}', [PacController::class, 'is'])->name('pac.is');
     Route::get('/pac/is2/{id}', [PacController::class, 'is2'])->name('pac.is2');
     /**    Direccion       */
     Route::get('/pac/mda/{id}', [PacController::class, 'mda'])->name('pac.mda');
     Route::get('/pac/mdi/{id}', [PacController::class, 'mdi'])->name('pac.mdi');
     /**    Titular         */
     Route::get('/pac/aa/{id}', [PacController::class, 'aa'])->name('pac.aa');
    /**Fin del apartado de Seguimiento - Auditorias - PAC*/

    /** Seguimiento - Auditorias - Reportes */
    Route::resource('reportesseg', ReportesSeguimientoController::class);
    Route::resource('reportesregistrosauditorias', ReportesRegistrosAuditoriasController::class);
    Route::get('/reportesseg/excel', [ReportesSeguimientoController::class, 'export'])->name('reporteseguimiento.exportar');
    /**Fin del apartado de Seguimiento - Auditorias - Reportes*/

    /*** Seguimiento - Auditorias - folios */
    Route::resource('folioscrr', FolioCRRController::class);
    Route::resource('remitentes', FolioRemitentesController::class);
    Route::resource('foliosanexos', FolioAnexosController::class);
    Route::get('foliosanexos/{folio}/edit', [FolioAnexosController::class, 'edit'])->name('foliosanexos.edit');
    Route::get('/remitentes/create/{folioscrr}', [FolioRemitentesController::class, 'create'])->name('remitentes.create');
    Route::get('/remitentes/edit/{remitente}', [FolioRemitentesController::class, 'edit'])->name('remitentes.edit');

    



    /**Fin del apartado de Seguimiento - Auditorias - folios*/




   });
