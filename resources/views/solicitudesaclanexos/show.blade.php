@extends('layouts.appPopup')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    &nbsp;Anexos
                </h1>
            </div>
            <div class="card-body">                      
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Archivo</th>
                                <th>Nombre del archivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($anexos)>0)
                                @foreach($anexos as $anexo)
                                    <tr>
                                        <td class="text-center">
                                            {{ str_pad($anexo->consecutivo, 3, '0', STR_PAD_LEFT) }}                                          
                                        </td>  
                                        <td class="text-center">
                                            <a href="{{ asset($anexo->archivo) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($anexo->archivo)) ?>
                                            </a>                                           
                                        </td>                                                              
                                        <td>
                                           {{ $anexo->nombre_archivo }} <br>
                                           <span class="badge-light-dark text-gray-500">{{ $anexo->nombre_archivoe }}</span> 
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
                    {{ $anexos->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection