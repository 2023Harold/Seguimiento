@extends('layouts.appPopup')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">           
           Agregar {{ $tipo }}
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        {!!BootForm::open(['model' => $cedula, 'store' => 'agregarcedulainicial.store', 'update' => 'agregarcedulainicial.update', 'id' => 'form']) !!}
            <div class="row">
                {!!BootForm::hidden('tipo', $tipo) !!}
            
                {!! archivo('cedula_cargada', ' '.$tipo,  old('cedula_cargada', $auditoria->cedula)) !!}
            </div>
          <div class="col-md-6">
                        @canany(['agregarcedulainicial.store','agregarcedulainicial.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcanany
                        <a href="{{ route('cedulainicial.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    </div>
        {!!BootForm::close() !!}
    </div> 
</div>
@endsection
@section('script')

@endsection
