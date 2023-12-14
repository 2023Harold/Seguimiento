@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('prasseguimiento.show',$auditoria,$pras) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('prasturno.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp; Seguimiento
                </h1>
            </div>
            <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria')
            @include('layouts.contextos._accion')
            @include('layouts.contextos._pras')
            <h4 class="text-primary">Seguimiento</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Contestación OIC</th>
                                <th>Fecha del acuse de contestación</th>
                                <th>Estatus de cumplimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="text-center">
                                        @if (!empty($pras->oficio_contestacion))
                                            <a href="{{ asset($pras->oficio_contestacion) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($pras->oficio_contestacion)) ?>
                                            </a>                                           
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ fecha($pras->fecha_acuse_contestacion) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($pras->estatus_cumplimiento=='No Atendido')
                                            <span class="badge badge-light-danger">{{ $pras->estatus_cumplimiento }} </span><br>
                                        @else
                                            <span class="badge badge-light-success">{{ $pras->estatus_cumplimiento }} </span><br>
                                        @endif
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label>Conclusión: </label><br>
                        {!! BootForm::textarea('accionpraslb', false,old('accionpraslb', $pras->conlusion_pras),['rows'=>'3','disabled']) !!}
                    </div>
                </div>     
            </div>
        </div>
    </div>
</div>
@endsection
