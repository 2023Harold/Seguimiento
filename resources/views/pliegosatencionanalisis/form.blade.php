@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('pliegosobservacionanalisis.edit',$pliegosobservacion) }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('pliegosobservacionatencion.index') }}"><i
                    class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Análisis de la atención
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        @include('layouts.contextos._accion')
        <div>
            <h3 class="card-title text-primary">Atención de los pliegos de observacion </h3>
            <div class="card-body py-7">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Fecha compromiso de atención: </label>
                        <span class="text-primary">
                            {{ fecha($accion->fecha_termino_recomendacion) }}
                        </span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Nombre del responsable por parte de la entidad: </label>
                        <span class="text-primary">
                            {{$pliegosobservacion->nombre_responsable }}
                        </span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Cargo del responsable por parte de la entidad: </label>
                        <span class="text-primary">
                            {{$pliegosobservacion->cargo_responsable }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Responsable del seguimiento: </label>
                        <span class="text-primary">
                            {{$accion->analista->name }}
                        </span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Oficios de contestación: </label>
                        <span class="text-primary">
                            <a href="{{ route('pliegosobservacionatencioncontestacion.show', 0) }}" class="popupSinLocation">
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                            </a>
                        </span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Lista de documentos: </label>
                        <span class="text-primary">
                            <a href="{{ route('pliegosobservacioncalificacion.show', $pliegosobservacion) }}" class="popupSinLocation">
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                            </a>
                        </span>
                    </div>
                </div>
                @if (!empty($pliegosobservacion->calificacion_atencion))
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Calificación de la atención: </label>
                        @if ($pliegosobservacion->calificacion_atencion=='Atendida')
                            <span class="badge badge-light-success">Atendida</span>
                        @endif
                        @if ($pliegosobservacion->calificacion_atencion=='No Atendida')
                            <span class="badge badge-light-danger">No Atendida</span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <label>Conclusión: </label>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        {!! BootForm::textarea('conclusion', false,old('conclusion', $pliegosobservacion->conclusion),['rows'=>'3','disabled']) !!}
                    </div>
                </div>
                @endif
                <hr/>
            </div>
        </div>
        <div>
            <h3 class="card-title text-primary">Análisis</h3>
            <div class="card-body mt-2">
                <div class="row">
                    {!! BootForm::open(['model' => $pliegosobservacion,'update' =>'pliegosobservacionanalisis.update','id' =>'form',]) !!}
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::textarea('analisis', 'Análisis *',old('analisis', $pliegosobservacion->analisis),['rows'=>'10']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! BootForm::radios("calificacion_sugerida", ' Calificación sugerida de la atención: *', ['Atendida'=>'Atendida', 'No Atendida'=>'No Atendida','Parcialmente Atendida'=>'Parcialmente Atendida'],old('calificacion_atencion',$pliegosobservacion->calificacion_atencion),false,['class'=>'i-checks']); !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @btnSubmit('Guardar',route('pliegosobservacionanalisis.store'))
                            @btnCancelar('Cancelar', route('pliegosobservacionatencion.index'))
                        </div>
                    </div>
                    {!! BootForm::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\PliegosObservacionAnalisisRequest') !!}
@endsection


