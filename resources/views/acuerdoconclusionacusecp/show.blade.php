@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('acuerdoconclusionacusecp.show',$acuerdoconclusion,$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('acuerdoconclusion.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp; Acuses
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
				@include('layouts.contextos._acuerdoconclusion')
                <div class="row">                  
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <label>Fecha del acuerdo de conclusión: </label>
                        <span class="text-primary">
                            {{  fecha($auditoria->acuerdoconclusion->fecha_oficio_acuerdo)  }}
                        </span>
                    </div>                    
                </div>                
                <h4 class="text-primary">Acuses</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>                                    
                                    <th>Comprobante de recepción depto. de notificaciones</th>
                                    <th>Acuse de notificación de informe de auditoría</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                                    
                                    <td class="text-center">
                                        @if (!empty($acuerdoconclusion->oficio_recepcion))
                                        <a href="{{ asset($acuerdoconclusion->oficio_recepcion) }}"
                                            target="_blank">
                                            <?php echo htmlspecialchars_decode(iconoArchivo($acuerdoconclusion->oficio_recepcion)); ?>
                                        </a><br>
                                        <small>{{ fecha($acuerdoconclusion->fecha_recepcion) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($acuerdoconclusion->oficio_acuse))
                                        <a href="{{ asset($acuerdoconclusion->oficio_acuse) }}" target="_blank">
                                            <?php echo htmlspecialchars_decode(iconoArchivo($acuerdoconclusion->oficio_acuse)); ?>
                                        </a><br>
                                        <small>{{ fecha($acuerdoconclusion->fecha_acuse) }}</small>
                                        @endif
                                    </td>                                   
                                </tr>
                            </tbody>
                        </table>
                    </div>                   
            </div>
        </div>
    </div>
</div>
@endsection