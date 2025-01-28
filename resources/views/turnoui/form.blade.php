@extends('layouts.app')
@section('breadcrums')
@if (empty($turnoui->numero_ordenauditoria))
    {{ Breadcrumbs::render('turnoui.create') }}
@else
    {{ Breadcrumbs::render('turnoui.edit',$turnoui) }}
@endif    
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('turnoui.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; Turno a la Unidad de Investigación
                </h1>
                <div class="float-end">
                    <a href="{{route('turnoui.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;Of. UI</a>                         
                </div>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['model' => $turnoui,'store' => 'turnoui.store','update' => 'turnoui.update','id' => 'form']) !!}
       
                <div class="row">
                    <div class="col-md-4">
                        {!! BootForm::date('fecha_turno_oi', 'Fecha del oficio a la Unidad de Investigación *', old('fecha_turno_oi', fecha($turnoui->fecha_turno_oi, 'Y-m-d'))); !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::text('numero_turno_ui', 'Número de oficio *', old('numero_turno_ui', $turnoui->numero_turno_ui)) !!}
                    </div>
                </div> 
                <div class="row"> Expediente Técnico de la Auditoría:
                    <div class="row">
                        <div class="col-md-3">
                            {!! BootForm::text('legajos_tecnico', 'Número de legajos  *', old('legajos_tecnico', ($turnoui->legajos_tecnico))); !!}
                        </div>
                        <div class="col-md-5">
                            {!! BootForm::text('fojas_tecnico', 'Número de fojas  *', old('fojas_tecnico', ($turnoui->fojas_tecnico))); !!}
                        </div>
                    </div>
                </div>
                <div class="row"> Expediente de Seguimiento:
                    <div class="row">
                        <div class="col-md-3">
                            {!! BootForm::text('legajos_seg', 'Número de legajos  *', old('legajos_seg', ($turnoui->legajos_seg))); !!}
                        </div>
                        <div class="col-md-5">
                            {!! BootForm::text('fojas_seg', 'Número de fojas  *', old('fojas_seg', ($turnoui->fojas_seg))); !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('turno_ui', 'Turno a la Unidad de Investigación: *', old('turno_ui', $turnoui->turno_ui)) !!}
                    </div>    
                    <div class="col-md-4">
                        {!! BootForm::date('fecha_notificacion_ui', 'Fecha de notificación  *', old('fecha_notificacion_ui', fecha($turnoui->fecha_notificacion_ui))); !!}
                    </div>                
                </div>       
                
                <div class="row">
                    <div class="col-md-6"> 
                        @canany(['turnoui.store','turnoui.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcanany
                        <a href="{{ route('turnoui.index') }}" class="btn btn-secondary me-2">Cancelar</a>
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
