<h3 class="card-title text-primary">Turno a la Unidad de Investigación</h3> 
    <div class="row">
        <div class="col-lg-5 col-md-6 col-sm-12 col-12">          
            <label>Fecha del turno a la Unidad de Investigación:</label>
            <span class="text-sistema">
                {{ fecha($auditoria->turnoui->fecha_turno_oi) }}                
            </span>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Número de oficio: </label>
            <span class="text-sistema">
                {{ $auditoria->turnoui->numero_turno_ui }}                
            </span>
        </div>     
    </div>            
    <div class="row">
        <div class="col-lg-5 col-md-6 col-sm-12 col-12">
            <h4 class="card-title text-primary">Expediente Técnico de la Auditoría</h4> 
            <label>Número de legajos: </label>
            <span class="text-sistema">
                {{ $auditoria->turnoui->legajos_tecnico }}                
            </span>                
        </div>
        <div>    
            <label>Número de fojas: </label>
            <span class="text-sistema">
                {{ $auditoria->turnoui->fojas_tecnico }}                
            </span>     
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
            <h4 class="card-title text-primary">Expediente de Seguimiento</h4>
            <div>    
                <label>Número de legajos: </label>
                <span class="text-sistema">
                    {{ $auditoria->turnoui->legajos_seg }}  
                </span>
            </div>
            <div>    
                <label>Número de fojas: </label>
                <span class="text-sistema">
                    {{ $auditoria->turnoui->fojas_seg }}  
                </span>
            </div>
        </div>           
    <div class="row">        
        <div class="col-lg-5 col-md-6 col-sm-12 col-12">
            <label>Comprobante de recepción depto. de notificaciones: </label>
            <span class="text-sistema">
                <a href="{{ asset($auditoria->turnoui->turno_ui) }}" target="_blank">
                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->turnoui->turno_ui)) ?>
                </a> 
            </span>
        </div>
        <div class="col-lg-7 col-md-6 col-sm-12 col-12">
            <label>Fecha de notificación: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->turnoui->fecha_notificacion_ui) }}
            </span>
        </div>         
