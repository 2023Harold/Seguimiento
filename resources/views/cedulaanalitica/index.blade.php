@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('cedulaanalitica.edit',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('cedulainicial.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Cédula Analitica
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                <embed src="{{asset('storage/temporales/'.$nombre)}}" type="application/pdf" width="100%" height="600px"/>
            </div>
        </div>
    </div>
</div>
@endsection