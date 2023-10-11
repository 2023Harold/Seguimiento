@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('solicitudesaclaracioncalificacion.edit',$solicitud) }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('solicitudesaclaracionacciones.index') }}"><i
                    class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Calificación de la atención
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        @include('layouts.contextos._accion')
        {{-- @include('layouts.contextos._recomendacion') --}}
        <div>
            <h3 class="card-title text-primary">Atención de la solicitud de aclaración </h3>  
            <div class="card-body py-7"> 
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Oficios de contestación: </label>
                        <span class="text-primary">
                            <a href="{{ route('solicitudescontestaciones.oficiossolicitud', $solicitud) }}" class="popupSinLocation">
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                            </a> 
                        </span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Lista de documentos: </label>
                        <span class="text-primary">
                            <a href="{{ route('solicitudesaclaraciondocumentos.show', $solicitud) }}" class="popupSinLocation">
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                            </a> 
                        </span>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Analisis: </label><br>
                        {!! BootForm::textarea('analisis', false,old('analisis', $solicitud->analisis),['rows'=>'3','disabled']) !!}
                    </div>             
                </div>               
                @if (!empty($solicitud->calificacion_sugerida))
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Calificación sugerida de la atención: </label>
                        @if ($solicitud->calificacion_sugerida=='Solventada')
                            <span class="badge badge-light-success">Solventado</span>
                        @endif
                        @if ($solicitud->calificacion_sugerida=='No Solventada')
                            <span class="badge badge-light-danger">No Solventada</span>
                        @endif
                        @if ($solicitud->calificacion_sugerida=='Solventada Parcialmente')
                            <span class="badge badge-light-danger">Solventada Parcialmente</span>
                        @endif
                    </div>             
                </div>              
                @endif        
                <hr/>
            </div>
        </div>
        <div class="row" style="padding-left: 2rem; ">
            {!! BootForm::open(['model' => $solicitud,'update' =>'solicitudesaclaracioncalificacion.update','id' =>'form',]) !!}           
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::radios("calificacion_atencion", 'Calificación de la atención: *', ['Solventada'=>'Solventada', 'No Solventada'=>'No Solventada','Solventada Parcialmente'=>'Solventada Parcialmente'],old('calificacion_atencion',$solicitud->calificacion_atencion),false,['class'=>'i-checks']); !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! BootForm::textarea('conclusion', 'Conclusión: *',old('conclusion', $solicitud->conclusion),['rows'=>'20']) !!}
                </div>
            </div>           
            @php           
                $mostrarDivMonto = ((!empty(old('calificacion_atencion', $solicitud->calificacion_atencion))&&old('calificacion_atencion', $solicitud->calificacion_atencion)=='Solventada Parcialmente')?'block':'none');
            @endphp
            <div class="row" id="id_monto_solventa" style="display:{!! $mostrarDivMonto !!}">
                <div class="col-md-6">
                    {!! BootForm::text('monto_solventado', 'Monto solventado: *', old('monto_solventado', $solicitud->monto_solventado),['class' => 'numeric']) !!}
                </div>
            </div>             
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit('Guardar y enviar',route('solicitudesaclaracioncalificacion.update'))
                    @btnCancelar('Cancelar', route('solicitudesaclaracionacciones.index'))
                </div>
            </div>
            {!! BootForm::close() !!}
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('input[name=calificacion_atencion]').on('ifChanged', function(event){
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
{!! JsValidator::formRequest('App\Http\Requests\SolicitudesAclaracionCalificacionRequest') !!}
@endsection


   