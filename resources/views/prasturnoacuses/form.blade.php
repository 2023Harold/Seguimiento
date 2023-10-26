@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('prasturnoacuses.edit',$auditoria,$pras) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                <a href="{{ route('prasturno.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Acuses
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            @include('layouts.contextos._accion')
            @include('layouts.contextos._pras')
            {!! BootForm::open(['model' => $pras,'update' => 'prasturnoacuses.update','id' => 'form',]) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('oficio_comprobante', 'Comprobante de recepción depto. de notificaciones: *', old('oficio_comprobante', $pras->oficio_comprobante)) !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_recepcion', 'Fecha del comprobante: *', old('fecha_recepcion', fecha($pras->fecha_recepcion, 'Y-m-d'))); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! archivo('oficio_acuse', 'Acuse del turno del PRAS: *', old('oficio_acuse', $pras->oficio_acuse)) !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_acuse', 'Fecha del acuse: *', old('fecha_acuse', fecha($pras->fecha_acuse, 'Y-m-d'))); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {!! BootForm::date('fecha_proxima_seguimiento', 'Fecha próxima de seguimiento: *', old('fecha_proxima_seguimiento', fecha($pras->fecha_proxima_seguimiento, 'Y-m-d')),['readonly']); !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {{-- @can('comparecenciaacuse.update')  --}}
                            @btnSubmit("Guardar")
                        {{-- @endcan --}}
                        @btnCancelar('Cancelar', route('prasturno.index'))
                    </div>
                </div>
            {!! BootForm::close() !!}
        </div>
    </div>
@endsection
@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\PRASTurnoAcusesRequest') !!}
    <script>
        $(document).ready(function() {
                  $("#fecha_acuse").on("change", function() {
                      let pd = $(this).val();
                      fechaTermino();
                  });

                  function fechaTermino() {
                      let pickedDate = $("#fecha_acuse").val();
                      let date = new Date(pickedDate);
                      date.setDate(date.getDate() + 2);
                      for (let index = 1; index < 31; index++) {
                          date.setDate(date.getDate() + 1);
                          if (date.getDay() == 6 || date.getDay() == 0)
                              index--;
                      }
                      var dd = String(date.getDate()).padStart(2, '0');
                      var mm = String(date.getMonth() + 1).padStart(2, '0');
                      var yyyy = date.getFullYear();
                      today = yyyy + '-' + mm + '-' + dd;
                      $("#fecha_proxima_seguimiento").val(today);
                  }
              });
    </script>
@endsection
