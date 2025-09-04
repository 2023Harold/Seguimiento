@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('solicitudesaclaracionanalisis.show',$solicitud,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('solicitudesaclaracionatencion.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp; Análisis de la atención
                </h1>
                
            </div>
            
            <div class="card-body">
                @include('flash::message')
                <h3 class="card-title text-primary">Análisis</h3>
                <div class="card-body mt-2">
                    <div class="row">
                        <div class="col-md-12">
                            {!!BootForm::textarea('analisis', false,old('analisis', $solicitud->analisis),['rows'=>'10','readonly']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span>
                                <a class="btn btn-primary float-end" href="{{ route('solicitudesaclaracionanexos.show',$solicitud) }}">
                                    Anexos
                                </a>
                            </span>
                            <span>
                                @if (auth()->user()->siglas_rol!='ANA')
                                    <div class="col-md-11">
                                        <span>
                                            <a class="btn btn-icon bi bi-chat-fill text-sistema float popupcomentario" href="{{ route('revisionessolicitudes.create') }}?tipo=Analisis">
                                            </a>
                                        </span>
                                    </div>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>                
                <h3 class="card-title text-primary">Conclusión</h3>
                <div class="card-body mt-2">
                    <div class="row">
                        <div class="col-md-12">
                            {!!BootForm::textarea('conclusion', false,old('conclusion', $solicitud->conclusion),['rows'=>'10','readonly']) !!}
                        </div>
                    </div>

                    @if (auth()->user()->siglas_rol!='ANA')
                        <div class="col-md-11">
                            <span>
                                <a class="btn btn-icon bi bi-chat-fill text-sistema float popupcomentario" href="{{ route('revisionessolicitudes.create') }}?tipo=Conclusión">
                                </a>
                            </span>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label>Calificación de la atención: </label>
                            @if ($solicitud->calificacion_sugerida=='Solventada')
                                <span class="badge badge-light-success">Solventada</span>
                            @endif
                            @if ($solicitud->calificacion_sugerida=='No Solventada')
                                <span class="badge badge-light-danger">No Solventada</span>
                            @endif
                            @if ($solicitud->calificacion_sugerida=='Solventada Parcialmente')
                                <span class="badge badge-light-warning">Solventada Parcialmente</span>
                            @endif
                        </div>
                    </div>   
                    @php
                        $mostrarDivPromocion = ((!empty(old('calificacion_sugerida', $solicitud->calificacion_sugerida))&&old('calificacion_sugerida', $solicitud->calificacion_sugerida)!='Solventada')?'block':'none');
                    @endphp                         
                    <div id="div_promocion" style="display:{!! $mostrarDivPromocion !!}">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Promoción: </label>
                                <span class="text-primary">
                                    {{ (!empty($solicitud->promocionaccion->descripcion)?$solicitud->promocionaccion->descripcion:"")}}
                                </span>
                            </div>
                        </div>
                        @php
                            $mostrarDivMontoPromo = ((!empty(old('promocion', $solicitud->promocion))&&old('promocion', $solicitud->promocion)!='2')?'block':'none');                           
                        @endphp
                        <div id="div_monto_promocion" style="display:{!! $mostrarDivMontoPromo !!}">
                            <div class="row" id="id_monto_promocion">
                                <div class="col-md-12">
                                    <label>Monto de la promoción: </label>
                                    <span class="text-primary">
                                        {{ (!empty($solicitud->monto_promocion)?'$'.number_format( $solicitud->monto_promocion, 2):"")}}
                                    </span>                                   
                                </div>
                            </div>
                        </div>                                
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Importe promovido</th>
                                        <th>Importe solventado</th>
                                        <th>Importe no solventado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: right!important;">{{ '$'.number_format( $accion->monto_aclarar, 2) }}</td>
                                        <td style="text-align: right!important;">{{ '$'.number_format( $solicitud->monto_solventado, 2) }}</td>
                                        <td style="text-align: right!important;">{{ '$'.number_format( ($accion->monto_aclarar - $solicitud->monto_solventado), 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
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
                width:"65%",
                height:"650px",
                maxWidth:400,
                maxHeight:"650px",
                iframe: true,
                onClosed: function() {
                    location.reload(true);
                },
                onComplete: function () {
                 $(this).colorbox.resize({width:"65%",maxWidth:400, height:"650px", maxHeight:"650px"});
                 $(window).trigger("resize");
                }
            });
        });
    </script>
@endsection
