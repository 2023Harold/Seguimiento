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
        {!!BootForm::open(['model' => $AtenderComentario, 'store' => 'respuestacomentariosrecomendaciones.store', 'update' => 'respuestacomentariosrecomendaciones.update', 'id' => 'form']) !!}
        {{-- {!!BootForm::open(['model' => $comentario, 'store' => 'respuestacomentariosrecomendaciones.store', 'update' => 'respuestacomentariosrecomendaciones.update', 'id' => 'form']) !!} --}}
            <div class="row">
                
                <div class="col-md-6">
                        {!!BootForm::textarea('muestra_rev','Comentario que se esta atendiendo: ', old('analisis',$comentario->muestra_rev),['rows'=>'10', 'disabled']) !!}
                </div>
                <div class="col-md-6">
                        {!!BootForm::textarea('comentario_rev','.', old('analisis',$comentario->comentario),['rows'=>'10', 'disabled']) !!}
                </div>
            </div>
            <div class="row">
                {{-- {{ dd($recomendacion); }} --}}
                {{-- {!!BootForm::hidden('tipo', $tipo) !!} --}}                
                @if($tipo == "Analisis")
                    <div class="col-md-12">
                        {!!BootForm::textarea('analisis','Análisis: *', old('analisis',$recomendacion->analisis),['rows'=>'10']) !!}
                    </div>
                @endif
                @if($tipo == "Conclusión")
                    <div class="col-md-12">
                        {!!BootForm::textarea('conclusion','Conclusión: *', old('conclusion',$recomendacion->conclusion),['rows'=>'10']) !!}
                    </div>
                @endif  
                @if($tipo =="Listado Documentos" )
                    <div class="col-md-12">
                        {!!BootForm::textarea('listado_documentos','Listado de Documentos: *', old('listado_documentos', $recomendacion->listado_documentos),['rows'=>'10']) !!}
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
<script>
        $(document).ready(function () {
            function toggleElements() {
                const selected = $('input[name="estatus"]:checked').val();
                if (selected === 'Guardar') {
                    $('#comentario-row').hide();
                    $('#btn-enviar').hide();
                    $('#btn-guardar').show();
                    $('#comentario').val(null);
                } else if (selected === 'Enviar') {
                    $('#comentario-row').show();
                    $('#btn-enviar').show();
                    $('#btn-guardar').hide();
                }
            }

            toggleElements();
            $('input[name="estatus"]').on('change', toggleElements);
        });
    </script>
@endsection
