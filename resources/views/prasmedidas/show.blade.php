@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('prasmedida.show',$auditoria,$pras) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                <a href="{{ route('prasturno.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Medida de apremio
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            @include('layouts.contextos._accion')
            @include('layouts.contextos._pras')
            <h4 class="text-primary">Acuses</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Medida de apremio</th>
                                <th>Fecha del acuse de la medida de apremio</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="text-center">
                                        @if (!empty($pras->oficio_medida_apremio))
                                            <a href="{{ asset($pras->oficio_medida_apremio) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($pras->oficio_medida_apremio)) ?>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($pras->fecha_acuse_medida_apremio))
                                            {{  fecha($pras->fecha_acuse_medida_apremio) }}
                                        @endif
                                    </td>                                    
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
@endsection
