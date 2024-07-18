@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('informeprimeraetapa.index',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('auditoriaseguimiento.index') }}"><i
                            class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Informe
                </h1>
            </div>
            <div class="card-body">
                @include('layouts.contextos._auditoria')
                @include('flash::message')
                {!! BootForm::open(['route'=>'pliegosobservacionacciones.index','method'=>'GET']) !!}
                <div class="row">
                    <div class="col-md-2">
                        {!! BootForm::text('numero_accion', "No. acción:", old('numero_accion',
                        $request->numero_auditoria)) !!}
                    </div>
                    <div class="col-md-6 mt-8">
                        <button type="submit" class="btn btn-primary"><i class ="align-middle fas fa-search"
                                aria-hidden="true"></i> Buscar</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @can('informeprimeraetapa.create')
                            <a class="btn btn-primary float-end" href="{{ route('informeprimeraetapa.create') }}">
                                Generar Informe
                            </a> 
                        @endcan
                    </div>                    
                </div>                                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. consecutivo</th>
                                <th>No. de acción</th>
                                <th>Tipo de acción</th>
                                <th>Monto por aclarar</th>
                                <th>Monto solventado</th>
                                <th>Monto no solventado</th>
                                <th>Calificación</th>
                                <th>Promoción</th>
                                <th>Monto de la promoción</th>
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
                                    @if ($accion->tipo=='Solicitud de aclaración')
                                        {{(!empty($accion->solicitudesaclaracion->monto_solventado)?'$'.number_format( $accion->solicitudesaclaracion->monto_solventado, 2):'$'.number_format( 0, 2))}}
                                    @elseif($accion->tipo=='Recomendación')
                                        {{'$'.number_format( 0, 2)}}
                                    @elseif($accion->tipo=='Pliego de observación')
                                        {{(!empty($accion->pliegosobservacion->monto_solventado)?'$'.number_format( $accion->pliegosobservacion->monto_solventado, 2):'$'.number_format( 0, 2))}}
                                    @elseif($accion->tipo=='Promoción de responsabilidad administrativa sancionatoria')
                                        {{'$'.number_format( 0, 2)}}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($accion->tipo=='Solicitud de aclaración')
                                        @if (!empty($accion->solicitudesaclaracion->fase_autorizacion)&&$accion->solicitudesaclaracion->fase_autorizacion=='Autorizado')
                                            @if ($accion->solicitudesaclaracion->calificacion_sugerida=='Solventada')
                                                {{ '$'.number_format( 0, 2) }}
                                            @endif
                                            @if ($accion->solicitudesaclaracion->calificacion_sugerida=='No Solventada')
                                                {{ '$'.number_format( $accion->monto_aclarar, 2) }}
                                            @endif
                                            @if ($accion->solicitudesaclaracion->calificacion_sugerida=='Solventada Parcialmente')
                                                {{ '$'.number_format($accion->monto_aclarar-$accion->solicitudesaclaracion->monto_solventado, 2) }}
                                            @endif
                                        @else
                                            {{ '$'.number_format( 0, 2) }}
                                        @endif
                                    @elseif($accion->tipo=='Recomendación')
                                        {{'$'.number_format( 0, 2)}}
                                    @elseif($accion->tipo=='Pliego de observación')
                                         @if (!empty($accion->pliegosobservacion->fase_autorizacion)&&$accion->pliegosobservacion->fase_autorizacion=='Autorizado')
                                            @if ($accion->pliegosobservacion->calificacion_sugerida=='Solventado')
                                                {{ '$'.number_format( 0, 2) }}
                                            @endif
                                            @if ($accion->pliegosobservacion->calificacion_sugerida=='No Solventado')
                                                {{ '$'.number_format( $accion->monto_aclarar, 2) }}
                                            @endif
                                            @if ($accion->pliegosobservacion->calificacion_sugerida=='Solventado Parcialmente')
                                                {{ '$'.number_format($accion->monto_aclarar-$accion->pliegosobservacion->monto_solventado, 2) }}
                                            @endif
                                        @else
                                            {{ '$'.number_format( 0, 2) }}
                                        @endif
                                    @elseif($accion->tipo=='Promoción de responsabilidad administrativa sancionatoria')
                                        {{'$'.number_format( 0, 2)}}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($accion->tipo=='Solicitud de aclaración')
                                        {{((!empty($accion->solicitudesaclaracion->fase_autorizacion)&&$accion->solicitudesaclaracion->fase_autorizacion=='Autorizado')? $accion->solicitudesaclaracion->calificacion_sugerida:'Pendiente')}}
                                    @elseif($accion->tipo=='Recomendación')
                                        {{((!empty($accion->recomendaciones->fase_autorizacion)&&$accion->recomendaciones->fase_autorizacion=='Autorizado')? $accion->recomendaciones->calificacion_sugerida:'Pendiente')}}
                                    @elseif($accion->tipo=='Pliego de observación')
                                        {{((!empty($accion->pliegosobservacion->fase_autorizacion)&&$accion->pliegosobservacion->fase_autorizacion=='Autorizado')?$accion->pliegosobservacion->calificacion_sugerida:'Pendiente')}}
                                    @elseif($accion->tipo=='Promoción de responsabilidad administrativa sancionatoria')
                                        {{((!empty($accion->pras->fase_autorizacion)&&$accion->pras->fase_autorizacion=='Autorizado')? 'Turnado':'Sin turnar')}}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($accion->tipo=='Solicitud de aclaración')
                                        {{((!empty($accion->solicitudesaclaracion->fase_autorizacion)&&
                                            $accion->solicitudesaclaracion->fase_autorizacion=='Autorizado'&&
                                            $accion->solicitudesaclaracion->calificacion_sugerida!='Solventada'
                                            )? $accion->solicitudesaclaracion->promocionaccion->descripcion:'Sin promoción')}}
                                    @elseif($accion->tipo=='Pliego de observación')
                                        {{((!empty($accion->pliegosobservacion->fase_autorizacion)&&
                                            $accion->pliegosobservacion->fase_autorizacion=='Autorizado'&&
                                            $accion->pliegosobservacion->calificacion_sugerida!='Solventado'&&
                                            !empty($accion->pliegosobservacion->promocionaccion)
                                            )?$accion->pliegosobservacion->promocionaccion->descripcion:'Sin promoción')}}
                                    @else
                                        Sin promoción
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($accion->tipo=='Solicitud de aclaración')
                                        {{((!empty($accion->solicitudesaclaracion->fase_autorizacion)&&
                                            $accion->solicitudesaclaracion->fase_autorizacion=='Autorizado'&&
                                            !empty($accion->solicitudesaclaracion->promocion)&&
                                            ($accion->solicitudesaclaracion->promocion==3 || $accion->solicitudesaclaracion->promocion==4)
                                            )?'$'.number_format( $accion->solicitudesaclaracion->monto_promocion, 2):'$'.number_format( 0, 2))}}
                                    @elseif($accion->tipo=='Pliego de observación')
                                        {{((!empty($accion->pliegosobservacion->fase_autorizacion)&&
                                            $accion->pliegosobservacion->fase_autorizacion=='Autorizado'&&
                                            !empty($accion->pliegosobservacion->promocion)&&
                                            ($accion->pliegosobservacion->promocion==1 || $accion->pliegosobservacion->promocion==4)
                                            )?'$'.number_format( $accion->pliegosobservacion->monto_promocion, 2):'$'.number_format( 0, 2))}}
                                    @else
                                    {{'$'.number_format( 0, 2)}}
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-center" colspan="5">
                                    No se encuentran registros en este apartado.
                                </td>
                            </tr>
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
