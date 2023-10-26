@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('recomendacionesdocumentos.edit',$recomendacion,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('recomendacionesatencion.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp; Listado de documentos
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                <div>
                    <h3 class="card-title text-primary">Atención de la recomendación </h3>
                    <div class="card-body py-7">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label>Fecha compromiso de atención: </label>
                                <span class="text-primary">
                                    {{ fecha($accion->fecha_termino_recomendacion) }}
                                </span>
                            </div>
                            <div class="col-lg-6 col-md-16 col-sm-12 col-12">
                                <label>Nombre del responsable por parte de la entidad: </label>
                                <span class="text-primary">
                                    {{$recomendacion->nombre_responsable }}
                                </span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <label>Cargo del responsable por parte de la entidad: </label>
                                <span class="text-primary">
                                    {{$recomendacion->cargo_responsable }}
                                </span>
                            </div>                        
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label>Responsable del seguimiento: </label>
                                <span class="text-primary">
                                    {{$accion->analista->name }}
                                </span>
                            </div>                            
                        </div>                        
                        <hr/>
                    </div>
                </div>
                <div>                   
                    <div class="card-body mt-2">
                        <div class="row">
                            {!! BootForm::open(['model' => $recomendacion,'update' =>'recomendacionesdocumentos.update','id' =>'form',]) !!}
                            <div class="row">
                                <div class="col-md-12">
                                    {!! BootForm::textarea('listado_documentos', 'Listado de documentos *',old('listado_documentos', $recomendacion->listado_documentos),['rows'=>'10']) !!}
                                </div>
                            </div>                           
                            <div class="row">
                                <div class="col-md-12">
                                    @btnSubmit('Guardar',route('recomendacionesdocumentos.update'))
                                    @btnCancelar('Cancelar', route('recomendacionesatencion.index'))
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
{!! JsValidator::formRequest('App\Http\Requests\RecomendacionesDocumentoRequest') !!}
@endsection


