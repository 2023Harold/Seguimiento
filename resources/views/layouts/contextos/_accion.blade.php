<div>
    <h3 class="card-title text-primary">Acción</h3>
    <div class="card-body py-7">
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
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <label>Antecedentes de la acción: </label>
                    <span class="text-primary">
                        {!! BootForm::textarea('antecedentes_accionlb', false,old('antecedentes_accionlb', $accion->antecedentes_accion),['rows'=>'3','disabled']) !!}
                    </span>
                </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <label>Acción: </label><br>
                    {!! BootForm::textarea('accionlb', false,old('accionlb', $accion->accion),['rows'=>'3','disabled']) !!}
                </div>
            </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <label>Normatividad infringida: </label>
                    <span class="text-primary">
                        {!! BootForm::textarea('normativa_infringidalb', false,old('normativa_infringidalb', $accion->normativa_infringida),['rows'=>'3','disabled']) !!}
                    </span>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <label>Monto por aclarar: </label>
                    <span class="text-primary">
                        {{ '$'.number_format( $accion->monto_aclarar, 2) }}
                    </span>
                </div>
            </div>
            @if (!empty($accion->departamento_asignado))
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <label>Departamento:</label>
                    <span class="text-primary">
                        {{ $accion->departamento_asignado }} -
                        {{ $accion->depaasignado->name }} -
                        {{ $accion->depaasignado->puesto }}
                    </span>
                </div>
            @endif
        </div>
        <hr/>
    </div>
</div>
