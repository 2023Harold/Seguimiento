@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('administracion.index') }}
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
                            @can('asignacionunidadadministrativa.index')
                                <li class="d-flex align-items-center py-2">
                                    <span class="bullet me-5 bg-primary"></span>
                                    <a href="{{ route('asignacionunidadadministrativa.index') }}">
                                        Asignar Unidad Administrativa  
                                    </a>
                                </li>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @endcanany          
        </div>
    </div>
</div>
@endsection
