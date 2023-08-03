<div>  
    <h3 class="card-title text-primary">Acciones</h3>  
    <div class="card-body py-7">        
        @foreach ($auditoria->accionesDepartamento as $accion)
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>No. consecutivo: </label>
                <span class="text-primary">
                    {{ $accion->consecutivo }}
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Tipo de acción: </label>
                <span class="text-primary">
                    {{ $accion->tipo}}
                </span>
            </div>  
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Número de acción: </label>
                <span class="text-primary">
                    {{ $accion->numero }}  
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Monto por aclarar: </label>
                <span class="text-primary">
                    {{ '$'.number_format( $accion->monto_aclarar, 2) }}
                </span>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Departamento:</label>
                <span class="text-primary">
                    {{ $accion->departamento_asignado }} <br>
                    {{ $accion->depaasignado->name }} <br>
                    {{ $accion->depaasignado->puesto }} <br> 
                </span>
            </div>    
        </div>
        <hr/>
        @endforeach
    </div>
</div>

