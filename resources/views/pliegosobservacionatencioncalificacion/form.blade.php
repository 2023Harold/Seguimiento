@extends('layouts.app')
@section('breadcrums')
{{-- {{ Breadcrumbs::render('pliegosobservacioncalificacion.edit',$pliegosobservacion) }} --}}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            {{-- <a href="{{ route('pliegosobservacionacciones.edit',$pliegosobservacion) }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> --}}
            &nbsp; Calificación de la atención
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        @include('layouts.contextos._accion')
        <div>
            <h3 class="card-title text-primary">Atención del pliego de observación </h3>
            <div class="card-body py-7">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Oficios de contestación: </label>
                        <span class="text-primary">
                            <a href="{{ route('pliegosobservacioncontestacion.oficiospliegosobservacion', $pliegosobservacion) }}" class="popupSinLocation">
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                            </a>
                        </span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Lista de documentos: </label>
                        <span class="text-primary">
                            <a href="{{ route('pliegosobservaciondocumentos.show', $pliegosobservacion) }}" class="popupSinLocation">
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Analisis: </label><br>
                        {!! BootForm::textarea('analisis', false,old('analisis', $pliegosobservacion->analisis),['rows'=>'3','disabled']) !!}
                    </div>
                </div>
                @if (!empty($pliegosobservacion->calificacion_sugerida))
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Calificación de la atención: </label>
                        @if ($pliegosobservacion->calificacion_sugerida=='Solventado')
                            <span class="badge badge-light-success">Solventado</span>
                        @endif
                        @if ($pliegosobservacion->calificacion_sugerida=='No Solventado')
                            <span class="badge badge-light-danger">No Solventado</span>
                        @endif
                        @if ($pliegosobservacion->calificacion_sugerida=='Solventado Parcialmente')
                            <span class="badge badge-light-warning">Solventado Parcialmente</span>
                        @endif
                    </div>
                </div>
                @endif
                <hr/>
            </div>
        </div>
        <div>
            <h3 class="card-title text-primary">Calificación </h3>
            <div class="card-body py-7">
                <div class="row">
                    {!! BootForm::open(['model' => $pliegosobservacion,'update' =>'pliegosatencioncalificacion.update','id' =>'form',]) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! BootForm::radios("calificacion_atencion", ' Calificación de la atención: *', ['Solventado'=>'Solventado', 'No Solventado'=>'No Solventado', 'Solventado Parcialmente'=>'Solventado Parcialmente'],old('calificacion_atencion',$pliegosobservacion->calificacion_atencion),false,['class'=>'i-checks']); !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::textarea('conclusion', 'Conclusión: *',old('conclusion', $pliegosobservacion->conclusion),['rows'=>'20']) !!}
                        </div>
                    </div>
                    @php
                        $mostrarDivMonto = ((!empty(old('calificacion_atencion', $pliegosobservacion->calificacion_atencion))&&old('calificacion_atencion', $pliegosobservacion->calificacion_atencion)=='Solventada Parcialmente')?'block':'none');
                    @endphp
                    <div class="row" id="id_monto_solventa" style="display:{!! $mostrarDivMonto !!}">
                        <div class="col-md-6">
                            {!! BootForm::text('monto_solventado', 'Monto solventado: *', old('monto_solventado', $pliegosobservacion->monto_solventado),['class' => 'numeric']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @btnSubmit('Guardar y enviar',route('pliegosatencioncalificacion.update'))
                            @btnCancelar('Cancelar', route('pliegosobservacionatencion.index',$pliegosobservacion))
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
<script>
    $(document).ready(function() {
        $('input[name=calificacion_atencion]').on('ifChanged', function(event){
            if(event.target.value=='Solventado'){
                $('#id_monto_solventa').hide();
            } else if(event.target.value=='No Solventado') {
                $('#id_monto_solventa').hide();
            }else if(event.target.value=='Solventado Parcialmente'){
                $('#id_monto_solventa').show();
            }
        });
    });
</script>
{!! JsValidator::formRequest('App\Http\Requests\PliegosObservacionCalificacionRequest') !!}
@endsection


