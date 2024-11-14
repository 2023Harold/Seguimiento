<div>
    <h3 class="card-title text-primary">Atención de la recomendación </h3>
    <div class="card-body py-7">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <label>Fecha compromiso de atención: </label>
                <span class="text-primary">
                    {{ fecha($accion->fecha_termino_recomendacion) }}
                </span>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Nombre del responsable de la entidad fiscalizada: </label>
                <span class="text-primary">
                    {{$recomendacion->auditoria->comparecencia->nombre_titular }}
                </span>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Cargo del responsable: </label>
                <span class="text-primary">
                    {{$recomendacion->auditoria->comparecencia->cargo_titular }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <label>Responsable del seguimiento: </label>
                <span class="text-primary">
                    {{$accion->analista->name }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Oficios de contestación: </label>
                <span class="text-primary">
                    <a href="{{ route('recomendacionescontestaciones.oficiosrecomendacion', $recomendacion) }}" class="popupSinLocation">
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                    </a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Lista de documentos: </label>
                <span class="text-primary">
                    {!! BootForm::textarea('listado_documentoslb', false,old('listado_documentoslb', $recomendacion->listado_documentos),['rows'=>'3','disabled']) !!}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Analisis: </label><br>
                {!! BootForm::textarea('analisis', false,old('analisis', $recomendacion->analisis),['rows'=>'3','disabled']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <label>Anexos: </label>
                <span class="text-primary">
                    <a href="{{ route('recomendacion.anexos', $recomendacion) }}" class="popupSinLocation">
                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-list" aria-hidden="true"></span>
                    </a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Conclusión: </label><br>
                {!! BootForm::textarea('conclusionlb', false,old('conclusionlb', $recomendacion->conclusion),['rows'=>'3','disabled']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label>Calificación de la atención: </label>
                @if (empty($recomendacion->calificacion_atencion))
                  @if ($recomendacion->calificacion_sugerida=='Atendida')
                      <span class="badge badge-light-success">Atendida</span>
                  @endif
                  @if ($recomendacion->calificacion_sugerida=='No Atendida')
                      <span class="badge badge-light-danger">No Atendida</span>
                  @endif
                  @if ($recomendacion->calificacion_sugerida=='Parcialmente Atendida')
                      <span class="badge badge-light-warning">Parcialmente Atendida</span>
                  @endif
                @else
                  @if ($recomendacion->calificacion_atencion=='Atendida')
                      <span class="badge badge-light-success">Atendida</span>
                  @endif
                  @if ($recomendacion->calificacion_atencion=='No Atendida')
                      <span class="badge badge-light-danger">No Atendida</span>
                  @endif
                  @if ($recomendacion->calificacion_atencion=='Parcialmente Atendida')
                      <span class="badge badge-light-warning">Parcialmente Atendida</span>
                  @endif
                @endif
            </div>
        </div>  
        <hr/>
    </div>
</div>
