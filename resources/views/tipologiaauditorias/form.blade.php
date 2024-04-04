@extends('layouts.appPopup')
@section('content')

<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    Tipologia
                </h1>
            </div>
            <div class="card-body">
            {!! BootForm::open(['model' => $auditoria ,'update'=>'tipologiaauditorias.update','store'=>'tipologiaauditorias.store','id'=>'form'] )!!}

                <div class="row">
                    <div class="col-md-4">
                        {!! BootForm::select('tipologia_id', ' Tipologia:* ', $tipologias->toArray(),old('tipologia_id',$request->tipologia_id),['data-control'=>'select2','class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción']) !!}
                    </div>
                </div>
                <div class="row mt-3" style="padding-left: 2rem;">
                    <div class="col-md-6 justify-content-end">
                    @btnSubmit("Guardar")
                        {{-- <button type="submit" class="btn btn-primary">Guardar</button> --}}
                    </div>
                </div>
                   {!! BootForm::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{!! JsValidator::formRequest('App\Http\Requests\TipologiasRequest') !!}
@endsection
