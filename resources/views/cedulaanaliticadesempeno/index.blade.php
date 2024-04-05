@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('cedulaanaliticadesemp.edit',$auditoria) }}
@endsection
@section('content')
<div class="row">
    @include('layouts.partials._menu')
    <div class="col-md-9 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('cedulainicial.index') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a>
                    &nbsp;
                    Cédula Analitica Desempeño
                </h1>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('layouts.contextos._auditoria')
                @if(count($auditoria->cedulaanaliticadesemp)>0)  
                @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'Rechazado')
                    @can('cedulaanaliticadesemp.update')                          
                    {!! BootForm::close() !!}              
                        {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulaanaliticadesemp.update','id'=>'form']) !!}            
                        <div class="row">
                            <div class="col-md-12">                         
                                {!! BootForm::hidden('cedula2',$nombre)!!}                            
                                @if (auth()->user()->can('cedulaanaliticadesemp.update'))
                                    <button type="submit" name="enviar" class="btn btn-primary float-end">Iniciar revisión</button>
                                @endif 
                            </div>
                        </div>
                        {!! BootForm::close() !!}
                    @else
                        <span class="alert alert-danger float-end">Rechazado</span><br><br>
                    @endcan
                @endif 
                @if(count($analistasF)>0 && count($analistasL)== 0 && empty($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion))                           
                    {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulaanaliticadesemp.update','id'=>'form']) !!}            
                    <div class="row">
                        <div class="col-md-12">                         
                            {!! BootForm::hidden('cedula2',$nombre)!!}                            
                            @if (auth()->user()->can('cedulaanaliticadesemp.update'))
                                <button type="submit" name="enviar" class="btn btn-primary float-end">Iniciar revisión</button>
                            @endif 
                        </div>
                    </div>
                    {!! BootForm::close() !!}
                @elseif(in_array(auth()->user()->id, $analistasF) && count($analistasL)>0)                    
                    <a href="{{ route('cedanadesempanalista.edit',$auditoria->cedulaanaliticadesemp[0]) }}" class="btn btn-primary popuprevisar float-end">
                        <li class="fa fa-gavel"></li>
                        Aprobar
                    </a><br><br><br>
                @endif                    
                @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En revisión 01')          
                    @can('cedanadesemprevision01.edit') 
                        @if(in_array(auth()->user()->id, $lideresF))
                            <a href="{{ route('cedanadesemprevision01.edit',$auditoria->cedulaanaliticadesemp[0]) }}" class="btn btn-primary popuprevisar float-end">
                                <li class="fa fa-gavel"></li>
                                Revisar
                            </a><br><br><br>
                        @endif
                    @else
                        <span class="alert alert-warning float-end">En revisión</span><br><br>
                    @endcan
                @endif
                @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En revisión')     
                    @can('cedanadesemprevision.edit')
                        @if(in_array(auth()->user()->unidad_administrativa_id, $jefesF))
                            <a href="{{ route('cedanadesemprevision.edit',$auditoria->cedulaanaliticadesemp[0]) }}" class="btn btn-primary popuprevisar float-end">
                                <li class="fa fa-gavel"></li>
                                Revisar
                            </a><br><br><br>
                        @endif
                    @else
                        <span class="alert alert-warning float-end">{{ $auditoria->cedulaanaliticadesemp[0]->fase_autorizacion}} </span><br><br>
                    @endcan
                @endif
                @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En validación')
                    @can('cedanadesempvalidacion.edit')
                        <a href="{{ route('cedanadesempvalidacion.edit',$auditoria->cedulaanaliticadesemp[0]) }}" class="btn btn-primary popuprevisar float-end">
                            <li class="fa fa-gavel"></li>
                            Validar
                        </a><br><br><br>
                    @else
                        <span class="badge badge-light-warning float-end">{{ $auditoria->cedulaanaliticadesemp[0]->fase_autorizacion}} </span><br><br>
                    @endcan
                @endif
                @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion == 'En autorización')
                    @can('cedanadesempautorizacion.edit')
                        <a href="{{ route('cedanadesempautorizacion.edit',$auditoria->cedulaanaliticadesemp[0]) }}" class="btn btn-primary popuprevisar float-end">
                            <li class="fa fa-gavel"></li>
                            Autorizar
                        </a><br><br><br>
                    @else
                        <span class="alert alert-warning float-end">{{ $auditoria->cedulaanaliticadesemp[0]->fase_autorizacion}} </span><br><br>
                    @endcan
                @endif
                @if ($auditoria->cedulaanaliticadesemp[0]->fase_autorizacion=='Autorizado')
                    <span class="alert alert-success float-end">{{ $auditoria->cedulaanaliticadesemp[0]->fase_autorizacion }} </span><br><br>
                @endif
            @else   
                {!! BootForm::open(['model' => $auditoria, 'update' => 'cedulaanaliticadesemp.update','id'=>'form']) !!}  
                {!! BootForm::hidden('cedula2',$nombre)!!}             
                <div class="row">
                    <div class="col-md-12" >
                        @if (auth()->user()->can('cedulaanaliticadesemp.update'))
                            <button type="submit" name="enviar" class="btn btn-primary btn btn-primary float-end">Enviar a revisión</button>
                        @endif
                    </div>
                </div>
                {!! BootForm::close() !!}
            @endif
                <embed src="{{asset($nombre)}}" type="application/pdf" width="100%" height="600px"/>
                <div class="table-responsive">
                    <table class="table">                        
                        <tbody>     							
                            {!! movimientosDesglose($auditoria->id, 1, $auditoria->movimientosCedulaAnaliticaDesemp) !!}                            
                        </tbody>
                    </table>
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