@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('recomendacionescalificacion.edit',$recomendacion) }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('recomendacionesacciones.edit',$recomendacion) }}"><i
                    class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Calificación de la atención
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        @include('layouts.contextos._accion')
        @include('layouts.contextos._recomendacion')
        <div class="row">
            {!! BootForm::open(['model' => $recomendacion,'update' =>'recomendacionescalificacion.update','id' =>'form',]) !!}           
            <div class="row">
                <div class="col-md-12">
                    <a class="btn btn-light-linkedin popupSinLocation" href="{{ route('recomendacionesdocumentos.index') }}">Listado de documentos</a>
                    {!! BootForm::hidden('documentos','',['id'=>'documentos'])!!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! BootForm::textarea('analisis', 'Análisis *',old('analisis', $recomendacion->analisis)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::radios("calificacion_atencion", ' Calificación de la atención: *', ['Atendida'=>'Atendida', 'No Atendida'=>'No Atendida'],old('calificacion_atencion',$recomendacion->calificacion_atencion),false,['class'=>'i-checks']); !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! BootForm::textarea('conclusion', 'Conclusión: *',old('conclusion', $recomendacion->conclusion)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit('Guardar y enviar',route('recomendacionesatencion.store'))
                    @btnCancelar('Cancelar', route('recomendacionesacciones.edit',$recomendacion))
                </div>
            </div>
            {!! BootForm::close() !!}
        </div>
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\RecomendacionesCalificacionRequest') !!}
@endsection


   