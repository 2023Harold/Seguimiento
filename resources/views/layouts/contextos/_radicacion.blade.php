<h3 class="card-title text-primary">Radicación</h3> 
<div class="col-lg-6 col-md-6 col-sm-12 col-12">
    <label>Número de memorándum de recepción del expediente: </label>
    <span class="text-primary">
        {{ optional($auditoria->radicacion)->num_memo_recepcion_expediente }}
    </span>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-12">
    <label>Fecha de recepción del expediente turnado: </label>
    <span class="text-primary">
        {{ fecha(optional($auditoria->radicacion)->fecha_expediente_turnado)}}
    </span>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-12">
    <label>Número de expediente US: </label>
    <span class="text-primary">
        {{ optional($auditoria->radicacion)->numero_expediente}}
    </span>
</div>       
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">                         
        <label>Número de oficio de notificación del informe de auditoría: </label>
        <span class="text-primary">
            {{ optional($auditoria->radicacion)->numero_acuerdo }}
        </span>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <label>Fecha radicación: </label>
        <span class="text-primary">
            {{ fecha(optional($auditoria->radicacion)->fecha_notificacion)}}
        </span>
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">                         
        <label>Oficio Número: </label>
        <span class="text-primary">
            {{ optional($auditoria->radicacion)->oficio_acuerdo }}
        </span>
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">                         
        <label>Acta de reunión de resultados finales y cierre de auditoría: </label>
        <span class="text-primary">
            {{ optional($auditoria->radicacion)->fecha_cierre_auditoria }}
        </span>
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <label>Fecha acta: </label>
        <span class="text-primary">
            {{-- Aqui va la fecha del  Acta de reunión de resultados finales y cierre de auditoría--}}
            {{ fecha(optional($auditoria->radicacion)->fecha_acta)}}
        </span>
    </div>        

	 
@if(!empty($auditoria->comparecencia))	
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <label>Nombre del titular a quien se dirige la comparecencia:</label>
        <span class="text-sistema">
            {{ optional($auditoria->comparecencia)->nombre_titular }}                
        </span>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <label>Cargo del titular a quien se dirige la comparecencia: </label>
        <span class="text-sistema">
            {{ optional($auditoria->comparecencia)->cargo_titular }}                
        </span>
    </div>    
    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
        <label>Fecha y hora de la comparecencia: </label>
        <span class="text-sistema">
            {{ fecha(optional($auditoria->comparecencia)->fecha_comparecencia) . ' ' . date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_inicio)) . (empty($auditoria->comparecencia->hora_comparecencia_termino)?"":"-".date("g:i a",strtotime($auditoria->comparecencia->hora_comparecencia_termino))) }}
        </span>
    </div>
    @if (!empty($auditoria->comparecencia->agenda->hora_fin))
        <div class="col-lg-2 col-md-2 col-sm-12 col-12">
            <label>Sala: </label>
            <span class="text-sistema">
                {{ $auditoria->comparecencia->agenda->sala}}
            </span>
        </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <label>Hora aproximada de término: </label>
            <span class="text-sistema">
                {{ date("g:i a",strtotime($auditoria->comparecencia->agenda->hora_fin))}}
            </span>
        </div>    
        
    @endif
    @if ($auditoria->acto_fiscalizacion!='Desempeño')
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <label>Inicio de la etapa de aclaración: </label>
        <span class="text-sistema">
            {{ fecha($auditoria->comparecencia->fecha_inicio_aclaracion)  }}
        </span>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <label>Término de la etapa de aclaración: </label>
        <span class="text-sistema">
            {{ fecha($auditoria->comparecencia->fecha_termino_aclaracion) }}
        </span>
    </div>
    @endif
    @if ($auditoria->acto_fiscalizacion=='Legalidad' || $auditoria->acto_fiscalizacion=='Desempeño')
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <label>Inicio del proceso de atención: </label>
        <span class="text-sistema">
            {{ fecha($auditoria->comparecencia->fecha_inicio_proceso)  }}
        </span>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
        <label>Término del proceso de atención: </label>
        <span class="text-sistema">
            {{ fecha($auditoria->comparecencia->fecha_termino_proceso) }}
        </span>
    </div>
    @endif
	@endif
	
</div>
<div class="row">
    <div class="col-md-12"><hr></div>
</div>