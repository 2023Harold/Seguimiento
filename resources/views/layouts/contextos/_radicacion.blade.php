<h3 class="card-title text-primary">Radicación</h3> 
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>No. de auditoría: </label>
        <span class="text-primary">
            {{ $radicacion->auditoria->numero_auditoria }}
        </span>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Entidad fiscalizable: </label>
        <span class="text-primary">
            {{ mb_convert_encoding(mb_convert_case(strtolower($radicacion->auditoria->entidad_fiscalizable), MB_CASE_TITLE), "UTF-8"); }}
        </span>
    </div>  
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Acto de fiscalización: </label>
        <span class="text-primary">
            {{ $radicacion->auditoria->acto_fiscalizacion }}
        </span>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">                         
        <label>Número de acuerdo: </label>
        <span class="text-primary">
            {{ $radicacion->numero_acuerdo }}
        </span>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Acuerdo de radicación: </label>
        <span class="text-primary">
            <a href="{{ asset($radicacion->oficio_acuerdo) }}" target="_blank">
                <?php echo htmlspecialchars_decode(iconoArchivo($radicacion->oficio_acuerdo)) ?>
            </a>           
        </span>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Acuse del oficio de designación: </label>
        <span class="text-primary">
            <a href="{{ asset($radicacion->oficio_designacion) }}" target="_blank">
                <?php echo htmlspecialchars_decode(iconoArchivo($radicacion->oficio_designacion)) ?>
            </a> <br>      
        </span>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">                         
        &nbsp;
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">                         
        <label>Fecha del acuerdo de radicación: </label>
        <span class="text-primary">
            {{ fecha($radicacion->fecha_oficio_acuerdo) }}
        </span>
    </div>  
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">                         
        <label>Fecha del oficio de designación: </label>
        <span class="text-primary">
            {{ fecha($radicacion->fecha_oficio_acuerdo) }}
        </span>
    </div>      
</div>
<div class="row">
    <div class="col-md-12"><hr></div>
</div>