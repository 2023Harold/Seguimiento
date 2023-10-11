@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('solicitudesaclanalisisrevision.edit',$solicitud) }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('solicitudesaclaracionatencion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Análisis de la atención
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        @include('layouts.contextos._accion')
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
                @if (!empty($solicitud->calificacion_atencion))
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Calificación de la atención: </label>
                        @if ($solicitud->calificacion_atencion=='Solventada')
                            <span class="badge badge-light-success">Solventado</span>
                        @endif
                        @if ($solicitud->calificacion_atencion=='No Solventada')
                            <span class="badge badge-light-danger">No Solventada</span>
                        @endif
                        @if ($solicitud->calificacion_atencion=='Solventada Parcialmente')
                            <span class="badge badge-light-warning">Solventada Parcialmente</span>
                        @endif
                    </div>             
                </div>
                <div class="row">
                    <label>Conclusión: </label>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        {!! BootForm::textarea('conclusion', false,old('conclusion', $solicitud->conclusion),['rows'=>'3','disabled']) !!}
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
                    {!! BootForm::open(['model' => $solicitud,'update' =>'solicitudesaclanalisisrevision.update','id' =>'form',]) !!}           
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::textarea('analisis', 'Análisis *',old('analisis', $solicitud->analisis),['rows'=>'5','disabled']) !!}
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label>Calificación sugerida de la atención: </label>
                            @if ($solicitud->calificacion_sugerida=='Solventada')
                                <span class="badge badge-light-success">Solventada</span>
                            @endif
                            @if ($solicitud->calificacion_sugerida=='No Solventada')
                                <span class="badge badge-light-danger">No Solventada</span>
                            @endif
                            @if ($solicitud->calificacion_sugerida=='Solventada Parcialmente')
                                <span class="badge badge-light-warning">Solventada Parcialmente</span>
                            @endif
                        </div>             
                    </div>  
                    <div class="row">
                        <div class="col-md-6">
                            {!! BootForm::radios("estatus", ' ',
                            [
                                'Aprobado' => 'Aprobar',
                                'Rechazado' => 'Rechazar'
                            ], null,false,['class'=>'i-checks rechazado']); !!}
                        </div>
                    </div>
                    <div class="row" id="justificacion" style="display: none;">
                        <div class="col-md-12">
                            {!! BootForm::textarea('motivo_rechazo','Motivo del rechazo:*','',["rows" => "2", "style" => "rezise:none"])!!}
                        </div>
                    </div>         
                    <div class="row">
                        <div class="col-md-12">
                            @btnSubmit('Guardar',route('solicitudesaclanalisisrevision.update'))
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
{!! JsValidator::formRequest('App\Http\Requests\AprobarFlujoAutorizacionRequest') !!}
@endsection


   