@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('solicitudesaclaracionanalisis.edit',$solicitud,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('solicitudesaclaracionatencion.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp; Análisis de la atención
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._accionsolicitud')
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
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <span>
                                        <a class="btn btn-primary float-end" href="{{ route('solicitudesaclaracionanexos.index') }}">
                                            Agregar anexos
                                        </a>
                                    </span>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    {!! BootForm::textarea('conclusion', 'Conclusión *',old('conclusion', $solicitud->conclusion),['rows'=>'10']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    {!!BootForm::radios("calificacion_sugerida", ' Calificación de la atención: *', ['Solventada'=>'Solventada', 'No Solventada'=>'No Solventada','Solventada Parcialmente'=>'Solventada Parcialmente'],old('calificacion_atencion',$solicitud->calificacion_atencion),false,['class'=>'i-checks']) !!}
                                </div>
                            </div>
                            @php
                                $mostrarDivMonto = ((!empty(old('calificacion_sugerida', $solicitud->calificacion_sugerida))&&old('calificacion_sugerida', $solicitud->calificacion_sugerida)=='Solventada Parcialmente')?'block':'none');
                            @endphp
                            <div class="row" id="id_monto_solventa" style="display:{!! $mostrarDivMonto !!}">
                                <div class="col-md-6">
                                    {!! BootForm::text('monto_solventado', 'Monto solventado: *', old('monto_solventado', $solicitud->monto_solventado),['class' => 'numeric']) !!}
                                </div>
                            </div>   
                            @php
                                $mostrarDivPromocion = ((!empty(old('calificacion_sugerida', $solicitud->calificacion_sugerida))&&old('calificacion_sugerida', $solicitud->calificacion_sugerida)!='Solventada')?'block':'none');
                            @endphp                         
                            <div id="div_promocion">
                                <div class="row">
                                    <div class="col-md-4">
                                    {!! BootForm::select('promocion', 'Promoción: *', $promocion->toArray(), old('promocion',$solicitud->promocion),['data-control'=>'select2', 'class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción']) !!}
                                    </div>
                                </div>
                                @php
                                $mostrarDivMontoPromo = ((!empty(old('promocion', $solicitud->promocion))&&old('promocion', $solicitud->promocion)!='2')?'block':'none');
                                // $mostrarDivRecomendaciones = ((!empty(old('segtipo_accion_id', $accion->segtipo_accion_id))&&old('segtipo_accion_id', $accion->segtipo_accion_id)=='2')?'block':'none');
                                @endphp
                                <div id="div_monto_promocion" style="display:{!! $mostrarDivMontoPromo !!}">
                                    <div class="row" id="id_monto_promocion">
                                        <div class="col-md-6">
                                            {!! BootForm::text('monto_promocion', 'Monto de la promoción: *', old('monto_promocion', '$'.number_format( $solicitud->monto_promocion, 2)),['disabled']) !!}
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @btnSubmit('Guardar y continuar',route('solicitudesaclaracionanexos.index'))
                                    {{-- @btnSubmit('Guardar',route('solicitudesaclaracionanalisis.update')) --}}
                                    @btnCancelar('Cancelar', route('solicitudesaclaracionatencion.index'))
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
            if(event.target.value=='Solventada'){
                $('#id_monto_solventa').show();
                $('#div_promocion').show();
            } else if(event.target.value=='No Solventada') {
                $('#id_monto_solventa').hide();
                $('#div_promocion').show();     
                $('#monto_promocion').val('@php  echo '$'.number_format( $accion->monto_aclarar, 2);   @endphp'); 
                $('#div_monto_promocion').hide();    
            }else if(event.target.value=='Solventada Parcialmente'){
                $('#id_monto_solventa').show();
                $('#div_promocion').show();
                $('#monto_promocion').val('');
                $('#monto_solventado').val('');
                $('#div_monto_promocion').hide();
                $("#promocion").html('<option value="">Seleccione primero una categoría</option><option value="2">Recomendación</option><option value="3">Pliego de observación</option><option value="4">Promoción de responsabilidad administrativa sancionatoria</option>');
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
{!! JsValidator::formRequest('App\Http\Requests\SolicitudesAclaracionAnalisisRequest') !!}
@endsection


