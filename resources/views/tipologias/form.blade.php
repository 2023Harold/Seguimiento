@extends('layouts.appPopup')
@section('content')
{{ Breadcrumbs::render('tipologiaacciones.create',$auditoria) }}
<div class="card">
    <div class="card-header">
        <h1 class="card-title">           
            {{$accion}}
        </h1>
    </div>

    <div class="card-body">
        @include('flash::message')
        {{-- @include('layouts.contextos._auditoria') --}}

        {!!BootForm::open(['model' => $tipologia, 'store' => 'tipologiaacciones.store', 'update' => 'tipologiaacciones.update', 'id' => 'form']) !!}           
        {!!BootForm::hidden('tipo_auditoria_id', $auditoria->id) !!}
        

            <div class="row">                
                <div class="col-md-3">                    
                    {!!BootForm::text('tipologia', 'Tipología: *', old("tipologia", $tipologia->tipologia))!!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {{-- @canany(['tipologiaacciones.store','tipologiaacciones.update'])             
                    <botton type="submit" class="btn btn-primary"> Guardar </botton>    
                    @endcanany               --}}

                        @btnSubmit("Guardar")
                </div>
            </div>
        {!!BootForm::close() !!}
    </div>
</div>
@endsection

