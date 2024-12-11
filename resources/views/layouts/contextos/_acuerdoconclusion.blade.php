<h3 class="card-title text-primary">Acuerdo Conclusión</h3> 
    <div class="row">
        <div class="col-lg-6s col-md-6 col-sm-12 col-12">          
            <label>Nombre del titular a quien se dirige:</label>
            <span class="text-sistema">
                {{ ($auditoria->acuerdoconclusion->nombre_titular) }}                
            </span>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Cargo Titular: </label>
            <span class="text-sistema">
                {{ $auditoria->acuerdoconclusion->cargo_titular }}                
            </span>
        </div>     
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Domicilio: </label>
            <span class="text-sistema">
                {{ $auditoria->acuerdoconclusion->domicilio }}                
            </span>
        </div>     
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Número de Oficio: </label>
            <span class="text-sistema">
                {{ $auditoria->acuerdoconclusion->numero_oficio }}        
            </span>
        </div>     
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Fecha de Oficio: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->acuerdoconclusion->fecha_oficio) }}                
            </span>
        </div>     
        <div class="col-lg-5 col-md-6 col-sm-12 col-12">
            <label>Acuerdo de Conclusión: </label>
            <span class="text-sistema">
                <a href="{{ asset($auditoria->acuerdoconclusion->acuerdo_conclusion) }}" target="_blank">
                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->acuerdoconclusion->acuerdo_conclusion)) ?>
                </a> 
            </span>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Fecha del Acuerdo de Conclusión: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->acuerdoconclusion->fecha_acuerdo_conclusion) }}   
            </span>
        </div>     
    </div>            
        
