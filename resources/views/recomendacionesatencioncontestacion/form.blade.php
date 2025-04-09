@extends('layouts.app')
@section('breadcrums')
    @if (empty($contestacion->oficio_contestacion))
    {{ Breadcrumbs::render('recomendacionescontestaciones.create',$auditoria) }}
    @else
    {{ Breadcrumbs::render('recomendacionescontestaciones.edit',$contestacion, $auditoria) }}
    @endif
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('recomendacionescontestaciones.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    @if (empty($contestacion->oficio_contestacion))
                        &nbsp; Agregar
                    @else
                        &nbsp; Editar
                    @endif
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                <div class="col-md-12">
                    <h3 class="card-title text-primary float">Atención de la recomendación</h3>
                </div>
                <div class="card-body py-7">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <label>Fecha compromiso de atención: </label>
                            <span class="text-primary">
                                {{ fecha($accion->fecha_termino_recomendacion) }}
                            </span>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                            <label>Nombre del responsable de la entidad fiscalizable: </label>
                            <span class="text-primary">
                                {{$recomendacion->nombre_responsable }}
                            </span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <label>Cargo del responsable: </label>
                            <span class="text-primary">
                                {{$recomendacion->cargo_responsable }}
                            </span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <label>Responsable del seguimiento: </label>
                            <span class="text-primary">
                                {{$accion->analista->name }}
                            </span>
                        </div>
                    </div>
                    <hr/>
                </div>
                {!! BootForm::open(['model' => $contestacion, 'store' => 'recomendacionescontestaciones.store', 'update' => 'recomendacionescontestaciones.update', 'id' => 'form']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! archivo('oficio_contestacion', 'Oficio de contestación de la recomendación: *', old('oficio_contestacion', $contestacion->oficio_contestacion)) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        {!! BootForm::select('foliocrr_id', 'Folio de correspondencia: *', $folios->toArray() , old('foliocrr_id'), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @btnSubmit("Guardar")
                            @btnCancelar('Cancelar', route('recomendacionescontestaciones.index'))
                        </div>
                    </div>
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\RecomendacionesContestacionRequest') !!}
@endsection
