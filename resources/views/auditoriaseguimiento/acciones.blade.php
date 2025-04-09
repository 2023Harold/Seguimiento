@extends('layouts.appPopup')
@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                Acciones
            </h1>
        </div>
        <div class="card-body">
            @include('flash::message')
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Número consecutivo</th>
                            <th>Acción</th>
                            <th>Tipo de acción</th>
                            <th>Número de acción</th>
                            <th>Cédula de la acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auditoria->acciones_finales as $resultado_final)
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{ $resultado_final->accion_descripcion}}</td>
                                <td>{{ $resultado_final->tipo->descripcion}}</td>
                                <td class="text-center">{{ $resultado_final->accion_numero}}</td>
                                <td class="text-center"> @btnFile($resultado_final->cedula_accion)</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#close').click(function (event){
                event.preventDefault();
                parent.$.fn.colorbox.close();
            });
        });
    </script>
@endsection
