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
{{ Breadcrumbs::render('agregaracciones.index') }}
@endif
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    @if (!empty($movimiento)&&$movimiento=='consultar')
                    <a href="{{ route('agregaracciones.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    @elseif (!empty($movimiento)&&$movimiento=='direccionconsultar')
                    <a href="{{ route('agregaraccionesdireccion.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    @elseif (!empty($movimiento)&&$movimiento=='departamentoconsultar')
                    <a href="{{ route('agregaraccionesdepartamento.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    @else
                        <a href="{{ route('auditoriaseguimiento.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    @endif
                    &nbsp; Acciones
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['route'=>'agregaracciones.index','method'=>'GET']) !!}
                <div class="row">
                    <div class="col-md-2">
                        {!! BootForm::number('consecutivo', "No. Consecutivo:", old('consecutivo',
                        $request->consecutivo)) !!}
                    </div>
                    <div class="col-md-4">
                        {!! BootForm::select('segtipo_accion_id', 'Tipo de acción: ', $tiposaccion->toArray(),
                        old('segtipo_accion_id',$request->segtipo_accion_id),['data-control'=>'select2',
                        'class'=>'form-select form-group', 'data-placeholder'=>'Seleccionar una opción']) !!}
                    </div>
                    <div class="col-md-3">
                        {!! BootForm::text('numero', "Número de acción:", old('numero', $request->numero)) !!}
                    </div>
                    <div class="col-md-3 mt-8">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </div>
                {!! BootForm::close() !!} 
                @if (count ($auditoria->acciones)==count($auditoria->accionessinenvio))               
                @can('agregaracciones.create')
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-primary float-end" href="{{ route('agregaracciones.create') }}">
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
                                <th> </th>
                                <th>No. Consecutivo</th>
                                <th>Tipo de acción</th>
                                <th>Acto de fiscalización</th>
                                <th>Número de acción</th>
                                <th>Cédula de acción</th>
                                <th>Monto por aclarar</th>
                                <th>Estatus</th>
                                @if (count ($auditoria->acciones)==count($auditoria->accionessinenvio))
                                <th>Editar</th>
                                <th>Eliminar</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($acciones as $accion)
                            <tr>
                                <td class="text-center">

                                    @if (($accion->revision_lider=='Rechazado'&& empty($accion->revision_jefe))||($accion->revision_lider=='Rechazado'&& $accion->revision_jefe='Rechazado')||($accion->revision_lider=='Aprobado'&& $accion->revision_jefe=='Rechazado'))
                                        <a href="{{ route('agregaracciones.accion',$accion) }}">
                                            <i class="fa-regular fa-eye icon-hover"></i>
                                        </a>
                                        1
                                    @elseif(!empty($movimiento))
                                        <a href="{{ route('agregaracciones.accion',['accion'=>$accion->id,'movimiento'=>$movimiento]) }}">
                                            <i class="fa-regular fa-eye icon-hover"></i>
                                        </a>
                                        2
                                    @else
                                        <a href="{{ route('agregaracciones.accion',['accion'=>$accion]) }}">
                                            <i class="fa-regular fa-eye icon-hover"></i>
                                        </a>
                                        3
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ str_pad($accion->consecutivo, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>
                                    {{ $accion->tipo }}
                                </td>
                                <td>
                                    {{ $accion->acto_fiscalizacion }} {{(empty($accion->tipologia_id)?'':'-'.$accion->tipologiadesc->tipologia) }}
                                </td>
                                <td class="text-center">
                                    {{ $accion->numero }}
                                </td>
                                <td class="text-center">
                                    @if (!empty($accion->cedula))
                                    <a href="{{ asset($accion->cedula) }}" target="_blank">
                                        <?php echo htmlspecialchars_decode(iconoArchivo($accion->cedula)) ?>
                                    </a>
                                    @endif
                                </td>                            
                                <td style="text-align: right!important;">
                                    {{ '$'.number_format( $accion->monto_aclarar, 2) }}
                                </td>
                                <td class="text-center">
                                    {{-- @if (($accion->revision_lider))
                                    1 --}}
                                        @if (($accion->revision_lider=='Aprobado'&& empty($accion->revision_jefe))||($accion->revision_lider=='Aprobado'&& $accion->revision_jefe=='Aprobado'))
                                            <span class="badge badge-light-success">Aprobada</span>
                                        @elseif (($accion->revision_lider=='Rechazado'&& empty($accion->revision_jefe))||($accion->revision_lider=='Rechazado'&& $accion->revision_jefe='Rechazado')||($accion->revision_lider=='Aprobado'&& $accion->revision_jefe=='Rechazado'))
                                            <span class="badge badge-light-danger">Rechazada</span>
                                        @elseif ($accion->revision_lider=='En revisión 01')
                                            <span class="badge badge-light-warning">En revisión</span>
                                        @else
                                            <span class="badge badge-light-warning">{{ $accion->revision_lider }}</span>
                                        @endif
                                    {{-- @endif --}}
                                </td>
                                @if (count ($auditoria->acciones)==count($auditoria->accionessinenvio))
                                <td class="text-center">
                                    @can('agregaracciones.edit')
                                        @if (empty($accion->revision_lider)||$accion->revision_lider=='Rechazado'||$accion->revision_jefe=='Rechazado')
                                            <a href="{{ route('agregaracciones.edit',$accion) }}">
                                                <i class="align-middle fas fa-edit text-primary" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    @endcan
                                </td>
                                @endif
                                @if (count ($auditoria->acciones)==count($auditoria->accionessinenvio))
                                <td class="text-center">
                                    @can('agregaracciones.edit')
                                       @destroy(route('agregaracciones.destroy',$accion))
                                    @endcan
                                </td>
                                @endif
                            </tr>
                            {!! movimientosDesglose($accion->id, 8, $accion->movimientos) !!}
                            @empty
                            <tr>
                                <td class="text-center" colspan="9">
                                    <span class='text-center'>No hay registros en éste apartado</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (count ($auditoria->acciones)==count($auditoria->accionessinenvio))
                @can('agregaracciones.concluir')
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('agregaracciones.concluir',$auditoria) }}" class="btn btn-primary" onclick="return  confirm('Al concluir con el registro, no se podran registrar mas acciones. ¿Esta seguro que deseas continuar?');">Concluir</a>
                    </div>
                </div>
                @endcan
                @endif
                <div class="pagination">
                    {{
                    $acciones->appends(['consecutivo'=>$request->consecutivo,'segtipo_accion_id'=>$request->segtipo_accion_id,'numero'=>$request->numero])->links('vendor.pagination.bootstrap-5')
                    }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
