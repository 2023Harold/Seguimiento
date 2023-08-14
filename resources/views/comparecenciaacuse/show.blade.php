@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('comparecenciaacuse.show',$comparecencia) }}  
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <a href="{{ route('radicacion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                &nbsp; Acuses
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            @include('layouts.contextos._auditoria') 
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label>Acuerdo de radicación: </label>
                    <span class="text-primary">
                        <a href="{{ asset($auditoria->radicacion->oficio_acuerdo) }}" target="_blank">
                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->radicacion->oficio_acuerdo)) ?>
                        </a>           
                    </span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <label>Acuse del oficio de designación: </label>
                    <span class="text-primary">
                        <a href="{{ asset($auditoria->radicacion->oficio_designacion) }}" target="_blank">
                            <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->radicacion->oficio_designacion)) ?>
                        </a> <br>      
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">                         
                    <label>Fecha del acuerdo de radicación: </label>
                    <span class="text-primary">
                        {{ fecha($auditoria->radicacion->fecha_oficio_acuerdo) }}
                    </span>
                </div>  
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">                         
                    <label>Fecha del oficio de designación: </label>
                    <span class="text-primary">
                        {{ fecha($auditoria->radicacion->fecha_oficio_acuerdo) }}
                    </span>
                </div>
            </div>
            <h4 class="text-primary">Acuses</h3>                   
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Comprobante de recepción depto. de notificaciones</th>
                                <th>Acuse del oficio de la comparecencia</th>                                                                                            
                            </tr>
                        </thead>
                        <tbody>                            
                                <tr>
                                    <td class="text-center">
                                        @if (!empty($auditoria->comparecencia->oficio_recepcion))
                                            <a href="{{ asset($auditoria->comparecencia->oficio_recepcion) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_recepcion)) ?>
                                            </a><br>
                                            <small>{{  fecha($auditoria->comparecencia->fecha_recepcion) }}</small>
                                        @endif  
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($auditoria->comparecencia->oficio_acuse))
                                            <a href="{{ asset($auditoria->comparecencia->oficio_acuse) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($auditoria->comparecencia->oficio_acuse)) ?>
                                            </a><br>
                                            <small>{{  fecha($auditoria->comparecencia->fecha_acuse) }}</small>
                                        @endif                                                                   
                                    </td>                                                                
                                </tr>       
                        </tbody>
                    </table> 
                </div> 
            </div>
    </div>
@endsection
