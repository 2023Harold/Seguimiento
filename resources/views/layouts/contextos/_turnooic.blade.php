<h3 class="card-title text-primary">Turno al Órgano Interno de Control</h3> 
    <div class="row">
        <div class="col-lg-5 col-md-6 col-sm-12 col-12">          
            <label>Fecha del Turno al órgano Interno de Control:</label>
            <span class="text-sistema">
                {{ fecha($auditoria->turnooic->fecha_turno_oic) }}                
            </span>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Número de oficio: </label>
            <span class="text-sistema">
                {{ $auditoria->turnooic->numero_turno_oic }}                
            </span>
        </div>             
    </div>            
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Nombre del titular a quien se dirige: </label>
            <span class="text-sistema">
                {{ $auditoria->turnooic->nombre_titular_oic }}                
            </span>
        </div>     
        <div>    
            <label>Cargo del titular a quien se dirige: </label>
            <span class="text-sistema">
                {{ $auditoria->turnooic->cargo_titular_oic}}                
            </span>     
        </div>
        <div>    
            <label>Domicilio: </label>
            <span class="text-sistema">
                {{ $auditoria->turnooic->domicilio_oic}}                
            </span>     
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-12">              
                <label>Acuse envío a notificar: </label>
                <span class="text-sistema">
                    <a href="{{ asset($auditoria->turnooic->turno_oic) }}" target="_blank">
                        <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->turnooic->turno_oic)) ?>
                </a>  
                    {{ $auditoria->turnooic->turno_oic }}  
                </span>
        </div>
        <div>    
                <label>Fecha del envío a notificar: </label>
                <span class="text-sistema">
                    {{ fecha($auditoria->turnooic->fecha_envio) }}  
                </span>
         </div>        
    </div>           
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-12">              
                <label>Acuse de notificación: </label>
                <span class="text-sistema">
                <a href="{{ asset($auditoria->turnooic->acuse_notificacion) }}" target="_blank">
                        <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->turnooic->acuse_notificacion)) ?>
                </a>  
                </span>
        </div>
        <div>    
                <label>Fecha de notificación: </label>
                <span class="text-sistema">
                    {{ fecha($auditoria->turnooic->fecha_notificacion) }}  
                </span>
         </div>        
    </div>               
