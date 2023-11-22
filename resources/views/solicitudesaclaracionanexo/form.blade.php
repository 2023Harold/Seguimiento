@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('solicitudesaclaracionanexos.create',$solicitud,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('solicitudesaclaracionanexos.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp; Agregar                   
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                {!! BootForm::open(['model' => $anexo, 'store' => 'solicitudesaclaracionanexos.store', 'update' => 'solicitudesaclaracionanexos.update', 'id' => 'form']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            {!! archivo('archivo', 'Archivo: *', old('archivo', $anexo->archivo)) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! BootForm::text('nombre_archivo', 'Nombre del archivo: *', old('nombre_archivo', fecha($anexo->nombre_archivo, 'Y-m-d'))); !!}
                        </div>                        
                    </div>                    
                    <div class="row">
                        <div class="col-md-12">
                            @btnSubmit("Guardar")
                            @btnCancelar('Cancelar', route('solicitudesaclaracionanexos.index'))
                        </div>
                    </div>
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\SolicitudesAclaracionAnexoRequest') !!}
@endsection
