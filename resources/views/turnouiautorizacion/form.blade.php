@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('turnouiautorizacion.edit',$turnoui) }}  
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('turnoui.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
                    &nbsp; Autorizar
                </h1>
                <div class="float-end">
                    <a href="{{route('turnoui.exportar')}}" class="btn btn-light-primary"><span class="fa fa-file-word"></span>&nbsp;&nbsp;&nbsp;Of. UI</a>                         
                </div>
            </div>        
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._turnoui')
                {!! BootForm::open(['model' => $turnoui,'update'=>'turnouiautorizacion.update','id'=>'form'] )!!}                                                                
                    <div class="row" style="padding-left: 2rem;">
                        <div class="col-md-6">
                            {!! BootForm::radios("estatus", ' ',
                            [
                                'Aprobado' => 'Aprobar',
                                'Rechazado' => 'Rechazar'
                            ], null,false,['class'=>'i-checks rechazado']); !!}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 justify-content-end">
                            @can('turnouiautorizacion.update')                                                                                                          
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            @endcan                                    
                             <a href="{{ route('turnoui.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                        </div>
                    </div>
                {!! BootForm::close() !!}
                </div>
    </div>
</div> 
@endsection
@section('script')
{{-- {!! JsValidator::formRequest('App\Http\Requests\InformePrimeraEtapaRequest') !!} --}}
@endsection
