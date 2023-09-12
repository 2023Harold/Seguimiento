<div>
    <h3 class="card-title text-primary">Atención de la recomendación </h3>  
    <div class="card-body py-7">    
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Fecha compromiso de atención: </label>
                <span class="text-primary">
                    {{ fecha($accion->fecha_termino_recomendacion) }}
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Nombre del responsable por parte de la entidad: </label>
                <span class="text-primary">
                    {{$recomendacion->nombre_responsable }}
                </span>
            </div>  
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Cargo del responsable por parte de la entidad: </label>
                <span class="text-primary">
                    {{$recomendacion->cargo_responsable }} 
                </span>
            </div>                                   
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Responsable del seguimiento: </label>
                <span class="text-primary">
                    {{$accion->analista->name }}
                </span>
            </div> 
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Oficio de la contestación de la recomendación: </label>
                <span class="text-primary">
                    <a href="{{ asset($recomendacion->oficio_contestacion) }}" target="_blank">
                        <?php echo htmlspecialchars_decode(iconoArchivo($recomendacion->oficio_contestacion)) ?>
                    </a> <br>      
                </span>
            </div>
        </div>
        @if (!empty($recomendacion->analisis))
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Analisis: </label><br>
                <span class="text-primary text-justify">
                    {{$recomendacion->analisis }}
                </span>
            </div>             
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Calificación de la atención: </label>
                @if ($recomendacion->calificacion_atencion=='Atendida')
                    <span class="badge badge-light-success">Atendida</span>
                @endif
                @if ($recomendacion->calificacion_atencion=='No Atendida')
                    <span class="badge badge-light-danger">No Atendida</span>
                @endif
            </div>             
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Conclusión: </label><br>
                <span class="text-primary text-justify">
                    {{$recomendacion->conclusion }}
                </span>
            </div>             
        </div>
        @endif        
        <hr/>
    </div>
</div>