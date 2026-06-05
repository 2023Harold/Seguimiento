@extends('layouts.app')
@section('breadcrums')
    @if (empty($turnocontestacionesarc->fecha_notificacion_archivo))
        {{ Breadcrumbs::render('turnocontestacionesarc.create',$auditoria) }}
    @else
        {{ Breadcrumbs::render('turnocontestacionesarc.edit',$auditoria,$turnocontestacionesarc) }}
    @endif
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                         <a href="{{ route('turnocontestacionesarc.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    @if (empty($turnocontestacionesarc->fecha_notificacion))
                        &nbsp; Agregar Contestación
                    @else
                        &nbsp; Editar Contestación
                    @endif
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['model' => $turnocontestacionesarc,'store' => 'turnocontestacionesarc.store','update' => 'turnocontestacionesarc.update','id' => 'form']) !!}
                <div class="row">
                    <div class="col-md-8">
                        {!! archivo('archivo_contestacion', 'Contestación: *', old('archivo_contestacion', $turnocontestacionesarc->archivo_contestacion)) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!! BootForm::date('fecha_notificacion', 'Fecha de notificación: *', old('fecha_notificacion', fecha($turnocontestacionesarc->fecha_notificacion,'Y-m-d'))); !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::date('fecha_recepcion', 'Fecha de recepción:  *', old('fecha_recepcion', fecha($turnocontestacionesarc->fecha_recepcion,'Y-m-d'))); !!}
                    </div>
                </div>
                  <div class="row">
                    <div class="col-md-12">
                        {!! BootForm::textarea('observaciones','Observaciones: *',old('observaciones',$turnocontestacionesarc->observaciones),["rows" => "3", "style" => "rezise:none"])!!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @canany(['turnocontestacionesarc.store','turnocontestacionesarc.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcanany
                            <a href="{{ route('turnocontestacionesarc.index') }}" class="btn btn-secondary me-2">Cancelar</a>
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
