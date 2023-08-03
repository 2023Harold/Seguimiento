@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render($rutaCerrar) }}
@endsection
@section('content')   
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">
                        Constancia
                    </h1>
                </div>
                <div class="card-body">
                    @include('flash::message')
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <embed src="{{asset(env('MINIO_URL')).'/'.$constancia->constancia_pdf}}" type="application/pdf" width="100%" height="600px"/> --}}
                            <embed src="{{asset($constancia->constancia_pdf)}}" type="application/pdf" width="100%" height="600px"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mt-3">
                            @php
                                $nombres_constancia = explode("/", $constancia->constancia_pdf);
                                $nombres_xml = explode("/", $constancia->archivo_xml);
                            @endphp
                            <a href="data:application/pdf;base64,{{base64_encode(file_get_contents(asset($constancia->constancia_pdf)))}}"
                                download="{{end($nombres_constancia)}}" class="btn btn-primary" >Descargar PDF</a>
                            <a href="data:application/xml;base64,{{base64_encode(file_get_contents(asset($constancia->archivo_xml)))}}"
                                download="{{end($nombres_xml)}}" class="btn btn-primary" >Descargar XML</a>                            
                            <a href="{{ route($rutaCerrar) }}" class="btn btn-secondary me-2">Cancelar</a>
                        </div>
                    </div>

                </div>
            </div>
@endsection
