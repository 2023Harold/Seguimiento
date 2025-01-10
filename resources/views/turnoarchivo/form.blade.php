@extends('layouts.app')
@section('breadcrums')
@if (empty($turnoarchivo->numero_ordenauditoria))
    {{ Breadcrumbs::render('turnoarchivo.create') }}
@else
    {{ Breadcrumbs::render('turnoarchivo.edit',$turnoarchivo) }}
@endif    
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('turnoarchivo.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; Turno acuse envío al archivo
                </h1>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['model' => $turnoarchivo,'store' => 'turnoarchivo.store','update' => 'turnoarchivo.update','id' => 'form']) !!}
                <div class="row">
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_turno_archivo', 'Fecha del oficio *', old('fecha_turno_archivo', fecha($turnoarchivo->fecha_turno_archivo, 'Y-m-d'))); !!}
                    </div>
                    {{-- <div class="col-md-4">
                        {!! BootForm::text('numero_turno_archivo', 'Número de oficio: *', old('numero_turno_archivo', $turnoarchivo->numero_turno_archivo)) !!}
                    </div> --}}
                </div> 
                <div class="row"> Expediente Técnico de la Auditoría:
                    <div class="row">
                        <div class="col-md-2">
                            {!! BootForm::text('legajos_tecnico_archivo', 'Número de legajos  *', old('legajos_tecnico_archivo', ($turnoarchivo->legajos_tecnico_archivo))); !!}
                        </div>
                        <div class="col-md-4">
                            {!! BootForm::text('fojas_tecnico_archivo', 'Número de fojas  *', old('fojas_tecnico_archivo', ($turnoarchivo->fojas_tecnico_archivo))); !!}
                        </div>
                    </div>
                </div>
                <div class="row"> Expediente de Seguimiento:
                    <div class="row">
                        <div class="col-md-2">
                            {!! BootForm::text('legajos_seg_archivo', 'Número de legajos  *', old('legajos_seg_archivo', ($turnoarchivo->legajos_seg_archivo))); !!}
                        </div>
                        <div class="col-md-4">
                            {!! BootForm::text('fojas_seg_archivo', 'Número de fojas  *', old('fojas_seg', ($turnoarchivo->fojas_seg_archivo))); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('turno_archivo', 'Relación de expedientes al archivo: *', old('turno_archivo', $turnoarchivo->turno_archivo)) !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_notificacion_archivo', 'Fecha de entrega *', old('fecha_notificacion_archivo', fecha($turnoarchivo->fecha_notificacion_archivo, 'Y-m-d'))); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"> 
                        @canany(['turnoarchivo.store','turnoarchivo.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcan
                        <a href="{{ route('turnoarchivo.index') }}" class="btn btn-secondary me-2">Cancelar</a>
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
