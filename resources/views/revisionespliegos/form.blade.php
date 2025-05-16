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
        {!!BootForm::open(['model' => $comentario, 'store' => 'revisionespliegos.store', 'update' => 'revisionespliegos.update', 'id' => 'form']) !!}
            <div class="row">
                {!!BootForm::hidden('tipo', $tipo) !!}
                @if($tipo == "Analisis")
                    <div class="col-md-12">
                        {!!BootForm::textarea('muestra_rev','Analisis: *', old('muestra_rev', $acciones->pliegosobservacion->analisis),['rows'=>'10']) !!}
                    </div>
                @endif
                @if($tipo == "Conclusión")
                    <div class="col-md-12">
                        {!!BootForm::textarea('muestra_rev','Conclusión: *', old('muestra_rev', $acciones->pliegosobservacion->conclusion),['rows'=>'10']) !!}
                    </div>
                @endif
                @if($tipo == "Normatividad")
                    <div class="col-md-12">
                        {!!BootForm::textarea('muestra_rev','Normatividad: *', old('muestra_rev', $acciones->normativa_infringida),['rows'=>'10']) !!}
                    </div>
                @endif
                
            </div>
            <div class="row">
                <div class="col-md-12">
                    
                    {!!BootForm::textarea('comentario', 'Comentario: *', old("comentario", $comentario->comentario))!!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @btnSubmit("Guardar")
                </div>
            </div>
        {!!BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!!JsValidator::formRequest('App\Http\Requests\RevisionRequest') !!}
@endsection
