@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('pliegosobservacionanalisis.show',$pliegosobservacion,$auditoria) }}
@endsection
@section('content')
<div class="row">
  @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
      <div class="card">
          <div class="card-header">
              <h1 class="card-title">
                  <a href="{{ route('pliegosobservacionatencion.index') }}"><i
                          class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                  &nbsp; Análisis de la atención
              </h1>
          </div>
          <div class="card-body">
              @include('flash::message')
              <div>
                  <h3 class="card-title text-primary">Análisis</h3>
                  <div class="card-body mt-2">
                      <div class="row">
                          <div class="col-md-12">
                              {!! BootForm::textarea('analisis', false,old('analisis', $pliegosobservacion->analisis),['rows'=>'10','readonly']) !!}
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                            <span>
                                <a class="btn btn-primary float-end" href="{{ route('pliegosobservacionanexos.show',['anexo'=>$pliegosobservacion]) }}">
                                    Anexos
                                </a>
                            </span>
                        </div>
                    </div>
                  </div>
              </div>
                  <div>
                    <h3 class="card-title text-primary">Conclusión</h3>
                    <div class="card-body mt-2">
                        <div class="row">
                            <div class="col-md-12">
                                {!! BootForm::textarea('conclusion', false,old('conclusion', $pliegosobservacion->conclusion),['rows'=>'10','readonly']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label>Calificación de la atención: </label>
                                @if ($pliegosobservacion->calificacion_sugerida=='Solventado')
                                    <span class="badge badge-light-success">Solventado</span>
                                @endif
                                @if ($pliegosobservacion->calificacion_sugerida=='No Solventado')
                                    <span class="badge badge-light-danger">No Solventado</span>
                                @endif
                                @if($pliegosobservacion->calificacion_sugerida=='Solventado Parcialmente')
                                    <span class="badge badge-light-warning">Solventado Parcialmente</span>
                                @endif
                            </div>
                        </div>
                        @php
                            $mostrarDivPromocion = ((!empty(old('calificacion_sugerida', $pliegosobservacion->calificacion_sugerida))&&old('calificacion_sugerida', $pliegosobservacion->calificacion_sugerida)!='Solventado')?'block':'none');
                        @endphp 
                        <div id="div_promocion" style="display:{!! $mostrarDivPromocion !!}">
                            <div class="row">
                                <div class="col-md-12">                                
                                    <label>Promoción: </label>
                                    <span class="text-primary">
                                        {{ (!empty($pliegosobservacion->promocionaccion->descripcion)?$pliegosobservacion->promocionaccion->descripcion:"")}}
                                    </span>
                                </div>
                            </div>
                            @php
                            $mostrarDivMontoPromo = ((!empty(old('promocion', $pliegosobservacion->promocion))&&old('promocion', $pliegosobservacion->promocion)!='2')?'block':'none');                           
                            @endphp
                            <div class="row" id="div_monto_promocion" style="display:{!! $mostrarDivMontoPromo !!}">
                                <div class="col-md-12">                                
                                    <label>Monto de la promoción: </label>
                                    <span class="text-primary">
                                        {{ (!empty($pliegosobservacion->monto_promocion)?'$'.number_format( $pliegosobservacion->monto_promocion, 2):"")}}
                                    </span> 
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
                                            <td style="text-align: right!important;">{{ '$'.number_format( $pliegosobservacion->monto_solventado, 2) }}</td>
                                            <td style="text-align: right!important;">{{ '$'.number_format( ($accion->monto_aclarar - $pliegosobservacion->monto_solventado), 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>                      
                    
                        @if (auth()->user()->siglas_rol!='ANA')
                        <div class="row">
                            <div class="col-md-12">
                                <span>
                                    <h3 class="card-title text-primary float">Comentarios
                                    <a class="btn btn-primary float-end popupcomentario" href="{{ route('revisionespliegos.create') }}">
                                        Agregar comentario
                                    </a>
                                </h3>
                                </span>
                            </div>
                        </div>
                        @endif
                        

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
