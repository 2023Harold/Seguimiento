@extends('layouts.app')
@section('breadcrums')
    @if (empty($contestacion->oficio_contestacion))
    {{ Breadcrumbs::render('pliegosobservacionatencioncontestacion.create',$auditoria) }}
    @else
    {{ Breadcrumbs::render('pliegosobservacionatencioncontestacion.edit',$contestacion,$auditoria) }}
    @endif
@endsection
@section('content')
<div class="row">
  @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
      <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('pliegosobservacionatencioncontestacion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                @if (empty($contestacion->oficio_contestacion))
                    &nbsp; Agregar
                @else
                    &nbsp; Editar
                @endif
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            {!! BootForm::open(['model' => $contestacion, 'store' => 'pliegosobservacionatencioncontestacion.store', 'update' => 'pliegosobservacionatencioncontestacion.update', 'id' => 'form']) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('oficio_contestacion', 'Oficio de contestación de los pliegos de observación: *', old('oficio_contestacion', $contestacion->oficio_contestacion)) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_oficio_contestacion', 'Fecha del oficio de contestación: *', old('fecha_oficio_contestacion', fecha($contestacion->fecha_oficio_contestacion, 'Y-m-d'))); !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::text('numero_oficio', 'Número del oficio: *', old('numero_oficio', $contestacion->numero_oficio)); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::text('nombre_remitente', 'Remitente: *', old('nombre_remitente', fecha($contestacion->nombre_remitente, 'Y-m-d'))); !!}
                    </div>
                    <div class="col-md-6">
                        {!! BootForm::text('cargo_remitente', 'Cargo del remitente: *', old('cargo_remitente', $contestacion->cargo_remitente)); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_recepcion_oficialia', 'Fecha de recepción en oficialía: *', old('fecha_recepcion_oficialia', fecha($contestacion->fecha_recepcion_oficialia, 'Y-m-d'))); !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::number('folio_correspondencia', 'Folio de correspondencia: *', old('folio_correspondencia', $contestacion->folio_correspondencia)); !!}
                    </div>
                </div>
                <div class="row">
                    {!! BootForm::label('lbfecha','Fecha de recepción en la unidad de seguimiento: *') !!}
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_recepcion_seguimiento', false, old('fecha_recepcion_seguimiento', fecha($contestacion->fecha_recepcion_seguimiento, 'Y-m-d'))); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @btnSubmit("Guardar")
                        @btnCancelar('Cancelar', route('pliegosobservacionatencioncontestacion.index'))
                    </div>
                </div>
            {!! BootForm::close() !!}
        </div>
      </div>
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\PliegosObservacionContestacionRequest') !!}
@endsection
