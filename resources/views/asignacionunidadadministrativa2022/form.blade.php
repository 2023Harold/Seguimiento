@extends('layouts.app')
@section('breadcrums')
{{ Breadcrumbs::render('asignacionunidadadministrativa2022.edit',$user,$cp_2022) }}
@endsection
@section('content')
<div class="container-fluid">
    @section('content')
    @include('flash::message') 
            <div class="row ">
                <div class="col-md-12 mt-12">  
                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title">
                                @can('user.index')
                                    <a class="color-sistema" href="{{ route('user.index') }}"><i class="fa fa-arrow-circle-o-left"></i></a>
                                @endcan
                                Asignar Unidad Administrativa 2022
                            </h1>
                        </div>                                                   
                    </div>
                </div>
            </div>            
        <div class="card">    
            <div class="card-body">               
                <div class="row">
                    <div class="col-md-3">
                        {!! BootForm::open(['model' => $user,'update'=>'asignacionunidadadministrativa2022.update','id'=>'form'] )!!}                       
                        {!! BootForm::select('cp_ua2022', 'Unidad administrativa: *', $unidades->toArray() , old('unidadadministrativa_asignada_id',$user->unidadadministrativa_asignada_id), ['data-control'=>'select2', 'class'=>'form-select', 'data-placeholder'=>'Seleccionar una opción']) !!}                                                      
                    </div>      
                    <div class="row">               
                    <div class="col-md-6">        
                        @btnSubmit('Guardar',route('asignacionunidadadministrativa2022.update'))
                        @btnCancelar('Cancelar', route('asignacionunidadadministrativa.index'))                                            
                    </div>                 
                    </div>
                </div>
                        {!! BootForm::close() !!}
                 </div>                   
        </div>
        @endsection
        