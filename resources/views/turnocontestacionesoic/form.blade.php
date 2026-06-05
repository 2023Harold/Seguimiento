@extends('layouts.app')
@section('breadcrums')
    @if (empty($turnocontestacionesui->fecha_notificacion))
        {{ Breadcrumbs::render('turnocontestacionesoic.create',$auditoria) }}
    @else
        {{ Breadcrumbs::render('turnocontestacionesoic.edit',$auditoria,$turnocontestacionesui) }}
    @endif
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                         <a href="{{ route('turnocontestacionesoic.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    @if (empty($turnocontestacionesoic->fecha_notificacion))
                        &nbsp; Agregar Contestación
                    @else
                        &nbsp; Editar Contestación
                    @endif
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['model' => $turnocontestacionesoic,'store' => 'turnocontestacionesoic.store','update' => 'turnocontestacionesoic.update','id' => 'form']) !!}
                <div class="row">
                    <div class="col-md-8">
                        {!! archivo('archivo_contestacion', 'Contestación: *', old('archivo_contestacion', $turnocontestacionesoic->archivo_contestacion)) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!! BootForm::date('fecha_notificacion', 'Fecha de notificación: *', old('fecha_notificacion', fecha($turnocontestacionesoic->fecha_notificacion,'Y-m-d'))); !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::date('fecha_recepcion', 'Fecha de recepción:  *', old('fecha_recepcion', fecha($turnocontestacionesoic->fecha_recepcion,'Y-m-d'))); !!}
                    </div>
                </div>
                  <div class="row">
                    <div class="col-md-12">
                        {!! BootForm::textarea('observaciones','Observaciones: *',old('observaciones',$turnocontestacionesoic->observaciones),["rows" => "3", "style" => "rezise:none"])!!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @canany(['turnocontestacionesoic.store','turnocontestacionesoic.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcanany
                            <a href="{{ route('turnocontestacionesoic.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    </div>
                </div>
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\ContestacionRequest') !!}
@endsection
