@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('recomendacionescalificacion.edit',$recomendacion) }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('recomendacionesacciones.edit',$recomendacion) }}"><i
                    class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Calificación de la atención
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
                            <a href="{{ route('recomendacionescontestaciones.oficiosrecomendacion', $recomendacion) }}" class="popupSinLocation">
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                            </a> 
                        </span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Lista de documentos: </label>
                        <span class="text-primary">
                            <a href="{{ route('recomendacionesdocumentos.show', $recomendacion) }}" class="popupSinLocation">
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                            </a> 
                        </span>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Analisis: </label><br>
                        {!! BootForm::textarea('analisis', false,old('analisis', $recomendacion->analisis),['rows'=>'3','disabled']) !!}
                    </div>             
                </div>                     
                <hr/>
            </div>
        </div>
        <div>
            <h3 class="card-title text-primary">Calificación </h3>  
            <div class="card-body py-7">  
                <div class="row">
                    {!! BootForm::open(['model' => $recomendacion,'update' =>'recomendacionescalificacion.update','id' =>'form',]) !!}           
                    <div class="row">
                        <div class="col-md-6">
                            {!! BootForm::radios("calificacion_atencion", ' Calificación de la atención: *', ['Atendida'=>'Atendida', 'No Atendida'=>'No Atendida','Parcialmente Atendida'=>'Parcialmente Atendida'],old('calificacion_atencion',$recomendacion->calificacion_atencion),false,['class'=>'i-checks']); !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::textarea('conclusion', 'Conclusión: *',old('conclusion', $recomendacion->conclusion),['rows'=>'20']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @btnSubmit('Guardar y enviar',route('recomendacionesatencion.store'))
                            @btnCancelar('Cancelar', route('recomendacionesacciones.edit',$recomendacion))
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
{!! JsValidator::formRequest('App\Http\Requests\RecomendacionesCalificacionRequest') !!}
@endsection


   