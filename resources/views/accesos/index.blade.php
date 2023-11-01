@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('acceso.index') }}
@endsection
@section('content')
@include('flash::message')
<div class="card">
    <div class="card-header">
        <div class="row ">
            <h1 class="card-title">
                <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;&nbsp;&nbsp;
                Accesos
            </h1>  
        </div>
    </div>
    <div class="card-body">
        {!! BootForm::open(['id' => 'form', 'method' => 'GET']) !!}
            <div class="row align-items-end-base">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! BootForm::text('permiso','Permiso :',old('permiso',  $request->permiso),['class'=>''])!!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! BootForm::submit('Buscar', ['class' => 'btn btn-primary mt-8']); !!}
                    </div>
                </div>
            </div>
            {!! BootForm::close() !!}
           
            @if($permisos->isNotEmpty())
                <div class="row">
                    <div id="div-cnt-message" class="col-md-12"></div>
                    <div class="col-md-12">
                        <table class="table table-striped table-hover table-rounded table-row-gray-300 gy-7 table-sm results">
                            <thead>
                                <tr class="table-active">
                                <th>Permiso</th>
                                @foreach($roles as $rol)
                                    <th>{{$rol->name}}</th>
                                @endforeach
                                </tr>                                
                            </thead>
                            <tbody>
                                @foreach($permisos as $permiso)
                                <tr>
                                    <td class="table-active">{{$permiso->name}}</td>
                                    @foreach($roles as $rol)
                                        <td class="text-center">
                                            {!! Form::checkbox($rol->name.'-'.$permiso->name, $permiso->id, old('check', $rol->hasPermissionTo($permiso)), ['id' => 'check'.$rol->id.'-'.$permiso->id, 'class' => 'i-checks btn-asig-per', 'data-rol'=>$rol->id, 'data-permiso'=>$permiso->id]); !!}
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 mt-2">
                        {{ $permisos->appends(['permiso'=>$request->permiso])->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            @endif
            @if($permisos->isEmpty())
                <p>No se han registrado roles o permisos </p>
            @endif
    </div>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.btn-asig-per').on('ifChanged', function(event) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var estado = $(this).is(':checked')? 1 : 0;
                var rol = $(this).data('rol');
                var permiso = $(this).data('permiso');
                $.ajax({
                    url: "{{ route('setPermission') }}",
                    dataType: "JSON",
                    type: "POST",
                    method: 'POST',
                    data: {
                        "estado": estado,
                        "rol": rol,
                        "permiso": permiso,
                    },
                    beforeSend: function (objeto) {
                        $('#check'+rol+'-'+permiso).attr("disabled", true);
                        $('#div-cnt-message').html('<div class="alert alert-info" role="alert"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando el permiso.</div>');
                    },
                    success: function(respuesta) {
                        $('#check'+rol+'-'+permiso).attr("disabled", false);
                        if (respuesta.tipo=='danger') {
                            if (estado==1) {
                                $('#check'+rol+'-'+permiso).prop("checked", false);
                                $('#check'+rol+'-'+permiso).parent().removeAttr("class").attr("class", 'icheckbox_square-grey');
                            }else{
                                $('#check'+rol+'-'+permiso).prop("checked", true);
                                $('#check'+rol+'-'+permiso).parent().removeAttr("class").attr("class", 'icheckbox_square-grey checked');
                            }
                        }
                        $('#div-cnt-message').html('<div class="alert alert-'+respuesta.tipo+'" role="alert"> '+respuesta.msg+'.</div>');
                        setTimeout(function(){
                            $("#div-cnt-message").html("");
                        }, 5000);
                    },
                    error: function() {
                        $('#check'+rol+'-'+permiso).attr("disabled", false);
                        if (estado==1) {
                            $('#check'+rol+'-'+permiso).prop("checked", false);
                            $('#check'+rol+'-'+permiso).parent().removeAttr("class").attr("class", 'icheckbox_square-grey');
                        }else{
                            $('#check'+rol+'-'+permiso).prop("checked", true);
                            $('#check'+rol+'-'+permiso).parent().removeAttr("class").attr("class", 'icheckbox_square-grey checked');
                        }
                        $('#div-cnt-message').html('<div class="alert alert-danger" role="alert"> Error en la petición, intente más tarde.</div>');
                    }
                });
            });
        });
    </script>
@endsection
