<div>
    <h3 class="card-title text-primary">Turno</h3>  
    <div class="card-body py-7">    
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Nombre del titular del OIC: </label>
                <span class="text-primary">
                    {{ $pras->nombre_titular_oic }}
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Oficio de turno: </label>
                <span class="text-primary">
                    <a href="{{ asset($pras->oficio_remision) }}" target="_blank">
                        <?php echo htmlspecialchars_decode(iconoArchivo($pras->oficio_remision)) ?>
                    </a> 
                </span><br>
                <label>Fecha del oficio: </label>
                <span class="text-primary">
                    {{ fecha($pras->fecha_acuse_oficio) }}
                </span>
            </div>  
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Número del oficio: </label>
                <span class="text-primary">
                    {{ $pras->numero_oficio }}  
                </span>
            </div>
            {{-- <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Fecha proxima de seguimiento: </label>
                <span class="text-primary">
                    {{ fecha($pras->fecha_proxima_seguimiento) }}
                </span>
            </div>                       --}}
        </div>
        <hr/>
    </div>
</div>