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
                            <div class="row">
                                <div class="col-md-12">
                                    {!! BootForm::textarea('conclusion', 'Conclusión *',old('conclusion', $solicitud->conclusion),['rows'=>'10']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    {!! BootForm::radios("calificacion_sugerida", ' Calificación de la atención: *', ['Solventada'=>'Solventada', 'No Solventada'=>'No Solventada','Solventada Parcialmente'=>'Solventada Parcialmente'],old('calificacion_atencion',$solicitud->calificacion_atencion),false,['class'=>'i-checks']); !!}
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
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('input[name=calificacion_sugerida]').on('ifChanged', function(event){
            if(event.target.value=='Solventada'){
                $('#id_monto_solventa').hide();
            } else if(event.target.value=='No Solventada') {
                $('#id_monto_solventa').hide();
            }else if(event.target.value=='Solventada Parcialmente'){
                $('#id_monto_solventa').show();
            }
        });
    });
</script>
{!! JsValidator::formRequest('App\Http\Requests\SolicitudesAclaracionAnalisisRequest') !!}
@endsection


