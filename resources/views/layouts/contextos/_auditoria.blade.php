<h3 class="card-title text-primary">Auditoría</h3> 
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>No. de auditoría: </label>
        <span class="text-primary">
            {{ $auditoria->numero_auditoria }}
        </span>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Entidad fiscalizable: </label>
        <span class="text-primary">
            {{ mb_convert_encoding(mb_convert_case(strtolower($auditoria->entidad_fiscalizable), MB_CASE_TITLE), "UTF-8"); }}
        </span>
    </div>  
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Acto de fiscalización: </label>
        <span class="text-primary">
            {{ $auditoria->acto_fiscalizacion }}
        </span>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Acciones promovidas: </label>
        <span class="text-primary">
            {{ count($auditoria->acciones) }}
        </span>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Monto por aclarar:</label>
        <span class="text-primary">
            {{ '$'.number_format( $auditoria->total(), 2) }}
        </span>
    </div>    
</div>
<div class="row">
    <div class="col-md-12"><hr></div>
</div>