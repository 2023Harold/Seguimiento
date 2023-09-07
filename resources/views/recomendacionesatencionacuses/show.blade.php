@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('recomendacionesacuses.show',$recomendacion) }}  
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('recomendacionesatencion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Acuses
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria') 
            @include('layouts.contextos._accion') 
            @include('layouts.contextos._recomendacion')            
            <h4 class="text-primary">Acuses</h3>                   
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Comprobante de recepción depto. de notificaciones</th>
                                <th>Acuse del turno del PRAS</th>                                                                                           
                            </tr>
                        </thead>
                        <tbody>                            
                                <tr>
                                    <td class="text-center">
                                        @if (!empty($recomendacion->oficio_comprobante))
                                            <a href="{{ asset($recomendacion->oficio_comprobante) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($recomendacion->oficio_comprobante)) ?>
                                            </a><br>
                                            <small>{{  fecha($recomendacion->fecha_comprobante) }}</small>
                                        @endif  
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($recomendacion->oficio_acuse))
                                            <a href="{{ asset($recomendacion->oficio_acuse) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($recomendacion->oficio_acuse)) ?>
                                            </a><br>
                                            <small>{{  fecha($recomendacion->fecha_acuse) }}</small>
                                        @endif                                                                   
                                    </td>                                                                                                  
                                </tr>       
                        </tbody>
                    </table> 
                </div> 
            </div>
    </div>
@endsection
