@extends('layouts.app')
@section('breadcrums')
{{-- {{ Breadcrumbs::render('recomendacionesatencion.index') }} --}}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('recomendacionesacciones.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;Recomendaciones
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                {{-- @include('layouts.contextos._auditoria') --}}
                {{-- @include('layouts.contextos._accion') --}}
                <h3 class="card-title text-primary">Recomendacion</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha compromiso de Atención</th>
                                <th>Nombre del responsable de atender las recomendaciones por parte de la entidad
                                    fiscalizable</th>
                                <th>cargo del responsable</th>
                                <th>Responsable del seguimiento</th>
                                <th>Oficio de la contestacion de la recomendacion</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection