@extends('layouts.app')
@section('breadcrums')
@if ($accion=='Agregar')
{{ Breadcrumbs::render('radicacion.create') }}
@elseif($accion=='Editar')
{{ Breadcrumbs::render('radicacion.edit',$radicacion) }}
@endif
@endsection
@section('content')
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
        {!! BootForm::open(['model' => $radicacion,'store' => 'radicacion.store','update' => 'radicacion.update','id' =>
        'form',]) !!}
        <div class="row">
            <div class="col-md-2">
                {!! BootForm::text('numero_acuerdo', 'Número de acuerdo: *', old('numero_acuerdo',
                $radicacion->numero_acuerdo)) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! archivo('oficio_acuerdo', 'Acuerdo de radicación: *', old('oficio_acuerdo',
                $radicacion->oficio_acuerdo)) !!}
            </div>
            <div class="col-lg-2 col-md-3">
                {!! BootForm::date('fecha_oficio_acuerdo','Fecha del acuerdo: *',old('fecha_oficio_acuerdo',
                fecha($radicacion->fecha_oficio_acuerdo, 'Y-m-d')),) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! archivo('oficio_designacion', 'Oficio de designación: *', old('oficio_designacion',
                $radicacion->oficio_designacion)) !!}
            </div>
            <div class="col-lg-2 col-md-3">
                {!! BootForm::date('fecha_oficio_designacion','Fecha del oficio: *',old('fecha_oficio_designacion',
                fecha($radicacion->fecha_oficio_designacion, 'Y-m-d')),) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                {!! BootForm::text('nombre_titular','Nombre del titular a quien se dirige la comparecencia:
                *',old('nombre_titular', $comparecencia->nombre_titular),) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                {!! BootForm::text('cargo_titular','Cargo del titular a quien se dirige la comparecencia:
                *',old('cargo_titular', $comparecencia->cargo_titular),) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <span class="has-float-label">
                    {!! BootForm::date('fecha_comparecencia','Fecha de la comparecencia: *',old('fecha_comparecencia',
                    fecha($comparecencia->fecha_comparecencia, 'Y-m-d'))) !!}
                </span>
            </div>
            <div class="col-md-3">
                {!! BootForm::time('hora_comparecencia_inicio','Hora de inicio de la comparecencia:
                *',old('hora_comparecencia_inicio', $comparecencia->hora_comparecencia_inicio)) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                {!! BootForm::date('fecha_inicio_aclaracion','Inicio de la etapa de aclaración:
                *',old('fecha_inicio_aclaracion', fecha($comparecencia->fecha_inicio_aclaracion, 'Y-m-d')),['readonly'])
                !!}
            </div>
            <div class="col-md-3">
                {!! BootForm::date('fecha_termino_aclaracion','Término de la etapa de aclaración:
                *',old('fecha_termino_aclaracion', fecha($comparecencia->fecha_termino_aclaracion,
                'Y-m-d')),['readonly'],
                ) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @canany(['radicacion.store','radicacion.update'])
                <button type="submit" class="btn btn-primary">Guardar</button>
                @endcan
                <a href="{{ route('radicacion.index') }}" class="btn btn-secondary me-2">Cancelar</a>
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\RadicacionRequest') !!}
<script>
    $(document).ready(function() {
              // function rmydays(date) {
              //     return (date.getDay() === 0 || date.getDay() === 6);
              // }
              // $("#fecha_comparecencia").flatpickr({
              //     dateFormat: "d-m-Y",                
              //     disable: [rmydays],
              // });
              $("#fecha_comparecencia").prop('readonly', false);
              $("#fecha_comparecencia").on("change", function() {
                  let pd = $(this).val();
                  console.log(pd);
                  let dateu = pd.substr(0, 4)+'-'+pd.substr(5, 2)+'-'+pd.substr(8, 2);
                  let date = new Date(dateu);
                  //let date = new Date(pd);
                  date.setDate(date.getDate() + 1);
                  for (let index = 1; index <= 1; index++) {
                      date.setDate(date.getDate() + 1);
                      if (date.getDay() == 6 || date.getDay() == 0)
                          index--;
                  }
                  var dd = String(date.getDate()).padStart(2, '0');
                  var mm = String(date.getMonth() + 1).padStart(2, '0');
                  var yyyy = date.getFullYear();
                  today = yyyy + '-' + mm + '-' + dd;              
                  $("#fecha_inicio_aclaracion").val(today);
                  fechaTermino();
              });
  
              function fechaTermino() {
                  let pickedDate = $("#fecha_inicio_aclaracion").val();
                  let date = new Date(pickedDate);
                  date.setDate(date.getDate() + 2);
                  for (let index = 1; index < 30; index++) {
                      date.setDate(date.getDate() + 1);
                      if (date.getDay() == 6 || date.getDay() == 0)
                          index--;
                  }
                  var dd = String(date.getDate()).padStart(2, '0');
                  var mm = String(date.getMonth() + 1).padStart(2, '0');
                  var yyyy = date.getFullYear();
                  today = yyyy + '-' + mm + '-' + dd;
                  $("#fecha_termino_aclaracion").val(today);
              }
          });
</script>
@endsection