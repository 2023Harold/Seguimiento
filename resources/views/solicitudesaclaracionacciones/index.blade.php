@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('solicitudesaclaracionacciones.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('solicitudesaclaracion.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Acciones
                </h1>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')
                {!! BootForm::open(['route'=>'pras.index','method'=>'GET']) !!}
                <div class="row">
                    <div class="col-md-2">
                        {!! BootForm::text('numero_accion', "No. acción:", old('numero_accion',
                        $request->numero_auditoria)) !!}
                    </div>
                    <div class="col-md-6 mt-8">
                        <button type="submit" class="btn btn-primary"><i class="align-middle fas fa-search"
                                aria-hidden="true"></i> Buscar</button>
                    </div>
                </div>
                {!! BootForm::close() !!}
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. consecutivo</th>
                                <th>No. de acción</th>
                                <th>Tipo de acción</th>
                                <th>Monto por aclarar</th>
                                <th>Oficio de la contestación de la solicitud de aclaración</th>
                                <th>Calificación de la atención</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($acciones as $accion)
                            <tr>
                                <td class="text-center">
                                    {{$loop->iteration}}
                                </td>
                                <td>
                                    {{ $accion->numero }}
                                </td>
                                <td>
                                    {{ $accion->tipo}}
                                </td>
                                <td style="text-align: right!important;">
                                    {{'$'.number_format( $accion->monto_aclarar, 2)}}
                                </td>
                                <td class="text-center">
                                    @if(empty($accion->solicitudesaclaracion)&&in_array("Analista",auth()->user()->getRoleNames()->toArray()))
                                    {{-- @can('solicitudesaclaracionacciones.edit') --}}
                                        <a href="{{ route('solicitudesaclaracionacciones.edit',$accion)}}"
                                        class="btn btn-primary popupcontestacion">
                                            <i class="align-middle fa fa-file-circle-plus" aria-hidden="true"></i> Registar
                                        </a>
                                    {{-- @endcan --}}
                                    @else
                                        @if(!empty($accion->solicitudesaclaracion))
                                            <a href="{{ asset($accion->solicitudesaclaracion->oficio_atencion) }}" target="_blank">
                                                <?php echo htmlspecialchars_decode(iconoArchivo($accion->solicitudesaclaracion->oficio_atencion)) ?>
                                            </a> <br>
                                            <small>{{ fecha($accion->solicitudesaclaracion->fecha_oficio_atencion) }}</small><br>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (!empty($accion->solicitudesaclaracion))
                                        @if (empty($accion->solicitudesaclaracion->cumple))
                                            <a href="{{ route('solicitudesaclaracioncalificacion.edit',$accion->solicitudesaclaracion) }}" class="btn btn-primary">
                                                <i class="align-middle fa fa-file-circle-plus" aria-hidden="true"></i>Registrar 
                                            </a>
                                        @else
                                            <a href="{{ route('solicitudesaclaracioncalificacion.index') }}" class="btn btn-primary">
                                                <i class="align-middle fa fa-file-circle-plus" aria-hidden="true"></i>Consultar 
                                            </a>                                            
                                        @endif                                        
                                    @endif                                    
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{
                    $acciones->appends(['numero_auditoria'=>$request->numero_auditoria,'monto_aclarar'=>$request->monto_aclarar,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5')
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
            $('.popupcontestacion').colorbox({     
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