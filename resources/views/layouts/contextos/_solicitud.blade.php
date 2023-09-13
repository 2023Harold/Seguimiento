<div>
    <h3 class="card-title text-primary">Solicitud</h3>  
    <div class="card-body py-7">    
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Oficio de la contestación de la solicitud de aclaración: </label>
                <span class="text-primary">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ asset($solicitud->oficio_atencion) }}" target="_blank">
                        <?php echo htmlspecialchars_decode(iconoArchivo($solicitud->oficio_atencion)) ?>
                    </a> 
                </span>
            </div>                              
            <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                <label>Fecha del oficio de la contestación: </label>
                <span class="text-primary">
                    {{ fecha($solicitud->fecha_oficio_atencion)}}  
                </span>
            </div>                              
        {{-- </div>
        <div class="row"> --}}
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Listado de socumentos: </label>
                <a href="{{ route('solicitudesaclaracioncalificacion.show', $solicitud) }}" class="popupSinLocation">
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                </a> 
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Calificación: </label>
                @if ($solicitud->cumple=='Atendida')
                    <span class="badge badge-light-success">Atendida</span>
                @endif
                @if ($solicitud->cumple=='No Atendida')
                    <span class="badge badge-light-danger">No Atendida</span>
                @endif
                @if ($solicitud->cumple=='Parcialmente Atendida')
                    <span class="badge badge-light-warning">Parcialmente Atendida</span>
                @endif
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Monto solventado: </label>
                <span class="text-primary">
                    {{ '$'.number_format( $solicitud->monto_solventado, 2) }}
                </span>
            </div>                    
        </div>
        <hr/>
    </div>
</div>