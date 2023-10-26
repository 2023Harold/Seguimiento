@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('solicitudesaclaraciondocumentos.edit',$solicitud,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('solicitudesaclaracionatencion.index') }}"><i
                        class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                        &nbsp; Listado de documentos
                </h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        {!! BootForm::textarea('listado_documentos', 'Listado de documentos ',old('listado_documentos', $solicitud->listado_documentos),['rows'=>'10','disabled']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
