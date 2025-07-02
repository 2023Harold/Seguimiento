@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">           
            {{-- {{$accion}} --}}
            Agregar
        </h1>
    </div>
{{-- {{ dd($tipologia); }} --}}
    <div class="card-body">
        @include('flash::message')
        {{-- @include('layouts.contextos._auditoria') --}}
        {!!BootForm::open(['model' => $accion, 'update' => 'agregartipologiaaccion.update', 'id' => 'form']) !!}           
        {{-- {!!BootForm::hidden('tipo_auditoria_id', $auditoria->id) !!} --}}
        

            <div class="row">                
                <div class="col-md-12">                    
                    {!!BootForm::text('tipologia', 'Tipología: *', old("tipologia", $accion->tipologia))!!}
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


