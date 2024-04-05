@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('auditoriaconsultaacciones.index') }}
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('auditoriaseguimientoacciones.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
            &nbsp; Acción
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        <div>
        <h3 class="card-title text-primary">Acción</h3>
        <div class="card-body py-7">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label>No. consecutivo: </label>
                    <span class="text-primary">
                        {{ str_pad($accion->consecutivo, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label>Tipo de acción: </label>
                    <span class="text-primary">
                        {{ $accion->tipo}}
                    </span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label>Acto de fiscalización: </label>
                    <span class="text-primary">
                        {{ $accion->acto_fiscalizacion}} - {{ (empty($accion->tipologia_id)?'':$accion->tipologiadesc->tipologia) }}
                    </span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label>Número de acción: </label>
                    <span class="text-primary">
                        {{ $accion->numero }}
                    </span>
                </div>
                @if (!empty($accion->cedula))
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Cédula acción: </label>
                        <span class="text-primary">
                            <a href="{{ asset($accion->cedula) }}" target="_blank">
                                <?php echo htmlspecialchars_decode(iconoArchivo($accion->cedula)) ?>
                            </a>
                        </span>
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Acción: </label><br>
                        {!! BootForm::textarea('accionlb', false,old('accionlb', $accion->accion),['rows'=>'3','disabled']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Antecedentes de la acción: </label>
                        <span class="text-primary">
                            {!! BootForm::textarea('antecedentes_accionlb', false,old('antecedentes_accionlb', $accion->antecedentes_accion),['rows'=>'3','disabled']) !!}
                        </span>
                    </div>
                </div>_
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Normatividad infringida: </label>
                        <span class="text-primary">
                            {!! BootForm::textarea('normativa_infringidalb', false,old('normativa_infringidalb', $accion->normativa_infringida),['rows'=>'3','disabled']) !!}
                        </span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <label>Monto por aclarar: </label>
                    <span class="text-primary">
                        {{ '$'.number_format( $accion->monto_aclarar, 2) }}
                    </span>
                </div>
                @if ($accion->tipo=='Recomendación')
                    @if (!empty($accion->evidencia_recomendacion))
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label>Evidencia documental que acredite la atención de la recomendación: </label>
                            <span class="text-primary">
                                {!! BootForm::textarea('evidencia_resumen', false,old('evidencia_resumen', $accion->evidencia_resumen),['rows'=>'3','disabled']) !!}
                            </span>
                        </div>
                    </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label>Soporte de la evidencia documental que acredite la atención de la recomendación: </label>
                            <span class="text-primary">
                                <a href="{{ asset($accion->evidencia_recomendacion) }}" target="_blank">
                                    <?php echo htmlspecialchars_decode(iconoArchivo($accion->evidencia_recomendacion)) ?>
                                </a>
                            </span>
                        </div>
                    @endif
                    @if (!empty($accion->tipo_recomendacion))
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <label>Tipo de recomendación: </label>
                            <span class="text-primary">
                                {{ $accion->tipo_recomendacion}}
                            </span>
                        </div>
                    @endif
                    @if (!empty($accion->tramo_control_recomendacion))
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <label>Tramo de control: </label>
                            <span class="text-primary">
                                {{ $accion->tramo_control_recomendacion}}
                            </span>
                        </div>
                    @endif
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label>Fecha de término: </label>
                    <span class="text-primary">
                        {{ fecha($accion->fecha_termino_recomendacion) }}
                    </span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label>Plazo convenido: </label>
                    <span class="text-primary">
                        {{ $accion->plazo_recomendacion }}
                    </span>
                </div>
                @endif
            </div>
            <hr/>
        </div>
    </div>
    </div>
</div>
@endsection
