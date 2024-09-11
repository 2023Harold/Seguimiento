@extends('layouts.app')
@section('breadcrums')
@if (empty($acuerdoconclusion->numero_ordenauditoria))
    {{ Breadcrumbs::render('acuerdoconclusion.create') }}
@else
    {{ Breadcrumbs::render('acuerdoconclusion.edit',$acuerdoconclusion) }}
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
                {!! BootForm::open(['model' => $acuerdoconclusion,'store' => 'acuerdoconclusion.store','update' => 'acuerdoconclusion.update','id' => 'form']) !!}
       
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('acuerdoconclusion', 'Acuerdo de conclusión: *', old('acuerdoconclusion', $acuerdoconclusion->acuerdoconclusion)) !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::text('numero_acuerdo_conclusion', 'Número del acuerdo de conclusión: *', old('numero_acuerdo_conclusion', $acuerdoconclusion->acuerdoconclusion)) !!}
                    </div>
                </div>       
                <div class="row">
                    <div class="col-md-5">
                        {!! BootForm::date('fecha_acuerdo_conclusion', 'Fecha del acuerdo de conclusión *', old('fecha_acuerdo_conclusion', fecha($acuerdoconclusion->fecha_acuerdo_conclusion, 'Y-m-d'))); !!}
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-6"> 
                        @canany(['acuerdoconclusion.store','acuerdoconclusion.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcanany
                        <a href="{{ route('acuerdoconclusion.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    </div>
                </div>
                {!! BootForm::close() !!}    
            </div>    
        </div>  
    </div>
</div> 
@endsection
@section('script')
{{-- {!! JsValidator::formRequest('App\Http\Requests\InformePrimeraEtapaRequest') !!} --}}
@endsection
