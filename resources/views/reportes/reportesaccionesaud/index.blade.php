@extends('layouts.app')
@section('breadcrums')
    {{Breadcrumbs::render('reporteauditoriaacciones.index') }}
@endsection
@section('content')
<style>
tr:hover {background-color: #CAD5E2 !important;}
</style>
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title w-100">
                    <div class="row w-100">
                        <div class="col-md-11">
                            <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                            Reportes
                        </div>
                        <div class="col-md-1">
							@can('reporteauditoriaacciones.exportar')
                            <a href="{!! route('reporteauditoriaacciones.exportar',['aud' => $request->numero_auditoria,'ent' => $request->entidad_fiscalizable]) !!}" class=" btn btn-primary">Excel</a>
							@endcan
                        </div>
                    </div>                
                </h1>                
            </div> 
            <div class="card-body">     
				<h4>Seleccione una auditoría (Cuenta Publica {{ $cp }})</h4>
                    <select class="form-control form-select form-group " data-control='select2'
                        onchange="if(this.value) window.location.href=this.value">
                        <option value="">Seleccione una opción</option>
                        @foreach($auditorias as $aud)
                            <option value="{{ route('reporteauditoriaacciones.show', $aud->id) }}">
                                {{ $aud->numero_auditoria }}
                            </option>
                        @endforeach
                    </select>
            
                <div class="pagination" style="justify-content:right !important;">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')   
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection

