@extends('layouts.app')
@section('breadcrums')
    @if(getSession('cp')==2023)
        {{ Breadcrumbs::render('acuerdoconclusionacusecp.edit',$acuerdoconclusion,$auditoria) }} 
    @else
        {{ Breadcrumbs::render('acuerdoconclusionacuse.edit',$acuerdoconclusion,$auditoria) }} 
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
                    &nbsp; Acuses
                </h1>                
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._acuerdoconclusion')
                
               {!! BootForm::open(['model' => $acuerdoconclusion,'store' => 'acuerdoconclusionacuse.store','update' => 'acuerdoconclusionacuse.update','id' => 'form']) !!}
                <div class="row">
                        <div class="col-md-6">
                            {!! archivo('oficio_recepcion', 'Comprobante de recepción depto. de notificaciones: *', old('oficio_recepcion', $acuerdoconclusion->oficio_recepcion)) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::date('fecha_recepcion', 'Fecha de recepción: *', old('fecha_recepcion', fecha($acuerdoconclusion->fecha_recepcion, 'Y-m-d'))); !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {!! archivo('oficio_acuse', 'Acuse de notificación de acuerdos: *', old('oficio_acuse', $acuerdoconclusion->oficio_acuse)) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::date('fecha_acuse', 'Fecha del acuse: *', old('fecha_acuse', fecha($acuerdoconclusion->fecha_acuse, 'Y-m-d'))); !!}
                        </div>
                    </div>                
                    <div class="row">
                        <div class="col-md-12">
                            @can(['acuerdoconclusionacuse.store','acuerdoconclusionacuse.update']) 
                              <button type="submit" class="btn btn-primary">Guardar</button>
                            @endcan
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
    {!! JsValidator::formRequest('App\Http\Requests\ComparecenciaAcuseRequest') !!}  
@endsection
