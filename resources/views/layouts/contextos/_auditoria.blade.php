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
            {{ mb_convert_encoding(mb_convert_case(strtolower($auditoria->entidad_fiscalizable), MB_CASE_TITLE), "UTF-8") }}
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
        </span><br>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Monto por aclarar:</label>
        <span class="text-primary">
            {{ '$'.number_format( $auditoria->total(), 2) }}
        </span>
        @if (!empty($sumaMontoSolventadoPo))
            <br>
            <label>Monto aclarado:</label>
            <span class="text-primary" >{{'$'.number_format($sumaMontoSolventadoPo, 2) }}</span>
            <br>
            <label>Monto no aclarado:</label>
            <span class="text-primary" >{{'$'.number_format($restaMontoPo, 2) }}</span>
        @elseif (!empty($sumaMontoSolventadoRec))
            <br>
            <label>Monto aclarado:</label>
            <span class="text-primary" >{{'$'.number_format($sumaMontoSolventadoRec, 2) }}</span>
            <br>
            <label>Monto no aclarado:</label>
            <span class="text-primary" >{{'$'.number_format($restaMontoRec, 2) }}</span>
        @elseif (!empty($sumaMontoSolventadoSolAc))
            <br>
            <label>Monto aclarado:</label>
            <span class="text-primary" >{{'$'.number_format($sumaMontoSolventadoSolAc, 2) }}</span>
            <br>
            <label>Monto no aclarado:</label>
            <span class="text-primary" >{{'$'.number_format($restaMontoSolAc, 2) }}</span>
        @endif
		<br>
    </div> 
    <br>
    @if (getSession('cp')!=2022)
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <label>Número de orden de auditoria: </label>
            <span class="text-primary">
                {{ $auditoria->numero_orden }}
            </span>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <label>Periodo de revisión: </label>
            <span class="text-primary">
                {{ $auditoria->periodo_revision }}
            </span>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12"></div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12"></div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <label>Informe de auditoría: </label>
            <span class="text-primary">
                <a href="{{ asset($auditoria->informe_auditoria) }}" target="_blank">
                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->informe_auditoria)) ?>
                </a>
                
            </span><br>
            <label>Numero fojas: </label>
            <span class="text-primary">
                {{ $auditoria->fojas_utiles}}
            </span>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <label>Datos del servidor público al que se le notificó el informe de auditoría:</label>
            <label>Nombre: </label>
            <span class="text-primary">
                {{ $auditoria->nombre_informe_au }}
            </span><br>
            <label>Cargo: </label>
            <span class="text-primary">
                {{ $auditoria->cargo_informe_au}}
            </span><br>
            <label>Administración: </label>
            <span class="text-primary">
                {{ $auditoria->administracion_informe_au}}
            </span>
        </div>
        <br>
        @endif
</div>
<div class="row">
    <div class="col-md-12"><hr></div>
</div>