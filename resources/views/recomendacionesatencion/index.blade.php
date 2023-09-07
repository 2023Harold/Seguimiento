@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('recomendacionesatencion.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('recomendacionesacciones.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;Atención de la recomendación
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @include('layouts.contextos._accion')
                <h3 class="card-title text-primary">Atención de la recomendación</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha compromiso de atención</th>
                                <th>Nombre del responsable de la entidad fiscalizable</th>
                                <th>Cargo del responsable</th>
                                <th>Responsable del seguimiento</th>
                                <th>Oficio de la contestación de la recomendación</th>
                                <th>Fase / Constancia</th>
                                <th>Acuses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recomendaciones as $recomendacion)
                            <tr>
                                <td class="text-center">
                                    {{ fecha($accion->fecha_termino_recomendacion) }}
                                </td>
                                <td>
                                    {{$recomendacion->nombre_responsable }}
                                </td>
                                <td>
                                    {{$recomendacion->cargo_responsable }}
                                </td>
                                <td>
                                    {{$accion->analista->name }}
                                </td>
                                <td class="text-center">
                                    @if (!empty($recomendacion->oficio_contestacion))
                                        <a href="{{ asset($recomendacion->oficio_contestacion) }}" target="_blank">
                                            <?php echo htmlspecialchars_decode(iconoArchivo($recomendacion->oficio_contestacion)) ?>
                                        </a>                      
                                    @endif
                                </td>                         
                                <td class="text-center">                                                 
                                     @if (empty($recomendacion->fase_autorizacion)||$recomendacion->fase_autorizacion=='Rechazado')   
                                        <span class="badge badge-light-danger">{{ $recomendacion->fase_autorizacion }} </span><br>
                                            @can('recomendacionesatencion.edit')
                                                <a href="{{ route('recomendacionesatencion.edit',$recomendacion) }}" class="btn btn-primary">
                                                    <span class="fas fa-edit text-primar" aria-hidden="true"></span>&nbsp; Editar
                                                </a>  
                                            @endcan
                                    @endif  
                                    @if ($recomendacion->fase_autorizacion == 'En revisión 01')                                                
                                        @can('recomendacionesrevision01.edit')
                                            <a href="{{ route('recomendacionesrevision01.edit',$recomendacion) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $recomendacion->fase_autorizacion }} </span>
                                        @endcan                                               
                                    @endif
                                    @if ($recomendacion->fase_autorizacion == 'En revisión')                                                
                                        @can('recomendacionesrevision.edit')
                                            <a href="{{ route('recomendacionesrevision.edit',$recomendacion) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Revisar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $recomendacion->fase_autorizacion }} </span>
                                        @endcan                                               
                                    @endif
                                    @if ($recomendacion->fase_autorizacion == 'En validación')                                                
                                        @can('recomendacionesvalidacion.edit')
                                            <a href="{{ route('recomendacionesvalidacion.edit',$recomendacion) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Validar
                                            </a>
                                        @else
                                            <span class="badge badge-light-warning">{{ $recomendacion->fase_autorizacion }} </span>
                                        @endcan                                               
                                    @endif
                                    @if ($recomendacion->fase_autorizacion == 'En autorización')                                                
                                        @can('recomendacionesautorizacion.edit')
                                            <a href="{{ route('recomendacionesautorizacion.edit',$recomendacion) }}" class="btn btn-primary">
                                                <li class="fa fa-gavel"></li>
                                                Autorizar
                                            </a> 
                                        @else
                                            <span class="badge badge-light-warning">{{ $recomendacion->fase_autorizacion }} </span>                                           
                                        @endcan
                                    @endif                                     
                                    @if ($recomendacion->fase_autorizacion=='Autorizado')
                                    <span class="badge badge-light-success">{{ $recomendacion->fase_autorizacion }} </span> <br>
                                        @btnFile($recomendacion->constancia)
                                        @btnXml($recomendacion, 'constancia')
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($recomendacion->fase_autorizacion=='Autorizado')
                                        @if (empty($recomendacion->oficio_comprobante))
                                            @can('recomendacionesacuses.edit')
                                                <a href="{{ route('recomendacionesacuses.edit', $recomendacion) }}" class="btn btn-primary">
                                                    <span class="fa fa-file-plus" aria-hidden="true"></span>&nbsp; Adjuntar
                                                </a>
                                            @endcan
                                        @else
                                            @can('recomendacionesacuses.show')
                                                <a href="{{ route('recomendacionesacuses.show', $recomendacion) }}" class="btn btn-secondary" >
                                                    <img alt="Logo" src="{{asset('assets/img/consultar.png')}}" class="h-30px logo" />
                                                </a>
                                            @endcan
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            {!! movimientosDesglose($recomendacion->id, 7, $recomendacion->movimientos) !!}
                            @empty
                            <td colspan="6" class="text-center">
                                No se encontraron registros en este apartado.
                            </td>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection