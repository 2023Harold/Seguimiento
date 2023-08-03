@extends('layouts.app')
@section('breadcrums')
    @if (!empty($movimiento)&&$movimiento=='consultar')
        {{ Breadcrumbs::render('seguimientoauditoriaacciones.consulta',$auditoria) }}
    @elseif (!empty($movimiento)&&$movimiento=='direccionconsultar')
        {{ Breadcrumbs::render('asignaciondireccion.accionesconsulta',$auditoria) }}
    @elseif (!empty($movimiento)&&$movimiento=='departamentoconsultar')
        {{ Breadcrumbs::render('asignaciondepartamento.accionesconsulta',$auditoria) }}
    @elseif(!empty($movimiento)&&$movimiento=='lideranalistaconsultar')
        {{ Breadcrumbs::render('asignacionlideranalista.accionesconsulta',$auditoria) }}
    @else  
        {{ Breadcrumbs::render('seguimientoauditoriaacciones.index',$auditoria) }}
    @endif   
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    @if (!empty($movimiento)&&$movimiento=='consultar')
                        <a href="{{ route('seguimientoauditoria.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>                         
                    @elseif (!empty($movimiento)&&$movimiento=='direccionconsultar')
                        <a href="{{ route('asignaciondireccion.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> 
                    @elseif (!empty($movimiento)&&$movimiento=='departamentoconsultar')
                        <a href="{{ route('asignaciondepartamento.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> 
                    @else
                        <a href="{{ route('seguimientoauditoria.edit',$auditoria) }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> 
                    @endif  
                    &nbsp; Acciones                     
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['route'=>'seguimientoauditoriaacciones.index','method'=>'GET']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            {!! BootForm::number('consecutivo', "No. Consecutivo:", old('consecutivo', $request->consecutivo)) !!}
                        </div>                       
                        <div class="col-md-4">
                            {!! BootForm::select('segtipo_accion_id', 'Tipo de acción: ', $tiposaccion->toArray(), old('segtipo_accion_id',$request->segtipo_accion_id),['data-control'=>'select2', 'class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::text('numero', "Número de acción:", old('numero', $request->numero)) !!}
                        </div>
                        <div class="col-md-3 mt-8">
                            <button type="submit" class="btn btn-primary">Buscar</button>                           
                        </div>
                    </div>
                {!! BootForm::close() !!}
                @if ($auditoria->registro_concluido=='No')
                    @can('seguimientoauditoriaacciones.create')
                        <div class="row">
                            <div class="col-md-12">
                                <a class="btn btn-primary float-end" href="{{ route('seguimientoauditoriaacciones.create') }}">
                                    Agregar acción
                                </a>
                            </div>                    
                        </div>   
                    @endcan   
                @endif                          
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Consecutivo</th>
                                <th>Tipo de acción</th>
                                <th>Número de acción</th>                                
                                <th>Cédula de acción</th>                                
                                <th>Monto por aclarar</th>
                                @if ($auditoria->registro_concluido=='No')
                                    <th>Editar</th>
                                @endif  
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($acciones as $accion)
                                <tr>
                                    <td class="text-center">
                                        {{ str_pad($accion->consecutivo, 3, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td>
                                        {{ $accion->tipo }}
                                    </td>
                                    <td class="text-center">
                                        {{ $accion->numero }}                                        
                                    </td>                                    
                                    <td class="text-center"> 
                                        @if (!empty($accion->cedula))
                                            <a href="{{ asset($accion->cedula) }}" target="_blank">
                                                <i class="align-middle fas fa-file-pdf text-primary fa-2x" aria-hidden="true"></i>
                                            </a>                                       
                                        @endif                                
                                    </td>
                                    <td style="text-align: right!important;">
                                        {{ '$'.number_format( $accion->monto_aclarar, 2) }} 
                                    </td>
                                    @if ($auditoria->registro_concluido=='No')
                                        <td class="text-center">
                                            @can('seguimientoauditoriaacciones.edit')
                                                <a href="{{ route('seguimientoauditoriaacciones.edit',$accion) }}">
                                                    <i class="align-middle fas fa-edit text-primary" aria-hidden="true"></i>
                                                </a>
                                            @endcan  
                                        </td>                                  
                                    @endif                                 
                                </tr>
                                <tr>
                                    <td colspan="7">
                                        <div class="row">
                                            <div class="col-md-12 list-desglose">
                                                <div class="text-primary pl-4 pt-2" data-bs-toggle="collapse" href="#a-detalle-{{ $accion->id }}" aria-expanded="true">
                                                    <i class="fa fa-chevron-down fa-chev"></i> <span class="h5 text-primary">Acción</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="a-detalle-{{ $accion->id }}" class="collapse show">
                                            <div class="row">
                                                <div class="col-md-1">
                                                   &nbsp;
                                                </div>    
                                                <div class="col-md-11 text-justify">
                                                    <?php echo  htmlspecialchars_decode($accion->accion); ?>
                                                </div>                                                
                                            </div>                                           
                                        </div>
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
                @if ($auditoria->registro_concluido=='No')
                    @can('seguimientoauditoria.concluir')
                        <div class="row">
                            <div class="col-md-6">               
                                <a href="{{ route('seguimientoauditoria.concluir',$auditoria) }}" class="btn btn-primary">Concluir</a>
                            </div>
                        </div>
                    @endcan
                @endif 
                <div class="pagination">
                    {{ $acciones->appends(['consecutivo'=>$request->consecutivo,'segtipo_accion_id'=>$request->segtipo_accion_id,'numero'=>$request->numero])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
