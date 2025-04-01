<h3 class="card-title text-primary"> Turno acuse envío al archivo</h3> 
    <div class="row">
        <div class="col-lg-8 col-md-4 col-sm-12 col-12">          
            <label>Fecha del turno a la Unidad de Investigación:</label>
            <span class="text-sistema">
                {{ fecha($auditoria->turnoarchivo->fecha_turno_archivo) }}                
            </span>
        </div>
    </div>   
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">                   
            <h4 class="card-title text-primary">Expediente Técnico de la Auditoría</h4> 
        </div> 
        <div class="col-lg-4 col-md-4 col-sm-12 col-12"> 
            <h4 class="card-title text-primary">Expediente de Seguimiento</h4>
        </div> 
        <div class="col-lg-4 col-md-4 col-sm-12 col-12"> 
        </div> 

           <div class="col-lg-4 col-md-4 col-sm-12 col-12">
               <label>Número de legajos: </label>
               <span class="text-sistema">
                    {{ $auditoria->turnoarchivo->legajos_tecnico_archivo }}                
               </span>                
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">    
                <label>Número de legajos: </label>
                <span class="text-sistema">
                        {{ $auditoria->turnoarchivo->legajos_seg_archivo }}  
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Acuse envío al archivo: </label>
                <span class="text-sistema">
                    <a href="{{ asset($auditoria->turnoarchivo->turno_archivo) }}" target="_blank">
                        <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->turnoarchivo->turno_archivo)) ?>
                    </a> 
                </span>
            </div>            
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">    
            <label>Número de fojas: </label>
            <span class="text-sistema">
                {{ $auditoria->turnoarchivo->fojas_tecnico_archivo }}                
            </span>     
        </div>
        <div  class="col-lg-4 col-md-4 col-sm-12 col-12">    
            <label>Número de fojas: </label>
            <span class="text-sistema">
                {{ $auditoria->turnoarchivo->fojas_seg_archivo }}  
            </span>
        </div>       
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <label>Fecha de notificación: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->turnoarchivo->fecha_notificacion_archivo) }}
            </span>
        </div> 
    </div>                           
    <div class="row">
    <div class="col-md-12"><hr></div>
</div>