<h3 class="card-title text-primary">Informe Primera Etapa   {{  ucfirst($informeprimeraetapa->tipo) }}     </h3> 
    <div class="row">
        <div class="col-lg-5 col-md-6 col-sm-12 col-12">          
            <label>Fecha del oficio de notificación del informe de seguimiento:</label>
            <span class="text-sistema">
                {{ fecha($informeprimeraetapa->fecha_informe) }}                
            </span>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Número de oficio de notificación: </label>
            <span class="text-sistema">
                {{ $informeprimeraetapa->numero_informe }}                
            </span>
        </div>             
    </div>            
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Nombre del titular a quien se dirige: </label>
            <span class="text-sistema">
                {{ $informeprimeraetapa->nombre_titular_informe }}                
            </span>
        </div>     
        <div>    
            <label>Cargo del titular a quien se dirige: </label>
            <span class="text-sistema">
                {{ $informeprimeraetapa->cargo_titular_informe}}                
            </span>     
        </div>
        <div>    
            <label>Domicilio: </label>
            <span class="text-sistema">
                {{ $informeprimeraetapa->domicilio_informe}}                
            </span>     
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Número de fojas: </label>
            <span class="text-sistema">
                {{ $informeprimeraetapa->numero_fojas }}                
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-12">              
                <label>Informe de seguimiento: </label>
                <span class="text-sistema">
                    <a href="{{ asset($informeprimeraetapa->informe) }}" target="_blank">
                        <?php echo htmlspecialchars_decode(iconoArchivo($informeprimeraetapa->informe)) ?>
                </a>                  
                </span>
        </div>
    </div>    
    <div class="row">
        <div class="col-lg-5 col-md-6 col-sm-12 col-12">              
             <label>Acuse envío a notificar: </label>
             <span class="text-sistema">
                <a href="{{ asset($informeprimeraetapa->acuse_envio) }}" target="_blank">
                <?php echo htmlspecialchars_decode(iconoArchivo($informeprimeraetapa->acuse_envio)) ?>
                </a>                        
            </span>                          
        </div>    
        <div class="col-lg-4 col-md-4 col-sm-10 col-12">  
            <label>Fecha del envío a notificar: </label>
            <span class="text-sistema">
            {{ fecha($informeprimeraetapa->fecha_acuse_envio) }}  
            </span>
        </div>        
    </div>           
    <div class="row">
        <div class="col-lg-5 col-md-6 col-sm-12 col-12">              
             <label>Acuse de notificación: </label>
             <span class="text-sistema">
                <a href="{{ asset($informeprimeraetapa->acuse_notificacion) }}" target="_blank">
                <?php echo htmlspecialchars_decode(iconoArchivo($informeprimeraetapa->acuse_notificacion)) ?>
                </a>                        
            </span>                          
        </div>    
        <div class="col-lg-4 col-md-4 col-sm-10 col-12">  
            <label>Fecha de notificación: </label>
            <span class="text-sistema">
            {{ fecha($informeprimeraetapa->fecha_notificacion) }}  
            </span>
        </div>        
    </div>             
