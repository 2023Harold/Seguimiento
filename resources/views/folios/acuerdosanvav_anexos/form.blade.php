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
                  &nbsp;{{$anexoacuerdoaccion}} Anexos Acuerdos de Valoración y No Valoración       
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            {{--@include('layouts.contextos._radicacion')--}}
            @include('layouts.contextos._folio')

            {!!BootForm::open(['model' => $anexosacuerdoanvav, 'store' => 'anexosanvav.store', 'update' => 'anexosanvav.update', 'id' => 'form']) !!}
                <div class="row">
                    <div class="col-md-4">
                        {!!archivo('oficio_contestacion_general', 'Anexos: ', old('oficio_contestacion_general', $acuerdoanvav->oficio_contestacion_general)) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!!BootForm::text('numero_oficio', 'Número de oficio que presenta la entidad: ', old('numero_oficio', $acuerdoanvav->numero_oficio_ent)) !!}
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
