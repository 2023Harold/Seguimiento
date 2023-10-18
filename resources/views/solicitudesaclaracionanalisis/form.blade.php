@extends('layouts.app')
@section('breadcrums')
{{-- {{ Breadcrumbs::render('recomendacionesanalisis.edit',$recomendacion) }} --}}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('recomendacionesatencion.index') }}"><i
                    class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Análisis de la atención
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        @include('layouts.contextos._accion')
        <div>
        <div>
            <h3 class="card-title text-primary">Análisis</h3>
            <div class="card-body mt-2">
                <div class="row">
                    {!! BootForm::open(['model' => $solicitud,'update' =>'solicitudesaclaracionanalisis.update','id' =>'form',]) !!}
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::textarea('analisis', 'Análisis *',old('analisis', $solicitud->analisis),['rows'=>'10']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::textarea('conclusion', 'Conclusión *',old('conclusion', $solicitud->conclusion),['rows'=>'10']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! BootForm::radios("calificacion_sugerida", ' Calificación sugerida de la atención: *', ['Solventada'=>'Solventada', 'No Solventada'=>'No Solventada','Solventada Parcialmente'=>'Solventada Parcialmente'],old('calificacion_atencion',$solicitud->calificacion_atencion),false,['class'=>'i-checks']); !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @btnSubmit('Guardar',route('solicitudesaclaracionanalisis.update'))
                            @btnCancelar('Cancelar', route('solicitudesaclaracionatencion.index'))
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
{!! JsValidator::formRequest('App\Http\Requests\SolicitudesAclaracionAnalisisRequest') !!}
@endsection


