@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('folioscrr.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('auditoriaseguimiento.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Folios
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                        @can('folioscrr.create')
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('folioscrr.create') }}"  class="btn btn-primary float-end">
                                        <i class="align-middle fas fa-file-circle-plus" aria-hidden="true"></i> Agregar Folio
                                    </a>
                                </div>
                            </div>
                        @endcan
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Oficio</th>
                                <th>Remitentes</th>
                                <th>Recepción en oficialía</th>
                                <th>Fecha de recepción en la unidad de seguimiento</th>
                                @if(auth()->user()->siglas_rol=="ANA")
                                    <th></th>
                                @endif
                                <th>Acuerdos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($folios as $folio)
                            <tr>
                                <td class="text-center">
                                    <a href="{{ asset($folio->oficio_contestacion_general) }}" target="_blank">
                                        <?php echo htmlspecialchars_decode(iconoArchivo($folio->oficio_contestacion_general)) ?>
                                    </a> <br>
                                    <small>Número de oficio: {{ $folio->numero_oficio }}</small> <br>
                                    <small>Fecha: {{ fecha($folio->fecha_oficio_contestacion) }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('folioscrr.show',$folio) }}" class="btn btn-primary">
                                        <i class="fa fa-magnifying-glass"></i> Consultar
                                    </a>
                                    {{-- $folio->nombre_remitente --}} <br>
                                  {{--  <span class="badge-light-dark text-gray-500">{{ $folio->cargo_remitente }}</span>--}}
                                </td>
                                <td class="text-center">
                                    CRR: {{ $folio->folio }} <br>
                                    Fecha: {{ fecha($folio->fecha_recepcion_oficialia) }}
                                </td>
                                <td class="text-center">
                                    {{ fecha($folio->fecha_recepcion_us) }}
                                </td>

                                @if(auth()->user()->siglas_rol=="ANA")
                                    <td>
                                        @if(empty($folio->usuario_modificacion_id))
                                            <a href="{{ route('folioscrr.edit', $folio) }}"  class="btn btn-primary float-end">
                                                <i class="align-middle fas fa-edit" aria-hidden="true"></i> 
                                            </a>
                                        @endif
                                    </td>
                                @endif
                                <td class="text-center">
                                    <a href="{{ route('foliosanexos.index', $folio) }}" class="btn btn-primary">
                                        <span class="fa fa-file-circle-plus" aria-hidden="true"></span>&nbsp; Ingresar
                                    </a>
                                    @can('foliosanexos.show')
                                        <a href="{{ route('comparecenciaacuse.show', $folio) }}" class="btn btn-secondary" >
                                            <img alt="Logo" src="{{asset('assets/img/consultar.png')}}" class="h-30px logo" />
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan=5>
                                    No se han registrado datos en este apartado
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
