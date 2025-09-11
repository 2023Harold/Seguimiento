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
        {!!BootForm::open(['model' => $AtenderComentario, 'store' => 'respuestacomentariossolicitudes.store', 'update' => 'respuestacomentariossolicitudes.update', 'id' => 'form']) !!}
        {{-- {!!BootForm::open(['model' => $comentario, 'store' => 'respuestacomentariosrecomendaciones.store', 'update' => 'respuestacomentariosrecomendaciones.update', 'id' => 'form']) !!} --}}
            <div class="row">
                
                <div class="col-md-6">
                        {!!BootForm::textarea('muestra_rev','Comentario que se esta atendiendo: ', old('muestra_rev',$comentario->muestra_rev),['rows'=>'10', 'disabled']) !!}
                </div>
                <div class="col-md-6">
                        {!!BootForm::textarea('comentario_rev','Observación: ', old('comentario_rev',$comentario->comentario),['rows'=>'10', 'disabled']) !!}
                </div>
            </div>
            <div class="row">
                @if ($accion3 == "crear")    
                    @if($tipo == "Analisis")
                        <div class="col-md-12">
                            {!!BootForm::textarea('analisis','Análisis: *', old('analisis',$comentario->universo_rev),['rows'=>'10']) !!}
                        </div>
                    @endif
                    @if($tipo == "Conclusión")
                        <div class="col-md-12">
                            {!!BootForm::textarea('conclusion','Conclusión: *', old('conclusion',$comentario->universo_rev),['rows'=>'10']) !!}
                        </div>
                    @endif  
                    @if($tipo =="Listado Documentos" )
                        <div class="col-md-12">
                            {!!BootForm::textarea('listado_documentos','Listado de Documentos: *', old('listado_documentos', $comentario->universo_rev),['rows'=>'10']) !!}
                        </div>
                    @endif
                @else
                    @if($tipo == "Analisis")
                    
                        <div class="col-md-12">
                            {!!BootForm::textarea('analisis','Análisis: *', old('analisis',$AtenderComentario->muestra_rev),['rows'=>'10']) !!}
                        </div>
                    @endif
                    @if($tipo == "Conclusión")
                    
                        <div class="col-md-12">
                            {!!BootForm::textarea('conclusion','Conclusión: *', old('conclusion',$AtenderComentario->muestra_rev),['rows'=>'10']) !!}
                        </div>
                    @endif  
                    @if($tipo =="Listado Documentos" )
                    
                        <div class="col-md-12">
                            {!!BootForm::textarea('listado_documentos','Listado de Documentos: *', old('listado_documentos', $AtenderComentario->muestra_rev),['rows'=>'10']) !!}
                        </div>
                    @endif
                @endif  
            </div>                
            <div class="row">               
                 <div class="col-md-12">
                    {!!BootForm::textarea('respuesta', 'Respuesta: *', old("respuesta", $AtenderComentario->comentario),['rows'=>'10'])!!}
                </div>
            </div>
            
            <div class="row" id="btn-guardar">
                <div class="col-md-12">
                    @btnSubmit("Guardar",$comentario)
                </div>
            </div>

        {!!BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!!JsValidator::formRequest('App\Http\Requests\RevisionRequest') !!}
{{--<script>
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
    --}}
@endsection
