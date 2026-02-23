@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('buzonaudiencias.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h1 class="card-title">
                    @btnBack(route('home'))
                    Buzón
                </h1>
            </div>
            <div class="card-body">
			@include('layouts.partials._tabsbuzonaudiencias')
            @include('flash::message')
            <div class="row">
                <div class="col-md-12">
				</br>
                {{--- @include('layouts.partials._tabsbuzonaudiencias')---}}
				<h2 class="text-primary">Audiencia</h2>
                <br>
                 {!! BootForm::open(['id' => 'form', 'method' => 'GET']) !!}
                            <div class="row">
                                <div class="col-md-4">
                                    {!! BootForm::text(
                                        'num_expediente',
                                        'No. Expediente:',
                                        old('num_expediente', optional(request())->num_expediente),
                                    ) !!}
                                </div>
                                <div class="col-md-3 mt-8">
                                    @btnSubmit('Buscar')
                                </div>
                            </div>
                {!! BootForm::close() !!}
                <div class="row">
                <div class="col-md-10">
                    &nbsp;
                </div>
                <div class="col-md-2">
                    <span class="h5">
                    <ul class="nav nav-tabs nav-tabs-line justify-content-end mb-5">
                        <li class="nav-item">
                        <a class="nav-link {{ (Route::current()->getName() == 'buzonaudiencias.index')||(Route::current()->getName() == 'audienciarevision.edit')||(Route::current()->getName() == 'audienciavalidacion.edit')||(Route::current()->getName() == 'audienciaautorizacion.edit') ? 'active' : '' }}" aria-current="page"  data-toggle="tab" href="{{route('buzonaudiencias.index')}}">Pedientes</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link {{ (Route::current()->getName() == 'audienciaautorizacion.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('audienciaautorizacion.index')}}">Autorizados</a>
                        </li>
                    </ul>
                    </span>
                </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No. Expediente</th>
                                    <th>Acta de Audiencia inicial</th>
									<th>Numero de acta</th>
                                    <th>Citados/Presuntos responsables</th>
                  					<th>¿Acta cierre?</th>
                                    <th> </th>
                  					{{-- <th>Autoridad investigadora</th>
                  					<th>Resolución</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($audiencias as $audiencia)
                                    <tr class="bg-secondary">
                                        <td class="text-center">
                                            {{optional($audiencia->expediente)->numero_expediente}}
                                        </td>
                                        <td colspan="11">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">

                                        </td>
                                        <td class="text-center">
                                            @btnFile(asset($audiencia->acta_audiencia_inicial))<br>
                                            {{fecha($audiencia->fecha_acta)}}
                                        </td>
                                        <td class="text-center">
                                            {{$audiencia->numero_acta}}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('presuntoaudiencia.show',$audiencia) }}" class="btn btn-link btn-active-color-primary popupcomentario">
                                                <span class="bi bi-list-task" aria-hidden="true"></span> 
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{$audiencia->audiencia_cierre}}
                                        </td>
                                        <td class="text-center">
                                        @if($audiencia->fase_autorizacion == 'En revisión')
                                            @can('recursoreclamacionrevision.edit')
                                                @buttonCan('Revisar', route('audienciarevision.edit', $audiencia))<br>
                                            @else
                                                <span class="badge badge-light-warning">  {{$audiencia->fase_autorizacion}} </span>
                                            @endcan
                                        @endif
                                        @if($audiencia->fase_autorizacion == 'En validación')
                                            @can('audienciavalidacion.edit')
                                                @buttonCan('Validar', route('audienciavalidacion.edit', $audiencia))<br>
                                            @else
                                                <span class="badge badge-light-info">{{$audiencia->fase_autorizacion}} </span>
                                            @endcan

                                        @endif
                                        @if($audiencia->fase_autorizacion == 'En autorización')
                                            @can('audienciaautorizacion.edit')
                                                @buttonCan('Autorizar', route('audienciaautorizacion.edit', $audiencia))<br>
                                            @else
                                                <span class="badge badge-light-primary">{{$audiencia->fase_autorizacion}} </span>
                                            @endcan
                                        @endif
                                        @if($audiencia->fase_autorizacion == 'Rechazado')
                                            <span class="badge badge-light-danger">{{ $audiencia->fase_autorizacion }} </span><br>
                                            @buttonCan('<i class="fa fa-edit"></i>', route('audiencia.edit', $audiencia), '')
                                            @buttonCan('<i class="bi bi-send-fill">Concluir</i>', route('audienciacierre.edit', $audiencia), '')  
                                        @endif
                                        @if($audiencia->fase_autorizacion=='Autorizado')
                                            <span class="badge badge-light-success">{{ $audiencia->fase_autorizacion }} </span>
                                        @endif
                                        </td>
                                    </tr>
                                    {!! movimientosDesglose($audiencia->id, 11, $audiencia->movimientos) !!}
                                @empty
                                    <tr>
                                        <td class='text-center' colspan="11">No hay registros en éste apartado</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
					<div class="col-md-12 mt-5">
                        <div class="pagination justify-content-start">
                            {{ $audiencias->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
@section('script')

@endsection
