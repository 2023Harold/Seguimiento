@extends('layouts.app')
@section('breadcrums')
{{Breadcrumbs::render('solicitudesaclaraciondocumentos.edit',$solicitud,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('solicitudesaclaracionatencion.index') }}"><i
                        class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                        &nbsp; Listado de documentos
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                <div class="row">
                    <div class="col-md-12">
                        {!!BootForm::textarea('listado_documentos', 'Listado de documentos ',old('listado_documentos', $solicitud->listado_documentos),['rows'=>'10','disabled']) !!}
                    </div>
                </div>
                @if (auth()->user()->siglas_rol!='ANA' && ($solicitud->fase_autorizacion != "Autorizado"))
                    <div class="row">
                        <div class="col-md-12">
                            <span>
                                <a class="btn btn-icon bi bi-chat-fill text-sistema float popupcomentario" href="{{ route('revisionessolicitudes.create') }}?tipo=Listado Documentos"></a>
                            </span>
                        </div>
                    </div>
                @endif 
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
{!!JsValidator::formRequest('App\Http\Requests\RecomendacionesDocumentoRequest') !!}
    <script>
        $(document).ready(function() {
            $('.popupcomentario').colorbox({
                width:"80%",
                height:"1050px",
                maxWidth:700,
                maxHeight:"1050px",
                iframe: true,
                onClosed: function() {
                    location.reload(true);
                },
                onComplete: function () {
                 $(this).colorbox.resize({width:"80%",maxWidth:600, height:"800px", maxHeight:"800px"});
                 $(window).trigger("resize");
                }
            });
        });
    </script>
@endsection
