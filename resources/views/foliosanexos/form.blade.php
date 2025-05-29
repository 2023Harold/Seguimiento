@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('foliosanexos.edit',$auditoria) }} 
@endsection
@section('content')
<div class="row">
  @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
      <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('folioscrr.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                  &nbsp;Agregar Anexos         
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            {!!BootForm::open(['model' => $folio, 'store' => 'foliosanexos.store', 'update' => 'foliosanexos.update', 'id' => 'form']) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('archivo', 'Archivo: *', old('archivo', $folio->anexo_archivo)) !!}
                    </div>
                </div>               
                <div class="row">
                    <div class="col-md-6">
                        {!!BootForm::text('nombre_archivo', 'Nombre del archivo: *', old('nombre_archivo', $folio->nombre_archivo)) !!}
                    </div>
                </div>               
                <div class="row">
                    <div class="col-md-12">
                        @btnSubmit("Guardar")
                        @btnCancelar('Cancelar', route('folioscrr.index'))
                    </div>
                </div>
            {!!BootForm::close() !!}
        </div>
      </div>
    </div>
</div>
@endsection
@section('script')
    {{--!! JsValidator::formRequest('App\Http\Requests\ComparecenciaAcuseRequest') !!--}}  
@endsection
