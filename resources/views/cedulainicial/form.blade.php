@extends('layouts.app')
@section('breadcrums')
  {{ Breadcrumbs::render('cedulainicial.edit', $auditoria)}} 
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">
                        <a href="{{ route('cedulainicial.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                        Cédula
                    </h1>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        @include('flash::message')
                        @include('layouts.contextos._auditoria')                    
                        <div class="row">
                            <div class="col-md-12">
                                <div id="body_loader" class="body_loader" style="display:none;">
                                    <span class="loader"></span>
                                    <span> Firmando la constancia, por favor espere.</span>
                                </div>
                                <embed src="{{asset($preconstancia)}}" type="application/pdf" width="100%" height="600px"/>
                            </div>
                        </div>                        
                        <div class="row mt-3">
                            <div class="col-md-6 justify-content-end">                                   
                                <a href="{{ route('cedulainicial.index') }}" class="btn btn-secondary me-2">Regresar</a>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
@endsection
