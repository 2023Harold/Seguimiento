<div>
    <h3 class="card-title text-primary">Atención de la recomendación </h3>  
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
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Lista de documentos: </label>
                <span class="text-primary">
                    <a href="{{ route('solicitudesaclaraciondocumentos.show', $solicitud) }}" class="popupSinLocation">
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                    </a> 
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
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Calificación sugerida de la atención: </label>
                @if ($solicitud->calificacion_sugerida=='Solventada')
                    <span class="badge badge-light-success">Atendida</span>
                @endif
                @if ($solicitud->calificacion_sugerida=='No Solventada')
                    <span class="badge badge-light-danger">No Solventada</span>
                @endif
                @if ($solicitud->calificacion_sugerida=='Solventada Parcialmente')
                    <span class="badge badge-light-warning">Solventada Parcialmente</span>
                @endif
            </div>             
        </div>
        @if (!empty($solicitud->calificacion_atencion))
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Calificación de la atención: </label>
                @if ($solicitud->calificacion_atencion=='Solventada')
                    <span class="badge badge-light-success">Solventada</span>
                @endif
                @if ($solicitud->calificacion_atencion=='No Solventada')
                    <span class="badge badge-light-danger">No Solventada</span>
                @endif
                @if ($solicitud->calificacion_atencion=='Solventada Parcialmente')
                    <span class="badge badge-light-warning">Solventada Parcialmente</span>
                @endif
            </div>             
        </div>
        <div class="row">
            <label>Conclusión: </label>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                {!! BootForm::textarea('conclusion', false,old('conclusion', $solicitud->conclusion),['rows'=>'3','disabled']) !!}
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
                    {{ '$'.number_format( $solicitud->monto_solventado, 2) }}
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Importe no solventado: </label>
                <span class="text-primary">
                    {{ '$'.number_format( ($accion->monto_aclarar - $solicitud->monto_solventado), 2) }}
                </span>
            </div>            
        </div>
        @endif        
        <hr/>
    </div>
</div>