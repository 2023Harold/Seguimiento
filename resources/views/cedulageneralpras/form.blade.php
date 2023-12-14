@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('cedulageneralpras.edit',$auditoria) }}
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
                    Cédula General PRAS
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                <embed src="{{asset('storage/temporales/'.$nombre)}}" type="application/pdf" width="100%" height="600px"/>
                {!! BootForm::open(['model' => $auditoria, 'store' => 'cedulageneralpras.store', 'update' => 'cedulageneralpras.update','id'=>'form']) !!}            
                    <div class="row">
                        <div class="col-md-12">
                            {{-- @if (auth()->user()->can('permiso.store') || auth()->user()->can('permiso.update')) --}}
                            <button type="submit" name="enviar" class="btn btn-primary">Enviar a revisión</button>
                            {{-- @endif --}}
                        </div>
                    </div>
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection