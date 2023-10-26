@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('recomendacionescontestaciones.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('recomendacionesatencion.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;Oficios de contestación
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="card-title text-primary float">Atención de la recomendación</h3>                        
                    </div>
                    <div class="card-body py-7">    
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                <label>Fecha compromiso de atención: </label>
                                <span class="text-primary">
                                    {{ fecha($accion->fecha_termino_recomendacion) }}
                                </span>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                                <label>Nombre del responsable de la entidad fiscalizable: </label>
                                <span class="text-primary">
                                    {{$recomendacion->nombre_responsable }}
                                </span>
                            </div>  
                            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                <label>Cargo del responsable: </label>
                                <span class="text-primary">
                                    {{$recomendacion->cargo_responsable }} 
                                </span>
                            </div>                            
                            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                <label>Responsable del seguimiento: </label>
                                <span class="text-primary">
                                    {{$accion->analista->name }} 
                                </span>
                            </div>                            
                        </div>
                        <hr/>
                    </div>                    
                </div>                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Oficio</th>
                                <th>Remitente</th>
                                <th>Recepción en oficialía</th>
                                <th>Fecha de recepción en la unidad de seguimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($contestaciones)>0)
                                @foreach($contestaciones as $contestacion)
                                    <tr>
                                        <td class="text-center">
                                            {{ str_pad($contestacion->consecutivo, 3, '0', STR_PAD_LEFT) }}                                          
                                        </td>  
                                        <td class="text-center">
                                            <a href="{{ asset($contestacion->oficio_contestacion) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($contestacion->oficio_contestacion)) ?>
                                            </a> <br>
                                            <small>Número de oficio: {{ $contestacion->numero_oficio }}</small> <br>
                                            <small>Fecha: {{ fecha($contestacion->fecha_oficio_contestacion) }}</small> 
                                        </td>                                                              
                                        <td>
                                           {{ $contestacion->nombre_remitente }} <br>
                                           <span class="badge-light-dark text-gray-500">{{ $contestacion->cargo_remitente }}</span> 
                                        </td>
                                        <td class="text-center">
                                            CRR: {{ $contestacion->folio_correspondencia }} <br>
                                            Fecha: {{ fecha($contestacion->fecha_recepcion_oficialia) }}                                            
                                         </td>
                                        <td class="text-center">
                                            {{ fecha($contestacion->fecha_recepcion_seguimiento) }}                                            
                                         </td>                                        
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class='text-center' colspan="7">No hay datos registrados en este apartado.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $contestaciones->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection