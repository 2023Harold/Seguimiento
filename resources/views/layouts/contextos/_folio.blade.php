<h3 class="card-title text-primary">Folio</h3> 
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Crr: </label>
        <span class="text-primary">
            {{ $folio->folio }}
        </span>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Recepción en oficialía: </label>
        <span class="text-primary">
            {{ fecha($folio->fecha_recepcion_oficialia) }}
        </span>
    </div>  
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Fecha de recepción en la unidad de seguimiento: </label>
        <span class="text-primary">
            {{ fecha($folio->fecha_recepcion_us) }}
        </span>
    </div>
</div>

<div class = "row">
    @if(!empty($folio->numero_oficio))
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <label>Número oficio: </label>
            <span class="text-primary">
                {{ $folio->numero_oficio }}
            </span>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <label>Fecha oficio contestación:</label>
            <span class="text-primary">
                {{ fecha($folio->fecha_oficio_contestacion) }}
            </span>
        </div>    
    @endif
</div>
<div class="row">
    <div class="col-md-12"><hr></div>
</div>
<h3 class="card-title text-primary">Remitentes folio:</h3> 
<div class = "row">
    @forelse($remitentes as $remitente)
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <label>Nombre:</label>
            <span class="text-primary">
                {{$remitente->nombre_remitente}}
            </span>
            <br>
            <label>Cargo:</label>
            <span class="text-primary">
                {{$remitente->cargo_remitente}}
            </span>
        </div>
        
    @empty
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <label>No se han registrado datos en este apartado</label>
            <span class="text-primary">
            </span>
        </div>  
    @endforelse
</div>

<div class="row">
    <div class="col-md-12"><hr></div>
</div>