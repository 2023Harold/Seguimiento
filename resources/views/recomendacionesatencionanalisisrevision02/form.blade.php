@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('recomendacionesanalisis.edit',$recomendacion) }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('recomendacionesatencion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Análisis de la atención
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        @include('layouts.contextos._accion')
        <div>
            <h3 class="card-title text-primary">Atención de la recomendación </h3>  
            <div class="card-body py-7">    
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Fecha compromiso de atención: </label>
                        <span class="text-primary">
                            {{ fecha($accion->fecha_termino_recomendacion) }}
                        </span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Nombre del responsable por parte de la entidad: </label>
                        <span class="text-primary">
                            {{$recomendacion->nombre_responsable }}
                        </span>
                    </div>  
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Cargo del responsable por parte de la entidad: </label>
                        <span class="text-primary">
                            {{$recomendacion->cargo_responsable }} 
                        </span>
                    </div>                                   
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Responsable del seguimiento: </label>
                        <span class="text-primary">
                            {{$accion->analista->name }}
                        </span>
                    </div> 
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Oficios de contestación: </label>
                        <span class="text-primary">
                            <a href="{{ route('recomendacionescontestaciones.show', 0) }}" class="popupSinLocation">
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                            </a> 
                        </span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Lista de documentos: </label>
                        <span class="text-primary">
                            <a href="{{ route('recomendacionescalificacion.show', $recomendacion) }}" class="popupSinLocation">
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                            </a> 
                        </span>
                    </div>
                </div>              
                @if (!empty($recomendacion->calificacion_atencion))
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Calificación de la atención: </label>
                        @if ($recomendacion->calificacion_atencion=='Atendida')
                            <span class="badge badge-light-success">Atendida</span>
                        @endif
                        @if ($recomendacion->calificacion_atencion=='No Atendida')
                            <span class="badge badge-light-danger">No Atendida</span>
                        @endif
                    </div>             
                </div>
                <div class="row">
                    <label>Conclusión: </label>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        {!! BootForm::textarea('conclusion', false,old('conclusion', $recomendacion->conclusion),['rows'=>'3','disabled']) !!}
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
                    {!! BootForm::open(['model' => $recomendacion,'update' =>'recomendacionesanalisisrevision02.update','id' =>'form',]) !!}           
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::textarea('analisis', 'Análisis *',old('analisis', $recomendacion->analisis),['rows'=>'5','disabled']) !!}
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label>Calificación de la atención: </label>
                            @if ($recomendacion->calificacion_sugerida=='Atendida')
                                <span class="badge badge-light-success">Atendida</span>
                            @endif
                            @if ($recomendacion->calificacion_sugerida=='No Atendida')
                                <span class="badge badge-light-danger">No Atendida</span>
                            @endif
                            @if ($recomendacion->calificacion_sugerida=='Parcialmente Atendida')
                                <span class="badge badge-light-warning">Parcialmente Atendida</span>
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
                            @btnSubmit('Guardar',route('recomendacionesanalisisrevision.update'))
                            @btnCancelar('Cancelar', route('recomendacionesatencion.index'))
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


   