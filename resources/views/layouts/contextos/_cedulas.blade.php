<h3 class="card-title text-primary">Cédula {{  ucfirst($cedula->cedula_tipo) }} </h3> 
    <div class="row">        
        <div class="col-lg-5 col-md-6 col-sm-12 col-12">
            <label>Cédula: </label>
            <span class="text-sistema">
                <a href="{{ asset($cedula->cedula_cargada) }}" target="_blank">
                    <?php echo htmlspecialchars_decode(iconoArchivo($cedula->cedula_cargada)) ?>
                </a> 
            </span>
        </div>        
    </div>            
        
