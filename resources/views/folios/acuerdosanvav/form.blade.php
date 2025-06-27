@extends('layouts.app')
@section('breadcrums')
    @if ($acuerdoaccion =='Agregar')
        {{Breadcrumbs::render('acuerdosanvav.create',$auditoria) }}
    @else
        {{Breadcrumbs::render('acuerdosanvav.edit',$auditoria) }}
    @endif  
@endsection
@section('content')
<div class="row">
  @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
      <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('folioscrr.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                  &nbsp;{{$acuerdoaccion}} Acuerdos de Valoración y No Valoración         
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            {{--@include('layouts.contextos._radicacion')--}}
            @include('layouts.contextos._folio')

            {!!BootForm::open(['model' => $acuerdoanvav, 'store' => 'acuerdosanvav.store', 'update' => 'acuerdosanvav.update', 'id' => 'form']) !!}
            {!!BootForm::hidden('folio_id',$folio->id) !!}
            {!!BootForm::hidden('auditoria_id',$auditoria->id) !!}


                <div class="row">
                    <div class="col-md-4">
                        {!!BootForm::text('numero_expediente', 'Número de expediente: *', old('numero_expediente', $acuerdoanvav->numero_expediente)) !!}
                    </div>
                    <div class="col-md-2">
                        {!!BootForm::text('tipo_doc', 'Tipo de documento: *', old('tipo_doc', $acuerdoanvav_tipo_of)) !!}
                    </div>
                </div>
                <br>    
                <div class="row">
                    <div class="col-md-4">
                        {!!BootForm::text('numero_oficio', 'Número de oficio que presenta la entidad: ', old('numero_oficio', $acuerdoanvav->numero_oficio_ent)) !!}
                    </div>
                    <div class="col-md-3">
                        {!!BootForm::date('fecha_oficio', 'Fecha oficio que presenta la entidad: ', old('fecha_oficio',fecha($acuerdoanvav->fecha_oficio_ent, 'Y-m-d')),['onchange'=>'handler(event)']) !!}
                    </div>
                </div> 
                <br>
                <div class="row">
                    <div class="col-md-4">
                        {!!BootForm::text('nombre_informe_au', 'Nombre del informe de auditoria: *', old('nombre_informe_au', $acuerdoanvav->nombre_informe_au)) !!}
                    </div>
                    <div class="col-md-4">
                        {!!BootForm::text('cargo_informe_au', 'Cargo del informe de auditoria: *', old('cargo_informe_au', $acuerdoanvav->cargo_informe_au)) !!}
                    </div>
                    <div class="col-md-4">
                        {!!BootForm::text('administracion_informe_au', 'Administracion del informe de auditoria: *', old('administracion_informe_au', $acuerdoanvav->administracion_informe_au)) !!}
                    </div>
                </div> 
                <br>             
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
    {!!JsValidator::formRequest('App\Http\Requests\AcuerdosValoracionRequest') !!} 

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
