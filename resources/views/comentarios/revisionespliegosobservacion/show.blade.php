@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">           
            Comentario
        </h1>
    </div>
    <div class="card-body">  
        @if(!empty($comentario->muestra_rev))   
            <div class="row">
                <div class="col-md-12">
                    {{$comentario->tipo}}
                    {!!BootForm::textarea('muestra_rev', false, old("muestra_rev", $comentario->muestra_rev),['disabled'])!!}
                </div>
            </div> 
        @endif        
        <div class="row">
            <div class="col-md-12">
                Comentario:
                {!! BootForm::textarea('comentario', false, old("comentario", $comentario->comentario),['disabled'])!!}
            </div>
        </div>           
    </div>
</div>
@endsection