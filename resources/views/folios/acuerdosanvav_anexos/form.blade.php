@extends('layouts.app')
@section('breadcrums')
    @if ($anexoacuerdoaccion =='Agregar')
        {{Breadcrumbs::render('anexosanvav.create',$auditoria) }}
    @else
        {{Breadcrumbs::render('anexosanvav.edit',$auditoria) }}
    @endif  
@endsection
@section('content')
<div class="row">
  @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
      <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('acuerdosanvav.show',$folio) }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                  &nbsp;{{$anexoacuerdoaccion}} Acuses Acuerdos de Valoración y No Valoración
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            {{--@include('layouts.contextos._radicacion')--}}
            @include('layouts.contextos._folio')
            
            {!!BootForm::open(['model' => $anexosacuerdoanvav, 'store' => 'anexosanvav.store', 'update' => 'anexosanvav.update', 'id' => 'form']) !!}
            {!!BootForm::hidden('consecutivo',$acuerdoanvav->consecutivo) !!}
                <div class="row">
                    <div class="col-md-4">
                        {!!BootForm::text('nombre_firmante', 'Nombre del remitente: *', old('administracion_firmante', $anexosacuerdoanvav->nombre_firmante)) !!}
                    </div>
                    <div class="col-md-4">
                        {!!BootForm::text('cargo_firmante', 'Cargo del remitente: *', old('administracion_firmante', $anexosacuerdoanvav->cargo_firmante)) !!}
                    </div>
                    <div class="col-md-4">
                        {!!BootForm::text('administracion_firmante', 'Administración del remitente: *', old('administracion_firmante', $anexosacuerdoanvav->administracion_firmante)) !!}
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-5">
                        {!! archivo('archivo', 'Acuerdo firmado: *', old('archivo', $anexosacuerdoanvav->archivo))!!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        {!! archivo('of_notificacion', 'Oficio Notificación: ', old('of_notificacion', $anexosacuerdoanvav->of_notificacion)) !!}
                    </div>
                    <div class="col-md-5">
                        {!!BootForm::date('fecha_notificacion', 'Fecha de notificación: ', old('fecha_notificacion', fecha($anexosacuerdoanvav->fecha_notificacion, 'Y-m-d'))) !!}
                    </div>
                </div>
                                             
                <div class="row">
                    <div class="col-md-12">
                        @btnSubmit("Guardar y Continuar")
                        @btnCancelar('Cancelar', route('folioscrr.index'))
                    </div>
                </div>
            {!!BootForm::close() !!}
        </div>
      </div>
    </div>
</div>
@endsection
@section('script')
    {{--!! JsValidator::formRequest('App\Http\Requests\ComparecenciaAcuseRequest') !!--}}  
    $(document).ready(function() {
        $('[data-control="select2"]').select2({
            tags: true,
            placeholder: function(){
                return $(this).data('placeholder');
            },
            width: '100%'
        });
    });
@endsection
