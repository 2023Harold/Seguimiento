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
                <a href="{{ route('pliegosobservacionatencion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Análisis de la atención
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._accionpliego')
                <div>
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
                            <div class="col-md-12">
                                <span>
                                    <a class="btn btn-primary float-end" href="{{ route('pliegosobservacionanexos.index') }}">
                                        Agregar anexos
                                    </a>
                                </span>
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
                        @php
                            $mostrarDivPromocion = ((!empty(old('calificacion_sugerida', $pliegosobservacion->calificacion_sugerida))&&old('calificacion_sugerida', $pliegosobservacion->calificacion_sugerida)!='Solventado')?'block':'none');
                        @endphp
                        <div id="div_promocion" style="display:{!! $mostrarDivPromocion !!}">
                            <div class="row">
                                <div class="col-md-4">
                                {!! BootForm::select('promocion', 'Promoción: ', $promocion->toArray(), old('promocion',$pliegosobservacion->promocion),['data-control'=>'select2', 'class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción']) !!}
                                </div>
                            </div>
                            @php
                                $mostrarDivMontoPromo = ((!empty(old('promocion', $pliegosobservacion->promocion))&&old('promocion', $pliegosobservacion->promocion)!='2')?'block':'none');
                            @endphp
                            <div class="row" id="div_monto_promocion" style="display:{!! $mostrarDivMontoPromo !!}">
                                <div class="col-md-6">
                                {!! BootForm::text('monto_promocion', 'Monto de la promoción: *', old('monto_promocion', '$'.number_format( $pliegosobservacion->monto_promocion, 2)),['disabled']) !!}
                                </div>
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
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('input[name=calificacion_sugerida]').on('ifChanged', function(event){
            if(event.target.value=='Solventado'){
                $('#id_monto_solventa').hide();
                $('#div_promocion').hide();
            } else if(event.target.value=='No Solventado') {
                $('#id_monto_solventa').hide();
                $('#div_promocion').show();
                $('#monto_promocion').val('@php  echo '$'.number_format( $accion->monto_aclarar, 2);   @endphp');
                $('#div_monto_promocion').hide();
            }else if(event.target.value=='Solventado Parcialmente'){
                $('#id_monto_solventa').show();
                $('#div_promocion').show();
                $('#monto_promocion').val('');
                $('#monto_solventado').val('');
                $('#div_monto_promocion').hide();
                $("#promocion").html('<option value="">Seleccione primero una categoría</option><option value="2">Recomendación</option><option value="4">Promoción de responsabilidad administrativa sancionatoria</option>');
            }
        });

        $("#promocion").select().on('change', function(e) {
            var tipoaccionseleccionado = $(this).children("option:selected").text();
            if(tipoaccionseleccionado=='Recomendación'){
                $('#div_monto_promocion').hide();
            }else{
                $('#div_monto_promocion').show();
            }
        });

        $("#monto_solventado").change(function() {
            var montosolventado = $(this).val();
            var sdmon = montosolventado.replace("$", "");
            var sdmon2 = sdmon.replace(",", "");
            var montoacls=@php  echo $accion->monto_aclarar;   @endphp - parseFloat(sdmon2).toFixed(2);

            const formatoMexico = (number) => {
            const exp = /(\d)(?=(\d{3})+(?!\d))/g;
            const rep = '$1,';
            return number.toString().replace(exp,rep);
            }

            $('#monto_promocion').val('$'+formatoMexico(montoacls.toFixed(2)));
        });
    });
</script>
{!! JsValidator::formRequest('App\Http\Requests\PliegosObservacionAnalisisRequest') !!}
@endsection

