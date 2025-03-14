@extends('layouts.app')
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
                <div class="row">
                    <div class="col-md-3">
                        {!!BootForm::text('num_memo_recepcion_expediente', 'Número del memorándum de recepción del expediente: *', old('num_memo_recepcion_expediente',$radicacion->num_memo_recepcion_expediente)) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {!!BootForm::date('fecha_expediente_turnado', 'Fecha de recepción del expediente turnado: *', old('fecha_expediente_turnado',fecha($radicacion->fecha_expediente_turnado, 'Y-m-d'))) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {!!BootForm::text('numero_expediente', 'Número de expediente US: *', old('numero_expediente',$radicacion->numero_expediente)) !!}
                    </div>
                </div>
				<div class="row">
                    <div class="col-md-2">
                        {!!BootForm::date('fecha_notificacion', 'Fecha de radicación: *', old('fecha_notificacion',fecha($radicacion->fecha_notificacion,'Y-m-d'))) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!!BootForm::text('numero_acuerdo', 'Número de oficio de notificación del informe de auditoría: *', old('numero_acuerdo',$radicacion->numero_acuerdo)) !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!!BootForm::date('fecha_oficio_informe','Fecha de notificación: *', old('fecha_oficio_informe',fecha($radicacion->fecha_oficio_informe,'Y-m-d'))) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!!BootForm::text('oficio_acuerdo', 'Oficio de notificación de acuerdos: *', old('numero_acuerdo',$radicacion->oficio_acuerdo)) !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!!BootForm::date('fecha_oficio_acuerdo','Fecha de oficio: *', old('fecha_oficio_acuerdo',fecha($radicacion->fecha_oficio_acuerdo,'Y-m-d'))) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!!BootForm::text('acta_cierre_auditoria', 'Acta de reunión de resultados finales y cierre de auditoría: *', old('acta_cierre_auditoria',$radicacion->acta_cierre_auditoria)) !!}
                    </div>
                    <div class="col-lg-3 col-md-3">
                        {!!BootForm::date('fecha_acta','Fecha del acta: *', old('fecha_acta',fecha($radicacion->fecha_acta,'Y-m-d'))) !!}
                    </div>
                </div>




                <div class="row">
                    <div class="col-md-4">
                        {!!BootForm::text('nombre_titular','Nombre del titular a quien se dirige la comparecencia: *',old('nombre_titular', optional($comparecencia)->nombre_titular),) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!!BootForm::text('cargo_titular','Cargo del titular a quien se dirige la comparecencia: *',old('cargo_titular', optional($comparecencia)->cargo_titular),) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
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
<script>
    $(document).ready(function() {
              // function rmydays(date) {
              //     return (date.getDay() === 0 || date.getDay() === 6);
              // }
              // $("#fecha_comparecencia").flatpickr({
              //     dateFormat: "d-m-Y",
              //     disable: [rmydays],
              // });
              //$("#fecha_comparecencia").prop('readonly', false);
              $("#fecha_comparecencia").on("change", function() {
                  let pd = $(this).val();
                  console.log(pd,1);
                  let dateu = pd.substr(0, 4)+'-'+pd.substr(5, 2)+'-'+pd.substr(8, 2);
                  let date = new Date(dateu);
                  //let date = new Date(pd);
                  date.setDate(date.getDate() + 1);
                  for (let index = 1; index <= 1; index++) {
                      date.setDate(date.getDate() + 1);
                      if (date.getDay() == 6 || date.getDay() == 0)
                          index--;
                  }

                        //FEBRERO dias no laborables
                        if(date.getMonth()==1 && date.getFullYear()==2024){
                            if(date.getDate()==5){
                                date.setDate(date.getDate() + 1);
                            }
                        }

                        //MARZO dias no laborables
                        if(date.getMonth()==2 && date.getFullYear()==2024){
                            if(date.getDate()==2 || date.getDate()==18 || date.getDate()==28 ){
                                date.setDate(date.getDate() + 1);
                            }
                            if(date.getDate()==29 ){
                                date.setDate(date.getDate() + 1);
                            }

                        }

                        //MAYO dias no laborables
                        if(date.getMonth()==4 && date.getFullYear()==2024){
                            if(date.getDate()==1 || date.getDate()==5){
                                date.setDate(date.getDate() + 1);
                            }
                        }

                        //JULIO dias no laborables
                        if(date.getMonth()==6 && date.getFullYear()==2024){
                            if(date.getDate()==22){
                                date.setDate(date.getDate() + 14);
                            }
                        }

                        //SEPTIEMBRE dias no laborables
                        if(date.getMonth()==8 && date.getFullYear()==2024){
                            if(date.getDate()==16){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                        //OCTUBRE dias no laborables
                        if(date.getMonth()==9 && date.getFullYear()==2024){
                            if(date.getDate()==1){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                        //NOVIEMBRE dias no laborables
                        if(date.getMonth()==10 && date.getFullYear()==2024){
                            if(date.getDate()==2 || date.getDate()==18){
                                date.setDate(date.getDate() + 1);
                            }
                        }

                        //DICIEMBRE dias no laborables
                        if(date.getMonth()==11 && date.getFullYear()==2024){
                            if(date.getDate()==20 ){
                                date.setDate(date.getDate() + 18);
                            }
                        }


                        var dd = String(date.getDate()).padStart(2, '0');
                        var mm = String(date.getMonth() + 1).padStart(2, '0');
                        var yyyy = date.getFullYear();
                        today = yyyy + '-' + mm + '-' + dd;

                        date.setDate(date.getDate() + 1);
                        var dd2 = String(date.getDate()).padStart(2, '0');
                        var mm2 = String(date.getMonth() + 1).padStart(2, '0');
                        var yyyy2 = date.getFullYear();
                        todayDP = yyyy2 + '-' + mm2 + '-' + dd2;

                        $("#fecha_inicio_aclaracion").val(today);
                        fechaTermino(30,'aclaracion',todayDP);
              });

              function fechaTermino(sumadias,etapa,inicio) {
                  let pickedDate = inicio;
                  let date = new Date(pickedDate);

                  console.log('inicio In: '+inicio);
                  console.log('inicio PD : '+pickedDate);
                  console.log('inicio date: '+date);

                //   date.setDate(date.getDate() + 2);
                  for (let index = 1; index <= sumadias; index++) {
                    if (date.getDay() == 5 ) {

                        date.setDate(date.getDate() + 3);

                          //FEBRERO dias no laborables
                          if(date.getMonth()==1 && date.getFullYear()==2024){
                            if(date.getDate()==5){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                        //MARZO dias no laborables
                        if(date.getMonth()==2 && date.getFullYear()==2024){
                            if(date.getDate()==2 || date.getDate()==18 || date.getDate()==28){
                                date.setDate(date.getDate() + 1);
                            }
                            if(date.getDate()==29 ){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                        //MAYO dias no laborables
                        if(date.getMonth()==4 && date.getFullYear()==2024){
                            if(date.getDate()==1 || date.getDate()==5){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                         //JULIO dias no laborables
                         if(date.getMonth()==6 && date.getFullYear()==2024){
                            if(date.getDate()==22){
                                date.setDate(date.getDate() + 14);
                            }
                        }
                        //SEPTIEMBRE dias no laborables
                        if(date.getMonth()==8 && date.getFullYear()==2024){
                            if(date.getDate()==16){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                        //OCTUBRE dias no laborables
                        if(date.getMonth()==9 && date.getFullYear()==2024){
                            if(date.getDate()==1){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                        //NOVIEMBRE dias no laborables
                        if(date.getMonth()==10 && date.getFullYear()==2024){
                            if(date.getDate()==2 || date.getDate()==18){
                                date.setDate(date.getDate() + 1);
                            }
                        }

                        //DICIEMBRE dias no laborables
                        if(date.getMonth()==11 && date.getFullYear()==2024){
                            if(date.getDate()==20 ){
                                date.setDate(date.getDate() + 18);
                            }
                        }

                    }else {
                        date.setDate(date.getDate() + 1);


                        //FEBRERO dias no laborables
                        if(date.getMonth()==1 && date.getFullYear()==2024){
                            if(date.getDate()==5){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                        //MARZO dias no laborables
                        if(date.getMonth()==2 && date.getFullYear()==2024){
                            if(date.getDate()==2 || date.getDate()==18 || date.getDate()==28){
                                date.setDate(date.getDate() + 1);
                            }
                            if(date.getDate()==29 ){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                        //MAYO dias no laborables
                        if(date.getMonth()==4 && date.getFullYear()==2024){
                            if(date.getDate()==1 || date.getDate()==5){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                         //JULIO dias no laborables
                         if(date.getMonth()==6 && date.getFullYear()==2024){
                            if(date.getDate()==22){
                                date.setDate(date.getDate() + 14);
                            }
                        }
                        //SEPTIEMBRE dias no laborables
                        if(date.getMonth()==8 && date.getFullYear()==2024){
                            if(date.getDate()==16){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                        //OCTUBRE dias no laborables
                        if(date.getMonth()==9 && date.getFullYear()==2024){
                            if(date.getDate()==1){
                                date.setDate(date.getDate() + 1);
                            }
                        }
                        //NOVIEMBRE dias no laborables
                        if(date.getMonth()==10 && date.getFullYear()==2024){
                            if(date.getDate()==2 || date.getDate()==18){
                                date.setDate(date.getDate() + 1);
                            }
                        }

                        //DICIEMBRE dias no laborables
                        if(date.getMonth()==11 && date.getFullYear()==2024){
                            if(date.getDate()==20 ){
                                date.setDate(date.getDate() + 18);
                            }
                        }
                    }

                    console.log(index+"---------"+date.getDate()+"-----"+date.getMonth()+"------"+date.getFullYear());
                }


                  var dd = String(date.getDate()).padStart(2, '0');
                  var mm = String(date.getMonth() + 1).padStart(2, '0');
                  var yyyy = date.getFullYear();
                  today = yyyy + '-' + mm + '-' + dd;

                  if(etapa==='aclaracion') {
                    console.log(today,2);
                     $("#fecha_termino_aclaracion").val(today);
                     fechaanalisis(today);
                  }
                  if(etapa==='analisis') {
                    console.log(today,4);
                     $("#calculo_fecha").val(today);
                  }


              }

              function fechaanalisis(fechatermino) {

                  let pd = fechatermino;
                  console.log(3);
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
                  fechaTermino(120,'analisis',today);
              }


                $('input[name="aplicacion_periodo"]').on('ifChanged', function(event) {
                    // if (event.target.value == 'Si') {
                    //     document.getElementById("fecha_termino_aclaracion").readOnly = true;
                    // }else{
                    //     document.getElementById("fecha_termino_aclaracion").readOnly = false;
                    // }
                });

          });
</script>
@endsection
