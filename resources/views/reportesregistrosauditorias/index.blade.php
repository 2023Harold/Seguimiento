@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('reportesregistrosauditorias.index',$auditorias) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Reportes Registros de Auditorias
                </h1>
            </div> 
            <div class="card-body">           
            <div class="table-responsive" >
                <table class="table" border="1" style="table-layout: fixed; width: 2000px;">
                    <thead>
                        <tr>
                            <th rowspan=2 style="width:60px" class="text-center"> Núm. Auditoría </th>                          
                            <th rowspan=2 style="width:60px" class="text-center"> Entidad Fiscalizable </th>                    
                            <th rowspan=2 style="width:60px" class="text-center"> Acto de Fiscalización </th>
                            <th rowspan=2 style="width:60px" class="text-center"> Monto por aclarar </th>
                            <th colspan=4 style="width:60px" class="text-center"> Acción </th>                            
                        </tr>  
                        <tr>                              
                            <th> No. de acción </th>    
                            <th> Tipo de acción </th>    
                            <th> Acto de Fiscalización </th>                                                        
                            <th> Monto por aclarar </th>                                                        
                        </tr>                                                                          
                    </thead>
                    <tbody>
                        @forelse ($auditorias as $auditoria)
                            <tr>                                                              
                                <td>
                                    {{ $auditoria->numero_auditoria }}
                                </td>
                                <td  width='40%'>
                                    @php
                                        $entidadparciales = explode("-", $auditoria->entidad_fiscalizable);
                                    @endphp
                                    @foreach ($entidadparciales as $entidadparcial)
                                        {{ mb_convert_encoding(mb_convert_case(strtolower($entidadparcial), MB_CASE_TITLE), "UTF-8"); }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    {{ $auditoria->acto_fiscalizacion }}                                    
                                </td> 
                                <td style="text-align: right!important;">
                                    {{ '$'.number_format( $auditoria->total(), 2) }}
                                </td>  
                                <td>
                                    {{ $auditoria->tipo_auditoria_id }}                                    
                                </td> 
                                <td>
                                    {{ $auditoria->tipo_auditoria_id }}                                    
                                </td> 
                                <td>
                                    {{ $auditoria->tipo_auditoria_id }}                                    
                                </td> 
                                {{-- <td class="text-center">
                                    {{ $accion->numero }}
                                </td>                                                      --}}
                                <td class="text-center">                                       
                                    {{-- <a href="{{ route('tipologiaauditorias.edit', $auditoria) }}" class="btn btn-primary">Ingresar</a>                                        --}}
                                </td>
                                                                 
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="8">
                                    <span class='text-center'>No hay registros en éste apartado</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                {{ $auditorias->appends(['consecutivo'=>$request->consecutivo,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
