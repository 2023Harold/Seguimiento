@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('pliegosobservacionanalisis.edit',$pliegosobservacion,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
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
                @include('layouts.contextos._accionpliego')
                <div class="card-body">
                    <div class="row">
                        {!! BootForm::open(['model' => $pliegosobservacion,'update' =>'pliegosobservacionanalisis.update','id' =>'form',]) !!}
                        <div class="row">
                            <div class="col-md-12">
                                {!! BootForm::textarea('analisis', 'Análisis *',old('analisis', $pliegosobservacion->analisis),['rows'=>'10']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {!! BootForm::textarea('conclusion', 'Conclusión *',old('conclusion', $pliegosobservacion->analisis),['rows'=>'10']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                {!! BootForm::radios("calificacion_sugerida", ' Calificación de la atención: *', ['Solventado'=>'Solventado', 'No Solventado'=>'No Solventado','Solventado Parcialmente'=>'Solventado Parcialmente'],old('calificacion_sugerida',$pliegosobservacion->calificacion_sugerida),false,['class'=>'i-checks']); !!}
                            </div>
                        </div>
                        @php
                            $mostrarDivMonto = ((!empty(old('calificacion_sugerida', $pliegosobservacion->calificacion_sugerida))&&old('calificacion_sugerida', $pliegosobservacion->calificacion_sugerida)=='Solventado Parcialmente')?'block':'none');
                        @endphp
                        <div class="row" id="id_monto_solventa" style="display:{!! $mostrarDivMonto !!}">
                            <div class="col-md-6">
                                {!! BootForm::text('monto_solventado', 'Monto solventado: *', old('monto_solventado', $pliegosobservacion->monto_solventado),['class' => 'numeric']) !!}
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
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('input[name=calificacion_sugerida]').on('ifChanged', function(event){
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
{!! JsValidator::formRequest('App\Http\Requests\PliegosObservacionAnalisisRequest') !!}
@endsection

