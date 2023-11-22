<div>
    <h3 class="card-title text-primary">Atención de la solicitud de aclaración</h3>
    <div class="card-body py-7">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Oficios de contestación: </label>
                <span class="text-primary">
                    <a href="{{ route('solicitudescontestaciones.oficiossolicitud', $solicitud) }}" class="popupSinLocation">
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                    </a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Lista de documentos: </label>
                <span class="text-primary">
                    {!! BootForm::textarea('listado_documentoslb', false,old('listado_documentoslb', $solicitud->listado_documentos),['rows'=>'3','disabled']) !!}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Analisis: </label><br>
                {!! BootForm::textarea('analisis', false,old('analisis', $solicitud->analisis),['rows'=>'3','disabled']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Anexos: </label>
                <span class="text-primary">
                    <a href="{{ route('solicitudes.anexos', $solicitud) }}" class="popupSinLocation">
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                    </a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Calificación de la atención: </label>
                @if (empty($solicitud->calificacion_atencion))
                    @if ($solicitud->calificacion_sugerida=='Solventada')
                        <span class="badge badge-light-success">Atendida</span>
                    @endif
                    @if ($solicitud->calificacion_sugerida=='No Solventada')
                        <span class="badge badge-light-danger">No Solventada</span>
                    @endif
                    @if ($solicitud->calificacion_sugerida=='Solventada Parcialmente')
                        <span class="badge badge-light-warning">Solventada Parcialmente</span>
                    @endif
                @else
                    @if ($solicitud->calificacion_atencion=='Solventada')
                        <span class="badge badge-light-success">Solventada</span>
                    @endif
                    @if ($solicitud->calificacion_atencion=='No Solventada')
                        <span class="badge badge-light-danger">No Solventada</span>
                    @endif
                    @if ($solicitud->calificacion_atencion=='Solventada Parcialmente')
                        <span class="badge badge-light-warning">Solventada Parcialmente</span>
                    @endif
                @endif
            </div>
        </div>
        <div class="row">
            <label>Conclusión: </label>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                {!! BootForm::textarea('conclusion', false,old('conclusion', $solicitud->conclusion),['rows'=>'3','disabled']) !!}
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
        <hr/>
    </div>
</div>
