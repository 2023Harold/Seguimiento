<h3 class="card-title text-primary"> Turno archivo transferencia</h3> 
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <label>Inventarios de documentos:</label>
            <span class="text-sistema">
                <a href="{{ asset($auditoria->turnoarchivotransferencia->inventario_transferencia) }}" target="_blank">
                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->turnoarchivotransferencia->inventario_transferencia)) ?>
                </a> 
            </span>            
        </div>    
            <div>    
                <label>Fecha de transferencia: </label>
                <span class="text-sistema">
                {{ fecha ($auditoria->turnoarchivotransferencia->fecha_transferencia) }}  
                </span>
            </div>
    </div>        
    <div class="row">         
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">          
            <label>Tiempo de resguardo: </label>
            <span class="text-sistema">
                {{ $auditoria->turnoarchivotransferencia->tiempo_resguardo }}                
            </span>                
        </div>
        <div>    
            <label>Clave Topográfica: </label>
            <span class="text-sistema">
                {{ $auditoria->turnoarchivotransferencia->clave_topografica }}                
            </span>     
        </div>
    </div>       
<div class="row">
    <div class="col-md-12"><hr></div>
</div>