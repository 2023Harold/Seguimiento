@extends('layouts.app')
@section('breadcrums')
@if (empty($informe->numero_ordenauditoria))
    {{ Breadcrumbs::render('informeprimeraetapa.create') }}
@else
    {{ Breadcrumbs::render('informeprimeraetapa.edit',$informe) }}
@endif    
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('informeprimeraetapa.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; informe Primera Etapa
                </h1>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['model' => $informe,'store' => 'informeprimeraetapa.store','update' => 'informeprimeraetapa.update','id' => 'form']) !!}
                {{-- {!! BootForm::hidden('entidad_fiscalizable_id',$auditoria->entidad_fiscalizable_id,['id'=>'entidad_fiscalizable_id']) !!}        --}}
                {{-- <div class="col-md-2">
                    {!! BootForm::number('consecutivo', "No. Consecutivo:", old('consecutivo', $request->consecutivo)) !!}
                </div> --}}
                <div class="row">
                    <div class="col-md-8">
                        {!! BootForm::text('numero_ordenauditoria', 'Número de la orden de la auditoría: *', old('numero_ordenauditoria', $informe->numero_ordenauditoria)) !!}
                    </div>
                </div>       
                {{-- <div class="row">
                    <div class="col-md-5">
                        {!! BootForm::date('fecha_notificacion_oficio', 'Fecha de notificación del oficio con el cual se le entregó a la entidad fiscalizada el informe de auditoría: *', old('fecha_notificacion_oficio', fecha($auditoria->fecha_notificacion_oficio, 'Y-m-d'))); !!}
                    </div>
                </div>        --}}
                <div class="row">
                    <div class="col-md-8">
                        {!! BootForm::text('numero_oficio_entro', 'Número de oficio por el cual se entregó el informe de auditoría: *', old('numero_oficio_entro', $informe->numero_oficio_entro)) !!}
                    </div>
                </div>       
                <div class="row">
                    <div class="col-md-8">
                        {!! BootForm::text('acta_reunion_resultados', 'Acta de reunión de resultados y cierre de auditoría: *', old('acta_reunion_resultados', $informe->acta_reunion_resultados)) !!}
                    </div>            
                </div>       
                <div class="row">
                    <div class="col-md-8">
                        {!! BootForm::date('fecha_notificación', 'Fecha de notificación del oficio por el cual se remitieron las constancias que comprenden el informe de seguimiento: *', old('fecha_notificación', $informe->fecha_notificación)) !!}
                    </div>            
                </div>       
                <div class="row">
                    <div class="col-md-4">
                        {!! BootForm::text('informe_seguimiento', 'Informe de seguimiento: *', old('informe_seguimiento', $informe->informe_seguimiento)) !!}
                    </div>            
                    {{-- <div class="col-md-4">                                 
                        {!! BootForm::text('fojas_utiles', 'Fojas útiles: *', old('fojas_utiles', $informe->fojas_utiles)) !!}
                    </div>             --}}
                </div>       
                {{-- <div class="row">
                    <div class="col-md-8">
                        {!! BootForm::text('clave_accion_pliego', 'Número de acta administrativa de comparecencia : *', old('clave_accion_pliego', $informe->clave_accion_pliego)) !!}
                    </div>            
                </div> --}}
                <div class="row">
                    <div class="col-md-6"> 
                        {{-- @canany(['informeprimeraetapa.store','informeprimeraetapa.update'])      --}}
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        {{-- @endcanany --}}
                        <a href="{{ route('informeprimeraetapa.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    </div>
                </div>
                {!! BootForm::close() !!}    
            </div>    
        </div>  
    </div>
</div> 
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\InformePrimeraEtapaRequest') !!}
@endsection
