@extends('layouts.app')

@section('breadcrums')
    @if (empty($remitente->nombre_remitente))
        {{Breadcrumbs::render('remitentes.remitentecrear', $folioscrr) }}
    @else
        {{Breadcrumbs::render('remitentes.edit', $remitente) }}
    @endif
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')

    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('folioscrr.index', $auditoria) }}">
                        <i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i>
                    </a>
                    &nbsp; Agregar Remitentes
                </h1>
            </div>

            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')

                {{-- {!!BootForm::open(['route' => 'foliosremitentes.update', 'method' => 'post']) !!} --}}
                
                {!!BootForm::open(['model' => $folioremitente,'store' => 'remitentes.store','update' => 'remitentes.update','id' =>'form',]) !!}
                

                {{-- Campos ocultos --}}
                {!!BootForm::hidden('folio_id', $folioscrr->id) !!}

                <div class="row">
                    <div class="col-md-6">
                        {!!BootForm::text('nombre_remitente', 'Nombre del remitente: *', old('nombre_remitente', $folioremitente->nombre_remitente)) !!}
                    </div>
                    <div class="col-md-6">
                        {!!BootForm::text('cargo_remitente', 'Cargo del remitente: *', old('cargo_remitente', $folioremitente->cargo_remitente)) !!}
                    </div>
                    <div class="col-md-6">
                        {!!BootForm::text('domicilio_remitente', 'Domicilio notificación : ', old('domicilio_remitente', $folioremitente->domicilio_remitente)) !!}
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        @canany(['remitentes.store', 'remitentes.update'])
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        @endcan
                        <a href="{{ route('remitentes.show', $folioscrr) }}" class="btn btn-secondary me-2">Cancelar</a>
                    </div>
                </div>

                {!!BootForm::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    {{--!!JsValidator::formRequest('App\Http\Requests\FoliosRequest') !!--}}
    <script>
        // Puedes agregar JS adicional aquí
    </script>
@endsection
