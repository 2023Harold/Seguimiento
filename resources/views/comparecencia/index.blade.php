@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('comparecencia.index') }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Comparecencia
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')                
                {!! BootForm::open(['route'=>'radicacion.index','method'=>'GET']) !!}
                    <div class="row">
                        <div class="col-md-2">
                            {!! BootForm::text('numero_auditoria', "No. auditoría:", old('numero_auditoria', $request->numero_auditoria)) !!}
                        </div>
                        <div class="col-md-2">
                            {!! BootForm::text('entidad_fiscalizable', "Entidad fiscalizable:", old('entidad_fiscalizable', $request->entidad_fiscalizable)) !!}
                        </div>
                        <div class="col-md-2">
                            {!! BootForm::text('acto_fiscalizacion', "Acto de fiscalización:", old('acto_fiscalizacion', $request->acto_fiscalizacion)) !!}
                        </div>
                        <div class="col-md-6 mt-8">
                            <button type="submit" class="btn btn-primary"><i class="align-middle fas fa-search" aria-hidden="true"></i> Buscar</button>                           
                        </div>
                    </div>
                {!! BootForm::close() !!}                   
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. de auditoría</th>
                                <th>Entidad fiscalizable</th>
                                <th>Acto de fiscalización</th>
                                <th>Comparecencia</th>                               
                                <th>Fase / Acción / Constancia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($auditorias as $auditoria)
                                <tr>
                                    <td>
                                        {{ $auditoria->numero_auditoria }}
                                    </td>
                                    <td width='30%'>
                                        @php
                                            $entidadparciales = explode("-", $auditoria->entidad_fiscalizable);                                            
                                        @endphp
                                        @foreach ($entidadparciales as $entidadparcial)
                                            {{ mb_convert_encoding(mb_convert_case(strtolower($entidadparcial), MB_CASE_TITLE), "UTF-8"); }}<br>
                                        @endforeach                                        
                                    </td>
                                    <td>
                                        {{ $auditoria->acto_fiscalizacion }}
                                    </td>
                                    <td class="text-center">
                                        @if (empty($auditoria->comparecencia))
                                            @can('comparecencia.auditoria') 
                                                <a href="{{ route('comparecencia.auditoria', $auditoria) }}" class="btn btn-primary"><span class="fa fa-pencil" aria-hidden="true"></span>&nbsp; Registrar</a>                                           
                                            @endcan
                                        @elseif(!empty($auditoria->comparecencia)&&(empty($auditoria->comparecencia->fase_autorizacion)||$auditoria->comparecencia->fase_autorizacion=='Rechazado'))
                                            @can('comparecencia.edit')
                                                <a href="{{ route('comparecencia.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                    <span class="align-middle fas fa-edit" aria-hidden="true"></span> &nbsp; Editar
                                                </a> 
                                            @endcan
                                        @elseif (!empty($auditoria->comparecencia)&&!empty($auditoria->comparecencia->fase_autorizacion)&&$auditoria->comparecencia->fase_autorizacion!='Rechazado'&&$auditoria->comparecencia->fase_autorizacion!='Autorizado')
                                            @can('comparecencia.show')
                                                <a href="{{ route('comparecencia.show', $auditoria->comparecencia) }}" class="btn btn-primary">
                                                    <span class="fa fa-file-search" aria-hidden="true"></span>&nbsp; Consultar
                                                </a> 
                                            @endcan
                                        @elseif (!empty($auditoria->comparecencia)&&!empty($auditoria->comparecencia->fase_autorizacion)&&$auditoria->comparecencia->fase_autorizacion=='Autorizado'&&empty($auditoria->comparecencia->oficio_recepcion))
                                            @can('comparecenciaacuse.edit')
                                                <a href="{{ route('comparecenciaacuse.edit', $auditoria->comparecencia) }}" class="btn btn-primary">
                                                    <span class="fa fa-file-plus" aria-hidden="true"></span>&nbsp; Adjuntar Acuses
                                                </a> 
                                            @else
                                                @can('comparecencia.show')
                                                <a href="{{ route('comparecencia.show', $auditoria->comparecencia) }}" class="btn btn-primary">
                                                    <span class="fa fa-file-search" aria-hidden="true"></span>&nbsp; Consultar
                                                </a> 
                                                @endcan
                                            @endcan  
                                        @elseif (!empty($auditoria->comparecencia)&&!empty($auditoria->comparecencia->fase_autorizacion)&&$auditoria->comparecencia->fase_autorizacion=='Autorizado'&&!empty($auditoria->comparecencia->oficio_recepcion))
                                            @if (!empty($auditoria->comparecencia->cedula_general)&&!empty($auditoria->comparecencia->oficio_acta)&&!empty($auditoria->comparecencia->oficio_respuesta))
                                                @can('comparecencia.show')
                                                    <a href="{{ route('comparecencia.show', $auditoria->comparecencia) }}" class="btn btn-primary">
                                                        <span class="fa fa-file-search" aria-hidden="true"></span>&nbsp; Consultar
                                                    </a> 
                                                @endcan                                               
                                            @else
                                                @if (in_array("Jefe de Departamento de Seguimiento", auth()->user()->getRoleNames()->toArray()))
                                                    @can('comparecencia.show')
                                                        <a href="{{ route('comparecencia.show', $auditoria->comparecencia) }}" class="btn btn-secondary" id="link-respuesta" data-title="Consultar">
                                                            <img alt="Logo" src="{{asset('assets/img/consultar.png')}}" class="h-30px logo" />
                                                        </a>
                                                    @endcan
                                                @else
                                                    @can('comparecencia.show')
                                                        <a href="{{ route('comparecencia.show', $auditoria->comparecencia) }}" class="btn btn-primary">
                                                            <span class="fa fa-file-search" aria-hidden="true"></span>&nbsp; Consultar
                                                        </a> 
                                                    @endcan                                                        
                                                @endif
                                            @endif                                                
                                            @if (empty($auditoria->comparecencia->cedula_general))  
                                                @can('comparecenciacedula.edit')
                                                    <a href="{{ route('comparecenciacedula.edit', $auditoria->comparecencia) }}" class="btn btn-secondary" id="link-cedula" data-title="Adjuntar Cédula">
                                                        <img alt="Logo" src="{{asset('assets/img/cedula.png')}}" class="h-30px logo"/>
                                                    </a>                                               
                                                @endcan                                 
                                            @endif
                                            @if (empty($auditoria->comparecencia->oficio_acta)) 
                                                @can('comparecenciaacta.edit')
                                                    <a href="{{ route('comparecenciaacta.edit', $auditoria->comparecencia) }}" class="btn btn-secondary" id="link-acta" data-title="Adjuntar Acta">
                                                        <img alt="Logo" src="{{asset('assets/img/acta.png')}}" class="h-30px logo" />
                                                    </a>
                                                @endcan                           
                                            @endif
                                            @if (empty($auditoria->comparecencia->oficio_respuesta)) 
                                                @can('comparecenciarespuesta.edit')
                                                    <a href="{{ route('comparecenciarespuesta.edit', $auditoria->comparecencia) }}" class="btn btn-secondary" id="link-respuesta" data-title="Adjuntar Respuesta">
                                                        <img alt="Logo" src="{{asset('assets/img/respuesta.png')}}" class="h-30px logo" />
                                                    </a>
                                                @endcan
                                            @endif   
                                        @else
                                            @can('comparecencia.show')
                                                <a href="{{ route('comparecencia.show', $auditoria->comparecencia) }}" class="btn btn-primary">
                                                    <span class="fa fa-file-search" aria-hidden="true"></span>&nbsp; Consultar
                                                </a> 
                                            @endcan                          
                                        @endif                                        
                                    </td>                                                                       
                                    <td class="text-center"> 
                                        @if (!empty($auditoria->comparecencia))
                                            @if(!empty($auditoria->comparecencia)&&!empty($auditoria->comparecencia->fase_autorizacion)&&$auditoria->comparecencia->fase_autorizacion=='Rechazado')
                                                <span class="badge badge-light-danger">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                            @endif
                                            @if ($auditoria->comparecencia->fase_autorizacion == 'En validación')
                                                @can('comparecenciavalidacion.edit')
                                                    <a href="{{ route('comparecenciavalidacion.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Validar
                                                    </a>
                                                @else
                                                    <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>
                                                @endcan
                                            @endif
                                            @if ($auditoria->comparecencia->fase_autorizacion == 'En autorización')                                                                                              
                                                @can('comparecenciaautorizacion.edit')
                                                    <a href="{{ route('comparecenciaautorizacion.edit',$auditoria->comparecencia) }}" class="btn btn-primary">
                                                        <li class="fa fa-gavel"></li>
                                                        Autorizar
                                                    </a> 
                                                @else
                                                    <span class="badge badge-light-warning">{{ $auditoria->comparecencia->fase_autorizacion }} </span>                                           
                                                @endcan
                                            @endif 
                                            @if ($auditoria->comparecencia->fase_autorizacion=='Autorizado')
                                            <span class="badge badge-light-success">{{ $auditoria->comparecencia->fase_autorizacion }} </span> <br>
                                                @btnFile($auditoria->comparecencia->constancia)
                                                @btnXml($auditoria->comparecencia, 'constancia')
                                            @endif 
                                        @endif
                                    </td>                                                                             
                                </tr>
                                @if (!empty($auditoria->comparecencia))
                                    {!! movimientosDesglose($auditoria->comparecencia->id, 10, $auditoria->comparecencia->movimientos) !!}
                                @endif                                                                                           
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
                <div class="pagination">
                    {{ $auditorias->appends(['numero_auditoria'=>$request->numero_auditoria,'entidad_fiscalizable'=>$request->entidad_fiscalizable,'acto_fiscalizacion'=>$request->acto_fiscalizacion])->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\ComparecenciaRequest') !!}   
@endsection
