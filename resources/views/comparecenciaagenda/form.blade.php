@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('comparecenciaagenda.edit',$comparecencia,$radicacion,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('radicacion.edit',$radicacion) }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp; Agendar comparecia
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._radicacion')
                {!! BootForm::open(['model' => $comparecencia,'update' => 'comparecenciaagenda.update','id' =>'form',]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::radios('sala', 'Sala de la reunión *', ['s1'=>1,'s2'=>2,'s3'=>3], old('sala','s'.optional($comparecencia->agenda)->sala),true,['class'=>'i-checks']) !!}
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-3">
                        <span class="has-float-label">
                            {!! BootForm::date('fecha','Fecha: *',old('fecha_comparecencia', fecha($comparecencia->fecha_comparecencia, 'Y-m-d')),['readonly']) !!}
                        </span>
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::time('hora_inicio','Hora de inicio: *',old('hora_comparecencia_inicio', $comparecencia->hora_comparecencia_inicio),['readonly']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::time('hora_fin','Hora aproximada de término: *',old('hora_comparecencia_inicio', optional($comparecencia->agenda)->hora_fin)) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <span class="has-float-label">
                            {!! BootForm::text('lugar_comparecencia','Lugar de la comparecencia: *',old('lugar_comparecencia',"OSFEM-José María Pino Suárez",$comparecencia->lugar_comparecencia),['readonly']) !!}
                        </span>
                    </div>       
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {{-- @canany(['comparecenciaagenda.update']) --}}
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        {{-- @endcan
                        @canany(['comparecenciaagenda.update']) --}}
                            <a href="{{ route('radicacion.edit',$radicacion) }}" class="btn btn-secondary me-2">Cancelar</a>
                        {{-- @endcan --}}
                    </div>
                </div>
                {!! BootForm::close() !!}
                <div>            
                    <div class="table table-responsive">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                            <th>Sala</th>
                            <th>Fecha</th>
                            <th>Hora inicio</th>
                            <th>Hora aproximada de término</th>
                            <th>lugar de la comparecencia</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyCitas">  
                            @if (!empty($citas))
                                @forelse ($citas as $cita)              
                                <tr class="{{ ($cita->id == $comparecencia->agenda->id)?'table-success':''}}">
                                    <td class="text-center">{{ $cita->sala }}</td>
                                    <td class="text-center">{{ fecha($cita->fecha) }}</td>
                                    <td class="text-center">{{ date("g:i a",strtotime($cita->hora_inicio)) }}</td>
                                    <td class="text-center">{{ date("g:i a",strtotime($cita->hora_fin))}}</td>
                                    <td class="text-center">{{ $comparecencia->lugar_comparecencia }}</td>
                                </tr>
                                @empty             
                                    <tr>
                                        <td class="text-center" colspan="4">No se encontrarón comparecencias agendadas.</td>                               
                                    </tr> 
                                @endforelse 
                            @else
                                <tr>
                                    <td class="text-center" colspan="4">No se encontrarón comparecencias agendadas.</td>                               
                                </tr> 
                            @endif                        
                        
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\ComparecenciaAgendaRequest') !!}
<script>
    var numerosala;
    var fecha;
    var idcomparecencia;

    $(document).ready(function() {        
        idcomparecencia = @php echo $comparecencia->id @endphp;
        $('input[name="sala"]').on('ifChanged', function(event) {            
            if(event.target.checked){
                numerosala = event.target.value;
                fecha = $('#fecha').val();            //alert(numerosala);
                setTimeout(mostrarCitas(), 1000);
            }          
        });
    });
    function mostrarCitas(){
        
        $.ajax({
                url: "{{ route('getAgendaComparecencias') }}"
                , dataType: "JSON"
                , type: "POST"
                , method: 'POST'
                , data: {
                    "numerosala": numerosala
                    , "fecha": fecha
                , }
                , beforeSend: function(objeto) {}
                , success: function(respuesta) {
                    console.log(respuesta[0]);
                    var citas=respuesta[0];
                    var valor = '';
                    var clasesuccess='';

                    if (citas.length > 0) {
                        for (var i = 0; i < citas.length; i++) {
                            
                            

                            if(citas[i].id_comparecencia==idcomparecencia){
                                console.log('entra');
                                clasesuccess='table-success';
                            }

                            valor += '<tr class="'+clasesuccess+'">'+
                            '<td class="text-center">' + citas[i].sala + '</td>'+
                            '<td class="text-center">' + citas[i].fecha + '</td>'+
                            '<td class="text-center">' + citas[i].hora_inicio + '</td>'+ 
                            '<td class="text-center">' + citas[i].hora_fin + '</td>'+                       
                            '<tr>';
                        }
                    }else{
                        valor += '<tr>'+
                            '<td colspan="4" class="text-center"> No se encontrarón comparecencias agendadas. </td>'+                   
                            '<tr>';
                    }

                    $("#tbodyCitas").html(valor);                   
                }
                , error: function() {
                    console.log('Error al cargar las entidades fiscalizables');
                }
            }); 
    }
</script>
@endsection