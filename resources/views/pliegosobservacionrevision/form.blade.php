@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('pliegosobservacionrevision.edit', $pliegosobservacion, $auditoria)}}
@endsection
@section('content')
    <div class="row">
      @include('layouts.partials._menu')
        <div class="col-md-9 mt-2">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">
                        <a href="{{ route('pliegosobservacionatencion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                        Revisar
                    </h1>
                </div>
                <div class="card-body">
                    @include('flash::message')
                    @include('layouts.contextos._auditoria')
                    @include('layouts.contextos._accionpliego')
                    @include('layouts.contextos._pliego')
                    {!!BootForm::open(['model' => $pliegosobservacion,'update'=>'pliegosobservacionrevision.update','id'=>'form'] )!!}
                        <div class="row" style="padding-left: 2rem;">
                            <div class="col-md-6">
                                {!!BootForm::radios("estatus", ' ',
                                [
                                    'Aprobado' => 'Aprobar',
                                    'Rechazado' => 'Rechazar'
                                ], null,false,['class'=>'i-checks rechazado']) !!}
                            </div>
                        </div>
                        <div class="row" id="justificacion" style="display: none; padding-left: 2rem;">
                            <div class="col-md-12">
                                {!!BootForm::textarea('motivo_rechazo','Motivo del rechazo:*','',["rows" => "2", "style" => "rezise:none"])!!}
                            </div>
                        </div>
                        <div class="row" id="enviar" style="display: none; padding-left: 2rem;">
                            <div class="col-md-6 mb-3">
                                {!!BootForm::checkbox('reenviar', 'Se envía al superior para su revisión', '', true, ['class' => 'i-checks', 'disabled']) !!}
                            </div>
                        </div>
                        <div class="row mt-3" style="padding-left: 2rem;">
                            <div class="col-md-6 justify-content-end">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                                <a href="{{ route('pliegosobservacionatencion.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            </div>
                        </div>
                    {!!BootForm::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {!!JsValidator::formRequest('App\Http\Requests\AprobarFlujoAutorizacionRequest') !!}
@endsection
