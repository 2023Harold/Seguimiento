@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">           
            {{$accion}}
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        {!! BootForm::open(['model' => $comentario, 'store' => 'revisionessolicitudesatencion.store', 'update' => 'revisionessolicitudesatencion.update', 'id' => 'form']) !!}
      <div>           
                @if($comentario->tipo == "Analisis")
                    <div class="col-md-12">
                        {!! BootForm::textarea('muestra_rev', 'Analisis: *', old("tipo", $comentario->muestra_rev))!!}
                    </div>
                @endif
                @if($comentario->tipo == "Conclusión")
                    <div class="col-md-12">
                        {!! BootForm::textarea('muestra_rev', 'Conclusión: *', old("tipo", $comentario->muestra_rev),['rows'=>'10'])!!}                        
                    </div>
                @endif
                @if($comentario->tipo == "Normatividad")
                    <div class="col-md-12">
                        {!! BootForm::textarea('muestra_rev', 'Normatividad: *', old("tipo", $comentario->muestra_rev),['rows'=>'10'])!!}
                        
                    </div>
                @endif
      </div>          
            <div class="row">
                <div class="col-md-12">
                    {!!BootForm::textarea('comentario', 'Comentario:  *', old("comentario", ""))!!}
                <div class="col-md-12">                    

                    {!! BootForm::textarea('comentario', 'Comentario: *', old("comentario", $comentario->comentario))!!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit("Atender")
                </div>
            </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\RevisionRequest') !!}
@endsection
