@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('comparecenciaacta.edit',$comparecencia,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('comparecencia.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp; Acta
                </h1>
                <div class="float-end">
                    <a href="{{route('comparecencia.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;AC</a>                                  
                </div>
            </div>
            <div class="card-body">
                <div class="card-body">
                    @include('flash::message')
                    @include('layouts.contextos._auditoria')
                    @include('layouts.contextos._comparecencia')

                    {!! BootForm::open(['model' => $comparecencia,'update' => 'comparecenciaacta.update','id' => 'form',]) !!}                 
                    <div class="row">
                        <div class="col-md-12">
                            {!! archivo('oficio_acta', 'Acta de comparecencia: *', old('oficio_acta', $comparecencia->oficio_acta))
                            !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            {!! BootForm::text('numero_acta', 'Número de acta: *', old('numero_acta', $comparecencia->numero_acta));
                            !!}
                        </div>
                        <div class="col-md-4">
                            {!! BootForm::date('fecha_acta', 'Fecha del acta: *', old('fecha_comparecencia',
                            fecha($comparecencia->fecha_comparecencia, 'Y-m-d'))); !!}
                        </div>
                    </div>                       
                    
                    <div class="row">
                        <div class="col-md-12">
                            {!! BootForm::checkbox('comparecio', 'Comparecio', 'X', false, ['class' => 'i-checks rxs']) !!}
                        </div>                    
                    </div>
                        <div class="row">
                            <div class="col-md-12" style="display: none;" id="si_comparecio">                               
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! BootForm::time('hora_comparecencia_termino','Hora de término de la comparecencia:
                                            *',old('hora_comparecencia_termino', $comparecencia->hora_comparecencia_termino)) !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! archivo('oficio_designacion', 'Oficio de designación: ', old('oficio_designacion',
                                            $comparecencia->oficio_designacion)) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! BootForm::date('fecha_oficio_designacion', 'Fecha del oficio de designacion: ', old('fecha_oficio_designacion',
                                            fecha($comparecencia->fecha_oficio_designacion, 'Y-m-d'))); !!}
                                        </div>
                                    </div> 
                                    <div class="row">                    
                                        <div class="col-md-12"><hr></div>
                                    </div>    
                                    <h4 class="text-primary">Titular o representante </h4><br>            
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! BootForm::text('nombre_representante','Nombre: *',old('nombre_representante',
                                            $comparecencia->nombre_representante),)!!}
                                        </div>
                                        <div class="col-md-6">
                                            {!! BootForm::text('cargo_representante1','Cargo: *',old('cargo_representante',
                                            $comparecencia->cargo_representante),) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! BootForm::select('tipo_identificacion', 'Tipo de identificación: *', $tipo_identificacion , old('tipo_identificacion',$auditoria->tipo_identificacion), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! BootForm::text('numero_identificacion_representante','Número de identificación: *',old('numero de identificacion_testigo1', $comparecencia->numero_identificacion_representante),)
                                            !!}
                                        </div>
                                    </div>
                                    <div class="row">                    
                                        <div class="col-md-12"><hr></div><br>
                                    </div> 
                                    <h4 class="text-primary">Primer testigo  </h4><br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! BootForm::text('nombre_testigo1','Nombre: ',old('nombre_testigo1', $comparecencia->nombre_testigo1),)!!}
                                        </div>
                                        <div class="col-md-6">
                                            {!! BootForm::text('cargo_testigo1','Cargo: ',old('cargo_testigo1', $comparecencia->cargo_testigo1),) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! BootForm::select('tipo_identificacion1', 'Tipo de identificación: ', $tipo_identificacion1 , old('tipo_identificacion',$auditoria->tipo_identificacion1), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! BootForm::text('numero_identificacion_testigo1','Número de identificación: ',old('numero de identificacion_testigo1', $comparecencia->numero_identificacion_testigo1),) !!}
                                        </div>
                                    </div>
                                    <div class="row">                    
                                        <div class="col-md-12"><hr></div><br>
                                    </div>
                                    <h4 class="text-primary">Segundo testigo  </h4><br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! BootForm::text('nombre_testigo2','Nombre: ',old('nombre_testigo2', $comparecencia->nombre_testigo1),) !!}
                                        </div>
                                        <div class="col-md-6">
                                            {!! BootForm::text('cargo_testigo2','Cargo: ',old('cargo_testigo2', $comparecencia->cargo_testigo2),) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! BootForm::select('tipo_identificacion2', 'Tipo de identificación: ', $tipo_identificacion2 , old('tipo_identificacion',$auditoria->tipo_identificacion2), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! BootForm::text('numero_identificacion_testigo2','Número de identificación: ',old('numero de identificacion_testigo1', $comparecencia->numero_identificacion_testigo2),) !!}
                                        </div>
                                    </div>                                   
                            </div>     
                        </div>
                    </div>                                  
                    <div class="row">
                    <div class="col-md-12">
                        @can('comparecenciaacta.update')
                        <button type="submit" id='btn-guardar' class="btn btn-primary">Guardar</button>
                        {{-- @btnSubmit("Guardar") --}}
                        @endcan
                        @btnCancelar('Cancelar', route('comparecencia.index'))
                    </div>
                </div>
                {!! BootForm::close() !!}
            </div>            
        </div>
    </div>
</div>
@endsection
@section('script')
{{-- <script type="text/javascript" src="{{ asset('assets/js/signData.js')}}"></script> --}}
   
    <script>
        $(document).ready(function() {
            $('.rxs').on('ifChanged', function(event) {              
                var estado = $(this).is(':checked')? 1 : 0;

                if(estado==1){
                    $('#si_comparecio').show();                   
                    $('#btn-guardar').show();

                }else{
                    $('#si_comparecio').hide();
                    $('#btn-guardar').show();

                }
                //alert(estado);            
            });
        });
    </script> 
{!! JsValidator::formRequest('App\Http\Requests\ComparecenciaActaRequest') !!}
@endsection
