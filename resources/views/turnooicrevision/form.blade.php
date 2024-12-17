@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('turnooicrevision.edit',$turnooic) }}  
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('turnooic.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; Revisar
                </h1>
                <div class="float-end">
                    <a href="{{route('turnooic.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;Of. R_OICs</a>                         
                </div>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._turnooic')
                {!! BootForm::open(['model' => $turnooic,'update'=>'turnooicrevision.update','id'=>'form'] )!!}
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::radios("estatus", ' ',
                        [ 
                            'Aprobado' => 'Aprobar',
                            'Rechazado' => 'Rechazar'
                        ], null,false,['class'=>'i-checks rechazado']); !!}
                    </div>
                </div>
                <div class="row" id="justificacion" style="display: none;">
                    <div class="col-md-12">
                        {!! BootForm::textarea('motivo_rechazo','Motivo del rechazo:*','',["rows" => "2", "style" => "rezise:none"])!!}
                    </div>
                </div>
                <div class="row" id="enviar" style="display: none;">
                    <div class="col-md-6 mb-3">
                        {!! BootForm::checkbox('reenviar', 'Se envía al superior para su validación', '', true, ['class' => 'i-checks', 'disabled']) !!}
                    </div>
                </div>                     
                <div class="row mt-3">
                    <div class="col-md-6 justify-content-end">
                        @can('turnooicrevision.update')
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcan 
                        <a href="{{ route('turnooic.index') }}" class="btn btn-secondary me-2">Cancelar</a>
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
