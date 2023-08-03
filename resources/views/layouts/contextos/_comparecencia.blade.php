<h3 class="card-title text-primary">Comparecencia</h3> 
@if (!empty($auditoria->comparecencia))
    @if (str_contains(Route::current()->getName(), 'compare'))
    <div class="row">
        <div class="col-lg-5 col-md-6 col-sm-12 col-12">
            <label>Nombre del titular a quien se dirige la comparecencia:</label>
            <span class="text-sistema">
                {{ $auditoria->comparecencia->nombre_titular }}                
            </span>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Cargo del titular a quien se dirige la comparecencia: </label>
            <span class="text-sistema">
                {{ $auditoria->comparecencia->cargo_titular }}                
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg- col-md-6 col-sm-12 col-12">
            <label>Oficio de notificación de la comparecencia: </label>
            <span class="text-sistema">
                <a href="{{ asset($auditoria->comparecencia->oficio_comparecencia) }}" target="_blank">
                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_comparecencia)) ?>
                </a> 
            </span>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Fecha y hora de la comparecencia: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->comparecencia->fecha_comparecencia) . ' ' . $auditoria->comparecencia->hora_comparecencia_inicio . ' - ' . $auditoria->comparecencia->hora_comparecencia_termino }}
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Inicio de la etapa de aclaración: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->comparecencia->fecha_inicio_aclaracion)  }}
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <label>Término de la etapa de aclaración: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->comparecencia->fecha_termino_aclaracion) }}
            </span>
        </div>
    </div>
    @if (!empty($auditoria->comparecencia->oficio_recepcion))
    <h3 class="card-title text-primary">Acuses</h3> 
    <div class="row">        
        <div class="col-lg-3 col-md-5 col-sm-12 col-12">
            <label>Comprobante de recepción depto. de notificaciones: </label>
            <span class="text-sistema">
                <a href="{{ asset($auditoria->comparecencia->oficio_recepcion) }}" target="_blank">
                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_recepcion)) ?>
                </a> 
            </span>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12 col-12">
            <label>Fecha de recepción: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->comparecencia->fecha_recepcion) }}
            </span>
        </div>
    </div>
    <div class="row">        
        <div class="col-lg-3 col-md-6 col-sm-12 col-12">
            <label>Acuse del oficio de la comparecencia: </label>
            <span class="text-sistema">
                <a href="{{ asset($auditoria->comparecencia->oficio_acuse) }}" target="_blank">
                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acuse)) ?>
                </a> 
            </span>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12 col-12">
            <label>Fecha del acuse: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->comparecencia->fecha_acuse) }}
            </span>
        </div>
    </div>           
    @endif    
    @if (!empty($auditoria->comparecencia->cedula_general))
    <h3 class="card-title text-primary">Cédula</h3> 
    <div class="row">        
        <div class="col-lg-3 col-md-4 col-sm-12 col-12">
            <label>Cédula general: </label>
            <span class="text-sistema">
                <a href="{{ asset($auditoria->comparecencia->cedula_general) }}" target="_blank">
                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->cedula_general)) ?>
                </a> 
            </span>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
            <label>Fecha de la cédula: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->comparecencia->fecha_cedula) }}
            </span>
        </div>
    </div>            
    @endif    
    @if (!empty($auditoria->comparecencia->oficio_acta))
    <h3 class="card-title text-primary">Acta</h3> 
    <div class="row">        
        <div class="col-lg-3 col-md-5 col-sm-12 col-12">
            <label>Acta de comparecencia: </label>
            <span class="text-sistema">
                <a href="{{ asset($auditoria->comparecencia->oficio_acta) }}" target="_blank">
                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acta)) ?>
                </a> 
            </span>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-12 col-12">
            <label>Número de acta: </label>
            <span class="text-sistema">
                {{ $auditoria->comparecencia->numero_acta }}
            </span>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
            <label>Fecha del acta: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->comparecencia->fecha_comparecencia) }}
            </span>
        </div>
    </div>            
    @endif    
    @if (!empty($auditoria->comparecencia->oficio_respuesta))
    <h3 class="card-title text-primary">Respuesta</h3> 
    <div class="row">        
        <div class="col-lg-3 col-md-5 col-sm-12 col-12">
            <label>Oficio de respuesta de la Entidad Fiscalizable: </label>
            <span class="text-sistema">
                <a href="{{ asset($auditoria->comparecencia->oficio_respuesta) }}" target="_blank">
                    <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_respuesta)) ?>
                </a> 
            </span>
        </div>        
        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
            <label>Fecha de recibido: </label>
            <span class="text-sistema">
                {{ fecha($auditoria->comparecencia->fecha_comparecencia) }}
            </span>
        </div>
    </div>            
    @endif    
    @endif
    <div class="row">
        <div class="col-md-12"><hr></div>
    </div>
@endif