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
                            {!! archivo('oficio_contestacion', 'Oficio de contestación de la recomendación: *', old('oficio_contestacion', $contestacion->oficio_contestacion)) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        {!! BootForm::select('foliocrr_id', 'Folio de correspondencia: *', $folios->toArray() , old('foliocrr_id'), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
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
