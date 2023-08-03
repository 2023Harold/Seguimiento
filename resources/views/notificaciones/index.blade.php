@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('notificaciones') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h1 class="card-title">
                        <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                        Notificaciones
                    </h1>
                </div>
                <div class="card-body">
                    {!! BootForm::open(['id' => 'form', 'method' => 'GET']) !!}
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            {!! BootForm::radios("estatus", 'Estatus: ',['Todos' => ' Todos', 'Pendiente'=>' No leído','Leído'=>' Leído'],
                                old('estatus', empty($request->estatus) ? 'Todos' : $request->estatus),true,['class'=>'i-checks']); !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::date('created_at', 'Fecha de recepción', old('created_at', $request->created_at)) !!}
                        </div>
                        <div class="col-md-3">
                            {!! BootForm::date('updated_at', 'Fecha de lectura', old('updated_at', $request->updated_at)) !!}
                        </div>
                        <div class="col-md-3 mt-8">
                            <button type="submit" class="btn btn-primary">Buscar</button>  
                        </div>
                    </div>
                    {!! BootForm::close() !!}
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mensaje</th>
                                        <th>Fecha y hora de recibido</th>
                                        <th>Estatus</th>
                                        <th>Fecha y hora de lectura</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($notificaciones as $notificacion)
                                        <tr>
                                            <td>{{ explode("<br>", $notificacion->mensaje)[1]}}</td>
                                            <td>{{ fecha($notificacion->created_at, 'd/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                @if( $notificacion->estatus == 'Pendiente' ) 
                                                    <span class="badge badge-light-warning">No leído</span>
                                                @else
                                                    <span class="badge badge-light-success">{{$notificacion->estatus}}</span>
                                                @endif
                                            </td>
                                            <td>{{ $notificacion->estatus == 'Leído'? fecha($notificacion->updated_at, 'd/m/Y H:i') : 'Mensaje no leído'}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class='text-center' colspan="9"> No se han encontrado notificaciones.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 mt-5">
                            <div class="pagination justify-content-start">
                                {{ $notificaciones->appends(['estatus'=>$request->estatus])->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection