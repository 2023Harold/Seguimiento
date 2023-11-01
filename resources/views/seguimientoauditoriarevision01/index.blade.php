@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('seguimientoauditoriarevision01.edit', $auditoria)}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">
                        <a href="{{ route('seguimientoauditoria.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                         &nbsp;
                        Revisar
                    </h1>
                </div>
                <div class="card-body">
                    @include('flash::message')
                    @include('layouts.contextos._auditoria', $auditoria)
                    {!! BootForm::open(['model' => $auditoria,'update'=>'seguimientoauditoriarevision01.update','id'=>'form'] )!!}
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>        
                                        <th></th>                                
                                        <th>No. Consecutivo</th>
                                        <th>Tipo de acción</th>
                                        <th>Acto de fiscalización</th>
                                        <th>Número de acción</th>
                                        <th>Cédula de acción</th>
                                        <th>Monto por aclarar</th>
                                        <th>Revisar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($auditoria->acciones as $accion)
                                    <tr>         
                                        <td class="text-center">
                                            <a href="{{ route('seguimientoauditoriarevision01.show',$accion) }}">
                                                <i class="fa-regular fa-eye icon-hover"></i>
                                            </a>
                                        </td>                               
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
                                                <i class="align-middle fas fa-file-pdf text-primary fa-2x"
                                                    aria-hidden="true"></i>
                                            </a>
                                            @endif
                                        </td>                                        
                                        <td style="text-align: right!important;">
                                            {{ '$'.number_format( $accion->monto_aclarar, 2) }}
                                        </td>
                                        <td class="text-center">
                                            @if ($accion->fase_revision=='En revisión 01' && empty($accion->revision_lider))
                                                <a href="{{ route('seguimientoauditoriaaccionrevision01.edit',$accion) }}" class="btn btn-primary">
                                                    Revisar
                                                </a>   
                                            @else
                                                @if ($accion->revision_lider=='Aprobado')
                                                    <span class="badge badge-light-success">Apraboda</span>
                                                @elseif ($accion->revision_lider=='Rechazado')
                                                    <span class="badge badge-light-danger">Rechazada</span>                                            
                                                @endif                                        
                                            @endif                                            
                                        </td>                                      
                                    </tr>  
                                    {!! movimientosDesglose($accion->id, 8, $accion->movimientos) !!}                                 
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
                        {{-- {{ dd(count($auditoria->accionesrevisadaslider),count($auditoria->acciones)) }} --}}
                        @if (count($auditoria->accionesrevisadaslider) == count($auditoria->acciones))
                            <div class="row">
                                <div class="col-md-6">
                                    {!! BootForm::radios("estatus", ' ',
                                    [
                                        'Aprobado' => 'Aprobar',
                                        'Rechazado' => 'Rechazar'
                                    ], null,false,['class'=>'i-checks rechazado']); !!}
                                </div>
                            </div>
                            <div class="row" id="justificacion" style="display: none;">
                                <div class="col-md-12">
                                    {!! BootForm::textarea('motivo_rechazo','Motivo del rechazo:*','',["rows" => "2", "style" => "rezise:none"])!!}
                                </div>
                            </div>
                            <div class="row" id="enviar" style="display: none;">
                                <div class="col-md-6 mb-3">
                                    {!! BootForm::checkbox('reenviar', 'Se envía al superior para su revisión', '', true, ['class' => 'i-checks', 'disabled']) !!}
                                </div>
                            </div>                        
                            <div class="row mt-3">
                                <div class="col-md-6 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                    <a href="{{ route('seguimientoauditoria.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                                </div>
                            </div>                      
                        @endif
                    {!! BootForm::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')   
    {!! JsValidator::formRequest('App\Http\Requests\AprobarFlujoAutorizacionRequest') !!}
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

