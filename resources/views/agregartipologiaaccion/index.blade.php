@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('tipologiaaccion.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">                   
                        <a href="{{ route('tipologiaauditorias.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>                   
                    &nbsp; Acciones
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                {!! BootForm::open(['route'=>'seguimientoauditoriaacciones.index','method'=>'GET']) !!}
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
                @if ($auditoria->registro_concluido=='No')
                @can('seguimientoauditoriaacciones.create')
                {{-- <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-primary float-end" href="{{ route('seguimientoauditoriaacciones.create') }}">
                            Agregar acción
                        </a>
                    </div>
                </div> --}}
                @endcan
                @endif
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>                               
                                <th>No. Consecutivo</th>
                                <th>Tipo de acción</th>
                                <th>Acto de fiscalización</th>
                                <th>Número de acción</th>
                                <th>Cédula de acción</th>
                                <th>Monto por aclarar</th>                            
                                <th>Tipología</th>                            
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
                                <td>
                                    {{ $accion->acto_fiscalizacion }} 
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
                                    @if (empty($accion->tipologia_id))
                                        @can('seguimientoauditoriaacciones.edit')
                                            <a href="{{ route('tipologiaaccion.edit',$accion) }}" class="btn btn-primary popuprevisar">
                                                Agregar
                                            </a>
                                        @endcan
                                    @else
                                       {{ $accion->tipologiadesc->tipologia }}
                                    @endif
                                    
                                </td>
                            </tr>                           
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
@section('script')
    <script>
        $(document).ready(function() {
            $('.popuprevisar').colorbox({
                width:"65%",
                height:"650px",
                maxWidth:400,
                maxHeight:"650px",
                iframe: true,
                onClosed: function() {
                    location.reload(true);
                },
                onComplete: function () {
                 $(this).colorbox.resize({width:"65%",maxWidth:400, height:"650px", maxHeight:"650px"});
                 $(window).trigger("resize");
                }
            });
        });
    </script>
@endsection
