<div>
    <h3 class="card-title text-primary">Atención del pliego de observación</h3>
    <div class="card-body py-7">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Oficios de contestación: </label>
                <span class="text-primary">
                    <a href="{{ route('pliegosobservacioncontestacion.oficiospliegosobservacion', $pliegosobservacion) }}" class="popupSinLocation">
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                    </a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Lista de documentos: </label>
                <span class="text-primary">
                    {!! BootForm::textarea('listado_documentoslb', false,old('listado_documentoslb', $pliegosobservacion->listado_documentos),['rows'=>'3','disabled']) !!}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Analisis: </label><br>
                {!! BootForm::textarea('analisis', false,old('analisis', $pliegosobservacion->analisis),['rows'=>'3','disabled']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Conclusión: </label><br>
                {!! BootForm::textarea('conclusion', false,old('analisis', $pliegosobservacion->conclusion),['rows'=>'3','disabled']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Calificación de la atención: </label>
                @if (empty($pliegosobservacion->calificacion_atencion))
                    @if ($pliegosobservacion->calificacion_sugerida=='Solventado')
                    <span class="badge badge-light-success">Solventado</span>
                    @endif
                    @if ($pliegosobservacion->calificacion_sugerida=='No Solventado')
                        <span class="badge badge-light-danger">No Solventado</span>
                    @endif
                    @if ($pliegosobservacion->calificacion_sugerida=='Solventado Parcialmente')
                        <span class="badge badge-light-warning">Solventado Parcialmente</span>
                    @endif
                @else
                    @if ($pliegosobservacion->calificacion_atencion=='Solventado')
                        <span class="badge badge-light-success">Solventado</span>
                    @endif
                    @if ($pliegosobservacion->calificacion_atencion=='No Solventado')
                        <span class="badge badge-light-danger">No Solventado</span>
                    @endif
                    @if ($pliegosobservacion->calificacion_atencion=='Solventado Parcialmente')
                        <span class="badge badge-light-warning">Solventado Parcialmente</span>
                    @endif
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label class="h6">Importe promovido: </label>
                <span class="text-primary h3">
                    {{ '$'.number_format( $accion->monto_aclarar, 2) }}
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label class="h6">Importe solventado: </label>
                <span class="text-primary h5">
                    {{ '$'.number_format( $pliegosobservacion->monto_solventado, 2) }}
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label class="h6">Importe no solventado: </label>
                <span class="text-primary h3">
                    {{ '$'.number_format( ($accion->monto_aclarar - $pliegosobservacion->monto_solventado), 2) }}
                </span>
            </div>
        </div>
        <hr/>
    </div>
</div>
