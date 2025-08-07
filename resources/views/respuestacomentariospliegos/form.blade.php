@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">           
            Modificación
        </h1>
    </div>   
    <div class="card-body">
        
        @include('flash::message')
        {!!BootForm::open(['model' => $pliegos, 'store' => 'respuestacomentariospliegos.store', 'update' => 'respuestacomentariospliegos.update', 'id' => 'form']) !!}
        {{-- {!!BootForm::open(['model' => $comentario, 'store' => 'respuestacomentariosrecomendaciones.store', 'update' => 'respuestacomentariosrecomendaciones.update', 'id' => 'form']) !!} --}}
            <div class="row">
                {{-- {{ dd($recomendacion); }} --}}
                {{-- {!!BootForm::hidden('tipo', $tipo) !!} --}}                
                @if($tipo == "Analisis")
                    <div class="col-md-12">
                        {!!BootForm::textarea('analisis','Análisis: *', old('analisis',$pliegos->analisis),['rows'=>'10']) !!}
                    </div>
                @endif
                @if($tipo == "Conclusión")
                    <div class="col-md-12">
                        {!!BootForm::textarea('conclusion','Conclusión: *', old('conclusion',$pliegos->conclusion),['rows'=>'10']) !!}
                    </div>
                @endif  
                @if($tipo =="Listado Documentos" )
                    <div class="col-md-12">
                        {!!BootForm::textarea('listado_documentos','Listado de Documentos: *', old('listado_documentos', $pliegos->listado_documentos),['rows'=>'10']) !!}
                    </div>
                @endif
            </div>                
            <div class="row">                
                 <div class="col-md-12">
                    
                    {!!BootForm::textarea('comentario', 'Comentario:  *', old("comentario", ""))!!}
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
