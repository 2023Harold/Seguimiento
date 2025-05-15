@extends('layouts.app')
@section('breadcrums')
@if (empty($acuerdoconclusion->numero_ordenauditoria))
    {{ Breadcrumbs::render('acuerdoconclusioncp.create') }}
@else
    {{ Breadcrumbs::render('acuerdoconclusioncp.edit',$acuerdoconclusion,$acuerdoconclusionpliegos) }}
@endif    
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('acuerdoconclusion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; Acuerdo de Conclusión
                </h1>
                <div class="float-end">
                    <a href="{{route('acuerdoconclusionac.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;10. AC</a>                                                  
                    <a href="{{route('acuerdoconclusionofac.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;11. OF. AC</a>                                  
                </div>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')			
                {!! BootForm::open(['model' => $acuerdoconclusion,'store' => 'acuerdoconclusioncp.store','update' => 'acuerdoconclusioncp.update','id' => 'form']) !!}
                {!! BootForm::hidden('tipo',$tipo) !!}
                <div class= "row">
                     <div class="col-md-12">
                        {!! BootForm::checkbox('no_aplica', 'No Aplica', 'X', false, ['class' => 'i-checks rxs']) !!}
                    </div>                    
                </div>
                <div class="row">
                        <div class="col-md-12" style="display: none;" id="no_aplica">                 
                            <div class="row">
                            <div class="col-md-5">
                                {!! BootForm::text('nombre_titular', 'Nombre del titular a quien se dirige : *', old('nombre_titular', $acuerdoconclusion->nombre_titular)) !!}
                            </div>
                            <div class="col-md-3">
                                {!! BootForm::text('cargo_titular', 'Cargo del titular a quien se dirige : *', old('numero_acuerdo_conclusion', $acuerdoconclusion->cargo_titular)) !!}
                            </div>
                            </div>                
                        <div class= "row">
                            <div class="col-md-5">
                                {!! BootForm::text('domicilio', 'Domicilio : *', old('numero_acuerdo_conclusion', $acuerdoconclusion->domicilio)) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {!! BootForm::text('numero_oficio', 'Número de oficio: *', old('numero_oficio', $acuerdoconclusion->numero_oficio)) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">						
                                {!! BootForm::date('fecha_oficio', 'Fecha del Oficio *', old('fecha_oficio', fecha($acuerdoconclusion->fecha_oficio, 'Y-m-d'))); !!}
                            </div>
                        </div>				
                        <div class="row">
                            <div class="col-md-5">
                                {!! archivo('acuerdo_conclusion', 'Acuerdo de conclusión: *', old('acuerdo_conclusion', $acuerdoconclusion->acuerdo_conclusion)) !!}
                                
                            </div>                    
                        </div>       
                        <div class="row">
                            <div class="col-md-5">
                                {!! BootForm::date('fecha_acuerdo_conclusion', 'Fecha del acuerdo de conclusión *', old('fecha_acuerdo_conclusion', fecha($acuerdoconclusion->fecha_acuerdo_conclusion, 'Y-m-d'))); !!}
                            </div>
                        </div> 
                        </div>    
                 </div>  
                        <div class="row">
                            <div class="col-md-6"> 
                                @canany(['acuerdoconclusioncp.store','acuerdoconclusioncp.update'])
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                @endcanany
                                <a href="{{ route('acuerdoconclusion.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            </div>            
                {!! BootForm::close() !!}                  
            </div>    
        </div>  
    </div>
</div> 
@endsection
@section('script')
<script>
$(document).ready(function() {
        $('#no_aplica').show();    
        $('.rxs').on('ifChanged', function(event) {              
            var estado = $(this).is(':checked')? 1 : 0;

            if(estado==0){
                $('#no_aplica').show();                   
                $('#btn-guardar').show();

            }else{
                $('#no_aplica').hide();
                $('#btn-guardar').show();

            }
            //alert(estado);            
        });
    });
</script>
{{-- {!! JsValidator::formRequest('App\Http\Requests\AcuerdoConclusionRequest') !!} --}}
@endsection
