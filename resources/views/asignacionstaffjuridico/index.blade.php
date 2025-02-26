@extends('layouts.app')
@section('breadcrums')    
    @if ($accionstaff=='Asignación')
    {{Breadcrumbs::render('asignacionstaff.edit',$auditoria) }}   
    @else
        {{Breadcrumbs::render('asignacionstaff.reasignar', $auditoria, 'Reasignación') }}
    @endif     
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">
            <a href="{{ route('asignaciondepartamento.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>            
            &nbsp; {{ $accionstaff }}       
        </h1>
    </div>
    <div class="card-body">
        @include('flash::message')
        @include('layouts.contextos._auditoria')
        {!!BootForm::open(['model' => $auditoria,'update' => 'asignacionstaff.update','id' => 'form']) !!}
        {!!BootForm::hidden('accionstaff',$accionstaff) !!}
        <!-- !! BootForm::hidden('usuario_id',null,['id'=>'usuario_id']) !! -->
        @can('asignacionstaff.edit') 
            <div class="d-flex justify-content-end">
                <a href="{{ route('asignacionstaff.edit', $auditoria) }}" class="btn btn-primary"> 
                    Agregar <i class="bi bi-plus-square fs-1"></i>
                </a>
            </div>
        @endcan
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Staff juridico</th>
                        <th>Estatus</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffAsignado as $staff)
                        <tr>
                            <td class="text-center">
                                <span class="">
                                    {{ $staff->usuario->name ?? 'Usuario no encontrado' }} 
                                </span>
                            </td>
                            <td class="text-center">
                                @if($staff->usuario->estatus == 'Activo') 
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @can('asignacionstaff.edit') 
                                    <a href="{{ route('asignacionstaff.reasignar', $auditoria) }}" class="btn btn-primary">
                                        <i class="fa fa-user-edit"></i> Reasignar
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {!!BootForm::close() !!}
    </div>
</div>
@endsection
@section('script')

{!!JsValidator::formRequest('App\Http\Requests\AsignacionStaffJuridicoRequest') !!}
@endsection
