@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('comparecenciaagenda.edit',$comparecencia,$radicacion) }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('radicacion.edit',$radicacion) }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Agendar comparecia
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        {!! BootForm::open(['model' => $comparecencia,'update' => 'comparecenciaagenda.update','id' =>'form',]) !!}
        <div class="row">
            <div class="col-md-6">
                {!! BootForm::radios('sala', 'Sala de la reunión *', [1 => 1, 2 => 2, 3 => 3], old('sala',optional($comparecencia->agenda)->sala),true,['class'=>'i-checks']); !!}
            </div>
        </div> 
        <div class="row">
            <div class="col-md-3">
                <span class="has-float-label">
                    {!! BootForm::date('fecha','Fecha: *',old('fecha_comparecencia', fecha($comparecencia->fecha_comparecencia, 'Y-m-d')),['readonly']) !!}
                </span>
            </div>
            <div class="col-md-3">
                {!! BootForm::time('hora_inicio','Hora de inicio: *',old('hora_comparecencia_inicio', $comparecencia->hora_comparecencia_inicio),['readonly']) !!}
            </div>
            <div class="col-md-3">
                {!! BootForm::time('hora_fin','Hora aproximada de término: *',old('hora_comparecencia_inicio', optional($comparecencia->agenda)->hora_fin)) !!}
            </div>
        </div>       
        <div class="row">
            <div class="col-md-6">
                {{-- @canany(['comparecenciaagenda.update']) --}}
                    <button type="submit" class="btn btn-primary">Guardar</button>
                {{-- @endcan
                @canany(['comparecenciaagenda.update']) --}}
                    <a href="{{ route('radicacion.edit',$radicacion) }}" class="btn btn-secondary me-2">Cancelar</a>
                {{-- @endcan --}}
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\ComparecenciaAgendaRequest') !!}
@endsection