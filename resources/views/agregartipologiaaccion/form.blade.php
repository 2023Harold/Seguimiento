@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">           
            {{-- {{$accion}} --}}
            Agregar Tipología
        </h1>
    </div>
{{-- {{ dd($tipologia); }} --}}
    <div class="card-body">
        @include('flash::message')
        {{-- @include('layouts.contextos._auditoria') --}}
        {!!BootForm::open(['model' => $tipologia, 'store' => 'agregartipologiaaccion.store','update' => 'agregartipologiaaccion.update','id' => 'form']) !!}           
       {!!BootForm::hidden('accion_id', $accion->id) !!} 
            <div class="row">                
                <div class="col-md-12">                    
                    {!!BootForm::select('tipologia_id', 'Tipología: ', $tipologias->toArray(), old('tipologia_id',$tipologia->tipologia_id),['data-control'=>'select2', 'class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                @can('agregartipologiaaccion.update')
                        <button type="submit" id='btn-guardar' class="btn btn-primary">Guardar</button>
                        {{-- @btnSubmit("Guardar") --}}
                @endcan                       
                </div>
            </div>
        {!!BootForm::close() !!}
    </div>
</div>
@endsection


