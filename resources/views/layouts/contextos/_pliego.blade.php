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
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Lista de documentos: </label>
                <span class="text-primary">
                    <a href="{{ route('pliegosobservaciondocumentos.index') }}" class="popupSinLocation">
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                    </a> 
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
                <label>Calificación sugerida de la atención: </label>
                @if ($pliegosobservacion->calificacion_sugerida=='Solventado')
                    <span class="badge badge-light-success">Solventado</span>
                @endif
                @if ($pliegosobservacion->calificacion_sugerida=='No Solventado')
                    <span class="badge badge-light-danger">No Solventado</span>
                @endif
                @if ($pliegosobservacion->calificacion_sugerida=='Solventado Parcialmente')
                    <span class="badge badge-light-warning">Solventado Parcialmente</span>
                @endif
            </div>             
        </div>
        @if (!empty($pliegosobservacion->calificacion_atencion))
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Calificación de la atención: </label>
                @if ($pliegosobservacion->calificacion_atencion=='Solventado')
                    <span class="badge badge-light-success">Solventado</span>
                @endif
                @if ($pliegosobservacion->calificacion_atencion=='No Solventado')
                    <span class="badge badge-light-danger">No Solventado</span>
                @endif
                @if ($pliegosobservacion->calificacion_atencion=='Solventado Parcialmente')
                    <span class="badge badge-light-warning">Solventado Parcialmente</span>
                @endif
            </div>             
        </div>
        <div class="row">
            <label>Conclusión: </label>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                {!! BootForm::textarea('conclusion', false,old('conclusion', $pliegosobservacion->conclusion),['rows'=>'3','disabled']) !!}
            </div>             
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Importe promovido: </label>
                <span class="text-primary">
                    {{ '$'.number_format( $accion->monto_aclarar, 2) }}
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Importe solventado: </label>
                <span class="text-primary">
                    {{ '$'.number_format( $pliegosobservacion->monto_solventado, 2) }}
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Importe no solventado: </label>
                <span class="text-primary">
                    {{ '$'.number_format( ($accion->monto_aclarar - $pliegosobservacion->monto_solventado), 2) }}
                </span>
            </div>            
        </div>
        @endif        
        <hr/>
    </div>
</div>