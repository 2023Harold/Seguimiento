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
        <div class="row">
            {!! BootForm::open(['model' => $solicitud,'update' =>'solicitudesaclaracioncalificacion.update','id' =>'form',]) !!}           
            <div class="row">
                <div class="col-md-12">
                    <a class="btn btn-light-linkedin popupSinLocation" href="{{ route('solicitudesaclaraciondocumentos.index') }}">Listado de documentos</a>
                    {!! BootForm::hidden('documentos','',['id'=>'documentos'])!!}
                </div>
            </div>           
            <div class="row">
                <div class="col-md-6">
                    {!! BootForm::radios('cumple', 'Calificación de la atención: *', ['Atendida'=>'Atendida', 'No Atendida'=>'No Atendida','Parcialmente Atendida'=>'Parcialmente Atendida'],old('cumple',$solicitud->cumple),false,['class'=>'i-checks']); !!}
                </div>
            </div> 
            @php           
                $mostrarDivMonto = ((!empty(old('cumple', $solicitud->cumple))&&old('cumple', $solicitud->cumple)=='Parcialmente Atendida')?'block':'none');
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
        $('input[name=cumple]').on('ifChanged', function(event){
            if(event.target.value=='Atendida'){
                $('#id_monto_solventa').hide();                   
            } else if(event.target.value=='No Atendida') {
                $('#id_monto_solventa').hide();
            }else if(event.target.value=='Parcialmente Atendida'){
                $('#id_monto_solventa').show();
            }
        });        
    });
</script>   
{!! JsValidator::formRequest('App\Http\Requests\SolicitudesAclaracionCalificacionRequest') !!}
@endsection


   