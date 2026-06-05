@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('recomendacionescontestaciones.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
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
                @include('layouts.contextos._auditoria')
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
                <div class="row">
                    <div class="col-md-12">    
           
                        <span>                            
                            <a class="btn btn-primary float-end" href="{{ route('recomendacionescontestaciones.create') }}">
                                Agregar
                            </a>                        
                        </span>     
           
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
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($contestaciones)>0)
                                @foreach($contestaciones as $contestacion)
                                    <tr>
                                        <td class="text-center">
                                            
                                            {{-- @can('comparecenciaanexo.edit') --}}

                                                {{-- <a href="{{route('comparecenciaanexo.edit', $anexo)}}"> --}}
                                                {{ str_pad($contestacion->consecutivo, 3, '0', STR_PAD_LEFT) }}
                                                {{-- </a> --}}
                                            {{-- @else
                                                {{ str_pad($anexo->numero, 3, '0', STR_PAD_LEFT) }}
                                            @endcan --}}
                                        </td>  
                                        <td class="text-center">
                                            <a href="{{ asset($contestacion->oficio_contestacion) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($contestacion->oficio_contestacion)) ?>
                                            </a> <br>
                                            <small>Número de oficio: {{ $contestacion->numero_oficio }}</small> <br>
                                            <small>Fecha: {{ fecha($contestacion->fecha_oficio_contestacion) }}</small> 
                                        </td>                                                              
                                        <td>
                                            @foreach ($contestacion->remitentes as $remitente)
                                                {{ $remitente->nombre_remitente ?? $contestacion->nombre_remitente }} <br>
                                                <span class="badge-light-dark text-gray-500">{{ $remitente->cargo_remitente ?? $contestacion->cargo_remitente}}</span>
                                                
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            CRR: {{ $contestacion->folio_correspondencia }} <br>
                                            Fecha: {{ fecha($contestacion->fecha_recepcion_oficialia) }}                                            
                                         </td>
                                        <td class="text-center">
                                            {{ fecha($contestacion->fecha_recepcion_seguimiento) }}                                            
                                         </td>
                                        <td class="text-center">
                                            <a href="{{route('recomendacionescontestaciones.edit', $contestacion)}}" class="btn btn-color-primary btn-active-color-warning icon-hover">
                                                <i class="fa-solid fa-pen-to-square" style="font-size: 16px;" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{-- @can('comparecenciaanexo.destroy') --}}
                                                {{-- @destroy(route('recomendacionescontestaciones.destroy', $contestacion)) --}}
                                                <a href="{{ route('recomendacionescontestaciones.eliminar', $contestacion) }}" class="btn btn-link btn-active-color-danger sweet-contestacion"
                                                    data-success-text="El oficio de contestación se eliminó correctamente.">
                                                        <span class="bi bi-trash-fill" style="font-size: 1.6rem;"></span>
                                                </a>
                                            {{-- @endcan --}}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class='text-center' colspan="7">No se encuentran contestaciones registradas para la entidad.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $contestaciones->appends(['consecutivo'=>$request->consecutivo,'nombre_documento'=>$request->nombre_documento])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.popupcomentario').colorbox({
                width:"80%",
                height:"1050px",
                maxWidth:700,
                maxHeight:"1050px",
                iframe: true,
                onClosed: function() {
                    location.reload(true);
                },
                onComplete: function () {
                 $(this).colorbox.resize({width:"80%",maxWidth:600, height:"800px", maxHeight:"800px"});
                 $(window).trigger("resize");
                }
            });
        });
    </script>
@endsection