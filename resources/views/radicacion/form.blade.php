@extends('layouts.app')
@section('breadcrums')
@if ($accion=='Agregar')
    {{ Breadcrumbs::render('radicacion.create') }}
@elseif($accion=='Editar')
    {{ Breadcrumbs::render('radicacion.edit',$radicacion) }}
@endif   
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('radicacion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; {{ $accion }}
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            {!! BootForm::open(['model' => $radicacion,'store' => 'radicacion.store','update' => 'radicacion.update','id' => 'form',]) !!}
            <div class="row">
                <div class="col-md-2">
                    {!! BootForm::text('numero_acuerdo', 'Número de acuerdo: *', old('numero_acuerdo', $radicacion->numero_acuerdo)) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! archivo('oficio_acuerdo', 'Acuerdo de radicación: *', old('oficio_acuerdo', $radicacion->oficio_acuerdo)) !!}
                </div>
                <div class="col-lg-2 col-md-3">
                    {!! BootForm::date('fecha_oficio_acuerdo','Fecha del acuerdo: *',old('fecha_oficio_acuerdo', fecha($radicacion->fecha_oficio_acuerdo, 'Y-m-d')),) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {!! archivo('oficio_designacion', 'Oficio de designación: *', old('oficio_designacion', $radicacion->oficio_designacion)) !!}
                </div>
                <div class="col-lg-2 col-md-3">
                    {!! BootForm::date('fecha_oficio_designacion','Fecha del oficio: *',old('fecha_oficio_designacion', fecha($radicacion->fecha_oficio_designacion, 'Y-m-d')),) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    {{-- @if (empty($auditoria->asignacion_departamentos) || $auditoria->asignacion_departamentos != 'Si')--}}
                    @canany(['radicacion.store','radicacion.update'])                      
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    @endcan
                    <a href="{{ route('asignaciondepartamento.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    {{-- @endif --}}
                </div>
            </div>
            {!! BootForm::close() !!}
        </div>
    </div>
@endsection
@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\RadicacionRequest') !!}
@endsection
