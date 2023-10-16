@extends('layouts.appPopup')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    &nbsp;Oficios de contestación
                </h1>
            </div>
            <div class="card-body">                      
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