@extends('layouts.app')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('breadcrums')
@if ($accion=='Agregar')
{{Breadcrumbs::render('radicacion.create',$auditoria) }}
@elseif($accion=='Editar')
{{Breadcrumbs::render('radicacion.edit',$radicacion,$auditoria) }}
@endif
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('radicacion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp; {{ $accion }}
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!!BootForm::open(['model' => $radicacion,'store' => 'radicacion.store','update' => 'radicacion.update','id' =>'form',]) !!}
                {!!BootForm::hidden('acto_fiscalizacion_auditoria',$auditoria->acto_fiscalizacion)!!}
                {!!BootForm::hidden('calculo_fecha','',['id'=> 'calculo_fecha'])!!}

                 <h4 class="text-primary">Auditoría </h4><br>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            {!!BootForm::text('numero_orden', 'Número de orden de auditoría: *', old('numero_orden', $auditoria->numero_orden)) !!}
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                {!! archivo('informe_auditoria', 'informe de auditoría: ', optional($auditoria)->informe_auditoria) !!}
                            </div>
                            <div class="col-md-2">
                                {!!BootForm::text('fojas_utiles', 'Número de fojas útiles: *', optional($auditoria)->fojas_utiles) !!}
                            </div>
                        </div>  
                        <div class="row">
                            <h5><label>Datos del servidor público al que se le notificó el informe de auditoría:</label></h5>
                            <div class="col-md-4">
                                {!!BootForm::text('nombre_informe_au', 'Nombre: *', old('nombre_informe_au', $auditoria->nombre_informe_au)) !!}
                            </div>
                            <div class="col-md-4">
                                {!!BootForm::text('cargo_informe_au', 'Cargo : *', old('cargo_informe_au', $auditoria->cargo_informe_au)) !!}
                            </div>
                            <div class="col-md-4">
                                {!!BootForm::text('administracion_informe_au', 'Administración: *', old('administracion_informe_au', $auditoria->administracion_informe_au)) !!}
                            </div>
                        
                        </div> 
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                {!!BootForm::text('num_memo_recepcion_expediente', 'Número del memorándum de recepción del expediente: *', old('num_memo_recepcion_expediente',$radicacion->num_memo_recepcion_expediente)) !!}
                            </div>
                            <div class="col-md-3 mb-3">
                                {!!BootForm::date('fecha_expediente_turnado', 'Fecha de recepción del expediente turnado: *', old('fecha_expediente_turnado',fecha($radicacion->fecha_expediente_turnado, 'Y-m-d'))) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                {!!BootForm::text('numero_acuerdo', 'Número de oficio de notificación del informe de auditoría: *', old('numero_acuerdo',$radicacion->numero_acuerdo)) !!}
                            </div>
                            <div class="col-lg-3 col-md-3 mb-3">
                                {!!BootForm::date('fecha_oficio_informe','Fecha de notificación: *', old('fecha_oficio_informe',fecha($radicacion->fecha_oficio_informe,'Y-m-d'))) !!}
                            </div>
                        </div>
                        
                    </div>
                <hr>
                <h4 class="text-primary">Radicación</h4><br>
                
                <div class="row">
                    <div class="col-md-3 mb-5">
                        {!!BootForm::text('numero_expediente', 'Número de expediente US: *', old('numero_expediente',$radicacion->numero_expediente)) !!}
                    </div>
                </div>
				<div class="row">
                    <div class="col-md-2 mb-5">
                        {!!BootForm::date('fecha_notificacion', 'Fecha de radicación: *', old('fecha_notificacion',fecha($radicacion->fecha_notificacion,'Y-m-d'))) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-5">
                        {!!BootForm::text('oficio_acuerdo', 'Oficio de notificación de acuerdos: *', old('numero_acuerdo',$radicacion->oficio_acuerdo)) !!}
                    </div>

                <div class="col-lg-3 col-md-3 mb-5">
                        {!!BootForm::date('fecha_oficio_acuerdo','Fecha de oficio: *', old('fecha_oficio_acuerdo',fecha($radicacion->fecha_oficio_acuerdo,'Y-m-d'))) !!}
                    </div>
					</div>
                <div class="row">
                    <div class="col-md-4 mb-5">
                        {!!BootForm::text('acta_cierre_auditoria', 'Acta de reunión de resultados finales y cierre de auditoría: *', old('acta_cierre_auditoria',$radicacion->acta_cierre_auditoria)) !!}
                    </div>
                    <div class="col-lg-3 col-md-3 mb-5">
                        {!!BootForm::date('fecha_acta','Fecha del acta: *', old('fecha_acta',fecha($radicacion->fecha_acta,'Y-m-d'))) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-5">
                        {!!BootForm::text('nombre_titular','Nombre del titular a quien se dirige la comparecencia: *',old('nombre_titular', optional($comparecencia)->nombre_titular),) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-5">
                        {!!BootForm::text('cargo_titular','Cargo del titular a quien se dirige la comparecencia: *',old('cargo_titular', optional($comparecencia)->cargo_titular),) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-5">
                        {!!BootForm::text('domicilio_notificacion','Domicilio: *',old('domicilio', optional($comparecencia)->domicilio_notificacion),) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <span class="has-float-label">
                            {!!BootForm::date('fecha_comparecencia','Fecha de la comparecencia: *',old('fecha_comparecencia', fecha(optional($comparecencia)->fecha_comparecencia, 'Y-m-d'))) !!}
                        </span>
                    </div>
                    <div class="col-md-3">
                        {!!BootForm::time('hora_comparecencia_inicio','Hora de inicio de la comparecencia: *',old('hora_comparecencia_inicio', optional($comparecencia)->hora_comparecencia_inicio)) !!}
                    </div>
                </div>
                {{-- @if ($auditoria->acto_fiscalizacion!='Desempeño') --}}
                {{-- <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::radios('aplicacion_periodo', '¿El periodo de la etapa de aclaración es de 30 días hábiles? *', ['Si' => 'Si', 'No' => 'No'], old('aplicacion_periodo',optional($comparecencia)->aplicacion_periodo),true,['class'=>'i-checks']); !!}
                    </div>
                </div> --}}
				@if ($auditoria->acto_fiscalizacion!='Desempeño')
                <div class="row">
                    <div class="col-md-3">
                        {!!BootForm::date('fecha_inicio_aclaracion','Inicio de la etapa de aclaración: *',old('fecha_inicio_aclaracion', fecha(optional($comparecencia)->fecha_inicio_aclaracion, 'Y-m-d')))
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!BootForm::date('fecha_termino_aclaracion','Término de la etapa de aclaración: *',old('fecha_termino_aclaracion', fecha(optional($comparecencia)->fecha_termino_aclaracion, 'Y-m-d'))) !!}
                    </div>
                </div>
				@endif
                <div class="row">
                    <div class="col-md-2">
                        {!!BootForm::text('plazo_maximo', 'Plazo máximo: ', old('numero_expediente',$radicacion->plazo_maximo)) !!}
                    </div>
                </div>
                {{-- @endif
                @if ($auditoria->acto_fiscalizacion=='Legalidad' || $auditoria->acto_fiscalizacion=='Desempeño') --}}
                <div class="row">
                    <div class="col-md-3">
                        {!!BootForm::date('fecha_inicio_proceso','Inicio del proceso de atención: ',old('fecha_inicio_proceso', fecha(optional($comparecencia)->fecha_inicio_proceso, 'Y-m-d')))
                        !!}
                    </div>
                    <div class="col-md-3">
                        {!!BootForm::date('fecha_termino_proceso','Término del proceso de atención: ',old('fecha_termino_proceso', fecha(optional($comparecencia)->fecha_termino_proceso, 'Y-m-d'))) !!}
                    </div>
                </div>
                {{-- @endif --}}
                <div class="row">
                    <div class="col-md-6">
                        @canany(['radicacion.store','radicacion.update'])
                        <button type="submit" class="btn btn-primary">Guardar y continuar</button>
                        @endcan
                        <a href="{{ route('radicacion.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    </div>
                </div>
                {!!BootForm::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{!!JsValidator::formRequest('App\Http\Requests\RadicacionRequest') !!}

@php
  $noLab = \App\Models\SUTIC\DiasNoLaboralesIntra::query()
      ->where('StsEntDia', 1)
      ->whereIn('TipoDia', ['I','V'])
      ->pluck('DiaNoLab')
      ->map(fn($dt) => \Carbon\Carbon::parse($dt)->format('Y-m-d'))
      ->values();
@endphp
<script>
  const NO_LAB = @json($noLab);

  // --- Utilidades ---
  function ymd(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth()+1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
  }

  function parseYmd(str) {
    // Siempre forzamos medianoche local para evitar desfases por huso horario
    return new Date(str + 'T00:00:00');
  }

  function esNoLaboralDate(d) {
    const s = ymd(d);
    return d.getDay() === 0 || d.getDay() === 6 || NO_LAB.includes(s);
  }

  function nextBusinessDayYmd(startYmd, mode = 'after') {
    let d = parseYmd(startYmd);
    if (mode === 'after') d.setDate(d.getDate() + 1);
    while (esNoLaboralDate(d)) {
      d.setDate(d.getDate() + 1);
    }
    return ymd(d);
  }

  function addBusinessDaysYmd(startYmd, n) {
    let d = parseYmd(startYmd);
    let added = 0;
    while (added < n) {
      d.setDate(d.getDate() + 1);
      if (!esNoLaboralDate(d)) added++;
    }
    return ymd(d);
  }

  // --- Handler único ---
  $(function () {
    $("#fecha_comparecencia").on("change", function () {
      const sel = $(this).val(); // "YYYY-MM-DD"
      if (!sel) return;

      // 1) Inicio de aclaración: siguiente hábil DESPUÉS de la comparecencia
      const inicio = nextBusinessDayYmd(sel, 'after');
      $("#fecha_inicio_aclaracion").val(inicio);

      // 2) Término de aclaración: sumar 30 días hábiles desde el inicio
      const termino = addBusinessDaysYmd(inicio, 29);
      $("#fecha_termino_aclaracion").val(termino);

      // (Opcional) Si quieres calcular también tu "calculo_fecha" (120 hábiles
      // después del día hábil posterior al término), descomenta:
      // const inicioAnalisis = nextBusinessDayYmd(termino, 'after');
      // const calculo = addBusinessDaysYmd(inicioAnalisis, 120);
      // $("#calculo_fecha").val(calculo);
    });
  });
</script>
@endsection
