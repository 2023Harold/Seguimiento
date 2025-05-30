@extends('layouts.app')
@section('breadcrums')
{{Breadcrumbs::render('recomendacionesanalisis.edit',$recomendacion,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('recomendacionesatencion.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp; Análisis de la atención
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                <div>
                    <h3 class="card-title text-primary">Atención de la recomendación </h3>
                    <div class="card-body py-7">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label>Fecha compromiso de atención: </label>
                                <span class="text-primary">
                                    {{ fecha($accion->fecha_termino_recomendacion) }}
                                </span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <label>Nombre del responsable por parte de la entidad: </label>
                                <span class="text-primary">
                                    {{$recomendacion->nombre_responsable }}
                                </span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <label>Cargo del responsable por parte de la entidad: </label>
                                <span class="text-primary">
                                    {{$recomendacion->cargo_responsable }}
                                </span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                <label>Responsable del seguimiento: </label>
                                <span class="text-primary">
                                    {{$accion->analista->name }}
                                </span>
                            </div>
                        </div>
                        <hr/>
                    </div>
                </div>
				@include('layouts.contextos._accionrecomendacion')
                <div>
                    <h3 class="card-title text-primary">Análisis</h3>
                    <div class="card-body mt-2">
                        <div class="row">
                            <div class="col-md-12">
                                {!!BootForm::textarea('analisis', false,old('analisis', $recomendacion->analisis),['rows'=>'10','readonly']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span>
                                    <a class="btn btn-primary float-end" href="{{ route('recomendacionesanexos.show',['anexo'=>$recomendacion]) }}">
                                        Anexos
                                    </a>
                                </span>
                                <span>
                                    @if (auth()->user()->siglas_rol!='ANA')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <span>
                                                    <!-- <h3 class="card-title text-primary float">Comentarios -->
                                                        <a class="btn btn-icon bi bi-chat-fill text-sistema float popupcomentario" href="{{ route('revisionesrecomendaciones.create') }}?tipo=Analisis">

                                                        </a>
                                                </h3>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="card-title text-primary">Conclusión</h3>
                        <div class="card-body mt-2">
                            <div class="row">
                                <div class="col-md-12">
                                    {!!BootForm::textarea('conclusion', false,old('conclusion', $recomendacion->conclusion),['rows'=>'10','readonly']) !!}
                                </div>
                            </div>
                            @if (auth()->user()->siglas_rol!='ANA')
                                <div class="row">
                                    <div class="col-md-12">
                                        <span>
                                            <!-- <h3 class="card-title text-primary float">Comentarios -->
                                                <a class="btn btn-icon bi bi-chat-fill text-sistema float popupcomentario" href="{{ route('revisionesrecomendaciones.create') }}?tipo=Conclusión">

                                                </a>
                                        </h3>
                                        </span>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <label>Calificación de la atención: </label>
                                    @if ($recomendacion->calificacion_sugerida=='Atendida')
                                        <span class="badge badge-light-success">Atendida</span>
                                    @endif
                                    @if ($recomendacion->calificacion_sugerida=='No Atendida')
                                        <span class="badge badge-light-danger">No Atendida</span>
                                    @endif
                                    @if ($recomendacion->calificacion_sugerida=='Parcialmente Atendida')
                                        <span class="badge badge-light-warning">Parcialmente Atendida</span>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
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
