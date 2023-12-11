@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('home') }}
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="mt-4 mb-5 col-12">
        <div class="flex-row row flex-center">
            @canany(['user.index'])
            <div class="col-xl-3 col-lg-5 col-md-6 col-sm-6 col-12 mb-3 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <i class="far fa-user-cog text-dark fs-2x"></i> Administración
                        </h1>
                    </div>
                    <div class="card-body overflow-auto h-200px">
                        <div class="d-flex flex-column">
                            @can('user.index')
                            <li class="d-flex align-items-center py-2">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('user.index') }}">Usuarios</a>
                            </li>
                            @endcan                            
                            @can('rol.index')
                                <li class="d-flex align-items-center py-2">
                                    <span class="bullet me-5 bg-primary"></span>
                                    <a href="{{ route('rol.index') }}">
                                        Roles
                                    </a>
                                </li>
                            @endcan
                            @can('permiso.index')
                                <li class="d-flex align-items-center py-2">
                                    <span class="bullet me-5 bg-primary"></span>
                                    <a href="{{ route('permiso.index') }}">
                                        Permisos
                                    </a>
                                </li>
                            @endcan
                            @can('acceso.index')
                                <li class="d-flex align-items-center py-2">
                                    <span class="bullet me-5 bg-primary"></span>
                                    <a href="{{ route('acceso.index') }}">
                                        Accesos
                                    </a>
                                </li>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endcanany
            {{-- @canany(['catrequerimiento.index', 'user.index', 'rol.index', 'permiso.index', 'acceso.index',
            'ejercicio.index'])
            <div class="mb-3 col-xl-3 col-lg-5 col-md-6 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <i class="text-gray-800 far fa-user fs-2x"></i> &nbsp;
                            Administración
                        </h1>
                    </div>
                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">
                            @can('catrequerimiento.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('catrequerimiento.index') }}">
                                    Catálogo de requerimientos
                                </a>
                            </li>
                            @endcan
                            @can('entidadfiscalizable.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('entidadfiscalizable.index') }}">
                                    Entidades fiscalizables
                                </a>
                            </li>
                            @endcan
                            @can('user.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a href="{{ route('user.index') }}">
                                    Usuarios
                                </a>
                            </li>
                            @endcan
                            @can('rol.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a href="{{ route('rol.index') }}">
                                    Roles
                                </a>
                            </li>
                            @endcan
                            @can('permiso.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a href="{{ route('permiso.index') }}">
                                    Permisos
                                </a>
                            </li>
                            @endcan
                            @can('acceso.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a href="{{ route('acceso.index') }}">
                                    Accesos
                                </a>
                            </li>
                            @endcan
                            @can('ejercicio.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a href="{{ route('ejercicio.index') }}">
                                    Ejercicio
                                </a>
                            </li>
                            @endcan
                            @can('firmaelectronicademo.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('firmaelectronicademo.index') }}">
                                    Firma eletronica demo
                                </a>
                            </li>
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('firmaarchivodemo.index') }}"> Firma archivo demo </a>
                            </li>
                            @endcan
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('firmaarchivodemo.index') }}">
                                    Firma archivo demo
                                </a>
                            </li>
                        </div>
                    </div>
                </div>
            </div>
            @endcan --}}
            {{-- @canany(['programaanual.index', 'programaanualauditoria.index', 'auditoriafuera.index',
            'asignacionauditoria.index'])
            <div class="mb-3 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <span class="text-gray-800 fa fa-handshake fs-2x"></span>&nbsp;
                            Auditorías
                        </h1>
                    </div>
                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">
                            @can('programaanual.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('programaanual.index') }}">
                                    Registro de Auditorías del Programa Anual de Auditorías (PAA)
                                </a>
                            </li>
                            @endcan
                            @can('programaanualauditoria.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('programaanualauditoria.index') }}">
                                    Adjuntar PAA (PDF)
                                </a>
                            </li>
                            @endcan
                            @can('auditoriafuera.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('auditoriafuera.index') }}">
                                    Auditorías fuera del PAA
                                </a>
                            </li>
                            @endcan
                            @can('asignacionauditoria.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('asignacionauditoria.index') }}">
                                    Asignación de auditorías
                                </a>
                            </li>
                            @endcan
                            @can('reasignaciondireccionauditoria.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('reasignaciondireccionauditoria.index') }}">
                                    Reasignación de auditorías
                                </a>
                            </li>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            @canany(['planeacion.index', 'planeacion_especifica.index', 'proyecto.index',
            'planeacionasignaciondepartamentos.index'])
            <div class="mb-3 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <span class="text-gray-800 fa fa-calendar fs-2x"></span>&nbsp;
                            Planeación
                        </h1>
                    </div>
                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">
                            @can('planeacionasignaciondepartamento.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('planeacionasignaciondepartamento.index') }}">
                                    Asignación de auditorías
                                </a>
                            </li>
                            @endcan
                            @can('reasignaciondepartamentoauditoria.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('reasignaciondepartamentoauditoria.index') }}">
                                    Reasignación de auditorías
                                </a>
                            </li>
                            @endcan
                            @can('auditoriamodificarcancelar.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('auditoriamodificarcancelar.index') }}">
                                    Cancelación o modificación de auditorías
                                </a>
                            </li>
                            @endcan
                            @can('planeacion.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a href="{{ route('planeacion.index') }}">
                                    Planeación general
                                </a>
                            </li>
                            @endcan
                            @can('planeacion_especifica.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('planeacion_especifica.index') }}">
                                    Planeación específica
                                </a>
                            </li>
                            @endcan
                            @can('proyecto.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a href="{{ route('proyecto.index') }}">
                                    Catálogo de proyectos
                                </a>
                            </li>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            @canany(['ejecucionauditoria.index', 'ejecucionauditoriaprocedimiento.index'])
            <div class="mb-3 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <span class="text-gray-800 fa fa-cogs fs-2x"></span>&nbsp;
                            Ejecución
                        </h1>
                    </div>
                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">
                            @can('ejecucionauditoria.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('ejecucionauditoria.index') }}">
                                    Ejecución de auditorías
                                </a>
                            </li>
                            @endcan
                            @can('ejecucionauditoriaprocedimiento.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('ejecucionauditoriaprocedimiento.index') }}">
                                    Procedimientos, instrumentos y cédulas
                                </a>
                            </li>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            @canany(['resultadoauditoria.index'])
            <div class="mb-3 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <span class="text-gray-800 fa fa-inbox fs-2x"></span>&nbsp;
                            Resultados
                        </h1>
                    </div>
                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">
                            @can('resultadoauditoria.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('resultadoauditoria.index') }}">
                                    Resultados
                                </a>
                            </li>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endcan --}}


            @canany(['seguimientoauditoria.index'])
            <div class="mb-3 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <span class="text-gray-800 fs-2x"><img alt="Logo" src="{{asset('assets/img/registro.png')}}"
                                    class="h-40px logo" /></span>&nbsp;
                            Auditorias
                        </h1>
                    </div>
                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">
                            @can('seguimientoauditoria.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span>
                                <a href="{{ route('seguimientoauditoria.index') }}">
                                    Registro
                                </a>
                            </li>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            @canany(['asignaciondireccion.index','asignaciondepartamento.index','asignacionlideranalista.index'])
            <div class="mb-3 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <span class="text-gray-800 fs-2x"><img alt="Logo"
                                    src="{{asset('assets/img/asignacion.png')}}" class="h-40px logo" /></span>&nbsp;
                            Asignaciones
                        </h1>
                    </div>
                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">
                            @can('asignaciondireccion.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span>
                                <a href="{{ route('asignaciondireccion.index') }}">
                                    Dirección
                                </a>
                            </li>
                            @endcan
                            @can('asignaciondepartamento.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span>
                                <a href="{{ route('asignaciondepartamento.index') }}">
                                    Departamento
                                </a>
                            </li>
                            @endcan
                            @can('asignacionlideranalista.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span>
                                <a href="{{ route('asignacionlideranalista.index') }}">
                                    Lider y analista
                                </a>
                            </li>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            @canany(['radicacion.index','comparecencia.index'])
            <div class="mb-3 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <span class="text-gray-800 fs-2x"><img alt="Logo"
                                    src="{{asset('assets/img/seguimiento1.png')}}" class="h-40px logo" /></span>&nbsp;
                            Seguimiento
                        </h1>
                    </div>


                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span>
                                <a href="{{ route('auditoriaseguimiento.index') }}">
                                    Auditorias
                                </a>
                            </li>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            {{--
            <div class="mb-3 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <span class="text-gray-800 fs-2x"><img alt="Logo"
                                    src="{{asset('assets/img/asignacion.png')}}" class="h-40px logo" /></span>&nbsp;
                            Informe de seguimiento
                        </h1>
                    </div>
                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span>
                                <a href="{{ route('asignaciondireccion.index') }}">
                                    Primera etapa de aclaracion
                                </a>
                            </li>
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span>
                                <a href="{{ route('asignaciondepartamento.index') }}">
                                    Segunda etapa de aclaracion
                                </a>
                            </li>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <span class="text-gray-800 fs-2x"><img alt="Logo"
                                    src="{{asset('assets/img/asignacion.png')}}" class="h-40px logo" /></span>&nbsp;
                            Turno a Investigación
                        </h1>
                    </div>
                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span>
                                <a href="{{ route('asignaciondireccion.index') }}">
                                    Turno a Autoridad Investigadora
                                </a>
                            </li>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="mb-3 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <span class="text-gray-800 fa fa-share-square fs-2x"></span>&nbsp;
                            Turno a Investigación
                        </h1>
                    </div>
                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">
                            @can('turnoinvestigacion.index')
                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('turnoinvestigacion.index') }}">
                                    Turno a Autoridad Investigadora
                                </a>
                            </li>
                            @endcan
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="mb-3 col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <span class="text-gray-800 fa fa-download"></span>&nbsp;
                            Buzón de fiscalización
                        </h1>
                    </div>
                    <div class="overflow-auto card-body h-200px">
                        <div class="d-flex flex-column">

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('buzoncancelacionmodificacion.index') }}">
                                    Cancelaciones o modificaciones de auditorías
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('solicitudcancelacion.index') }}">
                                    Cancelación o modificación de auditorías
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a href="{{ route('buzon.index') }}">
                                    Solicitudes de requerimientos
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('designaciondepartamentoautorizacion.index') }}">
                                    Designación del departamento
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('autorizacionreasignaciondepartamento.index') }}">
                                    Reasignación de departamento de seguimiento
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('designacionlideranalistaautorizacion.index') }}">
                                    Designación del líder de proyecto y equipo de analistas
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('reasignacionliderautorizacion.index') }}">
                                    Reasignación de líder de proyecto
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('requerimientoautorizacion.index') }}">
                                    Requerimientos y solicitudes de prórroga
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('medidaapremioautorizacion.index') }}">
                                    Medidas de apremio
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('buzonautorizarpersonalasignado.index') }}">
                                    Asignaciones de personal (Planeación)
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('ejecucionpersonalautorizacion.index') }}">
                                    Asignaciones de personal (Ejecución)
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('buzonautorizarinstrumentoprocedimiento.index') }}">
                                    Instrumentos y cédulas
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('buzonconsultainstrumento.index') }}">
                                    Consulta de instrumentos y cédulas
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('preconfrontaautorizacionauditor.index') }}">
                                    Preconfrontas
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('buzonconsultapreconfronta.index') }}">
                                    Consulta de preconfrontas
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('preliminarautorizacionauditor.index') }}">
                                    Cédulas de resultados preliminares
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('resultadoactaautorizacion.index') }}">
                                    Actas de preconfrontas
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('aclaracionresultadoautorizacion.index') }}">
                                    Aclaraciones de resultados preliminares
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('aclaracionresultadoprorrogaautorizacion.index') }}">
                                    Prórrogas para la aclaración de resultados preliminares
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('resultadopreliminaraclaracionbuzon.index') }}">
                                    Aclaraciones de resultados preliminares
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('resultadoconfrontaautorizacion.index') }}">
                                    Confrontas
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('buzonconfronta.index') }}">
                                    Consulta de confrontas
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('resultadofinalconstancia.index') }}">
                                    Cédulas de resultados finales
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('resultadofinalauditoriaactaautorizacion.index') }}">
                                    Actas de confrontas
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('resultadoinformeauditoriaautorizacion.index') }}">
                                    Informes de auditorías
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('turnodenunciajuicioautorizacion.index') }}">
                                    Turnos de denuncias de juicio político
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('turnodenunciahechoautorizacion.index') }}">
                                    Turno de denuncias de hechos
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('promocionautorizacion.index') }}">
                                    Turnos de promociónes del ejercicio de la facultad de comprobación fiscal
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('turnoinformeautorizacion.index') }}">
                                    Turnos de informe de auditorías a la US
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('movimientoanalistaautorizacion.index') }}">
                                    Habilitar, inhabilitar y agregar analistas
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('radicacionconstancia.index') }}">
                                    Radicación
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('comparecenciaautorizacion.index') }}">
                                    Comparecencias
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('prasauditoriaautorizacion.index') }}">
                                    PRAS turnados de Auditoría
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('praseguimientoprorrogaautorizacion.index') }}">
                                    Prórrogas de seguimiento de PRA
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span>
                                <a href="{{ route('solventacionprimeraautorizacion.index') }}">Solventación primera
                                    etapa</a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span>
                                <a href="{{ route('solventacionsegundaetapaautorizacion.index') }}">Solventación segunda
                                    etapa</a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('autorizacionsolventacion.index') }}">
                                    Resolución de prórroga para solventación
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('conclusionarchivoautorizacion.index') }}">
                                    Acuerdos de conclusión y archivo
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('recomendaciondatoatencionautorizacion.index') }}">
                                    Datos de atención de las recomendaciones
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('recomendacionestadoautorizacion.index') }}">
                                    Oficios del Estado de las Recomendaciones
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('recomendacionseguimientoautorizacion.index') }}">
                                    Recomedaciones de seguimiento
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('recomendacionseguimientoestadoautorizacion.index') }}">
                                    Oficios del Estado de las Recomendaciones de Seguimiento
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('recomendaciondatocedulaautorizacion.index') }}">
                                    Cédulas subanalíticas de atención de recomendaciónes
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('recomendacionconclusionautorizacion.index') }}">
                                    Oficios de conclusión de atención de recomendaciones
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('recomendacionseguimientoconclusionautorizacion.index') }}">
                                    Oficios de conclusión de seguimiento de recomendaciones
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('recomendacionseguimientodatoautorizacion.index') }}">
                                    Datos de atención de las recomendaciones de seguimiento
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('recomendacionseguimientoavanceautorizacion.index') }}">
                                    Cédulas subanalíticas de atención de recomendaciónes de seguimiento
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('seguimientorequerimientoautorizacion.index') }}">
                                    Requerimientos
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('seguimientorequerimientoprorrogaautorizacion.index') }}">
                                    Prórrogas de requerimientos
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('informeprimeraetapaoficioautorizacion.index') }}">
                                    Informe de seguimiento 1ra etapa de aclaración
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('informesegundaetapaoficioautorizacion.index') }}">
                                    Informe de seguimiento 2da etapa de aclaración
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('verificacionautorizacion.index') }}">
                                    Verificaciones e inspecciones
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('prasreconduccionautorizacion.index') }}">
                                    PRAS reconducción
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('prareconduccionprorrogaautorizacion.index') }}">
                                    Prórrogas de reconducción de PRA
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('turnoinformefinalautorizacion.index') }}">
                                    Turnos de informe final
                                </a>
                            </li>

                            <li class="py-2 d-flex align-items-center">
                                <span class="bullet me-5 bg-primary"></span> <a
                                    href="{{ route('turnodevolucionautorizacion.index') }}">
                                    Atención de devolución
                                </a>
                            </li>

                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection
